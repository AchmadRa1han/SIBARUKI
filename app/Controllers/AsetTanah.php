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
                ->orLike('lokasi', $search)
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

        $headers = [
            'ID', 'No Sertifikat', 'Nama Pemilik / Instansi', 'Luas (m2)', 
            'Lokasi / Alamat', 'Desa / Kelurahan', 'Kecamatan', 'Tgl Terbit Sertifikat', 
            'Nomor Hak', 'Peruntukan', 'Koordinat', 'Nilai Aset (Rp)', 'Status Tanah', 'Keterangan'
        ];
        
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }

        $rowNum = 2;
        foreach ($data as $row) {
            $sheet->setCellValue('A' . $rowNum, $row['id']);
            $sheet->setCellValue('B' . $rowNum, $row['no_sertifikat']);
            $sheet->setCellValue('C' . $rowNum, $row['nama_pemilik']);
            $sheet->setCellValue('D' . $rowNum, $row['luas_m2']);
            $sheet->setCellValue('E' . $rowNum, $row['lokasi']);
            $sheet->setCellValue('F' . $rowNum, $row['desa_kelurahan']);
            $sheet->setCellValue('G' . $rowNum, $row['kecamatan']);
            $sheet->setCellValue('H' . $rowNum, $row['tgl_terbit']);
            $sheet->setCellValue('I' . $rowNum, $row['nomor_hak']);
            $sheet->setCellValue('J' . $rowNum, $row['peruntukan']);
            $sheet->setCellValue('K' . $rowNum, $row['koordinat']);
            $sheet->setCellValue('L' . $rowNum, $row['nilai_aset']);
            $sheet->setCellValue('M' . $rowNum, $row['status_tanah']);
            $sheet->setCellValue('N' . $rowNum, $row['keterangan']);
            $rowNum++;
        }

        $sheet->getStyle('A1:N1')->getFont()->setBold(true);
        foreach (range('A', 'N') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Catat Log
        $this->logActivity('Export Excel', 'Aset Tanah', "Mengekspor " . count($data) . " data Aset Tanah Lengkap");

        $filename = 'Export_Lengkap_Aset_Tanah_' . date('YmdHis') . '.xlsx';
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
                // Lewati header atau baris judul
                if (count($row) < 10 || stripos(implode(' ', $row), 'Sertifikat') !== false || !is_numeric($row[0])) {
                    continue;
                }

                // Bersihkan format angka Indonesia (misal: 6.890,00 -> 6890.00)
                $luasRaw = $row[3] ?? '0';
                $luas = (float)str_replace(',', '.', str_replace('.', '', $luasRaw));

                $nilaiRaw = $row[12] ?? '0';
                $nilai = (float)str_replace(',', '.', str_replace('.', '', $nilaiRaw));

                // Parsing Tanggal (d-m-Y -> Y-m-d)
                $tglTerbit = null;
                $tglRaw = trim($row[7] ?? '');
                if ($tglRaw) {
                    $dt = \DateTime::createFromFormat('d-m-Y', $tglRaw);
                    if ($dt) $tglTerbit = $dt->format('Y-m-d');
                }

                // Gabungkan & Sembuhkan Koordinat (Hilangkan titik ribuan pada lat/lng)
                $lonRaw = trim($row[10] ?? '');
                $latRaw = trim($row[11] ?? '');
                
                $lon = str_replace(',', '.', $lonRaw);
                $lat = str_replace(',', '.', $latRaw);
                
                // Jika lat/lng punya > 1 titik, asumsikan titik pertama adalah pemisah ribuan yang salah
                if (substr_count($lat, '.') > 1) {
                    $firstDot = strpos($lat, '.');
                    $lat = substr($lat, 0, $firstDot) . substr($lat, $firstDot + 1);
                }
                if (substr_count($lon, '.') > 1) {
                    $firstDot = strpos($lon, '.');
                    $lon = substr($lon, 0, $firstDot) . substr($lon, $firstDot + 1);
                }

                $coords = ($lat && $lon) ? "$lat, $lon" : null;

                $this->asetModel->insert([
                    'no_sertifikat'  => trim($row[1] ?? '-'),
                    'nama_pemilik'   => trim($row[2] ?? '-'),
                    'luas_m2'        => $luas,
                    'lokasi'         => trim($row[4] ?? '-'),
                    'desa_kelurahan' => trim($row[5] ?? '-'),
                    'kecamatan'      => trim($row[6] ?? '-'),
                    'tgl_terbit'     => $tglTerbit,
                    'nomor_hak'      => trim($row[8] ?? '-'),
                    'peruntukan'     => trim($row[9] ?? '-'),
                    'koordinat'      => $coords,
                    'nilai_aset'     => $nilai,
                    'status_tanah'   => trim($row[13] ?? '-'),
                    'keterangan'     => trim($row[14] ?? '-'),
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

            $this->logActivity('Import', 'Aset Tanah', "Berhasil mengimpor $count data Aset Tanah");

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
        if (!$data['aset']) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
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
