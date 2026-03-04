<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class SyncRefMaster extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:sync-ref';
    protected $description = 'Sinkronisasi tabel ref_master dari File Master.csv tanpa merusak relasi data';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        
        $filePath = WRITEPATH . 'repository/File Master.csv';
        if (!file_exists($filePath)) {
            CLI::error('File Master.csv tidak ditemukan!');
            return;
        }

        CLI::write('Memulai sinkronisasi data master...', 'yellow');

        $file = fopen($filePath, 'r');
        $headers = fgetcsv($file, 0, ';');
        $headers[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $headers[0]);

        $dataMap = [];
        while (($row = fgetcsv($file, 0, ';')) !== FALSE) {
            foreach ($headers as $index => $header) {
                $val = trim($row[$index] ?? '');
                if (!empty($val)) {
                    $kategori = $this->mapHeaderToCategory($header);
                    if ($kategori) {
                        $dataMap[$kategori][] = $val;
                    }
                }
            }
        }
        fclose($file);

        $countAdded = 0;
        foreach ($dataMap as $kategori => $pilihan) {
            $uniquePilihan = array_unique($pilihan);
            foreach ($uniquePilihan as $p) {
                // Cek apakah sudah ada di DB
                $exists = $db->table('ref_master')
                            ->where('kategori', $kategori)
                            ->where('nama_pilihan', $p)
                            ->countAllResults();

                if ($exists === 0) {
                    $db->table('ref_master')->insert([
                        'kategori' => $kategori,
                        'nama_pilihan' => $p
                    ]);
                    $countAdded++;
                }
            }
        }

        CLI::write("✅ Sinkronisasi Selesai. $countAdded data baru telah ditambahkan.", 'green');
    }

    private function mapHeaderToCategory($header)
    {
        $map = [
            'PENDIDIKAN' => 'PENDIDIKAN',
            'JENIS KELAMIN' => 'JENIS_KELAMIN',
            'PEKERJAAN' => 'PEKERJAAN',
            'PENGHASILAN PERBULAN (RP)' => 'PENGHASILAN',
            'KEPEMILIKAN RUMAH' => 'KEPEMILIKAN_RUMAH',
            'ASET RUMAH DI LOKASI LAIN' => 'ASET_LAIN',
            'KEPEMILIKAN TANAH' => 'KEPEMILIKAN_TANAH',
            'ASET TANAH DI LOKASI LAIN' => 'ASET_LAIN',
            'SUMBER PENERANGAN' => 'SUMBER_PENERANGAN',
            'BANTUAN PERUMAHAN' => 'BANTUAN_PERUMAHAN',
            'JENIS KAWASAN' => 'JENIS_KAWASAN',
            'FUNGSI RUANG' => 'FUNGSI_RUANG',
            'PONDASI' => 'KONDISI',
            'KONDISI' => 'KONDISI',
            'MATERIAL LANTAI' => 'MATERIAL_LANTAI',
            'MATERIAL DINDING' => 'MATERIAL_DINDING',
            'MATERIAL ATAP' => 'MATERIAL_ATAP',
            'SUMBER AIR MINUM' => 'SUMBER_AIR_MINUM',
            'KAMAR MANDI DAN JAMBAN' => 'FASILITAS_MCK',
            'JENIS JAMBAN/KLOSET' => 'JENIS_JAMBAN',
            'JENIS TPA TINJA' => 'JENIS_TPA_TINJA'
        ];

        foreach ($map as $key => $val) {
            if (stripos($header, $key) !== false) return $val;
        }
        return null;
    }
}
