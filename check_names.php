<?php
$conn = new mysqli('localhost', 'root', '', 'sibaruki');
echo "--- Kecamatan Table ---\n";
$res = $conn->query("SELECT kecamatan_nama FROM kode_kecamatan");
while($row = $res->fetch_assoc()) {
    echo "[" . $row['kecamatan_nama'] . "]\n";
}
echo "\n--- Desa-Join-Kecamatan names ---\n";
$res2 = $conn->query("SELECT DISTINCT k.kecamatan_nama FROM kode_desa d JOIN kode_kecamatan k ON d.kecamatan_id = k.kecamatan_id");
while($row = $res2->fetch_assoc()) {
    echo "[" . $row['kecamatan_nama'] . "]\n";
}
$conn->close();
