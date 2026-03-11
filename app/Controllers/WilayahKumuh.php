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
        if (!$file->isValid()) return redirect()->back()->with('error', 'File tidak valid.');
        $handle = fopen($file->getTempName(), 'r');
        fgetcsv($handle); 
        $count = 0;
        while (($row = fgetcsv($handle)) !== FALSE) {
            if (empty($row[0])) continue;
            $this->kumuhModel->insert([
                'FID' => $row[0],
                'Kecamatan' => $row[1],
                'Kelurahan' => $row[2],
                'Kawasan' => $row[3],
                'Luas_kumuh' => $row[4],
                'skor_kumuh' => $row[5],
                'WKT' => $row[6] ?? null,
            ]);
            $count++;
        }
        fclose($handle);
        return redirect()->to('/wilayah-kumuh')->with('success', "$count data berhasil diimpor.");
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
            $this->kumuhModel->delete($id);
            $this->logActivity('Hapus', 'Wilayah Kumuh', "Menghapus data wilayah kumuh: " . ($data['Kawasan'] ?? 'Unknown'), $this->formatLogData($data));
        }
        return redirect()->to('/wilayah-kumuh')->with('success', 'Data berhasil dihapus.');
    }
}
