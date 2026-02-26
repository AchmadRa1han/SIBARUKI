<?php

namespace App\Models;

use CodeIgniter\Model;

class WilayahKumuhModel extends Model
{
    protected $table            = 'wilayah_kumuh';
    protected $primaryKey       = 'FID';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'FID', 'Provinsi', 'Kode_Prov', 'Kab_Kota', 'Kode_Kab', 
        'Kecamatan', 'Kode_Kec', 'Kelurahan', 'desa_id', 
        'Kode_Kel', 'Kode_RT_RW', 'Luas_kumuh', 'skor_kumuh', 
        'Sumber_data', 'Sk_Kumuh', 'Kawasan', 'WKT'
    ];
}
