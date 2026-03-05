<?php

namespace App\Controllers;

use App\Models\RtlhPenerimaModel;
use App\Models\RumahRtlhModel;
use App\Models\KondisiRumahModel;
use App\Models\RefMasterModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Rtlh extends BaseController
{
    protected $penerimaModel;
    protected $rumahModel;
    protected $kondisiModel;
    protected $refModel;

    public function __construct()
    {
        $this->penerimaModel = new RtlhPenerimaModel();
        $this->rumahModel = new RumahRtlhModel();
        $this->kondisiModel = new KondisiRumahModel();
        $this->refModel = new RefMasterModel();
    }

    public function index()
    {
        $keyword = $this->request->getGet('keyword');
        $perPage = $this->request->getGet('per_page') ?? 10;

        $query = $this->rumahModel->select('rtlh_rumah.*, rtlh_penerima.nama_kepala_keluarga as pemilik')
                                  ->join('rtlh_penerima', 'rtlh_penerima.nik = rtlh_rumah.nik_pemilik', 'left');

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
            'rumah_all' => $this->rumahModel->select('rtlh_rumah.*, rtlh_penerima.nama_kepala_keluarga as pemilik')
                                           ->join('rtlh_penerima', 'rtlh_penerima.nik = rtlh_rumah.nik_pemilik', 'left')
                                           ->findAll(),
            'pager' => $this->rumahModel->pager,
            'perPage' => $perPage,
            'keyword' => $keyword,
            'total_verifikasi' => $this->rumahModel->countAllResults(false),
        ];

        return view('rtlh/index', $data);
    }

    public function exportExcel()
    {
        // Gabungkan data dari 3 tabel untuk ekspor lengkap
        $db = \Config\Database::connect();
        $builder = $db->table('rtlh_rumah');
        $builder->select('rtlh_rumah.*, rtlh_penerima.nama_kepala_keluarga as pemilik, rtlh_penerima.no_kk, rtlh_kondisi_rumah.st_atap, rtlh_kondisi_rumah.st_lantai, rtlh_kondisi_rumah.st_dinding');
        $builder->join('rtlh_penerima', 'rtlh_penerima.nik = rtlh_rumah.nik_pemilik', 'left');
        $builder->join('rtlh_kondisi_rumah', 'rtlh_kondisi_rumah.id_survei = rtlh_rumah.id_survei', 'left');
        $data = $builder->get()->getResultArray();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = ['ID', 'Pemilik', 'NIK', 'Alamat', 'Desa', 'Kecamatan', 'Atap', 'Lantai', 'Dinding', 'WKT'];
        foreach ($headers as $key => $header) {
            $sheet->setCellValueByColumnAndRow($key + 1, 1, $header);
        }

        $rowNum = 2;
        foreach ($data as $row) {
            $sheet->setCellValue('A' . $rowNum, $row['id_survei']);
            $sheet->setCellValue('B' . $rowNum, $row['pemilik'] ?? '-');
            $sheet->setCellValue('C' . $rowNum, $row['nik_pemilik']);
            $sheet->setCellValue('D' . $rowNum, $row['alamat_detail']);
            $sheet->setCellValue('E' . $rowNum, $row['desa']);
            $sheet->setCellValue('F' . $rowNum, $row['desa_id']); // Assuming kecamatan is not directly in rtlh_rumah
            $sheet->setCellValue('G' . $rowNum, $row['st_atap'] ?? '-');
            $sheet->setCellValue('H' . $rowNum, $row['st_lantai'] ?? '-');
            $sheet->setCellValue('I' . $rowNum, $row['st_dinding'] ?? '-');
            $sheet->setCellValue('J' . $rowNum, $row['lokasi_koordinat']);
            $rowNum++;
        }

        $sheet->getStyle('A1:J1')->getFont()->setBold(true);
        foreach (range('A', 'J') as $col) { $sheet->getColumnDimension($col)->setAutoSize(true); }

        $filename = 'Export_RTLH_Lengkap_' . date('YmdHis') . '.xlsx';
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
            'nik'                  => ['nik', '*nik'],
            'nama_kepala_keluarga' => ['nama kepala rumah tangga', '*nama kepala rumah tangga'],
            'no_kk'                => ['no.kk', 'no kk'],
            'desa'                 => ['desa', '*desa'],
            'alamat_detail'        => ['alamat', '*alamat'],
            'jenis_kawasan'        => ['jenis kawasan'],
            'fungsi_ruang'         => ['fungsi ruang'],
            'luas_rumah_m2'        => ['luas rumah (m2)', '*luas rumah (m2)'],
            'luas_lahan_m2'        => ['luah lahan (m2)', 'luas lahan (m2)'],
            'jumlah_penghuni_jiwa' => ['jumlah penghuni (jiwa)', '*jumlah penghuni (jiwa)'],
            'pendidikan_id'        => ['pendidikan', '*pendidikan'],
            'pekerjaan_id'         => ['pekerjaan', '*pekerjaan'],
            'penghasilan_per_bulan'=> ['penghasilan perbulan', '*penghasilan perbulan'],
            'jenis_kelamin'        => ['jenis kelamin', '*jenis kelamin'],
            'tempat_tanggal_lahir' => ['tempat tanggal lahir'],
            'kepemilikan_rumah'    => ['kepemilikan rumah', '*kepemilikan rumah'],
            'kepemilikan_tanah'    => ['kepemilikan tanah', '*kepemilikan tanah'],
            'bantuan_perumahan'    => ['bantuan perumahan', '*bantuan perumahan'],
            'st_pondasi'           => ['pondasi', '*pondasi'],
            'st_kolom'             => ['kondisi kolom', '*kondisi kolom'],
            'st_balok'             => ['kondisi balok', 'balok'],
            'st_sloof'             => ['kondisi sloof', 'sloof'],
            'st_rangka_atap'       => ['kondisi rangka atap', '*kondisi rangka atap'],
            'st_plafon'            => ['kondisi plafon', 'plafon'],
            'st_jendela'           => ['jendela', '*jendela'],
            'st_ventilasi'         => ['ventilasi', '*ventilasi'],
            'mat_lantai'           => ['material lantai terluas', '*material lantai terluas'],
            'st_lantai'            => ['kondisi lantai', '*kondisi lantai'],
            'mat_dinding'          => ['material dinding terluas', '*material dinding terluas'],
            'st_dinding'           => ['kondisi dinding', '*kondisi dinding'],
            'mat_atap'             => ['material atap terluas', '*material atap terluas'],
            'st_atap'              => ['kondisi atap', '*kondisi atap'],
            'sumber_air_minum'     => ['sumber air minum', '*sumber air minum'],
            'jarak_sam_ke_tpa_tinja'=> ['jarak sam ke tpa tinja', '*jarak sam ke tpa tinja'],
            'kamar_mandi_dan_jamban'=> ['kamar mandi dan jamban', '*kamar mandi dan jamban'],
            'jenis_jamban_kloset'  => ['jenis jamban/ kloset'],
            'jenis_tpa_tinja'      => ['jenis tpa tinja'],
        ];

        $db = \Config\Database::connect();
        $refMap = $this->refModel->findAll(); 

        $handle = fopen($file->getTempName(), 'r');
        $headerPos = [];
        $foundHeader = false;

        // Skip metadata dan cari header sesungguhnya
        while (($line = fgetcsv($handle, 2000, ";")) !== FALSE) {
            $lineStr = implode(' ', $line);
            if (stripos($lineStr, 'NIK') !== false) {
                foreach ($line as $index => $colName) {
                    $cleanCol = strtolower(trim($colName));
                    foreach ($aliasMap as $field => $aliases) {
                        if ($cleanCol == $field || in_array($cleanCol, $aliases)) {
                            if ($cleanCol == '*sumber penerangan' && isset($headerPos['sumber_penerangan'])) {
                                $headerPos['sumber_penerangan_detail'] = $index;
                            } else {
                                $headerPos[$field] = $index;
                            }
                        }
                    }
                }
                $foundHeader = true;
                break;
            }
        }

        if (!$foundHeader || !isset($headerPos['nik'])) {
            return redirect()->back()->with('error', 'Header CSV tidak valid atau kolom NIK tidak ditemukan.');
        }

        $db->transStart();
        $count = 0;

        while (($row = fgetcsv($handle, 2000, ";")) !== FALSE) {
            if (empty($row[$headerPos['nik']])) continue;

            $getVal = function($field) use ($row, $headerPos) {
                $v = isset($headerPos[$field]) ? trim($row[$headerPos[$field]]) : null;
                if ($v && in_array($field, ['luas_rumah_m2', 'luas_lahan_m2'])) {
                    $v = preg_replace('/[^0-9.]/', '', $v);
                }
                return $v;
            };

            $findRefId = function($text) use ($refMap) {
                if (empty($text)) return null;
                $text = strtoupper(trim($text));
                foreach ($refMap as $r) {
                    $opt = strtoupper($r['nama_pilihan']);
                    if ($opt == $text || stripos($opt, $text) !== false || stripos($text, $opt) !== false) {
                        return $r['id'];
                    }
                }
                return null;
            };

            $nik = $getVal('nik');
            
            $ttl = $getVal('tempat_tanggal_lahir');
            $tempat = null; $tanggal = null;
            if ($ttl && strpos($ttl, ',') !== false) {
                $parts = explode(',', $ttl);
                $tempat = trim($parts[0]);
                $tglStr = trim($parts[1] ?? '');
                if ($tglStr) $tanggal = date('Y-m-d', strtotime($tglStr));
            }

            $dataPenerima = [
                'nik' => $nik,
                'nama_kepala_keluarga' => $getVal('nama_kepala_keluarga'),
                'no_kk' => $getVal('no_kk'),
                'tempat_lahir' => $tempat,
                'tanggal_lahir' => $tanggal,
                'jenis_kelamin' => (stripos($getVal('jenis_kelamin'), 'PEREMPUAN') !== false) ? 'P' : 'L',
                'pendidikan_id' => $findRefId($getVal('pendidikan_id')),
                'pekerjaan_id' => $findRefId($getVal('pekerjaan_id')),
                'penghasilan_per_bulan' => $getVal('penghasilan_per_bulan'),
            ];
            
            if ($this->penerimaModel->find($nik)) $this->penerimaModel->update($nik, $dataPenerima);
            else $this->penerimaModel->insert($dataPenerima);

            $this->rumahModel->set([
                'nik_pemilik' => $nik,
                'desa' => $getVal('desa'),
                'alamat_detail' => $getVal('alamat_detail'),
                'jenis_kawasan' => $getVal('jenis_kawasan'),
                'fungsi_ruang' => $getVal('fungsi_ruang'),
                'kepemilikan_rumah' => $getVal('kepemilikan_rumah'),
                'kepemilikan_tanah' => $getVal('kepemilikan_tanah'),
                'bantuan_perumahan' => $getVal('bantuan_perumahan'),
                'luas_rumah_m2' => $getVal('luas_rumah_m2') ?: 0,
                'luas_lahan_m2' => $getVal('luas_lahan_m2') ?: 0,
                'jumlah_penghuni_jiwa' => $getVal('jumlah_penghuni_jiwa') ?: 0,
                'sumber_penerangan' => isset($headerPos['sumber_penerangan']) ? ($row[$headerPos['sumber_penerangan']] ?? null) : null,
                'sumber_penerangan_detail' => isset($headerPos['sumber_penerangan_detail']) ? ($row[$headerPos['sumber_penerangan_detail']] ?? null) : null,
                'sumber_air_minum' => $getVal('sumber_air_minum'),
                'jarak_sam_ke_tpa_tinja' => $getVal('jarak_sam_ke_tpa_tinja'),
                'kamar_mandi_dan_jamban' => $getVal('kamar_mandi_dan_jamban'),
                'jenis_jamban_kloset' => $getVal('jenis_jamban_kloset'),
                'jenis_tpa_tinja' => $getVal('jenis_tpa_tinja'),
            ]);
            $this->rumahModel->insert();
            $surveiId = $this->rumahModel->getInsertID();

            $this->kondisiModel->insert([
                'id_survei'  => $surveiId,
                'st_pondasi' => $findRefId($getVal('st_pondasi')),
                'st_kolom'   => $findRefId($getVal('st_kolom')),
                'st_balok'   => $findRefId($getVal('st_balok')),
                'st_sloof'   => $findRefId($getVal('st_sloof')),
                'st_rangka_atap' => $findRefId($getVal('st_rangka_atap')),
                'st_plafon'  => $findRefId($getVal('st_plafon')),
                'st_jendela' => $findRefId($getVal('st_jendela')),
                'st_ventilasi' => $findRefId($getVal('st_ventilasi')),
                'mat_lantai' => $findRefId($getVal('mat_lantai')),
                'st_lantai'  => $findRefId($getVal('st_lantai')),
                'mat_dinding' => $findRefId($getVal('mat_dinding')),
                'st_dinding' => $findRefId($getVal('st_dinding')),
                'mat_atap'   => $findRefId($getVal('mat_atap')),
                'st_atap'    => $findRefId($getVal('st_atap')),
            ]);

            $count++;
        }

        fclose($handle);
        $db->transComplete();

        return redirect()->to('/rtlh')->with('success', "Import Berhasil: $count data RTLH telah diproses.");
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
            'bantuan_per_perumahan' => $post['bantuan_perumahan'] ?? null,
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
