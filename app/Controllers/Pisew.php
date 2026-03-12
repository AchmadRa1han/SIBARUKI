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

    public function importCsv()
    {
        if (!has_permission('create_rtlh')) return redirect()->back()->with('error', 'Izin ditolak.');
        
        $file = $this->request->getFile('csv_file');
        if (!$file || !$file->isValid()) return redirect()->back()->with('error', 'File tidak valid.');

        $handle = fopen($file->getTempName(), 'r');
        
        // 1. Deteksi Delimiter (Semikolon vs Koma)
        $firstLine = fgets($handle);
        $secondLine = fgets($handle);
        $thirdLine = fgets($handle); // Baris header NO.;JENIS PEKERJAAN;...
        
        $combined = $firstLine . $secondLine . $thirdLine;
        $delimiter = (substr_count($combined, ';') > substr_count($combined, ',')) ? ';' : ',';
        
        rewind($handle);

        $count = 0;
        $db = \Config\Database::connect();

        // Reset Auto Increment jika tabel kosong
        if ($db->table('pisew')->countAllResults() === 0) {
            $db->query("ALTER TABLE pisew AUTO_INCREMENT = 1");
        }

        $db->transStart();

        try {
            while (($row = fgetcsv($handle, 2000, $delimiter)) !== FALSE) {
                // Lewati baris judul (biasanya kata 'PISEW' atau 'DATA KEGIATAN')
                // Lewati baris header (berisi 'JENIS PEKERJAAN')
                // Pastikan kolom pertama (NO) adalah angka
                if (count($row) < 6 || stripos(implode(' ', $row), 'JENIS PEKERJAAN') !== false || !is_numeric($row[0])) {
                    continue;
                }

                /* 
                   Berdasarkan analisis file PISEW KAB. SINJAI 2022-2025.csv:
                   Index 0: NO.
                   Index 1: JENIS PEKERJAAN
                   Index 2: (KOSONG)
                   Index 3: LOKASI (Desa)
                   Index 4: KECAMATAN
                   Index 5: PELAKSANA
                   Index 6: ANGGARAN (Rp.) -> Contoh: 600.000.000
                   Index 7: SUMBER DANA
                   Index 8: TAHUN
                */

                $anggaran = (float)preg_replace('/[^0-9]/', '', $row[6] ?? '0');

                $this->pisewModel->insert([
                    'jenis_pekerjaan' => trim($row[1] ?? '-'),
                    'lokasi_desa'     => trim($row[3] ?? '-'),
                    'kecamatan'       => trim($row[4] ?? '-'),
                    'pelaksana'       => trim($row[5] ?? '-'),
                    'anggaran'        => $anggaran,
                    'tahun'           => trim($row[8] ?? date('Y')),
                    'sumber_dana'     => trim($row[7] ?? '-'),
                ]);
                $count++;
            }
            fclose($handle);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->with('error', 'Gagal menyimpan data ke database.');
            }

            if ($count == 0) {
                return redirect()->back()->with('error', 'Tidak ada data valid yang diimpor. Periksa format CSV.');
            }

            return redirect()->to('/pisew')->with('success', "$count data PISEW berhasil diimpor.");

        } catch (\Exception $e) {
            if (isset($handle)) fclose($handle);
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
            $db = \Config\Database::connect();
            $db->table('trash_data')->insert([
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
                $db->table('trash_data')->insert([
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
