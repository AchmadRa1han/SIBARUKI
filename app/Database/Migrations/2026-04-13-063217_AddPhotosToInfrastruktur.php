<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPhotosToInfrastruktur extends Migration
{
    public function up()
    {
        // Add photo column to arsinum
        $this->forge->addColumn('arsinum', [
            'foto' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'tahun'
            ],
        ]);

        // Add photo column to pisew
        $this->forge->addColumn('pisew', [
            'foto' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'koordinat'
            ],
        ]);

        // Add before/after photos to psu_jalan
        $this->forge->addColumn('psu_jalan', [
            'foto_before' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'jalan'
            ],
            'foto_after' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'foto_before'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('arsinum', 'foto');
        $this->forge->dropColumn('pisew', 'foto');
        $this->forge->dropColumn('psu_jalan', ['foto_before', 'foto_after']);
    }
}
