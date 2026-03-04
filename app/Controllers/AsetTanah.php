<?php

namespace App\Controllers;

use App\Models\AsetTanahModel;

class AsetTanah extends BaseController
{
    protected $asetModel;

    public function __construct()
    {
        $this->asetModel = new AsetTanahModel();
    }

    public function index()
    {
        $search = $this->request->getGet('search');
        $kecamatan = $this->request->getGet('kecamatan');
        $sortBy = $this->request->getGet('sort_by') ?? 'id';
        $sortOrder = $this->request->getGet('sort_order') ?? 'desc';

        $query = $this->asetModel;

        if ($search) {
            $query = $query->groupStart()
                ->like('no_sertifikat', $search)
                ->orLike('nama_pemilik', $search)
                ->orLike('lokasi', $search)
                ->groupEnd();
        }

        if ($kecamatan) {
            $query = $query->where('kecamatan', $kecamatan);
        }

        // Apply Sorting
        $query = $query->orderBy($sortBy, $sortOrder);

        $perPage = $this->request->getGet('per_page') ?? 10;
        $aset = $query->paginate($perPage, 'group1');
        
        // Ambil semua data untuk peta (tanpa pagination)
        $aset_all = (clone $query)->findAll();

        $data = [
            'aset' => $aset,
            'aset_all' => $aset_all,
            'pager' => $this->asetModel->pager,
            'perPage' => $perPage,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'total_aset' => $this->asetModel->countAllResults(false),
            'total_luas' => $this->asetModel->selectSum('luas_m2')->get()->getRow()->luas_m2,
            'total_nilai' => $this->asetModel->selectSum('nilai_aset')->get()->getRow()->nilai_aset,
            'kecamatans' => $this->asetModel->select('kecamatan')->distinct()->findAll(),
            'search' => $search,
            'selected_kecamatan' => $kecamatan
        ];

        return view('aset_tanah/index', $data);
    }

    public function create()
    {
        return view('aset_tanah/create');
    }

    public function store()
    {
        $rules = [
            'no_sertifikat' => 'required',
            'nama_pemilik'  => 'required',
            'luas_m2'       => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->asetModel->save($this->request->getPost());
        return redirect()->to('/aset-tanah')->with('success', 'Data aset berhasil ditambahkan.');
    }

    public function detail($id)
    {
        $aset = $this->asetModel->find($id);
        if (!$aset) {
            return redirect()->to('/aset-tanah')->with('error', 'Data tidak ditemukan.');
        }

        return view('aset_tanah/detail', ['aset' => $aset]);
    }

    public function edit($id)
    {
        $aset = $this->asetModel->find($id);
        if (!$aset) {
            return redirect()->to('/aset-tanah')->with('error', 'Data tidak ditemukan.');
        }

        return view('aset_tanah/edit', ['aset' => $aset]);
    }

    public function update($id)
    {
        $rules = [
            'no_sertifikat' => 'required',
            'nama_pemilik'  => 'required',
            'luas_m2'       => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->asetModel->update($id, $this->request->getPost());
        return redirect()->to('/aset-tanah')->with('success', 'Data aset berhasil diperbarui.');
    }

    public function delete($id)
    {
        $this->asetModel->delete($id);
        return redirect()->to('/aset-tanah')->with('success', 'Data aset berhasil dihapus.');
    }
}
