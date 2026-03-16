<?php

namespace App\Controllers\Api\V1;

use App\Models\RefMasterModel;

class MasterApi extends BaseApiController
{
    /**
     * Mengambil semua data referensi untuk dropdown mobile
     */
    public function referensi()
    {
        $model = new RefMasterModel();
        $data = $model->findAll();
        
        // Kelompokkan berdasarkan kategori agar lebih mudah dikonsumsi mobile
        $grouped = [];
        foreach ($data as $item) {
            $grouped[$item['category']][] = [
                'id' => $item['id'],
                'name' => $item['name']
            ];
        }

        return $this->respondSuccess($grouped, 'Data referensi berhasil diambil');
    }

    /**
     * Mengambil daftar desa berdasarkan scope user (RTLH/Kumuh)
     */
    public function desa()
    {
        $db = \Config\Database::connect();
        $userData = $this->getUserData();
        
        // Ambil kategori dari query param (default rtlh)
        $category = $this->request->getGet('category') ?: 'rtlh';
        $desaIds = ($category === 'rtlh') ? ($userData->desa_ids_rtlh ?? []) : ($userData->desa_ids_kumuh ?? []);

        $builder = $db->table('ref_desa_kelurahan d');
        $builder->select('d.id, d.nama_desa, k.nama_kecamatan');
        $builder->join('ref_kecamatan k', 'k.id = d.kecamatan_id');

        // Jika user memiliki scope lokal (bukan global admin), filter berdasarkan desa yang diizinkan
        if ($userData->role_scope !== 'global' && !empty($desaIds)) {
            $builder->whereIn('d.id', $desaIds);
        }

        $data = $builder->get()->getResultArray();

        return $this->respondSuccess($data, 'Daftar desa berhasil diambil');
    }
}
