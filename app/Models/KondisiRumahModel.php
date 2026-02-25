<?php

namespace App\Models;

use CodeIgniter\Model;

class KondisiRumahModel extends Model
{
    protected $table            = 'rtlh_kondisi_rumah';
    protected $primaryKey       = 'id_survei';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'id_survei', 'st_pondasi', 'st_kolom', 'st_balok', 'st_sloof', 
        'st_rangka_atap', 'st_plafon', 'st_jendela', 'st_ventilasi', 
        'mat_lantai', 'st_lantai', 'mat_dinding', 'st_dinding', 
        'mat_atap', 'st_atap'
    ];
}
