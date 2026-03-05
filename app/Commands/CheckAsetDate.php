<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CheckAsetDate extends BaseCommand
{
    protected $group       = 'Debug';
    protected $name        = 'db:check-aset-date';
    protected $description = 'Cek statistik tanggal terbit aset tanah';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        $total = $db->table('aset_tanah')->countAllResults();
        $nullCount = $db->table('aset_tanah')->where('tgl_terbit IS NULL')->countAllResults();
        $emptyCount = $db->table('aset_tanah')->where('tgl_terbit', '0000-00-00')->countAllResults();

        CLI::write("Total Aset: $total");
        CLI::write("Tanggal NULL: $nullCount");
        CLI::write("Tanggal 0000-00-00: $emptyCount");

        $sample = $db->table('aset_tanah')->where('tgl_terbit IS NOT NULL')->limit(3)->get()->getResultArray();
        CLI::write("
Contoh Data Ber-Tanggal:");
        foreach($sample as $s) {
            CLI::write("ID: " . $s['id'] . " | Tgl: " . $s['tgl_terbit']);
        }
    }
}
