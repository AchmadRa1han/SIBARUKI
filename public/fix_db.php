<?php
// File: public/fix_db.php
// Jalankan file ini melalui browser (http://localhost:8080/fix_db.php) untuk memperbaiki database

require_once __DIR__ . '/../app/Config/Paths.php';
$paths = new \Config\Paths();

require_once $paths->systemDirectory . '/Common.php';
require_once $paths->appDirectory . '/Config/Autoload.php';
require_once $paths->appDirectory . '/Config/Services.php';

$db = \Config\Database::connect();

echo "<h2>Database Fix Utility</h2>";

try {
    $fields = [
        'foto_depan' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
        'foto_samping' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
        'foto_belakang' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
        'foto_dalam' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
    ];

    $forge = \Config\Database::forge();
    
    echo "Sedang menambahkan kolom foto ke tabel rtlh_rumah...<br>";
    
    foreach ($fields as $name => $def) {
        // Cek apakah kolom sudah ada
        if (!$db->fieldExists($name, 'rtlh_rumah')) {
            $forge->addColumn('rtlh_rumah', [$name => $def]);
            echo "✅ Kolom <b>$name</b> berhasil ditambahkan.<br>";
        } else {
            echo "ℹ️ Kolom <b>$name</b> sudah ada.<br>";
        }
    }

    echo "<br><b>Selesai!</b> Silakan coba update data RTLH kembali.<br>";
    echo "<p style='color:red'>HAPUS file ini (public/fix_db.php) setelah selesai demi keamanan.</p>";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
