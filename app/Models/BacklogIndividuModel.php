<?php

namespace App\Models;

use CodeIgniter\Model;

class BacklogIndividuModel extends Model
{
    protected $table            = 'perumahan_backlog_individu';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nik', 
        'no_kk', 
        'nama_lengkap', 
        'desa', 
        'desa_id', 
        'alamat_detail', 
        'nama_pemilik_rumah', 
        'keterangan_hunian', 
        'tahun_data'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
