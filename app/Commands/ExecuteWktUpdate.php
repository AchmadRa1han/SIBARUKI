<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ExecuteWktUpdate extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'wkt:update';
    protected $description = 'Update ONLY WKT column from output.csv to database.';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        
        // 1. Ambil Data dari Database
        $dbRecords = $db->table('wilayah_kumuh')->get()->getResultArray();

        // 2. Baca CSV
        $csvFile = ROOTPATH . 'output.csv';
        if (!file_exists($csvFile)) {
            CLI::error("File $csvFile tidak ditemukan.");
            return;
        }

        $csvData = [];
        if (($handle = fopen($csvFile, "r")) !== FALSE) {
            fgetcsv($handle, 10000, ","); // Skip header
            while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
                if (isset($data[2])) {
                    $csvData[] = $data;
                }
            }
            fclose($handle);
        }

        CLI::write("Memulai Update Koordinat (WKT)...", 'yellow');

        $updatedCount = 0;
        $unmatchedDBCount = count($dbRecords);

        foreach ($csvData as $csvRow) {
            $csvWkt = $csvRow[0]; // Kolom WKT
            $csvLokasiClean = $this->normalize($csvRow[2]);

            foreach ($dbRecords as $dbRow) {
                $parts = explode('-', $dbRow['Kode_RT_RW']);
                $dbSuffix = $this->normalize(end($parts));

                if ($dbSuffix === $csvLokasiClean) {
                    // Konversi POLYGON ke MULTIPOLYGON jika perlu
                    $finalWkt = $csvWkt;
                    if (strpos($finalWkt, 'POLYGON') === 0) {
                        $finalWkt = str_replace('POLYGON (', 'MULTIPOLYGON ((', $finalWkt) . ')';
                    }

                    // EKSEKUSI UPDATE HANYA KOLOM WKT
                    $db->table('wilayah_kumuh')
                       ->where('FID', $dbRow['FID'])
                       ->update(['WKT' => $finalWkt]);

                    $updatedCount++;
                    break;
                }
            }
        }

        CLI::write("
--- PROSES SELESAI ---", 'green');
        CLI::write("✅ Berhasil Update : $updatedCount baris (Kolom WKT saja)");
        CLI::write("ℹ️ Sisa Data DB     : " . ($unmatchedDBCount - $updatedCount) . " baris tetap menggunakan data lama.");
    }

    private function normalize($text)
    {
        $text = strtolower(trim($text));
        $text = str_replace(['_', '-', ' '], '', $text);
        return $text;
    }
}
