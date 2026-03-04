<?php

namespace App\Controllers;

use App\Models\WilayahKumuhModel;

class WilayahKumuh extends BaseController
{
    protected $kumuhModel;

    public function __construct()
    {
        $this->kumuhModel = new WilayahKumuhModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();
        $builder = $this->kumuhModel;
        
        $keyword = $this->request->getGet('keyword');
        $filterKec = $this->request->getGet('kecamatan');
        $filterDesa = $this->request->getGet('desa_id');
        $filterSkor = $this->request->getGet('skor');

        // 1. Filter Keyword
        if ($keyword) {
            $builder->groupStart()
                ->like('Kecamatan', $keyword)
                ->orLike('Kelurahan', $keyword)
                ->orLike('desa_id', $keyword)
                ->orLike('Kawasan', $keyword)
                ->groupEnd();
        }

        // 2. Filter Lanjutan (Khusus Global)
        if (session()->get('role_scope') === 'global') {
            if ($filterKec) $builder->where('Kecamatan', $filterKec);
            if ($filterDesa) $builder->where('desa_id', $filterDesa);
            
            if ($filterSkor) {
                if ($filterSkor === 'high') $builder->where('skor_kumuh >=', 60);
                if ($filterSkor === 'mid') $builder->where('skor_kumuh >=', 40)->where('skor_kumuh <', 60);
                if ($filterSkor === 'low') $builder->where('skor_kumuh <', 40);
            }
        }

        // 3. Filter Wilayah (Petugas)
        if (session()->get('role_scope') === 'local') {
            $desa_ids = session()->get('desa_ids_kumuh');
            if (!empty($desa_ids)) {
                $builder->whereIn('desa_id', $desa_ids);
            } else {
                $builder->where('desa_id', '000000');
            }
        }

        $optKec = $db->table('wilayah_kumuh')->select('Kecamatan')->distinct()->get()->getResultArray();
        $optDesa = $db->table('wilayah_kumuh')->select('Kelurahan, desa_id')->distinct()->get()->getResultArray();

        $perPage = $this->request->getGet('per_page') ?? 10;

        // Ambil semua data untuk peta (tanpa pagination)
        $kumuh_all = (clone $builder)->findAll();

        $data = [
            'title' => 'Wilayah Kumuh',
            'kumuh' => $builder->paginate($perPage, 'group1'),
            'kumuh_all' => $kumuh_all,
            'pager' => $this->kumuhModel->pager,
            'perPage' => $perPage,
            'options' => [
                'kecamatan' => array_filter(array_column($optKec, 'Kecamatan')),
                'desa' => $optDesa
            ],
            'filters' => [
                'kecamatan' => $filterKec,
                'desa_id' => $filterDesa,
                'skor' => $filterSkor
            ]
        ];

        return view('wilayah_kumuh/index', $data);
    }

    public function peta()
    {
        if (!has_permission('view_kumuh')) {
            return redirect()->to('/dashboard')->with('message', 'Akses ditolak.');
        }

        $builder = $this->kumuhModel->where('WKT !=', null)->where('WKT !=', '');

        // Filter Wilayah (Petugas)
        if (session()->get('role_scope') === 'local') {
            $desa_ids = session()->get('desa_ids_kumuh');
            if (!empty($desa_ids)) {
                $builder->whereIn('desa_id', $desa_ids);
            } else {
                return redirect()->to('/dashboard')->with('message', 'Akses ditolak.');
            }
        }

        $data = [
            'title' => 'Peta Sebaran Wilayah Kumuh',
            'kumuh' => $builder->findAll()
        ];

        return view('wilayah_kumuh/peta', $data);
    }

    public function detail($id)
    {
        if (!has_permission('view_kumuh_detail')) {
            return redirect()->to('/wilayah-kumuh')->with('message', 'Akses ditolak. Anda tidak memiliki izin untuk melihat rincian detail wilayah.');
        }

        $kumuh = $this->kumuhModel->find($id);
        if (!$kumuh) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        // Security Check
        if (session()->get('role_scope') === 'local') {
            $desa_ids = session()->get('desa_ids_kumuh') ?? [];
            if (!in_array($kumuh['desa_id'], $desa_ids)) {
                return redirect()->to('/wilayah-kumuh')->with('message', 'Akses ditolak.');
            }
        }

        $data = [
            'title' => 'Detail Wilayah Kumuh',
            'kumuh' => $kumuh
        ];

        return view('wilayah_kumuh/detail', $data);
    }

    public function create()
    {
        if (!has_permission('create_kumuh')) return redirect()->to('/wilayah-kumuh')->with('message', 'Akses ditolak.');
        return view('wilayah_kumuh/create', ['title' => 'Tambah Wilayah Kumuh']);
    }

    public function store()
    {
        if (!has_permission('create_kumuh')) return redirect()->to('/wilayah-kumuh')->with('message', 'Akses ditolak.');

        $id = $this->kumuhModel->insert($data);
        $saved = $this->kumuhModel->find($id);
        $detailLog = $this->formatLogData($saved);

        // Tambahkan Log DETAIL
        $this->logActivity('Tambah', 'Wilayah Kumuh', 'Menambah lokasi kumuh baru: ' . ($data['Kelurahan'] ?? 'Tanpa Nama'), $detailLog);

        return redirect()->to('/wilayah-kumuh')->with('message', 'Data wilayah kumuh berhasil disimpan');
    }

    public function edit($id)
    {
        if (!has_permission('edit_kumuh')) return redirect()->to('/wilayah-kumuh')->with('message', 'Akses ditolak.');

        $kumuh = $this->kumuhModel->find($id);
        if (!$kumuh) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        // Security Check
        if (session()->get('role_scope') === 'local') {
            $desa_ids = session()->get('desa_ids_kumuh') ?? [];
            if (!in_array($kumuh['desa_id'], $desa_ids)) {
                return redirect()->to('/wilayah-kumuh')->with('message', 'Akses ditolak.');
            }
        }

        $data = [
            'title' => 'Edit Wilayah Kumuh',
            'kumuh' => $kumuh
        ];
        return view('wilayah_kumuh/edit', $data);
    }

    public function update($id)
    {
        if (!has_permission('edit_kumuh')) return redirect()->to('/wilayah-kumuh')->with('message', 'Akses ditolak.');

        $oldData = $this->kumuhModel->find($id);
        if (!$oldData) return redirect()->back()->with('message', 'Data tidak ditemukan.');

        // Security Check
        if (session()->get('role_scope') === 'local') {
            $desa_ids = session()->get('desa_ids_kumuh') ?? [];
            if (!in_array($oldData['desa_id'], $desa_ids)) {
                return redirect()->to('/wilayah-kumuh')->with('message', 'Akses ditolak.');
            }
        }

        $newData = $this->request->getPost();
        
        // Deteksi Perubahan
        $changes = [];
        if ($newData['Kecamatan'] !== $oldData['Kecamatan']) $changes[] = 'Kecamatan';
        if ($newData['Kelurahan'] !== $oldData['Kelurahan']) $changes[] = 'Kelurahan';
        if ($newData['Kode_RT_RW'] !== $oldData['Kode_RT_RW']) $changes[] = 'Kode RT/RW';
        if ($newData['skor_kumuh'] != $oldData['skor_kumuh']) $changes[] = 'Skor Kumuh';
        if ($newData['Luas_kumuh'] != $oldData['Luas_kumuh']) $changes[] = 'Luas';
        
        $detailLog = empty($changes) ? 'Memperbarui rincian wilayah' : 'Mengubah ' . implode(', ', $changes);

        $this->kumuhModel->update($id, $newData);
        $newDataRecord = $this->kumuhModel->find($id);
        $diff = $this->generateDiff($oldData, $newDataRecord);

        // Format Pesan Baru: "Perubahan pada Balangnipa Kode RT RW 01/02: Mengubah Skor Kumuh"
        $logMessage = "Perubahan pada " . $oldData['Kelurahan'] . " " . ($oldData['Kode_RT_RW'] ?? '') . ": " . $detailLog;

        // Tambahkan Log dengan DETAIL
        $this->logActivity('Ubah', 'Wilayah Kumuh', $logMessage, $diff);

        return redirect()->to('/wilayah-kumuh/detail/' . $id)->with('message', 'Data wilayah kumuh berhasil diperbarui');
    }

    public function delete($id)
    {
        if (!has_permission('delete_kumuh')) return redirect()->to('/wilayah-kumuh')->with('message', 'Akses ditolak.');

        $kumuh = $this->kumuhModel->find($id);
        if (!$kumuh) return redirect()->back()->with('message', 'Data tidak ditemukan.');

        // Security Check
        if (session()->get('role_scope') === 'local') {
            $desa_ids = session()->get('desa_ids_kumuh') ?? [];
            if (!in_array($kumuh['desa_id'], $desa_ids)) {
                return redirect()->to('/wilayah-kumuh')->with('message', 'Akses ditolak.');
            }
        }

        $db = \Config\Database::connect();
        
        $db->transStart();
        // 1. Simpan ke Trash
        $db->table('trash_data')->insert([
            'entity_type' => 'KUMUH',
            'entity_id'   => $id,
            'data_json'   => json_encode($kumuh),
            'deleted_by'  => session()->get('username'),
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        // 2. Hapus data asli
        $this->kumuhModel->delete($id);
        $db->transComplete();

        // Tambahkan Log DETAIL
        $detailLog = $this->formatLogData($kumuh);
        $this->logActivity('Hapus', 'Wilayah Kumuh', 'Memindahkan data wilayah ke Recycle Bin: ' . $kumuh['Kelurahan'], $detailLog);

        return redirect()->to('/wilayah-kumuh')->with('message', 'Data telah dipindahkan ke Recycle Bin.');
    }
}
