<?php

namespace App\Controllers\Api\V1;

use App\Models\RtlhPenerimaModel;
use App\Models\RumahRtlhModel;
use App\Models\KondisiRumahModel;

class RtlhApi extends BaseApiController
{
    /**
     * List RTLH dengan filter wilayah & incremental sync
     */
    public function index()
    {
        $userData = $this->getUserData();
        $rumahModel = new RumahRtlhModel();

        $desaId = $this->request->getGet('desa_id');
        $lastSync = $this->request->getGet('last_sync'); // Format: Y-m-d H:i:s

        $builder = $rumahModel->builder();
        $builder->select('rtlh_rumah.*, rtlh_penerima.nama_kepala_keluarga, rtlh_penerima.no_kk');
        $builder->join('rtlh_penerima', 'rtlh_penerima.nik = rtlh_rumah.nik_pemilik');

        // Filter Scope Wilayah
        $desaIds = $userData->desa_ids_rtlh ?? [];
        if ($userData->role_scope !== 'global' && !empty($desaIds)) {
            $builder->whereIn('rtlh_rumah.desa_id', $desaIds);
        }

        // Filter Spesifik Desa (jika diminta)
        if ($desaId) {
            $builder->where('rtlh_rumah.desa_id', $desaId);
        }

        // Incremental Sync (Hanya yang berubah setelah lastSync)
        if ($lastSync) {
            $builder->where('rtlh_rumah.updated_at >', $lastSync);
        }

        $data = $builder->get()->getResultArray();

        return $this->respondSuccess($data, 'Data RTLH berhasil ditarik');
    }

    /**
     * Detail Lengkap 1 RTLH (Penerima + Rumah + Kondisi)
     */
    public function detail($nik = null)
    {
        if (!$nik) return $this->respondError('NIK tidak valid');

        $penerimaModel = new RtlhPenerimaModel();
        $rumahModel = new RumahRtlhModel();
        $kondisiModel = new KondisiRumahModel();

        $penerima = $penerimaModel->find($nik);
        if (!$penerima) return $this->respondError('Data penerima tidak ditemukan', 404);

        $rumah = $rumahModel->where('nik_pemilik', $nik)->first();
        $kondisi = $rumah ? $kondisiModel->find($rumah['id_survei']) : null;

        $data = [
            'penerima' => $penerima,
            'rumah' => $rumah,
            'kondisi' => $kondisi
        ];

        return $this->respondSuccess($data, 'Detail RTLH berhasil ditarik');
    }

    /**
     * Sinkronisasi Batch dari Mobile
     */
    public function sync()
    {
        $db = \Config\Database::connect();
        $json = $this->request->getJSON(true);

        if (!$json || !isset($json['data'])) {
            return $this->respondError('Format data tidak valid, butuh object { "data": [...] }');
        }

        $results = [
            'success' => 0,
            'failed' => 0,
            'details' => []
        ];

        $penerimaModel = new RtlhPenerimaModel();
        $rumahModel = new RumahRtlhModel();
        $kondisiModel = new KondisiRumahModel();

        foreach ($json['data'] as $item) {
            $db->transStart();
            try {
                $nik = $item['penerima']['nik'] ?? null;
                if (!$nik) throw new \Exception('NIK Kosong');

                // 1. Upsert Penerima
                if ($penerimaModel->find($nik)) {
                    $penerimaModel->update($nik, $item['penerima']);
                } else {
                    $penerimaModel->insert($item['penerima']);
                }

                // 2. Upsert Rumah
                $existingRumah = $rumahModel->where('nik_pemilik', $nik)->first();
                $idSurvei = null;

                if ($existingRumah) {
                    $idSurvei = $existingRumah['id_survei'];
                    $rumahModel->update($idSurvei, $item['rumah']);
                } else {
                    $rumahModel->insert($item['rumah']);
                    $idSurvei = $db->insertID();
                }

                // 3. Upsert Kondisi
                if (isset($item['kondisi'])) {
                    $item['kondisi']['id_survei'] = $idSurvei;
                    if ($kondisiModel->find($idSurvei)) {
                        $kondisiModel->update($idSurvei, $item['kondisi']);
                    } else {
                        $kondisiModel->insert($item['kondisi']);
                    }
                }

                $db->transComplete();

                if ($db->transStatus() === FALSE) {
                    throw new \Exception('Database Transaction Failed');
                }

                $results['success']++;
                $results['details'][] = ['nik' => $nik, 'status' => 'success'];

            } catch (\Exception $e) {
                $db->transRollback();
                $results['failed']++;
                $results['details'][] = ['nik' => $nik ?? 'unknown', 'status' => 'failed', 'error' => $e->getMessage()];
            }
        }

        return $this->respondSuccess($results, 'Proses sinkronisasi selesai');
    }
}
