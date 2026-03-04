<?php
$db = \Config\Database::connect();
$results = $db->table('ref_master')->select('kategori, nama_pilihan')->orderBy('kategori', 'ASC')->get()->getResultArray();

$current_data = [];
foreach ($results as $row) {
    $current_data[$row['kategori']][] = $row['nama_pilihan'];
}

header('Content-Type: application/json');
echo json_encode($current_data, JSON_PRETTY_PRINT);
