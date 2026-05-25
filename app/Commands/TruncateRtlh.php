<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TruncateRtlh extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:truncate_rtlh';
    protected $description = 'Mengosongkan tabel-tabel RTLH untuk pengujian.';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        
        CLI::write('Sedang mengosongkan tabel RTLH...', 'yellow');
        
        $db->query("SET FOREIGN_KEY_CHECKS = 0;");
        $db->query("TRUNCATE TABLE perumahan_rtlh_kondisi");
        $db->query("TRUNCATE TABLE perumahan_rtlh_rumah");
        $db->query("TRUNCATE TABLE perumahan_rtlh_penerima");
        $db->query("TRUNCATE TABLE perumahan_rtlh_bansos");
        $db->query("TRUNCATE TABLE perumahan_rtlh_history");
        $db->query("SET FOREIGN_KEY_CHECKS = 1;");
        
        CLI::write('Berhasil! Semua tabel terkait RTLH (Rumah, Kondisi, Penerima, Bansos, & Histori) telah kosong.', 'green');
    }
}
