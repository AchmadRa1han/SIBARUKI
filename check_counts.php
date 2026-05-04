<?php
require 'vendor/autoload.php';
$db = \Config\Database::connect();
$res = $db->table('rtlh_rumah')
          ->select('status_bantuan, COUNT(*) as total')
          ->groupBy('status_bantuan')
          ->get()->getResultArray();
print_r($res);
