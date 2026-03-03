<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RbacSeeder extends Seeder
{
    public function run()
    {
        // 1. Update Scope pada Roles
        $this->db->table('roles')->where('id', 1)->update(['scope' => 'global']); // Admin
        $this->db->table('roles')->where('id', 2)->update(['scope' => 'local']);  // Petugas

        // 2. Daftar Permissions
        $permissions = [
            // RTLH
            ['permission_name' => 'view_rtlh', 'description' => 'Melihat Daftar RTLH'],
            ['permission_name' => 'view_rtlh_detail', 'description' => 'Melihat Rincian Detail RTLH (Sensitif)'],
            ['permission_name' => 'create_rtlh', 'description' => 'Menambah Data RTLH'],
            ['permission_name' => 'edit_rtlh', 'description' => 'Mengubah Data RTLH'],
            ['permission_name' => 'delete_rtlh', 'description' => 'Menghapus Data RTLH'],
            
            // Wilayah Kumuh
            ['permission_name' => 'view_kumuh', 'description' => 'Melihat Daftar Wilayah Kumuh'],
            ['permission_name' => 'view_kumuh_detail', 'description' => 'Melihat Rincian Detail Wilayah Kumuh'],
            ['permission_name' => 'create_kumuh', 'description' => 'Menambah Data Wilayah Kumuh'],
            ['permission_name' => 'edit_kumuh', 'description' => 'Mengubah Data Wilayah Kumuh'],
            ['permission_name' => 'delete_kumuh', 'description' => 'Menghapus Data Wilayah Kumuh'],
            
            // User Management
            ['permission_name' => 'view_users', 'description' => 'Melihat Daftar Pengguna'],
            ['permission_name' => 'create_users', 'description' => 'Menambah Pengguna Baru'],
            ['permission_name' => 'edit_users', 'description' => 'Mengubah Profil Pengguna'],
            ['permission_name' => 'delete_users', 'description' => 'Menghapus Pengguna'],
            
            // Manajemen Sistem
            ['permission_name' => 'manage_roles', 'description' => 'Mengelola Role & Hak Akses'],
            ['permission_name' => 'export_data', 'description' => 'Export Data ke PDF/Excel'],
        ];

        // Gunakan IGNORE agar tidak error jika dijalankan ulang
        foreach ($permissions as $p) {
            $exists = $this->db->table('permissions')->where('permission_name', $p['permission_name'])->get()->getRow();
            if (!$exists) {
                $this->db->table('permissions')->insert($p);
            }
        }

        // 3. Mapping Role Permissions
        $allPermissions = $this->db->table('permissions')->get()->getResultArray();
        $permMap = [];
        foreach ($allPermissions as $p) {
            $permMap[$p['permission_name']] = $p['id'];
        }

        // Admin (Dapatkan SEMUA Izin)
        $adminRoleID = 1;
        $this->db->table('role_permissions')->where('role_id', $adminRoleID)->delete();
        $adminPermissions = [];
        foreach ($allPermissions as $p) {
            $adminPermissions[] = [
                'role_id'       => $adminRoleID,
                'permission_id' => $p['id'],
                'created_at'    => date('Y-m-d H:i:s')
            ];
        }
        $this->db->table('role_permissions')->insertBatch($adminPermissions);

        // Petugas (Izin Terbatas - Tetap seperti semula)
        $petugasRoleID = 2;
        $this->db->table('role_permissions')->where('role_id', $petugasRoleID)->delete();
        $petugasPermNames = [
            'view_rtlh', 'create_rtlh', 'edit_rtlh',
            'view_kumuh', 'create_kumuh', 'edit_kumuh',
            'export_data'
        ];
        
        $petugasPermissions = [];
        foreach ($petugasPermNames as $name) {
            if (isset($permMap[$name])) {
                $petugasPermissions[] = [
                    'role_id'       => $petugasRoleID,
                    'permission_id' => $permMap[$name],
                    'created_at'    => date('Y-m-d H:i:s')
                ];
            }
        }
        $this->db->table('role_permissions')->insertBatch($petugasPermissions);
    }
}
