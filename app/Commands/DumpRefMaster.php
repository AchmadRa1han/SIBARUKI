<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class DumpRefMaster extends BaseCommand
{
    protected $group       = 'Debug';
    protected $name        = 'db:dump-ref';
    protected $description = 'Dump ref_master table content';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        $results = $db->table('ref_master')->select('kategori, nama_pilihan')->orderBy('kategori', 'ASC')->get()->getResultArray();

        $current_data = [];
        foreach ($results as $row) {
            $current_data[$row['kategori']][] = $row['nama_pilihan'];
        }

        CLI::write(json_encode($current_data, JSON_PRETTY_PRINT));
    }
}
