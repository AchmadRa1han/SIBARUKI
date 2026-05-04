<?php

namespace App\Controllers;

use App\Models\RtlhPenerimaModel;
use App\Models\RumahRtlhModel;
use App\Models\KondisiRumahModel;
use App\Models\RefMasterModel;
use App\Models\RtlhHistoryModel;
use App\Models\BansosRtlhModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Rtlh extends BaseController
{
    protected $penerimaModel;
    protected $rumahModel;
    protected $kondisiModel;
    protected $refModel;
    protected $historyModel;
    protected $bansosModel;

    public function __construct()
    {
        $this->penerimaModel = new RtlhPenerimaModel();
        $this->rumahModel = new RumahRtlhModel();
        $this->kondisiModel = new KondisiRumahModel();
        $this->refModel = new RefMasterModel();
        $this->historyModel = new RtlhHistoryModel();
        $this->bansosModel = new BansosRtlhModel();
    }

    public function index()
    {
        $keyword = $this->request->getGet('keyword');
        $perPage = $this->request->getGet('per_page') ?? 10;
        $status = $this->request->getGet('status') ?? 'Belum Menerima';

        $query = $this->rumahModel->select('rtlh_rumah.*, ST_AsText(lokasi_koordinat) as wkt, rtlh_penerima.nama_kepala_keluarga as pemilik')
                                  ->join('rtlh_penerima', 'rtlh_penerima.nik = rtlh_rumah.nik_pemilik', 'left');

        if ($status !== 'semua') {
            $query->where('status_bantuan', $status);
        }

        if ($keyword) {
            $query->groupStart()
                  ->like('rtlh_penerima.nama_kepala_keluarga', $keyword)
                  ->orLike('rtlh_rumah.desa', $keyword)
                  ->groupEnd();
        }

        $rumah = $query->paginate($perPage, 'default');

        // Data untuk Map (Semua yang punya koordinat) - Ambil data minimal saja
        $db = \Config\Database::connect();
        $rumah_all = $db->table('rtlh_rumah')
                        ->select('id_survei, desa, ST_AsText(lokasi_koordinat) as wkt, nik_pemilik')
                        ->where('lokasi_koordinat IS NOT NULL')
                        ->where('lokasi_koordinat !=', '')
                        ->limit(500)
                        ->get()->getResultArray();

        // Ambil nama pemilik secara terpisah untuk popup agar tidak membebani join
        $niks = array_unique(array_column($rumah_all, 'nik_pemilik'));
        $pemilikMap = [];
        if (!empty($niks)) {
            $penerima = $db->table('rtlh_penerima')->select('nik, nama_kepala_keluarga')->whereIn('nik', $niks)->get()->getResultArray();
            foreach ($penerima as $p) $pemilikMap[$p['nik']] = $p['nama_kepala_keluarga'];
        }
        foreach ($rumah_all as &$r) {
            $r['pemilik'] = $pemilikMap[$r['nik_pemilik']] ?? 'Pemilik Tidak Terdata';
        }

        $data = [
            'title' => 'Data RTLH',
            'rumah' => $rumah,
            'rumah_all' => $rumah_all,
            'pager' => $this->rumahModel->pager,
            'perPage' => $perPage,
            'keyword' => $keyword,
            'status' => $status,
            'total_verifikasi' => $this->rumahModel->countAllResults(false),
        ];

        return view('rtlh/index', $data);
    }
    public function markTuntas($id)
    {
        $post = $this->request->getPost();
        $tahun = $post['tahun_bansos'] ?? date('Y');
        $program = $post['program_bansos'];
        $koordinat = $post['lokasi_realisasi'] ?? null;

        if (!$id) return redirect()->back()->with('error', 'ID Survei tidak valid.');

        $db = \Config\Database::connect();
        $rumah = $db->table('rtlh_rumah')
                    ->select('rtlh_rumah.*, ST_AsText(lokasi_koordinat) as lokasi_koordinat_text')
                    ->where('id_survei', $id)
                    ->get()->getRowArray();

        if (!$rumah) return redirect()->back()->with('error', 'Data rumah tidak ditemukan.');

        $kondisi = $this->kondisiModel->where('id_survei', $id)->first();
        $penerima = $this->penerimaModel->where('nik', $rumah['nik_pemilik'])->first();

        $snapshotSebelum = [
            'rumah' => $rumah,
            'kondisi' => $kondisi,
            'penerima' => $penerima
        ];

        $db->transStart();
        try {
            $now = date('Y-m-d H:i:s');
            
            // 1. Update Tabel Utama
            $db->table('rtlh_rumah')->where('id_survei', $id)->update([
                'status_bantuan' => 'Sudah Menerima',
                'tahun_bansos' => $tahun,
                'bantuan_perumahan' => $program ?: 'Bansos RTLH',
                'updated_at' => $now
            ]);

            // 2. Persiapkan Data Realisasi
            $dataBansos = [
                'id_survei' => $id,
                'nik' => $rumah['nik_pemilik'],
                'nama_penerima' => $penerima['nama_kepala_keluarga'] ?? 'Unknown',
                'desa' => $rumah['desa'],
                'tahun_anggaran' => $tahun,
                'sumber_dana' => $program ?: 'Bansos RTLH',
                'keterangan' => $post['keterangan_realisasi'] ?? 'Ditandai tuntas dari modul RTLH',
                'foto_before' => $rumah['foto_depan'] ?? null, // Ambil foto depan lama sebagai bukti awal
                'created_at' => $now,
                'updated_at' => $now
            ];

            // Handle Upload Foto After (3 Posisi)
            $uploadPath = FCPATH . 'uploads/rtlh/';
            if (!is_dir($uploadPath)) mkdir($uploadPath, 0777, true);

            foreach(['foto_setelah_depan', 'foto_setelah_samping', 'foto_setelah_dalam'] as $field) {
                $img = $this->request->getFile($field);
                if ($img && $img->isValid() && !$img->hasMoved()) {
                    $newName = 'AFTER_' . $img->getRandomName();
                    $img->move($uploadPath, $newName);
                    $dataBansos[$field] = $newName;
                    
                    // Fallback untuk foto_after (kolom tunggal) ambil yang depan
                    if ($field === 'foto_setelah_depan') {
                        $dataBansos['foto_after'] = $newName;
                    }
                }
            }

            // Simpan Realisasi
            $this->bansosModel->insert($dataBansos);
            $bansosId = $this->bansosModel->getInsertID();

            // Simpan Koordinat Realisasi jika ada
            if (!empty($koordinat) && preg_match('/POINT\s*\(\s*-?\d+\.?\d*\s+-?\d+\.?\d*\s*\)/i', $koordinat)) {
                $db->table('rtlh_bansos')->where('id', $bansosId)
                   ->set('lokasi_realisasi', "ST_GeomFromText('{$koordinat}')", false)
                   ->update();
            }

            // 3. Simpan History Perubahan
            $db->table('rtlh_history_perubahan')->insert([
                'id_survei' => $id,
                'nik' => $rumah['nik_pemilik'],
                'nama_penerima' => $penerima['nama_kepala_keluarga'] ?? 'Unknown',
                'sumber_bantuan' => $program ?: 'Bansos RTLH',
                'tahun_anggaran' => $tahun,
                'data_sebelum' => json_encode($snapshotSebelum),
                'keterangan' => 'Transformasi RTLH ke RLH (Realisasi)',
                'created_at' => $now,
                'updated_at' => $now
            ]);

            $db->transComplete();
            if ($db->transStatus() === false) throw new \Exception('Database Error');

            $this->logActivity('Tuntas Bansos', 'RTLH', "Realisasi bantuan ID $id tahun $tahun berhasil dicatat");
            return redirect()->to('/rtlh?status=Sudah Menerima')->with('success', "Realisasi Program berhasil dicatat. Foto Before-After tersedia di halaman detail.");
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Gagal memproses realisasi: ' . $e->getMessage());
        }
    }

    public function importCsv()
    {
        if (!has_permission('create_rtlh')) return redirect()->back()->with('error', 'Izin ditolak.');
        
        $file = $this->request->getFile('csv_file');
        if (!$file || !$file->isValid()) return redirect()->back()->with('error', 'File tidak valid.');

        $handle = fopen($file->getTempName(), 'r');
        $firstFewLines = "";
        for($i=0; $i<10; $i++) {
            $line = fgets($handle);
            if ($line) $firstFewLines .= $line;
        }
        $delimiter = (substr_count($firstFewLines, ';') > substr_count($firstFewLines, ',')) ? ';' : ',';
        rewind($handle);

        // Alias Map Masif untuk seluruh kolom (Penerima, Rumah, Kondisi)
        $aliasMap = [
            'nik'                  => ['*nik', 'nik'],
            'nama_kepala_keluarga' => ['*nama kepala rumah tangga', 'nama kepala rumah tangga', 'nama'],
            'no_kk'                => ['no.kk', 'no kk', 'nomor kk'],
            'desa'                 => ['*desa', 'desa'],
            'alamat_detail'        => ['*alamat', 'alamat'],
            'jenis_kawasan'        => ['jenis kawasan', 'kawasan'],
            'fungsi_ruang'         => ['fungsi ruang'],
            'luas_rumah_m2'        => ['*luas rumah (m2)', 'luas rumah (m2)'],
            'luas_lahan_m2'        => ['luah lahan (m2)', 'luas lahan (m2)'],
            'pendidikan_id'        => ['*pendidikan', 'pendidikan'],
            'pekerjaan_id'         => ['*pekerjaan', 'pekerjaan'],
            'penghasilan_per_bulan'=> ['*penghasilan perbulan', 'penghasilan perbulan'],
            'jenis_kelamin'        => ['*jenis kelamin', 'jenis kelamin'],
            'tempat_tanggal_lahir' => ['tempat tanggal lahir', 'ttl'],
            'kepemilikan_rumah'    => ['*kepemilikan rumah', 'kepemilikan rumah'],
            'aset_rumah_di_lokasi_lain' => ['*aset rumah di lokasi lain', 'aset rumah di lokasi lain'],
            'kepemilikan_tanah'    => ['*kepemilikan tanah', 'kepemilikan tanah'],
            'bantuan_perumahan'    => ['*bantuan perumahan', 'bantuan perumahan'],
            'jumlah_anggota_keluarga' => ['*jumlah keluarga (kk)', 'jumlah keluarga (kk)'],
            'sumber_penerangan'    => ['*sumber penerangan', 'sumber penerangan'],
            'st_pondasi'           => ['*pondasi', 'pondasi', '*pondasi'],
            'st_kolom'             => ['*kondisi kolom', 'kondisi kolom'],
            'st_balok'             => ['kondisi balok', 'balok'],
            'st_sloof'             => ['kondisi sloof', 'sloof'],
            'st_rangka_atap'       => ['*kondisi rangka atap', 'kondisi rangka atap'],
            'st_plafon'            => ['kondisi plafon', 'plafon'],
            'st_jendela'           => ['*jendela', 'jendela'],
            'st_ventilasi'         => ['*ventilasi', 'ventilasi'],
            'mat_lantai'           => ['*material lantai terluas', 'material lantai terluas'],
            'st_lantai'            => ['*kondisi lantai', 'kondisi lantai'],
            'mat_dinding'          => ['*material dinding terluas', 'material dinding terluas'],
            'st_dinding'           => ['*kondisi dinding', 'kondisi dinding'],
            'mat_atap'             => ['*material atap terluas', 'material atap terluas'],
            'st_atap'              => ['*kondisi atap', 'kondisi atap'],
            'sumber_air_minum'     => ['*sumber air minum', 'sumber air minum'],
            'jarak_sam_ke_tpa_tinja'=> ['*jarak sam ke tpa tinja', 'jarak sam ke tpa tinja'],
            'kamar_mandi_dan_jamban'=> ['*kamar mandi dan jamban', 'kamar mandi dan jamban'],
            'jenis_jamban_kloset'  => ['jenis jamban/ kloset', 'jenis jamban'],
            'jenis_tpa_tinja'      => ['jenis tpa tinja'],
        ];

        $db = \Config\Database::connect();
        
        // Reset Auto Increment jika tabel kosong
        if ($db->table('rtlh_rumah')->countAllResults() === 0) {
            $db->query("ALTER TABLE rtlh_rumah AUTO_INCREMENT = 1");
            $db->query("ALTER TABLE rtlh_kondisi_rumah AUTO_INCREMENT = 1");
        }

        $allRefs = $this->refModel->findAll();
        $refMap = [];
        foreach ($allRefs as $r) {
            $refMap[$r['kategori']][strtoupper(trim($r['nama_pilihan']))] = $r['id'];
        }

        $allDesa = $db->table('kode_desa')->select('desa_id, desa_nama')->get()->getResultArray();
        $desaLookup = [];
        foreach ($allDesa as $d) {
            $desaLookup[strtoupper(trim($d['desa_nama']))] = $d['desa_id'];
        }

        $headerPos = [];
        $foundHeader = false;

        while (($line = fgetcsv($handle, 5000, $delimiter)) !== FALSE) {
            $lineClean = array_map(function($v) { return strtolower(trim($v)); }, $line);
            if (in_array('*nik', $lineClean) || in_array('nik', $lineClean)) {
                $countPenerangan = 0;
                foreach ($lineClean as $index => $colName) {
                    foreach ($aliasMap as $field => $aliases) {
                        if ($colName == $field || in_array($colName, $aliases)) {
                            if ($colName == '*sumber penerangan' || $colName == 'sumber penerangan') {
                                if ($countPenerangan == 0) {
                                    $headerPos['sumber_penerangan'] = $index;
                                    $countPenerangan++;
                                } else {
                                    $headerPos['sumber_penerangan_detail'] = $index;
                                }
                            } else {
                                $headerPos[$field] = $index;
                            }
                            break;
                        }
                    }
                }
                $foundHeader = true;
                break;
            }
        }

        if (!$foundHeader || !isset($headerPos['nik'])) {
            fclose($handle);
            return redirect()->back()->with('error', 'Format Header CSV tidak dikenali. Pastikan kolom *NIK tersedia.');
        }

        $count = 0;
        $db->transStart();
        try {
            while (($row = fgetcsv($handle, 5000, $delimiter)) !== FALSE) {
                $nikRaw = trim($row[$headerPos['nik']] ?? '');
                $nik = preg_replace('/[^0-9]/', '', $nikRaw);
                
                if (empty($nik) || strlen($nik) < 10) continue;

                $getVal = function($field) use ($row, $headerPos) {
                    return isset($headerPos[$field]) ? trim($row[$headerPos[$field]] ?? '') : null;
                };

                $findId = function($cat, $text) use ($refMap) {
                    if (empty($text)) return null;
                    $text = strtoupper(trim($text));
                    if (isset($refMap[$cat][$text])) return $refMap[$cat][$text];
                    foreach ($refMap[$cat] ?? [] as $nama => $id) {
                        if (stripos($nama, $text) !== false || stripos($text, $nama) !== false) return $id;
                    }
                    return null;
                };

                $ttl = strtoupper(trim($getVal('tempat_tanggal_lahir') ?? ''));
                $tempat = null; $tanggal = null;
                
                if ($ttl) {
                    $monthsIndo = [
                        'JANUARI' => '01', 'FEBRUARI' => '02', 'MARET' => '03', 'APRIL' => '04',
                        'MEI' => '05', 'JUNI' => '06', 'JULI' => '07', 'AGUSTUS' => '08',
                        'SEPTEMBER' => '09', 'OKTOBER' => '10', 'NOVEMBER' => '11', 'DESEMBER' => '12'
                    ];

                    // 1. Cari pola tanggal (bisa ada spasi antar pemisah)
                    if (preg_match('/(\d{1,2}\s*[\/\-\.]\s*\d{1,2}\s*[\/\-\.]\s*\d{4})/', $ttl, $matches)) {
                        $tglRaw = $matches[1];
                        $tempat = trim(str_ireplace([$tglRaw, ','], '', $ttl));
                        
                        // Bersihkan spasi di dalam tanggal (misal: "31 - 12 - 1973" -> "31-12-1973")
                        $tglClean = str_replace(' ', '', $tglRaw);
                        $tglClean = str_replace(['/', '.'], '-', $tglClean);
                        
                        $parts = explode('-', $tglClean);
                        if (count($parts) === 3) {
                            $p1 = (int)$parts[0];
                            $p2 = (int)$parts[1];
                            $p3 = (int)$parts[2];

                            // Logika Penentuan d-m-Y vs m-d-Y
                            if ($p1 > 12 && $p1 <= 31 && $p2 <= 12) {
                                // Pasti d-m-Y
                                $tanggal = sprintf('%04d-%02d-%02d', $p3, $p2, $p1);
                            } else if ($p2 > 12 && $p2 <= 31 && $p1 <= 12) {
                                // Pasti m-d-Y
                                $tanggal = sprintf('%04d-%02d-%02d', $p3, $p1, $p2);
                            } else if ($p1 <= 12 && $p2 <= 12) {
                                // Ambigu, asumsikan d-m-Y
                                $tanggal = sprintf('%04d-%02d-%02d', $p3, $p2, $p1);
                            }
                        }
                    } 
                    // 2. Coba pola dengan nama bulan (Sinjai, 01 Juli 1995)
                    else if (preg_match('/(\d{1,2})\s+([A-Z]+)\s+(\d{4})/', $ttl, $matches)) {
                        $day = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
                        $monthName = $matches[2];
                        $year = $matches[3];
                        
                        $month = $monthsIndo[$monthName] ?? null;
                        if ($month) {
                            $tanggal = "$year-$month-$day";
                            $tempat = trim(str_replace([$matches[0], ','], '', $ttl));
                        }
                    }

                    // Fallback
                    if (!$tanggal) $tempat = $ttl;
                }

                // 1. Simpan/Update Penerima
                $dataPenerima = [
                    'nik' => $nik,
                    'nama_kepala_keluarga' => $getVal('nama_kepala_keluarga'),
                    'no_kk' => preg_replace('/[^0-9]/', '', $getVal('no_kk') ?? ''),
                    'tempat_lahir' => $tempat,
                    'tanggal_lahir' => $tanggal,
                    'jenis_kelamin' => (stripos($getVal('jenis_kelamin') ?? '', 'PEREMPUAN') !== false) ? 'P' : 'L',
                    'pendidikan_id' => $findId('PENDIDIKAN', $getVal('pendidikan_id')),
                    'pekerjaan_id' => $findId('PEKERJAAN', $getVal('pekerjaan_id')),
                    'penghasilan_per_bulan' => $findId('PENGHASILAN', $getVal('penghasilan_per_bulan')) ?: $getVal('penghasilan_per_bulan'),
                    'jumlah_anggota_keluarga' => (int) preg_replace('/[^0-9]/', '', $getVal('jumlah_anggota_keluarga') ?? '0'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                if ($this->penerimaModel->find($nik)) $this->penerimaModel->update($nik, $dataPenerima);
                else {
                    $dataPenerima['created_at'] = date('Y-m-d H:i:s');
                    $this->penerimaModel->insert($dataPenerima);
                }

                // 2. Simpan/Update Rumah
                $desaName = strtoupper(trim($getVal('desa') ?? ''));
                $dataRumah = [
                    'nik_pemilik'   => $nik,
                    'desa'          => $getVal('desa'),
                    'desa_id'       => $desaLookup[$desaName] ?? null,
                    'alamat_detail' => $getVal('alamat_detail'),
                    'jenis_kawasan' => $getVal('jenis_kawasan'),
                    'fungsi_ruang'  => $getVal('fungsi_ruang'),
                    'kepemilikan_rumah' => $getVal('kepemilikan_rumah'),
                    'aset_rumah_di_lokasi_lain' => $getVal('aset_rumah_di_lokasi_lain'),
                    'kepemilikan_tanah' => $getVal('kepemilikan_tanah'),
                    'sumber_penerangan' => $getVal('sumber_penerangan'),
                    'sumber_penerangan_detail' => $getVal('sumber_penerangan_detail'),
                    'bantuan_perumahan' => $getVal('bantuan_perumahan'),
                    'luas_rumah_m2' => preg_replace('/[^0-9.]/', '', $getVal('luas_rumah_m2') ?? '0'),
                    'luas_lahan_m2' => preg_replace('/[^0-9.]/', '', $getVal('luas_lahan_m2') ?? '0'),
                    'sumber_air_minum' => $getVal('sumber_air_minum'),
                    'jarak_sam_ke_tpa_tinja' => $getVal('jarak_sam_ke_tpa_tinja'),
                    'kamar_mandi_dan_jamban' => $getVal('kamar_mandi_dan_jamban'),
                    'jenis_jamban_kloset' => $getVal('jenis_jamban_kloset'),
                    'jenis_tpa_tinja' => $getVal('jenis_tpa_tinja'),
                    'status_bantuan' => 'Belum Menerima',
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                
                $existingRumah = $this->rumahModel->where('nik_pemilik', $nik)->first();
                if ($existingRumah) {
                    $this->rumahModel->update($existingRumah['id_survei'], $dataRumah);
                    $surveiId = $existingRumah['id_survei'];
                } else {
                    $dataRumah['created_at'] = date('Y-m-d H:i:s');
                    $this->rumahModel->insert($dataRumah);
                    $surveiId = $this->rumahModel->getInsertID();
                }

                // 3. Simpan/Update Kondisi
                $dataKondisi = [
                    'id_survei'  => $surveiId,
                    'st_pondasi' => $findId('KONDISI', $getVal('st_pondasi')),
                    'st_kolom'   => $findId('KONDISI', $getVal('st_kolom')),
                    'st_balok'   => $findId('KONDISI', $getVal('st_balok')),
                    'st_sloof'   => $findId('KONDISI', $getVal('st_sloof')),
                    'st_rangka_atap' => $findId('KONDISI', $getVal('st_rangka_atap')),
                    'st_plafon'  => $findId('KONDISI', $getVal('st_plafon')),
                    'st_jendela' => $findId('KONDISI', $getVal('st_jendela')),
                    'st_ventilasi' => $findId('KONDISI', $getVal('st_ventilasi')),
                    'mat_lantai' => $findId('MATERIAL_LANTAI', $getVal('mat_lantai')),
                    'st_lantai'  => $findId('KONDISI', $getVal('st_lantai')),
                    'mat_dinding' => $findId('MATERIAL_DINDING', $getVal('mat_dinding')),
                    'st_dinding' => $findId('KONDISI', $getVal('st_dinding')),
                    'mat_atap'   => $findId('MATERIAL_ATAP', $getVal('mat_atap')),
                    'st_atap'    => $findId('KONDISI', $getVal('st_atap')),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                if ($this->kondisiModel->find($surveiId)) $this->kondisiModel->update($surveiId, $dataKondisi);
                else {
                    $dataKondisi['created_at'] = date('Y-m-d H:i:s');
                    $this->kondisiModel->insert($dataKondisi);
                }

                $count++;
            }

            $db->transComplete();
            fclose($handle);

            if ($count === 0) return redirect()->back()->with('error', 'Tidak ada data valid yang diimpor. Pastikan kolom NIK benar.');
            
            $this->logActivity('Import', 'RTLH', "Berhasil mengimpor $count data RTLH via CSV");
            return redirect()->to('/rtlh')->with('success', "Import Selesai: $count data RTLH berhasil diproses.");
        } catch (\Exception $e) {
            if (isset($handle)) fclose($handle);
            $db->transRollback();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function detail($id)
    {
        if (!has_permission('view_rtlh_detail')) return redirect()->to('/rtlh')->with('message', 'Akses ditolak.');
        $rumah = $this->rumahModel->select('rtlh_rumah.*, ST_AsText(lokasi_koordinat) as wkt')->find($id);
        if (!$rumah) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        
        $db = \Config\Database::connect();
        $kondisi = $this->kondisiModel->where('id_survei', $id)->first();
        $penerima = $this->penerimaModel->where('nik', $rumah['nik_pemilik'])->first();
        $realisasi = $db->table('rtlh_bansos')
                        ->select('*, ST_AsText(lokasi_realisasi) as wkt_realisasi')
                        ->where('id_survei', $id)
                        ->orderBy('id', 'DESC')
                        ->get()->getRowArray();

        return view('rtlh/detail', [
            'title' => 'Detail RTLH',
            'rumah' => $rumah,
            'kondisi' => $kondisi,
            'penerima' => $penerima,
            'realisasi' => $realisasi,
            'ref' => $this->refModel->getAllMapped()
        ]);
    }

    public function rekapDesa()
    {
        if (session()->get('role_id') != 1) {
            return redirect()->to('/dashboard')->with('error', 'Hanya Admin yang dapat melihat data rekapan per desa.');
        }

        $db = \Config\Database::connect();
        $builder = $db->table('rtlh_rumah');
        $builder->select("
            desa, 
            desa_id, 
            COUNT(*) as total_semua,
            COUNT(CASE WHEN status_bantuan = 'Belum Menerima' THEN 1 END) as total_rtlh,
            COUNT(CASE WHEN status_bantuan = 'Sudah Menerima' THEN 1 END) as total_rlh
        ");
        $builder->groupBy('desa, desa_id');
        $builder->orderBy('desa', 'ASC');
        $rekap = $builder->get()->getResultArray();

        return view('rtlh/rekap_desa', [
            'title' => 'Rekapitulasi RTLH per Desa',
            'rekap' => $rekap
        ]);
    }

    public function historyTransformasi()
    {
        $data = [
            'title' => 'Histori Transformasi RTLH',
            'history' => $this->historyModel->orderBy('created_at', 'DESC')->paginate(10, 'default'),
            'pager' => $this->historyModel->pager,
            'ref' => $this->refModel->getAllMapped()
        ];
        return view('rtlh/history_transformasi', $data);
    }

    public function exportExcel()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('rtlh_rumah');
        $builder->select('rtlh_rumah.*, ST_AsText(lokasi_koordinat) as wkt_text, 
                         rtlh_penerima.*, 
                         rtlh_kondisi_rumah.*');
        $builder->join('rtlh_penerima', 'rtlh_penerima.nik = rtlh_rumah.nik_pemilik', 'left');
        $builder->join('rtlh_kondisi_rumah', 'rtlh_kondisi_rumah.id_survei = rtlh_rumah.id_survei', 'left');
        $data = $builder->get()->getResultArray();

        $refMap = $this->refModel->getAllMapped();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $headers = [
            'ID SURVEI', 'NAMA KEPALA KELUARGA', 'NIK', 'NO KK', 'TEMPAT LAHIR', 'TGL LAHIR', 'JK', 
            'PENDIDIKAN', 'PEKERJAAN', 'PENGHASILAN', 'JML ANGGOTA KELUARGA',
            'ALAMAT', 'DESA', 'JENIS KAWASAN', 'FUNGSI RUANG', 'KEPEMILIKAN RUMAH', 'ASET DI LOKASI LAIN', 
            'KEPEMILIKAN TANAH', 'SUMBER PENERANGAN', 'DETAIL PENERANGAN', 'BANTUAN PERUMAHAN', 
            'LUAS RUMAH', 'LUAS LAHAN', 'SUMBER AIR MINUM', 'JARAK SAM KE TPA', 
            'KM DAN JAMBAN', 'JENIS KLOSET', 'JENIS TPA TINJA',
            'ST PONDASI', 'ST KOLOM', 'ST BALOK', 'ST SLOOF', 'ST RANGKA ATAP', 'ST PLAFON', 
            'ST JENDELA', 'ST VENTILASI', 'MAT LANTAI', 'ST LANTAI', 'MAT DINDING', 'ST DINDING', 
            'MAT ATAP', 'ST ATAP', 'STATUS BANTUAN', 'TAHUN BANSOS', 'BACKLOG', 'DESIL', 'WKT'
        ];
        
        $colIndex = 1;
        foreach ($headers as $header) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
            $sheet->setCellValue($colLetter . '1', $header);
            $colIndex++;
        }

        $rowNum = 2;
        foreach ($data as $row) {
            $sheet->setCellValue('A' . $rowNum, $row['id_survei']);
            $sheet->setCellValue('B' . $rowNum, $row['nama_kepala_keluarga']);
            $sheet->setCellValue('C' . $rowNum, $row['nik_pemilik'] . ' '); 
            $sheet->setCellValue('D' . $rowNum, $row['no_kk'] . ' ');
            $sheet->setCellValue('E' . $rowNum, $row['tempat_lahir']);
            $sheet->setCellValue('F' . $rowNum, $row['tanggal_lahir']);
            $sheet->setCellValue('G' . $rowNum, $row['jenis_kelamin']);
            $sheet->setCellValue('H' . $rowNum, $refMap[$row['pendidikan_id']] ?? '-');
            $sheet->setCellValue('I' . $rowNum, $refMap[$row['pekerjaan_id']] ?? '-');
            $sheet->setCellValue('J' . $rowNum, $row['penghasilan_per_bulan']);
            $sheet->setCellValue('K' . $rowNum, $row['jumlah_anggota_keluarga']);
            $sheet->setCellValue('L' . $rowNum, $row['alamat_detail']);
            $sheet->setCellValue('M' . $rowNum, $row['desa']);
            $sheet->setCellValue('N' . $rowNum, $row['jenis_kawasan']);
            $sheet->setCellValue('O' . $rowNum, $row['fungsi_ruang']);
            $sheet->setCellValue('P' . $rowNum, $row['kepemilikan_rumah']);
            $sheet->setCellValue('Q' . $rowNum, $row['aset_rumah_di_lokasi_lain']);
            $sheet->setCellValue('R' . $rowNum, $row['kepemilikan_tanah']);
            $sheet->setCellValue('S' . $rowNum, $row['sumber_penerangan']);
            $sheet->setCellValue('T' . $rowNum, $row['sumber_penerangan_detail']);
            $sheet->setCellValue('U' . $rowNum, $row['bantuan_perumahan']);
            $sheet->setCellValue('V' . $rowNum, $row['luas_rumah_m2']);
            $sheet->setCellValue('W' . $rowNum, $row['luas_lahan_m2']);
            $sheet->setCellValue('X' . $rowNum, $row['sumber_air_minum']);
            $sheet->setCellValue('Y' . $rowNum, $row['jarak_sam_ke_tpa_tinja']);
            $sheet->setCellValue('Z' . $rowNum, $row['kamar_mandi_dan_jamban']);
            $sheet->setCellValue('AA' . $rowNum, $row['jenis_jamban_kloset']);
            $sheet->setCellValue('AB' . $rowNum, $row['jenis_tpa_tinja']);
            
            $sheet->setCellValue('AC' . $rowNum, $refMap[$row['st_pondasi']] ?? '-');
            $sheet->setCellValue('AD' . $rowNum, $refMap[$row['st_kolom']] ?? '-');
            $sheet->setCellValue('AE' . $rowNum, $refMap[$row['st_balok']] ?? '-');
            $sheet->setCellValue('AF' . $rowNum, $refMap[$row['st_sloof']] ?? '-');
            $sheet->setCellValue('AG' . $rowNum, $refMap[$row['st_rangka_atap']] ?? '-');
            $sheet->setCellValue('AH' . $rowNum, $refMap[$row['st_plafon']] ?? '-');
            $sheet->setCellValue('AI' . $rowNum, $refMap[$row['st_jendela']] ?? '-');
            $sheet->setCellValue('AJ' . $rowNum, $refMap[$row['st_ventilasi']] ?? '-');
            $sheet->setCellValue('AK' . $rowNum, $refMap[$row['mat_lantai']] ?? '-');
            $sheet->setCellValue('AL' . $rowNum, $refMap[$row['st_lantai']] ?? '-');
            $sheet->setCellValue('AM' . $rowNum, $refMap[$row['mat_dinding']] ?? '-');
            $sheet->setCellValue('AN' . $rowNum, $refMap[$row['st_dinding']] ?? '-');
            $sheet->setCellValue('AO' . $rowNum, $refMap[$row['mat_atap']] ?? '-');
            $sheet->setCellValue('AP' . $rowNum, $refMap[$row['st_atap']] ?? '-');
            
            $sheet->setCellValue('AQ' . $rowNum, $row['status_bantuan']);
            $sheet->setCellValue('AR' . $rowNum, $row['tahun_bansos']);
            $sheet->setCellValue('AS' . $rowNum, $row['desil_nasional']);
            $sheet->setCellValue('AU' . $rowNum, $row['wkt_text']);
            
            $rowNum++;
        }

        $sheet->getStyle('A1:AV1')->getFont()->setBold(true);
        foreach (range('A', 'Z') as $col) $sheet->getColumnDimension($col)->setAutoSize(true);
        foreach (['AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV'] as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // CATAT LOG SEBELUM EXIT
        $this->logActivity('Export Excel', 'RTLH', "Mengekspor " . count($data) . " data RTLH Lengkap");

        $filename = 'Export_Lengkap_RTLH_' . date('YmdHis') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function create()
    {
        $db = \Config\Database::connect();
        $master = [];
        foreach ($this->refModel->findAll() as $ref) $master[$ref['kategori']][] = $ref;
        
        $allDesa = $db->table('kode_desa')->orderBy('desa_nama', 'ASC')->get()->getResultArray();
        $desaList = array_map(function($d) {
            return ['desa' => $d['desa_nama'], 'desa_id' => $d['desa_id']];
        }, $allDesa);

        return view('rtlh/create', [
            'title' => 'Tambah RTLH',
            'master' => $master,
            'desa_list' => $desaList
        ]);
    }

    public function store()
    {
        $db = \Config\Database::connect();
        $post = $this->request->getPost();
        $nik = preg_replace('/[^0-9]/', '', $post['nik'] ?? '');

        if (empty($nik)) return redirect()->back()->with('error', 'NIK wajib diisi.')->withInput();
        if ($this->penerimaModel->find($nik)) return redirect()->back()->with('error', 'NIK sudah terdaftar dalam sistem.')->withInput();

        $db->transStart();
        try {
            // 1. Simpan Penerima
            $this->penerimaModel->insert([
                'nik' => $nik,
                'no_kk' => preg_replace('/[^0-9]/', '', $post['no_kk'] ?? ''),
                'nama_kepala_keluarga' => $post['nama_kepala_keluarga'] ?? null,
                'tempat_lahir' => $post['tempat_lahir'] ?? null,
                'tanggal_lahir' => $post['tanggal_lahir'] ?: null,
                'jenis_kelamin' => $post['jenis_kelamin'] ?? 'L',
                'pendidikan_id' => $this->resolveMasterId('pendidikan_id', $post, 'PENDIDIKAN'),
                'pekerjaan_id' => $this->resolveMasterId('pekerjaan_id', $post, 'PEKERJAAN'),
                'penghasilan_per_bulan' => $this->resolveMasterId('penghasilan_per_bulan', $post, 'PENGHASILAN'),
                'jumlah_anggota_keluarga' => $post['jumlah_anggota_keluarga'] ?? 0
            ]);

            // 2. Simpan Rumah
            $dataRumah = [
                'nik_pemilik' => $nik,
                'desa' => $post['desa'] ?? null,
                'desa_id' => $post['desa_id'] ?? null,
                'alamat_detail' => $post['alamat_detail'] ?? null,
                'jenis_kawasan' => $this->resolveMasterId('jenis_kawasan', $post, 'JENIS_KAWASAN'),
                'luas_rumah_m2' => $post['luas_rumah_m2'] ?? 0,
                'luas_lahan_m2' => $post['luas_lahan_m2'] ?? 0,
                'kepemilikan_rumah' => $this->resolveMasterId('kepemilikan_rumah', $post, 'KEPEMILIKAN_RUMAH'),
                'kepemilikan_tanah' => $this->resolveMasterId('kepemilikan_tanah', $post, 'KEPEMILIKAN_TANAH'),
                'sumber_penerangan' => $this->resolveMasterId('sumber_penerangan', $post, 'SUMBER_PENERANGAN'),
                'sumber_air_minum' => $this->resolveMasterId('sumber_air_minum', $post, 'SUMBER_AIR_MINUM'),
                'jenis_jamban_kloset' => $this->resolveMasterId('jenis_jamban_kloset', $post, 'JENIS_JAMBAN'),
                'status_bantuan' => 'Belum Menerima',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Handle Upload Foto
            $uploadPath = FCPATH . 'uploads/rtlh/';
            if (!is_dir($uploadPath)) mkdir($uploadPath, 0777, true);

            foreach(['foto_depan', 'foto_samping', 'foto_belakang', 'foto_dalam'] as $field) {
                $img = $this->request->getFile($field);
                if ($img && $img->isValid() && !$img->hasMoved()) {
                    $newName = $img->getRandomName();
                    $img->move($uploadPath, $newName);
                    $dataRumah[$field] = $newName;
                }
            }

            $this->rumahModel->set($dataRumah);
            if (!empty($post['lokasi_koordinat']) && preg_match('/POINT\s*\(\s*-?\d+\.?\d*\s+-?\d+\.?\d*\s*\)/i', $post['lokasi_koordinat'])) {
                $this->rumahModel->set('lokasi_koordinat', "ST_GeomFromText('{$post['lokasi_koordinat']}')", false);
            }
            $this->rumahModel->insert();
            $surveiId = $this->rumahModel->getInsertID();

            // 3. Simpan Kondisi Fisik
            $this->kondisiModel->insert([
                'id_survei' => $surveiId,
                'st_pondasi' => $this->resolveMasterId('st_pondasi', $post, 'KONDISI'),
                'st_kolom' => $this->resolveMasterId('st_kolom', $post, 'KONDISI'),
                'st_balok' => $this->resolveMasterId('st_balok', $post, 'KONDISI'),
                'st_sloof' => $this->resolveMasterId('st_sloof', $post, 'KONDISI'),
                'st_rangka_atap' => $this->resolveMasterId('st_rangka_atap', $post, 'KONDISI'),
                'st_plafon' => $this->resolveMasterId('st_plafon', $post, 'KONDISI'),
                'st_jendela' => $this->resolveMasterId('st_jendela', $post, 'KONDISI'),
                'st_ventilasi' => $this->resolveMasterId('st_ventilasi', $post, 'KONDISI'),
                'mat_atap' => $this->resolveMasterId('mat_atap', $post, 'MATERIAL_ATAP'),
                'st_atap' => $this->resolveMasterId('st_atap', $post, 'KONDISI'),
                'mat_dinding' => $this->resolveMasterId('mat_dinding', $post, 'MATERIAL_DINDING'),
                'st_dinding' => $this->resolveMasterId('st_dinding', $post, 'KONDISI'),
                'mat_lantai' => $this->resolveMasterId('mat_lantai', $post, 'MATERIAL_LANTAI'),
                'st_lantai' => $this->resolveMasterId('st_lantai', $post, 'KONDISI'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $db->transComplete();
            $this->logActivity('Tambah', 'RTLH', "Menambah data RTLH baru NIK: $nik");
            return redirect()->to('/rtlh')->with('success', 'Data RTLH berhasil ditambahkan.');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $db = \Config\Database::connect();
        $rumah = $this->rumahModel->find($id);
        if (!$rumah) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        $master = [];
        foreach ($this->refModel->findAll() as $ref) $master[$ref['kategori']][] = $ref;

        $allDesa = $db->table('kode_desa')->orderBy('desa_nama', 'ASC')->get()->getResultArray();
        $desaList = array_map(function($d) {
            return ['desa' => $d['desa_nama'], 'desa_id' => $d['desa_id']];
        }, $allDesa);

        return view('rtlh/edit', [
            'title' => 'Edit RTLH',
            'rumah' => $rumah,
            'penerima' => $this->penerimaModel->where('nik', $rumah['nik_pemilik'])->first(),
            'kondisi' => $this->kondisiModel->where('id_survei', $id)->first(),
            'master' => $master,
            'desa_list' => $desaList
        ]);
    }

    public function update($id)
    {
        $db = \Config\Database::connect();
        $rumahLama = $this->rumahModel->find($id);
        if (!$rumahLama) return redirect()->back()->with('error', 'Data tidak ditemukan.');

        $post = $this->request->getPost();
        $nik = $rumahLama['nik_pemilik'];

        $penerima = $this->penerimaModel->where('nik', $nik)->first();
        $kondisi = $this->kondisiModel->where('id_survei', $id)->first();

        try {
            $db->transException(true)->transStart();

            // 1. Update Penerima
            $dataPenerima = [
                'nama_kepala_keluarga' => $post['nama_kepala_keluarga'] ?? null,
                'no_kk' => preg_replace('/[^0-9]/', '', $post['no_kk'] ?? ''),
                'tempat_lahir' => $post['tempat_lahir'] ?? null,
                'tanggal_lahir' => $post['tanggal_lahir'] ?? null,
                'jenis_kelamin' => $post['jenis_kelamin'] ?? null,
                'jumlah_anggota_keluarga' => $post['jumlah_anggota_keluarga'] ?? null,
                'pendidikan_id' => $this->resolveMasterId('pendidikan_id', $post, 'PENDIDIKAN', $penerima['pendidikan_id'] ?? null),
                'pekerjaan_id' => $this->resolveMasterId('pekerjaan_id', $post, 'PEKERJAAN', $penerima['pekerjaan_id'] ?? null),
                'penghasilan_per_bulan' => $this->resolveMasterId('penghasilan_per_bulan', $post, 'PENGHASILAN', $penerima['penghasilan_per_bulan'] ?? null)
            ];
            $this->penerimaModel->update($nik, $dataPenerima);

            // 2. Update Rumah
            $dataRumah = [
                'alamat_detail' => $post['alamat_detail'] ?? null,
                'desa' => $post['desa'] ?? null,
                'desa_id' => $post['desa_id'] ?? null,
                'jenis_kawasan' => $this->resolveMasterId('jenis_kawasan', $post, 'JENIS_KAWASAN', $rumahLama['jenis_kawasan']),
                'luas_rumah_m2' => $post['luas_rumah_m2'] ?? null,
                'luas_lahan_m2' => $post['luas_lahan_m2'] ?? null,
                'fungsi_ruang' => $post['fungsi_ruang'] ?? null,
                'kepemilikan_rumah' => $this->resolveMasterId('kepemilikan_rumah', $post, 'KEPEMILIKAN_RUMAH', $rumahLama['kepemilikan_rumah']),
                'kepemilikan_tanah' => $this->resolveMasterId('kepemilikan_tanah', $post, 'KEPEMILIKAN_TANAH', $rumahLama['kepemilikan_tanah']),
                'aset_rumah_di_lokasi_lain' => $post['aset_rumah_di_lokasi_lain'] ?? null,
                'sumber_penerangan' => $this->resolveMasterId('sumber_penerangan', $post, 'SUMBER_PENERANGAN', $rumahLama['sumber_penerangan']),
                'sumber_penerangan_detail' => $post['sumber_penerangan_detail'] ?? null,
                'sumber_air_minum' => $this->resolveMasterId('sumber_air_minum', $post, 'SUMBER_AIR_MINUM', $rumahLama['sumber_air_minum']),
                'jarak_sam_ke_tpa_tinja' => $post['jarak_sam_ke_tpa_tinja'] ?? null,
                'kamar_mandi_dan_jamban' => $post['kamar_mandi_dan_jamban'] ?? null,
                'jenis_jamban_kloset' => $this->resolveMasterId('jenis_jamban_kloset', $post, 'JENIS_JAMBAN', $rumahLama['jenis_jamban_kloset']),
                'jenis_tpa_tinja' => $post['jenis_tpa_tinja'] ?? null,
                'bantuan_perumahan' => $post['bantuan_perumahan'] ?? null,
                'status_backlog' => $post['status_backlog'] ?? null,
                'desil_nasional' => $post['desil_nasional'] ?? null
            ];
            
            if (!empty($post['lokasi_koordinat']) && preg_match('/POINT\s*\(\s*-?\d+\.?\d*\s+-?\d+\.?\d*\s*\)/i', $post['lokasi_koordinat'])) {
                $this->rumahModel->set('lokasi_koordinat', "ST_GeomFromText('{$post['lokasi_koordinat']}')", false);
            }

            // LOGIKA UPLOAD FOTO (UPDATE)
            $uploadPath = FCPATH . 'uploads/rtlh/';
            if (!is_dir($uploadPath)) mkdir($uploadPath, 0777, true);

            foreach(['foto_depan', 'foto_samping', 'foto_belakang', 'foto_dalam'] as $field) {
                $img = $this->request->getFile($field);
                if ($img && $img->isValid() && !$img->hasMoved()) {
                    if (!empty($rumahLama[$field]) && file_exists($uploadPath . $rumahLama[$field])) {
                        @unlink($uploadPath . $rumahLama[$field]);
                    }
                    $newName = $img->getRandomName();
                    $img->move($uploadPath, $newName);
                    $dataRumah[$field] = $newName;
                }
            }

            $this->rumahModel->update($id, $dataRumah);

            // 3. Update Kondisi Fisik
            $dataKondisi = [
                'st_pondasi' => $this->resolveMasterId('st_pondasi', $post, 'KONDISI', $kondisi['st_pondasi'] ?? null),
                'st_kolom' => $this->resolveMasterId('st_kolom', $post, 'KONDISI', $kondisi['st_kolom'] ?? null),
                'st_balok' => $this->resolveMasterId('st_balok', $post, 'KONDISI', $kondisi['st_balok'] ?? null),
                'st_sloof' => $this->resolveMasterId('st_sloof', $post, 'KONDISI', $kondisi['st_sloof'] ?? null),
                'st_rangka_atap' => $this->resolveMasterId('st_rangka_atap', $post, 'KONDISI', $kondisi['st_rangka_atap'] ?? null),
                'st_plafon' => $this->resolveMasterId('st_plafon', $post, 'KONDISI', $kondisi['st_plafon'] ?? null),
                'st_jendela' => $this->resolveMasterId('st_jendela', $post, 'KONDISI', $kondisi['st_jendela'] ?? null),
                'st_ventilasi' => $this->resolveMasterId('st_ventilasi', $post, 'KONDISI', $kondisi['st_ventilasi'] ?? null),
                'mat_atap' => $this->resolveMasterId('mat_atap', $post, 'MATERIAL_ATAP', $kondisi['mat_atap'] ?? null),
                'st_atap' => $this->resolveMasterId('st_atap', $post, 'KONDISI', $kondisi['st_atap'] ?? null),
                'mat_dinding' => $this->resolveMasterId('mat_dinding', $post, 'MATERIAL_DINDING', $kondisi['mat_dinding'] ?? null),
                'st_dinding' => $this->resolveMasterId('st_dinding', $post, 'KONDISI', $kondisi['st_dinding'] ?? null),
                'mat_lantai' => $this->resolveMasterId('mat_lantai', $post, 'MATERIAL_LANTAI', $kondisi['mat_lantai'] ?? null),
                'st_lantai' => $this->resolveMasterId('st_lantai', $post, 'KONDISI', $kondisi['st_lantai'] ?? null)
            ];
            
            $existingKondisi = $this->kondisiModel->where('id_survei', $id)->first();
            if ($existingKondisi) {
                $this->kondisiModel->update($id, $dataKondisi);
            } else {
                $dataKondisi['id_survei'] = $id;
                $this->kondisiModel->insert($dataKondisi);
            }

            $db->transComplete();
            
            $this->logActivity('Ubah', 'RTLH', "Memperbarui data RTLH ID: $id");
            return redirect()->to('/rtlh/detail/' . $id)->with('success', 'Data RTLH berhasil diperbarui.');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Gagal Simpan: ' . $e->getMessage())->withInput();
        }
    }

    public function delete($id)
    {
        $rumah = $this->rumahModel->find($id);
        if ($rumah) {
            $db = \Config\Database::connect();
            $db->transStart();
            $db->table('trash_data')->insert([
                'entity_type' => 'RTLH',
                'entity_id'   => $id,
                'data_json'   => json_encode(['rumah' => $rumah]),
                'deleted_by'  => session()->get('username'),
                'created_at'  => date('Y-m-d H:i:s')
            ]);
            $db->table('rtlh_kondisi_rumah')->where('id_survei', $id)->delete();
            $this->rumahModel->delete($id);
            $db->transComplete();
        }
        return redirect()->to('/rtlh')->with('success', 'Data berhasil dipindahkan ke Recycle Bin.');
    }

    public function bulkDelete()
    {
        $ids = $this->request->getPost('ids');
        if (empty($ids)) return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak ada data yang dipilih.']);

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $deletedCount = 0;
            foreach ($ids as $id) {
                // 1. Cari Rumah dulu karena ID yang dikirim adalah id_survei
                $rumah = $this->rumahModel->find($id);
                if (!$rumah) continue;

                // 2. Cari Penerima berdasarkan nik_pemilik di rumah
                $penerima = $this->penerimaModel->where('nik', $rumah['nik_pemilik'])->first();
                
                // 3. Cari Kondisi berdasarkan id_survei
                $kondisi = $this->kondisiModel->find($id);

                $fullData = [
                    'penerima' => $penerima,
                    'rumah'    => $rumah,
                    'kondisi'  => $kondisi
                ];

                // 4. Pindahkan ke Trash
                $db->table('trash_data')->insert([
                    'entity_type' => 'RTLH',
                    'entity_id'   => $rumah['nik_pemilik'],
                    'data_json'   => json_encode($fullData),
                    'deleted_by'  => session()->get('username'),
                    'created_at'  => date('Y-m-d H:i:s')
                ]);

                // 5. Hapus Kondisi & Rumah (Child)
                $this->kondisiModel->delete($id);
                $this->rumahModel->delete($id);
                
                // 6. Hapus Penerima (Parent) - Opsional, jika satu NIK hanya untuk satu rumah
                if ($penerima) {
                    $this->penerimaModel->delete($penerima['nik']);
                }

                $deletedCount++;
            }

            $db->transComplete();
            if ($db->transStatus() === FALSE) throw new \Exception('Gagal menghapus data massal ke database.');

            $this->logActivity('Hapus Massal', 'RTLH', "Menghapus $deletedCount data RTLH ke Recycle Bin");

            return $this->response->setJSON(['status' => 'success', 'message' => $deletedCount . ' data berhasil dipindahkan ke Recycle Bin.']);
        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    private function resolveMasterId($field, $post, $kategori, $previousValue = null)
    {
        $val = $post[$field] ?? null;
        if ($val === 'lainnya') {
            $manualText = trim($post[$field . '_manual'] ?? '');
            if (!empty($manualText)) {
                $existing = $this->refModel->where('kategori', $kategori)
                                          ->where('nama_pilihan', $manualText)
                                          ->first();
                if ($existing) return $existing['id'];
                
                $this->refModel->insert([
                    'kategori' => $kategori,
                    'nama_pilihan' => $manualText
                ]);
                return $this->refModel->getInsertID();
            }
            return $previousValue; // Balik ke nilai lama jika manual kosong
        }
        return $val ?: $previousValue;
    }
}
