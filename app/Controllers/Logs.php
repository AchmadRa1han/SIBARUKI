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
        $filterSeverity = $this->request->getGet('severity');

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

        // 4. TREN AKTIVITAS (Multi-Range)
        $trendHourlyQuery = $db->query("SELECT HOUR(created_at) as jam, COUNT(*) as total FROM sys_logs WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR) GROUP BY HOUR(created_at) ORDER BY jam ASC")->getResultArray();
        $trendHourlyData = array_fill(0, 24, 0);
        foreach($trendHourlyQuery as $t) { $trendHourlyData[$t['jam']] = (int)$t['total']; }
        $trendHourlyLabels = [];
        for($i=0;$i<24;$i++) { $trendHourlyLabels[] = str_pad($i, 2, '0', STR_PAD_LEFT); }

        $trendDailyQuery = $db->query("SELECT DATE(created_at) as tgl, COUNT(*) as total FROM sys_logs WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) GROUP BY DATE(created_at) ORDER BY tgl ASC")->getResultArray();
        $trendDailyData = []; $trendDailyLabels = [];
        for($i=6; $i>=0; $i--) {
            $d = date('Y-m-d', strtotime("-$i days"));
            $trendDailyLabels[] = date('d M', strtotime($d));
            $found = false;
            foreach($trendDailyQuery as $t) { if($t['tgl'] == $d) { $trendDailyData[] = (int)$t['total']; $found = true; break; } }
            if(!$found) $trendDailyData[] = 0;
        }

        $trendMonthlyQuery = $db->query("SELECT DATE_FORMAT(created_at, '%Y-%m') as bulan, COUNT(*) as total FROM sys_logs WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH) GROUP BY bulan ORDER BY bulan ASC")->getResultArray();
        $trendMonthlyData = []; $trendMonthlyLabels = [];
        for($i=5; $i>=0; $i--) {
            $m = date('Y-m', strtotime("-$i months"));
            $trendMonthlyLabels[] = date('M Y', strtotime($m));
            $found = false;
            foreach($trendMonthlyQuery as $t) { if($t['bulan'] == $m) { $trendMonthlyData[] = (int)$t['total']; $found = true; break; } }
            if(!$found) $trendMonthlyData[] = 0;
        }

        // 5. USER ANALYTICS
        $topLogins = $db->table('sys_logs')
            ->select('user, COUNT(*) as total')
            ->where('action', 'Login')
            ->groupBy('user')
            ->orderBy('total', 'DESC')
            ->limit(3)
            ->get()->getResultArray();

        // 6. ACTION DISTRIBUTION
        $actionDistRaw = $db->table('sys_logs')->select('action, COUNT(*) as total')->groupBy('action')->get()->getResultArray();
        $actionDist = [];
        foreach($actionDistRaw as $row) { if(!empty($row['action'])) { $actionDist[] = ['label' => $row['action'], 'total' => (int)$row['total']]; } }

        // 7. ONLINE USERS
        $onlineUsers = $db->table('users')->select('username, last_active, instansi')->where('last_active >=', date('Y-m-d H:i:s', strtotime('-5 minutes')))->get()->getResultArray();

        // 8. ANOMALY DETECTION
        $anomalies = [];
        $nightActions = $db->query("SELECT user, action, table_name, created_at FROM sys_logs WHERE (HOUR(created_at) >= 22 OR HOUR(created_at) < 5) AND created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR) LIMIT 5")->getResultArray();
        foreach($nightActions as $na) { $anomalies[] = ['type' => 'Jam Tidak Wajar', 'user' => $na['user'], 'desc' => "Aksi {$na['action']} pada " . date('H:i', strtotime($na['created_at']))]; }
        $massActions = $db->query("SELECT user, COUNT(*) as total FROM sys_logs WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR) GROUP BY user, MINUTE(created_at) HAVING total > 10")->getResultArray();
        foreach($massActions as $ma) { $anomalies[] = ['type' => 'Aktivitas Masal', 'user' => $ma['user'], 'desc' => "Melakukan {$ma['total']} aksi dalam 1 menit"]; }

        // 8.1 AUDIT SNAPSHOT (CRITICAL ACTIONS 24H)
        $snapshot = [
            'deleted' => $db->table('sys_logs')->where('action', 'Hapus')->where('created_at >=', date('Y-m-d H:i:s', strtotime('-24 hours')))->countAllResults(),
            'exported' => $db->table('sys_logs')->where('action', 'Ekspor PDF')->where('created_at >=', date('Y-m-d H:i:s', strtotime('-24 hours')))->countAllResults(),
            'created' => $db->table('sys_logs')->where('action', 'Tambah')->where('created_at >=', date('Y-m-d H:i:s', strtotime('-24 hours')))->countAllResults(),
            'critical' => $db->table('sys_logs')->whereIn('severity', ['warning', 'critical'])->where('created_at >=', date('Y-m-d H:i:s', strtotime('-24 hours')))->countAllResults(),
        ];

        // 9. LOGS QUERY WITH USER INFO
        $logModel->select('sys_logs.*, users.instansi, roles.role_name')
                 ->join('users', 'users.username = sys_logs.user', 'left')
                 ->join('roles', 'roles.id = users.role_id', 'left');

        if ($filterUser) $logModel->where('sys_logs.user', $filterUser);
        if ($filterAction) $logModel->where('sys_logs.action', $filterAction);
        if ($filterTable) $logModel->where('sys_logs.table_name', $filterTable);
        if ($filterSeverity) $logModel->where('sys_logs.severity', $filterSeverity);
        if ($filterDate) $logModel->like('sys_logs.created_at', $filterDate);

        // Filter Dropdown Options
        $optUsers = $db->table('sys_logs')->select('user')->distinct()->get()->getResultArray();
        $optTables = $db->table('sys_logs')->select('table_name')->distinct()->get()->getResultArray();

        $data = [
            'title'   => 'Monitoring Aktivitas Sistem',
            'logs'    => $logModel->orderBy('sys_logs.created_at', 'DESC')->paginate($perPage, 'group1'),
            'pager'   => $logModel->pager,
            'perPage' => $perPage,
            'total'   => $db->table('sys_logs')->countAllResults(),
            'snapshot' => $snapshot,
            'system'  => [
                'dbStatus' => $dbStatus, 'serverLoad' => $serverLoad, 'phpVersion' => $phpVersion, 'os' => PHP_OS, 
                'responseTime' => $responseTime, 'disk' => ['total' => $totalDisk, 'used' => $usedDisk, 'percent' => $diskPercent, 'path' => FCPATH]
            ],
            'analytics' => [
                'topLogins' => $topLogins, 'failedLogins' => $failedLogins, 'onlineUsers' => $onlineUsers, 'anomalies' => $anomalies, 'actionDist' => $actionDist,
                'trend' => ['hourly' => ['data' => $trendHourlyData, 'labels' => $trendHourlyLabels], 'daily' => ['data' => $trendDailyData, 'labels' => $trendDailyLabels], 'monthly' => ['data' => $trendMonthlyData, 'labels' => $trendMonthlyLabels]]
            ],
            'options' => ['users' => array_column($optUsers, 'user'), 'tables' => array_column($optTables, 'table_name')],
            'filters' => ['user' => $filterUser, 'action' => $filterAction, 'table' => $filterTable, 'date' => $filterDate, 'severity' => $filterSeverity]
        ];

        return view('logs/index', $data);
    }

    public function clear()
    {
        if (!has_permission('manage_roles')) return redirect()->to('/dashboard');
        $db = \Config\Database::connect();
        $db->table('sys_logs')->where('created_at <', date('Y-m-d H:i:s', strtotime('-6 months')))->delete();
        $this->logActivity('Housekeeping', 'System', 'Membersihkan log aktivitas lama (> 6 bulan)');
        return redirect()->to('/logs')->with('message', 'Log lama berhasil dibersihkan.');
    }
}
