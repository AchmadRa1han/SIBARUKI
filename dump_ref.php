<?php
// Load the CodeIgniter 4 framework bootstrap
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
$pathsConfig = 'app/Config/Paths.php';
require realpath($pathsConfig) ?: $pathsConfig;
$paths = new Config\Paths();
require $paths->systemDirectory . '/Boot.php';
\CodeIgniter\Boot::bootTest($paths);

$db = \Config\Database::connect();
$results = $db->table('ref_master')->get()->getResultArray();
foreach ($results as $row) {
    echo "ID: {$row['id']} | Kategori: {$row['kategori']} | Nama: [{$row['nama_pilihan']}]\n";
}
