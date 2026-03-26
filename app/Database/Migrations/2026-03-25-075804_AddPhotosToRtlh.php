<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPhotosToRtlh extends Migration
{
    public function up()
    {
        $fields = [
            'foto_depan' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'jenis_tpa_tinja'
            ],
            'foto_samping' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'foto_depan'
            ],
            'foto_belakang' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'foto_samping'
            ],
            'foto_dalam' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'foto_belakang'
            ],
        ];
        $this->forge->addColumn('rtlh_rumah', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('rtlh_rumah', ['foto_depan', 'foto_samping', 'foto_belakang', 'foto_dalam']);
    }
}
