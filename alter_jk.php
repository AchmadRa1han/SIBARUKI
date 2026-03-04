<?php
$db = \Config\Database::connect();
$forge = \Config\Database::forge();

try {
    $forge->modifyColumn('rtlh_penerima', [
        'jenis_kelamin' => [
            'type'       => 'VARCHAR',
            'constraint' => 20,
            'null'       => true,
        ]
    ]);
    echo "Tipe data jenis_kelamin berhasil diubah ke VARCHAR.
";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "
";
}
