<?php

namespace App\Models;

use CodeIgniter\Model;

class RolePermissionModel extends Model
{
    protected $table            = 'sys_role_permissions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['role_id', 'permission_id'];

    // Dates
    protected $useTimestamps = false; // We only have created_at in pivot
}
