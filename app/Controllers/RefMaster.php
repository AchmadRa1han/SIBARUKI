<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RefMasterModel;

class RefMaster extends BaseController
{
    protected $refModel;

    public function __construct()
    {
        $this->refModel = new RefMasterModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Referensi Master',
            'ref_master' => $this->refModel->paginate(25, 'group1'),
            'pager' => $this->refModel->pager
        ];

        return view('ref_master/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Ref Master'
        ];

        return view('ref_master/create', $data);
    }

    public function store()
    {
        if (!$this->validate($this->refModel->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->refModel->save([
            'kategori' => $this->request->getPost('kategori'),
            'nama_pilihan' => $this->request->getPost('nama_pilihan'),
        ]);

        return redirect()->to('/ref-master')->with('message', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Ref Master',
            'ref' => $this->refModel->find($id)
        ];

        if (!$data['ref']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('ref_master/edit', $data);
    }

    public function update($id)
    {
        if (!$this->validate($this->refModel->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->refModel->update($id, [
            'kategori' => $this->request->getPost('kategori'),
            'nama_pilihan' => $this->request->getPost('nama_pilihan'),
        ]);

        return redirect()->to('/ref-master')->with('message', 'Data berhasil diubah');
    }

    public function delete($id)
    {
        $this->refModel->delete($id);
        return redirect()->to('/ref-master')->with('message', 'Data berhasil dihapus');
    }
}
