<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSysLogs extends Migration
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
            'user' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'default'    => 'Admin',
            ],
            'action' => [
                'type'       => 'VARCHAR',
                'constraint' => '50', // Tambah, Ubah, Hapus
            ],
            'table_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('sys_logs');
    }

    public function down()
    {
        $this->forge->dropTable('sys_logs');
    }
}
