<?php

namespace App\Models;

use CodeIgniter\Model;

class RtlhPenerimaModel extends Model
{
    protected $table            = 'rtlh_penerima';
    protected $primaryKey       = 'nik';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nik', 'no_kk', 'nama_kepala_keluarga', 'tempat_lahir', 
        'tanggal_lahir', 'jenis_kelamin', 'pendidikan_id', 
        'pekerjaan_id', 'penghasilan_per_bulan', 'jumlah_anggota_keluarga',
        'created_at', 'updated_at'
    ];

    // Validasi dikosongkan agar dihandle secara manual di Controller
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
}
