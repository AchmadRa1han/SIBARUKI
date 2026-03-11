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

        $userData = [
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'instansi' => $this->request->getPost('instansi'),
            'role_id'  => $this->request->getPost('role_id')
        ];

        $userId = $this->userModel->insert($userData);
        $savedData = $this->userModel->find($userId);
        $detailLog = $this->formatLogData($savedData);

        // ... (simpan assignments desa) ...
        
        $this->logActivity('Tambah', 'Users', "Menambah user baru: {$savedData['username']}", $detailLog);

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

        $oldData = $this->userModel->find($id);
        $this->userModel->update($id, $updateData);
        $newData = $this->userModel->find($id);

        $diff = $this->generateDiff($oldData, $newData, ['password', 'updated_at']);
        $this->logActivity('Ubah', 'Users', 'Memperbarui profil user: ' . $user['username'], $diff);

        return redirect()->to('/users')->with('message', 'User berhasil diperbarui');
    }

    public function delete($id)
    {
        if (!has_permission('delete_users')) return redirect()->to('/users')->with('message', 'Akses ditolak.');
        
        $user = $this->userModel->find($id);
        if (!$user) return redirect()->back()->with('message', 'User tidak ditemukan.');
        if ($user['username'] === 'admin') {
            return redirect()->back()->with('message', 'Admin utama tidak bisa dihapus.');
        }

        $db = \Config\Database::connect();
        
        // Ambil data penugasan desa sebelum dihapus
        $assignments = $db->table('user_desa')->where('user_id', $id)->get()->getResultArray();

        $allData = [
            'user' => $user,
            'assignments' => $assignments
        ];

        $db->transStart();
        
        // 1. Simpan ke Trash
        $db->table('trash_data')->insert([
            'entity_type' => 'USER',
            'entity_id'   => $id,
            'data_json'   => json_encode($allData),
            'deleted_by'  => session()->get('username'),
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        // 2. Hapus data asli
        $db->table('user_desa')->where('user_id', $id)->delete();
        $this->userModel->delete($id);

        $db->transComplete();

        $detailLog = $this->formatLogData($user);
        $this->logActivity('Hapus', 'Users', 'Memindahkan user ke Recycle Bin: ' . $user['username'], $detailLog);

        return redirect()->to('/users')->with('message', 'User telah dipindahkan ke Recycle Bin.');
    }

    public function bulkDelete()
    {
        if (!has_permission('delete_users')) return $this->response->setJSON(['status' => 'error', 'message' => 'Izin ditolak.']);
        $ids = $this->request->getPost('ids');
        if (empty($ids)) return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak ada data yang dipilih.']);

        $db = \Config\Database::connect();
        $db->transStart();
        try {
            $this->userModel->whereIn('id', $ids)->delete();
            $db->transComplete();
            if ($db->transStatus() === FALSE) throw new \Exception('Gagal menghapus data massal.');
            $this->logActivity('Hapus Massal', 'Users', "Menghapus " . count($ids) . " user sekaligus");
            return $this->response->setJSON(['status' => 'success', 'message' => count($ids) . ' user berhasil dihapus.']);
        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
