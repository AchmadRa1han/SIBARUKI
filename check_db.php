<?php

require_once 'app/Config/Paths.php';
$paths = new \Config\Paths();

require_once $paths->systemDirectory . '/bootstrap.php';

$db = \Config\Database::connect();

$tables = ['arsinum', 'pisew', 'psu_jalan'];

foreach ($tables as $table) {
    echo "Table: $table\n";
    $fields = $db->getFieldNames($table);
    foreach ($fields as $field) {
        echo "  - $field\n";
    }
    echo "\n";
}
