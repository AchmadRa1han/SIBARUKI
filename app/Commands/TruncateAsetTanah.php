<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TruncateAsetTanah extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:truncate_aset';
    protected $description = 'Mengosongkan seluruh data tabel aset_tanah dan mereset ID ke 1.';

    public function run(array $params)
    {
        CLI::write('Sedang mengosongkan tabel Aset Tanah...', 'yellow');

        $db = \Config\Database::connect();
        
        // Matikan check foreign key jika ada relasi
        $db->query("SET FOREIGN_KEY_CHECKS = 0;");
        
        // Kosongkan tabel utama
        $db->query("TRUNCATE TABLE aset_tanah");
        
        $db->query("SET FOREIGN_KEY_CHECKS = 1;");
        
        CLI::write('Berhasil! Tabel aset_tanah telah kosong dan ID telah direset ke 1.', 'green');
    }
}
