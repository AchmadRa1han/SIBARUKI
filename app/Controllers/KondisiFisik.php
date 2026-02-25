<?php

namespace App\Controllers;

use App\Models\KondisiRumahModel;
use App\Models\RumahRtlhModel;

class KondisiFisik extends BaseController
{
    protected $kondisiModel;
    protected $rumahModel;

    public function __construct()
    {
        $this->kondisiModel = new KondisiRumahModel();
        $this->rumahModel = new RumahRtlhModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Kondisi Fisik Rumah',
            'kondisi' => $this->kondisiModel->select('rtlh_kondisi_rumah.*, p.nama_kepala_keluarga as pemilik, r.desa')
                ->select('ref_pondasi.nama_pilihan as pondasi, ref_kolom.nama_pilihan as kolom')
                ->select('ref_balok.nama_pilihan as balok, ref_sloof.nama_pilihan as sloof')
                ->select('ref_atap_st.nama_pilihan as atap_st, ref_atap_mat.nama_pilihan as atap_mat')
                ->select('ref_dinding_st.nama_pilihan as dinding_st, ref_dinding_mat.nama_pilihan as dinding_mat')
                ->select('ref_lantai_st.nama_pilihan as lantai_st, ref_lantai_mat.nama_pilihan as lantai_mat')
                ->join('rumah_rtlh r', 'r.id_survei = rtlh_kondisi_rumah.id_survei')
                ->join('rtlh_penerima p', 'p.nik = r.nik_pemilik')
                ->join('ref_master ref_pondasi', 'ref_pondasi.id = rtlh_kondisi_rumah.st_pondasi', 'left')
                ->join('ref_master ref_kolom', 'ref_kolom.id = rtlh_kondisi_rumah.st_kolom', 'left')
                ->join('ref_master ref_balok', 'ref_balok.id = rtlh_kondisi_rumah.st_balok', 'left')
                ->join('ref_master ref_sloof', 'ref_sloof.id = rtlh_kondisi_rumah.st_sloof', 'left')
                ->join('ref_master ref_atap_st', 'ref_atap_st.id = rtlh_kondisi_rumah.st_atap', 'left')
                ->join('ref_master ref_atap_mat', 'ref_atap_mat.id = rtlh_kondisi_rumah.mat_atap', 'left')
                ->join('ref_master ref_dinding_st', 'ref_dinding_st.id = rtlh_kondisi_rumah.st_dinding', 'left')
                ->join('ref_master ref_dinding_mat', 'ref_dinding_mat.id = rtlh_kondisi_rumah.mat_dinding', 'left')
                ->join('ref_master ref_lantai_st', 'ref_lantai_st.id = rtlh_kondisi_rumah.st_lantai', 'left')
                ->join('ref_master ref_lantai_mat', 'ref_lantai_mat.id = rtlh_kondisi_rumah.mat_lantai', 'left')
                ->paginate(25, 'group1'),
            'pager' => $this->kondisiModel->pager
        ];

        return view('kondisi_fisik/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Kondisi Fisik',
            'rumah' => $this->rumahModel->findAll()
        ];

        return view('kondisi_fisik/create', $data);
    }

    public function store()
    {
        $this->kondisiModel->insert($this->request->getPost());
        return redirect()->to('/kondisi-fisik')->with('message', 'Kondisi fisik berhasil disimpan');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Kondisi Fisik',
            'kondisi' => $this->kondisiModel->find($id)
        ];

        return view('kondisi_fisik/edit', $data);
    }

    public function update($id)
    {
        $this->kondisiModel->update($id, $this->request->getPost());
        return redirect()->to('/kondisi-fisik')->with('message', 'Kondisi fisik berhasil diperbarui');
    }

    public function delete($id)
    {
        $this->kondisiModel->delete($id);
        return redirect()->to('/kondisi-fisik')->with('message', 'Data berhasil dihapus');
    }
}
