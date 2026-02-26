<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RoleModel;
use App\Models\UserDesaModel;

class Users extends BaseController
{
    protected $userModel;
    protected $roleModel;
    protected $userDesaModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();
        $this->userDesaModel = new UserDesaModel();
    }

    public function index()
    {
        if (session()->get('role_name') !== 'admin') return redirect()->to('/dashboard');

        $data = [
            'title' => 'Manajemen Pengguna',
            'users' => $this->userModel->select('users.*, roles.role_name')
                        ->join('roles', 'roles.id = users.role_id')
                        ->findAll()
        ];

        return view('users/index', $data);
    }

    public function create()
    {
        if (session()->get('role_name') !== 'admin') return redirect()->to('/dashboard');

        $db = \Config\Database::connect();
        $data = [
            'title' => 'Tambah Pengguna Baru',
            'roles' => $this->roleModel->findAll(),
            'all_desa' => $db->table('kode_desa')->orderBy('desa_nama', 'ASC')->get()->getResultArray()
        ];

        return view('users/create', $data);
    }

    public function store()
    {
        if (session()->get('role_name') !== 'admin') return redirect()->to('/dashboard');

        $rules = [
            'username' => 'required|is_unique[users.username]',
            'password' => 'required|min_length[6]',
            'role_id'  => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userId = $this->userModel->insert([
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'instansi' => $this->request->getPost('instansi'),
            'role_id'  => $this->request->getPost('role_id')
        ]);

        // Simpan data desa jika ada (sekarang dikirim sebagai array dari dropdown)
        $desaIds = $this->request->getPost('desa_ids');
        if ($desaIds && is_array($desaIds)) {
            foreach ($desaIds as $desaId) {
                $this->userDesaModel->insert([
                    'user_id' => $userId,
                    'desa_id' => trim($desaId)
                ]);
            }
        }

        $this->logActivity('Tambah', 'Users', 'Menambah user baru: ' . $this->request->getPost('username'));

        return redirect()->to('/users')->with('message', 'User berhasil ditambahkan');
    }

    public function edit($id)
    {
        if (session()->get('role_name') !== 'admin') return redirect()->to('/dashboard');

        $user = $this->userModel->find($id);
        if (!$user) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $db = \Config\Database::connect();
        
        // Ambil desa yang ditugaskan
        $userDesa = $this->userDesaModel->where('user_id', $id)->findAll();
        $assignedDesaIds = array_column($userDesa, 'desa_id');

        $data = [
            'title'             => 'Edit Pengguna',
            'user'              => $user,
            'roles'             => $this->roleModel->findAll(),
            'assigned_desa_ids' => $assignedDesaIds,
            'all_desa'          => $db->table('kode_desa')->orderBy('desa_nama', 'ASC')->get()->getResultArray()
        ];

        return view('users/edit', $data);
    }

    public function update($id)
    {
        if (session()->get('role_name') !== 'admin') return redirect()->to('/dashboard');

        $user = $this->userModel->find($id);
        $passwordInput = $this->request->getPost('password');

        $updateData = [
            'username' => $this->request->getPost('username'),
            'instansi' => $this->request->getPost('instansi'),
            'role_id'  => $this->request->getPost('role_id')
        ];

        if (!empty($passwordInput)) {
            $updateData['password'] = password_hash($passwordInput, PASSWORD_DEFAULT);
        }

        $this->userModel->update($id, $updateData);

        // Update Desa Penugasan (sekarang sebagai array)
        $this->userDesaModel->where('user_id', $id)->delete();
        $desaIds = $this->request->getPost('desa_ids');
        if ($desaIds && is_array($desaIds)) {
            foreach ($desaIds as $desaId) {
                if (trim($desaId)) {
                    $this->userDesaModel->insert([
                        'user_id' => $id,
                        'desa_id' => trim($desaId)
                    ]);
                }
            }
        }

        $this->logActivity('Ubah', 'Users', 'Memperbarui profil user: ' . $user['username']);

        return redirect()->to('/users')->with('message', 'User berhasil diperbarui');
    }

    public function delete($id)
    {
        if (session()->get('role_name') !== 'admin') return redirect()->to('/dashboard');
        
        $user = $this->userModel->find($id);
        if ($user['username'] === 'admin') {
            return redirect()->back()->with('message', 'Admin utama tidak bisa dihapus.');
        }

        $this->userModel->delete($id);
        $this->logActivity('Hapus', 'Users', 'Menghapus user: ' . $user['username']);

        return redirect()->to('/users')->with('message', 'User berhasil dihapus');
    }
}
