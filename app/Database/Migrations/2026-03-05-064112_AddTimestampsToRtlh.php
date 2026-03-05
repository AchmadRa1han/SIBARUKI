<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTimestampsToRtlh extends Migration
{
    protected $tables = ['rtlh_rumah', 'rtlh_penerima', 'rtlh_kondisi_rumah'];

    public function up()
    {
        foreach ($this->tables as $table) {
            $fields = [
                'created_at' => [
                    'type'    => 'DATETIME',
                    'null'    => true,
                ],
                'updated_at' => [
                    'type'    => 'DATETIME',
                    'null'    => true,
                ],
            ];
            $this->forge->addColumn($table, $fields);
            
            // Set default values for existing rows using raw queries
            $this->db->query("UPDATE $table SET created_at = NOW(), updated_at = NOW() WHERE created_at IS NULL");
        }
    }

    public function down()
    {
        foreach ($this->tables as $table) {
            $this->forge->dropColumn($table, ['created_at', 'updated_at']);
        }
    }
}
