<?php

namespace App\Controllers;

use App\Models\ArsinumModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Arsinum extends BaseController
{
    protected $arsinumModel;

    public function __construct()
    {
        $this->arsinumModel = new ArsinumModel();
    }

    public function index()
    {
        $search = $this->request->getGet('search');
        $kecamatan = $this->request->getGet('kecamatan');
        $sortBy = $this->request->getGet('sort_by') ?? 'tahun';
        $sortOrder = $this->request->getGet('sort_order') ?? 'desc';
        $perPage = $this->request->getGet('per_page') ?? 10;

        $query = $this->arsinumModel;

        if ($search) {
            $query = $query->groupStart()
                ->like('jenis_pekerjaan', $search)
                ->orLike('desa', $search)
                ->orLike('pelaksana', $search)
                ->groupEnd();
        }

        if ($kecamatan) {
            $query = $query->where('kecamatan', $kecamatan);
        }

        $query = $query->orderBy($sortBy, $sortOrder);

        $data = [
            'arsinum' => $query->paginate($perPage, 'group1'),
            'arsinum_all' => (clone $query)->findAll(),
            'pager' => $this->arsinumModel->pager,
            'perPage' => $perPage,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'search' => $search,
            'selected_kecamatan' => $kecamatan,
            'kecamatans' => $this->arsinumModel->select('kecamatan')->distinct()->findAll(),
            'total_anggaran' => $this->arsinumModel->selectSum('anggaran')->get()->getRow()->anggaran,
            'total_unit' => $this->arsinumModel->countAllResults(false)
        ];

        return view('arsinum/index', $data);
    }

    public function create()
    {
        return view('arsinum/create');
    }

    public function store()
    {
        $this->arsinumModel->save($this->request->getPost());
        return redirect()->to('/arsinum')->with('success', 'Data ARSINUM berhasil ditambahkan.');
    }

    public function exportExcel()
    {
        $data = $this->arsinumModel->findAll();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $headers = ['ID', 'Nama Objek', 'Kecamatan', 'Desa', 'Tahun', 'Status', 'Koordinat', 'WKT'];
        foreach ($headers as $key => $header) {
            $sheet->setCellValueByColumnAndRow($key + 1, 1, $header);
        }

        // Data
        $rowNum = 2;
        foreach ($data as $row) {
            $sheet->setCellValueByColumnAndRow(1, $rowNum, $row['id']);
            $sheet->setCellValueByColumnAndRow(2, $rowNum, $row['nama_objek']);
            $sheet->setCellValueByColumnAndRow(3, $rowNum, $row['kecamatan']);
            $sheet->setCellValueByColumnAndRow(4, $rowNum, $row['desa']);
            $sheet->setCellValueByColumnAndRow(5, $rowNum, $row['tahun']);
            $sheet->setCellValueByColumnAndRow(6, $rowNum, $row['status']);
            $sheet->setCellValueByColumnAndRow(7, $rowNum, $row['lokasi_koordinat']);
            $sheet->setCellValueByColumnAndRow(8, $rowNum, $row['wkt']);
            $rowNum++;
        }

        // Style header
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'Export_Arsinum_' . date('YmdHis') . '.xlsx';

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
            $this->arsinumModel->insert([
                'nama_objek' => $row[0],
                'kecamatan' => $row[1],
                'desa' => $row[2],
                'tahun' => $row[3],
                'status' => $row[4],
                'lokasi_koordinat' => $row[5],
                'wkt' => $row[6] ?? null,
            ]);
            $count++;
        }
        fclose($handle);
        return redirect()->to('/arsinum')->with('success', "$count data berhasil diimpor.");
    }

    public function detail($id)
    {
        $data['item'] = $this->arsinumModel->find($id);
        if (!$data['item']) return redirect()->to('/arsinum');
        return view('arsinum/detail', $data);
    }

    public function edit($id)
    {
        $data['item'] = $this->arsinumModel->find($id);
        if (!$data['item']) return redirect()->to('/arsinum');
        return view('arsinum/edit', $data);
    }

    public function update($id)
    {
        $this->arsinumModel->update($id, $this->request->getPost());
        return redirect()->to('/arsinum')->with('success', 'Data ARSINUM berhasil diperbarui.');
    }

    public function delete($id)
    {
        $this->arsinumModel->delete($id);
        return redirect()->to('/arsinum')->with('success', 'Data ARSINUM berhasil dihapus.');
    }
}
