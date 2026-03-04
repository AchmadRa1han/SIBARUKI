<?php
// check_tongke.php
require 'app/Config/Paths.php';
$paths = new \Config\Paths();
require $paths->systemDirectory . '/Common.php';
require 'app/Config/Autoload.php';
require 'app/Config/Services.php';

$db = \Config\Database::connect();
$row = $db->table('wilayah_kumuh')->like('Kode_RT_RW', 'Tongke-Tongke')->get()->getRowArray();

if ($row) {
    echo "DATA DITEMUKAN:
";
    echo "FID: " . $row['FID'] . "
";
    echo "Kode_RT_RW: " . $row['Kode_RT_RW'] . "
";
    echo "Luas: " . $row['Luas_kumuh'] . "
";
    echo "WKT Status: " . (empty($row['WKT']) ? "KOSONG" : "ADA (" . strlen($row['WKT']) . " karakter)") . "
";
    if (!empty($row['WKT'])) {
        echo "WKT Snippet: " . substr($row['WKT'], 0, 50) . "..." . substr($row['WKT'], -20) . "
";
    }
} else {
    echo "Data 'Tongke-Tongke' tidak ditemukan di database.
";
}
