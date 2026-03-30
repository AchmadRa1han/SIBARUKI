<?php
// Script pengetesan mandiri (Tanpa CI)
$path = __DIR__ . '/uploads/carousel/';
echo "<h3>SIBARUKI Hosting Path Tester</h3>";
echo "<b>Server Path:</b> " . $path . "<br><br>";

if (is_dir($path)) {
    echo "<span style='color:green; font-weight:bold;'>✅ FOLDER DITEMUKAN</span><br>";
    
    // Cek Izin Tulis
    if (is_writable($path)) {
        echo "<span style='color:green;'>✅ Folder bisa ditulisi (Writable)</span><br>";
    } else {
        echo "<span style='color:red;'>❌ Folder TIDAK bisa ditulisi (Permission Denied)</span><br>";
    }

    $files = scandir($path);
    echo "<b>Total file di dalam:</b> " . (count($files) - 2) . "<br>";
    echo "<b>List file:</b><pre>";
    print_r($files);
    echo "</pre>";
} else {
    echo "<span style='color:red; font-weight:bold;'>❌ FOLDER TIDAK DITEMUKAN</span><br>";
    echo "Pastikan folder 'uploads' ada di dalam folder yang sama dengan file index.php (biasanya public_html atau public).";
}
