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
        CLI::write('Total di rtlh_penerima: ' . $db->table('perumahan_rtlh_penerima')->countAllResults());
        CLI::write('Total di rtlh_rumah: ' . $db->table('perumahan_rtlh_rumah')->countAllResults());
        CLI::write('Total di rtlh_kondisi_rumah: ' . $db->table('perumahan_rtlh_kondisi')->countAllResults());

        $sample = $db->table('perumahan_rtlh_rumah')->select('perumahan_rtlh_rumah.*, perumahan_rtlh_penerima.nama_kepala_keluarga')
                    ->join('perumahan_rtlh_penerima', 'perumahan_rtlh_penerima.nik = perumahan_rtlh_rumah.nik_pemilik', 'left')
                    ->limit(1)->get()->getRowArray();
        CLI::write("
Contoh Join Data:");
        print_r($sample);
    }
}
