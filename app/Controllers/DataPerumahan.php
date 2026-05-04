<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class DataPerumahan extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $db->query("CREATE TABLE IF NOT EXISTS `data_perumahan` (
          `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `desa_id` varchar(50) DEFAULT NULL,
          `jumlah_rumah` int(11) DEFAULT 0,
          `jumlah_rlh` int(11) DEFAULT 0,
          `jumlah_backlog` int(11) DEFAULT 0,
          `created_at` datetime DEFAULT NULL,
          `updated_at` datetime DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        
        $desa = $db->table('kode_desa')->get()->getResultArray();
        
        // Initial insert if empty
        if ($db->table('data_perumahan')->countAllResults() == 0) {
            foreach($desa as $d) {
                $db->table('data_perumahan')->insert([
                    'desa_id' => $d['desa_id'],
                    'jumlah_rumah' => 0,
                    'jumlah_rlh' => 0,
                    'jumlah_backlog' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
        
        $dataUmum = $db->table('data_perumahan')
                       ->select('data_perumahan.*, kode_desa.desa_nama, kode_kecamatan.kecamatan_nama')
                       ->join('kode_desa', 'kode_desa.desa_id = data_perumahan.desa_id')
                       ->join('kode_kecamatan', 'kode_kecamatan.kecamatan_id = kode_desa.kecamatan_id')
                       ->orderBy('kode_kecamatan.kecamatan_nama', 'ASC')
                       ->orderBy('kode_desa.desa_nama', 'ASC')
                       ->get()->getResultArray();

        return view('data_perumahan/index', [
            'title' => 'Data Umum Perumahan (Backlog)',
            'data' => $dataUmum
        ]);
    }

    public function update()
    {
        $db = \Config\Database::connect();
        $post = $this->request->getPost();
        
        if (!empty($post['id'])) {
            foreach ($post['id'] as $idx => $id) {
                $db->table('data_perumahan')->where('id', $id)->update([
                    'jumlah_rumah' => $post['jumlah_rumah'][$idx] ?? 0,
                    'jumlah_rlh' => $post['jumlah_rlh'][$idx] ?? 0,
                    'jumlah_backlog' => $post['jumlah_backlog'][$idx] ?? 0,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
        
        return redirect()->to('/data-perumahan')->with('success', 'Data Umum Perumahan berhasil diperbarui.');
    }
}
