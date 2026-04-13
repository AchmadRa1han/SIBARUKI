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
        } elseif ($item['entity_type'] === 'PISEW') {
            $db->table('pisew')->insert($data);
        } elseif ($item['entity_type'] === 'ARSINUM') {
            $db->table('arsinum')->insert($data);
        } elseif ($item['entity_type'] === 'PSU_JALAN') {
            $db->table('psu_jalan')->insert($data);
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
        $item = $db->table('trash_data')->where('id', $id)->get()->getRowArray();
        if ($item) {
            $data = json_decode($item['data_json'], true);
            $this->cleanupPhysicalFiles($item['entity_type'], $data);
            $db->table('trash_data')->where('id', $id)->delete();
        }

        return redirect()->to('/trash')->with('message', 'Data dihapus secara permanen.');
    }

    public function emptyTrash()
    {
        if (!has_permission('manage_roles')) return redirect()->to('/dashboard');

        $db = \Config\Database::connect();
        $items = $db->table('trash_data')->get()->getResultArray();
        foreach ($items as $item) {
            $data = json_decode($item['data_json'], true);
            $this->cleanupPhysicalFiles($item['entity_type'], $data);
        }

        $db->table('trash_data')->truncate();

        $this->logActivity('Hapus', 'Recycle Bin', 'Mengosongkan seluruh data di Recycle Bin');

        return redirect()->to('/trash')->with('message', 'Recycle Bin telah dikosongkan.');
    }

    private function cleanupPhysicalFiles($type, $data)
    {
        $paths = [
            'RTLH' => ['path' => 'uploads/rtlh/', 'fields' => ['foto_depan', 'foto_samping', 'foto_belakang', 'foto_dalam']],
            'PISEW' => ['path' => 'uploads/pisew/', 'fields' => ['foto']],
            'ARSINUM' => ['path' => 'uploads/arsinum/', 'fields' => ['foto']],
            'PSU_JALAN' => ['path' => 'uploads/psu/', 'fields' => ['foto_before', 'foto_after']],
        ];

        if (isset($paths[$type])) {
            $config = $paths[$type];
            // Handle RTLH different structure
            $targetData = ($type === 'RTLH') ? ($data['rumah'] ?? []) : $data;
            
            foreach ($config['fields'] as $field) {
                if (!empty($targetData[$field])) {
                    $file = FCPATH . $config['path'] . $targetData[$field];
                    if (file_exists($file)) unlink($file);
                }
            }
        }
    }
}
