<?php

namespace App\Controllers;

use App\Models\WilayahKumuhModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class WilayahKumuh extends BaseController
{
    protected $kumuhModel;

    public function __construct()
    {
        $this->kumuhModel = new WilayahKumuhModel();
    }

    public function index()
    {
        $keyword = $this->request->getGet('keyword');
        $perPage = $this->request->getGet('per_page') ?? 10;

        $query = $this->kumuhModel;
        if ($keyword) {
            $query = $query->groupStart()
                ->like('Kelurahan', $keyword)
                ->orLike('Kawasan', $keyword)
                ->groupEnd();
        }

        $kumuh = $query->paginate($perPage, 'group1');
        
        $db = \Config\Database::connect();
        $kecamatans = $db->table('kode_kecamatan')->select('kecamatan_nama as kecamatan')->distinct()->orderBy('kecamatan_nama', 'ASC')->get()->getResultArray();

        $data = [
            'title' => 'Data Wilayah Kumuh',
            'kumuh' => $kumuh,
            'kumuh_all' => $this->kumuhModel->findAll(),
            'pager' => $this->kumuhModel->pager,
            'perPage' => $perPage,
            'keyword' => $keyword,
            'kecamatans' => $kecamatans
        ];

        return view('wilayah_kumuh/index', $data);
    }

    public function exportExcel()
    {
        $data = $this->kumuhModel->findAll();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'FID', 'Provinsi', 'Kode Prov', 'Kab/Kota', 'Kode Kab', 
            'Kecamatan', 'Kode Kec', 'Kelurahan', 'Kode Kel', 
            'RT/RW', 'Luas (Ha)', 'Skor', 'Sumber Data', 
            'SK Kumuh', 'Kawasan', 'WKT'
        ];

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }

        $rowNum = 2;
        foreach ($data as $row) {
            $sheet->setCellValue('A' . $rowNum, $row['FID']);
            $sheet->setCellValue('B' . $rowNum, $row['Provinsi']);
            $sheet->setCellValue('C' . $rowNum, $row['Kode_Prov']);
            $sheet->setCellValue('D' . $rowNum, $row['Kab_Kota']);
            $sheet->setCellValue('E' . $rowNum, $row['Kode_Kab']);
            $sheet->setCellValue('F' . $rowNum, $row['Kecamatan']);
            $sheet->setCellValue('G' . $rowNum, $row['Kode_Kec']);
            $sheet->setCellValue('H' . $rowNum, $row['Kelurahan']);
            $sheet->setCellValue('I' . $rowNum, $row['Kode_Kel']);
            $sheet->setCellValue('J' . $rowNum, $row['Kode_RT_RW']);
            $sheet->setCellValue('K' . $rowNum, $row['Luas_kumuh']);
            $sheet->setCellValue('L' . $rowNum, $row['skor_kumuh']);
            $sheet->setCellValue('M' . $rowNum, $row['Sumber_data']);
            $sheet->setCellValue('N' . $rowNum, $row['Sk_Kumuh']);
            $sheet->setCellValue('O' . $rowNum, $row['Kawasan']);
            $sheet->setCellValue('P' . $rowNum, $row['WKT']);
            $rowNum++;
        }

        $sheet->getStyle('A1:P1')->getFont()->setBold(true);
        foreach (range('A', 'P') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Catat Log
        $this->logActivity('Export Excel', 'Wilayah Kumuh', "Mengekspor " . count($data) . " data Wilayah Kumuh Lengkap");

        $filename = 'Export_Lengkap_Wilayah_Kumuh_' . date('YmdHis') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function importCsv()
    {
        if (!has_permission('manage_kumuh')) return redirect()->back()->with('error', 'Izin ditolak.');
        
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

        $aliasMap = [
            'WKT'         => ['wkt', 'geometry', 'geom', 'koordinat'],
            'Provinsi'    => ['provinsi', 'prov'],
            'Kode_Prov'   => ['kode prov', 'kode_prov', 'kode provinsi'],
            'Kab_Kota'    => ['kab_kota', 'kabupaten/kota', 'kabupaten', 'kota', 'kab_kota'],
            'Kode_Kab'    => ['kode kab', 'kode_kab', 'kode kabupaten'],
            'Kecamatan'   => ['kecamatan', 'kec'],
            'Kode_Kec'    => ['kode kec', 'kode_kec', 'kode kecamatan'],
            'Kelurahan'   => ['kelurahan', 'desa', 'kelurahan/desa', 'desa/kelurahan'],
            'Kode_Kel'    => ['kode kel', 'kode_kel', 'kode kelurahan'],
            'Kode_RT_RW'  => ['kode rt/rw', 'kode_rt_rw', 'rt/rw', 'rt rw', 'rt_rw'],
            'Luas_kumuh'  => ['luas kumuh', 'luas_kumuh', 'luas (ha)', 'luas'],
            'skor_kumuh'  => ['skor kumuh', 'skor_kumuh', 'skor', 'nilai'],
            'Sumber_data' => ['sumber data', 'sumber_data', 'sumber'],
            'Sk_Kumuh'    => ['sk kumuh', 'sk_kumuh', 'sk_penetapan', 'sk'],
            'Kawasan'     => ['kawasan', 'nama kawasan', 'nama_kawasan'],
        ];

        $headerPos = [];
        $foundHeader = false;
        $dataStartIndex = 0;

        foreach ($rows as $rowIndex => $row) {
            $rowClean = array_map(function($v) { return strtolower(trim((string)($v ?? ''))); }, $row);
            
            $isHeader = false;
            foreach ($rowClean as $cell) {
                if (in_array($cell, ['wkt', 'geometry', 'kawasan', 'nama kawasan', 'skor kumuh'])) {
                    $isHeader = true;
                    break;
                }
            }
            
            if ($isHeader) {
                foreach ($rowClean as $index => $colName) {
                    foreach ($aliasMap as $field => $aliases) {
                        if ($colName === $field || in_array($colName, $aliases)) {
                            if (!isset($headerPos[$field])) {
                                $headerPos[$field] = $index;
                            }
                        }
                    }
                }
                $foundHeader = true;
                $dataStartIndex = $rowIndex + 1;
                break;
            }
        }

        if (!$foundHeader || !isset($headerPos['Kawasan'])) {
            return redirect()->back()->with('error', 'Format Header Excel/CSV tidak dikenali. Pastikan kolom Kawasan tersedia.');
        }

        $count = 0;
        $db = \Config\Database::connect();

        if ($db->table('permukiman_wilayah_kumuh')->countAllResults() === 0) {
            $db->query("ALTER TABLE permukiman_wilayah_kumuh AUTO_INCREMENT = 1");
        }

        // Get desa lookup
        $allDesa = $db->table('kode_desa')->select('desa_id, desa_nama')->get()->getResultArray();
        $desaLookup = [];
        foreach ($allDesa as $d) {
            $desaLookup[strtoupper(trim($d['desa_nama']))] = $d['desa_id'];
        }

        $db->transStart();
        try {
            $getVal = function($row, $field) use ($headerPos) {
                return isset($headerPos[$field]) ? trim((string)($row[$headerPos[$field]] ?? '')) : null;
            };

            for ($i = $dataStartIndex; $i < count($rows); $i++) {
                $row = $rows[$i];
                
                $kawasan = $getVal($row, 'Kawasan');
                if (empty($kawasan) || $kawasan === '-') continue;

                $wkt = $getVal($row, 'WKT') ?? '';
                if (is_numeric($wkt) && strlen($wkt) < 5) {
                    $wkt = null;
                }

                $kelurahan = $getVal($row, 'Kelurahan') ?? '-';
                $desaId = $desaLookup[strtoupper(trim($kelurahan))] ?? null;

                $luasRaw = $getVal($row, 'Luas_kumuh');
                $luas = $luasRaw ? (float)str_replace(',', '.', $luasRaw) : 0.0;

                $skorRaw = $getVal($row, 'skor_kumuh');
                $skor = $skorRaw ? (float)str_replace(',', '.', $skorRaw) : 0.0;

                $this->kumuhModel->insert([
                    'WKT'         => $wkt,
                    'Provinsi'    => $getVal($row, 'Provinsi') ?? 'Sulawesi Selatan',
                    'Kode_Prov'   => $getVal($row, 'Kode_Prov') ?? '73',
                    'Kab_Kota'    => $getVal($row, 'Kab_Kota') ?? 'Sinjai',
                    'Kode_Kab'    => $getVal($row, 'Kode_Kab') ?? '07',
                    'Kecamatan'   => $getVal($row, 'Kecamatan') ?? '-',
                    'Kode_Kec'    => $getVal($row, 'Kode_Kec') ?? '-',
                    'Kelurahan'   => $kelurahan,
                    'desa_id'     => $desaId,
                    'Kode_Kel'    => $getVal($row, 'Kode_Kel') ?? '-',
                    'Kode_RT_RW'  => $getVal($row, 'Kode_RT_RW') ?? '-',
                    'Luas_kumuh'  => $luas,
                    'skor_kumuh'  => $skor,
                    'Sumber_data' => $getVal($row, 'Sumber_data') ?? '-',
                    'Sk_Kumuh'    => $getVal($row, 'Sk_Kumuh') ?? '-',
                    'Kawasan'     => $kawasan,
                ]);
                $count++;
            }

            $db->transComplete();
            if ($db->transStatus() === false) throw new \Exception('Database Transaction Failed');
            if ($count == 0) return redirect()->back()->with('error', 'Tidak ada data valid yang ditemukan. Pastikan data tidak kosong.');

            $this->logActivity('Import', 'Wilayah Kumuh', "Berhasil mengimpor $count data Wilayah Kumuh via Excel");
            return redirect()->to('/wilayah-kumuh')->with('success', "$count data Wilayah Kumuh berhasil diimpor.");
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function peta()
    {
        $data = [
            'title' => 'Peta Wilayah Kumuh',
            'kumuh' => $this->kumuhModel->findAll(),
        ];
        return view('wilayah_kumuh/peta', $data);
    }

    public function detail($id)
    {
        $data['kumuh'] = $this->kumuhModel->find($id);
        if (!$data['kumuh']) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        $data['title'] = 'Detail Wilayah Kumuh';
        return view('wilayah_kumuh/detail', $data);
    }

    public function print($id)
    {
        $data['kumuh'] = $this->kumuhModel->find($id);
        if (!$data['kumuh']) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        return view('wilayah_kumuh/print_report', $data);
    }

    public function create()
    {
        return view('wilayah_kumuh/create', ['title' => 'Tambah Wilayah Kumuh']);
    }

    public function store()
    {
        $data = $this->request->getPost();
        
        // Handle empty fields for foreign keys or auto-increment
        if (empty($data['FID'])) unset($data['FID']);
        if (empty($data['desa_id'])) $data['desa_id'] = null;

        $this->kumuhModel->insert($data);
        $this->logActivity('Tambah', 'Wilayah Kumuh', "Menambah wilayah kumuh: " . ($data['Kawasan'] ?? 'Tanpa Nama'), $this->formatLogData($data));
        return redirect()->to('/wilayah-kumuh')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $data['kumuh'] = $this->kumuhModel->find($id);
        $data['title'] = 'Edit Wilayah Kumuh';
        return view('wilayah_kumuh/edit', $data);
    }

    public function update($id)
    {
        $oldData = $this->kumuhModel->find($id);
        $newData = $this->request->getPost();
        
        if (empty($newData['desa_id'])) $newData['desa_id'] = null;

        $this->kumuhModel->update($id, $newData);
        
        $diff = $this->generateDiff($oldData, $newData);
        $this->logActivity('Ubah', 'Wilayah Kumuh', "Memperbarui data wilayah kumuh: " . ($oldData['Kawasan'] ?? 'Unknown'), $diff);
        
        return redirect()->to('/wilayah-kumuh/detail/' . $id)->with('success', 'Data berhasil diperbarui.');
    }

    public function delete($id)
    {
        $data = $this->kumuhModel->find($id);
        if ($data) {
            $db = \Config\Database::connect();
            $db->table('sys_trash')->insert([
                'entity_type' => 'KUMUH',
                'entity_id'   => $id,
                'data_json'   => json_encode($data),
                'deleted_by'  => session()->get('username'),
                'created_at'  => date('Y-m-d H:i:s')
            ]);

            $this->kumuhModel->delete($id);
            $this->logActivity('Hapus', 'Wilayah Kumuh', "Memindahkan wilayah kumuh ke Recycle Bin: " . ($data['Kawasan'] ?? 'Unknown'), $this->formatLogData($data));
        }
        return redirect()->to('/wilayah-kumuh')->with('success', 'Data berhasil dipindahkan ke Recycle Bin.');
    }

    public function bulkDelete()
    {
        $ids = $this->request->getPost('ids');
        if (empty($ids)) return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak ada data yang dipilih.']);

        $db = \Config\Database::connect();
        $db->transStart();
        try {
            $items = $this->kumuhModel->whereIn('FID', $ids)->findAll();
            foreach ($items as $item) {
                $db->table('sys_trash')->insert([
                    'entity_type' => 'KUMUH',
                    'entity_id'   => $item['FID'],
                    'data_json'   => json_encode($item),
                    'deleted_by'  => session()->get('username'),
                    'created_at'  => date('Y-m-d H:i:s')
                ]);
            }

            $this->kumuhModel->whereIn('FID', $ids)->delete();
            $db->transComplete();
            if ($db->transStatus() === FALSE) throw new \Exception('Gagal menghapus data massal.');
            $this->logActivity('Hapus Massal', 'Wilayah Kumuh', "Memindahkan " . count($ids) . " data wilayah kumuh ke Recycle Bin");
            return $this->response->setJSON(['status' => 'success', 'message' => count($ids) . ' data berhasil dipindahkan ke Recycle Bin.']);
        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
