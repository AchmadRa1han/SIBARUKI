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
        $permissions = $session->get('permissions') ?? [];
        
        // Admin selalu punya akses penuh
        if ($session->get('role_name') === 'admin') {
            return true;
        }

        return in_array($permission, $permissions);
    }
}
