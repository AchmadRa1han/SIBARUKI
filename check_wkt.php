<?php
$id = 1; // Assuming 1 for testing
$db = mysqli_connect('localhost', 'root', '', 'sibaruki');
$res = mysqli_query($db, "SELECT ST_AsText(lokasi_koordinat) as wkt FROM perumahan_rtlh_rumah WHERE id_survei = $id");
if ($row = mysqli_fetch_assoc($res)) {
    echo "WKT: " . $row['wkt'] . PHP_EOL;
} else {
    echo "No record found." . PHP_EOL;
}
?>
