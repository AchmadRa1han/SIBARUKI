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
                ->orLike('id_csv', $keyword)
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
            'total_panjang' => $this->jalanModel->selectSum('jalan')->get()->getRow()->jalan,
        ];

        return view('psu/index', $data);
    }

    public function exportExcel()
    {
        $data = $this->jalanModel->findAll();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $headers = ['ID', 'Nama Jalan', 'ID CSV', 'Nilai Jalan', 'WKT', 'Dibuat Pada'];
        foreach ($headers as $key => $header) {
            $sheet->setCellValueByColumnAndRow($key + 1, 1, $header);
        }

        // Data
        $rowNum = 2;
        foreach ($data as $row) {
            $sheet->setCellValueByColumnAndRow(1, $rowNum, $row['id']);
            $sheet->setCellValueByColumnAndRow(2, $rowNum, $row['nama_jalan']);
            $sheet->setCellValueByColumnAndRow(3, $rowNum, $row['id_csv']);
            $sheet->setCellValueByColumnAndRow(4, $rowNum, $row['jalan']);
            $sheet->setCellValueByColumnAndRow(5, $rowNum, $row['wkt']);
            $sheet->setCellValueByColumnAndRow(6, $rowNum, $row['created_at']);
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
        if (!$file->isValid()) return redirect()->back()->with('error', 'File tidak valid.');

        $handle = fopen($file->getTempName(), 'r');
        fgetcsv($handle); // Skip header

        $count = 0;
        while (($row = fgetcsv($handle)) !== FALSE) {
            if (empty($row[0])) continue;
            $this->jalanModel->insert([
                'nama_jalan' => $row[0] ?? null,
                'id_csv'     => $row[1] ?? 0,
                'jalan'      => $row[2] ?? 0,
                'wkt'        => $row[3] ?? null,
            ]);
            $count++;
        }
        fclose($handle);

        return redirect()->to('/psu')->with('success', "$count data berhasil diimpor.");
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
            $this->jalanModel->delete($id);
            $this->logActivity('Hapus', 'PSU Jalan', 'Menghapus data jalan: ' . ($data['nama_jalan'] ?? 'Tanpa Nama'), $this->formatLogData($data));
        }

        return redirect()->to('/psu')->with('success', 'Data berhasil dihapus.');
    }
}
