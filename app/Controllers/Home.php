<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        
        // 1. Menghitung Total Tabel yang kita kelola
        $tables = ['ref_master', 'rtlh_penerima', 'rumah_rtlh', 'rtlh_kondisi_rumah', 'wilayah_kumuh'];
        $totalTables = count($tables);

        // 2. Menghitung Total Seluruh Data (Row) dari tabel-tabel tersebut
        $totalData = 0;
        foreach ($tables as $t) {
            $totalData += $db->table($t)->countAll();
        }

        // 3. Mengambil 5 Aktivitas Terakhir
        // Jika tabel sys_logs belum dibuat, ini akan mengembalikan array kosong
        $logs = [];
        if ($db->tableExists('sys_logs')) {
            $logs = $db->table('sys_logs')
                       ->orderBy('created_at', 'DESC')
                       ->limit(5)
                       ->get()
                       ->getResultArray();
        }

        $data = [
            'title'       => 'Dashboard Utama',
            'totalTables' => $totalTables,
            'totalData'   => $totalData,
            'logs'        => $logs
        ];

        return view('dashboard', $data);
    }
}
