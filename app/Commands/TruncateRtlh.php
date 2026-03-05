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
        $db->query("TRUNCATE TABLE rtlh_kondisi_rumah");
        $db->query("TRUNCATE TABLE rtlh_rumah");
        $db->query("TRUNCATE TABLE rtlh_penerima");
        $db->query("SET FOREIGN_KEY_CHECKS = 1;");
        
        CLI::write('Berhasil! Tabel rtlh_penerima, rtlh_rumah, dan rtlh_kondisi_rumah telah kosong.', 'green');
    }
}
