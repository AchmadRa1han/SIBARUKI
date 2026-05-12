<?php

namespace App\Controllers;

use App\Models\WilayahKumuhModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class WilayahKumuh extends BaseController
{
    protected $kumuhModel;

    public function __construct()
    {
        $this->kumuhModel = new WilayahKumuhModel();
    }

    public function index()
    {
        $keyword = $this->request->getGet('keyword');
        $perPage = $this->request->getGet('per_page') ?? 10;

        $query = $this->kumuhModel;
        if ($keyword) {
            $query = $query->groupStart()
                ->like('Kelurahan', $keyword)
                ->orLike('Kawasan', $keyword)
                ->groupEnd();
        }

        $kumuh = $query->paginate($perPage, 'group1');
        
        $data = [
            'title' => 'Data Wilayah Kumuh',
            'kumuh' => $kumuh,
            'kumuh_all' => $this->kumuhModel->findAll(),
            'pager' => $this->kumuhModel->pager,
            'perPage' => $perPage,
            'keyword' => $keyword,
        ];

        return view('wilayah_kumuh/index', $data);
    }

    public function exportExcel()
    {
        $data = $this->kumuhModel->findAll();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'FID', 'Provinsi', 'Kode Prov', 'Kab/Kota', 'Kode Kab', 
            'Kecamatan', 'Kode Kec', 'Kelurahan', 'Kode Kel', 
            'RT/RW', 'Luas (Ha)', 'Skor', 'Sumber Data', 
            'SK Kumuh', 'Kawasan', 'WKT'
        ];

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }

        $rowNum = 2;
        foreach ($data as $row) {
            $sheet->setCellValue('A' . $rowNum, $row['FID']);
            $sheet->setCellValue('B' . $rowNum, $row['Provinsi']);
            $sheet->setCellValue('C' . $rowNum, $row['Kode_Prov']);
            $sheet->setCellValue('D' . $rowNum, $row['Kab_Kota']);
            $sheet->setCellValue('E' . $rowNum, $row['Kode_Kab']);
            $sheet->setCellValue('F' . $rowNum, $row['Kecamatan']);
            $sheet->setCellValue('G' . $rowNum, $row['Kode_Kec']);
            $sheet->setCellValue('H' . $rowNum, $row['Kelurahan']);
            $sheet->setCellValue('I' . $rowNum, $row['Kode_Kel']);
            $sheet->setCellValue('J' . $rowNum, $row['Kode_RT_RW']);
            $sheet->setCellValue('K' . $rowNum, $row['Luas_kumuh']);
            $sheet->setCellValue('L' . $rowNum, $row['skor_kumuh']);
            $sheet->setCellValue('M' . $rowNum, $row['Sumber_data']);
            $sheet->setCellValue('N' . $rowNum, $row['Sk_Kumuh']);
            $sheet->setCellValue('O' . $rowNum, $row['Kawasan']);
            $sheet->setCellValue('P' . $rowNum, $row['WKT']);
            $rowNum++;
        }

        $sheet->getStyle('A1:P1')->getFont()->setBold(true);
        foreach (range('A', 'P') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Catat Log
        $this->logActivity('Export Excel', 'Wilayah Kumuh', "Mengekspor " . count($data) . " data Wilayah Kumuh Lengkap");

        $filename = 'Export_Lengkap_Wilayah_Kumuh_' . date('YmdHis') . '.xlsx';
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

        $handle = fopen($file->getTempName(), 'r');
        
        // Deteksi Delimiter
        $firstLine = fgets($handle);
        $delimiter = (substr_count($firstLine, ';') > substr_count($firstLine, ',')) ? ';' : ',';
        rewind($handle);

        $count = 0;
        $db = \Config\Database::connect();

        // Reset Auto Increment jika tabel kosong
        if ($db->table('wilayah_kumuh')->countAllResults() === 0) {
            $db->query("ALTER TABLE wilayah_kumuh AUTO_INCREMENT = 1");
        }

        $db->transStart();

        try {
            while (($row = fgetcsv($handle, 10000, $delimiter)) !== FALSE) {
                // Lewati header (berisi kata 'WKT' atau 'Kawasan')
                if (count($row) < 10 || stripos(implode(' ', $row), 'WKT') !== false) {
                    continue;
                }

                /* 
                   Berdasarkan analisis file Delineasi_Kumuh_AR.csv:
                   Index 0: WKT
                   Index 5: KECAMATAN
                   Index 7: KELURAHAN
                   Index 9: KODE_RT_RW
                   Index 10: LUAS_KUMUH
                   Index 11: skor_kumuh
                   Index 12: SUMBR_DATA
                   Index 13: SK_KUMUH
                   Index 14: Kawasan
                */

                $this->kumuhModel->insert([
                    'WKT'         => $row[0] ?? null,
                    'Provinsi'    => trim($row[1] ?? 'Sulawesi Selatan'),
                    'Kode_Prov'   => trim($row[2] ?? '73'),
                    'Kab_Kota'    => trim($row[3] ?? 'Sinjai'),
                    'Kode_Kab'    => trim($row[4] ?? '07'),
                    'Kecamatan'   => trim($row[5] ?? '-'),
                    'Kode_Kec'    => trim($row[6] ?? '-'),
                    'Kelurahan'   => trim($row[7] ?? '-'),
                    'Kode_Kel'    => trim($row[8] ?? '-'),
                    'Kode_RT_RW'  => trim($row[9] ?? '-'),
                    'Luas_kumuh'  => (float)($row[10] ?? '0'),
                    'skor_kumuh'  => (float)($row[11] ?? '0'),
                    'Sumber_data' => trim($row[12] ?? '-'),
                    'Sk_Kumuh'    => trim($row[13] ?? '-'),
                    'Kawasan'     => trim($row[14] ?? '-'),
                ]);
                $count++;
            }
            fclose($handle);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data ke database.');
            }

            if ($count == 0) {
                return redirect()->back()->with('error', 'Tidak ada data valid yang ditemukan. Pastikan format file sesuai.');
            }

            return redirect()->to('/wilayah-kumuh')->with('success', "$count data Wilayah Kumuh berhasil diimpor.");

        } catch (\Exception $e) {
            if (isset($handle)) fclose($handle);
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function peta()
    {
        $data = [
            'title' => 'Peta Wilayah Kumuh',
            'kumuh' => $this->kumuhModel->findAll(),
        ];
        return view('wilayah_kumuh/peta', $data);
    }

    public function detail($id)
    {
        $data['kumuh'] = $this->kumuhModel->find($id);
        if (!$data['kumuh']) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        $data['title'] = 'Detail Wilayah Kumuh';
        return view('wilayah_kumuh/detail', $data);
    }

    public function print($id)
    {
        $data['kumuh'] = $this->kumuhModel->find($id);
        if (!$data['kumuh']) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        return view('wilayah_kumuh/print_report', $data);
    }

    public function create()
    {
        return view('wilayah_kumuh/create', ['title' => 'Tambah Wilayah Kumuh']);
    }

    public function store()
    {
        $data = $this->request->getPost();
        
        // Handle empty fields for foreign keys or auto-increment
        if (empty($data['FID'])) unset($data['FID']);
        if (empty($data['desa_id'])) $data['desa_id'] = null;

        $this->kumuhModel->insert($data);
        $this->logActivity('Tambah', 'Wilayah Kumuh', "Menambah wilayah kumuh: " . ($data['Kawasan'] ?? 'Tanpa Nama'), $this->formatLogData($data));
        return redirect()->to('/wilayah-kumuh')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $data['kumuh'] = $this->kumuhModel->find($id);
        $data['title'] = 'Edit Wilayah Kumuh';
        return view('wilayah_kumuh/edit', $data);
    }

    public function update($id)
    {
        $oldData = $this->kumuhModel->find($id);
        $newData = $this->request->getPost();
        
        if (empty($newData['desa_id'])) $newData['desa_id'] = null;

        $this->kumuhModel->update($id, $newData);
        
        $diff = $this->generateDiff($oldData, $newData);
        $this->logActivity('Ubah', 'Wilayah Kumuh', "Memperbarui data wilayah kumuh: " . ($oldData['Kawasan'] ?? 'Unknown'), $diff);
        
        return redirect()->to('/wilayah-kumuh')->with('success', 'Data berhasil diperbarui.');
    }

    public function delete($id)
    {
        $data = $this->kumuhModel->find($id);
        if ($data) {
            $db = \Config\Database::connect();
            $db->table('trash_data')->insert([
                'entity_type' => 'KUMUH',
                'entity_id'   => $id,
                'data_json'   => json_encode($data),
                'deleted_by'  => session()->get('username'),
                'created_at'  => date('Y-m-d H:i:s')
            ]);

            $this->kumuhModel->delete($id);
            $this->logActivity('Hapus', 'Wilayah Kumuh', "Memindahkan wilayah kumuh ke Recycle Bin: " . ($data['Kawasan'] ?? 'Unknown'), $this->formatLogData($data));
        }
        return redirect()->to('/wilayah-kumuh')->with('success', 'Data berhasil dipindahkan ke Recycle Bin.');
    }

    public function bulkDelete()
    {
        $ids = $this->request->getPost('ids');
        if (empty($ids)) return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak ada data yang dipilih.']);

        $db = \Config\Database::connect();
        $db->transStart();
        try {
            $items = $this->kumuhModel->whereIn('FID', $ids)->findAll();
            foreach ($items as $item) {
                $db->table('trash_data')->insert([
                    'entity_type' => 'KUMUH',
                    'entity_id'   => $item['FID'],
                    'data_json'   => json_encode($item),
                    'deleted_by'  => session()->get('username'),
                    'created_at'  => date('Y-m-d H:i:s')
                ]);
            }

            $this->kumuhModel->whereIn('FID', $ids)->delete();
            $db->transComplete();
            if ($db->transStatus() === FALSE) throw new \Exception('Gagal menghapus data massal.');
            $this->logActivity('Hapus Massal', 'Wilayah Kumuh', "Memindahkan " . count($ids) . " data wilayah kumuh ke Recycle Bin");
            return $this->response->setJSON(['status' => 'success', 'message' => count($ids) . ' data berhasil dipindahkan ke Recycle Bin.']);
        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
