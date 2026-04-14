<?php

namespace App\Models;

use CodeIgniter\Model;

class ArsinumModel extends Model
{
    protected $table            = 'arsinum';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'jenis_pekerjaan', 'volume', 'kecamatan', 'desa', 
        'pelaksana', 'anggaran', 'sumber_dana', 'koordinat', 'tahun', 'foto_before', 'foto_after'
    ];

    protected $useTimestamps = true;
}
