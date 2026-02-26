<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\RtlhPenerimaModel;
use App\Models\RumahRtlhModel;
use App\Models\KondisiRumahModel;
use App\Models\RefMasterModel;

class Rtlh extends BaseController
{
    // Fungsi pembantu untuk mengubah string kosong menjadi NULL
    private function nullify($value) {
        return ($value === '' || $value === null) ? null : $value;
    }

    public function index()
    {
        $rumahModel = new RumahRtlhModel();
        $keyword = $this->request->getGet('keyword');
        
        $builder = $rumahModel->select('rumah_rtlh.*, p.nama_kepala_keluarga as pemilik')
                ->join('rtlh_penerima p', 'p.nik = rumah_rtlh.nik_pemilik', 'left');

        // Filter Pencarian
        if ($keyword) {
            $builder->groupStart()
                ->like('p.nama_kepala_keluarga', $keyword)
                ->orLike('rumah_rtlh.nik_pemilik', $keyword)
                ->orLike('rumah_rtlh.desa', $keyword)
                ->groupEnd();
        }

        // Filter untuk Petugas
        if (session()->get('role_name') === 'petugas') {
            $desa_ids = session()->get('desa_ids');
            if (!empty($desa_ids)) {
                $builder->whereIn('rumah_rtlh.desa_id', $desa_ids);
            } else {
                $builder->where('rumah_rtlh.id_survei', 0); // Tampilkan kosong jika tidak ada tugas desa
            }
        }

        $data = [
            'title' => 'Data RTLH',
            'rumah' => $builder->orderBy('id_survei', 'ASC')
                ->paginate(25, 'group1'),
            'pager' => $rumahModel->pager
        ];
        return view('rtlh/index', $data);
    }

    public function detail($id_survei)
    {
        $db = \Config\Database::connect();
        $rumah = $db->table('rumah_rtlh')->where('id_survei', $id_survei)->get()->getRowArray();
        if (!$rumah) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        // Security Check untuk Petugas
        if (session()->get('role_name') === 'petugas') {
            $desa_ids = session()->get('desa_ids');
            if (!in_array($rumah['desa_id'], $desa_ids)) {
                return redirect()->to('/rtlh')->with('message', 'Anda tidak memiliki akses ke data wilayah ini.');
            }
        }

        $penerima = $db->table('rtlh_penerima')
            ->select('rtlh_penerima.*, pnd.nama_pilihan as pendidikan, pkj.nama_pilihan as pekerjaan')
            ->join('ref_master pnd', 'pnd.id = rtlh_penerima.pendidikan_id', 'left')
            ->join('ref_master pkj', 'pkj.id = rtlh_penerima.pekerjaan_id', 'left')
            ->where('nik', $rumah['nik_pemilik'])
            ->get()->getRowArray();

        $kondisi = $db->table('rtlh_kondisi_rumah')
            ->select('rtlh_kondisi_rumah.*')
            ->select('ref_pondasi.nama_pilihan as pondasi, ref_kolom.nama_pilihan as kolom')
            ->select('ref_balok.nama_pilihan as balok, ref_sloof.nama_pilihan as sloof')
            ->select('ref_atap_st.nama_pilihan as atap_st, ref_atap_mat.nama_pilihan as atap_mat')
            ->select('ref_dinding_st.nama_pilihan as dinding_st, ref_dinding_mat.nama_pilihan as dinding_mat')
            ->select('ref_lantai_st.nama_pilihan as lantai_st, ref_lantai_mat.nama_pilihan as lantai_mat')
            ->select('ref_rangka.nama_pilihan as rangka_atap, ref_plafon.nama_pilihan as plafon')
            ->select('ref_jendela.nama_pilihan as jendela, ref_ventilasi.nama_pilihan as ventilasi')
            ->join('ref_master ref_pondasi', 'ref_pondasi.id = rtlh_kondisi_rumah.st_pondasi', 'left')
            ->join('ref_master ref_kolom', 'ref_kolom.id = rtlh_kondisi_rumah.st_kolom', 'left')
            ->join('ref_master ref_balok', 'ref_balok.id = rtlh_kondisi_rumah.st_balok', 'left')
            ->join('ref_master ref_sloof', 'ref_sloof.id = rtlh_kondisi_rumah.st_sloof', 'left')
            ->join('ref_master ref_atap_st', 'ref_atap_st.id = rtlh_kondisi_rumah.st_atap', 'left')
            ->join('ref_master ref_atap_mat', 'ref_atap_mat.id = rtlh_kondisi_rumah.mat_atap', 'left')
            ->join('ref_master ref_dinding_st', 'ref_dinding_st.id = rtlh_kondisi_rumah.st_dinding', 'left')
            ->join('ref_master ref_dinding_mat', 'ref_dinding_mat.id = rtlh_kondisi_rumah.mat_dinding', 'left')
            ->join('ref_master ref_lantai_st', 'ref_lantai_st.id = rtlh_kondisi_rumah.st_lantai', 'left')
            ->join('ref_master ref_lantai_mat', 'ref_lantai_mat.id = rtlh_kondisi_rumah.mat_lantai', 'left')
            ->join('ref_master ref_rangka', 'ref_rangka.id = rtlh_kondisi_rumah.st_rangka_atap', 'left')
            ->join('ref_master ref_plafon', 'ref_plafon.id = rtlh_kondisi_rumah.st_plafon', 'left')
            ->join('ref_master ref_jendela', 'ref_jendela.id = rtlh_kondisi_rumah.st_jendela', 'left')
            ->join('ref_master ref_ventilasi', 'ref_ventilasi.id = rtlh_kondisi_rumah.st_ventilasi', 'left')
            ->where('rtlh_kondisi_rumah.id_survei', $id_survei)
            ->get()->getRowArray();

        $data = [ 'title' => 'Detail Data RTLH', 'rumah' => $rumah, 'penerima' => $penerima ?: [], 'kondisi' => $kondisi ?: [] ];
        return view('rtlh/detail', $data);
    }

    public function create()
    {
        $refModel = new RefMasterModel();
        $allMaster = $refModel->findAll();
        $master = [];
        foreach ($allMaster as $m) { $master[$m['kategori']][] = $m; }
        return view('rtlh/create', ['title' => 'Input RTLH Terpadu', 'master' => $master]);
    }

    public function store()
    {
        $db = \Config\Database::connect();
        $input = $this->request->getPost();
        
        // Ambil penghasilan sebagai string
        $penghasilan = $input['penghasilan_per_bulan'] ?? '';

        $db->transStart();
        try {
            $db->table('rtlh_penerima')->insert([
                'nik' => $input['nik'],
                'no_kk' => $this->nullify($input['no_kk']),
                'nama_kepala_keluarga' => $input['nama_kepala_keluarga'],
                'tempat_lahir' => $this->nullify($input['tempat_lahir']),
                'tanggal_lahir' => $this->nullify($input['tanggal_lahir']),
                'jenis_kelamin' => $this->nullify($input['jenis_kelamin']),
                'pendidikan_id' => $this->nullify($input['pendidikan_id']),
                'pekerjaan_id' => $this->nullify($input['pekerjaan_id']),
                'penghasilan_per_bulan' => $this->nullify($penghasilan),
                'jumlah_anggota_keluarga' => $this->nullify($input['jumlah_anggota_keluarga']),
            ]);
            
            $db->table('rumah_rtlh')->insert([
                'nik_pemilik' => $input['nik'],
                'desa' => $input['desa'],
                'desa_id' => $this->nullify($input['desa_id']),
                'alamat_detail' => $this->nullify($input['alamat_detail']),
                'kepemilikan_rumah' => $this->nullify($input['kepemilikan_rumah']),
                'aset_rumah_di_lokasi_lain' => $this->nullify($input['aset_rumah_di_lokasi_lain']),
                'kepemilikan_tanah' => $this->nullify($input['kepemilikan_tanah']),
                'sumber_penerangan' => $this->nullify($input['sumber_penerangan']),
                'sumber_penerangan_detail' => $this->nullify($input['sumber_penerangan_detail']),
                'bantuan_perumahan' => $this->nullify($input['bantuan_perumahan']),
                'jenis_kawasan' => $this->nullify($input['jenis_kawasan']),
                'fungsi_ruang' => $this->nullify($input['fungsi_ruang']),
                'luas_rumah_m2' => $this->nullify($input['luas_rumah_m2']),
                'luas_lahan_m2' => $this->nullify($input['luas_lahan_m2']),
                'jumlah_penghuni_jiwa' => $this->nullify($input['jumlah_penghuni_jiwa']),
                'sumber_air_minum' => $this->nullify($input['sumber_air_minum']),
                'jarak_sam_ke_tpa_tinja' => $this->nullify($input['jarak_sam_ke_tpa_tinja']),
                'kamar_mandi_dan_jamban' => $this->nullify($input['kamar_mandi_dan_jamban']),
                'jenis_jamban_kloset' => $this->nullify($input['jenis_jamban_kloset']),
                'jenis_tpa_tinja' => $this->nullify($input['jenis_tpa_tinja']),
                'lokasi_koordinat' => $this->nullify($input['lokasi_koordinat']),
            ]);
            $id_survei = $db->insertID();

            $db->table('rtlh_kondisi_rumah')->insert([
                'id_survei' => $id_survei,
                'st_pondasi' => $this->nullify($input['st_pondasi']),
                'st_kolom' => $this->nullify($input['st_kolom']),
                'st_balok' => $this->nullify($input['st_balok']),
                'st_sloof' => $this->nullify($input['st_sloof']),
                'st_rangka_atap' => $this->nullify($input['st_rangka_atap']),
                'st_plafon' => $this->nullify($input['st_plafon']),
                'st_jendela' => $this->nullify($input['st_jendela']),
                'st_ventilasi' => $this->nullify($input['st_ventilasi']),
                'mat_lantai' => $this->nullify($input['mat_lantai']),
                'st_lantai' => $this->nullify($input['st_lantai']),
                'mat_dinding' => $this->nullify($input['mat_dinding']),
                'st_dinding' => $this->nullify($input['st_dinding']),
                'mat_atap' => $this->nullify($input['mat_atap']),
                'st_atap' => $this->nullify($input['st_atap']),
            ]);

            $db->transComplete();

            // Tambahkan Log
            $this->logActivity('Tambah', 'RTLH', 'Menambah data baru untuk NIK: ' . $input['nik']);

            return redirect()->to('/rtlh')->with('message', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('message', 'Gagal: ' . $e->getMessage());
        }
    }

    public function edit($id_survei)
    {
        $db = \Config\Database::connect();
        $refModel = new RefMasterModel();
        $rumah = $db->table('rumah_rtlh')->where('id_survei', $id_survei)->get()->getRowArray();
        if (!$rumah) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        // Security Check untuk Petugas
        if (session()->get('role_name') === 'petugas') {
            $desa_ids = session()->get('desa_ids');
            if (!in_array($rumah['desa_id'], $desa_ids)) {
                return redirect()->to('/rtlh')->with('message', 'Anda tidak memiliki akses untuk mengubah data ini.');
            }
        }

        $penerima = $db->table('rtlh_penerima')->where('nik', $rumah['nik_pemilik'])->get()->getRowArray();
        $kondisi = $db->table('rtlh_kondisi_rumah')->where('id_survei', $id_survei)->get()->getRowArray();
        $master = [];
        foreach ($refModel->findAll() as $m) { $master[$m['kategori']][] = $m; }
        return view('rtlh/edit', ['title' => 'Edit RTLH', 'rumah' => $rumah, 'penerima' => $penerima, 'kondisi' => $kondisi, 'master' => $master]);
    }

    public function update($id_survei)
    {
        $db = \Config\Database::connect();
        $input = $this->request->getPost();
        
        $rumahLama = $db->table('rumah_rtlh')->where('id_survei', $id_survei)->get()->getRowArray();
        if (!$rumahLama) return redirect()->back()->with('message', 'Data tidak ditemukan.');
        
        // Security Check untuk Petugas
        if (session()->get('role_name') === 'petugas') {
            $desa_ids = session()->get('desa_ids');
            if (!in_array($rumahLama['desa_id'], $desa_ids)) {
                return redirect()->to('/rtlh')->with('message', 'Anda tidak memiliki izin memperbarui data ini.');
            }
        }

        $nikLama = $rumahLama['nik_pemilik'];
        $nikBaru = $input['nik'];
        $penghasilan = $input['penghasilan_per_bulan'] ?? '';

        // Deteksi perubahan untuk log
        $penerimaLama = $db->table('rtlh_penerima')->where('nik', $nikLama)->get()->getRowArray();
        $changes = [];
        if ($input['nama_kepala_keluarga'] !== $penerimaLama['nama_kepala_keluarga']) $changes[] = 'Nama';
        if ($nikBaru !== $nikLama) $changes[] = 'NIK';
        if ($penghasilan != $penerimaLama['penghasilan_per_bulan']) $changes[] = 'Penghasilan';
        if ($input['alamat_detail'] !== $rumahLama['alamat_detail']) $changes[] = 'Alamat';
        $detailLog = empty($changes) ? 'Memperbarui detail data' : 'Mengubah ' . implode(', ', $changes);

        // MULAI TRANSAKSI
        $db->query('SET FOREIGN_KEY_CHECKS=0'); // Matikan pengecekan sementara
        $db->transStart();
        try {
            // 1. Update Tabel Penerima
            $db->table('rtlh_penerima')->where('nik', $nikLama)->update([
                'nik' => $nikBaru,
                'no_kk' => $this->nullify($input['no_kk']),
                'nama_kepala_keluarga' => $input['nama_kepala_keluarga'],
                'tempat_lahir' => $this->nullify($input['tempat_lahir']),
                'tanggal_lahir' => $this->nullify($input['tanggal_lahir']),
                'jenis_kelamin' => $this->nullify($input['jenis_kelamin']),
                'pendidikan_id' => $this->nullify($input['pendidikan_id']),
                'pekerjaan_id' => $this->nullify($input['pekerjaan_id']),
                'penghasilan_per_bulan' => $this->nullify($penghasilan),
                'jumlah_anggota_keluarga' => $this->nullify($input['jumlah_anggota_keluarga']),
            ]);
            
            // 2. Update Tabel Rumah
            $db->table('rumah_rtlh')->where('id_survei', $id_survei)->update([
                'nik_pemilik' => $nikBaru,
                'desa' => $input['desa'],
                'desa_id' => $this->nullify($input['desa_id']),
                'alamat_detail' => $this->nullify($input['alamat_detail']),
                'kepemilikan_rumah' => $this->nullify($input['kepemilikan_rumah']),
                'aset_rumah_di_lokasi_lain' => $this->nullify($input['aset_rumah_di_lokasi_lain']),
                'kepemilikan_tanah' => $this->nullify($input['kepemilikan_tanah']),
                'sumber_penerangan' => $this->nullify($input['sumber_penerangan']),
                'sumber_penerangan_detail' => $this->nullify($input['sumber_penerangan_detail']),
                'bantuan_perumahan' => $this->nullify($input['bantuan_perumahan']),
                'jenis_kawasan' => $this->nullify($input['jenis_kawasan']),
                'fungsi_ruang' => $this->nullify($input['fungsi_ruang']),
                'luas_rumah_m2' => $this->nullify($input['luas_rumah_m2']),
                'luas_lahan_m2' => $this->nullify($input['luas_lahan_m2']),
                'jumlah_penghuni_jiwa' => $this->nullify($input['jumlah_penghuni_jiwa']),
                'sumber_air_minum' => $this->nullify($input['sumber_air_minum']),
                'jarak_sam_ke_tpa_tinja' => $this->nullify($input['jarak_sam_ke_tpa_tinja']),
                'kamar_mandi_dan_jamban' => $this->nullify($input['kamar_mandi_dan_jamban']),
                'jenis_jamban_kloset' => $this->nullify($input['jenis_jamban_kloset']),
                'jenis_tpa_tinja' => $this->nullify($input['jenis_tpa_tinja']),
                'lokasi_koordinat' => $this->nullify($input['lokasi_koordinat']),
            ]);

            // 3. Update Tabel Kondisi Rumah
            $db->table('rtlh_kondisi_rumah')->where('id_survei', $id_survei)->update([
                'st_pondasi' => $this->nullify($input['st_pondasi']),
                'st_kolom' => $this->nullify($input['st_kolom']),
                'st_balok' => $this->nullify($input['st_balok']),
                'st_sloof' => $this->nullify($input['st_sloof']),
                'st_rangka_atap' => $this->nullify($input['st_rangka_atap']),
                'st_plafon' => $this->nullify($input['st_plafon']),
                'st_jendela' => $this->nullify($input['st_jendela']),
                'st_ventilasi' => $this->nullify($input['st_ventilasi']),
                'mat_lantai' => $this->nullify($input['mat_lantai']),
                'st_lantai' => $this->nullify($input['st_lantai']),
                'mat_dinding' => $this->nullify($input['mat_dinding']),
                'st_dinding' => $this->nullify($input['st_dinding']),
                'mat_atap' => $this->nullify($input['mat_atap']),
                'st_atap' => $this->nullify($input['st_atap']),
            ]);

            $db->transComplete();
            $db->query('SET FOREIGN_KEY_CHECKS=1'); // Hidupkan kembali

            $this->logActivity('Ubah', 'RTLH', $detailLog . ' (NIK: ' . $nikBaru . ')');
            return redirect()->to('/rtlh/detail/' . $id_survei)->with('message', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            $db->transRollback();
            $db->query('SET FOREIGN_KEY_CHECKS=1');
            return redirect()->back()->withInput()->with('message', 'Gagal: ' . $e->getMessage());
        }
    }

    public function delete($id_survei)
    {
        $db = \Config\Database::connect();
        
        $rumah = $db->table('rumah_rtlh')->where('id_survei', $id_survei)->get()->getRowArray();
        if (!$rumah) return redirect()->back()->with('message', 'Data tidak ditemukan.');

        // Security Check untuk Petugas
        if (session()->get('role_name') === 'petugas') {
            $desa_ids = session()->get('desa_ids');
            if (!in_array($rumah['desa_id'], $desa_ids)) {
                return redirect()->to('/rtlh')->with('message', 'Anda tidak memiliki izin menghapus data ini.');
            }
        }

        $db->transStart();
        $db->table('rtlh_kondisi_rumah')->where('id_survei', $id_survei)->delete();
        $db->table('rumah_rtlh')->where('id_survei', $id_survei)->delete();
        $db->transComplete();

        // Tambahkan Log
        $this->logActivity('Hapus', 'RTLH', 'Menghapus data ID Survei: ' . $id_survei);

        return redirect()->to('/rtlh')->with('message', 'Data berhasil dihapus');
    }
}
