<?php

namespace App\Controllers;

use App\Models\RtlhPenerimaModel;
use App\Models\RumahRtlhModel;
use App\Models\KondisiRumahModel;
use App\Models\RefMasterModel;

class Rtlh extends BaseController
{
    private function nullify($value) {
        return ($value === '' || $value === null) ? null : $value;
    }

    public function index()
    {
        $db = \Config\Database::connect();
        
        $keyword = $this->request->getGet('keyword') ?? '';
        $perPage = $this->request->getGet('per_page') ?? 10;
        
        $builder = $db->table('rtlh_rumah')
                ->select('rtlh_rumah.*, p.nama_kepala_keluarga as pemilik')
                ->join('rtlh_penerima p', 'p.nik = rtlh_rumah.nik_pemilik', 'left');

        if (!empty($keyword)) {
            $builder->groupStart()
                ->like('p.nama_kepala_keluarga', $keyword)
                ->orLike('rtlh_rumah.nik_pemilik', $keyword)
                ->orLike('rtlh_rumah.desa', $keyword)
                ->groupEnd();
        }

        if (session()->get('role_scope') === 'local') {
            $desa_ids = session()->get('desa_ids_rtlh');
            if (!empty($desa_ids)) {
                $builder->whereIn('rtlh_rumah.desa_id', $desa_ids);
            } else {
                $builder->where('rtlh_rumah.id_survei', 0);
            }
        }

        $rumah_all = (clone $builder)->get()->getResultArray();

        $totalRows = (clone $builder)->countAllResults(false);
        $page = $this->request->getGet('page_group1') ?? 1;
        $offset = ($page - 1) * $perPage;
        
        $rumah = $builder->orderBy('id_survei', 'ASC')->limit($perPage, $offset)->get()->getResultArray();

        $pager = service('pager');
        $pager_links = $pager->makeLinks($page, $perPage, $totalRows, 'tailwind_full', 0, 'group1');

        $data = [
            'title' => 'Data RTLH',
            'rumah' => $rumah,
            'rumah_all' => $rumah_all ?? [],
            'pager' => $pager_links,
            'perPage' => $perPage,
            'keyword' => $keyword,
            'all_desa' => $db->table('kode_desa')->orderBy('desa_nama', 'ASC')->get()->getResultArray()
        ];
        return view('rtlh/index', $data);
    }

    public function detail($id)
    {
        if (!has_permission('view_rtlh_detail')) return redirect()->to('/rtlh')->with('message', 'Akses ditolak.');
        
        $db = \Config\Database::connect();
        $rumah = $db->table('rtlh_rumah')->where('id_survei', $id)->get()->getRowArray();
        if (!$rumah) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

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
            ->where('rtlh_kondisi_rumah.id_survei', $id)
            ->get()->getRowArray();

        return view('rtlh/detail', [ 
            'title' => 'Detail RTLH', 
            'rumah' => $rumah, 
            'penerima' => $penerima ?: [], 
            'kondisi' => $kondisi ?: [] 
        ]);
    }

    public function create()
    {
        if (!has_permission('create_rtlh')) return redirect()->to('/rtlh')->with('message', 'Akses ditolak.');
        $db = \Config\Database::connect();
        $refModel = new RefMasterModel();
        $master = [];
        foreach ($refModel->findAll() as $m) { $master[$m['kategori']][] = $m; }
        return view('rtlh/create', ['title' => 'Input RTLH', 'master' => $master, 'all_desa' => $db->table('kode_desa')->orderBy('desa_nama', 'ASC')->get()->getResultArray()]);
    }

    public function store()
    {
        if (!has_permission('create_rtlh')) return redirect()->to('/rtlh')->with('message', 'Akses ditolak.');
        $db = \Config\Database::connect();
        $input = $this->request->getPost();
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
                'penghasilan_per_bulan' => $this->nullify($input['penghasilan_per_bulan']),
                'jumlah_anggota_keluarga' => $this->nullify($input['jumlah_anggota_keluarga']),
            ]);
            $db->table('rtlh_rumah')->insert([
                'nik_pemilik' => $input['nik'],
                'desa' => $input['desa'],
                'desa_id' => $this->nullify($input['desa_id'] ?? null),
                'alamat_detail' => $this->nullify($input['alamat_detail']),
                'kepemilikan_rumah' => $this->nullify($input['kepemilikan_rumah'] ?? null),
                'aset_rumah_di_lokasi_lain' => $this->nullify($input['aset_rumah_di_lokasi_lain'] ?? null),
                'kepemilikan_tanah' => $this->nullify($input['kepemilikan_tanah'] ?? null),
                'sumber_penerangan' => $this->nullify($input['sumber_penerangan'] ?? null),
                'sumber_penerangan_detail' => $this->nullify($input['sumber_penerangan_detail'] ?? null),
                'bantuan_perumahan' => $this->nullify($input['bantuan_perumahan'] ?? null),
                'jenis_kawasan' => $this->nullify($input['jenis_kawasan'] ?? null),
                'fungsi_ruang' => $this->nullify($input['fungsi_ruang'] ?? null),
                'luas_rumah_m2' => $this->nullify($input['luas_rumah_m2']),
                'luas_lahan_m2' => $this->nullify($input['luas_lahan_m2'] ?? null),
                'jumlah_penghuni_jiwa' => $this->nullify($input['jumlah_penghuni_jiwa']),
                'sumber_air_minum' => $this->nullify($input['sumber_air_minum'] ?? null),
                'jarak_sam_ke_tpa_tinja' => $this->nullify($input['jarak_sam_ke_tpa_tinja'] ?? null),
                'kamar_mandi_dan_jamban' => $this->nullify($input['kamar_mandi_dan_jamban'] ?? null),
                'jenis_jamban_kloset' => $this->nullify($input['jenis_jamban_kloset'] ?? null),
                'jenis_tpa_tinja' => $this->nullify($input['jenis_tpa_tinja'] ?? null),
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
            $this->logActivity('Tambah', 'RTLH', 'Menambah data RTLH baru untuk NIK: ' . $input['nik']);
            return redirect()->to('/rtlh')->with('message', 'Data disimpan.');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('message', 'Gagal: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        if (!has_permission('edit_rtlh')) return redirect()->to('/rtlh')->with('message', 'Akses ditolak.');
        $db = \Config\Database::connect();
        $rumah = $db->table('rtlh_rumah')->where('id_survei', $id)->get()->getRowArray();
        if (!$rumah) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $penerima = $db->table('rtlh_penerima')->where('nik', $rumah['nik_pemilik'])->get()->getRowArray();
        $kondisi = $db->table('rtlh_kondisi_rumah')->where('id_survei', $id)->get()->getRowArray();

        $refModel = new RefMasterModel();
        $master = [];
        foreach ($refModel->findAll() as $m) { $master[$m['kategori']][] = $m; }

        return view('rtlh/edit', [
            'title' => 'Edit RTLH',
            'rumah' => $rumah,
            'penerima' => $penerima ?: [],
            'kondisi' => $kondisi ?: [],
            'master' => $master
        ]);
    }

    public function update($id)
    {
        if (!has_permission('edit_rtlh')) return redirect()->to('/rtlh')->with('message', 'Akses ditolak.');
        $db = \Config\Database::connect();
        $input = $this->request->getPost();
        
        $oldRumah = $db->table('rtlh_rumah')->where('id_survei', $id)->get()->getRowArray();
        if (!$oldRumah) return redirect()->to('/rtlh');

        $db->transStart();
        try {
            // Update Penerima
            $db->table('rtlh_penerima')->where('nik', $oldRumah['nik_pemilik'])->update([
                'nik' => $input['nik'],
                'no_kk' => $this->nullify($input['no_kk']),
                'nama_kepala_keluarga' => $input['nama_kepala_keluarga'],
                'tempat_lahir' => $this->nullify($input['tempat_lahir']),
                'tanggal_lahir' => $this->nullify($input['tanggal_lahir']),
                'jenis_kelamin' => $this->nullify($input['jenis_kelamin']),
                'pendidikan_id' => $this->nullify($input['pendidikan_id']),
                'pekerjaan_id' => $this->nullify($input['pekerjaan_id']),
                'penghasilan_per_bulan' => $this->nullify($input['penghasilan_per_bulan']),
                'jumlah_anggota_keluarga' => $this->nullify($input['jumlah_anggota_keluarga']),
            ]);

            // Update Rumah
            $db->table('rtlh_rumah')->where('id_survei', $id)->update([
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

            // Update Kondisi
            $db->table('rtlh_kondisi_rumah')->where('id_survei', $id)->update([
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
            $this->logActivity('Update', 'RTLH', 'Memperbarui data RTLH untuk NIK: ' . $input['nik']);
            return redirect()->to('/rtlh/detail/'.$id)->with('message', 'Data diperbarui.');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('message', 'Gagal: ' . $e->getMessage());
        }
    }

    public function delete($id_survei)
    {
        if (!has_permission('delete_rtlh')) return redirect()->to('/rtlh')->with('message', 'Akses ditolak.');
        $db = \Config\Database::connect();
        $db->transStart();
        $db->table('rtlh_kondisi_rumah')->where('id_survei', $id_survei)->delete();
        $db->table('rtlh_rumah')->where('id_survei', $id_survei)->delete();
        $db->transComplete();
        $this->logActivity('Hapus', 'RTLH', 'Menghapus data RTLH ID: ' . $id_survei);
        return redirect()->to('/rtlh')->with('message', 'Data dihapus.');
    }

    public function logExport($id)
    {
        if (!has_permission('view_rtlh_detail')) return $this->response->setJSON(['status' => 'error']);
        $db = \Config\Database::connect();
        $rumah = $db->table('rtlh_rumah')->where('id_survei', $id)->get()->getRowArray();
        $this->logActivity('Ekspor PDF', 'RTLH', 'Mendownload laporan RTLH untuk NIK: ' . ($rumah['nik_pemilik'] ?? 'Unknown'));
        return $this->response->setJSON(['status' => 'success']);
    }
}
