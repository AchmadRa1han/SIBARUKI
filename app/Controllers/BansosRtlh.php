<?php

namespace App\Controllers;

use App\Models\BansosRtlhModel;
use App\Models\RumahRtlhModel;
use App\Models\RtlhPenerimaModel;
use App\Models\RtlhHistoryModel;
use App\Models\KondisiRumahModel;

class BansosRtlh extends BaseController
{
    protected $bansosModel;
    protected $rumahModel;
    protected $penerimaModel;
    protected $historyModel;
    protected $kondisiModel;

    public function __construct()
    {
        $this->bansosModel = new BansosRtlhModel();
        $this->rumahModel = new RumahRtlhModel();
        $this->penerimaModel = new RtlhPenerimaModel();
        $this->historyModel = new RtlhHistoryModel();
        $this->kondisiModel = new KondisiRumahModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();
        $keyword = $this->request->getGet('keyword');
        $query = $this->bansosModel->orderBy('tahun_anggaran', 'DESC');

        if ($keyword) {
            $query->like('nama_penerima', $keyword)
                  ->orLike('desa', $keyword)
                  ->orLike('nik', $keyword);
        }

        $rtlh = $db->table('perumahan_rtlh_rumah')
                   ->select('perumahan_rtlh_rumah.id_survei, perumahan_rtlh_rumah.nik_pemilik as nik, perumahan_rtlh_rumah.desa, perumahan_rtlh_penerima.nama_kepala_keluarga')
                   ->join('perumahan_rtlh_penerima', 'perumahan_rtlh_penerima.nik = perumahan_rtlh_rumah.nik_pemilik', 'left')
                   ->whereIn('perumahan_rtlh_rumah.status_bantuan', ['Belum Menerima', 'Target', 'Rtlh'])
                   ->get()->getResultArray();

        $data = [
            'title' => 'Bansos Perbaikan RTLH',
            'bansos' => $query->paginate(10, 'default'),
            'pager' => $this->bansosModel->pager,
            'keyword' => $keyword,
            'rtlh' => $rtlh
        ];

        return view('bansos_rtlh/index', $data);
    }

    public function create()
    {
        return redirect()->to('/bansos-rtlh')->with('error', 'Halaman tidak tersedia. Gunakan tombol Input Realisasi.');
    }

    public function store()
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $id_survei = $this->request->getPost('id_survei');
        $nik = $this->request->getPost('nik');
        $nama = $this->request->getPost('nama_penerima');
        $desa = $this->request->getPost('desa');
        $tahun = $this->request->getPost('tahun_anggaran');
        $sumber = $this->request->getPost('sumber_dana');
        $koordinat = $this->request->getPost('lokasi_realisasi');

        // 1. Persiapkan Data Realisasi
        $dataBansos = [
            'id_survei' => $id_survei ?: null,
            'nik' => $nik,
            'nama_penerima' => $nama,
            'desa' => $desa,
            'tahun_anggaran' => $tahun,
            'sumber_dana' => $sumber,
            'keterangan' => $this->request->getPost('keterangan'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Handle Upload Foto Before & After (3 Positions)
        $uploadPath = FCPATH . 'uploads/rtlh/';
        if (!is_dir($uploadPath)) mkdir($uploadPath, 0777, true);

        $photoFields = ['foto_before', 'foto_after', 'foto_setelah_depan', 'foto_setelah_samping', 'foto_setelah_dalam'];
        foreach($photoFields as $field) {
            $img = $this->request->getFile($field);
            if ($img && $img->isValid() && !$img->hasMoved()) {
                $prefix = strtoupper(str_replace('foto_', '', $field));
                $newName = $prefix . '_' . $img->getRandomName();
                $img->move($uploadPath, $newName);
                $dataBansos[$field] = $newName;
                
                // Sync foto_after with foto_setelah_depan if not provided
                if ($field === 'foto_setelah_depan' && empty($dataBansos['foto_after'])) {
                    $dataBansos['foto_after'] = $newName;
                }
            }
        }

        // Simpan ke tabel bansos
        $this->bansosModel->insert($dataBansos);
        $bansosId = $this->bansosModel->getInsertID();

        // Simpan Koordinat Realisasi jika ada (POINT WKT)
        if (!empty($koordinat) && preg_match('/POINT\s*\(\s*-?\d+\.?\d*\s+-?\d+\.?\d*\s*\)/i', $koordinat)) {
            $db->table('perumahan_rtlh_bansos')->where('id', $bansosId)
               ->set('lokasi_realisasi', "ST_GeomFromText('{$koordinat}')", false)
               ->update();
        }

        // 2. Jika terhubung ke data survei RTLH, update statusnya otomatis
        $targetId = $id_survei;
        if (!$targetId) {
            $existing = $this->rumahModel->where('nik_pemilik', $nik)->first();
            if ($existing) $targetId = $existing['id_survei'];
        }

        if ($targetId) {
            // Capture Snapshot Sebelum
            $rumahData = $db->table('perumahan_rtlh_rumah')
                            ->select('perumahan_rtlh_rumah.*, ST_AsText(lokasi_koordinat) as lokasi_koordinat')
                            ->where('id_survei', $targetId)
                            ->get()->getRowArray();
            
            if ($rumahData) {
                $kondisi = $this->kondisiModel->where('id_survei', $targetId)->first();
                $penerima = $this->penerimaModel->where('nik', $rumahData['nik_pemilik'])->first();

                $snapshot = ['rumah' => $rumahData, 'kondisi' => $kondisi, 'penerima' => $penerima];

                // Update Status RTLH
                $db->table('perumahan_rtlh_rumah')->where('id_survei', $targetId)->update([
                    'status_bantuan' => 'Rlh',
                    'tahun_bansos' => $tahun,
                    'bantuan_perumahan' => $sumber,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                // Save to History
                $this->historyModel->insert([
                    'id_survei' => $targetId,
                    'nik' => $nik,
                    'nama_penerima' => $nama,
                    'sumber_bantuan' => $sumber,
                    'tahun_anggaran' => $tahun,
                    'data_sebelum' => json_encode($snapshot),
                    'keterangan' => 'Transformasi via Modul Bansos',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
        }

        $db->transComplete();

        if ($db->transStatus() === FALSE) {
            return redirect()->back()->with('error', 'Gagal menyimpan data bansos.');
        }

        $this->logActivity('Input Bansos', 'Bansos', "Menginput realisasi bansos untuk $nama ($nik)");

        return redirect()->to('/bansos-rtlh')->with('success', 'Data realisasi bansos berhasil disimpan dan status RTLH diperbarui.');
    }

    public function detail($id)
    {
        $db = \Config\Database::connect();
        $bansos = $db->table('perumahan_rtlh_bansos')
                     ->select('perumahan_rtlh_bansos.*, ST_AsText(lokasi_realisasi) as wkt_realisasi')
                     ->where('id', $id)
                     ->get()->getRowArray();

        if (!$bansos) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        // Overwrite binary geometry data with string WKT to prevent json_encode failures
        $bansos['lokasi_realisasi'] = $bansos['wkt_realisasi'];

        $rumah = null;
        if ($bansos['id_survei']) {
            $rumah = $this->rumahModel->find($bansos['id_survei']);
        }

        // Fetch candidate RTLH records for the modal edit dropdown
        $rtlh = $db->table('perumahan_rtlh_rumah')
                   ->select('perumahan_rtlh_rumah.id_survei, perumahan_rtlh_rumah.nik_pemilik as nik, perumahan_rtlh_rumah.desa, perumahan_rtlh_penerima.nama_kepala_keluarga')
                   ->join('perumahan_rtlh_penerima', 'perumahan_rtlh_penerima.nik = perumahan_rtlh_rumah.nik_pemilik', 'left')
                   ->whereIn('perumahan_rtlh_rumah.status_bantuan', ['Belum Menerima', 'Target', 'Rtlh'])
                   ->get()->getResultArray();

        return view('bansos_rtlh/detail', [
            'title' => 'Detail Realisasi Bansos',
            'bansos' => $bansos,
            'rumah' => $rumah,
            'rtlh' => $rtlh
        ]);
    }

    public function print($id)
    {
        $db = \Config\Database::connect();
        $bansos = $db->table('perumahan_rtlh_bansos')
                     ->select('perumahan_rtlh_bansos.*, ST_AsText(lokasi_realisasi) as wkt_realisasi')
                     ->where('id', $id)
                     ->get()->getRowArray();

        if (!$bansos) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $rumah = null;
        if ($bansos['id_survei']) {
            $rumah = $this->rumahModel->find($bansos['id_survei']);
        }

        return view('bansos_rtlh/print_report', [
            'bansos' => $bansos,
            'rumah' => $rumah
        ]);
    }

    public function delete($id)
    {
        $this->bansosModel->delete($id);
        return redirect()->to('/bansos-rtlh')->with('success', 'Data bansos berhasil dihapus.');
    }

    public function bulkDelete()
    {
        $ids = $this->request->getPost('ids');
        if (empty($ids)) return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak ada data yang dipilih.']);

        $db = \Config\Database::connect();
        $db->transStart();
        try {
            $this->bansosModel->whereIn('id', $ids)->delete();
            $db->transComplete();
            if ($db->transStatus() === FALSE) throw new \Exception('Gagal menghapus data massal.');
            $this->logActivity('Hapus Massal', 'Bansos', "Menghapus " . count($ids) . " data realisasi bansos");
            return $this->response->setJSON(['status' => 'success', 'message' => count($ids) . ' data berhasil dihapus.']);
        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        return redirect()->to('/bansos-rtlh')->with('error', 'Halaman tidak tersedia. Gunakan tombol Edit pada halaman detail.');
    }

    public function update($id)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $oldData = $this->bansosModel->find($id);
        if (!$oldData) return redirect()->back()->with('error', 'Data tidak ditemukan.');

        $id_survei = $this->request->getPost('id_survei');
        $nik = $this->request->getPost('nik');
        $nama = $this->request->getPost('nama_penerima');
        $desa = $this->request->getPost('desa');
        $tahun = $this->request->getPost('tahun_anggaran');
        $sumber = $this->request->getPost('sumber_dana');
        $koordinat = $this->request->getPost('lokasi_realisasi');

        $dataBansos = [
            'id_survei' => $id_survei ?: null,
            'nik' => $nik,
            'nama_penerima' => $nama,
            'desa' => $desa,
            'tahun_anggaran' => $tahun,
            'sumber_dana' => $sumber,
            'keterangan' => $this->request->getPost('keterangan'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Handle Upload Foto
        $uploadPath = FCPATH . 'uploads/rtlh/';
        $photoFields = ['foto_before', 'foto_after', 'foto_setelah_depan', 'foto_setelah_samping', 'foto_setelah_dalam'];
        foreach($photoFields as $field) {
            $img = $this->request->getFile($field);
            if ($img && $img->isValid() && !$img->hasMoved()) {
                // Hapus foto lama
                if (!empty($oldData[$field]) && file_exists($uploadPath . $oldData[$field])) {
                    @unlink($uploadPath . $oldData[$field]);
                }
                
                $prefix = strtoupper(str_replace('foto_', '', $field));
                $newName = $prefix . '_' . $img->getRandomName();
                $img->move($uploadPath, $newName);
                $dataBansos[$field] = $newName;

                // Sync foto_after with foto_setelah_depan if not provided
                if ($field === 'foto_setelah_depan' && empty($dataBansos['foto_after'])) {
                    $dataBansos['foto_after'] = $newName;
                }
            }
        }

        $this->bansosModel->update($id, $dataBansos);

        // Update Koordinat Realisasi (POINT WKT)
        if (!empty($koordinat) && preg_match('/POINT\s*\(\s*-?\d+\.?\d*\s+-?\d+\.?\d*\s*\)/i', $koordinat)) {
            $db->table('perumahan_rtlh_bansos')->where('id', $id)
               ->set('lokasi_realisasi', "ST_GeomFromText('{$koordinat}')", false)
               ->update();
        }

        $db->transComplete();

        if ($db->transStatus() === FALSE) {
            return redirect()->back()->with('error', 'Gagal memperbarui data bansos.');
        }

        $this->logActivity('Ubah Bansos', 'Bansos', "Memperbarui data bansos untuk $nama ($nik)");

        return redirect()->to('/bansos-rtlh/detail/' . $id)->with('success', 'Data bansos berhasil diperbarui.');
    }
}
