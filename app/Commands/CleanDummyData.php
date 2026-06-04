<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CleanDummyData extends BaseCommand
{
    protected $group       = 'Custom';
    protected $name        = 'clean:dummy';
    protected $description = 'Delete all dummy data and reset backlog statistics.';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        
        CLI::write('Cleaning dummy data...', 'yellow');

        $db->query('SET FOREIGN_KEY_CHECKS = 0');
        
        try {
            // 1. Delete dummy RTLH (NIK starting with 99)
            // Get id_survei first
            $dummySurveiIds = $db->table('perumahan_rtlh_rumah')
                                 ->select('id_survei')
                                 ->where('nik_pemilik LIKE', '99%')
                                 ->get()->getResultArray();
            
            $ids = array_column($dummySurveiIds, 'id_survei');
            
            if (!empty($ids)) {
                $db->table('perumahan_rtlh_kondisi')->whereIn('id_survei', $ids)->delete();
                $db->table('perumahan_rtlh_rumah')->whereIn('id_survei', $ids)->delete();
            }
            
            $db->table('perumahan_rtlh_penerima')->where('nik LIKE', '99%')->delete();

            // 2. Truncate Backlog Individu
            $db->table('perumahan_backlog_individu')->truncate();

            // 3. Reset Aggregate Backlog (Removed as table is now legacy)
            // CLI::write('Skipping legacy aggregate backlog reset...', 'gray');

            CLI::write('Success! All dummy data removed and statistics reset.', 'green');
        } catch (\Exception $e) {
            CLI::error('Failed to clean dummy data: ' . $e->getMessage());
        } finally {
            $db->query('SET FOREIGN_KEY_CHECKS = 1');
        }
    }
}
