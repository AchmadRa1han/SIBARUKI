<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CheckTongke extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'wkt:check-tongke';
    protected $description = 'Check WKT status for Tongke-Tongke.';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        $row = $db->table('wilayah_kumuh')->like('Kode_RT_RW', 'Tongke-Tongke')->get()->getRowArray();

        if ($row) {
            CLI::write("DATA DITEMUKAN:", 'green');
            CLI::write("FID: " . $row['FID']);
            CLI::write("Kode_RT_RW: " . $row['Kode_RT_RW']);
            CLI::write("WKT Status: " . (empty($row['WKT']) ? "KOSONG" : "ADA (" . strlen($row['WKT']) . " karakter)"));
            if (!empty($row['WKT'])) {
                CLI::write("WKT Snippet: " . substr($row['WKT'], 0, 100) . "...");
            }
        } else {
            CLI::error("Data 'Tongke-Tongke' tidak ditemukan di database.");
        }
    }
}
