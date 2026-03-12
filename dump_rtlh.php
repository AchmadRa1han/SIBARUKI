<?php
require 'app/Config/Database.php';
$config = new \Config\Database();
$db = mysqli_connect($config->default['hostname'], $config->default['username'], $config->default['password'], $config->default['database']);
$res = mysqli_query($db, "SELECT * FROM rtlh_penerima");
while($row = mysqli_fetch_assoc($res)) {
    echo "NIK: {$row['nik']} | Nama: {$row['nama_kepala_keluarga']} | Tempat: {$row['tempat_lahir']} | Tgl: {$row['tanggal_lahir']}\n";
}
