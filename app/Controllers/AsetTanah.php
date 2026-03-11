<?php

namespace App\Controllers;

use App\Models\AsetTanahModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AsetTanah extends BaseController
{
    protected $asetModel;

    public function __construct()
    {
        $this->asetModel = new AsetTanahModel();
    }

    public function index()
    {
        $search = $this->request->getGet('search') ?? '';
        $perPage = $this->request->getGet('per_page') ?? 10;
        $selected_kecamatan = $this->request->getGet('kecamatan') ?? '';
        $sortBy = $this->request->getGet('sort_by') ?? 'id';
        $sortOrder = $this->request->getGet('sort_order') ?? 'desc';

        $query = $this->asetModel;

        if ($search) {
            $query = $query->groupStart()
                ->like('nama_pemilik', $search)
                ->orLike('no_sertifikat', $search)
                ->groupEnd();
        }

        if ($selected_kecamatan) {
            $query = $query->where('kecamatan', $selected_kecamatan);
        }

        $data = [
            'title' => 'Data Aset Tanah',
            'aset' => $query->orderBy($sortBy, $sortOrder)->paginate($perPage, 'group1'),
            'aset_all' => $this->asetModel->findAll(),
            'pager' => $this->asetModel->pager,
            'perPage' => $perPage,
            'search' => $search,
            'kecamatans' => $this->asetModel->select('kecamatan')->distinct()->findAll(),
            'selected_kecamatan' => $selected_kecamatan,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'total_aset' => $this->asetModel->countAllResults(false),
            'total_luas' => $this->asetModel->selectSum('luas_m2')->get()->getRow()->luas_m2 ?? 0,
            'total_nilai' => $this->asetModel->selectSum('nilai_aset')->get()->getRow()->nilai_aset ?? 0,
        ];

        return view('aset_tanah/index', $data);
    }

    public function exportExcel()
    {
        $data = $this->asetModel->findAll();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'No Sertifikat');
        $sheet->setCellValue('C1', 'Nama Pemilik');
        $sheet->setCellValue('D1', 'Luas (m2)');
        $sheet->setCellValue('E1', 'Kecamatan');
        $sheet->setCellValue('F1', 'Desa');
        $sheet->setCellValue('G1', 'Koordinat');

        $rowNum = 2;
        foreach ($data as $row) {
            $sheet->setCellValue('A' . $rowNum, $row['id']);
            $sheet->setCellValue('B' . $rowNum, $row['no_sertifikat']);
            $sheet->setCellValue('C' . $rowNum, $row['nama_pemilik']);
            $sheet->setCellValue('D' . $rowNum, $row['luas_m2']);
            $sheet->setCellValue('E' . $rowNum, $row['kecamatan']);
            $sheet->setCellValue('F' . $rowNum, $row['desa_kelurahan']);
            $sheet->setCellValue('G' . $rowNum, $row['koordinat']);
            $rowNum++;
        }

        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
        foreach (range('A', 'G') as $col) { $sheet->getColumnDimension($col)->setAutoSize(true); }

        $filename = 'Export_Aset_Tanah_' . date('YmdHis') . '.xlsx';
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
            $this->asetModel->insert([
                'no_sertifikat' => $row[0],
                'nama_pemilik' => $row[1],
                'luas_m2' => $row[2],
                'kecamatan' => $row[3],
                'desa_kelurahan' => $row[4],
                'koordinat' => $row[5] ?? null,
            ]);
            $count++;
        }
        fclose($handle);
        return redirect()->to('/aset-tanah')->with('success', "$count data berhasil diimpor.");
    }

    public function detail($id)
    {
        $data['aset'] = $this->asetModel->find($id);
        if (!$data['aset']) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        $data['title'] = 'Detail Aset Tanah';
        return view('aset_tanah/detail', $data);
    }

    public function create()
    {
        return view('aset_tanah/create', ['title' => 'Tambah Aset']);
    }

    public function store()
    {
        $data = $this->request->getPost();
        $this->asetModel->insert($data);
        $this->logActivity('Tambah', 'Aset Tanah', "Menambah aset tanah baru: {$data['nama_pemilik']}", $this->formatLogData($data));
        return redirect()->to('/aset-tanah')->with('success', 'Data aset berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $data['aset'] = $this->asetModel->find($id);
        $data['title'] = 'Edit Aset Tanah';
        return view('aset_tanah/edit', $data);
    }

    public function update($id)
    {
        $oldData = $this->asetModel->find($id);
        $newData = $this->request->getPost();
        $this->asetModel->update($id, $newData);
        
        $diff = $this->generateDiff($oldData, $newData);
        $this->logActivity('Ubah', 'Aset Tanah', "Memperbarui data aset: " . ($oldData['nama_pemilik'] ?? 'Unknown'), $diff);
        
        return redirect()->to('/aset-tanah')->with('success', 'Data aset berhasil diperbarui.');
    }

    public function delete($id)
    {
        $data = $this->asetModel->find($id);
        if ($data) {
            $this->asetModel->delete($id);
            $this->logActivity('Hapus', 'Aset Tanah', "Menghapus data aset: " . ($data['nama_pemilik'] ?? 'Unknown'), $this->formatLogData($data));
        }
        return redirect()->to('/aset-tanah')->with('success', 'Data aset berhasil dihapus.');
    }

    public function bulkDelete()
    {
        $ids = $this->request->getPost('ids');
        if (empty($ids)) return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak ada data yang dipilih.']);

        $db = \Config\Database::connect();
        $db->transStart();
        try {
            $this->asetModel->whereIn('id', $ids)->delete();
            $db->transComplete();
            if ($db->transStatus() === FALSE) throw new \Exception('Gagal menghapus data massal.');
            $this->logActivity('Hapus Massal', 'Aset Tanah', "Menghapus " . count($ids) . " data aset sekaligus");
            return $this->response->setJSON(['status' => 'success', 'message' => count($ids) . ' data berhasil dihapus.']);
        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
