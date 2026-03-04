<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class FixFid0 extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'wkt:fix-fid0';
    protected $description = 'Fix WKT coordinates for FID 0 using the long version from CSV.';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        $wkt = "MULTIPOLYGON (((120.263642763855 -5.12077298067045,120.263435250399 -5.12068816555131,120.263667041336 -5.12033576835011,120.263692256614 -5.12029760867614,120.263705982548 -5.12026222452874,120.263821838074 -5.12004342123838,120.263965478421 -5.11962921806437,120.264039299148 -5.11935612930501,120.264184175638 -5.1194015164797,120.264509605733 -5.11950346770029,120.264170754524 -5.12059632300355,120.264221525929 -5.12061276554123,120.2641295627 -5.12092281133009,120.264074125357 -5.12090796785737,120.263781015277 -5.12082948689722,120.263642763855 -5.12077298067045)))";

        $db->table('wilayah_kumuh')->where('FID', 0)->update(['WKT' => $wkt]);
        
        CLI::write("Berhasil: Koordinat RT01_RW01 TOKINJONG (FID 0) telah diperbarui ke versi lengkap.", 'green');
    }
}
