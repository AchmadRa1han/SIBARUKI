<?php

namespace App\Models;

use CodeIgniter\Model;

class PsuJalanModel extends Model
{
    protected $table            = 'psu_jalan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['wkt', 'nama_jalan', 'jalan', 'tahun', 'panjang_luas', 'foto_before', 'foto_after'];
    protected $useTimestamps    = true;
}
