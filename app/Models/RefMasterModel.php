<?php

namespace App\Models;

use CodeIgniter\Model;

class RefMasterModel extends Model
{
    protected $table            = 'ref_master';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['kategori', 'nama_pilihan'];

    // Validation
    protected $validationRules      = [
        'kategori'     => 'required|min_length[3]|max_length[50]',
        'nama_pilihan' => 'required|min_length[1]|max_length[100]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
}
