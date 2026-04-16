<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPhotosToBansosRtlh extends Migration
{
    public function up()
    {
        // Pastikan kolom lokasi_realisasi ada terlebih dahulu agar 'after' tidak error
        if (!$this->db->fieldExists('lokasi_realisasi', 'rtlh_bansos')) {
            $this->forge->addColumn('rtlh_bansos', [
                'lokasi_realisasi' => [
                    'type' => 'GEOMETRY',
                    'null' => true,
                    'after' => 'keterangan'
                ]
            ]);
        }

        // Cek foto_before
        if (!$this->db->fieldExists('foto_before', 'rtlh_bansos')) {
            $this->forge->addColumn('rtlh_bansos', [
                'foto_before' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,
                    'after' => 'lokasi_realisasi'
                ]
            ]);
        }

        // Cek foto_after
        if (!$this->db->fieldExists('foto_after', 'rtlh_bansos')) {
            $this->forge->addColumn('rtlh_bansos', [
                'foto_after' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,
                    'after' => 'foto_before'
                ]
            ]);
        }
    }

    public function down()
    {
        $fields = [];
        if ($this->db->fieldExists('foto_before', 'rtlh_bansos')) $fields[] = 'foto_before';
        if ($this->db->fieldExists('foto_after', 'rtlh_bansos')) $fields[] = 'foto_after';
        if ($this->db->fieldExists('lokasi_realisasi', 'rtlh_bansos')) $fields[] = 'lokasi_realisasi';
        
        if (!empty($fields)) {
            $this->forge->dropColumn('rtlh_bansos', $fields);
        }
    }
}
