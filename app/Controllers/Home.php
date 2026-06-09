<?php

namespace App\Controllers;

use App\Models\SettingsModel;

class Home extends BaseController
{
    /**
     * Landing Page Publik
     */
    public function index()
    {
        $db = \Config\Database::connect();
        $settingsModel = new SettingsModel();

        // Carousel Dinamis
        $carouselJson = $settingsModel->getSetting('carousel_images', '[]');
        $carousel = json_decode($carouselJson, true);

        // --- 1. DATA STATISTIK (REKAP) ---
        // a. RTLH (Sasaran)
        $rtlhTargetBuilder = $db->table('perumahan_rtlh_rumah')->whereIn('status_bantuan', ['Rtlh', 'Target']);
        if (isset($roleScope) && $roleScope === 'local') $rtlhTargetBuilder->whereIn('desa_id', !empty($desaRtlh) ? $desaRtlh : ['0']);
        $totalRtlh = $rtlhTargetBuilder->countAllResults();

        // b. RLH (Sudah Layak)
        $rlhSurveiBuilder = $db->table('perumahan_rtlh_rumah')->whereIn('status_bantuan', ['Rlh', 'Sudah Menerima']);
        if (isset($roleScope) && $roleScope === 'local') $rlhSurveiBuilder->whereIn('desa_id', !empty($desaRtlh) ? $desaRtlh : ['0']);
        $rlhSurvei = $rlhSurveiBuilder->countAllResults();

        // c. RLH Bansos (Bansos yang tidak terhubung ke survei)
        $bansosExtraQuery = "
            SELECT COUNT(*) as total FROM perumahan_rtlh_bansos b
            WHERE (b.id_survei IS NULL OR b.id_survei = '' OR b.id_survei = '0')
            AND b.nik NOT IN (SELECT nik_pemilik FROM perumahan_rtlh_rumah)
        ";
        // Filter desa for bansos if local scope
        if (isset($roleScope) && $roleScope === 'local') {
            $desaList = "'" . implode("','", (!empty($desaRtlh) ? $desaRtlh : ['0'])) . "'";
        }
        $bansosExtra = $db->query($bansosExtraQuery)->getRowArray()['total'] ?? 0;

        $totalRLH = $rlhSurvei + $bansosExtra;
        
        // d. Total Rumah (Semua record di database spasial)
        $totalRumahBuilder = $db->table('perumahan_rtlh_rumah');
        if (isset($roleScope) && $roleScope === 'local') $totalRumahBuilder->whereIn('desa_id', !empty($desaRtlh) ? $desaRtlh : ['0']);
        $totalRumah = $totalRumahBuilder->countAllResults();

        // d. Backlog dari tabel individu (By Name By Address)
        $backlogBuilder = $db->table('perumahan_backlog_individu');
        if (isset($roleScope) && $roleScope === 'local') $backlogBuilder->whereIn('desa_id', !empty($desaRtlh) ? $desaRtlh : ['0']);
        $totalBacklog = $backlogBuilder->countAllResults();

        // Statistik Lainnya
        $totalKumuhBuilder = $db->table('permukiman_wilayah_kumuh');
        if (isset($roleScope) && $roleScope === 'local') $totalKumuhBuilder->whereIn('desa_id', !empty($desaKumuh) ? $desaKumuh : ['0']);
        $totalKumuh = $totalKumuhBuilder->countAllResults();

        $totalFormal = $db->table('perumahan_formal')->countAllResults();
        $totalPsu = $db->table('permukiman_psu_jalan')->countAllResults();
        $totalArsinum = $db->table('permukiman_arsinum')->countAllResults();
        $totalPisew = $db->table('permukiman_pisew')->countAllResults();
        $totalAset = $db->table('pertanahan_aset')->countAllResults();
        $totalBansos = $db->table('perumahan_rtlh_bansos')->countAllResults();

        // Data Spasial Publik (Limit untuk performa)
        $desaPolygons = $db->query("SELECT desa_id, TRIM(desa_nama) as desa_nama, wkt FROM kode_desa WHERE wkt IS NOT NULL AND wkt != ''")->getResultArray();
        $mapRtlh = $db->table('perumahan_rtlh_rumah')
                       ->select('perumahan_rtlh_rumah.id_survei as id, perumahan_rtlh_penerima.nama_kepala_keluarga as name, perumahan_rtlh_rumah.desa, ST_AsText(perumahan_rtlh_rumah.lokasi_koordinat) as wkt, perumahan_rtlh_rumah.foto_depan as image')
                       ->join('perumahan_rtlh_penerima', 'perumahan_rtlh_penerima.nik = perumahan_rtlh_rumah.nik_pemilik', 'left')
                       ->where('perumahan_rtlh_rumah.lokasi_koordinat IS NOT NULL')
                       ->where('perumahan_rtlh_rumah.lokasi_koordinat !=', '')
                       ->limit(200)->get()->getResultArray();
        $mapKumuh = $db->table('permukiman_wilayah_kumuh')->select('FID as id, Kawasan as name, WKT as wkt, skor_kumuh, Luas_kumuh, Sk_Kumuh')->where('WKT IS NOT NULL')->get()->getResultArray();
        $mapFormal = $db->table('perumahan_formal')->select('id, nama_perumahan as name, latitude, longitude, pengembang')->get()->getResultArray();
        $mapPsu = $db->table('permukiman_psu_jalan')->select('id, nama_jalan as name, wkt, panjang_luas as nilai, tahun, foto_after as image')->limit(100)->get()->getResultArray();
        $mapArsinum = $db->table('permukiman_arsinum')->select('id, jenis_pekerjaan as name, koordinat as coords, tahun')->get()->getResultArray();
        $mapPisew = $db->table('permukiman_pisew')->select('id, jenis_pekerjaan as name, koordinat as coords, tahun')->where('koordinat IS NOT NULL AND koordinat != ""')->get()->getResultArray();
        $mapAset = $db->table('pertanahan_aset')->select('id, nama_pemilik as name, nomor_hak, koordinat as coords, luas_m2')->where('koordinat IS NOT NULL')->where('koordinat !=', '')->get()->getResultArray();

        // Markers Bansos (Tambahkan ini untuk homepage)
        $mapBansos = $db->table('perumahan_rtlh_bansos')->select('id, nama_penerima as name, desa, ST_AsText(lokasi_realisasi) as wkt, tahun_anggaran, sumber_dana, foto_after as image')
            ->where('lokasi_realisasi IS NOT NULL')->get()->getResultArray();

        $data = [
            'title'   => 'Selamat Datang di SIBARUKI Sinjai',
            'isLoggedIn' => session()->get('user_id') ? true : false,
            'carousel' => $carousel,
            'rekap'   => [
                'rumah'   => $totalRumah,
                'rlh'     => $totalRLH,
                'backlog' => $totalBacklog,
                'rtlh'    => $totalRtlh,
                'kumuh'   => $totalKumuh,
                'formal'  => $totalFormal,
                'psu'     => $totalPsu,
                'arsinum' => $totalArsinum,
                'pisew'   => $totalPisew,
                'aset'    => $totalAset
            ],
            'spasial' => [
                'kecamatan' => $desaPolygons,
                'rtlh'      => $mapRtlh,
                'kumuh'     => $mapKumuh,
                'formal'    => $mapFormal,
                'psu'       => $mapPsu,
                'arsinum'   => $mapArsinum,
                'pisew'     => $mapPisew,
                'aset'      => $mapAset,
                'bansos'    => $mapBansos
            ]
        ];

        return view('home', $data);
    }

    /**
     * Pencarian NIK Publik
     */
    public function searchNik()
    {
        $nik = $this->request->getVar('nik');
        if (!$nik) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'NIK tidak boleh kosong']);
        }

        // Bersihkan NIK
        $nik = preg_replace('/[^0-9]/', '', $nik);
        if (!$nik) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Format NIK tidak valid']);
        }

        $db = \Config\Database::connect();
        $builder = $db->table('perumahan_rtlh_penerima p');
        $builder->select('p.nama_kepala_keluarga, p.nik, r.desa, r.alamat_detail, r.status_bantuan, r.id_survei, ST_AsText(r.lokasi_koordinat) as wkt');
        $builder->join('perumahan_rtlh_rumah r', 'p.nik = r.nik_pemilik');
        $builder->where('p.nik', $nik);
        
        try {
            $data = $builder->get()->getRowArray();
            if ($data) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'data'   => $data
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan saat mengakses database.'
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'not_found',
            'message' => 'NIK tidak terdaftar'
        ]);
    }

    /**
     * Dashboard Internal (Hanya setelah login)
     */
    public function dashboard()
    {
        $db = \Config\Database::connect();
        $roleScope = session()->get('role_scope');
        $desaRtlh = session()->get('desa_ids_rtlh') ?? [];
        $desaKumuh = session()->get('desa_ids_kumuh') ?? [];

        // --- 1. STATISTIK REKAPITULASI (7 TABEL) ---
        // a. RTLH (Sasaran)
        $rtlhTargetBuilder = $db->table('perumahan_rtlh_rumah')->whereIn('status_bantuan', ['Rtlh', 'Target']);
        if ($roleScope === 'local') $rtlhTargetBuilder->whereIn('desa_id', !empty($desaRtlh) ? $desaRtlh : ['0']);
        $totalRtlh = $rtlhTargetBuilder->countAllResults();

        // b. RLH (Sudah Layak)
        $rlhSurveiBuilder = $db->table('perumahan_rtlh_rumah')->whereIn('status_bantuan', ['Rlh', 'Sudah Menerima']);
        if ($roleScope === 'local') $rlhSurveiBuilder->whereIn('desa_id', !empty($desaRtlh) ? $desaRtlh : ['0']);
        $rlhSurvei = $rlhSurveiBuilder->countAllResults();

        // c. RLH Bansos (Extra)
        $bansosExtraQuery = "
            SELECT COUNT(*) as total FROM perumahan_rtlh_bansos b
            WHERE (b.id_survei IS NULL OR b.id_survei = '' OR b.id_survei = '0')
            AND b.nik NOT IN (SELECT nik_pemilik FROM perumahan_rtlh_rumah)
        ";
        $bansosExtra = $db->query($bansosExtraQuery)->getRowArray()['total'] ?? 0;

        $totalRLH = $rlhSurvei + $bansosExtra;
        
        // d. Total Rumah (Semua record di database spasial)
        $totalRumahBuilder = $db->table('perumahan_rtlh_rumah');
        if ($roleScope === 'local') $totalRumahBuilder->whereIn('desa_id', !empty($desaRtlh) ? $desaRtlh : ['0']);
        $totalRumah = $totalRumahBuilder->countAllResults();

        // d. Backlog dari tabel individu (By Name By Address)
        $backlogBuilder = $db->table('perumahan_backlog_individu');
        if ($roleScope === 'local') $backlogBuilder->whereIn('desa_id', !empty($desaRtlh) ? $desaRtlh : ['0']);
        $totalBacklog = $backlogBuilder->countAllResults();

        // Wilayah Kumuh
        $kumuhBuilder = $db->table('permukiman_wilayah_kumuh');
        if ($roleScope === 'local') $kumuhBuilder->whereIn('desa_id', !empty($desaKumuh) ? $desaKumuh : ['0']);
        $totalKumuh = $kumuhBuilder->countAllResults(false);

        // Perumahan Formal
        $totalFormal = $db->table('perumahan_formal')->countAllResults();

        // PSU Jalan
        $totalPsu = $db->table('permukiman_psu_jalan')->countAllResults();

        // PISEW
        $totalPisew = $db->table('permukiman_pisew')->countAllResults();

        // Bansos RTLH
        $totalBansos = $db->table('perumahan_rtlh_bansos')->countAllResults();

        // Aset Tanah
        $totalAset = $db->table('pertanahan_aset')->countAllResults();

        // ARSINUM
        $totalArsinum = $db->table('permukiman_arsinum')->countAllResults();

        // --- 2. DATA ANALISIS (GRAFIK) ---
        
        // Status Kelayakan (RTLH & RLH)
        // Logika: 
        // 1. RTLH (Target) = status_bantuan 'Rtlh' atau 'Target'
        // 2. RLH = status_bantuan 'Rlh' atau 'Sudah Menerima'
        $layakQuery = "
            SELECT 
                SUM(CASE WHEN status_bantuan = 'Target' THEN 1 ELSE 0 END) as target,
                SUM(CASE WHEN status_bantuan = 'Rtlh' THEN 1 ELSE 0 END) as rtlh,
                SUM(CASE WHEN status_bantuan = 'Rlh' THEN 1 ELSE 0 END) as rlh,
                SUM(CASE WHEN status_bantuan = 'Sudah Menerima' THEN 1 ELSE 0 END) as sudah_menerima,
                SUM(CASE WHEN status_bantuan IS NULL OR status_bantuan = '' OR status_bantuan NOT IN ('Target', 'Rtlh', 'Rlh', 'Sudah Menerima') THEN 1 ELSE 0 END) as belum_terdata
            FROM perumahan_rtlh_rumah
        ";
        if ($roleScope === 'local') {
            $desaList = "'" . implode("','", (!empty($desaRtlh) ? $desaRtlh : ['0'])) . "'";
            $layakQuery = "
                SELECT 
                    SUM(CASE WHEN status_bantuan = 'Target' THEN 1 ELSE 0 END) as target,
                    SUM(CASE WHEN status_bantuan = 'Rtlh' THEN 1 ELSE 0 END) as rtlh,
                    SUM(CASE WHEN status_bantuan = 'Rlh' THEN 1 ELSE 0 END) as rlh,
                    SUM(CASE WHEN status_bantuan = 'Sudah Menerima' THEN 1 ELSE 0 END) as sudah_menerima,
                    SUM(CASE WHEN status_bantuan IS NULL OR status_bantuan = '' OR status_bantuan NOT IN ('Target', 'Rtlh', 'Rlh', 'Sudah Menerima') THEN 1 ELSE 0 END) as belum_terdata
                FROM perumahan_rtlh_rumah
                WHERE desa_id IN ($desaList)
            ";
        }
        $statusLayak = $db->query($layakQuery)->getRowArray();

        // --- NEW: ANALISIS ASET TANAH PEMDA ---
        // a. Status Sertifikat
        $asetSertifQuery = "
            SELECT 
                SUM(CASE WHEN no_sertifikat = 'Belum Bersertifikat' THEN 1 ELSE 0 END) as belum_sertifikat,
                SUM(CASE WHEN no_sertifikat != 'Belum Bersertifikat' AND no_sertifikat != '' AND no_sertifikat IS NOT NULL THEN 1 ELSE 0 END) as bersertifikat
            FROM pertanahan_aset
        ";
        $statusAset = $db->query($asetSertifQuery)->getRowArray();

        // Top Kumuh
        $topKumuhBuilder = $db->table('permukiman_wilayah_kumuh');
        if ($roleScope === 'local') $topKumuhBuilder->whereIn('desa_id', !empty($desaKumuh) ? $desaKumuh : ['0']);
        $topKumuh = $topKumuhBuilder->orderBy('skor_kumuh', 'DESC')->limit(5)->get()->getResultArray();

        // --- 3. DATA SPASIAL (TACTICAL MAP) ---

        // Batas Desa (Peta Desa - Sumber Utama karena Data Kecamatan Terpotong)
        $desaPolygons = $db->query("
            SELECT 
                d.desa_id,
                TRIM(d.desa_nama) as desa_nama, 
                d.wkt,
                k.kecamatan_id,
                k.kecamatan_nama,
                (SELECT COUNT(*) FROM perumahan_rtlh_rumah r WHERE r.desa_id = d.desa_id) as total_rtlh,
                (SELECT COUNT(*) FROM permukiman_wilayah_kumuh wk WHERE wk.desa_id = d.desa_id) as total_kumuh,
                (SELECT COUNT(*) FROM pertanahan_aset ast WHERE ast.desa_kelurahan = TRIM(d.desa_nama) OR ast.desa_kelurahan LIKE CONCAT('%', TRIM(d.desa_nama), '%')) as total_aset,
                (SELECT COUNT(*) FROM permukiman_arsinum ars WHERE ars.desa = TRIM(d.desa_nama) OR ars.desa LIKE CONCAT('%', TRIM(d.desa_nama), '%')) as total_arsinum,
                (SELECT COUNT(*) FROM permukiman_pisew pis WHERE pis.lokasi_desa = TRIM(d.desa_nama) OR pis.lokasi_desa LIKE CONCAT('%', TRIM(d.desa_nama), '%')) as total_pisew
            FROM kode_desa d
            JOIN kode_kecamatan k ON d.kecamatan_id = k.kecamatan_id
            WHERE d.wkt IS NOT NULL AND d.wkt != ''
        ")->getResultArray();

        // Markers RTLH (Tipe: POINT/GEOMETRY -> WAJIB ST_AsText)
        $mapRtlh = $db->table('perumahan_rtlh_rumah')
            ->select('perumahan_rtlh_rumah.id_survei as id, perumahan_rtlh_penerima.nama_kepala_keluarga as name, perumahan_rtlh_rumah.desa, ST_AsText(perumahan_rtlh_rumah.lokasi_koordinat) as wkt, "rtlh" as type, perumahan_rtlh_rumah.foto_depan as image')
            ->join('perumahan_rtlh_penerima', 'perumahan_rtlh_penerima.nik = perumahan_rtlh_rumah.nik_pemilik', 'left')
            ->where('perumahan_rtlh_rumah.lokasi_koordinat IS NOT NULL')
            ->where('perumahan_rtlh_rumah.lokasi_koordinat !=', '')
            ->limit(100)->get()->getResultArray();

        // Markers Kumuh (Tipe: LONGTEXT -> Ambil Langsung)
        $mapKumuh = $db->table('permukiman_wilayah_kumuh')->select('FID as id, Kawasan as name, WKT as wkt, skor_kumuh, Luas_kumuh, Sk_Kumuh, "kumuh" as type')
            ->where('WKT IS NOT NULL')->get()->getResultArray();

        // Markers Perumahan Formal (Gunakan Lat/Lng asli)
        $mapFormal = $db->table('perumahan_formal')->select('id, nama_perumahan as name, latitude, longitude, pengembang, "formal" as type')->get()->getResultArray();

        // Linestrings PSU (Tipe: TEXT -> Ambil Langsung)
        $mapPsu = $db->table('permukiman_psu_jalan')->select('id, nama_jalan as name, wkt, panjang_luas as nilai, tahun, foto_after as image, "psu" as type')->get()->getResultArray();

        // Markers Aset Tanah
        $mapAset = $db->table('pertanahan_aset')
            ->select('id, nama_pemilik as name, nomor_hak, koordinat as coords, luas_m2, "aset" as type')
            ->where('koordinat IS NOT NULL')->where('koordinat !=', '')
            ->get()->getResultArray();

        // Markers Arsinum
        $mapArsinum = $db->table('permukiman_arsinum')->select('id, jenis_pekerjaan as name, koordinat as coords, tahun, "arsinum" as type')->get()->getResultArray();

        // Markers PISEW
        $mapPisew = $db->table('permukiman_pisew')->select('id, jenis_pekerjaan as name, koordinat as coords, tahun, "pisew" as type')
            ->where('koordinat IS NOT NULL AND koordinat != ""')->get()->getResultArray();

        // Markers Bansos
        $mapBansos = $db->table('perumahan_rtlh_bansos')->select('id, nama_penerima as name, desa, ST_AsText(lokasi_realisasi) as wkt, tahun_anggaran, sumber_dana, foto_after as image, "bansos" as type')
            ->where('lokasi_realisasi IS NOT NULL')->get()->getResultArray();

        // --- 4. DATA LAINNYA ---
        $missingCoords = $db->table('perumahan_rtlh_rumah')
            ->where('lokasi_koordinat IS NULL OR lokasi_koordinat = "" OR lokasi_koordinat = "Point(0 0)"')
            ->countAllResults();
        $missingKK = $db->table('perumahan_rtlh_penerima')->where('no_kk IS NULL OR no_kk = ""')->countAllResults();

        $assignedDesaNames = [];
        if ($roleScope === 'local') {
            $allMyDesa = array_unique(array_merge($desaRtlh, $desaKumuh));
            if (!empty($allMyDesa)) {
                $desaData = $db->table('kode_desa')->select('desa_nama')->whereIn('desa_id', $allMyDesa)->get()->getResultArray();
                $assignedDesaNames = array_column($desaData, 'desa_nama');
            }
        }

        $data = [
            'title'         => 'Dashboard',
            'rekap'         => [
                'rumah'     => $totalRumah,
                'rlh'       => $totalRLH,
                'backlog'   => $totalBacklog,
                'rtlh'      => $totalRtlh,
                'kumuh'     => $totalKumuh,
                'formal'    => $totalFormal,
                'psu'       => $totalPsu,
                'pisew'     => $totalPisew,
                'aset'      => $totalAset,
                'arsinum'   => $totalArsinum,
                'bansos'    => $totalBansos
            ],
            'statusLayak'   => $statusLayak,
            'statusAset'    => $statusAset,
            'topKumuh'      => $topKumuh,
            'health'        => ['coords' => $missingCoords, 'kk' => $missingKK],
            'spasial'       => [
                'kecamatan' => $desaPolygons,
                'rtlh'      => $mapRtlh,
                'kumuh'     => $mapKumuh,
                'formal'    => $mapFormal,
                'psu'       => $mapPsu,
                'aset'      => $mapAset,
                'arsinum'   => $mapArsinum,
                'pisew'     => $mapPisew,
                'bansos'    => $mapBansos
            ],
            'assignedDesa'  => $assignedDesaNames
        ];

        return view('dashboard', $data);
    }
}
