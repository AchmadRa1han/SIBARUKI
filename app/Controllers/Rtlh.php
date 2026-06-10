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
        $status = $this->request->getGet('status') ?? 'semua';
        if ($status === 'Sudah Menerima') $status = 'Rlh';
        if ($status === 'Belum Menerima') $status = 'Rtlh';

        $query = $this->rumahModel->select('perumahan_rtlh_rumah.*, ST_AsText(lokasi_koordinat) as wkt, perumahan_rtlh_penerima.nama_kepala_keluarga as pemilik')
                                  ->join('perumahan_rtlh_penerima', 'perumahan_rtlh_penerima.nik = perumahan_rtlh_rumah.nik_pemilik', 'left');

        if ($status !== 'semua') {
            $query->where('status_bantuan', $status);
        }

        if ($keyword) {
            $query->groupStart()
                  ->like('perumahan_rtlh_penerima.nama_kepala_keluarga', $keyword)
                  ->orLike('perumahan_rtlh_rumah.desa', $keyword)
                  ->orLike('perumahan_rtlh_rumah.nik_pemilik', $keyword)
                  ->groupEnd();
        }

        $rumah = $query->paginate($perPage, 'default');

        // Data untuk Map
        $db = \Config\Database::connect();
        $rumah_all = $db->table('perumahan_rtlh_rumah')
                        ->select('id_survei, desa, ST_AsText(lokasi_koordinat) as wkt, nik_pemilik, status_bantuan, foto_depan, foto_samping, foto_belakang, foto_dalam')
                        ->where('lokasi_koordinat IS NOT NULL')
                        ->where('lokasi_koordinat !=', '')
                        ->limit(1000)
                        ->get()->getResultArray();

        $niks = array_unique(array_column($rumah_all, 'nik_pemilik'));
        $pemilikMap = [];
        if (!empty($niks)) {
            $penerima = $db->table('perumahan_rtlh_penerima')->select('nik, nama_kepala_keluarga')->whereIn('nik', $niks)->get()->getResultArray();
            foreach ($penerima as $p) $pemilikMap[$p['nik']] = $p['nama_kepala_keluarga'];
        }
        foreach ($rumah_all as &$r) {
            $r['pemilik'] = $pemilikMap[$r['nik_pemilik']] ?? 'Pemilik Tidak Terdata';
        }

        $master = []; foreach ($this->refModel->findAll() as $ref) $master[$ref['kategori']][] = $ref;
        $allDesa = $db->table('kode_desa')->orderBy('desa_nama', 'ASC')->get()->getResultArray();
        $desaList = array_map(function($d) { return ['desa' => $d['desa_nama'], 'desa_id' => $d['desa_id']]; }, $allDesa);

        $data = [
            'title' => 'Master Data Perumahan',
            'rumah' => $rumah,
            'rumah_all' => $rumah_all,
            'pager' => $this->rumahModel->pager,
            'perPage' => $perPage,
            'keyword' => $keyword,
            'status' => $status,
            'total_data' => $this->rumahModel->countAllResults(false),
            'master' => $master,
            'desa_list' => $desaList
        ];

        return view('rtlh/index', $data);
    }

    public function markTuntas($id)
    {
        $post = $this->request->getPost();
        $tahun = $post['tahun_bansos'] ?? date('Y');
        $program = $post['program_bansos'];
        $koordinat = $post['lokasi_realisasi'] ?? null;
        $tanggalBantuan = $post['tanggal_bantuan'] ?? date('Y-m-d');

        if (!$id) return redirect()->back()->with('error', 'ID Survei tidak valid.');

        $db = \Config\Database::connect();
        $rumah = $db->table('perumahan_rtlh_rumah')
                    ->select('perumahan_rtlh_rumah.*, ST_AsText(lokasi_koordinat) as lokasi_koordinat_text')
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
            $created_at = !empty($tanggalBantuan) ? $tanggalBantuan . ' ' . date('H:i:s') : $now;
            
            // 1. Update Tabel Utama
            $db->table('perumahan_rtlh_rumah')->where('id_survei', $id)->update([
                'status_bantuan' => 'Rlh',
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
                'created_at' => $created_at,
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

            // Simpan Koordinat Realisasi jika ada, fallback ke koordinat rumah asal jika ada
            $geomText = null;
            if (!empty($koordinat) && preg_match('/POINT\s*\(\s*-?\\d+\\.?\\d*\\s+-?\\d+\\.?\\d*\\s*\\)/i', $koordinat)) {
                $geomText = $koordinat;
            } elseif (!empty($rumah['lokasi_koordinat_text'])) {
                $geomText = $rumah['lokasi_koordinat_text'];
            }

            if ($geomText) {
                $db->table('perumahan_rtlh_bansos')->where('id', $bansosId)
                   ->set('lokasi_realisasi', "ST_GeomFromText('{$geomText}')", false)
                   ->update();
            }

            // 3. Simpan History Perubahan
            $db->table('perumahan_rtlh_history')->insert([
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
            return redirect()->to('/rtlh/detail/' . $id)->with('success', "Realisasi Program berhasil dicatat. Foto Before-After tersedia di halaman detail.");
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

        try {
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file->getTempName());
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membaca file: ' . $e->getMessage());
        }

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
            'jumlah_penghuni_jiwa' => ['jumlah penghuni', 'jumlah penghuni (jiwa)', 'penghuni'],
            'status_backlog'       => ['status backlog', 'backlog'],
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
            'st_pondasi'           => ['*pondasi', 'pondasi'],
            'st_kolom'             => ['*kondisi kolom', 'kondisi kolom', '*tiang/ kolom', 'tiang/ kolom', 'tiang/kolom'],
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
        
        if ($db->table('perumahan_rtlh_rumah')->countAllResults() === 0) {
            $db->query("ALTER TABLE perumahan_rtlh_rumah AUTO_INCREMENT = 1");
            $db->query("ALTER TABLE perumahan_rtlh_kondisi AUTO_INCREMENT = 1");
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
        $dataStartIndex = 0;

        foreach ($rows as $rowIndex => $row) {
            $rowClean = array_map(function($v) { return strtolower(trim($v ?? '')); }, $row);
            if (in_array('*nik', $rowClean) || in_array('nik', $rowClean)) {
                $countPenerangan = 0;
                foreach ($rowClean as $index => $colName) {
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
                $dataStartIndex = $rowIndex + 1;
                break;
            }
        }

        if (!$foundHeader || !isset($headerPos['nik'])) {
            return redirect()->back()->with('error', 'Format Header Excel tidak dikenali. Pastikan kolom *NIK tersedia.');
        }

        $count = 0;
        $db->transStart();
        try {
            for ($i = $dataStartIndex; $i < count($rows); $i++) {
                $row = $rows[$i];
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
                    if (preg_match('/(\d{1,2}\s*[\/\-\.]\s*\d{1,2}\s*[\/\-\.]\s*\d{4})/', $ttl, $matches)) {
                        $tglRaw = $matches[1];
                        $tempat = trim(str_ireplace([$tglRaw, ','], '', $ttl));
                        $tglClean = str_replace(' ', '', $tglRaw);
                        $tglClean = str_replace(['/', '.'], '-', $tglClean);
                        $parts = explode('-', $tglClean);
                        if (count($parts) === 3) {
                            $p1 = (int)$parts[0]; $p2 = (int)$parts[1]; $p3 = (int)$parts[2];
                            if ($p1 > 12 && $p1 <= 31 && $p2 <= 12) $tanggal = sprintf('%04d-%02d-%02d', $p3, $p2, $p1);
                            else if ($p2 > 12 && $p2 <= 31 && $p1 <= 12) $tanggal = sprintf('%04d-%02d-%02d', $p3, $p1, $p2);
                            else $tanggal = sprintf('%04d-%02d-%02d', $p3, $p2, $p1);
                        }
                    } else {
                        foreach ($monthsIndo as $mIndo => $mNum) {
                            if (stripos($ttl, $mIndo) !== false) {
                                if (preg_match('/(\d{1,2})\s+' . $mIndo . '\s+(\d{4})/i', $ttl, $m)) {
                                    $tanggal = $m[2] . '-' . $mNum . '-' . str_pad($m[1], 2, '0', STR_PAD_LEFT);
                                    $tempat = trim(str_ireplace([$m[1], $mIndo, $m[2], ','], '', $ttl));
                                }
                                break;
                            }
                        }
                    }
                }

                $dataPenerima = [
                    'nik'                   => $nik,
                    'no_kk'                 => preg_replace('/[^0-9]/', '', $getVal('no_kk') ?? ''),
                    'nama_kepala_keluarga'  => strtoupper($getVal('nama_kepala_keluarga') ?? ''),
                    'tempat_lahir'          => $tempat,
                    'tanggal_lahir'         => $tanggal,
                    'jenis_kelamin'         => (stripos($getVal('jenis_kelamin') ?? '', 'P') !== false) ? 'P' : 'L',
                    'pendidikan_id'         => $findId('PENDIDIKAN', $getVal('pendidikan_id')),
                    'pekerjaan_id'          => $findId('PEKERJAAN', $getVal('pekerjaan_id')),
                    'penghasilan_per_bulan' => $getVal('penghasilan_per_bulan'),
                    'jumlah_anggota_keluarga'=> (int)($getVal('jumlah_anggota_keluarga') ?? '0'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                if ($this->penerimaModel->find($nik)) $this->penerimaModel->update($nik, $dataPenerima);
                else {
                    $dataPenerima['created_at'] = date('Y-m-d H:i:s');
                    $this->penerimaModel->insert($dataPenerima);
                }

                $desaNama = strtoupper(trim($getVal('desa') ?? ''));
                $desaId = $desaLookup[$desaNama] ?? null;

                $dataRumah = [
                    'nik_pemilik'         => $nik,
                    'desa'                => $desaNama,
                    'desa_id'             => $desaId,
                    'alamat_detail'       => $getVal('alamat_detail'),
                    'jenis_kawasan'       => $getVal('jenis_kawasan'),
                    'fungsi_ruang'        => $getVal('fungsi_ruang'),
                    'luas_rumah_m2'       => (float)str_replace(',', '.', $getVal('luas_rumah_m2') ?? '0'),
                    'luas_lahan_m2'       => (float)str_replace(',', '.', $getVal('luas_lahan_m2') ?? '0'),
                    'kepemilikan_rumah'   => $findId('KEPEMILIKAN_RUMAH', $getVal('kepemilikan_rumah')),
                    'aset_rumah_di_lokasi_lain' => $getVal('aset_rumah_di_lokasi_lain'),
                    'kepemilikan_tanah'   => $findId('KEPEMILIKAN_TANAH', $getVal('kepemilikan_tanah')),
                    'bantuan_perumahan'   => $getVal('bantuan_perumahan'),
                    'sumber_penerangan'   => $findId('SUMBER_PENERANGAN', $getVal('sumber_penerangan')),
                    'sumber_penerangan_detail' => $getVal('sumber_penerangan_detail'),
                    'sumber_air_minum'    => $findId('SUMBER_AIR_MINUM', $getVal('sumber_air_minum')),
                    'jarak_sam_ke_tpa_tinja'=> $getVal('jarak_sam_ke_tpa_tinja'),
                    'kamar_mandi_dan_jamban'=> $getVal('kamar_mandi_dan_jamban'),
                    'jenis_jamban_kloset' => $findId('JENIS_JAMBAN', $getVal('jenis_jamban_kloset')),
                    'jenis_tpa_tinja'     => $getVal('jenis_tpa_tinja'),
                    'status_bantuan'      => 'Rtlh',
                    'updated_at'          => date('Y-m-d H:i:s')
                ];
                
                $existingRumah = $this->rumahModel->where('nik_pemilik', $nik)->first();
                if ($existingRumah) {
                    $this->rumahModel->update($existingRumah['id_survei'], $dataRumah);
                    $idSurvei = $existingRumah['id_survei'];
                } else {
                    $dataRumah['created_at'] = date('Y-m-d H:i:s');
                    $this->rumahModel->insert($dataRumah);
                    $idSurvei = $this->rumahModel->getInsertID();
                }

                $dataKondisi = [
                    'id_survei'      => $idSurvei,
                    'st_pondasi'     => $findId('KONDISI', $getVal('st_pondasi')),
                    'st_kolom'       => $findId('KONDISI', $getVal('st_kolom')),
                    'st_balok'       => $findId('KONDISI', $getVal('st_balok')),
                    'st_sloof'       => $findId('KONDISI', $getVal('st_sloof')),
                    'st_rangka_atap' => $findId('KONDISI', $getVal('st_rangka_atap')),
                    'st_plafon'      => $findId('KONDISI', $getVal('st_plafon')),
                    'st_jendela'     => $findId('KONDISI', $getVal('st_jendela')),
                    'st_ventilasi'   => $findId('KONDISI', $getVal('st_ventilasi')),
                    'mat_lantai'     => $findId('MATERIAL_LANTAI', $getVal('mat_lantai')),
                    'st_lantai'      => $findId('KONDISI', $getVal('st_lantai')),
                    'mat_dinding'    => $findId('MATERIAL_DINDING', $getVal('mat_dinding')),
                    'st_dinding'     => $findId('KONDISI', $getVal('st_dinding')),
                    'mat_atap'       => $findId('MATERIAL_ATAP', $getVal('mat_atap')),
                    'st_atap'        => $findId('KONDISI', $getVal('st_atap')),
                    'updated_at'     => date('Y-m-d H:i:s')
                ];

                if ($this->kondisiModel->find($idSurvei)) $this->kondisiModel->update($idSurvei, $dataKondisi);
                else {
                    $dataKondisi['created_at'] = date('Y-m-d H:i:s');
                    $this->kondisiModel->insert($dataKondisi);
                }

                $count++;
            }
            $db->transComplete();
            $this->logActivity('Import', 'RTLH', "Berhasil mengimpor $count data RTLH via Excel");
            return redirect()->back()->with('success', "$count data RTLH berhasil diimpor.");
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Gagal Impor: ' . $e->getMessage());
        }
    }

    public function detail($id)
    {
        if (!has_permission('view_rtlh_detail')) return redirect()->to('/rtlh')->with('message', 'Akses ditolak.');
        $rumah = $this->rumahModel->select('perumahan_rtlh_rumah.*, ST_AsText(lokasi_koordinat) as wkt')->find($id);
        if (!$rumah) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        
        // Remove raw binary geometry data to prevent json_encode failure in views
        if (isset($rumah['lokasi_koordinat'])) unset($rumah['lokasi_koordinat']);

        $db = \Config\Database::connect();
        
        // Ensure desa name is populated from kode_desa if it is empty/null in perumahan_rtlh_rumah
        if (empty($rumah['desa']) && !empty($rumah['desa_id'])) {
            $desaRow = $db->table('kode_desa')->where('desa_id', $rumah['desa_id'])->get()->getRowArray();
            if ($desaRow) {
                $rumah['desa'] = $desaRow['desa_nama'];
            }
        }

        $kondisi = $this->kondisiModel->where('id_survei', $id)->first();
        $penerima = $this->penerimaModel->where('nik', $rumah['nik_pemilik'])->first();
        $realisasi = $db->table('perumahan_rtlh_bansos')->select('*, ST_AsText(lokasi_realisasi) as wkt_realisasi')->where('id_survei', $id)->orderBy('id', 'DESC')->get()->getRowArray();
        $master = []; foreach ($this->refModel->findAll() as $ref) $master[$ref['kategori']][] = $ref;
        $allDesa = $db->table('kode_desa')->orderBy('desa_nama', 'ASC')->get()->getResultArray();
        $desaList = array_map(function($d) { return ['desa' => $d['desa_nama'], 'desa_id' => $d['desa_id']]; }, $allDesa);

        return view('rtlh/detail', [
            'title' => 'Detail RTLH', 
            'rumah' => $rumah, 
            'kondisi' => $kondisi, 
            'penerima' => $penerima, 
            'realisasi' => $realisasi, 
            'ref' => $this->refModel->getAllMapped(),
            'master' => $master,
            'desa_list' => $desaList
        ]);
    }

    public function print($id)
    {
        $db = \Config\Database::connect();
        $rumah = $this->rumahModel->select('perumahan_rtlh_rumah.*, ST_AsText(lokasi_koordinat) as wkt')->find($id);
        if (!$rumah) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        
        $penerima = $db->table('perumahan_rtlh_penerima')
                       ->select('perumahan_rtlh_penerima.*, ref_edu.nama_pilihan as nama_pendidikan, ref_job.nama_pilihan as nama_pekerjaan')
                       ->join('sys_ref_master as ref_edu', 'ref_edu.id = perumahan_rtlh_penerima.pendidikan_id', 'left')
                       ->join('sys_ref_master as ref_job', 'ref_job.id = perumahan_rtlh_penerima.pekerjaan_id', 'left')
                       ->where('nik', $rumah['nik_pemilik'])
                       ->get()->getRowArray();

        $kondisi = $db->table('perumahan_rtlh_kondisi')
                      ->select('perumahan_rtlh_kondisi.*, 
                                r1.nama_pilihan as nm_st_pondasi, r2.nama_pilihan as nm_st_kolom, 
                                r3.nama_pilihan as nm_st_balok, r4.nama_pilihan as nm_st_sloof,
                                r5.nama_pilihan as nm_st_rangka_atap, r6.nama_pilihan as nm_st_plafon,
                                r7.nama_pilihan as nm_st_jendela, r8.nama_pilihan as nm_st_ventilasi,
                                r9.nama_pilihan as nm_mat_lantai, r10.nama_pilihan as nm_st_lantai,
                                r11.nama_pilihan as nm_mat_dinding, r12.nama_pilihan as nm_st_dinding,
                                r13.nama_pilihan as nm_mat_atap, r14.nama_pilihan as nm_st_atap')
                      ->join('sys_ref_master as r1', 'r1.id = perumahan_rtlh_kondisi.st_pondasi', 'left')
                      ->join('sys_ref_master as r2', 'r2.id = perumahan_rtlh_kondisi.st_kolom', 'left')
                      ->join('sys_ref_master as r3', 'r3.id = perumahan_rtlh_kondisi.st_balok', 'left')
                      ->join('sys_ref_master as r4', 'r4.id = perumahan_rtlh_kondisi.st_sloof', 'left')
                      ->join('sys_ref_master as r5', 'r5.id = perumahan_rtlh_kondisi.st_rangka_atap', 'left')
                      ->join('sys_ref_master as r6', 'r6.id = perumahan_rtlh_kondisi.st_plafon', 'left')
                      ->join('sys_ref_master as r7', 'r7.id = perumahan_rtlh_kondisi.st_jendela', 'left')
                      ->join('sys_ref_master as r8', 'r8.id = perumahan_rtlh_kondisi.st_ventilasi', 'left')
                      ->join('sys_ref_master as r9', 'r9.id = perumahan_rtlh_kondisi.mat_lantai', 'left')
                      ->join('sys_ref_master as r10', 'r10.id = perumahan_rtlh_kondisi.st_lantai', 'left')
                      ->join('sys_ref_master as r11', 'r11.id = perumahan_rtlh_kondisi.mat_dinding', 'left')
                      ->join('sys_ref_master as r12', 'r12.id = perumahan_rtlh_kondisi.st_dinding', 'left')
                      ->join('sys_ref_master as r13', 'r13.id = perumahan_rtlh_kondisi.mat_atap', 'left')
                      ->join('sys_ref_master as r14', 'r14.id = perumahan_rtlh_kondisi.st_atap', 'left')
                      ->where('id_survei', $id)
                      ->get()->getRowArray();

        return view('rtlh/print_report', [
            'rumah' => $rumah,
            'penerima' => $penerima,
            'kondisi' => $kondisi,
            'ref' => $this->refModel->getAllMapped()
        ]);
    }

    public function rekapDesa()
    {
        if (session()->get('role_id') != 1) return redirect()->to('/dashboard')->with('error', 'Hanya Admin yang dapat melihat data rekapan per desa.');
        
        $db = \Config\Database::connect();
        $keyword = $this->request->getGet('keyword');
        
        $desaBuilder = $db->table('kode_desa')->select('desa_id, desa_nama')->orderBy('desa_nama', 'ASC');
        if ($keyword) {
            $desaBuilder->like('desa_nama', $keyword);
        }
        $desaMaster = $desaBuilder->get()->getResultArray();
        
        $rekap = [];
        foreach($desaMaster as $dm) {
            $desaId = $dm['desa_id']; $desaNama = $dm['desa_nama'];
            $totalRtlh = $db->table('perumahan_rtlh_rumah')->where('desa_id', $desaId)->whereIn('status_bantuan', ['Rtlh', 'Target'])->countAllResults();
            $rlhSurvei = $db->table('perumahan_rtlh_rumah')->where('desa_id', $desaId)->whereIn('status_bantuan', ['Rlh', 'Sudah Menerima'])->countAllResults();
            $baseName = trim(str_replace(['DESA', 'KELURAHAN', 'KEL.', ' '], '', strtoupper($desaNama)));
            $bansosExtra = $db->query("
                SELECT COUNT(*) as total FROM perumahan_rtlh_bansos b
                WHERE (REPLACE(REPLACE(REPLACE(REPLACE(UPPER(b.desa), 'DESA', ''), 'KELURAHAN', ''), 'KEL.', ''), ' ', '') LIKE ?)
                AND (b.id_survei IS NULL OR b.id_survei = '' OR b.id_survei = '0')
                AND b.nik NOT IN (SELECT nik_pemilik FROM perumahan_rtlh_rumah WHERE desa_id = ?)
            ", ['%' . $baseName . '%', $desaId])->getRowArray()['total'] ?? 0;
            $totalRlh = $rlhSurvei + $bansosExtra;
            $rekap[] = [
                'desa' => $desaNama, 'desa_id' => $desaId, 'total_rtlh' => $totalRtlh, 'total_rlh' => $totalRlh, 'total_semua' => $totalRtlh + $totalRlh
            ];
        }
        return view('rtlh/rekap_desa', ['title' => 'Rekapitulasi Desa', 'rekap' => $rekap, 'keyword' => $keyword]);
    }

    public function backlog()
    {
        if (session()->get('role_id') != 1) return redirect()->to('/dashboard')->with('error', 'Hanya Admin yang dapat mengakses halaman manajemen backlog.');

        $model = new \App\Models\BacklogIndividuModel();
        $db = \Config\Database::connect();
        $keyword = $this->request->getGet('keyword');
        $perPage = $this->request->getGet('per_page') ?: 10;

        $query = $model->select('perumahan_backlog_individu.*');

        if ($keyword) {
            $query->groupStart()
                  ->like('nik', $keyword)
                  ->orLike('nama_lengkap', $keyword)
                  ->orLike('desa', $keyword)
                  ->groupEnd();
        }

        $data = $query->orderBy('created_at', 'DESC')->paginate($perPage, 'default');
        
        return view('rtlh/backlog', [
            'title' => 'Manajemen Data Backlog',
            'backlog' => $data,
            'pager' => $model->pager,
            'keyword' => $keyword,
            'perPage' => $perPage,
            'desa' => $db->table('kode_desa')->get()->getResultArray()
        ]);
    }

    public function storeBacklogIndividu()
    {
        if (session()->get('role_id') != 1) return redirect()->to('/dashboard');
        
        $model = new \App\Models\BacklogIndividuModel();
        $post = $this->request->getPost();
        
        $nik = preg_replace('/[^0-9]/', '', $post['nik'] ?? '');
        if (strlen($nik) != 16) return redirect()->back()->with('error', 'NIK harus 16 digit.')->withInput();
        
        if ($model->where('nik', $nik)->first()) {
            return redirect()->back()->with('error', 'NIK tersebut sudah terdaftar dalam data Backlog.')->withInput();
        }

        if ($this->penerimaModel->find($nik)) {
            return redirect()->back()->with('error', 'NIK tersebut sudah terdaftar sebagai penerima RTLH.')->withInput();
        }

        $db = \Config\Database::connect();
        $desaInfo = $db->table('kode_desa')->where('desa_id', $post['desa_id'])->get()->getRowArray();

        $data = [
            'nik' => $nik,
            'no_kk' => preg_replace('/[^0-9]/', '', $post['no_kk'] ?? ''),
            'nama_lengkap' => strtoupper($post['nama_lengkap']),
            'desa_id' => $post['desa_id'],
            'desa' => $desaInfo['desa_nama'] ?? null,
            'alamat_detail' => $post['alamat_detail'],
            'nama_pemilik_rumah' => strtoupper($post['nama_pemilik_rumah']),
            'keterangan_hunian' => $post['keterangan_hunian'],
            'tahun_data' => $post['tahun_data'] ?: date('Y')
        ];

        if ($model->insert($data)) {
            $this->logActivity('Tambah', 'Backlog', "Menambah data backlog individu NIK: $nik");
            return redirect()->to('/rtlh/backlog')->with('success', 'Data backlog berhasil ditambahkan.');
        }

        return redirect()->back()->with('error', 'Gagal menyimpan data.')->withInput();
    }

    public function updateBacklogIndividu($id)
    {
        if (session()->get('role_id') != 1) return redirect()->to('/dashboard');
        
        $model = new \App\Models\BacklogIndividuModel();
        $post = $this->request->getPost();
        
        $db = \Config\Database::connect();
        $desaInfo = $db->table('kode_desa')->where('desa_id', $post['desa_id'])->get()->getRowArray();

        $data = [
            'no_kk' => preg_replace('/[^0-9]/', '', $post['no_kk'] ?? ''),
            'nama_lengkap' => strtoupper($post['nama_lengkap']),
            'desa_id' => $post['desa_id'],
            'desa' => $desaInfo['desa_nama'] ?? null,
            'alamat_detail' => $post['alamat_detail'],
            'nama_pemilik_rumah' => strtoupper($post['nama_pemilik_rumah']),
            'keterangan_hunian' => $post['keterangan_hunian'],
            'tahun_data' => $post['tahun_data'] ?: date('Y')
        ];

        if ($model->update($id, $data)) {
            $this->logActivity('Ubah', 'Backlog', "Memperbarui data backlog individu ID: $id");
            return redirect()->to('/rtlh/backlog')->with('success', 'Data backlog berhasil diperbarui.');
        }

        return redirect()->back()->with('error', 'Gagal memperbarui data.');
    }

    public function deleteBacklogIndividu($id)
    {
        if (session()->get('role_id') != 1) return $this->response->setJSON(['status' => 'error', 'message' => 'Akses ditolak.']);
        
        $model = new \App\Models\BacklogIndividuModel();
        if ($model->delete($id)) {
            $this->logActivity('Hapus', 'Backlog', "Menghapus data backlog individu ID: $id");
            return $this->response->setJSON(['status' => 'success', 'message' => 'Data berhasil dihapus.']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menghapus data.']);
    }

    public function historyTransformasi()
    {
        return view('rtlh/history_transformasi', [
            'title' => 'Histori Transformasi RTLH',
            'history' => $this->historyModel->orderBy('created_at', 'DESC')->paginate(10, 'default'),
            'pager' => $this->historyModel->pager,
            'ref' => $this->refModel->getAllMapped()
        ]);
    }

    public function exportExcel()
    {
        $db = \Config\Database::connect();
        $data = $db->table('perumahan_rtlh_rumah')->select('perumahan_rtlh_rumah.*, ST_AsText(lokasi_koordinat) as wkt_text, perumahan_rtlh_penerima.*, perumahan_rtlh_kondisi.*')
                   ->join('perumahan_rtlh_penerima', 'perumahan_rtlh_penerima.nik = perumahan_rtlh_rumah.nik_pemilik', 'left')
                   ->join('perumahan_rtlh_kondisi', 'perumahan_rtlh_kondisi.id_survei = perumahan_rtlh_rumah.id_survei', 'left')
                   ->get()->getResultArray();
        $refMap = $this->refModel->getAllMapped();
        $spreadsheet = new Spreadsheet(); $sheet = $spreadsheet->getActiveSheet();
        $headers = ['ID SURVEI', 'NAMA KEPALA KELUARGA', 'NIK', 'NO KK', 'TEMPAT LAHIR', 'TGL LAHIR', 'JK', 'PENDIDIKAN', 'PEKERJAAN', 'PENGHASILAN', 'JML ANGGOTA KELUARGA', 'ALAMAT', 'DESA', 'JENIS KAWASAN', 'FUNGSI RUANG', 'KEPEMILIKAN RUMAH', 'ASET DI LOKASI LAIN', 'KEPEMILIKAN TANAH', 'SUMBER PENERANGAN', 'DETAIL PENERANGAN', 'BANTUAN PERUMAHAN', 'LUAS RUMAH', 'LUAS LAHAN', 'SUMBER AIR MINUM', 'JARAK SAM KE TPA', 'KM DAN JAMBAN', 'JENIS KLOSET', 'JENIS TPA TINJA', 'ST PONDASI', 'ST KOLOM', 'ST BALOK', 'ST SLOOF', 'ST RANGKA ATAP', 'ST PLAFON', 'ST JENDELA', 'ST VENTILASI', 'MAT LANTAI', 'ST LANTAI', 'MAT DINDING', 'ST DINDING', 'MAT ATAP', 'ST ATAP', 'STATUS BANTUAN', 'TAHUN BANSOS', 'BACKLOG', 'DESIL', 'WKT'];
        foreach ($headers as $idx => $header) $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($idx + 1) . '1', $header);
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
            $sheet->setCellValue('AU' . $rowNum, $row['wkt_text']);
            $rowNum++;
        }
        $sheet->getStyle('A1:AV1')->getFont()->setBold(true);
        $this->logActivity('Export Excel', 'RTLH', "Mengekspor " . count($data) . " data RTLH Lengkap");
        $filename = 'Export_Lengkap_RTLH_' . date('YmdHis') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        $writer = new Xlsx($spreadsheet); $writer->save('php://output'); exit;
    }

    public function create()
    {
        $db = \Config\Database::connect();
        $master = []; foreach ($this->refModel->findAll() as $ref) $master[$ref['kategori']][] = $ref;
        $allDesa = $db->table('kode_desa')->orderBy('desa_nama', 'ASC')->get()->getResultArray();
        $desaList = array_map(function($d) { return ['desa' => $d['desa_nama'], 'desa_id' => $d['desa_id']]; }, $allDesa);
        return view('rtlh/create', ['title' => 'Tambah RTLH', 'master' => $master, 'desa_list' => $desaList]);
    }

    public function store()
    {
        $db = \Config\Database::connect(); $post = $this->request->getPost(); $nik = preg_replace('/[^0-9]/', '', $post['nik'] ?? '');
        if (empty($nik)) return redirect()->back()->with('error', 'NIK wajib diisi.')->withInput();
        if ($this->penerimaModel->find($nik)) return redirect()->back()->with('error', 'NIK sudah terdaftar.')->withInput();
        $db->transStart();
        try {
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
            
            // Resolve Desa Name from kode_desa if it is empty
            $desaId = $post['desa_id'] ?? null;
            $desaNama = !empty($post['desa']) ? $post['desa'] : null;
            if (empty($desaNama) && !empty($desaId)) {
                $desaRow = $db->table('kode_desa')->where('desa_id', $desaId)->get()->getRowArray();
                if ($desaRow) $desaNama = $desaRow['desa_nama'];
            }
            if (empty($desaNama)) $desaNama = null;

            $dataRumah = [
                'nik_pemilik' => $nik, 
                'desa' => $desaNama, 
                'desa_id' => $desaId, 
                'alamat_detail' => $post['alamat_detail'] ?? null, 
                'jenis_kawasan' => $this->resolveMasterId('jenis_kawasan', $post, 'JENIS_KAWASAN'), 
                'luas_rumah_m2' => $post['luas_rumah_m2'] ?? 0, 
                'luas_lahan_m2' => $post['luas_lahan_m2'] ?? 0, 
                'kepemilikan_rumah' => $this->resolveMasterId('kepemilikan_rumah', $post, 'KEPEMILIKAN_RUMAH'), 
                'kepemilikan_tanah' => $this->resolveMasterId('kepemilikan_tanah', $post, 'KEPEMILIKAN_TANAH'), 
                'aset_rumah_di_lokasi_lain' => $post['aset_rumah_di_lokasi_lain'] ?? 'TIDAK ADA',
                'sumber_penerangan' => $this->resolveMasterId('sumber_penerangan', $post, 'SUMBER_PENERANGAN'), 
                'sumber_penerangan_detail' => $post['sumber_penerangan_detail'] ?? null,
                'sumber_air_minum' => $this->resolveMasterId('sumber_air_minum', $post, 'SUMBER_AIR_MINUM'), 
                'jarak_sam_ke_tpa_tinja' => $post['jarak_sam_ke_tpa_tinja'] ?? null,
                'kamar_mandi_dan_jamban' => $post['kamar_mandi_dan_jamban'] ?? 'SENDIRI',
                'jenis_jamban_kloset' => $this->resolveMasterId('jenis_jamban_kloset', $post, 'JENIS_JAMBAN'), 
                'jenis_tpa_tinja' => $post['jenis_tpa_tinja'] ?? null,
                'bantuan_perumahan' => $post['bantuan_perumahan'] ?? null,
                'desil_nasional' => $post['desil_nasional'] ?? null,
                'status_backlog' => $post['status_backlog'] ?? 'TIDAK BACKLOG',
                'jumlah_penghuni_jiwa' => $post['jumlah_penghuni_jiwa'] ?? 0,
                'status_bantuan' => (isset($post['status_bantuan']) && $post['status_bantuan'] === 'Belum Menerima') ? 'Rtlh' : ((isset($post['status_bantuan']) && $post['status_bantuan'] === 'Sudah Menerima') ? 'Rlh' : ($post['status_bantuan'] ?? 'Rtlh')), 
                'created_at' => date('Y-m-d H:i:s'), 
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $uploadPath = FCPATH . 'uploads/rtlh/'; if (!is_dir($uploadPath)) mkdir($uploadPath, 0777, true);
            foreach(['foto_depan', 'foto_samping', 'foto_belakang', 'foto_dalam'] as $field) {
                $img = $this->request->getFile($field); if ($img && $img->isValid() && !$img->hasMoved()) { $newName = $img->getRandomName(); $img->move($uploadPath, $newName); $dataRumah[$field] = $newName; }
            }
            $this->rumahModel->set($dataRumah); if (!empty($post['lokasi_koordinat']) && preg_match('/POINT\s*\(\s*-?\d+\.?\d*\s+-?\d+\.?\d*\s*\)/i', $post['lokasi_koordinat'])) { $this->rumahModel->set('lokasi_koordinat', "ST_GeomFromText('{$post['lokasi_koordinat']}')", false); }
            $this->rumahModel->insert(); $surveiId = $this->rumahModel->getInsertID();
            $this->kondisiModel->insert(['id_survei' => $surveiId, 'st_pondasi' => $this->resolveMasterId('st_pondasi', $post, 'KONDISI'), 'st_kolom' => $this->resolveMasterId('st_kolom', $post, 'KONDISI'), 'st_balok' => $this->resolveMasterId('st_balok', $post, 'KONDISI'), 'st_sloof' => $this->resolveMasterId('st_sloof', $post, 'KONDISI'), 'st_rangka_atap' => $this->resolveMasterId('st_rangka_atap', $post, 'KONDISI'), 'st_plafon' => $this->resolveMasterId('st_plafon', $post, 'KONDISI'), 'st_jendela' => $this->resolveMasterId('st_jendela', $post, 'KONDISI'), 'st_ventilasi' => $this->resolveMasterId('st_ventilasi', $post, 'KONDISI'), 'mat_atap' => $this->resolveMasterId('mat_atap', $post, 'MATERIAL_ATAP'), 'st_atap' => $this->resolveMasterId('st_atap', $post, 'KONDISI'), 'mat_dinding' => $this->resolveMasterId('mat_dinding', $post, 'MATERIAL_DINDING'), 'st_dinding' => $this->resolveMasterId('st_dinding', $post, 'KONDISI'), 'mat_lantai' => $this->resolveMasterId('mat_lantai', $post, 'MATERIAL_LANTAI'), 'st_lantai' => $this->resolveMasterId('st_lantai', $post, 'KONDISI'), 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
            $db->transComplete(); $this->logActivity('Tambah', 'RTLH', "Menambah data RTLH baru NIK: $nik");
            return redirect()->to('/rtlh')->with('success', 'Data RTLH berhasil ditambahkan.');
        } catch (\Exception $e) { $db->transRollback(); return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage())->withInput(); }
    }

    public function edit($id)
    {
        $db = \Config\Database::connect(); 
        $rumah = $this->rumahModel->select('*, ST_AsText(lokasi_koordinat) as lokasi_koordinat')->find($id); 
        if (!$rumah) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        
        $master = []; 
        foreach ($this->refModel->findAll() as $ref) $master[$ref['kategori']][] = $ref;
        
        $allDesa = $db->table('kode_desa')->orderBy('desa_nama', 'ASC')->get()->getResultArray();
        $desaList = array_map(function($d) { return ['desa' => $d['desa_nama'], 'desa_id' => $d['desa_id']]; }, $allDesa);
        
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
        $rumahLama = $this->rumahModel->select('*, ST_AsText(lokasi_koordinat) as lokasi_koordinat')->find($id); 
        if (!$rumahLama) return redirect()->back()->with('error', 'Data tidak ditemukan.');
        
        $post = $this->request->getPost(); 
        $nik = $rumahLama['nik_pemilik']; 
        $penerima = $this->penerimaModel->where('nik', $nik)->first(); 
        $kondisi = $this->kondisiModel->where('id_survei', $id)->first();
        
        try {
            $db->transException(true)->transStart();
            
            // Data Penerima - Preserve if missing or empty
            $dataPenerima = [
                'nama_kepala_keluarga' => !empty($post['nama_kepala_keluarga']) ? $post['nama_kepala_keluarga'] : ($penerima['nama_kepala_keluarga'] ?? null),
                'no_kk' => !empty($post['no_kk']) ? preg_replace('/[^0-9]/', '', $post['no_kk']) : ($penerima['no_kk'] ?? null),
                'tempat_lahir' => !empty($post['tempat_lahir']) ? $post['tempat_lahir'] : ($penerima['tempat_lahir'] ?? null),
                'tanggal_lahir' => !empty($post['tanggal_lahir']) ? $post['tanggal_lahir'] : ($penerima['tanggal_lahir'] ?? null),
                'jenis_kelamin' => !empty($post['jenis_kelamin']) ? $post['jenis_kelamin'] : ($penerima['jenis_kelamin'] ?? null),
                'jumlah_anggota_keluarga' => isset($post['jumlah_anggota_keluarga']) && $post['jumlah_anggota_keluarga'] !== '' ? $post['jumlah_anggota_keluarga'] : ($penerima['jumlah_anggota_keluarga'] ?? 0),
                'pendidikan_id' => $this->resolveMasterId('pendidikan_id', $post, 'PENDIDIKAN', $penerima['pendidikan_id'] ?? null),
                'pekerjaan_id' => $this->resolveMasterId('pekerjaan_id', $post, 'PEKERJAAN', $penerima['pekerjaan_id'] ?? null),
                'penghasilan_per_bulan' => $this->resolveMasterId('penghasilan_per_bulan', $post, 'PENGHASILAN', $penerima['penghasilan_per_bulan'] ?? null)
            ];

            // Up-sert Penerima (Insert if not exists, Update if exists)
            $penerimaExists = $this->penerimaModel->where('nik', $nik)->countAllResults() > 0;
            if ($penerimaExists) {
                $this->penerimaModel->update($nik, $dataPenerima);
            } else {
                $dataPenerima['nik'] = $nik;
                $this->penerimaModel->insert($dataPenerima);
            }

            // Resolve Desa Name from kode_desa if it is empty on update
            $desaId = !empty($post['desa_id']) ? $post['desa_id'] : $rumahLama['desa_id'];
            $desaNama = !empty($post['desa']) ? $post['desa'] : null;
            if (empty($desaNama) && !empty($desaId)) {
                $desaRow = $db->table('kode_desa')->where('desa_id', $desaId)->get()->getRowArray();
                if ($desaRow) $desaNama = $desaRow['desa_nama'];
            }
            if (empty($desaNama)) $desaNama = $rumahLama['desa'];

            // Data Rumah - Preserve if missing or empty
            $dataRumah = [
                'alamat_detail' => !empty($post['alamat_detail']) ? $post['alamat_detail'] : $rumahLama['alamat_detail'],
                'desa' => $desaNama,
                'desa_id' => $desaId,
                'jenis_kawasan' => $this->resolveMasterId('jenis_kawasan', $post, 'JENIS_KAWASAN', $rumahLama['jenis_kawasan']),
                'luas_rumah_m2' => isset($post['luas_rumah_m2']) && $post['luas_rumah_m2'] !== '' ? $post['luas_rumah_m2'] : $rumahLama['luas_rumah_m2'],
                'luas_lahan_m2' => isset($post['luas_lahan_m2']) && $post['luas_lahan_m2'] !== '' ? $post['luas_lahan_m2'] : $rumahLama['luas_lahan_m2'],
                'fungsi_ruang' => !empty($post['fungsi_ruang']) ? $post['fungsi_ruang'] : $rumahLama['fungsi_ruang'],
                'jumlah_penghuni_jiwa' => isset($post['jumlah_penghuni_jiwa']) && $post['jumlah_penghuni_jiwa'] !== '' ? $post['jumlah_penghuni_jiwa'] : $rumahLama['jumlah_penghuni_jiwa'],
                'kepemilikan_rumah' => $this->resolveMasterId('kepemilikan_rumah', $post, 'KEPEMILIKAN_RUMAH', $rumahLama['kepemilikan_rumah']),
                'kepemilikan_tanah' => $this->resolveMasterId('kepemilikan_tanah', $post, 'KEPEMILIKAN_TANAH', $rumahLama['kepemilikan_tanah']),
                'aset_rumah_di_lokasi_lain' => !empty($post['aset_rumah_di_lokasi_lain']) ? $post['aset_rumah_di_lokasi_lain'] : $rumahLama['aset_rumah_di_lokasi_lain'],
                'sumber_penerangan' => $this->resolveMasterId('sumber_penerangan', $post, 'SUMBER_PENERANGAN', $rumahLama['sumber_penerangan']),
                'sumber_penerangan_detail' => !empty($post['sumber_penerangan_detail']) ? $post['sumber_penerangan_detail'] : $rumahLama['sumber_penerangan_detail'],
                'sumber_air_minum' => $this->resolveMasterId('sumber_air_minum', $post, 'SUMBER_AIR_MINUM', $rumahLama['sumber_air_minum']),
                'jarak_sam_ke_tpa_tinja' => !empty($post['jarak_sam_ke_tpa_tinja']) ? $post['jarak_sam_ke_tpa_tinja'] : $rumahLama['jarak_sam_ke_tpa_tinja'],
                'kamar_mandi_dan_jamban' => !empty($post['kamar_mandi_dan_jamban']) ? $post['kamar_mandi_dan_jamban'] : $rumahLama['kamar_mandi_dan_jamban'],
                'jenis_jamban_kloset' => $this->resolveMasterId('jenis_jamban_kloset', $post, 'JENIS_JAMBAN', $rumahLama['jenis_jamban_kloset']),
                'jenis_tpa_tinja' => !empty($post['jenis_tpa_tinja']) ? $post['jenis_tpa_tinja'] : $rumahLama['jenis_tpa_tinja'],
                'bantuan_perumahan' => !empty($post['bantuan_perumahan']) ? $post['bantuan_perumahan'] : $rumahLama['bantuan_perumahan'],
                'desil_nasional' => !empty($post['desil_nasional']) ? $post['desil_nasional'] : $rumahLama['desil_nasional'],
                'status_backlog' => !empty($post['status_backlog']) ? $post['status_backlog'] : $rumahLama['status_backlog'],
                'status_bantuan' => (($post['status_bantuan'] ?? '') === 'Belum Menerima') ? 'Rtlh' : ((($post['status_bantuan'] ?? '') === 'Sudah Menerima') ? 'Rlh' : (!empty($post['status_bantuan']) ? $post['status_bantuan'] : $rumahLama['status_bantuan']))
            ];

            if (!empty($post['lokasi_koordinat']) && preg_match('/POINT\s*\(\s*-?\d+\.?\d*\s+-?\d+\.?\d*\s*\)/i', $post['lokasi_koordinat'])) {
                $this->rumahModel->set('lokasi_koordinat', "ST_GeomFromText('{$post['lokasi_koordinat']}')", false);
            }
            
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
            
            // Data Kondisi - Preserve if missing or empty
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
                'st_lantai' => $this->resolveMasterId('st_lantai', $post, 'KONDISI', $kondisi['st_lantai'] ?? null),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Up-sert Kondisi (Insert if not exists, Update if exists)
            $kondisiExists = $this->kondisiModel->where('id_survei', $id)->countAllResults() > 0;
            if ($kondisiExists) {
                $this->kondisiModel->update($id, $dataKondisi);
            } else {
                $dataKondisi['id_survei'] = $id;
                $dataKondisi['created_at'] = date('Y-m-d H:i:s');
                $this->kondisiModel->insert($dataKondisi);
            }
            
            $db->transComplete(); 
            $this->logActivity('Ubah', 'RTLH', "Memperbarui data RTLH ID: $id");
            return redirect()->to('/rtlh/detail/' . $id)->with('success', 'Data RTLH berhasil diperbarui.');
        } catch (\Exception $e) { 
            $db->transRollback(); 
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage())->withInput(); 
        }
    }

    public function delete($id)
    {
        $rumah = $this->rumahModel->find($id);
        if ($rumah) {
            $db = \Config\Database::connect(); $db->transStart();
            $db->table('sys_trash')->insert(['entity_type' => 'RTLH', 'entity_id' => $id, 'data_json' => json_encode(['rumah' => $rumah]), 'deleted_by' => session()->get('username'), 'created_at' => date('Y-m-d H:i:s')]);
            $db->table('perumahan_rtlh_kondisi')->where('id_survei', $id)->delete(); $this->rumahModel->delete($id); $db->transComplete();
        }
        return redirect()->to('/rtlh')->with('success', 'Data dipindahkan ke Recycle Bin.');
    }

    public function bulkDelete()
    {
        $ids = $this->request->getPost('ids'); if (empty($ids)) return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak ada data dipilih.']);
        $db = \Config\Database::connect(); $db->transStart();
        try {
            $deletedCount = 0;
            foreach ($ids as $id) {
                $rumah = $this->rumahModel->find($id); if (!$rumah) continue;
                $penerima = $this->penerimaModel->where('nik', $rumah['nik_pemilik'])->first(); $kondisi = $this->kondisiModel->find($id);
                $db->table('sys_trash')->insert(['entity_type' => 'RTLH', 'entity_id' => $rumah['nik_pemilik'], 'data_json' => json_encode(['penerima' => $penerima, 'rumah' => $rumah, 'kondisi' => $kondisi]), 'deleted_by' => session()->get('username'), 'created_at' => date('Y-m-d H:i:s')]);
                $this->kondisiModel->delete($id); $this->rumahModel->delete($id); if ($penerima) $this->penerimaModel->delete($penerima['nik']);
                $deletedCount++;
            }
            $db->transComplete(); $this->logActivity('Hapus Massal', 'RTLH', "Menghapus $deletedCount data RTLH");
            return $this->response->setJSON(['status' => 'success', 'message' => $deletedCount . ' data dihapus.']);
        } catch (\Exception $e) { $db->transRollback(); return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]); }
    }

    public function getApiDetail($id)
    {
        $rumah = $this->rumahModel->select('perumahan_rtlh_rumah.*, ST_AsText(lokasi_koordinat) as wkt')->find($id);
        if (!$rumah) return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan.']);
        
        $penerima = $this->penerimaModel->where('nik', $rumah['nik_pemilik'])->first();
        $kondisi = $this->kondisiModel->where('id_survei', $id)->first();
        
        return $this->response->setJSON([
            'status' => 'success',
            'rumah' => $rumah,
            'penerima' => $penerima,
            'kondisi' => $kondisi
        ]);
    }

    private function resolveMasterId($field, $post, $kategori, $previousValue = null)
    {
        $val = $post[$field] ?? null;
        if ($val === 'lainnya') {
            $manualText = trim($post[$field . '_manual'] ?? '');
            if (!empty($manualText)) {
                $existing = $this->refModel->where('kategori', $kategori)->where('nama_pilihan', $manualText)->first();
                if ($existing) return $existing['id'];
                $this->refModel->insert(['kategori' => $kategori, 'nama_pilihan' => $manualText]);
                return $this->refModel->getInsertID();
            }
            return $previousValue;
        }
        return $val ?: $previousValue;
    }
}
