<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan RTLH - <?= $penerima['nama_kepala_keluarga'] ?></title>
    <style>
        @page { size: A4; margin: 1cm; }
        body { font-family: 'Arial', sans-serif; font-size: 9pt; line-height: 1.3; color: #000; background: #fff; margin: 0; padding: 0; }
        .kop-surat { display: flex; align-items: center; border-bottom: 3px solid #000; padding-bottom: 10px; margin-bottom: 15px; }
        .logo { width: 60px; height: auto; }
        .kop-text { flex: 1; text-align: center; }
        .kop-text h1 { font-size: 12pt; margin: 0; text-transform: uppercase; }
        .kop-text h2 { font-size: 14pt; margin: 0; text-transform: uppercase; font-weight: 900; }
        .kop-text p { font-size: 7pt; margin: 3px 0 0; italic; }
        
        .title { text-align: center; text-transform: uppercase; font-weight: bold; font-size: 11pt; margin-bottom: 15px; text-decoration: underline; }
        
        .section-title { background: #eee; padding: 4px 8px; font-weight: bold; text-transform: uppercase; font-size: 8pt; border: 1px solid #000; margin-bottom: 8px; margin-top: 10px; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table td { padding: 3px 5px; vertical-align: top; border: 0.5px solid #ccc; }
        .label { font-weight: bold; width: 30%; background: #f9f9f9; }
        
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
        .card { border: 1px solid #000; padding: 5px; }
        .card-label { font-size: 7pt; text-transform: uppercase; font-weight: bold; color: #555; border-bottom: 0.5px solid #eee; margin-bottom: 3px; }
        .card-value { font-weight: bold; font-size: 9pt; }

        .photo-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 5px; margin-bottom: 10px; }
        .photo-item { border: 1px solid #000; height: 100px; display: flex; align-items: center; justify-content: center; overflow: hidden; background: #fafafa; }
        .photo-item img { width: 100%; height: 100%; object-fit: cover; }
        
        .footer { margin-top: 15px; border-top: 1px solid #ccc; padding-top: 5px; text-align: center; font-size: 7pt; color: #666; }
        
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print" style="background: #333; padding: 10px; text-align: center;">
        <button onclick="window.print()" style="padding: 8px 16px; cursor: pointer; font-weight: bold;">KLIK UNTUK CETAK (PDF/PRINTER)</button>
        <button onclick="window.history.back()" style="padding: 8px 16px; cursor: pointer; margin-left: 10px;">KEMBALI</button>
    </div>

    <div class="kop-surat">
        <img src="<?= base_url('sinjai.png') ?>" class="logo">
        <div class="kop-text">
            <h1>Pemerintah Kabupaten Sinjai</h1>
            <h2>Dinas Perumahan Kawasan Permukiman dan Pertanahan Kab. Sinjai</h2>
            <p>Jl. Persatuan Raya No. 123, Kabupaten Sinjai - Sulawesi Selatan</p>
        </div>
        <div style="width: 60px;"></div>
    </div>

    <div class="title">Laporan Survei Rumah Tidak Layak Huni (RTLH)</div>

    <div class="section-title">Identitas Penerima</div>
    <table>
        <tr><td class="label">Nama Kepala Keluarga</td><td><?= $penerima['nama_kepala_keluarga'] ?></td><td class="label">NIK</td><td><?= $rumah['nik_pemilik'] ?></td></tr>
        <tr><td class="label">Desa / Kelurahan</td><td><?= $rumah['desa'] ?></td><td class="label">No. KK</td><td><?= $penerima['no_kk'] ?></td></tr>
        <tr><td class="label">Pekerjaan</td><td><?= $penerima['nama_pekerjaan'] ?? '-' ?></td><td class="label">Penghasilan</td><td><?= $penerima['penghasilan_per_bulan'] ?></td></tr>
    </table>

    <div class="section-title">Kondisi Teknis & Material</div>
    <div class="grid-3">
        <div class="card"><div class="card-label">Pondasi</div><div class="card-value"><?= $kondisi['nama_st_pondasi'] ?? '-' ?></div></div>
        <div class="card"><div class="card-label">Dinding</div><div class="card-value"><?= $kondisi['nama_mat_dinding'] ?? '-' ?> (<?= $kondisi['nama_st_dinding'] ?? '-' ?>)</div></div>
        <div class="card"><div class="card-label">Atap</div><div class="card-value"><?= $kondisi['nama_mat_atap'] ?? '-' ?> (<?= $kondisi['nama_st_atap'] ?? '-' ?>)</div></div>
        <div class="card"><div class="card-label">Lantai</div><div class="card-value"><?= $kondisi['nama_mat_lantai'] ?? '-' ?> (<?= $kondisi['nama_st_lantai'] ?? '-' ?>)</div></div>
        <div class="card"><div class="card-label">Sanitasi</div><div class="card-value"><?= $rumah['kamar_mandi_dan_jamban'] ?></div></div>
        <div class="card"><div class="card-label">Air Minum</div><div class="card-value"><?= $rumah['sumber_air_minum'] ?></div></div>
    </div>

    <div class="section-title">Dokumentasi Visual (Kondisi Saat Survei)</div>
    <div class="photo-grid">
        <?php foreach(['foto_depan', 'foto_samping', 'foto_belakang', 'foto_dalam'] as $f): ?>
            <div class="photo-item">
                <?php if (!empty($rumah[$f])): ?>
                    <img src="<?= base_url('uploads/rtlh/'.$rumah[$f]) ?>">
                <?php else: ?>
                    <span style="font-size: 6pt; color: #999;">TIDAK ADA FOTO</span>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="footer">
        Dicetak otomatis oleh Sistem SIBARUKI pada <?= date('d/m/Y H:i') ?> | ID Survei: SRV-<?= str_pad($rumah['id_survei'], 5, '0', STR_PAD_LEFT) ?>
    </div>
</body>
</html>