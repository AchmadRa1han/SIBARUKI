<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterPenghasilanToVarchar extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('rtlh_penerima', [
            'penghasilan_per_bulan' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
        ]);
    }

    public function down()
    {
        // Jika ingin dikembalikan ke INT (asumsi awal INT)
        $this->forge->modifyColumn('rtlh_penerima', [
            'penghasilan_per_bulan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
        ]);
    }
}
