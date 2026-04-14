<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPhotosToBansosRtlh extends Migration
{
    public function up()
    {
        $this->forge->addColumn('rtlh_bansos', [
            'foto_before' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'lokasi_realisasi'
            ],
            'foto_after' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'foto_before'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('rtlh_bansos', ['foto_before', 'foto_after']);
    }
}
