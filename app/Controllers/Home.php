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
        // a. RTLH (Belum Menerima di survei)
        $rtlhTargetBuilder = $db->table('rtlh_rumah')->where('status_bantuan', 'Belum Menerima');
        if (isset($roleScope) && $roleScope === 'local') $rtlhTargetBuilder->whereIn('desa_id', !empty($desaRtlh) ? $desaRtlh : ['0']);
        $totalRtlh = $rtlhTargetBuilder->countAllResults();

        // b. RLH Survei (Sudah Menerima di survei)
        $rlhSurveiBuilder = $db->table('rtlh_rumah')->where('status_bantuan', 'Sudah Menerima');
        if (isset($roleScope) && $roleScope === 'local') $rlhSurveiBuilder->whereIn('desa_id', !empty($desaRtlh) ? $desaRtlh : ['0']);
        $rlhSurvei = $rlhSurveiBuilder->countAllResults();

        // c. RLH Bansos (Bansos yang tidak terhubung ke survei)
        $bansosExtraQuery = "
            SELECT COUNT(*) as total FROM rtlh_bansos b
            WHERE (b.id_survei IS NULL OR b.id_survei = '' OR b.id_survei = '0')
            AND b.nik NOT IN (SELECT nik_pemilik FROM rtlh_rumah)
        ";
        // Filter desa for bansos if local scope
        if (isset($roleScope) && $roleScope === 'local') {
            $desaList = "'" . implode("','", (!empty($desaRtlh) ? $desaRtlh : ['0'])) . "'";
            // Note: rtlh_bansos usually has 'desa' as name, so we join with kode_desa to get id if needed, 
            // but for simplicity here we assume if it's not in rtlh_rumah for that desa, it's extra.
            // Better yet, just filter by NIK subquery which already accounts for desa in dashboard context.
        }
        $bansosExtra = $db->query($bansosExtraQuery)->getRowArray()['total'] ?? 0;

        $totalRLH = $rlhSurvei + $bansosExtra;
        $totalRumah = $totalRtlh + $totalRLH;

        // d. Backlog dari tabel khusus
        $backlogBuilder = $db->table('backlog_data');
        if (isset($roleScope) && $roleScope === 'local') $backlogBuilder->whereIn('desa_id', !empty($desaRtlh) ? $desaRtlh : ['0']);
        $totalBacklog = $backlogBuilder->selectSum('jumlah_backlog')->get()->getRowArray()['jumlah_backlog'] ?? 0;

        // Statistik Lainnya
        $totalKumuhBuilder = $db->table('wilayah_kumuh');
        if (isset($roleScope) && $roleScope === 'local') $totalKumuhBuilder->whereIn('desa_id', !empty($desaKumuh) ? $desaKumuh : ['0']);
        $totalKumuh = $totalKumuhBuilder->countAllResults();

        $totalFormal = $db->table('perumahan_formal')->countAllResults();
        $totalPsu = $db->table('psu_jalan')->countAllResults();
        $totalArsinum = $db->table('arsinum')->countAllResults();
        $totalPisew = $db->table('pisew')->countAllResults();
        $totalAset = $db->table('aset_tanah')->countAllResults();

        // Data Spasial Publik (Limit untuk performa)
        $desaPolygons = $db->query("SELECT desa_id, TRIM(desa_nama) as desa_nama, wkt FROM kode_desa WHERE wkt IS NOT NULL AND wkt != ''")->getResultArray();
        $mapRtlh = $db->table('rtlh_rumah')
                       ->select('rtlh_rumah.id_survei as id, rtlh_penerima.nama_kepala_keluarga as name, rtlh_rumah.desa, ST_AsText(rtlh_rumah.lokasi_koordinat) as wkt')
                       ->join('rtlh_penerima', 'rtlh_penerima.nik = rtlh_rumah.nik_pemilik', 'left')
                       ->where('rtlh_rumah.lokasi_koordinat IS NOT NULL')
                       ->where('rtlh_rumah.lokasi_koordinat !=', '')
                       ->limit(200)->get()->getResultArray();
        $mapKumuh = $db->table('wilayah_kumuh')->select('FID as id, Kawasan as name, WKT as wkt, skor_kumuh, Luas_kumuh, Kode_RT_RW')->where('WKT IS NOT NULL')->get()->getResultArray();
        $mapFormal = $db->table('perumahan_formal')->select('id, nama_perumahan as name, latitude, longitude')->get()->getResultArray();
        $mapPsu = $db->table('psu_jalan')->select('id, nama_jalan as name, wkt, jalan as nilai, id_csv')->limit(100)->get()->getResultArray();
        $mapArsinum = $db->table('arsinum')->select('id, jenis_pekerjaan as name, koordinat as coords, anggaran, tahun')->get()->getResultArray();
        $mapPisew = $db->table('pisew')->select('id, jenis_pekerjaan as name, koordinat as coords')->where('koordinat IS NOT NULL AND koordinat != ""')->get()->getResultArray();
        $mapAset = $db->table('aset_tanah')->select('id, nama_pemilik as name, no_sertifikat, koordinat as coords, luas_m2, tgl_terbit')->where('koordinat IS NOT NULL')->where('koordinat !=', '')->get()->getResultArray();

        // Markers Bansos (Tambahkan ini untuk homepage)
        $mapBansos = $db->table('rtlh_bansos')->select('id, nama_penerima as name, desa, ST_AsText(lokasi_realisasi) as wkt, "bansos" as type')
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
        $builder = $db->table('rtlh_penerima p');
        $builder->select('p.nama_kepala_keluarga, p.nik, r.desa, r.alamat_detail, r.status_bantuan, r.id_survei, ST_AsText(r.lokasi_koordinat) as wkt');
        $builder->join('rtlh_rumah r', 'p.nik = r.nik_pemilik');
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
        // a. RTLH (Belum Menerima di survei)
        $rtlhTargetBuilder = $db->table('rtlh_rumah')->where('status_bantuan', 'Belum Menerima');
        if ($roleScope === 'local') $rtlhTargetBuilder->whereIn('desa_id', !empty($desaRtlh) ? $desaRtlh : ['0']);
        $totalRtlh = $rtlhTargetBuilder->countAllResults();

        // b. RLH Survei (Sudah Menerima di survei)
        $rlhSurveiBuilder = $db->table('rtlh_rumah')->where('status_bantuan', 'Sudah Menerima');
        if ($roleScope === 'local') $rlhSurveiBuilder->whereIn('desa_id', !empty($desaRtlh) ? $desaRtlh : ['0']);
        $rlhSurvei = $rlhSurveiBuilder->countAllResults();

        // c. RLH Bansos (Bansos yang tidak terhubung ke survei)
        $bansosExtraQuery = "
            SELECT COUNT(*) as total FROM rtlh_bansos b
            WHERE (b.id_survei IS NULL OR b.id_survei = '' OR b.id_survei = '0')
            AND b.nik NOT IN (SELECT nik_pemilik FROM rtlh_rumah)
        ";
        $bansosExtra = $db->query($bansosExtraQuery)->getRowArray()['total'] ?? 0;

        $totalRLH = $rlhSurvei + $bansosExtra;
        $totalRumah = $totalRtlh + $totalRLH;

        // d. Backlog dari tabel khusus
        $backlogBuilder = $db->table('backlog_data');
        if ($roleScope === 'local') $backlogBuilder->whereIn('desa_id', !empty($desaRtlh) ? $desaRtlh : ['0']);
        $totalBacklog = $backlogBuilder->selectSum('jumlah_backlog')->get()->getRowArray()['jumlah_backlog'] ?? 0;

        // Wilayah Kumuh
        $kumuhBuilder = $db->table('wilayah_kumuh');
        if ($roleScope === 'local') $kumuhBuilder->whereIn('desa_id', !empty($desaKumuh) ? $desaKumuh : ['0']);
        $totalKumuh = $kumuhBuilder->countAllResults(false);

        // Perumahan Formal
        $totalFormal = $db->table('perumahan_formal')->countAllResults();

        // PSU Jalan
        $totalPsu = $db->table('psu_jalan')->countAllResults();

        // PISEW
        $totalPisew = $db->table('pisew')->countAllResults();

        // Aset Tanah
        $totalAset = $db->table('aset_tanah')->countAllResults();

        // ARSINUM
        $totalArsinum = $db->table('arsinum')->countAllResults();

        // --- 2. DATA ANALISIS (GRAFIK) ---
        
        // Status Kelayakan (RTLH)
        // Logika: Jika status_bantuan = 'Sudah Menerima', otomatis LAYAK.
        // Jika belum, dicek kondisi atap & lantai.
        $layakQuery = "
            SELECT 
                SUM(CASE WHEN status_bantuan = 'Belum Menerima' AND st_atap = 'RUSAK' AND st_lantai = 'RUSAK' THEN 1 ELSE 0 END) as tidak_layak,
                SUM(CASE WHEN status_bantuan = 'Sudah Menerima' OR (st_atap = 'BAIK' AND st_lantai = 'BAIK') THEN 1 ELSE 0 END) as layak,
                COUNT(*) - SUM(CASE WHEN status_bantuan = 'Belum Menerima' AND st_atap = 'RUSAK' AND st_lantai = 'RUSAK' THEN 1 ELSE 0 END) 
                         - SUM(CASE WHEN status_bantuan = 'Sudah Menerima' OR (st_atap = 'BAIK' AND st_lantai = 'BAIK') THEN 1 ELSE 0 END) as menuju_layak
            FROM rtlh_rumah r
            LEFT JOIN rtlh_kondisi_rumah kr ON r.id_survei = kr.id_survei
        ";
        if ($roleScope === 'local') {
            $desaList = "'" . implode("','", (!empty($desaRtlh) ? $desaRtlh : ['0'])) . "'";
            $layakQuery .= " WHERE r.desa_id IN ($desaList)";
        }
        $statusLayak = $db->query($layakQuery)->getRowArray();

        // Top Kumuh
        $topKumuhBuilder = $db->table('wilayah_kumuh');
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
                (SELECT COUNT(*) FROM rtlh_rumah r WHERE r.desa_id = d.desa_id) as total_rtlh,
                (SELECT COUNT(*) FROM wilayah_kumuh wk WHERE wk.desa_id = d.desa_id) as total_kumuh,
                (SELECT COUNT(*) FROM aset_tanah ast WHERE ast.desa_kelurahan = TRIM(d.desa_nama) OR ast.desa_kelurahan LIKE CONCAT('%', TRIM(d.desa_nama), '%')) as total_aset,
                (SELECT COUNT(*) FROM arsinum ars WHERE ars.desa = TRIM(d.desa_nama) OR ars.desa LIKE CONCAT('%', TRIM(d.desa_nama), '%')) as total_arsinum,
                (SELECT COUNT(*) FROM pisew pis WHERE pis.lokasi_desa = TRIM(d.desa_nama) OR pis.lokasi_desa LIKE CONCAT('%', TRIM(d.desa_nama), '%')) as total_pisew
            FROM kode_desa d
            JOIN kode_kecamatan k ON d.kecamatan_id = k.kecamatan_id
            WHERE d.wkt IS NOT NULL AND d.wkt != ''
        ")->getResultArray();

        // Markers RTLH (Tipe: POINT/GEOMETRY -> WAJIB ST_AsText)
        $mapRtlh = $db->table('rtlh_rumah')
            ->select('rtlh_rumah.id_survei as id, rtlh_penerima.nama_kepala_keluarga as name, rtlh_rumah.desa, ST_AsText(rtlh_rumah.lokasi_koordinat) as wkt, "rtlh" as type')
            ->join('rtlh_penerima', 'rtlh_penerima.nik = rtlh_rumah.nik_pemilik', 'left')
            ->where('rtlh_rumah.lokasi_koordinat IS NOT NULL')
            ->where('rtlh_rumah.lokasi_koordinat !=', '')
            ->limit(100)->get()->getResultArray();

        // Markers Kumuh (Tipe: LONGTEXT -> Ambil Langsung)
        $mapKumuh = $db->table('wilayah_kumuh')->select('FID as id, Kawasan as name, WKT as wkt, skor_kumuh, Luas_kumuh, Kode_RT_RW, "kumuh" as type')
            ->where('WKT IS NOT NULL')->get()->getResultArray();

        // Markers Perumahan Formal (Gunakan Lat/Lng asli)
        $mapFormal = $db->table('perumahan_formal')->select('id, nama_perumahan as name, latitude, longitude, "formal" as type')->get()->getResultArray();

        // Linestrings PSU (Tipe: TEXT -> Ambil Langsung)
        $mapPsu = $db->table('psu_jalan')->select('id, nama_jalan as name, wkt, jalan as nilai, id_csv, "psu" as type')->get()->getResultArray();

        // Markers Aset Tanah
        $mapAset = $db->table('aset_tanah')
            ->select('id, nama_pemilik as name, koordinat as coords, luas_m2, tgl_terbit, "aset" as type')
            ->get()->getResultArray();

        // Markers Arsinum
        $mapArsinum = $db->table('arsinum')->select('id, jenis_pekerjaan as name, koordinat as coords, anggaran, tahun, "arsinum" as type')->get()->getResultArray();

        // Markers PISEW
        $mapPisew = $db->table('pisew')->select('id, jenis_pekerjaan as name, koordinat as coords, "pisew" as type')
            ->where('koordinat IS NOT NULL AND koordinat != ""')->get()->getResultArray();

        // Markers Bansos
        $mapBansos = $db->table('rtlh_bansos')->select('id, nama_penerima as name, desa, ST_AsText(lokasi_realisasi) as wkt, "bansos" as type')
            ->where('lokasi_realisasi IS NOT NULL')->get()->getResultArray();

        // --- 4. DATA LAINNYA ---
        $missingCoords = $db->table('rtlh_rumah')
            ->where('lokasi_koordinat IS NULL OR lokasi_koordinat = "" OR lokasi_koordinat = "Point(0 0)"')
            ->countAllResults();
        $missingKK = $db->table('rtlh_penerima')->where('no_kk IS NULL OR no_kk = ""')->countAllResults();

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
                'arsinum'   => $totalArsinum
            ],
            'statusLayak'   => $statusLayak,
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
