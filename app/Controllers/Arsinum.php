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
        $search = $this->request->getGet('search') ?? '';
        $perPage = $this->request->getGet('per_page') ?? 10;
        $selected_kecamatan = $this->request->getGet('kecamatan') ?? '';
        $sortBy = $this->request->getGet('sort_by') ?? 'id';
        $sortOrder = $this->request->getGet('sort_order') ?? 'desc';

        $model = new ArsinumModel();

        if (!empty($search)) {
            $model->groupStart()
                ->like('jenis_pekerjaan', $search)
                ->orLike('desa', $search)
                ->groupEnd();
        }

        if (!empty($selected_kecamatan)) {
            $model->where('kecamatan', $selected_kecamatan);
        }

        $arsinum = $model->orderBy($sortBy, $sortOrder)->paginate($perPage);
        $pager = $model->pager;

        $db = \Config\Database::connect();
        $kecamatans = $db->table('kode_kecamatan')->select('kecamatan_nama as kecamatan')->distinct()->orderBy('kecamatan_nama', 'ASC')->get()->getResultArray();

        $data = [
            'title' => 'Data ARSINUM',
            'arsinum' => $arsinum,
            'pager' => $pager,
            'arsinum_all' => (new ArsinumModel())->findAll(),
            'perPage' => $perPage,
            'search' => $search,
            'kecamatans' => $kecamatans,
            'selected_kecamatan' => $selected_kecamatan,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'total_unit' => (new ArsinumModel())->countAllResults(false),
            'total_anggaran' => (new ArsinumModel())->selectSum('anggaran')->get()->getRow()->anggaran ?? 0,
        ];

        return view('arsinum/index', $data);
    }

    public function exportExcel()
    {
        $data = $this->arsinumModel->findAll();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Jenis Pekerjaan');
        $sheet->setCellValue('C1', 'Volume');
        $sheet->setCellValue('D1', 'Desa');
        $sheet->setCellValue('E1', 'Kecamatan');
        $sheet->setCellValue('F1', 'Anggaran');
        $sheet->setCellValue('G1', 'Pelaksana');
        $sheet->setCellValue('H1', 'Sumber Dana');
        $sheet->setCellValue('I1', 'Tahun');
        $sheet->setCellValue('J1', 'Koordinat');

        $rowNum = 2;
        foreach ($data as $row) {
            $sheet->setCellValue('A' . $rowNum, $row['id']);
            $sheet->setCellValue('B' . $rowNum, $row['jenis_pekerjaan']);
            $sheet->setCellValue('C' . $rowNum, $row['volume']);
            $sheet->setCellValue('D' . $rowNum, $row['desa']);
            $sheet->setCellValue('E' . $rowNum, $row['kecamatan']);
            $sheet->setCellValue('F' . $rowNum, $row['anggaran']);
            $sheet->setCellValue('G' . $rowNum, $row['pelaksana']);
            $sheet->setCellValue('H' . $rowNum, $row['sumber_dana']);
            $sheet->setCellValue('I' . $rowNum, $row['tahun']);
            $sheet->setCellValue('J' . $rowNum, $row['koordinat']);
            $rowNum++;
        }

        $sheet->getStyle('A1:J1')->getFont()->setBold(true);
        foreach (range('A', 'J') as $col) { $sheet->getColumnDimension($col)->setAutoSize(true); }

        $filename = 'Export_Arsinum_' . date('YmdHis') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function importCsv()
    {
        if (!has_permission('manage_arsinum')) return redirect()->back()->with('error', 'Izin ditolak.');
        
        $file = $this->request->getFile('csv_file');
        if (!$file || !$file->isValid()) return redirect()->back()->with('error', 'File tidak valid.');

        try {
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file->getTempName());
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membaca file: ' . $e->getMessage());
        }

        $count = 0;
        $db = \Config\Database::connect();

        if ($db->table('permukiman_arsinum')->countAllResults() === 0) {
            $db->query("ALTER TABLE permukiman_arsinum AUTO_INCREMENT = 1");
        }

        $db->transStart();
        try {
            foreach ($rows as $rowIndex => $row) {
                if (count($row) < 8 || stripos(implode(' ', array_map(function($v) { return (string)$v; }, $row)), 'JENIS PEKERJAAN') !== false || !is_numeric($row[0] ?? null)) {
                    continue;
                }

                $anggaranRaw = (string)($row[7] ?? '0');
                $anggaran = (float)preg_replace('/[^0-9]/', '', $anggaranRaw);

                $this->arsinumModel->insert([
                    'jenis_pekerjaan' => $row[1] ?? '-',
                    'volume'          => $row[3] ?? '-',
                    'kecamatan'       => $row[4] ?? '-',
                    'desa'            => $row[5] ?? '-',
                    'pelaksana'       => $row[6] ?? '-',
                    'anggaran'        => $anggaran,
                    'sumber_dana'     => $row[8] ?? '-',
                    'koordinat'       => $row[9] ?? null,
                    'tahun'           => isset($row[10]) ? trim((string)$row[10], " \t\n\r\0\x0B;") : date('Y')
                ]);
                $count++;
            }

            $db->transComplete();
            if ($db->transStatus() === false) throw new \Exception('Database Transaction Failed');
            if ($count == 0) return redirect()->back()->with('error', 'Tidak ada data valid yang ditemukan.');

            $this->logActivity('Import', 'Arsinum', "Berhasil mengimpor $count data Arsinum via Excel");
            return redirect()->to('/arsinum')->with('success', "$count data Arsinum berhasil diimpor.");
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function detail($id)
    {
        $data['item'] = $this->arsinumModel->find($id);
        if (!$data['item']) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        $data['title'] = 'Detail Arsinum';
        return view('arsinum/detail', $data);
    }

    public function create()
    {
        return redirect()->to('/arsinum')->with('error', 'Halaman tidak tersedia. Gunakan tombol Tambah.');
    }

    public function store()
    {
        $data = $this->request->getPost();
        
        // Handle Foto Before & After
        $uploadPath = FCPATH . 'uploads/arsinum/';
        if (!is_dir($uploadPath)) mkdir($uploadPath, 0777, true);

        foreach(['foto_after'] as $field) {
            $img = $this->request->getFile($field);
            if ($img && $img->isValid() && !$img->hasMoved()) {
                $newName = strtoupper($field) . '_' . $img->getRandomName();
                $img->move($uploadPath, $newName);
                $data[$field] = $newName;
            }
        }

        $this->arsinumModel->insert($data);
        $this->logActivity('Tambah', 'Arsinum', "Menambah data Arsinum: {$data['jenis_pekerjaan']}");
        return redirect()->to('/arsinum')->with('success', 'Data Arsinum berhasil ditambahkan.');
    }

    public function edit($id)
    {
        return redirect()->to('/arsinum')->with('error', 'Halaman tidak tersedia. Gunakan tombol Edit pada tabel.');
    }

    public function update($id)
    {
        $oldData = $this->arsinumModel->find($id);
        if (!$oldData) return redirect()->back()->with('error', 'Data tidak ditemukan.');

        $newData = $this->request->getPost();
        
        // Handle Foto Before & After
        $uploadPath = FCPATH . 'uploads/arsinum/';
        if (!is_dir($uploadPath)) mkdir($uploadPath, 0777, true);

        foreach(['foto_after'] as $field) {
            $img = $this->request->getFile($field);
            if ($img && $img->isValid() && !$img->hasMoved()) {
                // Hapus foto lama jika ada
                if (!empty($oldData[$field]) && file_exists($uploadPath . $oldData[$field])) {
                    unlink($uploadPath . $oldData[$field]);
                }
                
                $newName = strtoupper($field) . '_' . $img->getRandomName();
                $img->move($uploadPath, $newName);
                $newData[$field] = $newName;
            }
        }

        $this->arsinumModel->update($id, $newData);
        $this->logActivity('Ubah', 'Arsinum', "Memperbarui data Arsinum: " . ($oldData['jenis_pekerjaan'] ?? 'Unknown'));
        
        return redirect()->to('/arsinum')->with('success', 'Data Arsinum berhasil diperbarui.');
    }

    public function delete($id)
    {
        $data = $this->arsinumModel->find($id);
        if ($data) {
            // Hapus foto fisik
            $uploadPath = FCPATH . 'uploads/arsinum/';
            foreach(['foto_after'] as $field) {
                if (!empty($data[$field]) && file_exists($uploadPath . $data[$field])) {
                    unlink($uploadPath . $data[$field]);
                }
            }

            $this->arsinumModel->delete($id);
            $this->logActivity('Hapus', 'Arsinum', "Menghapus data Arsinum: " . ($data['jenis_pekerjaan'] ?? 'Unknown'));
        }
        return redirect()->to('/arsinum')->with('success', 'Data Arsinum berhasil dihapus.');
    }

    public function bulkDelete()
    {
        $ids = $this->request->getPost('ids');
        if (empty($ids)) return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak ada data yang dipilih.']);

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $items = $this->arsinumModel->whereIn('id', $ids)->findAll();
            
            foreach ($items as $item) {
                $db->table('sys_trash')->insert([
                    'entity_type' => 'ARSINUM',
                    'entity_id'   => $item['id'],
                    'data_json'   => json_encode($item),
                    'deleted_by'  => session()->get('username'),
                    'created_at'  => date('Y-m-d H:i:s')
                ]);
            }

            $this->arsinumModel->whereIn('id', $ids)->delete();
            $db->transComplete();

            if ($db->transStatus() === FALSE) throw new \Exception('Gagal menghapus data massal.');

            $this->logActivity('Hapus Massal', 'Arsinum', "Menghapus " . count($ids) . " data Arsinum ke Recycle Bin");

            return $this->response->setJSON(['status' => 'success', 'message' => count($ids) . ' data berhasil dipindahkan ke Recycle Bin.']);
        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
