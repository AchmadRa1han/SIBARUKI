<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ImportAsetTanah extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:import-aset';
    protected $description = 'Import data aset tanah dari CSV ke database';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        $forge = \Config\Database::forge();

        // 1. Create Table if not exists
        CLI::write('Mengecek tabel aset_tanah...', 'yellow');
        $fields = [
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'no_sertifikat' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'nama_pemilik' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'luas_m2' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'null' => true],
            'lokasi' => ['type' => 'TEXT', 'null' => true],
            'desa_kelurahan' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'kecamatan' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'tgl_terbit' => ['type' => 'DATE', 'null' => true],
            'nomor_hak' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'peruntukan' => ['type' => 'TEXT', 'null' => true],
            'koordinat' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'nilai_aset' => ['type' => 'DECIMAL', 'constraint' => '20,2', 'null' => true],
            'status_tanah' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'keterangan' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ];

        $forge->addField($fields);
        $forge->addKey('id', true);
        $forge->createTable('aset_tanah', true);
        
        // Kosongkan tabel sebelum impor ulang
        $db->table('aset_tanah')->truncate();
        
        CLI::write('Tabel aset_tanah siap (dikosongkan).', 'green');

        // 2. Read CSV
        $filePath = WRITEPATH . 'repository/Data Aset Tanah Pemda yg telah dan belum disertifikatkan.csv';
        if (!file_exists($filePath)) {
            CLI::error('File CSV tidak ditemukan di: ' . $filePath);
            return;
        }

        CLI::write('Membaca file CSV...', 'yellow');
        $file = fopen($filePath, 'r');
        
        // Skip first 2 lines (Title and Header)
        fgetcsv($file, 0, ';');
        fgetcsv($file, 0, ';');

        $count = 0;
        $batch = [];

        while (($row = fgetcsv($file, 0, ';')) !== FALSE) {
            if (count($row) < 15 || empty($row[1])) continue;

            // Data Cleaning
            $luas = str_replace(['.', ','], ['', '.'], $row[3]);
            $nilai = str_replace(['.', ','], ['', '.'], $row[12]);
            
            // Force string format for Certificate Numbers
            $no_sertifikat = trim((string)$row[1]);
            $nomor_hak = trim((string)$row[8]);

            // Date Format Conversion (d-m-Y to Y-m-d)
            $tgl = null;
            if (!empty($row[7])) {
                $d = \DateTime::createFromFormat('d-m-Y', trim($row[7]));
                if ($d) $tgl = $d->format('Y-m-d');
            }

            // Coordinate Cleaning
            $lat = trim($row[11]);
            $lng = trim($row[10]);
            
            // Perbaikan Latitude (Sering bermasalah di CSV)
            if ($lat) {
                // Hapus semua titik dan koma terlebih dahulu
                $cleanLat = str_replace(['.', ','], '', $lat);
                
                // Jika koordinat negatif (Sinjai), pastikan ada titik setelah angka 5
                if (strpos($cleanLat, '-') === 0) {
                    // Contoh: -5299120 -> -5.299120
                    if (strlen($cleanLat) > 2 && $cleanLat[1] === '5') {
                        $lat = '-5.' . substr($cleanLat, 2);
                    }
                } else {
                    // Contoh: 5299120 -> 5.299120
                    if (strlen($cleanLat) > 1 && $cleanLat[0] === '5') {
                        $lat = '5.' . substr($cleanLat, 1);
                    }
                }
                $lat = rtrim($lat, '.'); // Hapus titik di akhir jika ada
            }

            // Perbaikan Longitude
            if ($lng) {
                $cleanLng = str_replace(['.', ','], '', $lng);
                // Longitude Sinjai sekitar 120.x
                if (strlen($cleanLng) > 3 && substr($cleanLng, 0, 3) === '120') {
                    $lng = '120.' . substr($cleanLng, 3);
                }
            }

            $koordinat = ($lat && $lng) ? "$lat, $lng" : null;

            $batch[] = [
                'no_sertifikat'  => $no_sertifikat,
                'nama_pemilik'   => $row[2],
                'luas_m2'        => is_numeric($luas) ? $luas : 0,
                'lokasi'         => $row[4],
                'desa_kelurahan' => $row[5],
                'kecamatan'      => $row[6],
                'tgl_terbit'     => $tgl,
                'nomor_hak'      => $nomor_hak,
                'peruntukan'     => $row[9],
                'koordinat'      => $koordinat,
                'nilai_aset'     => is_numeric($nilai) ? $nilai : 0,
                'status_tanah'   => $row[13],
                'keterangan'     => $row[14],
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ];
            $count++;
        }
        fclose($file);

        if (!empty($batch)) {
            $db->table('aset_tanah')->insertBatch($batch);
            CLI::write("Berhasil mengimpor $count data aset tanah.", 'green');
        } else {
            CLI::error('Tidak ada data yang diimpor.');
        }
    }
}
