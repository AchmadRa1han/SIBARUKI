<?php

namespace App\Controllers;

use App\Models\PerumahanFormalModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PerumahanFormal extends BaseController
{
    protected $perumahanModel;

    public function __construct()
    {
        $this->perumahanModel = new PerumahanFormalModel();
    }

    public function index()
    {
        $keyword = $this->request->getGet('keyword');
        $perPage = $this->request->getGet('per_page') ?? 10;

        $query = $this->perumahanModel;
        if ($keyword) {
            $query = $query->groupStart()
                ->like('nama_perumahan', $keyword)
                ->orLike('pengembang', $keyword)
                ->groupEnd();
        }

        $perumahan = $query->paginate($perPage, 'default');
        
        $data = [
            'title' => 'Data Perumahan Formal',
            'perumahan' => $perumahan,
            'perumahan_all' => $this->perumahanModel->findAll(),
            'pager' => $this->perumahanModel->pager,
            'perPage' => $perPage,
            'keyword' => $keyword,
            'total_perumahan' => $this->perumahanModel->countAllResults(false),
            'total_luas' => $this->perumahanModel->selectSum('luas_kawasan_ha')->get()->getRow()->luas_kawasan_ha,
        ];

        return view('perumahan_formal/index', $data);
    }

    public function exportExcel()
    {
        $data = $this->perumahanModel->findAll();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = ['ID', 'Nama Perumahan', 'Pengembang', 'Tahun', 'Luas Ha', 'Longitude', 'Latitude', 'WKT'];
        foreach ($headers as $key => $header) {
            $sheet->setCellValueByColumnAndRow($key + 1, 1, $header);
        }

        $rowNum = 2;
        foreach ($data as $row) {
            $sheet->setCellValueByColumnAndRow(1, $rowNum, $row['id']);
            $sheet->setCellValueByColumnAndRow(2, $rowNum, $row['nama_perumahan']);
            $sheet->setCellValueByColumnAndRow(3, $rowNum, $row['pengembang']);
            $sheet->setCellValueByColumnAndRow(4, $rowNum, $row['tahun_pembangunan']);
            $sheet->setCellValueByColumnAndRow(5, $rowNum, $row['luas_kawasan_ha']);
            $sheet->setCellValueByColumnAndRow(6, $rowNum, $row['longitude']);
            $sheet->setCellValueByColumnAndRow(7, $rowNum, $row['latitude']);
            $sheet->setCellValueByColumnAndRow(8, $rowNum, $row['wkt']);
            $rowNum++;
        }

        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        foreach (range('A', 'H') as $col) { $sheet->getColumnDimension($col)->setAutoSize(true); }

        $filename = 'Export_Perumahan_Formal_' . date('YmdHis') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

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
            $this->perumahanModel->insert([
                'nama_perumahan' => $row[0],
                'pengembang' => $row[1],
                'tahun_pembangunan' => $row[2],
                'luas_kawasan_ha' => $row[3],
                'longitude' => $row[4],
                'latitude' => $row[5],
                'wkt' => $row[6] ?? null,
            ]);
            $count++;
        }
        fclose($handle);
        return redirect()->to('/perumahan-formal')->with('success', "$count data berhasil diimpor.");
    }

    public function create()
    {
        if (!has_permission('create_rtlh')) return redirect()->back()->with('error', 'Izin ditolak.');
        return view('perumahan_formal/create', ['title' => 'Tambah Perumahan Formal']);
    }

    public function store()
    {
        if (!has_permission('create_rtlh')) return redirect()->back()->with('error', 'Izin ditolak.');

        $rules = [
            'nama_perumahan' => 'required',
            'pengembang' => 'required',
            'tahun_pembangunan' => 'required|numeric',
            'luas_kawasan_ha' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost();
        $this->perumahanModel->insert($data);
        
        $this->logActivity('Tambah', 'Perumahan Formal', 'Menambah perumahan: ' . $data['nama_perumahan'], $this->formatLogData($data));

        return redirect()->to('/perumahan-formal')->with('success', 'Data perumahan berhasil ditambahkan.');
    }

    public function detail($id)
    {
        $data['item'] = $this->perumahanModel->find($id);
        if (!$data['item']) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        
        $data['title'] = 'Detail Perumahan: ' . $data['item']['nama_perumahan'];
        return view('perumahan_formal/detail', $data);
    }

    public function edit($id)
    {
        $data['item'] = $this->perumahanModel->find($id);
        if (!$data['item']) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        $data['title'] = 'Edit Perumahan Formal';
        return view('perumahan_formal/edit', $data);
    }

    public function update($id)
    {
        $oldData = $this->perumahanModel->find($id);
        $newData = $this->request->getPost();
        
        $this->perumahanModel->update($id, $newData);
        
        $diff = $this->generateDiff($oldData, $newData);
        $this->logActivity('Ubah', 'Perumahan Formal', 'Memperbarui data perumahan: ' . $oldData['nama_perumahan'], $diff);

        return redirect()->to('/perumahan-formal')->with('success', 'Data berhasil diperbarui.');
    }

    public function delete($id)
    {
        if (!has_permission('delete_rtlh')) {
            return redirect()->back()->with('error', 'Izin ditolak.');
        }

        $data = $this->perumahanModel->find($id);
        if ($data) {
            $this->perumahanModel->delete($id);
            $this->logActivity('Hapus', 'Perumahan Formal', 'Menghapus data perumahan: ' . ($data['nama_perumahan'] ?? 'Tanpa Nama'), $this->formatLogData($data));
        }

        return redirect()->to('/perumahan-formal')->with('success', 'Data berhasil dihapus.');
    }

    public function bulkDelete()
    {
        if (!has_permission('delete_rtlh')) return $this->response->setJSON(['status' => 'error', 'message' => 'Izin ditolak.']);
        $ids = $this->request->getPost('ids');
        if (empty($ids)) return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak ada data yang dipilih.']);

        $db = \Config\Database::connect();
        $db->transStart();
        try {
            $this->perumahanModel->whereIn('id', $ids)->delete();
            $db->transComplete();
            if ($db->transStatus() === FALSE) throw new \Exception('Gagal menghapus data massal.');
            $this->logActivity('Hapus Massal', 'Perumahan Formal', "Menghapus " . count($ids) . " data perumahan sekaligus");
            return $this->response->setJSON(['status' => 'success', 'message' => count($ids) . ' data berhasil dihapus.']);
        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
