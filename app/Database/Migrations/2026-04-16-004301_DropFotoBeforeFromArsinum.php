<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropFotoBeforeFromArsinum extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('arsinum', 'foto_before');
    }

    public function down()
    {
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
