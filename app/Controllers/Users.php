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
        if (!has_permission('manage_users') && !has_permission('view_users')) {
            return redirect()->to('/dashboard');
        }

        $builder = $this->userModel->select('users.*, roles.role_name, roles.scope as role_scope')
                        ->join('roles', 'roles.id = users.role_id');

        // Filter Wilayah (Untuk Kepala Desa / Local Scope)
        if (session()->get('role_scope') === 'local') {
            $db = \Config\Database::connect();
            // 1. Dapatkan daftar desa milik user yang sedang login (Kades)
            $my_desa_ids = array_merge(
                session()->get('desa_ids_rtlh') ?? [],
                session()->get('desa_ids_kumuh') ?? []
            );

            if (!empty($my_desa_ids)) {
                // 2. Cari semua USER_ID yang ditugaskan di desa yang sama
                $related_users = $db->table('user_desa')
                                    ->select('user_id')
                                    ->whereIn('desa_id', $my_desa_ids)
                                    ->get()
                                    ->getResultArray();
                
                $user_ids = array_column($related_users, 'user_id');
                
                if (!empty($user_ids)) {
                    $builder->whereIn('users.id', $user_ids);
                } else {
                    $builder->where('users.id', 0); // Kosongkan jika tidak ada
                }
            } else {
                $builder->where('users.id', 0);
            }
        }

        $data = [
            'title' => 'Daftar Pengguna / Petugas',
            'users' => $builder->findAll()
        ];

        return view('users/index', $data);
    }

    public function create()
    {
        if (!has_permission('create_users')) return redirect()->to('/users')->with('message', 'Akses ditolak.');

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
        if (!has_permission('create_users')) return redirect()->to('/users')->with('message', 'Akses ditolak.');

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

        // Simpan data desa RTLH
        $desaIdsRtlh = $this->request->getPost('desa_ids_rtlh');
        if ($desaIdsRtlh && is_array($desaIdsRtlh)) {
            foreach ($desaIdsRtlh as $desaId) {
                $this->userDesaModel->insert([
                    'user_id'  => $userId,
                    'desa_id'  => trim($desaId),
                    'category' => 'rtlh'
                ]);
            }
        }

        // Simpan data desa Kumuh
        $desaIdsKumuh = $this->request->getPost('desa_ids_kumuh');
        if ($desaIdsKumuh && is_array($desaIdsKumuh)) {
            foreach ($desaIdsKumuh as $desaId) {
                $this->userDesaModel->insert([
                    'user_id'  => $userId,
                    'desa_id'  => trim($desaId),
                    'category' => 'kumuh'
                ]);
            }
        }

        $this->logActivity('Tambah', 'Users', 'Menambah user baru: ' . $this->request->getPost('username'));

        return redirect()->to('/users')->with('message', 'User berhasil ditambahkan');
    }

    public function edit($id)
    {
        if (!has_permission('edit_users')) return redirect()->to('/users')->with('message', 'Akses ditolak.');

        $user = $this->userModel->find($id);
        if (!$user) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $db = \Config\Database::connect();
        
        // Ambil desa penugasan RTLH
        $rtlhDesa = $this->userDesaModel->where(['user_id' => $id, 'category' => 'rtlh'])->findAll();
        $assignedRtlh = array_column($rtlhDesa, 'desa_id');

        // Ambil desa penugasan Kumuh
        $kumuhDesa = $this->userDesaModel->where(['user_id' => $id, 'category' => 'kumuh'])->findAll();
        $assignedKumuh = array_column($kumuhDesa, 'desa_id');

        $data = [
            'title'             => 'Edit Pengguna',
            'user'              => $user,
            'roles'             => $this->roleModel->findAll(),
            'assigned_rtlh'     => $assignedRtlh,
            'assigned_kumuh'    => $assignedKumuh,
            'all_desa'          => $db->table('kode_desa')->orderBy('desa_nama', 'ASC')->get()->getResultArray()
        ];

        return view('users/edit', $data);
    }

    public function update($id)
    {
        if (!has_permission('edit_users')) return redirect()->to('/users')->with('message', 'Akses ditolak.');

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

        // Update Desa Penugasan (Hapus semua, lalu simpan ulang per kategori)
        $this->userDesaModel->where('user_id', $id)->delete();
        
        // Simpan RTLH
        $desaIdsRtlh = $this->request->getPost('desa_ids_rtlh');
        if ($desaIdsRtlh && is_array($desaIdsRtlh)) {
            foreach ($desaIdsRtlh as $desaId) {
                $this->userDesaModel->insert([
                    'user_id'  => $id,
                    'desa_id'  => trim($desaId),
                    'category' => 'rtlh'
                ]);
            }
        }

        // Simpan Kumuh
        $desaIdsKumuh = $this->request->getPost('desa_ids_kumuh');
        if ($desaIdsKumuh && is_array($desaIdsKumuh)) {
            foreach ($desaIdsKumuh as $desaId) {
                $this->userDesaModel->insert([
                    'user_id'  => $id,
                    'desa_id'  => trim($desaId),
                    'category' => 'kumuh'
                ]);
            }
        }

        $this->logActivity('Ubah', 'Users', 'Memperbarui profil user: ' . $user['username']);

        return redirect()->to('/users')->with('message', 'User berhasil diperbarui');
    }

    public function delete($id)
    {
        if (!has_permission('delete_users')) return redirect()->to('/users')->with('message', 'Akses ditolak.');
        
        $user = $this->userModel->find($id);
        if ($user['username'] === 'admin') {
            return redirect()->back()->with('message', 'Admin utama tidak bisa dihapus.');
        }

        $this->userModel->delete($id);
        $this->logActivity('Hapus', 'Users', 'Menghapus user: ' . $user['username']);

        return redirect()->to('/users')->with('message', 'User berhasil dihapus');
    }
}
