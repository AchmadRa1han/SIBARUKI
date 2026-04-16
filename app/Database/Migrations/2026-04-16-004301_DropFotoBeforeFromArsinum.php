<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropFotoBeforeFromArsinum extends Migration
{
    public function up()
    {
        // Hanya hapus jika kolomnya memang ada (Robust check)
        if ($this->db->fieldExists('foto_before', 'arsinum')) {
            $this->forge->dropColumn('arsinum', 'foto_before');
        }
    }

    public function down()
    {
        if (!$this->db->fieldExists('foto_before', 'arsinum')) {
            $this->forge->addColumn('arsinum', [
                'foto_before' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,
                    'after' => 'tahun'
                ],
            ]);
        }
    }
}
