<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateMonitoringFeatures extends Migration
{
    public function up()
    {
        // Tambah kolom user_agent di logs
        $this->forge->addColumn('sys_logs', [
            'user_agent' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
                'after' => 'details'
            ]
        ]);

        // Tambah kolom last_active di users
        $this->forge->addColumn('users', [
            'last_active' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'role_id'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('sys_logs', 'user_agent');
        $this->forge->dropColumn('users', 'last_active');
    }
}
