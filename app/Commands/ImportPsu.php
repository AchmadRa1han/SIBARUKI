<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ImportPsu extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:import-psu';
    protected $description = 'Import data Jaringan Jalan (PSU) dari CSV';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        $forge = \Config\Database::forge();

        CLI::write('Mereset tabel psu_jalan agar sesuai CSV...', 'yellow');
        
        // Hapus tabel lama untuk sinkronisasi total
        $forge->dropTable('psu_jalan', true);
        
        $fieldsJalan = [
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'wkt' => ['type' => 'TEXT', 'null' => true],
            'id_csv' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'nama_jalan' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'jalan' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ];
        
        $forge->addField($fieldsJalan);
        $forge->addKey('id', true);
        $forge->createTable('psu_jalan', true);

        // 2. Import Jaringan Jalan
        $fileJalan = WRITEPATH . 'repository/PSU/jaringan jalan.csv';
        if (file_exists($fileJalan)) {
            CLI::write('Mengimpor Jaringan Jalan...', 'yellow');
            $file = fopen($fileJalan, 'r');
            fgetcsv($file, 0, ','); // Header: WKT,Id,nama_jalan,jalan
            
            $count = 0;
            while (($row = fgetcsv($file, 0, ',')) !== FALSE) {
                if (empty($row[0])) continue;
                
                $db->table('psu_jalan')->insert([
                    'wkt' => $row[0],
                    'id_csv' => (int)$row[1],
                    'nama_jalan' => !empty($row[2]) ? $row[2] : null,
                    'jalan' => (float)$row[3],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
                $count++;
            }
            fclose($file);
            CLI::write("✅ Berhasil mengimpor $count data Jaringan Jalan sesuai kolom CSV.", 'green');
        } else {
            CLI::error('File jaringan jalan.csv tidak ditemukan!');
        }
    }
}
