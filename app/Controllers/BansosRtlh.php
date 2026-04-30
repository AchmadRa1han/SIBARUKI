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
        $keyword = $this->request->getGet('keyword');
        $query = $this->bansosModel->orderBy('tahun_anggaran', 'DESC');

        if ($keyword) {
            $query->like('nama_penerima', $keyword)
                  ->orLike('desa', $keyword)
                  ->orLike('nik', $keyword);
        }

        $data = [
            'title' => 'Bansos Perbaikan RTLH',
            'bansos' => $query->paginate(10, 'default'),
            'pager' => $this->bansosModel->pager,
            'keyword' => $keyword
        ];

        return view('bansos_rtlh/index', $data);
    }

    public function create()
    {
        // Cari data RTLH yang belum menerima bantuan untuk pilihan dropdown
        $rtlh = $this->rumahModel->select('rtlh_rumah.id_survei, rtlh_rumah.desa, rtlh_penerima.nama_kepala_keluarga, rtlh_penerima.nik')
                                ->join('rtlh_penerima', 'rtlh_penerima.nik = rtlh_rumah.nik_pemilik')
                                ->where('rtlh_rumah.status_bantuan', 'Belum Menerima')
                                ->findAll();

        $data = [
            'title' => 'Input Realisasi Bansos',
            'rtlh' => $rtlh
        ];

        return view('bansos_rtlh/create', $data);
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

        // Handle Upload Foto Before & After
        $uploadPath = FCPATH . 'uploads/rtlh/';
        if (!is_dir($uploadPath)) mkdir($uploadPath, 0777, true);

        foreach(['foto_before', 'foto_after'] as $field) {
            $img = $this->request->getFile($field);
            if ($img && $img->isValid() && !$img->hasMoved()) {
                $prefix = strtoupper(str_replace('foto_', '', $field));
                $newName = $prefix . '_' . $img->getRandomName();
                $img->move($uploadPath, $newName);
                $dataBansos[$field] = $newName;
            }
        }

        // Simpan ke tabel bansos
        $this->bansosModel->insert($dataBansos);
        $bansosId = $this->bansosModel->getInsertID();

        // Simpan Koordinat Realisasi jika ada (POINT WKT)
        if (!empty($koordinat) && preg_match('/POINT\s*\(\s*-?\d+\.?\d*\s+-?\d+\.?\d*\s*\)/i', $koordinat)) {
            $db->table('rtlh_bansos')->where('id', $bansosId)
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
            $rumahData = $db->table('rtlh_rumah')
                            ->select('rtlh_rumah.*, ST_AsText(lokasi_koordinat) as lokasi_koordinat')
                            ->where('id_survei', $targetId)
                            ->get()->getRowArray();
            
            if ($rumahData) {
                $kondisi = $this->kondisiModel->where('id_survei', $targetId)->first();
                $penerima = $this->penerimaModel->where('nik', $rumahData['nik_pemilik'])->first();

                $snapshot = ['rumah' => $rumahData, 'kondisi' => $kondisi, 'penerima' => $penerima];

                // Update Status RTLH
                $db->table('rtlh_rumah')->where('id_survei', $targetId)->update([
                    'status_bantuan' => 'Sudah Menerima',
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
        $bansos = $db->table('rtlh_bansos')
                     ->select('rtlh_bansos.*, ST_AsText(lokasi_realisasi) as wkt_realisasi')
                     ->where('id', $id)
                     ->get()->getRowArray();

        if (!$bansos) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $rumah = null;
        if ($bansos['id_survei']) {
            $rumah = $this->rumahModel->find($bansos['id_survei']);
        }

        return view('bansos_rtlh/detail', [
            'title' => 'Detail Realisasi Bansos',
            'bansos' => $bansos,
            'rumah' => $rumah
        ]);
    }

    public function delete($id)
    {
        $this->bansosModel->delete($id);
        return redirect()->to('/bansos-rtlh')->with('success', 'Data bansos berhasil dihapus.');
    }

    public function edit($id)
    {
        $db = \Config\Database::connect();
        $bansos = $db->table('rtlh_bansos')
                     ->select('rtlh_bansos.*, ST_AsText(lokasi_realisasi) as wkt_realisasi')
                     ->where('id', $id)
                     ->get()->getRowArray();

        if (!$bansos) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        // Data RTLH untuk pilihan dropdown (jika ingin mengubah link)
        $rtlh = $this->rumahModel->select('rtlh_rumah.id_survei, rtlh_rumah.desa, rtlh_penerima.nama_kepala_keluarga, rtlh_penerima.nik')
                                ->join('rtlh_penerima', 'rtlh_penerima.nik = rtlh_rumah.nik_pemilik')
                                ->findAll();

        return view('bansos_rtlh/edit', [
            'title' => 'Edit Realisasi Bansos',
            'bansos' => $bansos,
            'rtlh' => $rtlh
        ]);
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
        foreach(['foto_before', 'foto_after'] as $field) {
            $img = $this->request->getFile($field);
            if ($img && $img->isValid() && !$img->hasMoved()) {
                // Hapus foto lama
                if (!empty($oldData[$field]) && file_exists($uploadPath . $oldData[$field])) {
                    unlink($uploadPath . $oldData[$field]);
                }
                
                $prefix = strtoupper(str_replace('foto_', '', $field));
                $newName = $prefix . '_' . $img->getRandomName();
                $img->move($uploadPath, $newName);
                $dataBansos[$field] = $newName;
            }
        }

        $this->bansosModel->update($id, $dataBansos);

        // Update Koordinat Realisasi (POINT WKT)
        if (!empty($koordinat) && preg_match('/POINT\s*\(\s*-?\d+\.?\d*\s+-?\d+\.?\d*\s*\)/i', $koordinat)) {
            $db->table('rtlh_bansos')->where('id', $id)
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
