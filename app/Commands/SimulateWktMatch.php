<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class SimulateWktMatch extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'wkt:simulate';
    protected $description = 'Simulate matching WKT data from output.csv to database.';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        
        // 1. Get DB Records
        $dbRecords = $db->table('wilayah_kumuh')->get()->getResultArray();
        $dbCount = count($dbRecords);

        // 2. Read CSV
        $csvFile = ROOTPATH . 'output.csv';
        if (!file_exists($csvFile)) {
            CLI::error("File $csvFile tidak ditemukan di " . ROOTPATH);
            return;
        }

        $csvData = [];
        if (($handle = fopen($csvFile, "r")) !== FALSE) {
            $header = fgetcsv($handle, 10000, ","); 
            while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
                if (isset($data[2])) {
                    $csvData[] = $data;
                }
            }
            fclose($handle);
        }
        $csvCount = count($csvData);

        CLI::write("--- HASIL SIMULASI PENCOCOKAN DATA ---", 'yellow');
        CLI::write("Database: $dbCount baris | CSV (output.csv): $csvCount baris");

        $matched = [];
        $unmatchedDB = $dbRecords;
        $newInCSV = [];

        foreach ($csvData as $csvRow) {
            $csvLokasiOriginal = $csvRow[2];
            $csvLokasiClean = $this->normalize($csvLokasiOriginal);
            $found = false;

            foreach ($unmatchedDB as $key => $dbRow) {
                $parts = explode('-', $dbRow['Kode_RT_RW']);
                $dbSuffix = $this->normalize(end($parts));

                if ($dbSuffix === $csvLokasiClean) {
                    $matched[] = [
                        'fid' => $dbRow['FID'],
                        'db_label' => $dbRow['Kode_RT_RW'],
                        'csv_label' => $csvLokasiOriginal
                    ];
                    unset($unmatchedDB[$key]);
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $newInCSV[] = $csvLokasiOriginal;
            }
        }

        CLI::write("
RINGKASAN:", 'white');
        CLI::write("✅ Cocok & Siap Update : " . count($matched) . " data", 'green');
        CLI::write("➕ Data Baru di CSV    : " . count($newInCSV) . " data (tidak ada di DB)", 'cyan');
        CLI::write("⚠️ Data DB Tetap Kosong: " . count($unmatchedDB) . " data (tidak ada di CSV)", 'red');

        if (count($newInCSV) > 0) {
            CLI::write("
CONTOH DATA BARU (CSV):", 'cyan');
            foreach (array_slice($newInCSV, 0, 5) as $item) {
                CLI::write("- $item");
            }
        }

        if (count($unmatchedDB) > 0) {
            CLI::write("
DATA DB TIDAK TERPASANG:", 'red');
            foreach (array_slice($unmatchedDB, 0, 5) as $item) {
                CLI::write("- " . $item['Kode_RT_RW']);
            }
        }
    }

    private function normalize($text)
    {
        $text = strtolower(trim($text));
        $text = str_replace(['_', '-', ' '], '', $text);
        return $text;
    }
}
