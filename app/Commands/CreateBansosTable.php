<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CreateBansosTable extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:create-bansos-table';
    protected $description = 'Creates the rtlh_bansos table';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        $forge = \Config\Database::forge();

        CLI::write('Creating rtlh_bansos table...', 'yellow');

        $forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_survei' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'nik' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            'nama_penerima' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'desa' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'tahun_anggaran' => [
                'type'       => 'YEAR',
            ],
            'sumber_dana' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'keterangan' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $forge->addKey('id', true);
        $forge->createTable('rtlh_bansos', true);

        CLI::write('✅ Table rtlh_bansos created successfully.', 'green');
    }
}
