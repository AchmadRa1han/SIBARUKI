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

        // Statistik Publik
        $totalRtlh = $db->table('rtlh_rumah')->countAllResults();
        $totalKumuh = $db->table('wilayah_kumuh')->countAllResults();
        $totalFormal = $db->table('perumahan_formal')->countAllResults();
        $totalPsu = $db->table('psu_jalan')->countAllResults();
        $totalArsinum = $db->table('arsinum')->countAllResults();
        $totalPisew = $db->table('pisew')->countAllResults();
        $totalAset = $db->table('aset_tanah')->countAllResults();

        // Data Spasial Publik (Limit untuk performa)
        $desaPolygons = $db->query("SELECT desa_id, TRIM(desa_nama) as desa_nama, wkt FROM kode_desa WHERE wkt IS NOT NULL AND wkt != ''")->getResultArray();
        $mapRtlh = $db->table('rtlh_rumah')->select('id_survei as id, desa as name, ST_AsText(lokasi_koordinat) as wkt')->where('lokasi_koordinat IS NOT NULL')->where('lokasi_koordinat !=', '')->limit(200)->get()->getResultArray();
        $mapKumuh = $db->table('wilayah_kumuh')->select('FID as id, Kawasan as name, WKT as wkt, skor_kumuh')->where('WKT IS NOT NULL')->get()->getResultArray();
        $mapFormal = $db->table('perumahan_formal')->select('id, nama_perumahan as name, latitude, longitude')->get()->getResultArray();
        $mapPsu = $db->table('psu_jalan')->select('id, nama_jalan as name, wkt')->limit(100)->get()->getResultArray();
        $mapArsinum = $db->table('arsinum')->select('id, jenis_pekerjaan as name, koordinat as coords')->get()->getResultArray();
        $mapPisew = $db->table('pisew')->select('id, jenis_pekerjaan as name, koordinat as coords')->where('koordinat IS NOT NULL AND koordinat != ""')->get()->getResultArray();
        $mapAset = $db->table('aset_tanah')->select('id, nama_pemilik as name, no_sertifikat, koordinat as coords')->where('koordinat IS NOT NULL')->where('koordinat !=', '')->get()->getResultArray();

        $data = [
            'title'   => 'Selamat Datang di SIBARUKI Sinjai',
            'carousel' => $carousel,
            'rekap'   => [
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
                'aset'      => $mapAset
            ]
        ];

        return view('home', $data);
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
        
        // RTLH
        $rtlhBuilder = $db->table('rtlh_rumah');
        if ($roleScope === 'local') $rtlhBuilder->whereIn('desa_id', !empty($desaRtlh) ? $desaRtlh : ['0']);
        $totalRtlh = $rtlhBuilder->countAllResults(false);

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
            ->select('id_survei as id, desa as name, ST_AsText(lokasi_koordinat) as wkt, "rtlh" as type')
            ->where('lokasi_koordinat IS NOT NULL')
            ->where('lokasi_koordinat !=', '')
            ->limit(100)->get()->getResultArray();

        // Markers Kumuh (Tipe: LONGTEXT -> Ambil Langsung)
        $mapKumuh = $db->table('wilayah_kumuh')->select('FID as id, Kawasan as name, WKT as wkt, skor_kumuh, "kumuh" as type')
            ->where('WKT IS NOT NULL')->get()->getResultArray();

        // Markers Perumahan Formal (Gunakan Lat/Lng asli)
        $mapFormal = $db->table('perumahan_formal')->select('id, nama_perumahan as name, latitude, longitude, "formal" as type')->get()->getResultArray();

        // Linestrings PSU (Tipe: TEXT -> Ambil Langsung)
        $mapPsu = $db->table('psu_jalan')->select('id, nama_jalan as name, wkt, "psu" as type')->get()->getResultArray();

        // Markers Aset Tanah
        $mapAset = $db->table('aset_tanah')
            ->select('id, nama_pemilik as name, koordinat as coords, "aset" as type')
            ->get()->getResultArray();

        // Markers Arsinum
        $mapArsinum = $db->table('arsinum')->select('id, jenis_pekerjaan as name, koordinat as coords, "arsinum" as type')->get()->getResultArray();

        // Markers PISEW
        $mapPisew = $db->table('pisew')->select('id, jenis_pekerjaan as name, koordinat as coords, "pisew" as type')
            ->where('koordinat IS NOT NULL AND koordinat != ""')->get()->getResultArray();

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
                'pisew'     => $mapPisew
            ],
            'assignedDesa'  => $assignedDesaNames
        ];

        return view('dashboard', $data);
    }
}
