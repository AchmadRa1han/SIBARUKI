<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropDataPerumahanTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('data_perumahan')) {
            $this->forge->dropTable('data_perumahan');
        }
    }

    public function down()
    {
        // No turning back for this one in this context, 
        // but we could recreate it if absolutely necessary.
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
            'jumlah_rumah' => [
                'type'       => 'INT',
                'constraint' => '11',
                'default'    => 0,
            ],
            'jumlah_rlh' => [
                'type'       => 'INT',
                'constraint' => '11',
                'default'    => 0,
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
        $this->forge->createTable('data_perumahan');
    }
}
