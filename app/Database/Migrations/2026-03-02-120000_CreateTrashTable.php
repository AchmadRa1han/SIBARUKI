<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTrashTable extends Migration
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
            'entity_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '50', // e.g., 'RTLH', 'KUMUH'
            ],
            'entity_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '100', // Original ID
            ],
            'data_json' => [
                'type' => 'LONGTEXT', // Seluruh kolom data dalam JSON
            ],
            'deleted_by' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('trash_data');
    }

    public function down()
    {
        $this->forge->dropTable('trash_data');
    }
}
