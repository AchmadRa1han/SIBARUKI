<?php
$db = \Config\Database::connect();
echo "Total di rtlh_penerima: " . $db->table('rtlh_penerima')->countAllResults() . "
";
echo "Total di rtlh_rumah: " . $db->table('rtlh_rumah')->countAllResults() . "
";
echo "Total di rtlh_kondisi_rumah: " . $db->table('rtlh_kondisi_rumah')->countAllResults() . "
";

$sample = $db->table('rtlh_rumah')->limit(1)->get()->getRowArray();
echo "
Contoh Data Rumah:
";
print_r($sample);
