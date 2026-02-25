<?php

namespace App\Controllers;

use App\Models\RtlhPenerimaModel;
use App\Models\RefMasterModel;

class PenerimaRtlh extends BaseController
{
    protected $penerimaModel;
    protected $refModel;

    public function __construct()
    {
        $this->penerimaModel = new RtlhPenerimaModel();
        $this->refModel = new RefMasterModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Penerima RTLH',
            'penerima' => $this->penerimaModel->select('rtlh_penerima.*, pnd.nama_pilihan as pendidikan, pkj.nama_pilihan as pekerjaan')
                ->join('ref_master pnd', 'pnd.id = rtlh_penerima.pendidikan_id', 'left')
                ->join('ref_master pkj', 'pkj.id = rtlh_penerima.pekerjaan_id', 'left')
                ->paginate(25, 'group1'),
            'pager' => $this->penerimaModel->pager
        ];

        return view('penerima_rtlh/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Penerima RTLH',
            'pendidikan' => $this->refModel->where('kategori', 'pendidikan')->findAll(),
            'pekerjaan' => $this->refModel->where('kategori', 'pekerjaan')->findAll()
        ];

        return view('penerima_rtlh/create', $data);
    }

    public function store()
    {
        if (!$this->validate($this->penerimaModel->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->penerimaModel->insert($this->request->getPost());
        return redirect()->to('/penerima-rtlh')->with('message', 'Data penerima berhasil disimpan');
    }

    public function edit($nik)
    {
        $data = [
            'title' => 'Edit Penerima RTLH',
            'penerima' => $this->penerimaModel->find($nik),
            'pendidikan' => $this->refModel->where('kategori', 'pendidikan')->findAll(),
            'pekerjaan' => $this->refModel->where('kategori', 'pekerjaan')->findAll()
        ];

        return view('penerima_rtlh/edit', $data);
    }

    public function update($nik)
    {
        $rules = $this->penerimaModel->getValidationRules();
        $rules['nik'] = "required|exact_length[16]"; // Hilangkan is_unique saat update

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->penerimaModel->update($nik, $this->request->getPost());
        return redirect()->to('/penerima-rtlh')->with('message', 'Data penerima berhasil diperbarui');
    }

    public function delete($nik)
    {
        $this->penerimaModel->delete($nik);
        return redirect()->to('/penerima-rtlh')->with('message', 'Data berhasil dihapus');
    }
}
