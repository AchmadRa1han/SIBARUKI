<?php

namespace App\Controllers;

use App\Models\ArsinumModel;

class Arsinum extends BaseController
{
    protected $arsinumModel;

    public function __construct()
    {
        $this->arsinumModel = new ArsinumModel();
    }

    public function index()
    {
        $search = $this->request->getGet('search');
        $kecamatan = $this->request->getGet('kecamatan');
        $sortBy = $this->request->getGet('sort_by') ?? 'tahun';
        $sortOrder = $this->request->getGet('sort_order') ?? 'desc';
        $perPage = $this->request->getGet('per_page') ?? 10;

        $query = $this->arsinumModel;

        if ($search) {
            $query = $query->groupStart()
                ->like('jenis_pekerjaan', $search)
                ->orLike('desa', $search)
                ->orLike('pelaksana', $search)
                ->groupEnd();
        }

        if ($kecamatan) {
            $query = $query->where('kecamatan', $kecamatan);
        }

        $query = $query->orderBy($sortBy, $sortOrder);

        $data = [
            'arsinum' => $query->paginate($perPage, 'group1'),
            'arsinum_all' => (clone $query)->findAll(),
            'pager' => $this->arsinumModel->pager,
            'perPage' => $perPage,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'search' => $search,
            'selected_kecamatan' => $kecamatan,
            'kecamatans' => $this->arsinumModel->select('kecamatan')->distinct()->findAll(),
            'total_anggaran' => $this->arsinumModel->selectSum('anggaran')->get()->getRow()->anggaran,
            'total_unit' => $this->arsinumModel->countAllResults(false)
        ];

        return view('arsinum/index', $data);
    }

    public function create()
    {
        return view('arsinum/create');
    }

    public function store()
    {
        $this->arsinumModel->save($this->request->getPost());
        return redirect()->to('/arsinum')->with('success', 'Data ARSINUM berhasil ditambahkan.');
    }

    public function detail($id)
    {
        $data['item'] = $this->arsinumModel->find($id);
        if (!$data['item']) return redirect()->to('/arsinum');
        return view('arsinum/detail', $data);
    }

    public function edit($id)
    {
        $data['item'] = $this->arsinumModel->find($id);
        if (!$data['item']) return redirect()->to('/arsinum');
        return view('arsinum/edit', $data);
    }

    public function update($id)
    {
        $this->arsinumModel->update($id, $this->request->getPost());
        return redirect()->to('/arsinum')->with('success', 'Data ARSINUM berhasil diperbarui.');
    }

    public function delete($id)
    {
        $this->arsinumModel->delete($id);
        return redirect()->to('/arsinum')->with('success', 'Data ARSINUM berhasil dihapus.');
    }
}
