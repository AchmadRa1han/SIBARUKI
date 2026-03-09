<?php
$conn = new mysqli('localhost', 'root', '', 'sibaruki');
echo "--- Kecamatan Table Hex ---\n";
$res = $conn->query("SELECT kecamatan_id, kecamatan_nama FROM kode_kecamatan");
while($row = $res->fetch_assoc()) {
    echo $row['kecamatan_id'] . "|" . bin2hex($row['kecamatan_nama']) . "|" . $row['kecamatan_nama'] . "\n";
}
echo "\n--- Tellu Limpoe Villages check ---\n";
$res2 = $conn->query("SELECT d.desa_nama, k.kecamatan_nama FROM kode_desa d JOIN kode_kecamatan k ON d.kecamatan_id = k.kecamatan_id WHERE k.kecamatan_id = 730704");
while($row = $res2->fetch_assoc()) {
    echo "Desa: [" . $row['desa_nama'] . "] | Kec: [" . $row['kecamatan_nama'] . "]\n";
}
$conn->close();
