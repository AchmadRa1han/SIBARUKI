<?php
// simulate_matching.php
require 'app/Config/Paths.php';
$paths = new \Config\Paths();

// Minimal bootstrap untuk akses Database CI4
require $paths->systemDirectory . '/Common.php';
require 'app/Config/Autoload.php';
require 'app/Config/Services.php';

$db = \Config\Database::connect();

// 1. Ambil Data dari Database
$dbRecords = $db->table('wilayah_kumuh')->get()->getResultArray();
$dbCount = count($dbRecords);

// 2. Baca Data dari output.csv
$csvFile = 'output.csv';
$csvData = [];
if (($handle = fopen($csvFile, "r")) !== FALSE) {
    $header = fgetcsv($handle, 10000, ","); // Lewati header
    while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
        if (!empty($data[2])) { // Lokasi ada di kolom index 2
            $csvData[] = $data;
        }
    }
    fclose($handle);
}
$csvCount = count($csvData);

echo "--- HASIL SIMULASI PENCOCOKAN DATA ---
";
echo "Database: $dbCount baris | CSV (output.csv): $csvCount baris

";

$matched = [];
$unmatchedDB = $dbRecords;
$newInCSV = [];

// Fungsi Normalisasi untuk Pembandingan yang Akurat
function normalize($text) {
    $text = strtolower(trim($text));
    $text = str_replace(['_', '-', ' '], '', $text);
    return $text;
}

foreach ($csvData as $csvRow) {
    $csvLokasiOriginal = $csvRow[2];
    $csvLokasiClean = normalize($csvLokasiOriginal);
    $found = false;

    foreach ($unmatchedDB as $key => $dbRow) {
        // Ambil bagian setelah '-' pada Kode_RT_RW di Database
        $parts = explode('-', $dbRow['Kode_RT_RW']);
        $dbSuffix = normalize(end($parts));

        if ($dbSuffix === $csvLokasiClean) {
            $matched[] = [
                'fid' => $dbRow['FID'],
                'db_label' => $dbRow['Kode_RT_RW'],
                'csv_label' => $csvLokasiOriginal
            ];
            unset($unmatchedDB[$key]);
            $found = true;
            break;
        }
    }

    if (!$found) {
        $newInCSV[] = $csvLokasiOriginal;
    }
}

echo "RINGKASAN:
";
echo "✅ Cocok & Siap Update : " . count($matched) . " data
";
echo "➕ Data Baru di CSV    : " . count($newInCSV) . " data (tidak ada di DB)
";
echo "⚠️ Data DB Tetap Kosong: " . count($unmatchedDB) . " data (tidak ada di CSV)

";

if (count($newInCSV) > 0) {
    echo "CONTOH DATA BARU (Akan Ditambahkan Jika Diizinkan):
";
    $i = 0;
    foreach ($newInCSV as $item) {
        echo "- $item
";
        if (++$i >= 5) break;
    }
    echo "... dan " . (count($newInCSV) - 5) . " lainnya.
";
}

if (count($unmatchedDB) > 0) {
    echo "
DATA DB YANG TIDAK MENERIMA UPDATE (Cek Manual):
";
    $i = 0;
    foreach ($unmatchedDB as $item) {
        echo "- " . $item['Kode_RT_RW'] . "
";
        if (++$i >= 5) break;
    }
}
