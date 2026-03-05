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
        $search = $this->request->getGet('search');
        $kecamatan = $this->request->getGet('kecamatan');
        $sortBy = $this->request->getGet('sort_by') ?? 'tahun';
        $sortOrder = $this->request->getGet('sort_order') ?? 'desc';
        $perPage = $this->request->getGet('per_page') ?? 10;

        $query = $this->pisewModel;

        if ($search) {
            $query = $query->groupStart()
                ->like('jenis_pekerjaan', $search)
                ->orLike('lokasi_desa', $search)
                ->groupEnd();
        }

        if ($kecamatan) {
            $query = $query->where('kecamatan', $kecamatan);
        }

        $query = $query->orderBy($sortBy, $sortOrder);

        $data = [
            'pisew' => $query->paginate($perPage, 'group1'),
            'pisew_all' => (clone $query)->findAll(),
            'pager' => $this->pisewModel->pager,
            'perPage' => $perPage,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'search' => $search,
            'selected_kecamatan' => $kecamatan,
            'kecamatans' => $this->pisewModel->select('kecamatan')->distinct()->findAll(),
            'total_anggaran' => $this->pisewModel->selectSum('anggaran')->get()->getRow()->anggaran,
            'total_kegiatan' => $this->pisewModel->countAllResults(false)
        ];

        return view('pisew/index', $data);
    }

    public function create()
    {
        return view('pisew/create');
    }

    public function store()
    {
        $this->pisewModel->save($this->request->getPost());
        return redirect()->to('/pisew')->with('success', 'Data PISEW berhasil ditambahkan.');
    }

    public function exportExcel()
    {
        $data = $this->pisewModel->findAll();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $headers = ['ID', 'Kecamatan', 'Desa', 'Jenis Kegiatan', 'Tahun', 'Pagu', 'Output', 'Koordinat', 'WKT'];
        foreach ($headers as $key => $header) {
            $sheet->setCellValueByColumnAndRow($key + 1, 1, $header);
        }

        // Data
        $rowNum = 2;
        foreach ($data as $row) {
            $sheet->setCellValueByColumnAndRow(1, $rowNum, $row['id']);
            $sheet->setCellValueByColumnAndRow(2, $rowNum, $row['kecamatan']);
            $sheet->setCellValueByColumnAndRow(3, $rowNum, $row['desa']);
            $sheet->setCellValueByColumnAndRow(4, $rowNum, $row['jenis_kegiatan']);
            $sheet->setCellValueByColumnAndRow(5, $rowNum, $row['tahun']);
            $sheet->setCellValueByColumnAndRow(6, $rowNum, $row['pagu']);
            $sheet->setCellValueByColumnAndRow(7, $rowNum, $row['output']);
            $sheet->setCellValueByColumnAndRow(8, $rowNum, $row['lokasi_koordinat']);
            $sheet->setCellValueByColumnAndRow(9, $rowNum, $row['wkt']);
            $rowNum++;
        }

        // Style header
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'Export_PISEW_' . date('YmdHis') . '.xlsx';

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
            $this->pisewModel->insert([
                'kecamatan' => $row[0],
                'desa' => $row[1],
                'jenis_kegiatan' => $row[2],
                'tahun' => $row[3],
                'pagu' => $row[4],
                'output' => $row[5],
                'lokasi_koordinat' => $row[6],
                'wkt' => $row[7] ?? null,
            ]);
            $count++;
        }
        fclose($handle);
        return redirect()->to('/pisew')->with('success', "$count data berhasil diimpor.");
    }

    public function detail($id)
    {
        $data['item'] = $this->pisewModel->find($id);
        if (!$data['item']) return redirect()->to('/pisew');
        return view('pisew/detail', $data);
    }

    public function edit($id)
    {
        $data['item'] = $this->pisewModel->find($id);
        if (!$data['item']) return redirect()->to('/pisew');
        return view('pisew/edit', $data);
    }

    public function update($id)
    {
        $this->pisewModel->update($id, $this->request->getPost());
        return redirect()->to('/pisew')->with('success', 'Data PISEW berhasil diperbarui.');
    }

    public function delete($id)
    {
        $this->pisewModel->delete($id);
        return redirect()->to('/pisew')->with('success', 'Data PISEW berhasil dihapus.');
    }
}
