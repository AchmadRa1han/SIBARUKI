<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixJalanColumnTypeInPsuJalan extends Migration
{
    public function up()
    {
        if ($this->db->fieldExists('jalan', 'permukiman_psu_jalan')) {
            $this->forge->modifyColumn('permukiman_psu_jalan', [
                'jalan' => [
                    'name'       => 'jalan',
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('jalan', 'permukiman_psu_jalan')) {
            $this->forge->modifyColumn('permukiman_psu_jalan', [
                'jalan' => [
                    'name'       => 'jalan',
                    'type'       => 'DECIMAL',
                    'constraint' => '10,2',
                    'null'       => true,
                ],
            ]);
        }
    }
}
