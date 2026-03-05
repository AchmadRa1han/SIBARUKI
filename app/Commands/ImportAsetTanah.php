<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ImportAsetTanah extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:import-aset';
    protected $description = 'Import data aset tanah dari CSV ke database dengan penanganan format tanggal fleksibel';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        
        // Kosongkan tabel sebelum impor ulang
        $db->table('aset_tanah')->truncate();
        
        CLI::write('Tabel aset_tanah dikosongkan.', 'yellow');

        $filePath = WRITEPATH . 'repository/Data Aset Tanah Pemda yg telah dan belum disertifikatkan.csv';
        if (!file_exists($filePath)) {
            CLI::error('File CSV tidak ditemukan!');
            return;
        }

        $file = fopen($filePath, 'r');
        fgetcsv($file, 0, ';'); // Judul
        fgetcsv($file, 0, ';'); // Header

        $count = 0;
        $batch = [];

        while (($row = fgetcsv($file, 0, ';')) !== FALSE) {
            if (count($row) < 15 || empty($row[1]) || !is_numeric($row[0])) continue;

            // 1. Tanggal Terbit Sertifikat (Kolom 7)
            $rawDate = trim($row[7]);
            $tgl = null;
            if (!empty($rawDate)) {
                // Bersihkan spasi
                $rawDate = str_replace(' ', '', $rawDate);
                
                // Coba berbagai format
                $formats = ['d-m-Y', 'd/m/Y', 'Y-m-d', 'Y/m/d'];
                foreach ($formats as $f) {
                    $d = \DateTime::createFromFormat($f, $rawDate);
                    if ($d) {
                        $tgl = $d->format('Y-m-d');
                        break;
                    }
                }

                // Handle format m/Y (misal: 03/2025)
                if (!$tgl && preg_match('/^(\d{1,2})[\/\-](\d{4})$/', $rawDate, $matches)) {
                    $tgl = $matches[2] . '-' . str_pad($matches[1], 2, '0', STR_PAD_LEFT) . '-01';
                }
            }

            // 2. Luas dan Nilai
            $luas = (float)str_replace(['.', ','], ['', '.'], trim($row[3]));
            $nilai = (float)str_replace(['.', ','], ['', '.'], trim($row[12]));

            // 3. Koordinat (Kolom 10 Long, 11 Lat)
            $lng = str_replace(['.', ','], ['', '.'], trim($row[10]));
            $lat = str_replace(['.', ','], ['', '.'], trim($row[11]));
            
            // Fix Koordinat Sinjai (-5.x , 120.x)
            if (!empty($lat)) {
                $cleanLat = str_replace('.', '', $lat);
                if (strpos($cleanLat, '-') === 0) {
                    $lat = substr($cleanLat, 0, 2) . '.' . substr($cleanLat, 2);
                } else {
                    $lat = '-' . $cleanLat[0] . '.' . substr($cleanLat, 1);
                }
            }
            if (!empty($lng)) {
                $cleanLng = str_replace('.', '', $lng);
                $lng = substr($cleanLng, 0, 3) . '.' . substr($cleanLng, 3);
            }
            $koordinat = ($lat && $lng) ? "$lat, $lng" : null;

            $batch[] = [
                'no_sertifikat'  => trim($row[1]),
                'nama_pemilik'   => trim($row[2]),
                'luas_m2'        => $luas,
                'lokasi'         => trim($row[4]),
                'desa_kelurahan' => trim($row[5]),
                'kecamatan'      => trim($row[6]),
                'tgl_terbit'     => $tgl,
                'nomor_hak'      => trim($row[8]),
                'peruntukan'     => trim($row[9]),
                'koordinat'      => $koordinat,
                'nilai_aset'     => $nilai,
                'status_tanah'   => trim($row[13]),
                'keterangan'     => trim($row[14]),
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ];
            $count++;
        }
        fclose($file);

        if (!empty($batch)) {
            $db->table('aset_tanah')->insertBatch($batch);
            CLI::write("Berhasil mengimpor $count data aset tanah.", 'green');
        }
    }
}
