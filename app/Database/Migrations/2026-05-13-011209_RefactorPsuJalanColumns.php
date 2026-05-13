<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RefactorPsuJalanColumns extends Migration
{
    public function up()
    {
        // 1. Rename id_csv ke tahun atau tambah jika belum ada
        if ($this->db->fieldExists('id_csv', 'psu_jalan')) {
            $this->forge->modifyColumn('psu_jalan', [
                'id_csv' => [
                    'name' => 'tahun',
                    'type' => 'INT',
                    'constraint' => 4,
                    'null' => true,
                ],
            ]);
        } else if (!$this->db->fieldExists('tahun', 'psu_jalan')) {
            $this->forge->addColumn('psu_jalan', [
                'tahun' => [
                    'type' => 'INT', 
                    'constraint' => 4, 
                    'null' => true, 
                    'after' => 'jalan'
                ]
            ]);
        }

        // 2. Tambah kolom panjang_luas (selalu gunakan addColumn untuk kolom baru)
        if (!$this->db->fieldExists('panjang_luas', 'psu_jalan')) {
            $this->forge->addColumn('psu_jalan', [
                'panjang_luas' => [
                    'type' => 'DOUBLE',
                    'null' => true,
                    'after' => 'tahun'
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('panjang_luas', 'psu_jalan')) {
            $this->forge->dropColumn('psu_jalan', 'panjang_luas');
        }
        
        if ($this->db->fieldExists('tahun', 'psu_jalan')) {
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
}
