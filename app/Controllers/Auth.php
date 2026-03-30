<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RoleModel;
use App\Models\UserDesaModel;

class Auth extends BaseController
{
    public function index()
    {
        // Jika sudah login, redirect ke beranda
        if (session()->get('isLoggedIn')) {
            return redirect()->to(base_url('/'));
        }
        return view('auth/login');
    }

    public function login()
    {
        $session = session();
        $db = \Config\Database::connect();
        $model = new UserModel();
        $userDesaModel = new UserDesaModel();
        $roleModel = new RoleModel();

        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $user = $model->where('username', $username)->first();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                
                // 1. Ambil info role dan scope
                $role = $roleModel->find($user['role_id']);
                
                // 2. Ambil Permissions (Daftar Izin)
                $permissions = $db->table('role_permissions rp')
                    ->select('p.permission_name')
                    ->join('permissions p', 'p.id = rp.permission_id')
                    ->where('rp.role_id', $user['role_id'])
                    ->get()
                    ->getResultArray();
                
                $perm_list = array_column($permissions, 'permission_name');

                // 3. Ambil daftar desa berdasarkan kategori (RTLH & Kumuh)
                $desa_rtlh = [];
                $desa_kumuh = [];
                
                $userDesa = $userDesaModel->where('user_id', $user['id'])->findAll();
                foreach ($userDesa as $ud) {
                    if ($ud['category'] === 'rtlh') {
                        $desa_rtlh[] = $ud['desa_id'];
                    } elseif ($ud['category'] === 'kumuh') {
                        $desa_kumuh[] = $ud['desa_id'];
                    }
                }

                $ses_data = [
                    'user_id'        => $user['id'],
                    'username'       => $user['username'],
                    'instansi'       => $user['instansi'],
                    'role_id'        => $user['role_id'],
                    'role_name'      => $role['role_name'],
                    'role_scope'     => $role['scope'], // global atau local
                    'permissions'    => $perm_list,    // Array string izin
                    'desa_ids_rtlh'  => $desa_rtlh,    // Filter wilayah RTLH
                    'desa_ids_kumuh' => $desa_kumuh,   // Filter wilayah Kumuh
                    'isLoggedIn'     => TRUE
                ];
                
                $session->set($ses_data);

                // Catat Log Login (Menggunakan fungsi global)
                $this->logActivity('Login', 'Users', 'User berhasil masuk ke dalam sistem');

                return redirect()->to(base_url('/'));
            } else {
                // Catat Log Login Gagal (Password Salah)
                $this->logActivity('Login Gagal', 'Security', 'Percobaan masuk dengan password yang salah');
                $session->setFlashdata('msg', 'Password salah.');
                return redirect()->to(base_url('login'));
            }
        } else {
            // Catat Log Login Gagal (Username Tidak Ditemukan)
            // Karena user tidak ada, kita catat manual dengan System/Unknown
            $this->logActivity('Login Gagal', 'Security', 'Percobaan masuk dengan username tidak terdaftar: ' . ($username ?? 'Unknown'));
            $session->setFlashdata('msg', 'Username tidak ditemukan.');
            return redirect()->to(base_url('login'));
        }
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to(base_url('login'));
    }
}
