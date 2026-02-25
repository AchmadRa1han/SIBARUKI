<?php

namespace App\Controllers;

use App\Models\RtlhPenerimaModel;
use App\Models\RumahRtlhModel;
use App\Models\KondisiRumahModel;
use App\Models\RefMasterModel;

class Rtlh extends BaseController
{
    protected $penerimaModel;
    protected $rumahModel;
    protected $kondisiModel;
    protected $refModel;

    public function __construct()
    {
        $this->penerimaModel = new RtlhPenerimaModel();
        $this->rumahModel = new RumahRtlhModel();
        $this->kondisiModel = new KondisiRumahModel();
        $this->refModel = new RefMasterModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Data RTLH',
            'rumah' => $this->rumahModel->select('rumah_rtlh.*, p.nama_kepala_keluarga as pemilik')
                ->join('rtlh_penerima p', 'p.nik = rumah_rtlh.nik_pemilik', 'left')
                ->paginate(25, 'group1'),
            'pager' => $this->rumahModel->pager
        ];
        return view('rtlh/index', $data);
    }

    public function detail($id_survei)
    {
        $rumah = $this->rumahModel->find($id_survei);
        if (!$rumah) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $penerima = $this->penerimaModel->select('rtlh_penerima.*, pnd.nama_pilihan as pendidikan, pkj.nama_pilihan as pekerjaan')
            ->join('ref_master pnd', 'pnd.id = rtlh_penerima.pendidikan_id', 'left')
            ->join('ref_master pkj', 'pkj.id = rtlh_penerima.pekerjaan_id', 'left')
            ->find($rumah['nik_pemilik']);
        
        $kondisi = $this->kondisiModel->select('rtlh_kondisi_rumah.*')
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
            ->first();

        $data = [
            'title' => 'Detail Data RTLH',
            'rumah' => $rumah,
            'penerima' => $penerima ?: [],
            'kondisi' => $kondisi ?: []
        ];
        return view('rtlh/detail', $data);
    }

    public function create()
    {
        $allMaster = $this->refModel->findAll();
        $master = [];
        foreach ($allMaster as $m) {
            $master[$m['kategori']][] = $m;
        }
        return view('rtlh/create', ['title' => 'Input RTLH Terpadu', 'master' => $master]);
    }

    public function store()
    {
        $db = \Config\Database::connect();
        $db->transStart();
        try {
            $this->penerimaModel->insert($this->request->getPost());
            
            $dataRumah = $this->request->getPost();
            $dataRumah['nik_pemilik'] = $this->request->getPost('nik');
            $this->rumahModel->insert($dataRumah);
            $id_survei = $db->insertID();

            $dataKondisi = $this->request->getPost();
            $dataKondisi['id_survei'] = $id_survei;
            $this->kondisiModel->insert($dataKondisi);

            $db->transComplete();
            return redirect()->to('/rtlh')->with('message', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('message', 'Gagal: ' . $e->getMessage());
        }
    }

    public function edit($id_survei)
    {
        $rumah = $this->rumahModel->find($id_survei);
        $data = [
            'title' => 'Edit RTLH',
            'rumah' => $rumah,
            'penerima' => $this->penerimaModel->find($rumah['nik_pemilik']),
            'kondisi' => $this->kondisiModel->find($id_survei),
            'master' => []
        ];
        foreach ($this->refModel->findAll() as $m) { $data['master'][$m['kategori']][] = $m; }
        return view('rtlh/edit', $data);
    }

    public function update($id_survei)
    {
        $db = \Config\Database::connect();
        try {
            $rumahLama = $this->rumahModel->find($id_survei);
            $db->transStart();
            
            // Update Penerima
            $this->penerimaModel->update($rumahLama['nik_pemilik'], $this->request->getPost());
            
            // Update Rumah
            $dataRumah = $this->request->getPost();
            $dataRumah['nik_pemilik'] = $this->request->getPost('nik');
            $this->rumahModel->update($id_survei, $dataRumah);

            // Update Kondisi
            $this->kondisiModel->update($id_survei, $this->request->getPost());

            $db->transComplete();
            return redirect()->to('/rtlh/detail/' . $id_survei)->with('message', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('message', 'Kesalahan: ' . $e->getMessage());
        }
    }

    public function delete($id_survei)
    {
        $rumah = $this->rumahModel->find($id_survei);
        if ($rumah) {
            $this->kondisiModel->delete($id_survei);
            $this->rumahModel->delete($id_survei);
        }
        return redirect()->to('/rtlh')->with('message', 'Data berhasil dihapus');
    }
}
