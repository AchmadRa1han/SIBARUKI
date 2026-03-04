<?php
$db = \Config\Database::connect();
$forge = \Config\Database::forge();

try {
    // 1. Tambah kolom jenis_kelamin ke rtlh_penerima jika belum ada
    $fields = $db->getFieldNames('rtlh_penerima');
    if (!in_array('jenis_kelamin', $fields)) {
        $forge->addColumn('rtlh_penerima', [
            'jenis_kelamin' => [
                'type'       => 'ENUM',
                'constraint' => ['LAKILAKI', 'PEREMPUAN'],
                'null'       => true,
                'after'      => 'tanggal_lahir'
            ]
        ]);
        echo "Kolom jenis_kelamin berhasil ditambahkan ke rtlh_penerima.
";
    }

    // 2. Pastikan desa_id ada di rtlh_rumah
    $fieldsRumah = $db->getFieldNames('rtlh_rumah');
    if (!in_array('desa_id', $fieldsRumah)) {
        $forge->addColumn('rtlh_rumah', [
            'desa_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'after'      => 'desa'
            ]
        ]);
        echo "Kolom desa_id berhasil dipastikan ada di rtlh_rumah.
";
    }

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "
";
}
