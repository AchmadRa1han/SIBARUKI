<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AuthSeeder extends Seeder
{
    public function run()
    {
        // Kosongkan tabel terlebih dahulu
        $this->db->query('SET FOREIGN_KEY_CHECKS=0;');
        $this->db->table('user_desa')->truncate();
        $this->db->table('users')->truncate();
        $this->db->table('roles')->truncate();
        $this->db->query('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Seed Roles
        $rolesData = [
            ['id' => 1, 'role_name' => 'admin', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 2, 'role_name' => 'petugas', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];
        $this->db->table('roles')->insertBatch($rolesData);

        // 2. Seed Users
        $password = password_hash('password123', PASSWORD_DEFAULT);
        
        $usersData = [
            [
                'id'         => 1,
                'username'   => 'admin',
                'password'   => $password,
                'instansi'   => 'Dinas Perkim',
                'role_id'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id'         => 2,
                'username'   => 'petugas_a',
                'password'   => $password,
                'instansi'   => 'Desa Saukang',
                'role_id'    => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id'         => 3,
                'username'   => 'petugas_b',
                'password'   => $password,
                'instansi'   => 'Balangnipa & Boto Lempangan',
                'role_id'    => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id'         => 4,
                'username'   => 'petugas_c',
                'password'   => $password,
                'instansi'   => 'Desa Saukang (Petugas 2)',
                'role_id'    => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        $this->db->table('users')->insertBatch($usersData);

        // 3. Seed User Desa assignments
        $userDesaData = [
            // petugas_a (ID 2) -> Saukang
            [
                'user_id'    => 2,
                'desa_id'    => '7307050009',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            // petugas_b (ID 3) -> Balangnipa & Boto Lempangan
            [
                'user_id'    => 3,
                'desa_id'    => '7307070005',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id'    => 3,
                'desa_id'    => '7307010006',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            // petugas_c (ID 4) -> Saukang (Sama dengan petugas_a)
            [
                'user_id'    => 4,
                'desa_id'    => '7307050009',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        $this->db->table('user_desa')->insertBatch($userDesaData);
    }
}
