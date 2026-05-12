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
            'title' => 'Data Perumahan',
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
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }

        $rowNum = 2;
        foreach ($data as $row) {
            $sheet->setCellValue('A' . $rowNum, $row['id']);
            $sheet->setCellValue('B' . $rowNum, $row['nama_perumahan']);
            $sheet->setCellValue('C' . $rowNum, $row['pengembang']);
            $sheet->setCellValue('D' . $rowNum, $row['tahun_pembangunan']);
            $sheet->setCellValue('E' . $rowNum, $row['luas_kawasan_ha']);
            $sheet->setCellValue('F' . $rowNum, $row['longitude']);
            $sheet->setCellValue('G' . $rowNum, $row['latitude']);
            $sheet->setCellValue('H' . $rowNum, $row['wkt']);
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
        if (!$file || !$file->isValid()) return redirect()->back()->with('error', 'File tidak valid.');

        $handle = fopen($file->getTempName(), 'r');
        $firstLine = fgets($handle);
        $secondLine = fgets($handle);
        fclose($handle);

        $combined = $firstLine . $secondLine;
        $countSemicolon = substr_count($combined, ';');
        $countComma = substr_count($combined, ',');
        $delimiter = ($countSemicolon > $countComma) ? ';' : ',';

        $count = 0;
        $db = \Config\Database::connect();

        // Reset Auto Increment jika tabel kosong agar ID mulai dari 1 lagi
        if ($db->table('perumahan_formal')->countAllResults() === 0) {
            $db->query("ALTER TABLE perumahan_formal AUTO_INCREMENT = 1");
        }

        $db->transStart();

        try {
            $handle = fopen($file->getTempName(), 'r');
            while (($row = fgetcsv($handle, 5000, $delimiter)) !== FALSE) {
                // Lewati header atau baris yang tidak valid (Id biasanya numerik di baris data)
                if (count($row) < 7 || stripos(implode(' ', $row), 'Keterangan') !== false || !is_numeric($row[1] ?? null)) {
                    continue;
                }

                $this->perumahanModel->insert([
                    'nama_perumahan'    => $row[2] ?? '-',
                    'pengembang'        => $row[6] ?? '-',
                    'tahun_pembangunan' => (int)($row[7] ?? date('Y')),
                    'luas_kawasan_ha'   => (float)str_replace(',', '.', $row[3] ?? '0'),
                    'longitude'         => $row[4] ?? null,
                    'latitude'          => $row[5] ?? null,
                    'wkt'               => $row[0] ?? null,
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

            return redirect()->to('/perumahan-formal')->with('success', "$count data Perumahan berhasil diimpor.");

        } catch (\Exception $e) {
            if (isset($handle)) fclose($handle);
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    public function create()
    {
        if (!has_permission('create_rtlh')) return redirect()->back()->with('error', 'Izin ditolak.');
        return view('perumahan_formal/create', ['title' => 'Tambah Perumahan']);
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
        
        $this->logActivity('Tambah', 'Perumahan', 'Menambah perumahan: ' . $data['nama_perumahan'], $this->formatLogData($data));

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
        $data['title'] = 'Edit Perumahan';
        return view('perumahan_formal/edit', $data);
    }

    public function update($id)
    {
        $oldData = $this->perumahanModel->find($id);
        $newData = $this->request->getPost();
        
        $this->perumahanModel->update($id, $newData);
        
        $diff = $this->generateDiff($oldData, $newData);
        $this->logActivity('Ubah', 'Perumahan', 'Memperbarui data perumahan: ' . $oldData['nama_perumahan'], $diff);

        return redirect()->to('/perumahan-formal')->with('success', 'Data berhasil diperbarui.');
    }

    public function delete($id)
    {
        if (!has_permission('delete_rtlh')) {
            return redirect()->back()->with('error', 'Izin ditolak.');
        }

        $data = $this->perumahanModel->find($id);
        if ($data) {
            $db = \Config\Database::connect();
            $db->table('trash_data')->insert([
                'entity_type' => 'PERUMAHAN',
                'entity_id'   => $id,
                'data_json'   => json_encode($data),
                'deleted_by'  => session()->get('username'),
                'created_at'  => date('Y-m-d H:i:s')
            ]);

            $this->perumahanModel->delete($id);
            $this->logActivity('Hapus', 'Perumahan', 'Memindahkan perumahan ke Recycle Bin: ' . ($data['nama_perumahan'] ?? 'Tanpa Nama'), $this->formatLogData($data));
        }

        return redirect()->to('/perumahan-formal')->with('success', 'Data berhasil dipindahkan ke Recycle Bin.');
    }

    public function bulkDelete()
    {
        if (!has_permission('delete_rtlh')) return $this->response->setJSON(['status' => 'error', 'message' => 'Izin ditolak.']);
        $ids = $this->request->getPost('ids');
        if (empty($ids)) return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak ada data yang dipilih.']);

        $db = \Config\Database::connect();
        $db->transStart();
        try {
            $items = $this->perumahanModel->whereIn('id', $ids)->findAll();
            foreach ($items as $item) {
                $db->table('trash_data')->insert([
                    'entity_type' => 'PERUMAHAN',
                    'entity_id'   => $item['id'],
                    'data_json'   => json_encode($item),
                    'deleted_by'  => session()->get('username'),
                    'created_at'  => date('Y-m-d H:i:s')
                ]);
            }

            $this->perumahanModel->whereIn('id', $ids)->delete();
            $db->transComplete();
            if ($db->transStatus() === FALSE) throw new \Exception('Gagal menghapus data massal.');
            $this->logActivity('Hapus Massal', 'Perumahan', "Memindahkan " . count($ids) . " data perumahan ke Recycle Bin");
            return $this->response->setJSON(['status' => 'success', 'message' => count($ids) . ' data berhasil dipindahkan ke Recycle Bin.']);
        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
