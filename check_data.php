<?php
$db = \Config\Database::connect();
$row = $db->table('aset_tanah')->where('no_sertifikat', '00009')->get()->getRowArray();
print_r($row);
