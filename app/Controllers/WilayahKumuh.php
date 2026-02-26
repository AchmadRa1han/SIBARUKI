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
        $builder = $this->kumuhModel;
        $keyword = $this->request->getGet('keyword');

        if ($keyword) {
            $builder->groupStart()
                ->like('Kecamatan', $keyword)
                ->orLike('Kelurahan', $keyword)
                ->orLike('desa_id', $keyword)
                ->orLike('Kawasan', $keyword)
                ->groupEnd();
        }

        if (session()->get('role_name') === 'petugas') {
            $desa_ids = session()->get('desa_ids');
            if (!empty($desa_ids)) {
                $builder->whereIn('desa_id', $desa_ids);
            } else {
                $builder->where('desa_id', '000000');
            }
        }

        $data = [
            'title' => 'Wilayah Kumuh',
            'kumuh' => $builder->paginate(25, 'group1'),
            'pager' => $this->kumuhModel->pager
        ];

        return view('wilayah_kumuh/index', $data);
    }

    public function detail($id)
    {
        $kumuh = $this->kumuhModel->find($id);
        if (!$kumuh) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        // Security Check
        if (session()->get('role_name') === 'petugas') {
            $desa_ids = session()->get('desa_ids');
            if (!in_array($kumuh['desa_id'], $desa_ids)) {
                return redirect()->to('/wilayah-kumuh')->with('message', 'Akses ditolak.');
            }
        }

        $data = [
            'title' => 'Detail Wilayah Kumuh',
            'kumuh' => $kumuh
        ];

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
        $kumuh = $this->kumuhModel->find($id);
        if (!$kumuh) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        // Security Check
        if (session()->get('role_name') === 'petugas') {
            $desa_ids = session()->get('desa_ids');
            if (!in_array($kumuh['desa_id'], $desa_ids)) {
                return redirect()->to('/wilayah-kumuh')->with('message', 'Akses ditolak.');
            }
        }

        $data = [
            'title' => 'Edit Wilayah Kumuh',
            'kumuh' => $kumuh
        ];
        return view('wilayah_kumuh/edit', $data);
    }

    public function update($id)
    {
        $kumuh = $this->kumuhModel->find($id);
        if (!$kumuh) return redirect()->back()->with('message', 'Data tidak ditemukan.');

        // Security Check
        if (session()->get('role_name') === 'petugas') {
            $desa_ids = session()->get('desa_ids');
            if (!in_array($kumuh['desa_id'], $desa_ids)) {
                return redirect()->to('/wilayah-kumuh')->with('message', 'Akses ditolak.');
            }
        }

        $this->kumuhModel->update($id, $this->request->getPost());
        return redirect()->to('/wilayah-kumuh/detail/' . $id)->with('message', 'Data wilayah kumuh berhasil diperbarui');
    }

    public function delete($id)
    {
        $kumuh = $this->kumuhModel->find($id);
        if (!$kumuh) return redirect()->back()->with('message', 'Data tidak ditemukan.');

        // Security Check
        if (session()->get('role_name') === 'petugas') {
            $desa_ids = session()->get('desa_ids');
            if (!in_array($kumuh['desa_id'], $desa_ids)) {
                return redirect()->to('/wilayah-kumuh')->with('message', 'Akses ditolak.');
            }
        }

        $this->kumuhModel->delete($id);
        return redirect()->to('/wilayah-kumuh')->with('message', 'Data berhasil dihapus');
    }
}
