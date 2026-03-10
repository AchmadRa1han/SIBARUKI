<?php

namespace App\Controllers;

use App\Models\BansosRtlhModel;
use App\Models\RumahRtlhModel;
use App\Models\RtlhPenerimaModel;

class BansosRtlh extends BaseController
{
    protected $bansosModel;
    protected $rumahModel;
    protected $penerimaModel;

    public function __construct()
    {
        $this->bansosModel = new BansosRtlhModel();
        $this->rumahModel = new RumahRtlhModel();
        $this->penerimaModel = new RtlhPenerimaModel();
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

        // 1. Simpan ke tabel bansos
        $this->bansosModel->insert([
            'id_survei' => $id_survei ?: null,
            'nik' => $nik,
            'nama_penerima' => $nama,
            'desa' => $desa,
            'tahun_anggaran' => $tahun,
            'sumber_dana' => $sumber,
            'keterangan' => $this->request->getPost('keterangan')
        ]);

        // 2. Jika terhubung ke data survei RTLH, update statusnya otomatis
        if ($id_survei) {
            $this->rumahModel->update($id_survei, [
                'status_bantuan' => 'Sudah Menerima',
                'tahun_bansos' => $tahun,
                'bantuan_perumahan' => $sumber
            ]);
        } else {
            // Cek apakah NIK ini ada di database rumah_rtlh (mungkin diinput manual tapi NIK cocok)
            $rumah = $this->rumahModel->where('nik_pemilik', $nik)->first();
            if ($rumah) {
                $this->rumahModel->update($rumah['id_survei'], [
                    'status_bantuan' => 'Sudah Menerima',
                    'tahun_bansos' => $tahun,
                    'bantuan_perumahan' => $sumber
                ]);
            }
        }

        $db->transComplete();

        if ($db->transStatus() === FALSE) {
            return redirect()->back()->with('error', 'Gagal menyimpan data bansos.');
        }

        $this->logActivity('Input Bansos', 'Bansos', "Menginput realisasi bansos untuk $nama ($nik)");

        return redirect()->to('/bansos-rtlh')->with('success', 'Data realisasi bansos berhasil disimpan.');
    }

    public function delete($id)
    {
        $this->bansosModel->delete($id);
        return redirect()->to('/bansos-rtlh')->with('success', 'Data bansos berhasil dihapus.');
    }
}
