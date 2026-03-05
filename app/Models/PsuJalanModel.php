<?php

namespace App\Models;

use CodeIgniter\Model;

class PsuJalanModel extends Model
{
    protected $table            = 'psu_jalan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['wkt', 'id_csv', 'nama_jalan', 'jalan'];
    protected $useTimestamps    = true;
}
