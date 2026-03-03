<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class FixWktData extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'wkt:fix';
    protected $description = 'Repair truncated WKT data in database by adding missing parentheses.';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        $records = $db->table('wilayah_kumuh')->get()->getResultArray();
        
        CLI::write("Mengecek integritas data WKT...", 'yellow');
        $fixed = 0;

        foreach ($records as $row) {
            $wkt = trim($row['WKT']);
            if (empty($wkt)) continue;

            // Cek apakah WKT diakhiri dengan ')'
            if (substr($wkt, -1) !== ')') {
                // Tambahkan kurung tutup yang hilang
                // Format MultiPolygon biasanya butuh )))
                $newWkt = $wkt;
                
                // Jika berakhir dengan angka atau spasi, tambahkan penutup
                if (!str_ends_with($newWkt, ')')) {
                    // Hitung berapa kurung buka yang ada
                    $openCount = substr_count($newWkt, '(');
                    $closeCount = substr_count($newWkt, ')');
                    $missing = $openCount - $closeCount;
                    
                    if ($missing > 0) {
                        $newWkt .= str_repeat(')', $missing);
                    }
                }

                $db->table('wilayah_kumuh')
                   ->where('FID', $row['FID'])
                   ->update(['WKT' => $newWkt]);
                
                $fixed++;
            }
        }

        CLI::write("Perbaikan Selesai!", 'green');
        CLI::write("Total data WKT yang diperbaiki: $fixed baris.");
    }
}
