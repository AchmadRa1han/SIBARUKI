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
        $chartDesaBuilder = $db->table('rtlh_rumah')->select('desa, COUNT(id_survei) as total');
        if ($roleScope === 'local') {
            $chartDesaBuilder->whereIn('desa_id', !empty($desaRtlh) ? $desaRtlh : ['0']);
        }
        $chartDesa = $chartDesaBuilder->groupBy('desa')->orderBy('total', 'DESC')->limit(5)->get()->getResultArray();

        // Status Kelayakan (Filtered)
        $layakQuery = "
            SELECT 
                SUM(CASE WHEN st_atap = 'RUSAK' AND st_lantai = 'RUSAK' THEN 1 ELSE 0 END) as tidak_layak,
                SUM(CASE WHEN st_atap = 'BAIK' AND st_lantai = 'BAIK' THEN 1 ELSE 0 END) as layak,
                COUNT(*) - SUM(CASE WHEN st_atap = 'RUSAK' AND st_lantai = 'RUSAK' THEN 1 ELSE 0 END) 
                         - SUM(CASE WHEN st_atap = 'BAIK' AND st_lantai = 'BAIK' THEN 1 ELSE 0 END) as menuju_layak
            FROM rtlh_kondisi_rumah kr
            JOIN rtlh_rumah r ON r.id_survei = kr.id_survei
        ";
        if ($roleScope === 'local') {
            $desaList = "'" . implode("','", (!empty($desaRtlh) ? $desaRtlh : ['0'])) . "'";
            $layakQuery .= " WHERE r.desa_id IN ($desaList)";
        }
        $statusLayak = $db->query($layakQuery)->getRowArray();

        // --- 3. WILAYAH KRITIS (TOP 5 KUMUH) ---
        $topKumuhBuilder = $db->table('wilayah_kumuh');
        if ($roleScope === 'local') {
            $topKumuhBuilder->whereIn('desa_id', !empty($desaKumuh) ? $desaKumuh : ['0']);
        }
        $topKumuh = $topKumuhBuilder->orderBy('skor_kumuh', 'DESC')->limit(5)->get()->getResultArray();

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

        // --- 6. LOG AKTIVITAS (Filter by User if not Admin) ---
        $logs = [];
        if ($db->tableExists('sys_logs')) {
            $logBuilder = $db->table('sys_logs')->orderBy('created_at', 'DESC');
            if ($roleScope === 'local') {
                $logBuilder->where('user', session()->get('username'));
            }
            $logs = $logBuilder->limit(6)->get()->getResultArray();
        }

        // --- 7. DAFTAR WILAYAH TUGAS (Untuk Widget Coverage) ---
        $assignedDesaNames = [];
        if ($roleScope === 'local') {
            $allMyDesa = array_unique(array_merge($desaRtlh, $desaKumuh));
            if (!empty($allMyDesa)) {
                $desaData = $db->table('kode_desa')
                    ->select('desa_nama')
                    ->whereIn('desa_id', $allMyDesa)
                    ->get()->getResultArray();
                $assignedDesaNames = array_column($desaData, 'desa_nama');
            }
        }

        $data = [
            'title'         => 'Dashboard',
            'totalRtlh'     => $totalRtlh,
            'totalKumuh'    => $totalKumuh,
            'totalDesa'     => $totalDesa,
            'chartDesa'     => $chartDesa,
            'statusLayak'   => $statusLayak,
            'topKumuh'      => $topKumuh,
            'health'        => ['coords' => $missingCoords, 'kk' => $missingKK],
            'mapMarkers'    => $mapMarkers,
            'assignedDesa'  => $assignedDesaNames,
            'logs'          => $logs
        ];

        return view('dashboard', $data);
    }
}
