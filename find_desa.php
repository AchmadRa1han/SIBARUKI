<?php
require 'vendor/autoload.php';
$db = \Config\Database::connect();
$desa = $db->table('kode_desa')->like('desa_nama', 'MANNANTI')->get()->getRowArray();
if ($desa) {
    echo "Found: ID={$desa['desa_id']}, Name='{$desa['desa_nama']}'\n";
} else {
    echo "Not found with LIKE. Trying search all.\n";
    $all = $db->table('kode_desa')->get()->getResultArray();
    foreach($all as $d) {
        if (strpos(strtoupper($d['desa_nama']), 'MANNANTI') !== false) {
            echo "Match found: ID={$d['desa_id']}, Name='{$d['desa_nama']}'\n";
        }
    }
}
