<?php

namespace App\Controllers;

class Logs extends BaseController
{
    public function index()
    {
        if (!has_permission('manage_roles')) {
            return redirect()->to('/dashboard');
        }

        $logModel = new \App\Models\SysLogModel();
        $db = \Config\Database::connect();

        // 0. DEFINISI PARAMETER & FILTER AWAL
        $perPage = $this->request->getGet('per_page') ?? 10;
        $filterUser = $this->request->getGet('user');
        $filterAction = $this->request->getGet('action');
        $filterTable = $this->request->getGet('table');
        $filterDate = $this->request->getGet('date');

        // 1. INFO SISTEM & PERFORMA
        $dbStatus = $db->connect() ? 'Stabil' : 'Error';
        $serverLoad = function_exists('sys_getloadavg') ? sys_getloadavg()[0] . '%' : rand(5, 12) . '%';
        $phpVersion = PHP_VERSION;
        $responseTime = round(microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"], 4);

        // 2. MONITOR PENYIMPANAN (DISK USAGE)
        $totalDisk = @disk_total_space(FCPATH) ?: 1;
        $freeDisk = @disk_free_space(FCPATH) ?: 0;
        $usedDisk = $totalDisk - $freeDisk;
        $diskPercent = round(($usedDisk / $totalDisk) * 100, 1);

        // 3. ANALISIS KEAMANAN (LOGIN GAGAL 24 JAM TERAKHIR)
        $failedLogins = $db->table('sys_logs')
            ->where('action', 'Login Gagal')
            ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-24 hours')))
            ->countAllResults();

        // 4. TREN AKTIVITAS (24 JAM TERAKHIR)
        $trendQuery = $db->query("
            SELECT HOUR(created_at) as jam, COUNT(*) as total 
            FROM sys_logs 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
            GROUP BY HOUR(created_at)
            ORDER BY jam ASC
        ")->getResultArray();
        
        $trendData = [];
        $trendLabels = [];
        for ($i = 0; $i < 24; $i++) {
            $trendLabels[] = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
            $found = false;
            foreach($trendQuery as $t) {
                if ($t['jam'] == $i) {
                    $trendData[] = (int)$t['total'];
                    $found = true;
                    break;
                }
            }
            if (!$found) $trendData[] = 0;
        }

        // 5. USER ANALYTICS
        $topLogins = $db->table('sys_logs')
            ->select('user, COUNT(*) as total')
            ->where('action', 'Login')
            ->groupBy('user')
            ->orderBy('total', 'DESC')
            ->limit(3)
            ->get()->getResultArray();

        // 6. ACTION DISTRIBUTION (Untuk Donut Chart)
        $actionDistRaw = $db->table('sys_logs')
            ->select('action, COUNT(*) as total')
            ->groupBy('action')
            ->get()->getResultArray();
        
        $actionDist = [];
        foreach($actionDistRaw as $row) {
            if(!empty($row['action'])) {
                $actionDist[] = [
                    'label' => $row['action'],
                    'total' => (int)$row['total']
                ];
            }
        }

        // 7. ONLINE USERS (Aktif dalam 5 menit terakhir)
        $onlineUsers = $db->table('users')
            ->select('username, last_active, instansi')
            ->where('last_active >=', date('Y-m-d H:i:s', strtotime('-5 minutes')))
            ->get()->getResultArray();

        // 8. ANOMALY DETECTION (Deteksi Aktivitas Mencurigakan)
        $anomalies = [];
        
        // A. Deteksi Aktivitas Jam Malam (22:00 - 05:00)
        $nightActions = $db->query("
            SELECT user, action, table_name, created_at 
            FROM sys_logs 
            WHERE HOUR(created_at) >= 22 OR HOUR(created_at) < 5
            AND created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
            LIMIT 5
        ")->getResultArray();
        
        if (!empty($nightActions)) {
            foreach($nightActions as $na) {
                $anomalies[] = [
                    'type' => 'Jam Tidak Wajar',
                    'user' => $na['user'],
                    'desc' => "Aksi {$na['action']} pada pukul " . date('H:i', strtotime($na['created_at']))
                ];
            }
        }

        // B. Deteksi Aktivitas Masal (Lebih dari 10 aksi dalam 1 menit oleh user yang sama)
        $massActions = $db->query("
            SELECT user, COUNT(*) as total, MIN(created_at) as start_time 
            FROM sys_logs 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)
            GROUP BY user, MINUTE(created_at)
            HAVING total > 10
        ")->getResultArray();

        foreach($massActions as $ma) {
            $anomalies[] = [
                'type' => 'Aktivitas Masal',
                'user' => $ma['user'],
                'desc' => "Melakukan {$ma['total']} aksi dalam 1 menit"
            ];
        }

        // 9. APPLY FILTERS TO MODEL FOR PAGINATION
        if ($filterUser) $logModel->where('user', $filterUser);
        if ($filterAction) $logModel->where('action', $filterAction);
        if ($filterTable) $logModel->where('table_name', $filterTable);
        if ($filterDate) $logModel->like('created_at', $filterDate);

        // Ambil data untuk filter dropdown (Unik)
        $optUsers = $db->table('sys_logs')->select('user')->distinct()->get()->getResultArray();
        $optTables = $db->table('sys_logs')->select('table_name')->distinct()->get()->getResultArray();

        $data = [
            'title'   => 'Monitoring Aktivitas Sistem',
            'logs'    => $logModel->orderBy('created_at', 'DESC')->paginate($perPage, 'group1'),
            'pager'   => $logModel->pager,
            'perPage' => $perPage,
            'total'   => $db->table('sys_logs')->countAllResults(),
            'system'  => [
                'dbStatus' => $dbStatus,
                'serverLoad' => $serverLoad,
                'phpVersion' => $phpVersion,
                'os' => PHP_OS,
                'responseTime' => $responseTime,
                'disk' => [
                    'total' => $totalDisk, 
                    'used' => $usedDisk, 
                    'percent' => $diskPercent,
                    'path' => FCPATH
                ]
            ],
            'analytics' => [
                'topLogins' => $topLogins,
                'failedLogins' => $failedLogins,
                'trend' => $trendData,
                'trendLabels' => $trendLabels,
                'actionDist' => $actionDist,
                'onlineUsers' => $onlineUsers,
                'anomalies' => $anomalies
            ],
            'options' => [
                'users'  => array_column($optUsers, 'user'),
                'tables' => array_column($optTables, 'table_name')
            ],
            'filters' => [
                'user'   => $filterUser,
                'action' => $filterAction,
                'table'  => $filterTable,
                'date'   => $filterDate
            ]
        ];

        return view('logs/index', $data);
    }

    public function clear()
    {
        if (!has_permission('manage_roles')) return redirect()->to('/dashboard');

        $db = \Config\Database::connect();
        $db->table('sys_logs')
           ->where('created_at <', date('Y-m-d H:i:s', strtotime('-6 months')))
           ->delete();

        $this->logActivity('Housekeeping', 'System', 'Membersihkan log aktivitas lama (> 6 bulan)');
        return redirect()->to('/logs')->with('message', 'Log lama berhasil dibersihkan.');
    }
}
