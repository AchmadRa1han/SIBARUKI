<?php

namespace App\Models;

use CodeIgniter\Model;

class BansosRtlhModel extends Model
{
    protected $table            = 'rtlh_bansos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $allowedFields    = [
        'id_survei', 'nik', 'nama_penerima', 'desa', 'tahun_anggaran', 
        'sumber_dana', 'keterangan', 'lokasi_realisasi',
        'foto_setelah_depan', 'foto_setelah_samping', 'foto_setelah_dalam'
    ];
}
