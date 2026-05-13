<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Delineasi Kumuh - <?= $kumuh['Kelurahan'] ?></title>
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

        .skor-box { border: 2px solid #000; padding: 15px; text-align: center; margin-bottom: 20px; }
        .skor-value { font-size: 28pt; font-weight: 900; margin: 5px 0; }
        .skor-status { font-weight: bold; text-transform: uppercase; font-size: 12pt; }

        .footer { margin-top: 30px; border-top: 1px solid #ccc; padding-top: 10px; text-align: center; font-size: 8pt; color: #666; }
        
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print" style="background: #333; padding: 10px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer; font-weight: bold;">KLIK UNTUK CETAK (PDF/PRINTER)</button>
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

    <div class="title">Laporan Delineasi Wilayah Kumuh</div>

    <div class="skor-box">
        <div style="font-size: 9pt; text-transform: uppercase; font-weight: bold;">Total Skor Kekumuhan</div>
        <div class="skor-value"><?= $kumuh['skor_kumuh'] ?></div>
        <div class="skor-status">
            Tingkat Kekumuhan: 
            <?php if($kumuh['skor_kumuh'] >= 60): ?>Sangat Berat
            <?php elseif($kumuh['skor_kumuh'] >= 40): ?>Sedang
            <?php else: ?>Ringan<?php endif; ?>
        </div>
    </div>

    <div class="section-title">Identitas Wilayah</div>
    <table>
        <tr><td class="label">Kelurahan / Desa</td><td><?= $kumuh['Kelurahan'] ?></td></tr>
        <tr><td class="label">Kecamatan</td><td><?= $kumuh['Kecamatan'] ?></td></tr>
        <tr><td class="label">Nama Kawasan</td><td><?= $kumuh['Kawasan'] ?: '-' ?></td></tr>
        <tr><td class="label">Luas Delineasi (Ha)</td><td><?= number_format($kumuh['Luas_kumuh'], 2) ?> Ha</td></tr>
        <tr><td class="label">Kode RT / RW</td><td><?= $kumuh['Kode_RT_RW'] ?: '-' ?></td></tr>
    </table>

    <div class="section-title">Data Administrasi</div>
    <table>
        <tr><td class="label">ID Delineasi (FID)</td><td><?= $kumuh['FID'] ?></td></tr>
        <tr><td class="label">Kode Kelurahan</td><td><?= $kumuh['Kode_Kel'] ?></td></tr>
        <tr><td class="label">Kode Kecamatan</td><td><?= $kumuh['Kode_Kec'] ?></td></tr>
        <tr><td class="label">Sumber Data</td><td><?= $kumuh['Sumber_data'] ?></td></tr>
        <tr><td class="label">Nomor SK Kumuh</td><td><?= $kumuh['Sk_Kumuh'] ?: '-' ?></td></tr>
    </table>

    <div class="footer">
        Dicetak otomatis oleh Sistem SIBARUKI pada <?= date('d/m/Y H:i') ?> | GIS ID: KUMUH-<?= $kumuh['FID'] ?>
    </div>
</body>
</html>