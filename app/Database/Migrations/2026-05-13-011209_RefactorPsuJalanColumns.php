<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RefactorPsuJalanColumns extends Migration
{
    public function up()
    {
        $fields = [
            'id_csv' => [
                'name' => 'tahun',
                'type' => 'INT',
                'constraint' => 4,
                'null' => true,
            ],
            'panjang_luas' => [
                'type' => 'DOUBLE',
                'null' => true,
                'after' => 'tahun'
            ],
        ];
        
        // Check if id_csv exists to rename it, or just add the new columns if it's already gone
        if ($this->db->fieldExists('id_csv', 'psu_jalan')) {
            $this->forge->modifyColumn('psu_jalan', $fields);
        } else {
            // If manual SQL was run, id_csv might be gone. Add columns if missing.
            if (!$this->db->fieldExists('tahun', 'psu_jalan')) {
                $this->forge->addColumn('psu_jalan', [
                    'tahun' => ['type' => 'INT', 'constraint' => 4, 'null' => true, 'after' => 'jalan']
                ]);
            }
            if (!$this->db->fieldExists('panjang_luas', 'psu_jalan')) {
                $this->forge->addColumn('psu_jalan', [
                    'panjang_luas' => ['type' => 'DOUBLE', 'null' => true, 'after' => 'tahun']
                ]);
            }
        }
    }

    public function down()
    {
        $this->forge->dropColumn('psu_jalan', 'panjang_luas');
        $this->forge->modifyColumn('psu_jalan', [
            'tahun' => [
                'name' => 'id_csv',
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
        ]);
    }
}
