<?php

namespace App\Models;

use CodeIgniter\Model;

class AsetTanahModel extends Model
{
    protected $table            = 'aset_tanah';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'no_sertifikat', 'nama_pemilik', 'luas_m2', 'lokasi', 
        'desa_kelurahan', 'kecamatan', 'tgl_terbit', 'nomor_hak', 
        'peruntukan', 'koordinat', 'nilai_aset', 'status_tanah', 'keterangan'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
