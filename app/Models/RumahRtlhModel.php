<?php

namespace App\Models;

use CodeIgniter\Model;

class RumahRtlhModel extends Model
{
    protected $table            = 'rtlh_rumah';
    protected $primaryKey       = 'id_survei';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'nik_pemilik', 'desa', 'desa_id', 'alamat_detail', 'kepemilikan_rumah', 
        'aset_rumah_di_lokasi_lain', 'kepemilikan_tanah', 'sumber_penerangan', 
        'sumber_penerangan_detail', 'bantuan_perumahan', 'jenis_kawasan', 
        'fungsi_ruang', 'luas_rumah_m2', 'luas_lahan_m2', 'jumlah_penghuni_jiwa', 
        'sumber_air_minum', 'jarak_sam_ke_tpa_tinja', 'kamar_mandi_dan_jamban', 
        'jenis_jamban_kloset', 'jenis_tpa_tinja', 'lokasi_koordinat'
    ];
}
