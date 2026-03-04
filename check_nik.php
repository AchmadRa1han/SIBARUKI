<?php
$db = \Config\Database::connect();
$penerima = $db->table('rtlh_penerima')->limit(5)->get()->getResultArray();
$rumah = $db->table('rtlh_rumah')->limit(5)->get()->getResultArray();

echo "--- CONTOH PENERIMA ---
";
foreach($penerima as $p) echo "NIK: [" . $p['nik'] . "] Nama: " . $p['nama_kepala_keluarga'] . "
";

echo "
--- CONTOH RUMAH ---
";
foreach($rumah as $r) echo "NIK Pemilik: [" . $r['nik_pemilik'] . "]
";
