<?php

namespace App\Controllers;

use App\Models\RumahRtlhModel;
use App\Models\RtlhPenerimaModel;

class ProfilRumah extends BaseController
{
    protected $rumahModel;
    protected $penerimaModel;

    public function __construct()
    {
        $this->rumahModel = new RumahRtlhModel();
        $this->penerimaModel = new RtlhPenerimaModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Profil Rumah',
            'rumah' => $this->rumahModel->select('rtlh_rumah.*, p.nama_kepala_keluarga as pemilik')
                ->join('rtlh_penerima p', 'p.nik = rtlh_rumah.nik_pemilik', 'left')
                ->paginate(25, 'group1'),
            'pager' => $this->rumahModel->pager
        ];

        return view('profil_rumah/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Profil Rumah',
            'penerima' => $this->penerimaModel->findAll()
        ];

        return view('profil_rumah/create', $data);
    }

    public function store()
    {
        $this->rumahModel->insert($this->request->getPost());
        return redirect()->to('/profil-rumah')->with('message', 'Profil rumah berhasil disimpan');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Profil Rumah',
            'rumah' => $this->rumahModel->find($id),
            'penerima' => $this->penerimaModel->findAll()
        ];

        return view('profil_rumah/edit', $data);
    }

    public function update($id)
    {
        $this->rumahModel->update($id, $this->request->getPost());
        return redirect()->to('/profil-rumah')->with('message', 'Profil rumah berhasil diperbarui');
    }

    public function delete($id)
    {
        $this->rumahModel->delete($id);
        return redirect()->to('/profil-rumah')->with('message', 'Data berhasil dihapus');
    }
}
