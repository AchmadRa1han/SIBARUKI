<?php

namespace App\Controllers;

class Trash extends BaseController
{
    public function index()
    {
        if (!has_permission('manage_roles')) return redirect()->to('/dashboard');

        $db = \Config\Database::connect();
        $data = [
            'title' => 'Recycle Bin (Pemulihan Data)',
            'trash' => $db->table('trash_data')->orderBy('created_at', 'DESC')->get()->getResultArray()
        ];

        return view('trash/index', $data);
    }

    public function restore($id)
    {
        if (!has_permission('manage_roles')) return redirect()->to('/dashboard');

        $db = \Config\Database::connect();
        $item = $db->table('trash_data')->where('id', $id)->get()->getRowArray();
        if (!$item) return redirect()->to('/trash')->with('error', 'Data tidak ditemukan.');

        $data = json_decode($item['data_json'], true);

        $db->transStart();
        if ($item['entity_type'] === 'RTLH') {
            // Restore ke 3 Tabel
            if (!empty($data['penerima'])) {
                $db->table('rtlh_penerima')->ignore(true)->insert($data['penerima']);
            }
            $db->table('rtlh_rumah')->insert($data['rumah']);
            $db->table('rtlh_kondisi_rumah')->insert($data['kondisi']);
        } elseif ($item['entity_type'] === 'USER') {
            // Restore User & Assignments
            $db->table('users')->insert($data['user']);
            if (!empty($data['assignments'])) {
                $db->table('user_desa')->insertBatch($data['assignments']);
            }
        } elseif ($item['entity_type'] === 'KUMUH') {
            // Restore Wilayah Kumuh
            $db->table('wilayah_kumuh')->insert($data);
        }

        // Hapus dari Trash setelah direstore
        $db->table('trash_data')->where('id', $id)->delete();
        $db->transComplete();

        $this->logActivity('Restore', $item['entity_type'], "Memulihkan data ID: {$item['entity_id']} dari Recycle Bin");

        return redirect()->to('/trash')->with('message', 'Data berhasil dipulihkan ke posisi semula.');
    }

    public function deletePermanently($id)
    {
        if (!has_permission('manage_roles')) return redirect()->to('/dashboard');

        $db = \Config\Database::connect();
        $db->table('trash_data')->where('id', $id)->delete();

        return redirect()->to('/trash')->with('message', 'Data dihapus secara permanen.');
    }

    public function emptyTrash()
    {
        if (!has_permission('manage_roles')) return redirect()->to('/dashboard');

        $db = \Config\Database::connect();
        $db->table('trash_data')->truncate();

        $this->logActivity('Hapus', 'Recycle Bin', 'Mengosongkan seluruh data di Recycle Bin');

        return redirect()->to('/trash')->with('message', 'Recycle Bin telah dikosongkan.');
    }
}
