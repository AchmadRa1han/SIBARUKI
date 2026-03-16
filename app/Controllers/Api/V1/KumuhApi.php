<?php

namespace App\Controllers\Api\V1;

use App\Models\WilayahKumuhModel;

class KumuhApi extends BaseApiController
{
    /**
     * Helper untuk memparsing WKT menjadi koordinat array lat-lng
     * Format WKT: POLYGON((lng lat, lng lat, ...))
     */
    private function extractCoordinates($wkt)
    {
        if (empty($wkt)) return null;

        // Mendeteksi apakah ini POLYGON atau POINT
        if (strpos($wkt, 'POLYGON') !== false) {
            preg_match_all('/([-+]?\d+\.\d+)\s+([-+]?\d+\.\d+)/', $wkt, $matches);
            $coords = [];
            for ($i = 0; $i < count($matches[0]); $i++) {
                $coords[] = [(float)$matches[2][$i], (float)$matches[1][$i]]; // [lat, lng]
            }
            return $coords;
        } elseif (strpos($wkt, 'POINT') !== false) {
            preg_match('/POINT\(([-+]?\d+\.\d+)\s+([-+]?\d+\.\d+)\)/', $wkt, $matches);
            if (isset($matches[1]) && isset($matches[2])) {
                return [(float)$matches[2], (float)$matches[1]]; // [lat, lng]
            }
        }
        
        return null;
    }

    public function index()
    {
        $userData = $this->getUserData();
        $model = new WilayahKumuhModel();
        
        $builder = $model->builder();
        
        // Filter Scope Wilayah
        $desaIds = $userData->desa_ids_kumuh ?? [];
        if ($userData->role_scope !== 'global' && !empty($desaIds)) {
            $builder->whereIn('desa_id', $desaIds);
        }

        $data = $builder->get()->getResultArray();

        // Tambahkan parsing koordinat GeoJSON-style untuk memudahkan Mobile
        foreach ($data as &$item) {
            $item['geo_coords'] = $this->extractCoordinates($item['delineasi_lokasi'] ?? '');
        }

        return $this->respondSuccess($data, 'Data wilayah kumuh berhasil diambil');
    }

    public function show($id = null)
    {
        $model = new WilayahKumuhModel();
        $data = $model->find($id);

        if (!$data) return $this->respondError('Data tidak ditemukan', 404);

        $data['geo_coords'] = $this->extractCoordinates($data['delineasi_lokasi'] ?? '');

        return $this->respondSuccess($data, 'Detail wilayah kumuh berhasil diambil');
    }

    public function create()
    {
        $model = new WilayahKumuhModel();
        $data = $this->request->getJSON(true);

        if ($model->insert($data)) {
            $data['fid'] = $model->insertID();
            return $this->respondSuccess($data, 'Data wilayah kumuh ditambahkan', 201);
        }

        return $this->respondError($model->errors() ?: 'Gagal menambah data');
    }

    public function update($id = null)
    {
        $model = new WilayahKumuhModel();
        $data = $this->request->getJSON(true);

        if ($model->update($id, $data)) {
            return $this->respondSuccess($data, 'Data wilayah kumuh diperbarui');
        }

        return $this->respondError($model->errors() ?: 'Gagal memperbarui data');
    }

    public function delete($id = null)
    {
        $model = new WilayahKumuhModel();
        if ($model->delete($id)) {
            return $this->respondSuccess(null, 'Data wilayah kumuh dihapus');
        }
        return $this->respondError('Gagal menghapus data');
    }
}
