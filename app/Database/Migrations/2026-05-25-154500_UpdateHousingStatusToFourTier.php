<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateHousingStatusToFourTier extends Migration
{
    public function up()
    {
        // 1. Ubah struktur kolom status_bantuan agar mendukung 4 status
        // Sesuai Plan: Target, Rtlh, Rlh, Unknown
        $this->forge->modifyColumn('perumahan_rtlh_rumah', [
            'status_bantuan' => [
                'type'       => 'ENUM',
                'constraint' => ['Target', 'Rtlh', 'Rlh', 'Unknown'],
                'default'    => 'Unknown',
            ],
        ]);

        // 2. Migrasi data lama agar tetap valid
        // 'Belum Menerima' -> 'Rtlh'
        // 'Sudah Menerima' -> 'Rlh'
        $this->db->query("UPDATE perumahan_rtlh_rumah SET status_bantuan = 'Rtlh' WHERE status_bantuan = 'Belum Menerima'");
        $this->db->query("UPDATE perumahan_rtlh_rumah SET status_bantuan = 'Rlh' WHERE status_bantuan = 'Sudah Menerima'");
    }

    public function down()
    {
        // Kembalikan ke struktur biner jika di-revert
        $this->forge->modifyColumn('perumahan_rtlh_rumah', [
            'status_bantuan' => [
                'type'       => 'ENUM',
                'constraint' => ['Belum Menerima', 'Sudah Menerima'],
                'default'    => 'Belum Menerima',
            ],
        ]);
    }
}
