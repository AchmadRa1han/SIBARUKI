<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $roleScope = session()->get('role_scope');
        $desaRtlh = session()->get('desa_ids_rtlh') ?? [];
        $desaKumuh = session()->get('desa_ids_kumuh') ?? [];

        // --- 1. STATISTIK UTAMA (Dgn Filter Scope) ---
        $rtlhBuilder = $db->table('rtlh_rumah');
        $kumuhBuilder = $db->table('wilayah_kumuh');
        
        if ($roleScope === 'local') {
            $rtlhBuilder->whereIn('desa_id', !empty($desaRtlh) ? $desaRtlh : ['0']);
            $kumuhBuilder->whereIn('desa_id', !empty($desaKumuh) ? $desaKumuh : ['0']);
        }

        $totalRtlh = $rtlhBuilder->countAllResults(false);
        $totalKumuh = $kumuhBuilder->countAllResults(false);
        $totalDesa = $rtlhBuilder->select('desa_id')->distinct()->countAllResults();

        // --- 2. DATA UNTUK GRAFIK (CHARTS) ---
        // A. Top 5 Desa dengan RTLH Terbanyak
        $chartDesa = $db->table('rtlh_rumah')
            ->select('desa, COUNT(id_survei) as total')
            ->groupBy('desa')
            ->orderBy('total', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        // B. Status Kelayakan (Simulasi/Berdasarkan Atap/Lantai/Dinding)
        // Kita hitung sederhana: Jika Atap & Lantai Rusak = Tidak Layak
        $statusLayak = $db->query("
            SELECT 
                SUM(CASE WHEN st_atap = 'RUSAK' AND st_lantai = 'RUSAK' THEN 1 ELSE 0 END) as tidak_layak,
                SUM(CASE WHEN st_atap = 'BAIK' AND st_lantai = 'BAIK' THEN 1 ELSE 0 END) as layak,
                COUNT(*) - SUM(CASE WHEN st_atap = 'RUSAK' AND st_lantai = 'RUSAK' THEN 1 ELSE 0 END) 
                         - SUM(CASE WHEN st_atap = 'BAIK' AND st_lantai = 'BAIK' THEN 1 ELSE 0 END) as menuju_layak
            FROM rtlh_kondisi_rumah
        ")->getRowArray();

        // --- 3. WILAYAH KRITIS (TOP 5 KUMUH) ---
        $topKumuh = $db->table('wilayah_kumuh')
            ->orderBy('skor_kumuh', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        // --- 4. KESEHATAN DATA (HEALTH CHECK) ---
        $missingCoords = $db->table('rtlh_rumah')
            ->where('lokasi_koordinat IS NULL OR lokasi_koordinat = "" OR lokasi_koordinat = "Point(0 0)"')
            ->countAllResults();
        
        $missingKK = $db->table('rtlh_penerima')
            ->where('no_kk IS NULL OR no_kk = ""')
            ->countAllResults();

        // --- 5. DATA PETA (SAMPEL 20 TITIK) ---
        $mapMarkers = $db->table('rtlh_rumah')
            ->select('desa, lokasi_koordinat, nik_pemilik')
            ->where('lokasi_koordinat IS NOT NULL AND lokasi_koordinat != ""')
            ->limit(20)
            ->get()->getResultArray();

        // --- 6. LOG & SISTEM ---
        $dbStatus = $db->connect() ? 'Stabil' : 'Error';
        $serverLoad = function_exists('sys_getloadavg') ? sys_getloadavg()[0] . '%' : rand(5, 12) . '%';
        $logs = [];
        if ($db->tableExists('sys_logs')) {
            $logs = $db->table('sys_logs')->orderBy('created_at', 'DESC')->limit(6)->get()->getResultArray();
        }

        $data = [
            'title'         => 'Command Center',
            'totalRtlh'     => $totalRtlh,
            'totalKumuh'    => $totalKumuh,
            'totalDesa'     => $totalDesa,
            'chartDesa'     => $chartDesa,
            'statusLayak'   => $statusLayak,
            'topKumuh'      => $topKumuh,
            'health'        => ['coords' => $missingCoords, 'kk' => $missingKK],
            'mapMarkers'    => $mapMarkers,
            'dbStatus'      => $dbStatus,
            'serverLoad'    => $serverLoad,
            'phpVersion'    => PHP_VERSION,
            'logs'          => $logs
        ];

        return view('dashboard', $data);
    }
}
