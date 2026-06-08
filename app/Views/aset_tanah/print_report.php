<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Aset Tanah - <?= $aset['nama_pemilik'] ?></title>
    <link rel="shortcut icon" type="image/png" href="<?= base_url('sinjai.png') ?>">
    <style>
        @page { size: A4; margin: 1cm; }
        body { font-family: 'Arial', sans-serif; font-size: 10pt; line-height: 1.4; color: #000; background: #fff; margin: 0; padding: 0; }
        .kop-surat { display: flex; align-items: center; border-bottom: 3px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .logo { width: 70px; height: auto; }
        .kop-text { flex: 1; text-align: center; }
        .kop-text h1 { font-size: 14pt; margin: 0; text-transform: uppercase; }
        .kop-text h2 { font-size: 16pt; margin: 0; text-transform: uppercase; font-weight: 900; }
        .kop-text p { font-size: 8pt; margin: 5px 0 0; italic; }
        
        .title { text-align: center; text-transform: uppercase; font-weight: bold; font-size: 12pt; margin-bottom: 20px; text-decoration: underline; }
        
        .section-title { background: #eee; padding: 5px 10px; font-weight: bold; text-transform: uppercase; font-size: 9pt; border: 1px solid #000; margin-bottom: 10px; margin-top: 15px; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table td { padding: 8px; vertical-align: top; border: 0.5px solid #ccc; }
        .label { font-weight: bold; width: 35%; background: #f9f9f9; }

        .status-box { border: 2px solid #000; padding: 15px; text-align: center; margin-bottom: 20px; }
        .status-value { font-size: 20pt; font-weight: 900; margin: 5px 0; text-transform: uppercase; }
        .status-label { font-weight: bold; text-transform: uppercase; font-size: 10pt; color: #666; }

        .footer { margin-top: 30px; border-top: 1px solid #ccc; padding-top: 10px; text-align: center; font-size: 8pt; color: #666; }
        
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print" style="background: #333; padding: 10px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer; font-weight: bold; background: #2563eb; color: white; border: none; border-radius: 5px;">KLIK UNTUK CETAK (PDF/PRINTER)</button>
        <button onclick="window.history.back()" style="padding: 10px 20px; cursor: pointer; margin-left: 10px;">KEMBALI</button>
    </div>

    <div class="kop-surat">
        <img src="<?= base_url('sinjai.png') ?>" class="logo">
        <div class="kop-text">
            <h1>Pemerintah Kabupaten Sinjai</h1>
            <h2>Dinas Perumahan Kawasan Permukiman dan Pertanahan Kab. Sinjai</h2>
            <p>Jl. Persatuan Raya No. 123, Kabupaten Sinjai - Sulawesi Selatan</p>
        </div>
        <div style="width: 70px;"></div>
    </div>

    <div class="title">Laporan Detail Aset Tanah Pemda</div>

    <div class="status-box">
        <div class="status-label">Status Legalitas</div>
        <div class="status-value"><?= $aset['no_sertifikat'] === 'Belum Bersertifikat' ? 'BELUM BERSERTIFIKAT' : 'BERSERTIFIKAT' ?></div>
        <div style="font-size: 9pt; font-weight: bold;">Nomor Sertifikat: <?= $aset['no_sertifikat'] ?></div>
    </div>

    <div class="section-title">Informasi Kepemilikan & Lokasi</div>
    <table>
        <tr><td class="label">Nama Pemilik / Instansi</td><td><?= $aset['nama_pemilik'] ?></td></tr>
        <tr><td class="label">Kecamatan</td><td><?= $aset['kecamatan'] ?></td></tr>
        <tr><td class="label">Desa / Kelurahan</td><td><?= $aset['desa_kelurahan'] ?></td></tr>
        <tr><td class="label">Alamat / Lokasi Detail</td><td><?= $aset['lokasi'] ?: '-' ?></td></tr>
        <tr><td class="label">Peruntukan Aset</td><td><?= $aset['peruntukan'] ?: '-' ?></td></tr>
    </table>

    <div class="section-title">Data Teknis & Fisik</div>
    <table>
        <tr><td class="label">Luas Aset</td><td><?= number_format($aset['luas_m2'], 2) ?> m<sup>2</sup></td></tr>
        <tr><td class="label">Nilai Aset (Rp)</td><td>Rp <?= number_format($aset['nilai_aset'], 0, ',', '.') ?></td></tr>
        <tr><td class="label">Nomor Hak</td><td><?= $aset['nomor_hak'] ?: '-' ?></td></tr>
        <tr><td class="label">Tanggal Terbit Sertifikat</td><td><?= $aset['tgl_terbit'] ? date('d F Y', strtotime($aset['tgl_terbit'])) : '-' ?></td></tr>
        <tr><td class="label">Status Tanah</td><td><?= $aset['status_tanah'] ?: '-' ?></td></tr>
    </table>

    <div class="section-title">Koordinat Geospasial</div>
    <table>
        <tr><td class="label">Koordinat (Lat, Lng)</td><td style="font-family: monospace; font-weight: bold;"><?= $aset['koordinat'] ?: 'Tidak tersedia' ?></td></tr>
    </table>

    <?php if(!empty($aset['keterangan'])): ?>
    <div class="section-title">Keterangan Tambahan</div>
    <div style="padding: 10px; border: 1px solid #ccc; font-style: italic; min-height: 50px;">
        <?= $aset['keterangan'] ?>
    </div>
    <?php endif; ?>

    <div class="footer">
        Dicetak otomatis oleh Sistem SIBARUKI pada <?= date('d/m/Y H:i') ?> | ID Referensi: ASET-<?= str_pad($aset['id'], 5, '0', STR_PAD_LEFT) ?>
    </div>
</body>
</html>