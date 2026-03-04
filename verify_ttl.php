<?php
$db = \Config\Database::connect();
$row = $db->table('rtlh_penerima')->where('nik', '7307087112710055')->get()->getRowArray();
if ($row) {
    echo "NIK: " . $row['nik'] . "
";
    echo "Nama: " . $row['nama_kepala_keluarga'] . "
";
    echo "Tempat Lahir: [" . $row['tempat_lahir'] . "]
";
    echo "Tanggal Lahir: [" . $row['tanggal_lahir'] . "]
";
} else {
    echo "Data NIK 7307087112710055 tidak ditemukan.
";
}
