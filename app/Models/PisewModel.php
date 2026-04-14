<?php

namespace App\Models;

use CodeIgniter\Model;

class PisewModel extends Model
{
    protected $table            = 'pisew';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'jenis_pekerjaan', 'lokasi_desa', 'kecamatan', 'pelaksana', 
        'anggaran', 'sumber_dana', 'tahun', 'koordinat', 'foto_before', 'foto_after'
    ];

    protected $useTimestamps = true;
}
