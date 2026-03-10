<?php

namespace App\Models;

use CodeIgniter\Model;

class RtlhHistoryModel extends Model
{
    protected $table            = 'rtlh_history_perubahan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $allowedFields    = [
        'id_survei', 'nik', 'nama_penerima', 'sumber_bantuan', 'tahun_anggaran', 'data_sebelum', 'data_sesudah', 'keterangan'
    ];
}
