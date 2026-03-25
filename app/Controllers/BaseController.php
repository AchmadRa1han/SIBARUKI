<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * @var \CodeIgniter\Database\BaseConnection
     */
    protected $db;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Load here all helpers you want to be available in your controllers that extend BaseController.
        $this->helpers = ['form', 'url', 'auth'];
        $this->db = \Config\Database::connect();

        // Update Last Active User (Throttled to once every 5 minutes to reduce DB load)
        if (session()->get('isLoggedIn')) {
            $lastUpdate = session()->get('last_active_update') ?? 0;
            $now = time();
            if (($now - $lastUpdate) > 300) { // 5 minutes
                $this->db->table('users')->where('id', session()->get('user_id'))->update([
                    'last_active' => date('Y-m-d H:i:s')
                ]);
                session()->set('last_active_update', $now);
            }
        }

        parent::initController($request, $response, $logger);
    }

    protected function logActivity($action, $table, $description, $details = null, $severity = null)
    {
        $agent = $this->request->getUserAgent();
        $ip = $this->request->getIPAddress();
        
        // Forensic Device Parsing
        $browser = $agent->getBrowser();
        $version = $agent->getVersion();
        $platform = $agent->getPlatform();
        $isMobile = $agent->isMobile() ? 'Mobile' : 'Desktop';
        
        // Calculate Processing Latency
        $startTime = $_SERVER["REQUEST_TIME_FLOAT"];
        $endTime = microtime(true);
        $latency = round(($endTime - $startTime) * 1000, 2); // in milliseconds

        // Auto-determine severity
        if (!$severity) {
            $severity = 'info';
            if (in_array($action, ['Hapus', 'Login Gagal', 'Reset Password', 'Ubah'])) $severity = 'warning';
            if ($action === 'Housekeeping' || str_contains(strtolower($description), 'force')) $severity = 'critical';
        }

        // Contextual Metadata (JSON)
        $metadata = json_encode([
            'browser' => $browser,
            'version' => $version,
            'platform' => $platform,
            'device' => $isMobile,
            'latency_ms' => $latency,
            'scope' => session()->get('role_scope') ?? 'unknown',
            'url' => current_url()
        ]);

        $this->db->table('sys_logs')->insert([
            'user'        => session()->get('username') ?? 'System',
            'action'      => $action,
            'severity'    => $severity,
            'table_name'  => $table,
            'description' => $description,
            'details'     => $details,
            'user_agent'  => $metadata, // Simpan sebagai JSON untuk transparansi total
            'ip_address'  => $ip,
            'created_at'  => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Memformat data mentah menjadi string deskriptif untuk log (Tambah/Hapus).
     */
    protected function formatLogData($data, $ignoreFields = ['password', 'created_at', 'updated_at'])
    {
        $entries = [];
        foreach ($data as $key => $value) {
            if (in_array($key, $ignoreFields) || empty($value)) continue;

            $displayValue = $value;
            
            // Logika Resolusi Nama
            if (is_numeric($value)) {
                if ($key === 'role_id') {
                    // Ambil dari tabel roles
                    $role = $this->db->table('roles')->where('id', $value)->get()->getRowArray();
                    if ($role) $displayValue = strtoupper($role['role_name']);
                } else {
                    // Ambil dari ref_master untuk kolom st_, mat_, atau _id lainnya
                    $isRef = str_starts_with($key, 'st_') || str_starts_with($key, 'mat_') || str_ends_with($key, '_id');
                    if ($isRef) {
                        $ref = $this->db->table('ref_master')->where('id', $value)->get()->getRowArray();
                        if ($ref) $displayValue = $ref['nama_pilihan'];
                    }
                }
            }

            $cleanKey = ucwords(str_replace(['st_', 'mat_', '_id', '_'], ['', '', '', ' '], $key));
            $entries[] = "{$cleanKey}: {$displayValue}";
        }
        return implode(" | ", $entries);
    }

    /**
     * Membandingkan data lama dan baru untuk menghasilkan log yang detail.
     */
    protected function generateDiff($oldData, $newData, $ignoreFields = ['updated_at', 'created_at'])
    {
        $changes = [];
        
        foreach ($newData as $key => $value) {
            if (in_array($key, $ignoreFields)) continue;
            
            if (array_key_exists($key, $oldData)) {
                $oldValue = $oldData[$key];
                
                $normOld = ($oldValue === null || $oldValue === '') ? null : $oldValue;
                $normNew = ($value === null || $value === '') ? null : $value;

                if ($normOld != $normNew) {
                    $displayOld = $normOld ?? 'KOSONG';
                    $displayNew = $normNew ?? 'KOSONG';

                    // Logika Resolusi Nama
                    if ($key === 'role_id') {
                        if ($normOld) {
                            $role = $this->db->table('roles')->where('id', $normOld)->get()->getRowArray();
                            if ($role) $displayOld = strtoupper($role['role_name']);
                        }
                        if ($normNew) {
                            $role = $this->db->table('roles')->where('id', $normNew)->get()->getRowArray();
                            if ($role) $displayNew = strtoupper($role['role_name']);
                        }
                    } else {
                        $isRef = str_starts_with($key, 'st_') || str_starts_with($key, 'mat_') || str_ends_with($key, '_id');
                        if ($isRef) {
                            if ($normOld && is_numeric($normOld)) {
                                $ref = $this->db->table('ref_master')->where('id', $normOld)->get()->getRowArray();
                                if ($ref) $displayOld = $ref['nama_pilihan'];
                            }
                            if ($normNew && is_numeric($normNew)) {
                                $ref = $this->db->table('ref_master')->where('id', $normNew)->get()->getRowArray();
                                if ($ref) $displayNew = $ref['nama_pilihan'];
                            }
                        }
                    }

                    $cleanKey = str_replace(['st_', 'mat_', '_id'], ['', '', ''], $key);
                    $cleanKey = ucwords(str_replace('_', ' ', $cleanKey));

                    $changes[] = "{$cleanKey} diubah dari '{$displayOld}' menjadi '{$displayNew}'";
                }
            }
        }
        return empty($changes) ? "Tidak ada perubahan data teknis." : implode(" | ", $changes);
    }
}
