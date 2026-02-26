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
        $builder = $this->kumuhModel;
        $keyword = $this->request->getGet('keyword');

        if ($keyword) {
            $builder->groupStart()
                ->like('Kecamatan', $keyword)
                ->orLike('Kelurahan', $keyword)
                ->orLike('desa_id', $keyword)
                ->orLike('Kawasan', $keyword)
                ->groupEnd();
        }

        if (session()->get('role_name') === 'petugas') {
            $desa_ids = session()->get('desa_ids');
            if (!empty($desa_ids)) {
                $builder->whereIn('desa_id', $desa_ids);
            } else {
                $builder->where('desa_id', '000000');
            }
        }

        $data = [
            'title' => 'Wilayah Kumuh',
            'kumuh' => $builder->paginate(25, 'group1'),
            'pager' => $this->kumuhModel->pager
        ];

        return view('wilayah_kumuh/index', $data);
    }

    public function detail($id)
    {
        $kumuh = $this->kumuhModel->find($id);
        if (!$kumuh) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        // Security Check
        if (session()->get('role_name') === 'petugas') {
            $desa_ids = session()->get('desa_ids');
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
        return view('wilayah_kumuh/create', ['title' => 'Tambah Wilayah Kumuh']);
    }

    public function store()
    {
        $data = $this->request->getPost();
        $this->kumuhModel->insert($data);

        // Tambahkan Log
        $this->logActivity('Tambah', 'Wilayah Kumuh', 'Menambah lokasi kumuh baru: ' . ($data['Kelurahan'] ?? 'Tanpa Nama'));

        return redirect()->to('/wilayah-kumuh')->with('message', 'Data wilayah kumuh berhasil disimpan');
    }

    public function edit($id)
    {
        $kumuh = $this->kumuhModel->find($id);
        if (!$kumuh) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        // Security Check
        if (session()->get('role_name') === 'petugas') {
            $desa_ids = session()->get('desa_ids');
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
        $oldData = $this->kumuhModel->find($id);
        if (!$oldData) return redirect()->back()->with('message', 'Data tidak ditemukan.');

        // Security Check
        if (session()->get('role_name') === 'petugas') {
            $desa_ids = session()->get('desa_ids');
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

        // Format Pesan Baru: "Perubahan pada Balangnipa Kode RT RW 01/02: Mengubah Skor Kumuh"
        $logMessage = "Perubahan pada " . $oldData['Kelurahan'] . " " . ($oldData['Kode_RT_RW'] ?? '') . ": " . $detailLog;

        // Tambahkan Log
        $this->logActivity('Ubah', 'Wilayah Kumuh', $logMessage);

        return redirect()->to('/wilayah-kumuh/detail/' . $id)->with('message', 'Data wilayah kumuh berhasil diperbarui');
    }

    public function delete($id)
    {
        $kumuh = $this->kumuhModel->find($id);
        if (!$kumuh) return redirect()->back()->with('message', 'Data tidak ditemukan.');

        // Security Check
        if (session()->get('role_name') === 'petugas') {
            $desa_ids = session()->get('desa_ids');
            if (!in_array($kumuh['desa_id'], $desa_ids)) {
                return redirect()->to('/wilayah-kumuh')->with('message', 'Akses ditolak.');
            }
        }

        $this->kumuhModel->delete($id);

        // Tambahkan Log
        $this->logActivity('Hapus', 'Wilayah Kumuh', 'Menghapus data wilayah: ' . $kumuh['Kelurahan']);

        return redirect()->to('/wilayah-kumuh')->with('message', 'Data berhasil dihapus');
    }
}
