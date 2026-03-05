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
        $search = $this->request->getGet('search');
        $kecamatan = $this->request->getGet('kecamatan');
        $sortBy = $this->request->getGet('sort_by') ?? 'id';
        $sortOrder = $this->request->getGet('sort_order') ?? 'desc';

        $query = $this->asetModel;

        if ($search) {
            $query = $query->groupStart()
                ->like('no_sertifikat', $search)
                ->orLike('nama_pemilik', $search)
                ->orLike('lokasi', $search)
                ->groupEnd();
        }

        if ($kecamatan) {
            $query = $query->where('kecamatan', $kecamatan);
        }

        // Apply Sorting
        $query = $query->orderBy($sortBy, $sortOrder);

        $perPage = $this->request->getGet('per_page') ?? 10;
        $aset = $query->paginate($perPage, 'group1');
        
        // Ambil semua data untuk peta (tanpa pagination)
        $aset_all = (clone $query)->findAll();

        $data = [
            'aset' => $aset,
            'aset_all' => $aset_all,
            'pager' => $this->asetModel->pager,
            'perPage' => $perPage,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'total_aset' => $this->asetModel->countAllResults(false),
            'total_luas' => $this->asetModel->selectSum('luas_m2')->get()->getRow()->luas_m2,
            'total_nilai' => $this->asetModel->selectSum('nilai_aset')->get()->getRow()->nilai_aset,
            'kecamatans' => $this->asetModel->select('kecamatan')->distinct()->findAll(),
            'search' => $search,
            'selected_kecamatan' => $kecamatan
        ];

        return view('aset_tanah/index', $data);
    }

    public function create()
    {
        return view('aset_tanah/create');
    }

    public function store()
    {
        $rules = [
            'no_sertifikat' => 'required',
            'nama_pemilik'  => 'required',
            'luas_m2'       => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->asetModel->save($this->request->getPost());
        return redirect()->to('/aset-tanah')->with('success', 'Data aset berhasil ditambahkan.');
    }

    public function exportExcel()
    {
        $data = $this->asetModel->findAll();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $headers = ['ID', 'Nama Objek', 'Kecamatan', 'Desa', 'Luas', 'Status', 'Koordinat', 'WKT'];
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
            $sheet->setCellValueByColumnAndRow(5, $rowNum, $row['luas']);
            $sheet->setCellValueByColumnAndRow(6, $rowNum, $row['status_sertifikat']);
            $sheet->setCellValueByColumnAndRow(7, $rowNum, $row['lokasi_koordinat']);
            $sheet->setCellValueByColumnAndRow(8, $rowNum, $row['wkt']);
            $rowNum++;
        }

        // Style header
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'Export_Aset_Tanah_' . date('YmdHis') . '.xlsx';

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
            $this->asetModel->insert([
                'nama_objek' => $row[0],
                'kecamatan' => $row[1],
                'desa' => $row[2],
                'luas' => $row[3],
                'status_sertifikat' => $row[4],
                'lokasi_koordinat' => $row[5],
                'wkt' => $row[6] ?? null,
            ]);
            $count++;
        }
        fclose($handle);
        return redirect()->to('/aset-tanah')->with('success', "$count data berhasil diimpor.");
    }

    public function detail($id)
    {
        $aset = $this->asetModel->find($id);
        if (!$aset) {
            return redirect()->to('/aset-tanah')->with('error', 'Data tidak ditemukan.');
        }

        return view('aset_tanah/detail', ['aset' => $aset]);
    }

    public function edit($id)
    {
        $aset = $this->asetModel->find($id);
        if (!$aset) {
            return redirect()->to('/aset-tanah')->with('error', 'Data tidak ditemukan.');
        }

        return view('aset_tanah/edit', ['aset' => $aset]);
    }

    public function update($id)
    {
        $rules = [
            'no_sertifikat' => 'required',
            'nama_pemilik'  => 'required',
            'luas_m2'       => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->asetModel->update($id, $this->request->getPost());
        return redirect()->to('/aset-tanah')->with('success', 'Data aset berhasil diperbarui.');
    }

    public function delete($id)
    {
        $this->asetModel->delete($id);
        return redirect()->to('/aset-tanah')->with('success', 'Data aset berhasil dihapus.');
    }
}
