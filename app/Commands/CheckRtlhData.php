<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CheckRtlhData extends BaseCommand
{
    protected $group       = 'Debug';
    protected $name        = 'db:check-rtlh';
    protected $description = 'Cek isi tabel RTLH';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        CLI::write('Total di rtlh_penerima: ' . $db->table('rtlh_penerima')->countAllResults());
        CLI::write('Total di rtlh_rumah: ' . $db->table('rtlh_rumah')->countAllResults());
        CLI::write('Total di rtlh_kondisi_rumah: ' . $db->table('rtlh_kondisi_rumah')->countAllResults());

        $sample = $db->table('rtlh_rumah')->select('rtlh_rumah.*, rtlh_penerima.nama_kepala_keluarga')
                    ->join('rtlh_penerima', 'rtlh_penerima.nik = rtlh_rumah.nik_pemilik', 'left')
                    ->limit(1)->get()->getRowArray();
        CLI::write("
Contoh Join Data:");
        print_r($sample);
    }
}
