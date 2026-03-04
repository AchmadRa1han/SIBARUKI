<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ImportArsinum extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:import-arsinum';
    protected $description = 'Import data ARSINUM dari CSV ke database';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        $forge = \Config\Database::forge();

        CLI::write('Mengecek tabel arsinum...', 'yellow');
        $fields = [
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'jenis_pekerjaan' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'volume' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'kecamatan' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'desa' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'pelaksana' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'anggaran' => ['type' => 'DECIMAL', 'constraint' => '20,2', 'null' => true],
            'sumber_dana' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'koordinat' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'tahun' => ['type' => 'INT', 'constraint' => 4, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ];

        $forge->addField($fields);
        $forge->addKey('id', true);
        $forge->createTable('arsinum', true);
        $db->table('arsinum')->truncate();
        CLI::write('Tabel arsinum siap.', 'green');

        $filePath = WRITEPATH . 'repository/ARSINUM KAB. SINJAI 2022-2025.csv';
        if (!file_exists($filePath)) {
            CLI::error('File CSV tidak ditemukan!');
            return;
        }

        $file = fopen($filePath, 'r');
        fgetcsv($file, 0, ';'); // Skip Title
        fgetcsv($file, 0, ';'); // Skip Header

        $count = 0;
        $batch = [];

        while (($row = fgetcsv($file, 0, ';')) !== FALSE) {
            if (count($row) < 10 || empty($row[1])) continue;

            $anggaran = str_replace(['.', ','], ['', '.'], $row[7]);

            $batch[] = [
                'jenis_pekerjaan' => $row[1],
                'volume'          => $row[3],
                'kecamatan'       => $row[4],
                'desa'            => $row[5],
                'pelaksana'       => $row[6],
                'anggaran'        => is_numeric($anggaran) ? $anggaran : 0,
                'sumber_dana'     => $row[8],
                'koordinat'       => trim($row[9]),
                'tahun'           => (int)$row[10],
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ];
            $count++;
        }
        fclose($file);

        if (!empty($batch)) {
            $db->table('arsinum')->insertBatch($batch);
            CLI::write("Berhasil mengimpor $count data ARSINUM.", 'green');
        }
    }
}
