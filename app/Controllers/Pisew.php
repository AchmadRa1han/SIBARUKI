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

        $model = new PisewModel();

        if (!empty($search)) {
            $model->groupStart()
                ->like('jenis_pekerjaan', $search)
                ->orLike('lokasi_desa', $search)
                ->groupEnd();
        }

        if (!empty($selected_kecamatan)) {
            $model->where('kecamatan', $selected_kecamatan);
        }

        $pisew = $model->orderBy($sortBy, $sortOrder)->paginate($perPage);
        $pager = $model->pager;

        $db = \Config\Database::connect();
        $kecamatans = $db->table('kode_kecamatan')->select('kecamatan_nama as kecamatan')->distinct()->orderBy('kecamatan_nama', 'ASC')->get()->getResultArray();

        $data = [
            'title' => 'Data PISEW',
            'pisew' => $pisew,
            'pager' => $pager,
            'pisew_all' => (new PisewModel())->findAll(),
            'perPage' => $perPage,
            'search' => $search,
            'kecamatans' => $kecamatans,
            'selected_kecamatan' => $selected_kecamatan,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'total_kegiatan' => (new PisewModel())->countAllResults(false),
            'total_anggaran' => (new PisewModel())->selectSum('anggaran')->get()->getRow()->anggaran ?? 0,
        ];

        return view('pisew/index', $data);
    }

    public function exportExcel()
    {
        $data = $this->pisewModel->findAll();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = ['ID', 'Jenis Pekerjaan', 'Lokasi Desa', 'Kecamatan', 'Pelaksana', 'Anggaran', 'Sumber Dana', 'Tahun', 'Koordinat'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }

        $rowNum = 2;
        foreach ($data as $row) {
            $sheet->setCellValue('A' . $rowNum, $row['id']);
            $sheet->setCellValue('B' . $rowNum, $row['jenis_pekerjaan']);
            $sheet->setCellValue('C' . $rowNum, $row['lokasi_desa']);
            $sheet->setCellValue('D' . $rowNum, $row['kecamatan']);
            $sheet->setCellValue('E' . $rowNum, $row['pelaksana']);
            $sheet->setCellValue('F' . $rowNum, $row['anggaran']);
            $sheet->setCellValue('G' . $rowNum, $row['sumber_dana']);
            $sheet->setCellValue('H' . $rowNum, $row['tahun']);
            $sheet->setCellValue('I' . $rowNum, $row['koordinat']);
            $rowNum++;
        }

        $sheet->getStyle('A1:I1')->getFont()->setBold(true);
        foreach (range('A', 'I') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Catat Log
        $this->logActivity('Export Excel', 'PISEW', "Mengekspor " . count($data) . " data PISEW");

        $filename = 'Export_PISEW_' . date('YmdHis') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function importCsv()
    {
        if (!has_permission('manage_pisew')) return redirect()->back()->with('error', 'Izin ditolak.');
        
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

        if ($db->table('permukiman_pisew')->countAllResults() === 0) {
            $db->query("ALTER TABLE permukiman_pisew AUTO_INCREMENT = 1");
        }

        $db->transStart();
        try {
            foreach ($rows as $rowIndex => $row) {
                if (count($row) < 6 || stripos(implode(' ', array_map(function($v) { return (string)$v; }, $row)), 'JENIS PEKERJAAN') !== false || !is_numeric($row[0] ?? null)) {
                    continue;
                }

                $anggaran = (float)preg_replace('/[^0-9]/', '', (string)($row[6] ?? '0'));

                $this->pisewModel->insert([
                    'jenis_pekerjaan' => trim((string)($row[1] ?? '-')),
                    'lokasi_desa'     => trim((string)($row[3] ?? '-')),
                    'kecamatan'       => trim((string)($row[4] ?? '-')),
                    'pelaksana'       => trim((string)($row[5] ?? '-')),
                    'anggaran'        => $anggaran,
                    'tahun'           => trim((string)($row[8] ?? date('Y'))),
                    'sumber_dana'     => trim((string)($row[7] ?? '-')),
                ]);
                $count++;
            }

            $db->transComplete();
            if ($db->transStatus() === false) throw new \Exception('Database Transaction Failed');
            if ($count == 0) return redirect()->back()->with('error', 'Tidak ada data valid yang ditemukan.');

            $this->logActivity('Import', 'PISEW', "Berhasil mengimpor $count data PISEW via Excel");
            return redirect()->to('/pisew')->with('success', "$count data PISEW berhasil diimpor.");
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
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
        return redirect()->to('/pisew')->with('error', 'Halaman tidak tersedia. Gunakan tombol Tambah.');
    }

    public function edit($id)
    {
        return redirect()->to('/pisew')->with('error', 'Halaman tidak tersedia. Gunakan tombol Edit pada tabel.');
    }

    public function update($id)
    {
        $oldData = $this->pisewModel->find($id);
        $newData = $this->request->getPost();

        // Handle Foto Before & After
        $uploadPath = FCPATH . 'uploads/pisew/';
        if (!is_dir($uploadPath)) mkdir($uploadPath, 0777, true);
        
        foreach (['foto_before', 'foto_after'] as $field) {
            $img = $this->request->getFile($field);
            if ($img && $img->isValid() && !$img->hasMoved()) {
                // Hapus foto lama jika ada
                if (!empty($oldData[$field]) && file_exists($uploadPath . $oldData[$field])) {
                    unlink($uploadPath . $oldData[$field]);
                }
                
                $newName = $img->getRandomName();
                $img->move($uploadPath, $newName);
                $newData[$field] = $newName;
            }
        }

        $this->pisewModel->update($id, $newData);
        
        $diff = $this->generateDiff($oldData, $newData);
        $this->logActivity('Ubah', 'PISEW', "Memperbarui data PISEW: " . ($oldData['jenis_pekerjaan'] ?? 'Unknown'), $diff);
        
        return redirect()->to('/pisew')->with('success', 'Data PISEW berhasil diperbarui.');
    }

    public function delete($id)
    {
        $data = $this->pisewModel->find($id);
        if ($data) {
            // Hapus foto fisik
            $uploadPath = FCPATH . 'uploads/pisew/';
            foreach (['foto_before', 'foto_after', 'foto'] as $f) {
                if (!empty($data[$f])) {
                    $filePath = $uploadPath . $data[$f];
                    if (file_exists($filePath)) unlink($filePath);
                }
            }

            $db = \Config\Database::connect();
            $db->table('sys_trash')->insert([
                'entity_type' => 'PISEW',
                'entity_id'   => $id,
                'data_json'   => json_encode($data),
                'deleted_by'  => session()->get('username'),
                'created_at'  => date('Y-m-d H:i:s')
            ]);

            $this->pisewModel->delete($id);
            $this->logActivity('Hapus', 'PISEW', "Memindahkan data PISEW ke Recycle Bin: " . ($data['jenis_pekerjaan'] ?? 'Unknown'), $this->formatLogData($data));
        }
        return redirect()->to('/pisew')->with('success', 'Data berhasil dipindahkan ke Recycle Bin.');
    }

    public function bulkDelete()
    {
        $ids = $this->request->getPost('ids');
        if (empty($ids)) return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak ada data yang dipilih.']);

        $db = \Config\Database::connect();
        $db->transStart();
        try {
            $items = $this->pisewModel->whereIn('id', $ids)->findAll();
            foreach ($items as $item) {
                $db->table('sys_trash')->insert([
                    'entity_type' => 'PISEW',
                    'entity_id'   => $item['id'],
                    'data_json'   => json_encode($item),
                    'deleted_by'  => session()->get('username'),
                    'created_at'  => date('Y-m-d H:i:s')
                ]);
            }

            $this->pisewModel->whereIn('id', $ids)->delete();
            $db->transComplete();
            if ($db->transStatus() === FALSE) throw new \Exception('Gagal menghapus data massal.');
            $this->logActivity('Hapus Massal', 'PISEW', "Memindahkan " . count($ids) . " data PISEW ke Recycle Bin");
            return $this->response->setJSON(['status' => 'success', 'message' => count($ids) . ' data berhasil dipindahkan ke Recycle Bin.']);
        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
