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
            'trash' => $db->table('sys_trash')->orderBy('created_at', 'DESC')->get()->getResultArray()
        ];

        return view('trash/index', $data);
    }

    public function restore($id)
    {
        if (!has_permission('manage_roles')) return redirect()->to('/dashboard');

        $db = \Config\Database::connect();
        $item = $db->table('sys_trash')->where('id', $id)->get()->getRowArray();
        if (!$item) return redirect()->to('/trash')->with('error', 'Data tidak ditemukan.');

        $data = json_decode($item['data_json'], true);

        $db->transStart();
        if ($item['entity_type'] === 'RTLH') {
            // Restore ke 3 Tabel
            if (!empty($data['penerima'])) {
                $db->table('perumahan_rtlh_penerima')->ignore(true)->insert($data['penerima']);
            }
            $db->table('perumahan_rtlh_rumah')->insert($data['rumah']);
            $db->table('perumahan_rtlh_kondisi')->insert($data['kondisi']);
        } elseif ($item['entity_type'] === 'USER') {
            // Restore User & Assignments
            $db->table('sys_users')->insert($data['user']);
            if (!empty($data['assignments'])) {
                $db->table('sys_user_desa')->insertBatch($data['assignments']);
            }
        } elseif ($item['entity_type'] === 'KUMUH') {
            // Restore Wilayah Kumuh
            $db->table('permukiman_wilayah_kumuh')->insert($data);
        } elseif ($item['entity_type'] === 'PISEW') {
            $db->table('permukiman_pisew')->insert($data);
        } elseif ($item['entity_type'] === 'ARSINUM') {
            $db->table('permukiman_arsinum')->insert($data);
        } elseif ($item['entity_type'] === 'PSU_JALAN') {
            $db->table('permukiman_psu_jalan')->insert($data);
        }

        // Hapus dari Trash setelah direstore
        $db->table('sys_trash')->where('id', $id)->delete();
        $db->transComplete();

        $this->logActivity('Restore', $item['entity_type'], "Memulihkan data ID: {$item['entity_id']} dari Recycle Bin");

        return redirect()->to('/trash')->with('message', 'Data berhasil dipulihkan ke posisi semula.');
    }

    public function deletePermanently($id)
    {
        if (!has_permission('manage_roles')) return redirect()->to('/dashboard');

        $db = \Config\Database::connect();
        $item = $db->table('sys_trash')->where('id', $id)->get()->getRowArray();
        if ($item) {
            $data = json_decode($item['data_json'], true);
            $this->cleanupPhysicalFiles($item['entity_type'], $data);
            $db->table('sys_trash')->where('id', $id)->delete();
        }

        return redirect()->to('/trash')->with('message', 'Data dihapus secara permanen.');
    }

    public function emptyTrash()
    {
        if (!has_permission('manage_roles')) return redirect()->to('/dashboard');

        $db = \Config\Database::connect();
        $items = $db->table('sys_trash')->get()->getResultArray();
        foreach ($items as $item) {
            $data = json_decode($item['data_json'], true);
            $this->cleanupPhysicalFiles($item['entity_type'], $data);
        }

        $db->table('sys_trash')->truncate();

        $this->logActivity('Hapus', 'Recycle Bin', 'Mengosongkan seluruh data di Recycle Bin');

        return redirect()->to('/trash')->with('message', 'Recycle Bin telah dikosongkan.');
    }

    public function bulkRestore()
    {
        if (!has_permission('manage_roles')) return redirect()->to('/dashboard');

        $ids = $this->request->getPost('ids');
        if (empty($ids) || !is_array($ids)) {
            return redirect()->to('/trash')->with('error', 'Pilih setidaknya satu data untuk dipulihkan.');
        }

        $db = \Config\Database::connect();
        $restoredCount = 0;

        $db->transStart();
        foreach ($ids as $id) {
            $item = $db->table('sys_trash')->where('id', $id)->get()->getRowArray();
            if (!$item) continue;

            $data = json_decode($item['data_json'], true);

            if ($item['entity_type'] === 'RTLH') {
                if (!empty($data['penerima'])) {
                    $db->table('perumahan_rtlh_penerima')->ignore(true)->insert($data['penerima']);
                }
                $db->table('perumahan_rtlh_rumah')->insert($data['rumah']);
                $db->table('perumahan_rtlh_kondisi')->insert($data['kondisi']);
            } elseif ($item['entity_type'] === 'USER') {
                $db->table('sys_users')->insert($data['user']);
                if (!empty($data['assignments'])) {
                    $db->table('sys_user_desa')->insertBatch($data['assignments']);
                }
            } elseif ($item['entity_type'] === 'KUMUH') {
                $db->table('permukiman_wilayah_kumuh')->insert($data);
            } elseif ($item['entity_type'] === 'PISEW') {
                $db->table('permukiman_pisew')->insert($data);
            } elseif ($item['entity_type'] === 'ARSINUM') {
                $db->table('permukiman_arsinum')->insert($data);
            } elseif ($item['entity_type'] === 'PSU_JALAN') {
                $db->table('permukiman_psu_jalan')->insert($data);
            }

            $db->table('sys_trash')->where('id', $id)->delete();
            $this->logActivity('Restore', $item['entity_type'], "Memulihkan data ID: {$item['entity_id']} dari Recycle Bin");
            $restoredCount++;
        }
        $db->transComplete();

        return redirect()->to('/trash')->with('message', "{$restoredCount} data berhasil dipulihkan.");
    }

    public function bulkDeletePermanently()
    {
        if (!has_permission('manage_roles')) return redirect()->to('/dashboard');

        $ids = $this->request->getPost('ids');
        if (empty($ids) || !is_array($ids)) {
            return redirect()->to('/trash')->with('error', 'Pilih setidaknya satu data untuk dihapus secara permanen.');
        }

        $db = \Config\Database::connect();
        $deletedCount = 0;

        $db->transStart();
        foreach ($ids as $id) {
            $item = $db->table('sys_trash')->where('id', $id)->get()->getRowArray();
            if (!$item) continue;

            $data = json_decode($item['data_json'], true);
            $this->cleanupPhysicalFiles($item['entity_type'], $data);
            $db->table('sys_trash')->where('id', $id)->delete();
            $deletedCount++;
        }
        $db->transComplete();

        return redirect()->to('/trash')->with('message', "{$deletedCount} data berhasil dihapus secara permanen.");
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
