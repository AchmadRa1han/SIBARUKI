<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class SeedDummyRtlh extends BaseCommand
{
    protected $group       = 'Custom';
    protected $name        = 'seed:dummy-rtlh';
    protected $description = 'Generate 1000 dummy RTLH records for testing.';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        
        CLI::write('Generating 1000 dummy records...', 'yellow');

        $db->transStart();
        
        for ($i = 1; $i <= 1000; $i++) {
            $nik = '99' . str_pad($i, 14, '0', STR_PAD_LEFT);
            
            // 1. Insert Penerima
            $db->table('perumahan_rtlh_penerima')->insert([
                'nik' => $nik,
                'nama_kepala_keluarga' => 'Dummy User ' . $i,
                'no_kk' => '11' . str_pad($i, 14, '0', STR_PAD_LEFT),
            ]);

            // 2. Insert Rumah
            $db->table('perumahan_rtlh_rumah')->insert([
                'nik_pemilik' => $nik,
                'desa' => 'Desa Dummy',
                'status_bantuan' => 'Rtlh',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            
            $surveiId = $db->insertID();
            
            // 3. Insert Kondisi (1:1)
            $db->table('perumahan_rtlh_kondisi')->insert([
                'id_survei' => $surveiId
            ]);

            if ($i % 100 === 0) {
                CLI::write("Created $i records...");
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            CLI::error('Failed to generate dummy data.');
        } else {
            CLI::write('Success! 1000 dummy records created.', 'green');
        }
    }
}
