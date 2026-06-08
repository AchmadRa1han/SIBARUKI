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
        $status_sertifikat = $this->request->getGet('status_sertifikat') ?? 'semua';
        $sortBy = $this->request->getGet('sort_by') ?? 'id';
        $sortOrder = $this->request->getGet('sort_order') ?? 'desc';

        $db = \Config\Database::connect();
        $total_count = (new AsetTanahModel())->countAllResults();
        $count_bersertifikat = (new AsetTanahModel())->where('no_sertifikat !=', 'Belum Bersertifikat')->countAllResults();
        $count_belum_bersertifikat = (new AsetTanahModel())->where('no_sertifikat', 'Belum Bersertifikat')->countAllResults();
        
        $pct_bersertifikat = $total_count > 0 ? ($count_bersertifikat / $total_count) * 100 : 0;
        $pct_belum_bersertifikat = $total_count > 0 ? ($count_belum_bersertifikat / $total_count) * 100 : 0;

        $kecamatans_spasial = $db->table('permukiman_wilayah_kumuh')
            ->select('Kecamatan as nama, Kelurahan as desa, WKT as wkt')
            ->groupBy(['Kelurahan', 'Kecamatan', 'WKT'])
            ->get()->getResultArray();

        $mainQuery = new AsetTanahModel();
        if ($search) {
            $mainQuery->groupStart()
                ->like('nama_pemilik', $search)
                ->orLike('no_sertifikat', $search)
                ->orLike('lokasi', $search)
                ->groupEnd();
        }

        if ($selected_kecamatan) {
            $mainQuery->where('kecamatan', $selected_kecamatan);
        }

        if ($status_sertifikat === 'Bersertifikat') {
            $mainQuery->where('no_sertifikat !=', 'Belum Bersertifikat');
        } elseif ($status_sertifikat === 'Belum Bersertifikat') {
            $mainQuery->where('no_sertifikat', 'Belum Bersertifikat');
        }

        $mapQuery = clone $mainQuery;

        // Fetch All Wilayah from Master Table (kode_kecamatan)
        $kecamatans = $db->table('kode_kecamatan')
                        ->select('kecamatan_nama as kecamatan')
                        ->distinct()
                        ->orderBy('kecamatan_nama', 'ASC')
                        ->get()->getResultArray();

        $data = [
            'title' => 'Data Aset Tanah',
            'aset' => $mainQuery->orderBy($sortBy, $sortOrder)->paginate($perPage, 'group1'),
            'aset_all' => $mapQuery->select('id, nama_pemilik, no_sertifikat, koordinat')->findAll(),
            'pager' => $mainQuery->pager, 
            'perPage' => $perPage,
            'search' => $search,
            'kecamatans' => $kecamatans,
            'selected_kecamatan' => $selected_kecamatan,
            'status_sertifikat' => $status_sertifikat,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'total_aset' => $total_count,
            'total_luas' => (new AsetTanahModel())->selectSum('luas_m2')->get()->getRow()->luas_m2 ?? 0,
            'total_nilai' => (new AsetTanahModel())->selectSum('nilai_aset')->get()->getRow()->nilai_aset ?? 0,
            'count_bersertifikat' => $count_bersertifikat,
            'count_belum_bersertifikat' => $count_belum_bersertifikat,
            'pct_bersertifikat' => $pct_bersertifikat,
            'pct_belum_bersertifikat' => $pct_belum_bersertifikat,
            'kecamatans_spasial' => $kecamatans_spasial
        ];

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 'success',
                'html' => view('aset_tanah/index', $data),
                'data' => [
                    'count_bersertifikat' => $count_bersertifikat,
                    'count_belum_bersertifikat' => $count_belum_bersertifikat,
                    'pct_bersertifikat' => round($pct_bersertifikat, 1),
                    'pct_belum_bersertifikat' => round($pct_belum_bersertifikat, 1),
                    'aset_all' => $data['aset_all'],
                    'kecamatans_spasial' => $kecamatans_spasial
                ]
            ]);
        }

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
        if (!has_permission('manage_asettanah')) return redirect()->back()->with('error', 'Izin ditolak.');
        
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

        if ($db->table('pertanahan_aset')->countAllResults() === 0) {
            $db->query("ALTER TABLE pertanahan_aset AUTO_INCREMENT = 1");
        }

        $db->transStart();
        try {
            foreach ($rows as $rowIndex => $row) {
                if (count($row) < 10 || stripos(implode(' ', array_map(function($v) { return (string)$v; }, $row)), 'Sertifikat') !== false || !is_numeric($row[0] ?? null)) {
                    continue;
                }

                $luasRaw = (string)($row[3] ?? '0');
                $luas = (float)str_replace(',', '.', str_replace('.', '', $luasRaw));

                $nilaiRaw = (string)($row[12] ?? '0');
                $nilai = (float)str_replace(',', '.', str_replace('.', '', $nilaiRaw));

                $tglTerbit = null;
                $tglRaw = trim((string)($row[7] ?? ''));
                if ($tglRaw) {
                    $dt = \DateTime::createFromFormat('d-m-Y', $tglRaw);
                    if (!$dt) $dt = \DateTime::createFromFormat('Y-m-d', $tglRaw);
                    if ($dt) $tglTerbit = $dt->format('Y-m-d');
                }

                $lonRaw = trim((string)($row[10] ?? ''));
                $latRaw = trim((string)($row[11] ?? ''));
                
                $lon = str_replace(',', '.', $lonRaw);
                $lat = str_replace(',', '.', $latRaw);
                
                if (substr_count($lat, '.') > 1) {
                    $firstDot = strpos($lat, '.');
                    $lat = substr($lat, 0, $firstDot + 1) . str_replace('.', '', substr($lat, $firstDot + 1));
                }
                if (substr_count($lon, '.') > 1) {
                    $firstDot = strpos($lon, '.');
                    $lon = substr($lon, 0, $firstDot + 1) . str_replace('.', '', substr($lon, $firstDot + 1));
                }

                $this->asetModel->insert([
                    'no_sertifikat'  => trim((string)($row[1] ?? '-')),
                    'nama_pemilik'   => trim((string)($row[2] ?? '-')),
                    'luas_m2'        => $luas,
                    'lokasi'         => trim((string)($row[4] ?? '-')),
                    'desa_kelurahan' => trim((string)($row[5] ?? '-')),
                    'kecamatan'      => trim((string)($row[6] ?? '-')),
                    'tgl_terbit'     => $tglTerbit,
                    'nomor_hak'      => trim((string)($row[8] ?? '-')),
                    'peruntukan'     => trim((string)($row[9] ?? '-')),
                    'koordinat'      => (is_numeric($lat) && is_numeric($lon)) ? "$lat, $lon" : null,
                    'nilai_aset'     => $nilai,
                    'status_tanah'   => trim((string)($row[13] ?? '-')),
                    'keterangan'     => trim((string)($row[14] ?? '-')),
                ]);
                $count++;
            }

            $db->transComplete();
            if ($db->transStatus() === false) throw new \Exception('Database Transaction Failed');
            if ($count == 0) return redirect()->back()->with('error', 'Tidak ada data valid yang ditemukan.');

            $this->logActivity('Import', 'Aset Tanah', "Berhasil mengimpor $count data Aset Tanah");
            return redirect()->to('/aset-tanah')->with('success', "$count data Aset berhasil diimpor.");
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function detail($id)
    {
        $db = \Config\Database::connect();
        $aset = $this->asetModel->find($id);
        if (!$aset) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        // Fetch All Wilayah from Master Table
        $kecamatans = $db->table('kode_kecamatan')->select('kecamatan_nama as kecamatan')->distinct()->orderBy('kecamatan_nama', 'ASC')->get()->getResultArray();

        return view('aset_tanah/detail', [
            'title' => 'Detail Aset Tanah',
            'aset' => $aset,
            'kecamatans' => $kecamatans
        ]);
    }

    public function print($id)
    {
        $aset = $this->asetModel->find($id);
        if (!$aset) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        return view('aset_tanah/print_report', [
            'aset' => $aset
        ]);
    }

    public function create()
    {
        return redirect()->to('/aset-tanah')->with('error', 'Halaman tidak tersedia. Gunakan tombol Tambah.');
    }

    public function edit($id)
    {
        return redirect()->to('/aset-tanah')->with('error', 'Halaman tidak tersedia. Gunakan tombol Edit pada halaman detail.');
    }

    public function getDesaByKecamatan()
    {
        $kecamatanNama = $this->request->getGet('kecamatan');
        $db = \Config\Database::connect();
        
        // Find kecamatan_id from name
        $kec = $db->table('kode_kecamatan')->where('kecamatan_nama', $kecamatanNama)->get()->getRowArray();
        if (!$kec) return $this->response->setJSON([]);

        $desas = $db->table('kode_desa')
                    ->select('desa_nama')
                    ->where('kecamatan_id', $kec['kecamatan_id'])
                    ->orderBy('desa_nama', 'ASC')
                    ->get()
                    ->getResultArray();
        
        return $this->response->setJSON($desas);
    }

    public function store()
    {
        if (!has_permission('manage_asettanah')) return redirect()->back()->with('error', 'Izin ditolak.');
        $data = $this->request->getPost();
        $this->asetModel->insert($data);
        $this->logActivity('Tambah', 'Aset Tanah', "Menambah aset tanah baru: " . ($data['nama_pemilik'] ?? 'Untitled'), $this->formatLogData($data));
        return redirect()->to('/aset-tanah')->with('success', 'Data aset berhasil ditambahkan.');
    }

    public function update($id)
    {
        if (!has_permission('manage_asettanah')) return redirect()->back()->with('error', 'Izin ditolak.');
        
        $oldData = $this->asetModel->find($id);
        $data = $this->request->getPost();
        
        $this->asetModel->update($id, $data);
        
        $diff = $this->generateDiff($oldData, $data);
        $this->logActivity('Ubah', 'Aset Tanah', "Memperbarui data aset: " . ($oldData['nama_pemilik'] ?? 'Unknown'), $diff);
        
        return redirect()->to('/aset-tanah/detail/' . $id)->with('success', 'Data aset berhasil diperbarui.');
    }

    public function delete($id)
    {
        $data = $this->asetModel->find($id);
        if ($data) {
            $db = \Config\Database::connect();
            $db->table('sys_trash')->insert([
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
                $db->table('sys_trash')->insert([
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
