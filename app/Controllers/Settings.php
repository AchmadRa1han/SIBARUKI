<?php

namespace App\Controllers;

class Settings extends BaseController
{
    public function index()
    {
        // Pastikan hanya yang punya minimal satu izin akses sistem yang bisa masuk
        if (!has_permission('manage_users') && !has_permission('manage_roles')) {
            return redirect()->to('/dashboard')->with('message', 'Akses ke menu Pengaturan ditolak.');
        }

        $data = [
            'title' => 'Pusat Pengaturan Sistem',
        ];

        return view('settings/index', $data);
    }
}
