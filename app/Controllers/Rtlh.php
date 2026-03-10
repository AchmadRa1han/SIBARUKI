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

        $query = $this->rumahModel->select('rtlh_rumah.*, rtlh_penerima.nama_kepala_keluarga as pemilik')
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
        
        $data = [
            'title' => 'Data RTLH',
            'rumah' => $rumah,
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
        $tahun = $this->request->getPost('tahun_bansos') ?? date('Y');
        $program = $this->request->getPost('program_bansos');

        if (!$id) {
            return redirect()->back()->with('error', 'ID Survei tidak valid.');
        }

        $db = \Config\Database::connect();
        
        // Ambil Data Saat Ini dengan ST_AsText untuk koordinat (agar json_encode tidak error)
        $rumah = $db->table('rtlh_rumah')
                    ->select('rtlh_rumah.*, ST_AsText(lokasi_koordinat) as lokasi_koordinat')
                    ->where('id_survei', $id)
                    ->get()->getRowArray();

        if (!$rumah) {
            return redirect()->back()->with('error', 'Data rumah tidak ditemukan.');
        }

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

            // 1. Update Status di Tabel Utama
            $db->table('rtlh_rumah')->where('id_survei', $id)->update([
                'status_bantuan' => 'Sudah Menerima',
                'tahun_bansos' => $tahun,
                'bantuan_perumahan' => $program ?: 'Bansos RTLH',
                'updated_at' => $now
            ]);

            // 2. Simpan ke Tabel Bansos (Rekap)
            $db->table('rtlh_bansos')->insert([
                'id_survei' => $id,
                'nik' => $rumah['nik_pemilik'],
                'nama_penerima' => $penerima['nama_kepala_keluarga'] ?? 'Unknown',
                'desa' => $rumah['desa'],
                'tahun_anggaran' => $tahun,
                'sumber_dana' => $program ?: 'Bansos RTLH',
                'keterangan' => 'Ditandai tuntas dari modul RTLH',
                'created_at' => $now,
                'updated_at' => $now
            ]);

            // 3. Simpan ke Tabel Histori Perubahan (Snapshot)
            $db->table('rtlh_history_perubahan')->insert([
                'id_survei' => $id,
                'nik' => $rumah['nik_pemilik'],
                'nama_penerima' => $penerima['nama_kepala_keluarga'] ?? 'Unknown',
                'sumber_bantuan' => $program ?: 'Bansos RTLH',
                'tahun_anggaran' => $tahun,
                'data_sebelum' => json_encode($snapshotSebelum),
                'keterangan' => 'Transformasi RTLH ke RLH',
                'created_at' => $now,
                'updated_at' => $now
            ]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                $error = $db->error();
                throw new \Exception('Database Error [' . $error['code'] . ']: ' . $error['message']);
            }

            $this->logActivity('Tuntas Bansos', 'RTLH', "Menandai rumah ID $id telah menerima bansos tahun $tahun");

            return redirect()->to('/rtlh?status=Sudah Menerima')->with('success', 'Data berhasil ditandai sebagai Tuntas (RLH). Silakan cek tab "Tuntas RLH".');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Gagal memproses data: ' . $e->getMessage());
        }
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

    public function rekapDesa()
    {
        if (session()->get('role_id') != 1) {
            return redirect()->to('/dashboard')->with('error', 'Hanya Admin yang dapat melihat data rekapan per desa.');
        }

        $db = \Config\Database::connect();
        
        $builder = $db->table('rtlh_rumah');
        $builder->select('desa, desa_id, COUNT(*) as total');
        $builder->groupBy('desa, desa_id');
        $builder->orderBy('desa', 'ASC');
        $rekap = $builder->get()->getResultArray();

        $data = [
            'title' => 'Rekapitulasi RTLH per Desa',
            'rekap' => $rekap
        ];

        return view('rtlh/rekap_desa', $data);
    }

    public function exportExcel()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('rtlh_rumah');
        $builder->select('rtlh_rumah.*, ST_AsText(lokasi_koordinat) as wkt_text, rtlh_penerima.nama_kepala_keluarga, rtlh_penerima.no_kk, rtlh_kondisi_rumah.st_atap, rtlh_kondisi_rumah.st_lantai, rtlh_kondisi_rumah.st_dinding');
        $builder->join('rtlh_penerima', 'rtlh_penerima.nik = rtlh_rumah.nik_pemilik', 'left');
        $builder->join('rtlh_kondisi_rumah', 'rtlh_kondisi_rumah.id_survei = rtlh_rumah.id_survei', 'left');
        $data = $builder->get()->getResultArray();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = ['ID', 'Pemilik', 'NIK', 'Alamat', 'Desa', 'Atap', 'Lantai', 'Dinding', 'WKT'];
        foreach ($headers as $key => $header) {
            $sheet->setCellValueByColumnAndRow($key + 1, 1, $header);
        }

        $rowNum = 2;
        foreach ($data as $row) {
            $sheet->setCellValue('A' . $rowNum, $row['id_survei']);
            $sheet->setCellValue('B' . $rowNum, $row['nama_kepala_keluarga'] ?? '-');
            $sheet->setCellValue('C' . $rowNum, $row['nik_pemilik']);
            $sheet->setCellValue('D' . $rowNum, $row['alamat_detail']);
            $sheet->setCellValue('E' . $rowNum, $row['desa']);
            $sheet->setCellValue('F' . $rowNum, $row['st_atap'] ?? '-');
            $sheet->setCellValue('G' . $rowNum, $row['st_lantai'] ?? '-');
            $sheet->setCellValue('H' . $rowNum, $row['st_dinding'] ?? '-');
            $sheet->setCellValue('I' . $rowNum, $row['wkt_text'] ?? '-');
            $rowNum++;
        }

        $sheet->getStyle('A1:I1')->getFont()->setBold(true);
        foreach (range('A', 'I') as $col) { $sheet->getColumnDimension($col)->setAutoSize(true); }

        $filename = 'Export_RTLH_' . date('YmdHis') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function importCsv()
    {
        if (!has_permission('create_rtlh')) return redirect()->back()->with('error', 'Izin ditolak.');
        
        $file = $this->request->getFile('csv_file');
        if (!$file || !$file->isValid()) return redirect()->back()->with('error', 'File tidak valid.');

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
            'jumlah_penghuni_jiwa' => ['*jumlah penghuni (jiwa)', 'jumlah penghuni (jiwa)'],
            'pendidikan_id'        => ['*pendidikan', 'pendidikan'],
            'pekerjaan_id'         => ['*pekerjaan', 'pekerjaan'],
            'penghasilan_per_bulan'=> ['*penghasilan perbulan', 'penghasilan perbulan'],
            'jenis_kelamin'        => ['*jenis kelamin', 'jenis kelamin'],
            'tempat_tanggal_lahir' => ['tempat tanggal lahir', 'ttl'],
            'kepemilikan_rumah'    => ['*kepemilikan rumah', 'kepemilikan rumah'],
            'aset_rumah_di_lokasi_lain' => ['*aset rumah di lokasi lain', 'aset rumah di lokasi lain', 'aset lain'],
            'kepemilikan_tanah'    => ['*kepemilikan tanah', 'kepemilikan tanah'],
            'bantuan_perumahan'    => ['*bantuan perumahan', 'bantuan perumahan'],
            'jumlah_anggota_keluarga' => ['*jumlah keluarga (kk)', 'jumlah keluarga (kk)', 'jumlah anggota keluarga'],
            'sumber_penerangan'    => ['*sumber penerangan', 'sumber penerangan'],
            'st_pondasi'           => ['*pondasi', 'pondasi'],
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
            'kamar_mandi_dan_jamban'=> ['*kamar mandi dan jamban', 'kamar mandi dan jamban', '*kamarmandi dan jamban'],
            'jenis_jamban_kloset'  => ['jenis jamban/ kloset', 'jenis jamban'],
            'jenis_tpa_tinja'      => ['jenis tpa tinja'],
        ];

        $db = \Config\Database::connect();
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

        $handle = fopen($file->getTempName(), 'r');
        $headerPos = [];
        $foundHeader = false;

        while (($line = fgetcsv($handle, 2000, ";")) !== FALSE) {
            $lineClean = array_map(function($v) { return strtolower(trim($v)); }, $line);
            if (in_array('*nik', $lineClean) || in_array('nik', $lineClean)) {
                $countPenerangan = 0;
                foreach ($lineClean as $index => $colName) {
                    $matched = false;
                    foreach ($aliasMap as $field => $aliases) {
                        if ($colName == $field || in_array($colName, $aliases)) {
                            if ($colName == '*sumber penerangan') {
                                if ($countPenerangan == 0) {
                                    $headerPos['sumber_penerangan'] = $index;
                                    $countPenerangan++;
                                } else {
                                    $headerPos['sumber_penerangan_detail'] = $index;
                                }
                            } else {
                                $headerPos[$field] = $index;
                            }
                            $matched = true;
                            break;
                        }
                    }
                }
                $foundHeader = true;
                break;
            }
        }

        if (!$foundHeader || !isset($headerPos['nik'])) {
            return redirect()->back()->with('error', 'Format Header CSV tidak dikenali.');
        }

        $db->transStart();
        $count = 0;

        while (($row = fgetcsv($handle, 2000, ";")) !== FALSE) {
            $nik = trim($row[$headerPos['nik']] ?? '');
            if (empty($nik)) continue;

            $getVal = function($field) use ($row, $headerPos) {
                return isset($headerPos[$field]) ? trim($row[$headerPos[$field]] ?? '') : null;
            };

            $findId = function($cat, $text) use ($refMap) {
                if (empty($text)) return null;
                $text = strtoupper(trim($text));
                if (isset($refMap[$cat][$text])) return $refMap[$cat][$text];
                if (isset($refMap[$cat])) {
                    foreach ($refMap[$cat] as $nama => $id) {
                        if (stripos($nama, $text) !== false || stripos($text, $nama) !== false) return $id;
                    }
                }
                return null;
            };

            $ttl = $getVal('tempat_tanggal_lahir');
            $tempat = null; $tanggal = null;
            if ($ttl && strpos($ttl, ',') !== false) {
                $parts = explode(',', $ttl);
                $tempat = trim($parts[0]);
                $tglRaw = trim($parts[1] ?? '');
                if ($tglRaw) {
                    $timestamp = strtotime($tglRaw);
                    if ($timestamp) $tanggal = date('Y-m-d', $timestamp);
                }
            }

            $dataPenerima = [
                'nik' => $nik,
                'nama_kepala_keluarga' => $getVal('nama_kepala_keluarga'),
                'no_kk' => $getVal('no_kk'),
                'tempat_lahir' => $tempat,
                'tanggal_lahir' => $tanggal,
                'jenis_kelamin' => (stripos($getVal('jenis_kelamin') ?? '', 'PEREMPUAN') !== false) ? 'P' : 'L',
                'pendidikan_id' => $findId('PENDIDIKAN', $getVal('pendidikan_id')),
                'pekerjaan_id' => $findId('PEKERJAAN', $getVal('pekerjaan_id')),
                'penghasilan_per_bulan' => $getVal('penghasilan_per_bulan'),
                'jumlah_anggota_keluarga' => (int) preg_replace('/[^0-9]/', '', $getVal('jumlah_anggota_keluarga') ?? '0'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            if ($this->penerimaModel->find($nik)) {
                $this->penerimaModel->update($nik, $dataPenerima);
            } else {
                $dataPenerima['created_at'] = date('Y-m-d H:i:s');
                $this->penerimaModel->insert($dataPenerima);
            }

            $desaName = strtoupper(trim($getVal('desa') ?? ''));
            $this->rumahModel->set([
                'nik_pemilik'   => $nik,
                'desa'          => $getVal('desa'),
                'desa_id'       => $desaLookup[$desaName] ?? null,
                'alamat_detail' => $getVal('alamat_detail'),
                'jenis_kawasan' => $getVal('jenis_kawasan'),
                'fungsi_ruang'  => $getVal('fungsi_ruang'),
                'kepemilikan_rumah' => $getVal('kepemilikan_rumah'),
                'aset_rumah_di_lokasi_lain' => $getVal('aset_rumah_di_lokasi_lain'),
                'kepemilikan_tanah' => $getVal('kepemilikan_tanah'),
                'bantuan_perumahan' => $getVal('bantuan_perumahan'),
                'luas_rumah_m2' => preg_replace('/[^0-9.]/', '', $getVal('luas_rumah_m2') ?? '0'),
                'luas_lahan_m2' => preg_replace('/[^0-9.]/', '', $getVal('luas_lahan_m2') ?? '0'),
                'jumlah_penghuni_jiwa' => (int) preg_replace('/[^0-9]/', '', $getVal('jumlah_penghuni_jiwa') ?? '0'),
                'sumber_penerangan' => $getVal('sumber_penerangan'),
                'sumber_penerangan_detail' => $getVal('sumber_penerangan_detail'),
                'sumber_air_minum' => $getVal('sumber_air_minum'),
                'jarak_sam_ke_tpa_tinja' => $getVal('jarak_sam_ke_tpa_tinja'),
                'kamar_mandi_dan_jamban' => $getVal('kamar_mandi_dan_jamban'),
                'jenis_jamban_kloset' => $getVal('jenis_jamban_kloset'),
                'jenis_tpa_tinja' => $getVal('jenis_tpa_tinja'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
            $existingRumah = $this->rumahModel->where('nik_pemilik', $nik)->first();
            if ($existingRumah) {
                $this->rumahModel->update($existingRumah['id_survei']);
                $surveiId = $existingRumah['id_survei'];
            } else {
                $this->rumahModel->set('created_at', date('Y-m-d H:i:s'));
                $this->rumahModel->insert();
                $surveiId = $this->rumahModel->getInsertID();
            }

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
            
            if ($this->kondisiModel->find($surveiId)) {
                $this->kondisiModel->update($surveiId, $dataKondisi);
            } else {
                $dataKondisi['created_at'] = date('Y-m-d H:i:s');
                $this->kondisiModel->insert($dataKondisi);
            }

            $count++;
        }

        fclose($handle);
        $db->transComplete();

        return redirect()->to('/rtlh')->with('success', "Import Selesai: $count data RTLH berhasil diproses.");
    }

    public function detail($id)
    {
        if (!has_permission('view_rtlh_detail')) return redirect()->to('/rtlh')->with('message', 'Akses ditolak.');
        
        $rumah = $this->rumahModel->find($id);
        if (!$rumah) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $kondisi = $this->kondisiModel->where('id_survei', $id)->first();
        $penerima = $this->penerimaModel->where('nik', $rumah['nik_pemilik'])->first();

        $data = [
            'title' => 'Detail RTLH - ' . ($penerima['nama_kepala_keluarga'] ?? $rumah['nik_pemilik']),
            'rumah' => $rumah,
            'kondisi' => $kondisi,
            'penerima' => $penerima,
            'ref' => $this->refModel->getAllMapped()
        ];

        return view('rtlh/detail', $data);
    }

    public function create()
    {
        $db = \Config\Database::connect();
        
        $allRefs = $this->refModel->findAll();
        $master = [];
        foreach ($allRefs as $ref) {
            $master[$ref['kategori']][] = $ref;
        }

        $all_desa = $db->table('kode_desa')->select('desa_id, desa_nama')->get()->getResultArray();

        $data = [
            'title' => 'Tambah RTLH',
            'master' => $master,
            'all_desa' => $all_desa
        ];

        return view('rtlh/create', $data);
    }

    public function store()
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $post = $this->request->getPost();
        $nik = $post['nik'];

        $dataPenerima = [
            'nik' => $nik,
            'no_kk' => $post['no_kk'] ?? null,
            'nama_kepala_keluarga' => $post['nama_kepala_keluarga'] ?? null,
            'tempat_lahir' => $post['tempat_lahir'] ?? null,
            'tanggal_lahir' => $post['tanggal_lahir'] ?? null,
            'jenis_kelamin' => $post['jenis_kelamin'] ?? null,
            'pendidikan_id' => $post['pendidikan_id'] ?: null,
            'pekerjaan_id' => $post['pekerjaan_id'] ?: null,
            'jumlah_anggota_keluarga' => $post['jumlah_anggota_keluarga'] ?? null,
            'penghasilan_per_bulan' => $post['penghasilan_per_bulan'] ?? null,
        ];

        if ($this->penerimaModel->find($nik)) $this->penerimaModel->update($nik, $dataPenerima);
        else $this->penerimaModel->insert($dataPenerima);

        $this->rumahModel->set([
            'nik_pemilik' => $nik,
            'desa' => $post['desa'] ?? null,
            'desa_id' => $post['desa_id'] ?? null,
            'jenis_kawasan' => $post['jenis_kawasan'] ?? null,
            'alamat_detail' => $post['alamat_detail'] ?? null,
            'kepemilikan_rumah' => $post['kepemilikan_rumah'] ?? null,
            'kepemilikan_tanah' => $post['kepemilikan_tanah'] ?? null,
            'fungsi_ruang' => $post['fungsi_ruang'] ?? null,
            'aset_rumah_di_lokasi_lain' => $post['aset_rumah_di_lokasi_lain'] ?? null,
            'bantuan_perumahan' => $post['bantuan_perumahan'] ?? null,
            'jumlah_penghuni_jiwa' => $post['jumlah_penghuni_jiwa'] ?? null,
            'luas_rumah_m2' => $post['luas_rumah_m2'] ?: null,
            'luas_lahan_m2' => $post['luas_lahan_m2'] ?: null,
            'sumber_penerangan' => $post['sumber_penerangan'] ?? null,
            'sumber_penerangan_detail' => $post['sumber_penerangan_detail'] ?? null,
            'sumber_air_minum' => $post['sumber_air_minum'] ?? null,
            'jarak_sam_ke_tpa_tinja' => $post['jarak_sam_ke_tpa_tinja'] ?? null,
            'kamar_mandi_dan_jamban' => $post['kamar_mandi_dan_jamban'] ?? null,
            'jenis_jamban_kloset' => $post['jenis_jamban_kloset'] ?? null,
            'jenis_tpa_tinja' => $post['jenis_tpa_tinja'] ?? null,
        ]);

        if (!empty($post['lokasi_koordinat'])) {
            $this->rumahModel->set('lokasi_koordinat', "ST_GeomFromText('{$post['lokasi_koordinat']}')", false);
        }
        
        $this->rumahModel->insert();
        $surveiId = $this->rumahModel->getInsertID();

        $this->kondisiModel->insert([
            'id_survei' => $surveiId,
            'st_pondasi' => $post['st_pondasi'] ?: null,
            'st_kolom' => $post['st_kolom'] ?: null,
            'st_balok' => $post['st_balok'] ?: null,
            'st_sloof' => $post['st_sloof'] ?: null,
            'st_rangka_atap' => $post['st_rangka_atap'] ?: null,
            'st_plafon' => $post['st_plafon'] ?: null,
            'st_jendela' => $post['st_jendela'] ?: null,
            'st_ventilasi' => $post['st_ventilasi'] ?: null,
            'mat_atap' => $post['mat_atap'] ?: null,
            'st_atap' => $post['st_atap'] ?: null,
            'mat_dinding' => $post['mat_dinding'] ?: null,
            'st_dinding' => $post['st_dinding'] ?: null,
            'mat_lantai' => $post['mat_lantai'] ?: null,
            'st_lantai' => $post['st_lantai'] ?: null,
        ]);

        $db->transComplete();

        return redirect()->to('/rtlh')->with('success', 'Data RTLH berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $rumah = $this->rumahModel->find($id);
        if (!$rumah) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $penerima = $this->penerimaModel->where('nik', $rumah['nik_pemilik'])->first();
        $kondisi = $this->kondisiModel->where('id_survei', $id)->first();

        $allRefs = $this->refModel->findAll();
        $master = [];
        foreach ($allRefs as $ref) {
            $master[$ref['kategori']][] = $ref;
        }

        $data = [
            'title' => 'Edit RTLH',
            'rumah' => $rumah,
            'penerima' => $penerima,
            'kondisi' => $kondisi,
            'master' => $master
        ];

        return view('rtlh/edit', $data);
    }

    public function update($id)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $post = $this->request->getPost();

        $this->rumahModel->set([
            'desa' => $post['desa'] ?? null,
            'desa_id' => $post['desa_id'] ?? null,
            'jenis_kawasan' => $post['jenis_kawasan'] ?? null,
            'alamat_detail' => $post['alamat_detail'] ?? null,
            'kepemilikan_rumah' => $post['kepemilikan_rumah'] ?? null,
            'kepemilikan_tanah' => $post['kepemilikan_tanah'] ?? null,
            'fungsi_ruang' => $post['fungsi_ruang'] ?? null,
            'aset_rumah_di_lokasi_lain' => $post['aset_rumah_di_lokasi_lain'] ?? null,
            'bantuan_perumahan' => $post['bantuan_perumahan'] ?? null,
            'jumlah_penghuni_jiwa' => $post['jumlah_penghuni_jiwa'] ?? null,
            'luas_rumah_m2' => $post['luas_rumah_m2'] ?? null,
            'luas_lahan_m2' => $post['luas_lahan_m2'] ?? null,
            'sumber_penerangan' => $post['sumber_penerangan'] ?? null,
            'sumber_penerangan_detail' => $post['sumber_penerangan_detail'] ?? null,
            'sumber_air_minum' => $post['sumber_air_minum'] ?? null,
            'jarak_sam_ke_tpa_tinja' => $post['jarak_sam_ke_tpa_tinja'] ?? null,
            'kamar_mandi_dan_jamban' => $post['kamar_mandi_dan_jamban'] ?? null,
            'jenis_jamban_kloset' => $post['jenis_jamban_kloset'] ?? null,
            'jenis_tpa_tinja' => $post['jenis_tpa_tinja'] ?? null,
        ]);

        if (!empty($post['lokasi_koordinat'])) {
            $this->rumahModel->set('lokasi_koordinat', "ST_GeomFromText('{$post['lokasi_koordinat']}')", false);
        }

        $this->rumahModel->update($id);

        $rumah = $this->rumahModel->find($id);
        $dataPenerima = [
            'nik' => $post['nik'] ?? $rumah['nik_pemilik'],
            'no_kk' => $post['no_kk'] ?? null,
            'nama_kepala_keluarga' => $post['nama_kepala_keluarga'] ?? null,
            'tempat_lahir' => $post['tempat_lahir'] ?? null,
            'tanggal_lahir' => $post['tanggal_lahir'] ?? null,
            'jenis_kelamin' => $post['jenis_kelamin'] ?? null,
            'pendidikan_id' => $post['pendidikan_id'] ?? null,
            'pekerjaan_id' => $post['pekerjaan_id'] ?? null,
            'jumlah_anggota_keluarga' => $post['jumlah_anggota_keluarga'] ?? null,
            'penghasilan_per_bulan' => $post['penghasilan_per_bulan'] ?? null,
        ];
        $this->penerimaModel->where('nik', $rumah['nik_pemilik'])->set($dataPenerima)->update();

        $dataKondisi = [
            'st_pondasi' => $post['st_pondasi'] ?? null,
            'st_kolom' => $post['st_kolom'] ?? null,
            'st_balok' => $post['st_balok'] ?? null,
            'st_sloof' => $post['st_sloof'] ?? null,
            'st_rangka_atap' => $post['st_rangka_atap'] ?? null,
            'st_plafon' => $post['st_plafon'] ?? null,
            'st_jendela' => $post['st_jendela'] ?? null,
            'st_ventilasi' => $post['st_ventilasi'] ?? null,
            'mat_atap' => $post['mat_atap'] ?? null,
            'st_atap' => $post['st_atap'] ?? null,
            'mat_dinding' => $post['mat_dinding'] ?? null,
            'st_dinding' => $post['st_dinding'] ?? null,
            'mat_lantai' => $post['mat_lantai'] ?? null,
            'st_lantai' => $post['st_lantai'] ?? null,
        ];
        $this->kondisiModel->where('id_survei', $id)->set($dataKondisi)->update();

        $db->transComplete();

        if ($db->transStatus() === FALSE) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Gagal memperbarui data RTLH terpadu.');
        }

        return redirect()->to('/rtlh/detail/' . $id)->with('success', 'Data RTLH berhasil diperbarui.');
    }

    public function delete($id)
    {
        $this->rumahModel->delete($id);
        return redirect()->to('/rtlh')->with('success', 'Data RTLH berhasil dihapus.');
    }

    public function logExport($id)
    {
        $rumah = $this->rumahModel->where('id', $id)->get()->getRowArray();
        $this->logActivity('Ekspor PDF', 'RTLH', 'Mendownload laporan RTLH untuk NIK: ' . ($rumah['nik_pemilik'] ?? 'Unknown'));
        return $this->response->setJSON(['status' => 'success']);
    }
}
