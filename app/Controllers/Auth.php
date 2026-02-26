<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RoleModel;
use App\Models\UserDesaModel;

class Auth extends BaseController
{
    public function index()
    {
        // Jika sudah login, redirect ke dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to(base_url('dashboard'));
        }
        return view('auth/login');
    }

    public function login()
    {
        $session = session();
        $model = new UserModel();
        $userDesaModel = new UserDesaModel();
        $roleModel = new RoleModel();

        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $user = $model->where('username', $username)->first();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                
                // Ambil info role
                $role = $roleModel->find($user['role_id']);
                
                // Ambil daftar desa jika petugas
                $desa_ids = [];
                if ($role['role_name'] === 'petugas') {
                    $userDesa = $userDesaModel->where('user_id', $user['id'])->findAll();
                    foreach ($userDesa as $ud) {
                        $desa_ids[] = $ud['desa_id'];
                    }
                }

                $ses_data = [
                    'user_id'    => $user['id'],
                    'username'   => $user['username'],
                    'instansi'   => $user['instansi'],
                    'role_id'    => $user['role_id'],
                    'role_name'  => $role['role_name'],
                    'desa_ids'   => $desa_ids, // Array of desa codes
                    'isLoggedIn' => TRUE
                ];
                $session->set($ses_data);
                return redirect()->to(base_url('dashboard'));
            } else {
                $session->setFlashdata('msg', 'Password salah.');
                return redirect()->to(base_url('login'));
            }
        } else {
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
