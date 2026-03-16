<?php

namespace App\Controllers\Api\V1;

use App\Models\UserModel;
use App\Models\RoleModel;
use App\Models\UserDesaModel;
use Firebase\JWT\JWT;

class AuthApi extends BaseApiController
{
    public function login()
    {
        $model = new UserModel();
        $userDesaModel = new UserDesaModel();
        $roleModel = new RoleModel();
        $db = \Config\Database::connect();

        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        if (!$username || !$password) {
            return $this->respondError('Username dan password wajib diisi', 400);
        }

        $user = $model->where('username', $username)->first();

        if (!$user || !password_verify($password, $user['password'])) {
            return $this->respondError('Username atau password salah', 401);
        }

        // Ambil info role
        $role = $roleModel->find($user['role_id']);
        
        // Ambil Permissions
        $permissions = $db->table('role_permissions rp')
            ->select('p.permission_name')
            ->join('permissions p', 'p.id = rp.permission_id')
            ->where('rp.role_id', $user['role_id'])
            ->get()
            ->getResultArray();
        
        $perm_list = array_column($permissions, 'permission_name');

        // Ambil daftar desa (scope)
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

        // Create JWT Payload
        $key = getenv('JWT_SECRET') ?: 'sibaruki_secret_key_2026';
        $iat = time();
        $exp = $iat + (3600 * 24 * 7); // Token berlaku 7 hari

        $payload = [
            'iss' => base_url(),
            'aud' => 'sibaruki_mobile',
            'iat' => $iat,
            'exp' => $exp,
            'uid' => $user['id'],
            'username' => $user['username'],
            'role_id' => $user['role_id'],
            'role_name' => $role['role_name'],
            'role_scope' => $role['scope'],
            'permissions' => $perm_list,
            'desa_ids_rtlh' => $desa_rtlh,
            'desa_ids_kumuh' => $desa_kumuh
        ];

        $token = JWT::encode($payload, $key, 'HS256');

        $data = [
            'token' => $token,
            'user' => [
                'id' => $user['id'],
                'username' => $user['username'],
                'instansi' => $user['instansi'],
                'role_name' => $role['role_name'],
                'role_scope' => $role['scope']
            ],
            'expires_at' => date('Y-m-d H:i:s', $exp)
        ];

        return $this->respondSuccess($data, 'Login berhasil');
    }

    public function profile()
    {
        $userData = $this->getUserData();
        return $this->respondSuccess($userData, 'Data profil berhasil diambil');
    }
}
