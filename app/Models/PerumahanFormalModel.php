<?php

namespace App\Models;

use CodeIgniter\Model;

class PerumahanFormalModel extends Model
{
    protected $table            = 'perumahan_formal';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['nama_perumahan', 'luas_kawasan_ha', 'longitude', 'latitude', 'pengembang', 'tahun_pembangunan', 'wkt'];
    protected $useTimestamps    = true;
}
