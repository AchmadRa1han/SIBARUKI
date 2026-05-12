<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ImportPerumahanFormal extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:import-perumahan';
    protected $description = 'Import data Sebaran Perumahan dari CSV';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        $forge = \Config\Database::forge();

        CLI::write('Mengecek tabel perumahan_formal...', 'yellow');
        $forge->dropTable('perumahan_formal', true);
        
        $fields = [
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama_perumahan' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'luas_kawasan_ha' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => true],
            'longitude' => ['type' => 'DECIMAL', 'constraint' => '11,8', 'null' => true],
            'latitude' => ['type' => 'DECIMAL', 'constraint' => '11,8', 'null' => true],
            'pengembang' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'tahun_pembangunan' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'wkt' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ];
        
        $forge->addField($fields);
        $forge->addKey('id', true);
        $forge->createTable('perumahan_formal', true);

        $fileCsv = WRITEPATH . 'repository/SEBARAN PERUMAHAN/Sebaran Perumahan Kab.Sinjai.csv';
        if (file_exists($fileCsv)) {
            CLI::write('Mengimpor Data Perumahan...', 'yellow');
            $file = fopen($fileCsv, 'r');
            fgetcsv($file, 0, ','); // Header
            
            $count = 0;
            while (($row = fgetcsv($file, 0, ',')) !== FALSE) {
                if (empty($row[0])) continue;
                
                // Bersihkan luas (ganti koma ke titik)
                $luas = str_replace(',', '.', $row[3]);
                
                $db->table('perumahan_formal')->insert([
                    'wkt' => $row[0],
                    'nama_perumahan' => !empty($row[2]) ? $row[2] : 'Tanpa Nama',
                    'luas_kawasan_ha' => (float)$luas,
                    'longitude' => (float)$row[4],
                    'latitude' => (float)$row[5],
                    'pengembang' => !empty($row[6]) ? $row[6] : '-', // Nama_Penge
                    'tahun_pembangunan' => !empty($row[7]) ? $row[7] : '-',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
                $count++;
            }
            fclose($file);
            CLI::write("✅ Berhasil mengimpor $count data Perumahan.", 'green');
        } else {
            CLI::error('File Sebaran Perumahan Kab.Sinjai.csv tidak ditemukan!');
        }
    }
}
