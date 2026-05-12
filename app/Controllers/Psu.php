<?php

namespace App\Controllers;

use App\Models\PsuJalanModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Psu extends BaseController
{
    protected $jalanModel;

    public function __construct()
    {
        $this->jalanModel = new PsuJalanModel();
    }

    public function index()
    {
        $keyword = $this->request->getGet('keyword');
        $perPage = $this->request->getGet('per_page') ?? 10;

        $query = $this->jalanModel;
        if ($keyword) {
            $query = $query->groupStart()
                ->like('nama_jalan', $keyword)
                ->orLike('tahun', $keyword)
                ->groupEnd();
        }

        $jalan = $query->paginate($perPage, 'default');
        
        $data = [
            'title' => 'PSU Jaringan Jalan',
            'jalan' => $jalan,
            'jalan_all' => $this->jalanModel->findAll(),
            'pager' => $this->jalanModel->pager,
            'perPage' => $perPage,
            'keyword' => $keyword,
            'total_jalan' => $this->jalanModel->countAllResults(false),
            'total_panjang' => $this->jalanModel->selectSum('panjang_luas')->get()->getRow()->panjang_luas,
        ];

        return view('psu/index', $data);
    }

    public function exportExcel()
    {
        $data = $this->jalanModel->findAll();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $headers = ['ID', 'Nama Jalan', 'Tahun', 'Panjang/Luas (m)', 'WKT', 'Dibuat Pada'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }

        // Data
        $rowNum = 2;
        foreach ($data as $row) {
            $sheet->setCellValue('A' . $rowNum, $row['id']);
            $sheet->setCellValue('B' . $rowNum, $row['nama_jalan']);
            $sheet->setCellValue('C' . $rowNum, $row['tahun']);
            $sheet->setCellValue('D' . $rowNum, $row['panjang_luas']);
            $sheet->setCellValue('E' . $rowNum, $row['wkt']);
            $sheet->setCellValue('F' . $rowNum, $row['created_at']);
            $rowNum++;
        }

        // Style header
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'Data_PSU_Jalan_' . date('YmdHis') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function importCsv()
    {
        if (!has_permission('create_psu')) return redirect()->back()->with('error', 'Izin ditolak.');

        $file = $this->request->getFile('csv_file');
        if (!$file || !$file->isValid()) return redirect()->back()->with('error', 'File tidak valid.');

        $handle = fopen($file->getTempName(), 'r');
        
        // Baca baris pertama untuk deteksi delimiter
        $firstLine = fgets($handle);
        $delimiter = (substr_count($firstLine, ';') > substr_count($firstLine, ',')) ? ';' : ',';
        rewind($handle);

        $count = 0;
        $db = \Config\Database::connect();

        // Reset Auto Increment jika tabel kosong agar ID mulai dari 1 lagi
        if ($db->table('psu_jalan')->countAllResults() === 0) {
            $db->query("ALTER TABLE psu_jalan AUTO_INCREMENT = 1");
        }

        $db->transStart();

        try {
            while (($row = fgetcsv($handle, 10000, $delimiter)) !== FALSE) {
                // Lewati header (berisi kata 'WKT' atau 'nama_jalan') atau baris kosong
                if (count($row) < 3 || stripos(implode(' ', $row), 'WKT') !== false || empty(trim($row[0] ?? ''))) {
                    continue;
                }

                /* 
                   Struktur CSV Jaringan Jalan Baru:
                   Index 0: WKT (Diharapkan POINT)
                   Index 1: nama_jalan
                   Index 2: tahun
                   Index 3: panjang_luas
                */

                $this->jalanModel->insert([
                    'wkt'          => $row[0] ?? null,
                    'nama_jalan'   => trim($row[1] ?? '-'),
                    'tahun'        => (int)($row[2] ?? date('Y')),
                    'panjang_luas' => (float)preg_replace('/[^0-9.]/', '', $row[3] ?? '0'),
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

            $this->logActivity('Import', 'PSU Jalan', "Berhasil mengimpor $count data Jaringan Jalan");

            return redirect()->to('/psu')->with('success', "$count data PSU Jalan berhasil diimpor.");

        } catch (\Exception $e) {
            if (isset($handle)) fclose($handle);
            $db->transRollback();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function detail($id)
    {
        $data['jalan'] = $this->jalanModel->find($id);
        if (!$data['jalan']) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        
        $data['title'] = 'Detail Jalan: ' . ($data['jalan']['nama_jalan'] ?? 'Tanpa Nama');
        return view('psu/detail', $data);
    }

    public function create()
    {
        if (!has_permission('create_psu')) return redirect()->back()->with('error', 'Izin ditolak.');
        return view('psu/create', ['title' => 'Tambah Jaringan Jalan']);
    }

    public function store()
    {
        if (!has_permission('create_psu')) return redirect()->back()->with('error', 'Izin ditolak.');
        
        $rules = [
            'nama_jalan' => 'required',
            'wkt'        => 'required',
            'jalan'      => 'required|numeric'
        ];

        if (!$this->validate($rules)) return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());

        $data = $this->request->getPost();

        // Handle Foto Before & After
        $uploadPath = FCPATH . 'uploads/psu/';
        if (!is_dir($uploadPath)) mkdir($uploadPath, 0777, true);

        foreach (['foto_before', 'foto_after'] as $field) {
            $img = $this->request->getFile($field);
            if ($img && $img->isValid() && !$img->hasMoved()) {
                $newName = $img->getRandomName();
                $img->move($uploadPath, $newName);
                $data[$field] = $newName;
            }
        }

        $this->jalanModel->insert($data);
        
        $this->logActivity('Tambah', 'PSU Jalan', 'Menambah jaringan jalan: ' . $data['nama_jalan'], $this->formatLogData($data));

        return redirect()->to('/psu')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit($id)
    {
        if (!has_permission('edit_psu')) return redirect()->back()->with('error', 'Izin ditolak.');
        
        $data['jalan'] = $this->jalanModel->find($id);
        if (!$data['jalan']) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        
        $data['title'] = 'Edit Jaringan Jalan';
        return view('psu/edit', $data);
    }

    public function update($id)
    {
        if (!has_permission('edit_psu')) return redirect()->back()->with('error', 'Izin ditolak.');

        $rules = [
            'nama_jalan' => 'required',
            'wkt'        => 'required',
            'jalan'      => 'required|numeric'
        ];

        if (!$this->validate($rules)) return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());

        $oldData = $this->jalanModel->find($id);
        $newData = $this->request->getPost();

        // Handle Foto Before & After
        $uploadPath = FCPATH . 'uploads/psu/';
        if (!is_dir($uploadPath)) mkdir($uploadPath, 0777, true);

        foreach (['foto_before', 'foto_after'] as $field) {
            $img = $this->request->getFile($field);
            if ($img && $img->isValid() && !$img->hasMoved()) {
                // Hapus foto lama jika ada
                if (!empty($oldData[$field]) && file_exists($uploadPath . $oldData[$field])) {
                    unlink($uploadPath . $oldData[$field]);
                }
                
                $newName = $img->getRandomName();
                $img->move($uploadPath, $newName);
                $newData[$field] = $newName;
            }
        }
        
        $this->jalanModel->update($id, $newData);
        
        $diff = $this->generateDiff($oldData, $newData);
        $this->logActivity('Ubah', 'PSU Jalan', 'Memperbarui data jalan: ' . $oldData['nama_jalan'], $diff);

        return redirect()->to('/psu')->with('success', 'Data berhasil diperbarui.');
    }

    public function delete($id)
    {
        if (!has_permission('delete_psu')) return redirect()->back()->with('error', 'Anda tidak memiliki izin.');

        $data = $this->jalanModel->find($id);
        if ($data) {
            // Hapus foto fisik
            foreach (['foto_before', 'foto_after'] as $f) {
                if (!empty($data[$f])) {
                    $filePath = FCPATH . 'uploads/psu/' . $data[$f];
                    if (file_exists($filePath)) unlink($filePath);
                }
            }

            $db = \Config\Database::connect();
            $db->table('trash_data')->insert([
                'entity_type' => 'PSU_JALAN',
                'entity_id'   => $id,
                'data_json'   => json_encode($data),
                'deleted_by'  => session()->get('username'),
                'created_at'  => date('Y-m-d H:i:s')
            ]);

            $this->jalanModel->delete($id);
            $this->logActivity('Hapus', 'PSU Jalan', 'Memindahkan data jalan ke Recycle Bin: ' . ($data['nama_jalan'] ?? 'Tanpa Nama'), $this->formatLogData($data));
        }

        return redirect()->to('/psu')->with('success', 'Data berhasil dipindahkan ke Recycle Bin.');
    }

    public function bulkDelete()
    {
        if (!has_permission('delete_psu')) return $this->response->setJSON(['status' => 'error', 'message' => 'Izin ditolak.']);
        $ids = $this->request->getPost('ids');
        if (empty($ids)) return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak ada data yang dipilih.']);

        $db = \Config\Database::connect();
        $db->transStart();
        try {
            $items = $this->jalanModel->whereIn('id', $ids)->findAll();
            foreach ($items as $item) {
                $db->table('trash_data')->insert([
                    'entity_type' => 'PSU_JALAN',
                    'entity_id'   => $item['id'],
                    'data_json'   => json_encode($item),
                    'deleted_by'  => session()->get('username'),
                    'created_at'  => date('Y-m-d H:i:s')
                ]);
            }

            $this->jalanModel->whereIn('id', $ids)->delete();
            $db->transComplete();
            if ($db->transStatus() === FALSE) throw new \Exception('Gagal menghapus data massal.');
            $this->logActivity('Hapus Massal', 'PSU Jalan', "Memindahkan " . count($ids) . " data jalan ke Recycle Bin");
            return $this->response->setJSON(['status' => 'success', 'message' => count($ids) . ' data berhasil dipindahkan ke Recycle Bin.']);
        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
