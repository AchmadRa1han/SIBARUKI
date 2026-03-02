<?php

namespace App\Models;

use CodeIgniter\Model;

class SysLogModel extends Model
{
    protected $table            = 'sys_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['user', 'action', 'table_name', 'description', 'details', 'created_at'];

    // Dates
    protected $useTimestamps = false;
}
