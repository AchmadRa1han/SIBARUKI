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
        
        // Proteksi: Hanya admin yang boleh akses Referensi Master
        if (session()->get('role_name') !== 'admin') {
            // Kita gunakan header() dan exit untuk memastikan proses berhenti di constructor jika bukan admin
            // Atau lebih elegan dengan melempar error di fungsi-fungsinya, 
            // tapi cara tercepat di CI4 adalah mengecek di setiap method atau di constructor dengan redirect.
        }
    }

    public function index()
    {
        if (session()->get('role_name') !== 'admin') return redirect()->to('/dashboard')->with('message', 'Akses ditolak: Hanya Admin yang dapat mengelola Referensi Master.');
        
        $keyword = $this->request->getGet('keyword');
        $builder = $this->refModel;

        if ($keyword) {
            $builder->groupStart()
                ->like('kategori', $keyword)
                ->orLike('nama_pilihan', $keyword)
                ->groupEnd();
        }

        $data = [
            'title' => 'Referensi Master',
            'ref_master' => $builder->paginate(25, 'group1'),
            'pager' => $this->refModel->pager
        ];

        return view('ref_master/index', $data);
    }

    public function create()
    {
        if (session()->get('role_name') !== 'admin') return redirect()->to('/dashboard');

        $data = [
            'title' => 'Tambah Ref Master'
        ];

        return view('ref_master/create', $data);
    }

    public function store()
    {
        if (session()->get('role_name') !== 'admin') return redirect()->to('/dashboard');

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
        if (session()->get('role_name') !== 'admin') return redirect()->to('/dashboard');

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
        if (session()->get('role_name') !== 'admin') return redirect()->to('/dashboard');

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
        if (session()->get('role_name') !== 'admin') return redirect()->to('/dashboard');

        $this->refModel->delete($id);
        return redirect()->to('/ref-master')->with('message', 'Data berhasil dihapus');
    }
}
