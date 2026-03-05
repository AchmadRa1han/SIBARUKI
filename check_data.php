<?php
$db = \Config\Database::connect();
$res = $db->table('aset_tanah')->where('id', 1)->get()->getRowArray();
print_r($res);
