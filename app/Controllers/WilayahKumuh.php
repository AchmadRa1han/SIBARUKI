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

        $sheet->setCellValue('A1', 'FID');
        $sheet->setCellValue('B1', 'Kecamatan');
        $sheet->setCellValue('C1', 'Kelurahan');
        $sheet->setCellValue('D1', 'Kawasan');
        $sheet->setCellValue('E1', 'Luas Kumuh (Ha)');
        $sheet->setCellValue('F1', 'Skor Kumuh');
        $sheet->setCellValue('G1', 'WKT');

        $rowNum = 2;
        foreach ($data as $row) {
            $sheet->setCellValue('A' . $rowNum, $row['FID']);
            $sheet->setCellValue('B' . $rowNum, $row['Kecamatan']);
            $sheet->setCellValue('C' . $rowNum, $row['Kelurahan']);
            $sheet->setCellValue('D' . $rowNum, $row['Kawasan']);
            $sheet->setCellValue('E' . $rowNum, $row['Luas_kumuh']);
            $sheet->setCellValue('F' . $rowNum, $row['skor_kumuh']);
            $sheet->setCellValue('G' . $rowNum, $row['WKT']);
            $rowNum++;
        }

        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
        foreach (range('A', 'G') as $col) { $sheet->getColumnDimension($col)->setAutoSize(true); }

        $filename = 'Export_Wilayah_Kumuh_' . date('YmdHis') . '.xlsx';
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
                    'Kawasan'    => trim($row[14] ?? '-'),
                    'Kecamatan'  => trim($row[5] ?? '-'),
                    'Kelurahan'  => trim($row[7] ?? '-'),
                    'Kode_RT_RW' => trim($row[9] ?? '-'),
                    'Luas_kumuh' => (float)($row[10] ?? '0'),
                    'skor_kumuh' => (float)($row[11] ?? '0'),
                    'Sumber_data'=> trim($row[12] ?? '-'),
                    'Sk_Kumuh'   => trim($row[13] ?? '-'),
                    'WKT'        => $row[0] ?? null,
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
