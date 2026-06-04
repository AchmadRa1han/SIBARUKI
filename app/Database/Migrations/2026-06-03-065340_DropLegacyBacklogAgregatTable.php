<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropLegacyBacklogAgregatTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('perumahan_backlog_agregat')) {
            $this->forge->dropTable('perumahan_backlog_agregat');
        }
    }

    public function down()
    {
        // No turning back for legacy tables usually, but we could recreate it if needed.
        // For now, leave it empty as we transition fully to Individu.
    }
}
