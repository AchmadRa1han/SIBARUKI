<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class UpdatePhotoColumns extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:update-photos';
    protected $description = 'Adds foto_before and foto_after columns to arsinum, pisew, and psu_jalan';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        
        $tables = ['arsinum', 'pisew', 'psu_jalan', 'rtlh_bansos'];
        
        foreach ($tables as $table) {
            CLI::write("Updating table: $table", 'yellow');
            
            $fields = $db->getFieldNames($table);
            
            if (!in_array('foto_before', $fields)) {
                $db->query("ALTER TABLE $table ADD COLUMN foto_before VARCHAR(255) NULL");
                CLI::write("  - Added foto_before", 'green');
            } else {
                CLI::write("  - foto_before already exists", 'white');
            }
            
            if (!in_array('foto_after', $fields)) {
                $db->query("ALTER TABLE $table ADD COLUMN foto_after VARCHAR(255) NULL");
                CLI::write("  - Added foto_after", 'green');
            } else {
                CLI::write("  - foto_after already exists", 'white');
            }
        }
        
        CLI::write("Done!", 'green');
    }
}
