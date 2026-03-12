<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class FindNgacoTtl extends BaseCommand
{
    protected $group       = 'Debug';
    protected $name        = 'db:find-ngaco-ttl';
    protected $description = 'Mencari data TTL yang belum terpisah dengan benar';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        $rows = $db->table('rtlh_penerima')
                   ->where('tanggal_lahir IS NULL')
                   ->orWhere('tanggal_lahir', '0000-00-00')
                   ->get()->getResultArray();

        if (empty($rows)) {
            CLI::write("Semua data tanggal lahir sudah terisi.", "green");
            return;
        }

        CLI::write("Ditemukan " . count($rows) . " data bermasalah:", "yellow");
        foreach ($rows as $row) {
            CLI::write("NIK: {$row['nik']} | Nama: {$row['nama_kepala_keluarga']} | Tempat: [{$row['tempat_lahir']}]");
        }
    }
}
