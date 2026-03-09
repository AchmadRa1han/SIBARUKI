<?php
$conn = new mysqli('localhost', 'root', '', 'sibaruki');
$res = $conn->query("SELECT kecamatan_id, kecamatan_nama FROM kode_kecamatan");
while($row = $res->fetch_assoc()) {
    echo $row['kecamatan_id'] . "|" . bin2hex($row['kecamatan_nama']) . "|" . $row['kecamatan_nama'] . "\n";
}
$conn->close();
