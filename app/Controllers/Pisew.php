<?php

namespace App\Controllers;

use App\Models\PisewModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Pisew extends BaseController
{
    protected $pisewModel;

    public function __construct()
    {
        $this->pisewModel = new PisewModel();
    }

    public function index()
    {
        $search = $this->request->getGet('search') ?? '';
        $perPage = $this->request->getGet('per_page') ?? 10;
        $selected_kecamatan = $this->request->getGet('kecamatan') ?? '';
        $sortBy = $this->request->getGet('sort_by') ?? 'id';
        $sortOrder = $this->request->getGet('sort_order') ?? 'desc';

        $query = $this->pisewModel;

        if ($search) {
            $query = $query->groupStart()
                ->like('jenis_pekerjaan', $search)
                ->orLike('lokasi_desa', $search)
                ->groupEnd();
        }

        if ($selected_kecamatan) {
            $query = $query->where('kecamatan', $selected_kecamatan);
        }

        $data = [
            'title' => 'Data PISEW',
            'pisew' => $query->orderBy($sortBy, $sortOrder)->paginate($perPage, 'group1'),
            'pisew_all' => $this->pisewModel->findAll(),
            'pager' => $this->pisewModel->pager,
            'perPage' => $perPage,
            'search' => $search,
            'kecamatans' => $this->pisewModel->select('kecamatan')->distinct()->findAll(),
            'selected_kecamatan' => $selected_kecamatan,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'total_kegiatan' => $this->pisewModel->countAllResults(false),
            'total_anggaran' => $this->pisewModel->selectSum('anggaran')->get()->getRow()->anggaran ?? 0,
        ];

        return view('pisew/index', $data);
    }

    public function exportExcel()
    {
        $data = $this->pisewModel->findAll();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Jenis Pekerjaan');
        $sheet->setCellValue('C1', 'Lokasi Desa');
        $sheet->setCellValue('D1', 'Kecamatan');
        $sheet->setCellValue('E1', 'Anggaran');
        $sheet->setCellValue('F1', 'Tahun');
        $sheet->setCellValue('G1', 'Pelaksana');
        $sheet->setCellValue('H1', 'Koordinat');

        $rowNum = 2;
        foreach ($data as $row) {
            $sheet->setCellValue('A' . $rowNum, $row['id']);
            $sheet->setCellValue('B' . $rowNum, $row['jenis_pekerjaan']);
            $sheet->setCellValue('C' . $rowNum, $row['lokasi_desa']);
            $sheet->setCellValue('D' . $rowNum, $row['kecamatan']);
            $sheet->setCellValue('E' . $rowNum, $row['anggaran']);
            $sheet->setCellValue('F' . $rowNum, $row['tahun']);
            $sheet->setCellValue('G' . $rowNum, $row['pelaksana']);
            $sheet->setCellValue('H' . $rowNum, $row['koordinat']);
            $rowNum++;
        }

        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        foreach (range('A', 'H') as $col) { $sheet->getColumnDimension($col)->setAutoSize(true); }

        $filename = 'Export_PISEW_' . date('YmdHis') . '.xlsx';
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
            $this->pisewModel->insert([
                'jenis_pekerjaan' => $row[0],
                'lokasi_desa' => $row[1],
                'kecamatan' => $row[2],
                'pelaksana' => $row[3],
                'anggaran' => $row[4],
                'tahun' => $row[5],
                'koordinat' => $row[6] ?? null,
            ]);
            $count++;
        }
        fclose($handle);
        return redirect()->to('/pisew')->with('success', "$count data berhasil diimpor.");
    }

    public function detail($id)
    {
        $data['item'] = $this->pisewModel->find($id);
        if (!$data['item']) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        $data['title'] = 'Detail PISEW';
        return view('pisew/detail', $data);
    }

    public function create()
    {
        return view('pisew/create', ['title' => 'Tambah PISEW']);
    }

    public function store()
    {
        $data = $this->request->getPost();
        $this->pisewModel->insert($data);
        $this->logActivity('Tambah', 'PISEW', "Menambah data PISEW: {$data['jenis_pekerjaan']}", $this->formatLogData($data));
        return redirect()->to('/pisew')->with('success', 'Data PISEW berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $data['item'] = $this->pisewModel->find($id);
        $data['title'] = 'Edit PISEW';
        return view('pisew/edit', $data);
    }

    public function update($id)
    {
        $oldData = $this->pisewModel->find($id);
        $newData = $this->request->getPost();
        $this->pisewModel->update($id, $newData);
        
        $diff = $this->generateDiff($oldData, $newData);
        $this->logActivity('Ubah', 'PISEW', "Memperbarui data PISEW: " . ($oldData['jenis_pekerjaan'] ?? 'Unknown'), $diff);
        
        return redirect()->to('/pisew')->with('success', 'Data PISEW berhasil diperbarui.');
    }

    public function delete($id)
    {
        $data = $this->pisewModel->find($id);
        if ($data) {
            $this->pisewModel->delete($id);
            $this->logActivity('Hapus', 'PISEW', "Menghapus data PISEW: " . ($data['jenis_pekerjaan'] ?? 'Unknown'), $this->formatLogData($data));
        }
        return redirect()->to('/pisew')->with('success', 'Data PISEW berhasil dihapus.');
    }
}
