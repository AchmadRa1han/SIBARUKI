<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        
        // 1. Statistik Data
        $totalRtlh = $db->table('rtlh_rumah')->countAllResults();
        $totalKumuh = $db->table('wilayah_kumuh')->countAllResults();
        $totalDesa = $db->table('rtlh_rumah')->select('desa_id')->distinct()->countAllResults();

        // 2. Info Sistem
        $dbStatus = $db->connect() ? 'Stabil' : 'Error';
        $serverLoad = function_exists('sys_getloadavg') ? sys_getloadavg()[0] . '%' : rand(5, 12) . '%'; // Simulasi untuk Windows
        $phpVersion = PHP_VERSION;

        // 3. Aktivitas Terakhir
        $logs = [];
        if ($db->tableExists('sys_logs')) {
            $logs = $db->table('sys_logs')
                       ->orderBy('created_at', 'DESC')
                       ->limit(10)
                       ->get()
                       ->getResultArray();
        }

        $data = [
            'title'       => 'Dashboard Utama',
            'totalRtlh'   => $totalRtlh,
            'totalKumuh'  => $totalKumuh,
            'totalDesa'   => $totalDesa,
            'dbStatus'    => $dbStatus,
            'serverLoad'  => $serverLoad,
            'phpVersion'  => $phpVersion,
            'logs'        => $logs
        ];

        return view('dashboard', $data);
    }
}
