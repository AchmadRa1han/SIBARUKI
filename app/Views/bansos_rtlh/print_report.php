<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Realisasi - <?= $bansos['nama_penerima'] ?></title>
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
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table td { padding: 5px; vertical-align: top; }
        .label { font-weight: bold; width: 30%; }
        .separator { width: 5px; }
        
        .section-title { background: #eee; padding: 5px 10px; font-weight: bold; text-transform: uppercase; font-size: 9pt; border: 1px solid #000; margin-bottom: 10px; }
        
        .coords-box { border: 1px solid #000; padding: 10px; margin-bottom: 15px; display: flex; justify-content: space-around; }
        .coords-item { text-align: center; }
        .coords-label { font-size: 7pt; text-transform: uppercase; font-weight: bold; color: #666; }
        .coords-value { font-family: monospace; font-weight: bold; font-size: 10pt; }

        .photo-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 5px; margin-bottom: 15px; }
        .photo-item { border: 1px solid #000; height: 90px; display: flex; align-items: center; justify-content: center; overflow: hidden; background: #fafafa; }
        .photo-item img { width: 100%; height: 100%; object-cover: cover; }
        .no-photo { font-size: 7pt; color: #999; text-transform: uppercase; }

        .photo-after { text-align: center; margin-top: 10px; }
        .photo-after img { max-width: 80%; max-height: 200px; border: 1px solid #000; }
        
        .footer { margin-top: 30px; display: flex; justify-content: space-between; }
        .signature { text-align: center; width: 40%; }
        .sig-space { height: 60px; }
        .sig-name { font-weight: bold; text-decoration: underline; text-transform: uppercase; }
        
        @media print {
            .no-print { display: none; }
            button { display: none; }
        }
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
            <h2>Dinas Perumahan Rakyat dan Kawasan Permukiman</h2>
            <p>Jl. Persatuan Raya No. 123, Kabupaten Sinjai - Sulawesi Selatan</p>
        </div>
        <div style="width: 70px;"></div>
    </div>

    <div class="title">Laporan Realisasi Bantuan Perumahan</div>

    <div class="section-title">Informasi Penerima & Bantuan</div>
    <table>
        <tr>
            <td class="label">Nama Penerima</td><td class="separator">:</td><td><?= $bansos['nama_penerima'] ?></td>
        </tr>
        <tr>
            <td class="label">NIK</td><td class="separator">:</td><td><?= $bansos['nik'] ?></td>
        </tr>
        <tr>
            <td class="label">Wilayah / Desa</td><td class="separator">:</td><td><?= $bansos['desa'] ?></td>
        </tr>
        <tr>
            <td class="label">Sumber Dana</td><td class="separator">:</td><td><?= $bansos['sumber_dana'] ?> (Tahun <?= $bansos['tahun_anggaran'] ?>)</td>
        </tr>
        <tr>
            <td class="label">Keterangan</td><td class="separator">:</td><td><?= $bansos['keterangan'] ?: '-' ?></td>
        </tr>
    </table>

    <div class="section-title">Koordinat Lokasi Realisasi</div>
    <?php
        $wkt = $bansos['wkt_realisasi'] ?? '';
        $lat = '-'; $lng = '-';
        if (preg_match('/POINT\s*\(\s*([-\d.]+)\s+([-\d.]+)\s*\)/i', $wkt, $matches)) {
            $lng = $matches[1]; $lat = $matches[2];
        }
    ?>
    <div class="coords-box">
        <div class="coords-item">
            <div class="coords-label">Garis Lintang (Latitude)</div>
            <div class="coords-value"><?= $lat ?></div>
        </div>
        <div class="coords-item">
            <div class="coords-label">Garis Bujur (Longitude)</div>
            <div class="coords-value"><?= $lng ?></div>
        </div>
    </div>

    <div class="section-title">Dokumentasi Kondisi Awal (Before)</div>
    <div class="photo-grid">
        <?php if ($rumah): ?>
            <?php foreach(['foto_depan', 'foto_samping', 'foto_belakang', 'foto_dalam'] as $f): ?>
                <div class="photo-item">
                    <?php if (!empty($rumah[$f])): ?>
                        <img src="<?= base_url('uploads/rtlh/'.$rumah[$f]) ?>">
                    <?php else: ?>
                        <span class="no-photo">Tidak Ada Foto</span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php elseif (!empty($bansos['foto_before'])): ?>
            <div class="photo-item" style="grid-column: span 4; height: 150px;">
                <img src="<?= base_url('uploads/rtlh/'.$bansos['foto_before']) ?>" style="object-fit: contain;">
            </div>
        <?php else: ?>
            <div class="photo-item" style="grid-column: span 4;"> <span class="no-photo">Foto Before Tidak Tersedia</span> </div>
        <?php endif; ?>
    </div>

    <div class="section-title">Dokumentasi Hasil Pekerjaan (After)</div>
    <div class="photo-after">
        <?php if (!empty($bansos['foto_after'])): ?>
            <img src="<?= base_url('uploads/rtlh/'.$bansos['foto_after']) ?>">
        <?php else: ?>
            <p style="font-size: 8pt; color: #999;">FOTO AFTER BELUM TERSEDIA</p>
        <?php endif; ?>
    </div>

    <div class="footer">
        <div class="signature">
            <p>Mengetahui,<br>Kepala Bidang Perumahan</p>
            <div class="sig-space"></div>
            <p class="sig-name">..........................................</p>
            <p>NIP. ..........................................</p>
        </div>
        <div class="signature">
            <p>Sinjai, <?= date('d F Y') ?><br>Verifikator Lapangan</p>
            <div class="sig-space"></div>
            <p class="sig-name"><?= session()->get('username') ?></p>
            <p>ID Sistem: SIBARUKI-<?= str_pad($bansos['id'], 4, '0', STR_PAD_LEFT) ?></p>
        </div>
    </div>

    <script>
        // Auto trigger print when loaded if needed, 
        // but better keep manual for preview.
    </script>
</body>
</html>