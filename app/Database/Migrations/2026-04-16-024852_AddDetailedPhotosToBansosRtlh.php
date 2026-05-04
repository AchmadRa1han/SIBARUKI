<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDetailedPhotosToBansosRtlh extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('foto_setelah_depan', 'rtlh_bansos')) {
            $this->forge->addColumn('rtlh_bansos', [
                'foto_setelah_depan' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,
                    'after' => 'foto_after'
                ],
            ]);
        }

        if (!$this->db->fieldExists('foto_setelah_samping', 'rtlh_bansos')) {
            $this->forge->addColumn('rtlh_bansos', [
                'foto_setelah_samping' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,
                    'after' => 'foto_setelah_depan'
                ],
            ]);
        }

        if (!$this->db->fieldExists('foto_setelah_dalam', 'rtlh_bansos')) {
            $this->forge->addColumn('rtlh_bansos', [
                'foto_setelah_dalam' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,
                    'after' => 'foto_setelah_samping'
                ],
            ]);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('rtlh_bansos', ['foto_setelah_depan', 'foto_setelah_samping', 'foto_setelah_dalam']);
    }
}
