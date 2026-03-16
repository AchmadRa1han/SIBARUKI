<?php

namespace App\Controllers\Api\V1;

use App\Models\PsuJalanModel;
use App\Models\PisewModel;
use App\Models\ArsinumModel;
use App\Models\PerumahanFormalModel;
use App\Models\AsetTanahModel;

class InfrastrukturApi extends BaseApiController
{
    /**
     * Helper untuk mendapatkan model berdasarkan nama modul
     */
    private function getModel($module)
    {
        switch ($module) {
            case 'psu': return new PsuJalanModel();
            case 'pisew': return new PisewModel();
            case 'arsinum': return new ArsinumModel();
            case 'perumahan-formal': return new PerumahanFormalModel();
            case 'aset-tanah': return new AsetTanahModel();
            default: return null;
        }
    }

    /**
     * GET /api/v1/infrastruktur/{modul}
     */
    public function index($module = null)
    {
        $model = $this->getModel($module);
        if (!$model) return $this->respondError('Modul tidak valid');

        $data = $model->findAll();
        return $this->respondSuccess($data, "Daftar data $module berhasil diambil");
    }

    /**
     * GET /api/v1/infrastruktur/{modul}/{id}
     */
    public function show($module = null, $id = null)
    {
        $model = $this->getModel($module);
        if (!$model || !$id) return $this->respondError('Modul atau ID tidak valid');

        $data = $model->find($id);
        if (!$data) return $this->respondError("Data $module tidak ditemukan", 404);

        return $this->respondSuccess($data, "Detail data $module berhasil diambil");
    }

    /**
     * POST /api/v1/infrastruktur/{modul}
     */
    public function create($module = null)
    {
        $model = $this->getModel($module);
        if (!$model) return $this->respondError('Modul tidak valid');

        $data = $this->request->getJSON(true);
        if (!$data) return $this->respondError('Data tidak boleh kosong');

        if ($model->insert($data)) {
            $data['id'] = $model->insertID();
            return $this->respondSuccess($data, "Data $module berhasil ditambahkan", 201);
        }

        return $this->respondError($model->errors() ?: "Gagal menambah data $module");
    }

    /**
     * PUT /api/v1/infrastruktur/{modul}/{id}
     */
    public function update($module = null, $id = null)
    {
        $model = $this->getModel($module);
        if (!$model || !$id) return $this->respondError('Modul atau ID tidak valid');

        $data = $this->request->getJSON(true);
        if (!$data) return $this->respondError('Data tidak boleh kosong');

        if ($model->update($id, $data)) {
            return $this->respondSuccess($data, "Data $module berhasil diperbarui");
        }

        return $this->respondError($model->errors() ?: "Gagal memperbarui data $module");
    }

    /**
     * DELETE /api/v1/infrastruktur/{modul}/{id}
     */
    public function delete($module = null, $id = null)
    {
        $model = $this->getModel($module);
        if (!$model || !$id) return $this->respondError('Modul atau ID tidak valid');

        if ($model->delete($id)) {
            return $this->respondSuccess(null, "Data $module berhasil dihapus");
        }

        return $this->respondError("Gagal menghapus data $module");
    }
}
