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

        // Reset Auto Increment jika tabel kosong
        if ($db->table('aset_tanah')->countAllResults() === 0) {
            $db->query("ALTER TABLE aset_tanah AUTO_INCREMENT = 1");
        }

        $db->transStart();

        try {
            $handle = fopen($file->getTempName(), 'r');
            while (($row = fgetcsv($handle, 2000, $delimiter)) !== FALSE) {
                if (count($row) < 10 || stripos(implode(' ', $row), 'Sertifikat') !== false || !is_numeric($row[0])) {
                    continue;
                }

                // Bersihkan format angka Indonesia (misal: 6.890,00 -> 6890.00)
                $luasRaw = $row[3] ?? '0';
                $luas = (float)str_replace(',', '.', str_replace('.', '', $luasRaw));

                $nilaiRaw = $row[12] ?? '0';
                $nilai = (float)str_replace(',', '.', str_replace('.', '', $nilaiRaw));

                $this->asetModel->insert([
                    'no_sertifikat'  => $row[1] ?? '-',
                    'nama_pemilik'   => $row[2] ?? '-',
                    'luas_m2'        => $luas,
                    'lokasi'         => $row[4] ?? '-',
                    'desa_kelurahan' => $row[5] ?? '-',
                    'kecamatan'      => $row[6] ?? '-',
                    'tgl_terbit'     => $row[7] ?? null,
                    'longitude'      => $row[10] ?? null,
                    'latitude'       => $row[11] ?? null,
                    'nilai_aset'     => $nilai,
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

            return redirect()->to('/aset-tanah')->with('success', "$count data Aset Tanah berhasil diimpor.");

        } catch (\Exception $e) {
            if (isset($handle)) fclose($handle);
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
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
            $db = \Config\Database::connect();
            $db->table('trash_data')->insert([
                'entity_type' => 'ASET_TANAH',
                'entity_id'   => $id,
                'data_json'   => json_encode($data),
                'deleted_by'  => session()->get('username'),
                'created_at'  => date('Y-m-d H:i:s')
            ]);

            $this->asetModel->delete($id);
            $this->logActivity('Hapus', 'Aset Tanah', "Memindahkan data aset ke Recycle Bin: " . ($data['nama_pemilik'] ?? 'Unknown'), $this->formatLogData($data));
        }
        return redirect()->to('/aset-tanah')->with('success', 'Data berhasil dipindahkan ke Recycle Bin.');
    }

    public function bulkDelete()
    {
        $ids = $this->request->getPost('ids');
        if (empty($ids)) return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak ada data yang dipilih.']);

        $db = \Config\Database::connect();
        $db->transStart();
        try {
            $items = $this->asetModel->whereIn('id', $ids)->findAll();
            foreach ($items as $item) {
                $db->table('trash_data')->insert([
                    'entity_type' => 'ASET_TANAH',
                    'entity_id'   => $item['id'],
                    'data_json'   => json_encode($item),
                    'deleted_by'  => session()->get('username'),
                    'created_at'  => date('Y-m-d H:i:s')
                ]);
            }

            $this->asetModel->whereIn('id', $ids)->delete();
            $db->transComplete();
            if ($db->transStatus() === FALSE) throw new \Exception('Gagal menghapus data massal.');
            $this->logActivity('Hapus Massal', 'Aset Tanah', "Memindahkan " . count($ids) . " data aset ke Recycle Bin");
            return $this->response->setJSON(['status' => 'success', 'message' => count($ids) . ' data berhasil dipindahkan ke Recycle Bin.']);
        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
