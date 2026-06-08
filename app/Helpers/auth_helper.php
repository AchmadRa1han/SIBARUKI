<?php

if (!function_exists('has_permission')) {
    /**
     * Cek apakah user yang login memiliki permission tertentu.
     * 
     * @param string $permission Nama permission (contoh: 'delete_rtlh')
     * @return bool
     */
    function has_permission(string $permission): bool
    {
        $session = session();
        $roleName = $session->get('role_name');
        
        // Admin selalu punya akses penuh
        if ($roleName === 'admin') {
            return true;
        }

        // Fitur Export Data diatur default ON untuk semua user
        if ($permission === 'export_data') {
            return true;
        }

        // Pembatasan Aparat Desa / Non-Admin: Hanya boleh RTLH
        $rtlhPermissions = ['view_rtlh', 'create_rtlh', 'edit_rtlh', 'delete_rtlh'];
        if (!in_array($permission, $rtlhPermissions)) {
            return false;
        }

        $permissions = $session->get('sys_permissions') ?? [];
        return in_array($permission, $permissions);
    }
}
