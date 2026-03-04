<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CheckNikRel extends BaseCommand
{
    protected $group       = 'Debug';
    protected $name        = 'db:check-nik';
    protected $description = 'Cek relasi NIK antar tabel RTLH';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        $penerima = $db->table('rtlh_penerima')->limit(3)->get()->getResultArray();
        $rumah = $db->table('rtlh_rumah')->limit(3)->get()->getResultArray();

        CLI::write('--- DATA PENERIMA ---');
        foreach($penerima as $p) {
            CLI::write("NIK: [" . $p['nik'] . "] | Nama: " . $p['nama_kepala_keluarga']);
        }

        CLI::write("
--- DATA RUMAH ---");
        foreach($rumah as $r) {
            CLI::write("NIK Pemilik: [" . $r['nik_pemilik'] . "]");
        }

        $matchCount = $db->table('rtlh_rumah')
                         ->join('rtlh_penerima', 'rtlh_penerima.nik = rtlh_rumah.nik_pemilik')
                         ->countAllResults();
        CLI::write("
Total Data Match: " . $matchCount, 'green');
    }
}
