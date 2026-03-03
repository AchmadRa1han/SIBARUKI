<?php
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
$app = require rtrim('app', '/') . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR . 'Paths.php');
$paths = new Config\Paths();
require $paths->systemDirectory . DIRECTORY_SEPARATOR . 'bootstrap.php';

$db = \Config\Database::connect();
$query = $db->query("SELECT COUNT(*) as total FROM wilayah_kumuh");
$row = $query->getRow();
echo "Total records in wilayah_kumuh table: " . $row->total . "
";
