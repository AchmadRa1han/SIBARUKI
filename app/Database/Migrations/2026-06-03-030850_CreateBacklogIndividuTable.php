<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBacklogIndividuTable extends Migration
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
            'nik' => [
                'type'       => 'VARCHAR',
                'constraint' => '16',
                'unique'     => true,
            ],
            'no_kk' => [
                'type'       => 'VARCHAR',
                'constraint' => '16',
                'null'       => true,
            ],
            'nama_lengkap' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'desa' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'desa_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'alamat_detail' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'nama_pemilik_rumah' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'keterangan_hunian' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'tahun_data' => [
                'type'       => 'VARCHAR',
                'constraint' => '4',
                'default'    => date('Y'),
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
        $this->forge->createTable('perumahan_backlog_individu');
    }

    public function down()
    {
        $this->forge->dropTable('perumahan_backlog_individu');
    }
}
