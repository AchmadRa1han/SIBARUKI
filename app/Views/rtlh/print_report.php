<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Komprehensif RTLH - <?= $penerima['nama_kepala_keluarga'] ?></title>
    <link rel="shortcut icon" type="image/png" href="<?= base_url('sinjai.png') ?>">
    <style>
        @page { size: A4; margin: 1.5cm 1cm; }
        body { font-family: 'Arial', sans-serif; font-size: 8pt; line-height: 1.4; color: #000; background: #fff; margin: 0; padding: 0; }
        
        .kop-surat { display: flex; align-items: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 15px; }
        .logo { width: 65px; height: auto; }
        .kop-text { flex: 1; text-align: center; }
        .kop-text h1 { font-size: 12pt; margin: 0; text-transform: uppercase; }
        .kop-text h2 { font-size: 14pt; margin: 0; text-transform: uppercase; font-weight: 900; }
        .kop-text p { font-size: 7pt; margin: 3px 0 0; italic; }

        .title { text-align: center; text-transform: uppercase; font-weight: bold; font-size: 11pt; margin-bottom: 20px; text-decoration: underline; }

        .section-header { background: #333; color: #fff; padding: 5px 10px; font-weight: bold; text-transform: uppercase; font-size: 8pt; margin-top: 15px; margin-bottom: 10px; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; table-layout: fixed; }
        table td { padding: 4px 6px; border: 1px solid #000; vertical-align: top; word-wrap: break-word; }
        .label { background: #f2f2f2; font-weight: bold; width: 25%; }
        .val { width: 25%; }

        .tech-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 0; border: 1px solid #000; }
        .tech-item { border: 0.5px solid #000; padding: 5px; display: flex; justify-content: space-between; align-items: center; }
        .tech-label { font-weight: bold; font-size: 7pt; }
        .tech-val { font-weight: bold; color: #333; }

        .page-break { page-break-before: always; }

        .photo-container { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin-top: 10px; }
        .photo-box { border: 1px solid #000; padding: 5px; text-align: center; }
        .photo-box img { width: 100%; height: 200px; object-fit: cover; }
        .photo-label { font-weight: bold; margin-top: 5px; text-transform: uppercase; font-size: 7pt; }

        .footer-note { margin-top: 20px; font-size: 7pt; color: #666; text-align: center; border-top: 1px solid #ccc; padding-top: 5px; }

        @media print {
            .no-print { display: none; }
            body { -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="background: #333; padding: 10px; text-align: center; color: white;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer; font-weight: bold; background: #2563eb; color: white; border: none; border-radius: 5px;">CETAK LAPORAN (PDF/PRINTER)</button>
        <button onclick="window.history.back()" style="padding: 10px 20px; cursor: pointer; margin-left: 10px;">KEMBALI</button>
    </div>

    <!-- PAGE 1 -->
    <div class="kop-surat">
        <img src="<?= base_url('sinjai.png') ?>" class="logo">
        <div class="kop-text">
            <h1>Pemerintah Kabupaten Sinjai</h1>
            <h2>Dinas Perumahan Kawasan Permukiman dan Pertanahan Kab. Sinjai</h2>
            <p>Jl. Persatuan Raya No. 123, Kabupaten Sinjai - Sulawesi Selatan</p>
        </div>
        <div style="width: 65px;"></div>
    </div>

    <div class="title">LAPORAN HASIL SURVEI RUMAH TIDAK LAYAK HUNI (RTLH)</div>

    <div class="section-header">I. Data Personal & Kependudukan</div>
    <table>
        <tr>
            <td class="label">Nama Kepala Keluarga</td><td class="val"><?= $penerima['nama_kepala_keluarga'] ?></td>
            <td class="label">NIK Pemilik</td><td class="val"><?= $rumah['nik_pemilik'] ?></td>
        </tr>
        <tr>
            <td class="label">Nomor Kartu Keluarga</td><td class="val"><?= $penerima['no_kk'] ?></td>
            <td class="label">Jenis Kelamin</td><td class="val"><?= $penerima['jenis_kelamin'] == 'L' ? 'Laki-Laki' : 'Perempuan' ?></td>
        </tr>
        <tr>
            <td class="label">Tempat, Tgl Lahir</td><td class="val"><?= $penerima['tempat_lahir'] ?>, <?= $penerima['tanggal_lahir'] ?></td>
            <td class="label">Jumlah Anggota Kel.</td><td class="val"><?= $penerima['jumlah_anggota_keluarga'] ?> Orang</td>
        </tr>
        <tr>
            <td class="label">Pendidikan Terakhir</td><td class="val"><?= $penerima['nama_pendidikan'] ?: '-' ?></td>
            <td class="label">Pekerjaan Utama</td><td class="val"><?= $penerima['nama_pekerjaan'] ?: '-' ?></td>
        </tr>
        <tr>
            <td class="label">Penghasilan / Bulan</td><td class="val"><?= $penerima['penghasilan_per_bulan'] ?></td>
            <td class="label">Status Survei</td><td class="val"><?= $rumah['status_bantuan'] ?></td>
        </tr>
    </table>

    <div class="section-header">II. Data Hunian & Informasi Spasial</div>
    <table>
        <tr>
            <td class="label">Alamat Detail</td><td colspan="3"><?= $rumah['alamat_detail'] ?: '-' ?></td>
        </tr>
        <tr>
            <td class="label">Desa / Kelurahan</td><td class="val"><?= $rumah['desa'] ?></td>
            <td class="label">ID Desa (Kemendagri)</td><td class="val"><?= $rumah['desa_id'] ?: '-' ?></td>
        </tr>
        <tr>
            <td class="label">Kepemilikan Rumah</td><td class="val"><?= $rumah['kepemilikan_rumah'] ?></td>
            <td class="label">Kepemilikan Tanah</td><td class="val"><?= $rumah['kepemilikan_tanah'] ?></td>
        </tr>
        <tr>
            <td class="label">Jenis Kawasan</td><td class="val"><?= $rumah['jenis_kawasan'] ?></td>
            <td class="label">Fungsi Ruang</td><td class="val"><?= $rumah['fungsi_ruang'] ?></td>
        </tr>
        <tr>
            <td class="label">Luas Rumah (m2)</td><td class="val"><?= $rumah['luas_rumah_m2'] ?> m2</td>
            <td class="label">Luas Lahan (m2)</td><td class="val"><?= $rumah['luas_lahan_m2'] ?> m2</td>
        </tr>
        <tr>
            <td class="label">Desil Nasional</td><td class="val"><?= $rumah['desil_nasional'] ?: '-' ?></td>
            <td class="label">Aset di Lokasi Lain</td><td class="val"><?= $rumah['aset_rumah_di_lokasi_lain'] ?: '-' ?></td>
        </tr>
    </table>

    <div class="section-header">III. Fasilitas & Sanitasi</div>
    <table>
        <tr>
            <td class="label">Sumber Air Minum</td><td class="val"><?= $rumah['sumber_air_minum'] ?></td>
            <td class="label">Jarak SAM ke TPA Tinja</td><td class="val"><?= $rumah['jarak_sam_ke_tpa_tinja'] ?></td>
        </tr>
        <tr>
            <td class="label">Kamar Mandi & Jamban</td><td class="val"><?= $rumah['kamar_mandi_dan_jamban'] ?></td>
            <td class="label">Jenis Jamban/Kloset</td><td class="val"><?= $rumah['jenis_jamban_kloset'] ?></td>
        </tr>
        <tr>
            <td class="label">Sumber Penerangan</td><td class="val"><?= $rumah['sumber_penerangan'] ?></td>
            <td class="label">ID Pelanggan Listrik</td><td class="val"><?= $rumah['sumber_penerangan_detail'] ?: '-' ?></td>
        </tr>
    </table>

    <div class="section-header">IV. Penilaian Teknis Kondisi Fisik</div>
    <div class="tech-grid">
        <div class="tech-item"><span class="tech-label">PONDASI</span><span class="tech-val"><?= $kondisi['nm_st_pondasi'] ?></span></div>
        <div class="tech-item"><span class="tech-label">KOLOM</span><span class="tech-val"><?= $kondisi['nm_st_kolom'] ?></span></div>
        <div class="tech-item"><span class="tech-label">BALOK</span><span class="tech-val"><?= $kondisi['nm_st_balok'] ?></span></div>
        <div class="tech-item"><span class="tech-label">SLOOF</span><span class="tech-val"><?= $kondisi['nm_st_sloof'] ?></span></div>
        <div class="tech-item"><span class="tech-label">RANGKA ATAP</span><span class="tech-val"><?= $kondisi['nm_st_rangka_atap'] ?></span></div>
        <div class="tech-item"><span class="tech-label">PLAFON</span><span class="tech-val"><?= $kondisi['nm_st_plafon'] ?></span></div>
        <div class="tech-item"><span class="tech-label">JENDELA</span><span class="tech-val"><?= $kondisi['nm_st_jendela'] ?></span></div>
        <div class="tech-item"><span class="tech-label">VENTILASI</span><span class="tech-val"><?= $kondisi['nm_st_ventilasi'] ?></span></div>
        <div class="tech-item"><span class="tech-label">MATERIAL LANTAI</span><span class="tech-val"><?= $kondisi['nm_mat_lantai'] ?></span></div>
        <div class="tech-item"><span class="tech-label">KONDISI LANTAI</span><span class="tech-val"><?= $kondisi['nm_st_lantai'] ?></span></div>
        <div class="tech-item"><span class="tech-label">MATERIAL DINDING</span><span class="tech-val"><?= $kondisi['nm_mat_dinding'] ?></span></div>
        <div class="tech-item"><span class="tech-label">KONDISI DINDING</span><span class="tech-val"><?= $kondisi['nm_st_dinding'] ?></span></div>
        <div class="tech-item"><span class="tech-label">MATERIAL ATAP</span><span class="tech-val"><?= $kondisi['nm_mat_atap'] ?></span></div>
        <div class="tech-item"><span class="tech-label">KONDISI ATAP</span><span class="tech-val"><?= $kondisi['nm_st_atap'] ?></span></div>
    </div>

    <!-- PAGE 2 -->
    <div class="page-break"></div>
    <div class="section-header">V. Dokumentasi Visual (Kondisi Eksisting)</div>
    <div class="photo-container">
        <div class="photo-box">
            <?php if(!empty($rumah['foto_depan'])): ?><img src="<?= base_url('uploads/rtlh/'.$rumah['foto_depan']) ?>"><?php else: ?><div style="height:200px; background:#eee;">NO PHOTO</div><?php endif; ?>
            <div class="photo-label">Tampak Depan</div>
        </div>
        <div class="photo-box">
            <?php if(!empty($rumah['foto_samping'])): ?><img src="<?= base_url('uploads/rtlh/'.$rumah['foto_samping']) ?>"><?php else: ?><div style="height:200px; background:#eee;">NO PHOTO</div><?php endif; ?>
            <div class="photo-label">Tampak Samping Kiri</div>
        </div>
        <div class="photo-box">
            <?php if(!empty($rumah['foto_belakang'])): ?><img src="<?= base_url('uploads/rtlh/'.$rumah['foto_belakang']) ?>"><?php else: ?><div style="height:200px; background:#eee;">NO PHOTO</div><?php endif; ?>
            <div class="photo-label">Tampak Belakang</div>
        </div>
        <div class="photo-box">
            <?php if(!empty($rumah['foto_dalam'])): ?><img src="<?= base_url('uploads/rtlh/'.$rumah['foto_dalam']) ?>"><?php else: ?><div style="height:200px; background:#eee;">NO PHOTO</div><?php endif; ?>
            <div class="photo-label">Tampak Samping Kanan</div>
        </div>
    </div>

    <div class="section-header">VI. Lokasi Geospasial</div>
    <div style="border:1px solid #000; padding:10px; text-align:center;">
        <p style="font-size:10pt; font-family:monospace; font-weight:bold;"><?= $rumah['wkt'] ?: 'KOORDINAT TIDAK TERSEDIA' ?></p>
        <p style="font-size:7pt; color:#666; margin-top:5px;">Data koordinat diproses secara otomatis melalui modul GIS SIBARUKI Sinjai.</p>
    </div>

    <div class="footer-note">
        Laporan ini dihasilkan secara otomatis oleh Sistem Informasi SIBARUKI pada <?= date('d/m/Y H:i') ?>.<br>
        Seluruh data di atas adalah hasil survei lapangan yang telah divalidasi kebenarannya.<br>
        ID Referensi Sistem: SRV-<?= str_pad($rumah['id_survei'], 5, '0', STR_PAD_LEFT) ?>
    </div>

</body>
</html>