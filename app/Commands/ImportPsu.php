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
            'nama_jalan' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'jalan' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true], // Lokasi wilayah
            'tahun' => ['type' => 'INT', 'constraint' => 4, 'null' => true],
            'panjang_luas' => ['type' => 'DOUBLE', 'null' => true],
            'foto_before' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'foto_after' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
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
            fgetcsv($file, 0, ','); // Header: WKT,nama_jalan,tahun,panjang_luas
            
            $count = 0;
            while (($row = fgetcsv($file, 0, ',')) !== FALSE) {
                if (empty($row[0])) continue;
                
                $db->table('psu_jalan')->insert([
                    'wkt'          => $row[0],
                    'nama_jalan'   => trim($row[1] ?? '-'),
                    'tahun'        => (int)($row[2] ?? date('Y')),
                    'panjang_luas' => (float)preg_replace('/[^0-9.]/', '', $row[3] ?? '0'),
                    'created_at'   => date('Y-m-d H:i:s'),
                    'updated_at'   => date('Y-m-d H:i:s'),
                ]);
                $count++;
            }
            fclose($file);
            CLI::write("✅ Berhasil mengimpor $count data Jaringan Jalan.", 'green');
        } else {
            CLI::error('File jaringan jalan.csv tidak ditemukan!');
        }
    }
}
