<?php

namespace App\Controllers;

use App\Models\PisewModel;

class Pisew extends BaseController
{
    protected $pisewModel;

    public function __construct()
    {
        $this->pisewModel = new PisewModel();
    }

    public function index()
    {
        $search = $this->request->getGet('search');
        $kecamatan = $this->request->getGet('kecamatan');
        $sortBy = $this->request->getGet('sort_by') ?? 'tahun';
        $sortOrder = $this->request->getGet('sort_order') ?? 'desc';
        $perPage = $this->request->getGet('per_page') ?? 10;

        $query = $this->pisewModel;

        if ($search) {
            $query = $query->groupStart()
                ->like('jenis_pekerjaan', $search)
                ->orLike('lokasi_desa', $search)
                ->groupEnd();
        }

        if ($kecamatan) {
            $query = $query->where('kecamatan', $kecamatan);
        }

        $query = $query->orderBy($sortBy, $sortOrder);

        $data = [
            'pisew' => $query->paginate($perPage, 'group1'),
            'pisew_all' => (clone $query)->findAll(),
            'pager' => $this->pisewModel->pager,
            'perPage' => $perPage,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'search' => $search,
            'selected_kecamatan' => $kecamatan,
            'kecamatans' => $this->pisewModel->select('kecamatan')->distinct()->findAll(),
            'total_anggaran' => $this->pisewModel->selectSum('anggaran')->get()->getRow()->anggaran,
            'total_kegiatan' => $this->pisewModel->countAllResults(false)
        ];

        return view('pisew/index', $data);
    }

    public function create()
    {
        return view('pisew/create');
    }

    public function store()
    {
        $this->pisewModel->save($this->request->getPost());
        return redirect()->to('/pisew')->with('success', 'Data PISEW berhasil ditambahkan.');
    }

    public function detail($id)
    {
        $data['item'] = $this->pisewModel->find($id);
        if (!$data['item']) return redirect()->to('/pisew');
        return view('pisew/detail', $data);
    }

    public function edit($id)
    {
        $data['item'] = $this->pisewModel->find($id);
        if (!$data['item']) return redirect()->to('/pisew');
        return view('pisew/edit', $data);
    }

    public function update($id)
    {
        $this->pisewModel->update($id, $this->request->getPost());
        return redirect()->to('/pisew')->with('success', 'Data PISEW berhasil diperbarui.');
    }

    public function delete($id)
    {
        $this->pisewModel->delete($id);
        return redirect()->to('/pisew')->with('success', 'Data PISEW berhasil dihapus.');
    }
}
