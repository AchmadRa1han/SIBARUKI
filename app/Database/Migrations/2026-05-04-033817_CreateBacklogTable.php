<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBacklogTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'desa_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'jumlah_backlog' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'tahun' => [
                'type'       => 'VARCHAR',
                'constraint' => '4',
                'default'    => date('Y'),
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
        $this->forge->addKey('id', true);
        $this->forge->addKey('desa_id');
        $this->forge->createTable('backlog_data');

        // Drop column from data_perumahan if exists
        if ($this->db->fieldExists('jumlah_backlog', 'data_perumahan')) {
            $this->forge->dropColumn('data_perumahan', 'jumlah_backlog');
        }
    }

    public function down()
    {
        $this->forge->dropTable('backlog_data');
        if (!$this->db->fieldExists('jumlah_backlog', 'data_perumahan')) {
            $this->forge->addColumn('data_perumahan', [
                'jumlah_backlog' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'default'    => 0,
                ],
            ]);
        }
    }
}
