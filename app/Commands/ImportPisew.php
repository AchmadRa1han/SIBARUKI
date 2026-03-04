<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ImportPisew extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:import-pisew';
    protected $description = 'Import data PISEW dari CSV ke database dengan penanganan kolom dinamis';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        $forge = \Config\Database::forge();

        $fields = [
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'jenis_pekerjaan' => ['type' => 'TEXT', 'null' => true],
            'lokasi_desa' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'kecamatan' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'pelaksana' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'anggaran' => ['type' => 'DECIMAL', 'constraint' => '20,2', 'null' => true],
            'sumber_dana' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'tahun' => ['type' => 'INT', 'constraint' => 4, 'null' => true],
            'koordinat' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ];

        $forge->addField($fields);
        $forge->addKey('id', true);
        $forge->createTable('pisew', true);
        $db->table('pisew')->truncate();

        $filePath = WRITEPATH . 'repository/PISEW KAB. SINJAI 2022-2025.csv';
        $file = fopen($filePath, 'r');

        $count = 0;
        $batch = [];

        while (($row = fgetcsv($file, 0, ';')) !== FALSE) {
            if (!isset($row[0]) || !is_numeric($row[0])) continue;

            // Logika cerdas pengambilan lokasi (Kadang di index 2, kadang di index 3)
            $lokasi = !empty(trim($row[3] ?? '')) ? trim($row[3]) : trim($row[2] ?? '');
            
            // Logika cerdas pengambilan jenis pekerjaan (Bersihkan newline)
            $jenis = str_replace(["\r", "\n"], " ", trim($row[1] ?? ''));

            $anggaran = str_replace(['.', ','], ['', '.'], $row[6] ?? '0');

            $batch[] = [
                'jenis_pekerjaan' => $jenis,
                'lokasi_desa'     => $lokasi,
                'kecamatan'       => trim($row[4] ?? ''),
                'pelaksana'       => trim($row[5] ?? ''),
                'anggaran'        => is_numeric($anggaran) ? $anggaran : 0,
                'sumber_dana'     => trim($row[7] ?? ''),
                'tahun'           => (int)($row[8] ?? 0),
                'koordinat'       => null,
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ];
            $count++;
        }
        fclose($file);

        if (!empty($batch)) {
            $db->table('pisew')->insertBatch($batch);
            CLI::write("✅ Berhasil mengimpor $count data PISEW.", 'green');
        }
    }
}
