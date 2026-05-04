<?php
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
require 'vendor/autoload.php';

try {
    // Manually setting up enough of CI4 to get Database to work
    $db = \Config\Database::connect();
    
    $desaList = $db->table('kode_desa')->get()->getResultArray();
    
    echo "Checking Bansos RTLH records...\n";
    $allBansos = $db->table('rtlh_bansos')->get()->getResultArray();
    foreach($allBansos as $b) {
        $idSurveiStr = ($b['id_survei'] === null) ? 'NULL' : "'{$b['id_survei']}'";
        echo "- ID: {$b['id']}, NIK: {$b['nik']}, Nama: {$b['nama_penerima']}, Desa: '{$b['desa']}', ID Survei: $idSurveiStr\n";
    }

    echo "\nLogic Simulation for 'MANNANTI':\n";
    foreach($desaList as $d) {
        if (trim(strtoupper($d['desa_nama'])) == 'MANNANTI') {
             echo "Target Desa Found: {$d['desa_nama']} (ID: {$d['desa_id']})\n";
             
             $rlhSurvei = $db->table('rtlh_rumah')
                           ->where('desa_id', $d['desa_id'])
                           ->where('status_bantuan', 'Sudah Menerima')
                           ->countAllResults();
             echo "  1. RLH from rtlh_rumah (status 'Sudah Menerima'): $rlhSurvei\n";

             $query = "
                SELECT COUNT(*) as total FROM rtlh_bansos b
                WHERE (TRIM(UPPER(b.desa)) = ? OR TRIM(UPPER(b.desa)) = ?)
                AND (b.id_survei IS NULL OR b.id_survei = '')
                AND b.nik NOT IN (SELECT nik_pemilik FROM rtlh_rumah WHERE desa_id = ?)
            ";
            $res = $db->query($query, ['MANNANTI', 'MANNANTI', $d['desa_id']])->getRowArray();
            $bansosExtra = $res['total'] ?? 0;
            echo "  2. Bansos Extra (not in rtlh_rumah): $bansosExtra\n";
            
            // Check why it might be 0
            echo "  --- Detailed Breakdown ---\n";
            foreach($allBansos as $b) {
                if (trim(strtoupper($b['desa'])) == 'MANNANTI') {
                    echo "  - Record '{$b['nama_penerima']}':\n";
                    echo "    - ID Survei is empty/null? " . (empty($b['id_survei']) ? "YES" : "NO") . "\n";
                    $inRumah = $db->table('rtlh_rumah')->where('nik_pemilik', $b['nik'])->where('desa_id', $d['desa_id'])->countAllResults();
                    echo "    - Exists in rtlh_rumah for this village? " . ($inRumah > 0 ? "YES (Count: $inRumah)" : "NO") . "\n";
                }
            }
        }
    }

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
