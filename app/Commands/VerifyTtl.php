<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class VerifyTtl extends BaseCommand
{
    protected $group       = 'Debug';
    protected $name        = 'db:verify-ttl';
    protected $description = 'Verifikasi pemisahan TTL';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        $nik = $params[0] ?? '7307087112710055';
        $row = $db->table('rtlh_penerima')->where('nik', $nik)->get()->getRowArray();
        if ($row) {
            CLI::write("NIK: " . $row['nik']);
            CLI::write("Nama: " . $row['nama_kepala_keluarga']);
            CLI::write("Tempat Lahir: [" . $row['tempat_lahir'] . "]");
            CLI::write("Tanggal Lahir: [" . $row['tanggal_lahir'] . "]");
        } else {
            CLI::error("Data NIK $nik tidak ditemukan.");
        }
    }
}
