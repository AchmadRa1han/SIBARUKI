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
        // Fitur Export Data diatur default ON untuk semua user
        if ($permission === 'export_data') {
            return true;
        }

        $session = session();
        $permissions = $session->get('sys_permissions') ?? [];
        
        // Admin selalu punya akses penuh
        if ($session->get('role_name') === 'admin') {
            return true;
        }

        return in_array($permission, $permissions);
    }
}
