<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDetailsToLogs extends Migration
{
    public function up()
    {
        $this->forge->addColumn('sys_logs', [
            'details' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'description'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('sys_logs', 'details');
    }
}
