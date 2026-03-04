<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TotalReimportKumuh extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'kumuh:reset-import';
    protected $description = 'Clean and re-import all slum area data with Auto-ID and NA-Cleaning.';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        $csvPath = WRITEPATH . 'repository/SHP Verifikasi Kumuh_Kab_Sinjai/SHP Verifikasi Kumuh_Kab_Sinjai/Delineasi_Kumuh_AR.csv';
        
        if (!file_exists($csvPath)) {
            CLI::error("File CSV tidak ditemukan.");
            return;
        }

        $confirmation = CLI::prompt("Reset database dan impor ulang dengan pembersihan data?", ['y', 'n']);
        if ($confirmation !== 'y') return;

        // 1. Kosongkan tabel
        $db->table('wilayah_kumuh')->emptyTable();
        
        // 2. Izinkan angka 0 masuk ke kolom Auto Increment
        $db->query("SET SESSION sql_mode = CONCAT(@@sql_mode, ',NO_AUTO_VALUE_ON_ZERO')");
        
        // 3. Reset urutan awal
        $db->query("ALTER TABLE wilayah_kumuh AUTO_INCREMENT = 0");
        
        CLI::write("Database dikosongkan dan mode ID 0 diaktifkan.", 'yellow');

        $handle = fopen($csvPath, "r");
        fgetcsv($handle, 0, ","); // Skip Header
        
        $successCount = 0;
        $rowCounter = 1;

        while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
            $rowCounter++;
            if (count($data) < 15) continue;

            // 1. Bersihkan Koordinat dari teks "NA"
            $wktRaw = str_replace('NA', '', $data[0]);
            
            // 2. Konversi ke MultiPolygon
            $finalWkt = $wktRaw;
            if (strpos(strtoupper($finalWkt), 'POLYGON') === 0 && strpos(strtoupper($finalWkt), 'MULTIPOLYGON') === false) {
                $finalWkt = "MULTIPOLYGON (((" . str_replace("POLYGON ((", "", rtrim($finalWkt, ")")) . ")))";
            }

            // 3. Lookup Desa
            $kelurahanName = trim($data[7]);
            $desa = $db->table('kode_desa')->like('desa_nama', $kelurahanName)->get()->getRow();
            $desaId = $desa ? $desa->desa_id : null;

            $insertData = [
                'FID'         => $successCount, // PAKSA MULAI DARI 0, 1, 2...
                'Provinsi'    => $data[1],
                'Kode_Prov'   => $data[2],
                'Kab_Kota'    => $data[3],
                'Kode_Kab'    => $data[4],
                'Kecamatan'   => $data[5],
                'Kode_Kec'    => $data[6],
                'Kelurahan'   => $data[7],
                'desa_id'     => $desaId,
                'Kode_Kel'    => $data[8],
                'Kode_RT_RW'  => $data[9],
                'Luas_kumuh'  => (double)$data[10],
                'skor_kumuh'  => (double)$data[11],
                'Sumber_data' => $data[12],
                'Sk_Kumuh'    => $data[13],
                'Kawasan'     => $data[14],
                'WKT'         => $finalWkt
            ];

            if ($db->table('wilayah_kumuh')->insert($insertData)) {
                $successCount++;
            }
        }

        fclose($handle);
        CLI::write("\n--- SELESAI ---", 'green');
        CLI::write("✅ Berhasil Diimpor: $successCount baris data murni.");
    }
}
