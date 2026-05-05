<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class DataPerumahan extends BaseController
{
    public function index()
    {
        if (session()->get('role_id') != 1) {
            return redirect()->to('/dashboard')->with('error', 'Hanya Admin yang dapat mengakses halaman rekapitulasi statistik.');
        }

        $db = \Config\Database::connect();
        
        // Ensure data_perumahan entries exist for all villages
        $desa = $db->table('kode_desa')->get()->getResultArray();
        foreach($desa as $d) {
            $exists = $db->table('data_perumahan')->where('desa_id', $d['desa_id'])->countAllResults();
            if ($exists == 0) {
                $db->table('data_perumahan')->insert([
                    'desa_id' => $d['desa_id'],
                    'jumlah_rumah' => 0,
                    'jumlah_rlh' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            $existsBacklog = $db->table('backlog_data')->where('desa_id', $d['desa_id'])->countAllResults();
            if ($existsBacklog == 0) {
                $db->table('backlog_data')->insert([
                    'desa_id' => $d['desa_id'],
                    'jumlah_backlog' => 0,
                    'tahun' => date('Y'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
        
        $query = "
            SELECT 
                kd.desa_id, kd.desa_nama, kk.kecamatan_nama,
                dp.id as dp_id, dp.jumlah_rumah, dp.jumlah_rlh,
                bd.id as bd_id, bd.jumlah_backlog,
                (SELECT COUNT(*) FROM rtlh_rumah rr WHERE rr.desa_id = kd.desa_id) as total_rtlh,
                (SELECT COUNT(*) FROM rtlh_rumah rr WHERE rr.desa_id = kd.desa_id AND rr.status_bantuan = 'Sudah Menerima') as rlh_auto_count
            FROM kode_desa kd
            JOIN kode_kecamatan kk ON kd.kecamatan_id = kk.kecamatan_id
            LEFT JOIN data_perumahan dp ON dp.desa_id = kd.desa_id
            LEFT JOIN backlog_data bd ON bd.desa_id = kd.desa_id
            ORDER BY kk.kecamatan_nama ASC, kd.desa_nama ASC
        ";
        
        $dataUmum = $db->query($query)->getResultArray();

        // Debug: Find unmapped bansos
        $unmappedBansos = $db->query("
            SELECT b.* FROM rtlh_bansos b
            WHERE b.id_survei IS NULL OR b.id_survei = '' OR b.id_survei = '0'
        ")->getResultArray();
        
        $unmappedList = [];
        foreach($unmappedBansos as $ub) {
            $found = false;
            $ubDesa = trim(str_replace(['DESA', 'KELURAHAN', 'KEL.', ' '], '', strtoupper($ub['desa'])));
            foreach($desa as $d) {
                $dDesa = trim(str_replace(['DESA', 'KELURAHAN', 'KEL.', ' '], '', strtoupper($d['desa_nama'])));
                if (strpos($ubDesa, $dDesa) !== false || strpos($dDesa, $ubDesa) !== false) {
                    $found = true;
                    break;
                }
            }
            if (!$found) $unmappedList[] = $ub;
        }

        return view('data_perumahan/index', [
            'title' => 'Rekapitulasi & Statistik Desa',
            'data' => $dataUmum,
            'unmappedBansos' => $unmappedList
        ]);
    }

    public function update()
    {
        $db = \Config\Database::connect();
        $post = $this->request->getPost();
        $redirectTo = $post['redirect_to'] ?? '/data-perumahan';
        
        $db->transStart();
        if (!empty($post['dp_id'])) {
            foreach ($post['dp_id'] as $idx => $id) {
                // Remove jumlah_rlh from update to prevent manual changes
                $db->table('data_perumahan')->where('id', $id)->update([
                    'jumlah_rumah' => $post['jumlah_rumah'][$idx] ?? 0,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
        }

        if (!empty($post['bd_id'])) {
            foreach ($post['bd_id'] as $idx => $id) {
                $db->table('backlog_data')->where('id', $id)->update([
                    'jumlah_backlog' => $post['jumlah_backlog'][$idx] ?? 0,
                    'tahun' => $post['tahun'][$idx] ?? date('Y'),
                    'keterangan' => $post['keterangan'][$idx] ?? '',
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
        $db->transComplete();
        
        if ($db->transStatus() === false) {
            return redirect()->to($redirectTo)->with('error', 'Gagal memperbarui data.');
        }

        return redirect()->to($redirectTo)->with('success', 'Data berhasil diperbarui.');
    }

    public function sync()
    {
        $db = \Config\Database::connect();
        $db->transStart();
        
        $desa = $db->table('kode_desa')->get()->getResultArray();
        foreach($desa as $d) {
            // 1. Total Data Lapangan (All records in rtlh_rumah)
            $totalCount = $db->table('rtlh_rumah')
                            ->where('desa_id', $d['desa_id'])
                            ->countAllResults();

            // 2. Sync RLH dari rtlh_rumah (status 'Sudah Menerima')
            $rlhSurvei = $db->table('rtlh_rumah')
                           ->where('desa_id', $d['desa_id'])
                           ->where('status_bantuan', 'Sudah Menerima')
                           ->countAllResults();
            
            // 3. Sync RLH dari rtlh_bansos yang TIDAK terhubung ke survei (id_survei NULL/Empty)
            // Dan NIK-nya tidak ada di rtlh_rumah desa tersebut (untuk menghindari double count)
            // Menggunakan fuzzy match yang sangat agresif
            $baseName = trim(str_replace(['DESA', 'KELURAHAN', 'KEL.', ' '], '', strtoupper($d['desa_nama'])));
            
            $bansosExtra = $db->query("
                SELECT COUNT(*) as total FROM rtlh_bansos b
                WHERE (
                    REPLACE(REPLACE(REPLACE(REPLACE(UPPER(b.desa), 'DESA', ''), 'KELURAHAN', ''), 'KEL.', ''), ' ', '') LIKE ? 
                    OR ? LIKE CONCAT('%', REPLACE(REPLACE(REPLACE(REPLACE(UPPER(b.desa), 'DESA', ''), 'KELURAHAN', ''), 'KEL.', ''), ' ', ''), '%')
                )
                AND (b.id_survei IS NULL OR b.id_survei = '' OR b.id_survei = '0')
                AND b.nik NOT IN (SELECT nik_pemilik FROM rtlh_rumah WHERE desa_id = ?)
            ", ['%' . $baseName . '%', $baseName, $d['desa_id']])->getRowArray()['total'] ?? 0;

            $totalRlh = $rlhSurvei + $bansosExtra;

            $db->table('data_perumahan')->where('desa_id', $d['desa_id'])->update([
                'jumlah_rumah' => $totalCount,
                'jumlah_rlh' => $totalRlh,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
        
        $db->transComplete();
        
        return redirect()->to('/data-perumahan')->with('success', 'Sinkronisasi data (Total Rumah & Capaian RLH) dari tabel RTLH dan Bansos berhasil diselesaikan.');
    }

    public function backlog()
    {
        if (session()->get('role_id') != 1) {
            return redirect()->to('/dashboard')->with('error', 'Hanya Admin yang dapat mengakses halaman manajemen backlog.');
        }

        $db = \Config\Database::connect();
        
        $query = "
            SELECT 
                kd.desa_id, kd.desa_nama, kk.kecamatan_nama,
                bd.id as bd_id, bd.jumlah_backlog, bd.tahun, bd.keterangan
            FROM kode_desa kd
            JOIN kode_kecamatan kk ON kd.kecamatan_id = kk.kecamatan_id
            LEFT JOIN backlog_data bd ON bd.desa_id = kd.desa_id
            ORDER BY kk.kecamatan_nama ASC, kd.desa_nama ASC
        ";
        
        $data = $db->query($query)->getResultArray();

        return view('data_perumahan/backlog', [
            'title' => 'Manajemen Data Backlog',
            'data' => $data
        ]);
    }
}
