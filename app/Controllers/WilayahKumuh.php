<?php

namespace App\Controllers;

use App\Models\WilayahKumuhModel;

class WilayahKumuh extends BaseController
{
    protected $kumuhModel;

    public function __construct()
    {
        $this->kumuhModel = new WilayahKumuhModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Wilayah Kumuh',
            'kumuh' => $this->kumuhModel->paginate(25, 'group1'),
            'pager' => $this->kumuhModel->pager
        ];

        return view('wilayah_kumuh/index', $data);
    }

    public function detail($id)
    {
        $data = [
            'title' => 'Detail Wilayah Kumuh',
            'kumuh' => $this->kumuhModel->find($id)
        ];

        if (!$data['kumuh']) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        return view('wilayah_kumuh/detail', $data);
    }

    public function create()
    {
        return view('wilayah_kumuh/create', ['title' => 'Tambah Wilayah Kumuh']);
    }

    public function store()
    {
        $this->kumuhModel->insert($this->request->getPost());
        return redirect()->to('/wilayah-kumuh')->with('message', 'Data wilayah kumuh berhasil disimpan');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Wilayah Kumuh',
            'kumuh' => $this->kumuhModel->find($id)
        ];
        return view('wilayah_kumuh/edit', $data);
    }

    public function update($id)
    {
        $this->kumuhModel->update($id, $this->request->getPost());
        return redirect()->to('/wilayah-kumuh')->with('message', 'Data wilayah kumuh berhasil diperbarui');
    }

    public function delete($id)
    {
        $this->kumuhModel->delete($id);
        return redirect()->to('/wilayah-kumuh')->with('message', 'Data berhasil dihapus');
    }
}
