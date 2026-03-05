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

        $content = file_get_contents($file->getTempName());
        $countSemicolon = substr_count($content, ';');
        $countComma = substr_count($content, ',');
        $delimiter = ($countSemicolon > $countComma) ? ';' : ',';

        $lines = explode("\n", str_replace("\r", "", $content));
        $count = 0;
        
        $db = \Config\Database::connect();
        $db->transStart();

        foreach ($lines as $line) {
            if (empty(trim($line))) continue;
            $row = str_getcsv($line, $delimiter);
            
            // Minimal kolom: Nama, NIK, Alamat, Desa, Kec (Sesuaikan urutan CSV)
            if (count($row) < 5 || stripos($line, 'PEMILIK') !== false || !is_numeric($row[1] ?? '')) continue;

            $nik = trim($row[1]);
            $nama = trim($row[0]);

            // 1. Simpan/Update ke rtlh_rumah
            $existingRumah = $this->rumahModel->where('nik_pemilik', $nik)->first();
            $surveiId = null;

            $dataRumah = [
                'nik_pemilik' => $nik,
                'alamat_detail' => $row[2] ?? '-',
                'desa' => $row[3] ?? '-',
                'lokasi_koordinat' => $row[6] ?? null,
            ];

            if ($existingRumah) {
                $this->rumahModel->update($existingRumah['id_survei'], $dataRumah);
                $surveiId = $existingRumah['id_survei'];
            } else {
                $surveiId = $this->rumahModel->insert($dataRumah);
            }

            // 2. Simpan/Update ke rtlh_penerima
            $dataPenerima = [
                'nik' => $nik,
                'nama_kepala_keluarga' => $nama,
            ];
            $existingPenerima = $this->penerimaModel->where('nik', $nik)->first();
            if ($existingPenerima) $this->penerimaModel->update($nik, $dataPenerima);
            else $this->penerimaModel->insert($dataPenerima);

            // 3. Simpan/Update ke rtlh_kondisi_rumah
            if (isset($row[8]) || isset($row[9]) || isset($row[10])) {
                $dataKondisi = [
                    'id_survei' => $surveiId,
                    'st_atap' => $row[8] ?? '-',
                    'st_lantai' => $row[9] ?? '-',
                    'st_dinding' => $row[10] ?? '-',
                ];
                $existingKondisi = $this->kondisiModel->where('id_survei', $surveiId)->first();
                if ($existingKondisi) $this->kondisiModel->update($surveiId, $dataKondisi);
                else $this->kondisiModel->insert($dataKondisi);
            }

            $count++;
        }

        $db->transComplete();

        if ($db->transStatus() === FALSE) {
            return redirect()->back()->with('error', 'Gagal memproses data ke beberapa tabel.');
        }

        return redirect()->to('/rtlh')->with('success', "$count data RTLH berhasil diproses.");
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
        return view('rtlh/create', ['title' => 'Tambah RTLH']);
    }

    public function store()
    {
        $this->rumahModel->insert($this->request->getPost());
        return redirect()->to('/rtlh')->with('success', 'Data RTLH berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $rumah = $this->rumahModel->find($id);
        if (!$rumah) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $penerima = $this->penerimaModel->where('nik', $rumah['nik_pemilik'])->first();
        $kondisi = $this->kondisiModel->where('id_survei', $id)->first();

        // Get all master reference data grouped by category
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

        // 1. Update rtlh_rumah
        $dataRumah = [
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
            'lokasi_koordinat' => $post['lokasi_koordinat'] ?? null,
        ];
        $this->rumahModel->update($id, $dataRumah);

        // 2. Update rtlh_penerima
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

        // 3. Update rtlh_kondisi_rumah
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
            return redirect()->back()->with('error', 'Gagal memperbarui data RTLH.');
        }

        return redirect()->to('/rtlh')->with('success', 'Data RTLH berhasil diperbarui.');
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
