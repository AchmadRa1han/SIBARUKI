<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<!-- Library for PDF Download -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<?php
    function getStatusBadge($status) {
        $status = strtoupper($status ?? '');
        if (str_contains($status, 'TIDAK LAYAK') || str_contains($status, 'KURANG LAYAK') || str_contains($status, 'RUSAK BERAT') || str_contains($status, 'RUSAK SEDANG')) {
            return 'bg-red-50 dark:bg-red-950/30 text-red-700 dark:text-red-400 border-red-100 dark:border-red-900';
        } elseif (str_contains($status, 'RUSAK RINGAN') || str_contains($status, 'MENUJU') || str_contains($status, 'AGAK')) {
            return 'bg-amber-50 dark:bg-amber-950/30 text-amber-700 dark:text-amber-400 border-amber-100 dark:border-amber-900';
        } elseif (str_contains($status, 'LAYAK') || str_contains($status, 'BAIK') || str_contains($status, 'SANGAT')) {
            return 'bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400 border-emerald-100 dark:border-emerald-900';
        }
        return 'bg-slate-50 dark:bg-slate-800 text-slate-400 dark:text-slate-500 border-slate-100 dark:border-slate-700';
    }
?>

<div id="report-content" class="max-w-6xl mx-auto space-y-8 pb-24 text-slate-900 dark:text-slate-200">
    
    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-slate-900 p-8 rounded-[2rem] border dark:border-slate-800 shadow-sm no-print transition-colors duration-300">
        <div class="flex items-center space-x-5">
            <div class="p-4 bg-blue-900 dark:bg-blue-700 rounded-2xl text-white shadow-lg">
                <i data-lucide="clipboard-list" class="w-10 h-10"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-blue-950 dark:text-white tracking-tight">Data Survei Lengkap RTLH</h1>
                <div class="flex items-center space-x-3 mt-1">
                    <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">ID Registrasi</span>
                    <span class="px-3 py-1 bg-blue-950 dark:bg-blue-800 text-white rounded-lg font-mono text-sm font-bold shadow-lg">SRV-<?= str_pad($rumah['id_survei'] ?? '0', 5, '0', STR_PAD_LEFT) ?></span>
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <?php if (has_permission('export_data')) : ?>
            <button onclick="window.print()" class="px-5 py-3 bg-white dark:bg-slate-800 border-2 border-blue-900/10 dark:border-slate-700 text-slate-600 dark:text-slate-300 rounded-2xl text-sm font-bold hover:bg-slate-50 dark:hover:bg-slate-700 transition-all flex items-center shadow-sm">
                <i data-lucide="printer" class="w-4 h-4 mr-2"></i> Cetak
            </button>
            <button onclick="downloadPDF()" class="px-5 py-3 bg-white dark:bg-slate-800 border-2 border-blue-900/10 dark:border-slate-700 text-blue-900 dark:text-blue-400 rounded-2xl text-sm font-bold hover:bg-blue-50 dark:hover:bg-slate-700 transition-all flex items-center shadow-sm">
                <i data-lucide="download" class="w-4 h-4 mr-2"></i> Download PDF
            </button>
            <?php endif; ?>
            <?php if (has_permission('edit_rtlh')) : ?>
                <?php if (($rumah['status_bantuan'] ?? 'Belum Menerima') == 'Belum Menerima') : ?>
                <button onclick="openModalTuntas()" class="px-6 py-3 bg-emerald-600 dark:bg-emerald-700 text-white rounded-2xl text-sm font-bold hover:bg-emerald-700 dark:hover:bg-emerald-600 shadow-xl shadow-emerald-900/20 transition-all flex items-center">
                    <i data-lucide="check-circle" class="w-4 h-4 mr-2"></i> Tandai Tuntas Bansos
                </button>
                <?php else: ?>
                <div class="px-6 py-3 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-2xl text-xs font-black uppercase tracking-widest border border-emerald-100 dark:border-emerald-800 flex items-center">
                    <i data-lucide="award" class="w-4 h-4 mr-2"></i> Telah Dibantu (<?= $rumah['tahun_bansos'] ?>)
                </div>
                <?php endif; ?>
            <a href="<?= base_url('rtlh/edit/' . ($rumah['id_survei'] ?? '')) ?>" class="px-8 py-3 bg-blue-900 dark:bg-blue-700 text-white rounded-2xl text-sm font-bold hover:bg-blue-950 dark:hover:bg-blue-600 shadow-xl shadow-blue-900/30 transition-all flex items-center">
                <i data-lucide="edit-3" class="w-4 h-4 mr-2"></i> Perbarui Data
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- BAGIAN 1: IDENTITAS PENERIMA -->
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-300">
        <div class="p-6 border-b dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 flex items-center justify-between">
            <h3 class="font-bold text-blue-950 dark:text-blue-400 uppercase tracking-[0.2em] text-xs flex items-center">
                <i data-lucide="user" class="w-4 h-4 mr-2 text-blue-900 dark:text-blue-400"></i> I. Identitas Lengkap Pemilik
            </h3>
        </div>
        <div class="p-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-y-10 gap-x-8">
            <div class="lg:col-span-2">
                <p class="text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest opacity-80">Nama Lengkap Kepala Keluarga</p>
                <p class="text-2xl font-black text-slate-900 dark:text-white leading-tight"><?= $penerima['nama_kepala_keluarga'] ?? '-' ?></p>
            </div>
            <div>
                <p class="text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest opacity-80">NIK (16 Digit)</p>
                <p class="text-sm font-mono font-bold text-blue-900 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/50 px-3 py-1 rounded-lg border border-blue-100 dark:border-blue-900 w-fit"><?= $penerima['nik'] ?? '-' ?></p>
            </div>
            <div>
                <p class="text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest opacity-80">Nomor KK</p>
                <p class="text-sm font-mono font-bold text-blue-900 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/50 px-3 py-1 rounded-lg border border-blue-100 dark:border-blue-900 w-fit"><?= $penerima['no_kk'] ?? '-' ?></p>
            </div>
            <div>
                <p class="text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest opacity-80">Tempat, Tanggal Lahir</p>
                <p class="text-sm font-bold text-slate-800 dark:text-slate-300"><?= $penerima['tempat_lahir'] ?? '-' ?>, <?= $penerima['tanggal_lahir'] ?? '-' ?></p>
            </div>
            <div>
                <p class="text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest opacity-80">Umur</p>
                <p class="text-sm font-bold text-slate-800 dark:text-slate-300">
                    <?php 
                        if (!empty($penerima['tanggal_lahir'])) {
                            $birthDate = new DateTime($penerima['tanggal_lahir']);
                            echo $birthDate->diff(new DateTime('today'))->y . ' Tahun';
                        } else echo '-';
                    ?>
                </p>
            </div>
            <div>
                <p class="text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest opacity-80">Jenis Kelamin</p>
                <p class="text-sm font-bold text-slate-800 dark:text-slate-300"><?= ($penerima['jenis_kelamin'] ?? '') == 'L' ? 'Laki-laki' : 'Perempuan' ?></p>
            </div>
            <div>
                <p class="text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest opacity-80">Pendidikan</p>
                <p class="text-sm font-bold text-slate-800 dark:text-slate-300"><?= $ref[$penerima['pendidikan_id'] ?? ''] ?? '-' ?></p>
            </div>
            <div>
                <p class="text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest opacity-80">Pekerjaan Utama</p>
                <p class="text-sm font-bold text-slate-800 dark:text-slate-300"><?= $ref[$penerima['pekerjaan_id'] ?? ''] ?? '-' ?></p>
            </div>
            <div>
                <p class="text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest opacity-80">Penghasilan / Bulan</p>
                <p class="text-sm font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/30 px-2 py-0.5 rounded-md w-fit"><?= $penerima['penghasilan_per_bulan'] ?? '-' ?></p>
            </div>
            <div>
                <p class="text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest opacity-80">Jml Anggota Keluarga</p>
                <p class="text-sm font-bold text-slate-800 dark:text-slate-300"><?= $penerima['jumlah_anggota_keluarga'] ?? '0' ?> Orang</p>
            </div>
        </div>
    </div>

    <!-- BAGIAN 2: PROFIL RUMAH & LAHAN -->
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-300">
        <div class="p-6 border-b dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 flex items-center justify-between">
            <h3 class="font-bold text-blue-950 dark:text-blue-400 uppercase tracking-[0.2em] text-xs flex items-center">
                <i data-lucide="home" class="w-4 h-4 mr-2 text-blue-900 dark:text-blue-400"></i> II. Lokasi, Lahan & Aset
            </h3>
        </div>
        <div class="p-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-y-10 gap-x-8">
            <div class="lg:col-span-2">
                <p class="text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest opacity-80">Alamat Lengkap Rumah</p>
                <p class="text-sm font-medium text-slate-800 dark:text-slate-300 leading-relaxed"><?= $rumah['alamat_detail'] ?? '-' ?></p>
            </div>
            <div>
                <p class="text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest opacity-80">Desa / Kelurahan</p>
                <p class="text-sm font-bold text-slate-800 dark:text-slate-300"><?= $rumah['desa'] ?? '-' ?> <span class="text-[10px] text-slate-400 dark:text-slate-500 font-normal ml-1">(ID: <?= $rumah['desa_id'] ?? '-' ?>)</span></p>
            </div>
            <div>
                <p class="text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest opacity-80">Jenis Kawasan</p>
                <p class="text-sm font-bold text-slate-800 dark:text-slate-300 uppercase tracking-tighter"><?= $rumah['jenis_kawasan'] ?? '-' ?></p>
            </div>
            <div class="bg-blue-900 dark:bg-blue-800 text-white p-5 rounded-3xl shadow-lg">
                <p class="text-[9px] font-black text-blue-200 uppercase mb-1 tracking-[0.2em]">Luas Rumah</p>
                <p class="text-2xl font-black"><?= $rumah['luas_rumah_m2'] ?? '0' ?><span class="text-xs font-bold ml-1 opacity-60 italic">m²</span></p>
            </div>
            <div class="bg-blue-900 dark:bg-blue-800 text-white p-5 rounded-3xl shadow-lg">
                <p class="text-[9px] font-black text-blue-200 uppercase mb-1 tracking-[0.2em]">Luas Lahan</p>
                <p class="text-2xl font-black"><?= $rumah['luas_lahan_m2'] ?? '0' ?><span class="text-xs font-bold ml-1 opacity-60 italic">m²</span></p>
            </div>
            <div>
                <p class="text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest opacity-80">Jml Penghuni Rumah</p>
                <p class="text-sm font-bold text-slate-800 dark:text-slate-300"><?= $rumah['jumlah_penghuni_jiwa'] ?? '0' ?> Jiwa</p>
            </div>
            <div>
                <p class="text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest opacity-80">Fungsi Ruang</p>
                <p class="text-sm font-bold text-slate-800 dark:text-slate-300"><?= $rumah['fungsi_ruang'] ?? '-' ?></p>
            </div>
            <div>
                <p class="text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest opacity-80">Kepemilikan Rumah</p>
                <p class="text-sm font-bold text-slate-800 dark:text-slate-300"><?= $rumah['kepemilikan_rumah'] ?? '-' ?></p>
            </div>
            <div>
                <p class="text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest opacity-80">Kepemilikan Tanah</p>
                <p class="text-sm font-bold text-slate-800 dark:text-slate-300"><?= $rumah['kepemilikan_tanah'] ?? '-' ?></p>
            </div>
            <div>
                <p class="text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest opacity-80">Aset Rumah Lain</p>
                <p class="text-sm font-bold text-slate-800 dark:text-slate-300"><?= $rumah['aset_rumah_di_lokasi_lain'] ?? '-' ?></p>
            </div>
            <div>
                <p class="text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest opacity-80">Bantuan Perumahan</p>
                <p class="text-sm font-bold text-slate-800 dark:text-slate-300"><?= $rumah['bantuan_perumahan'] ?? '-' ?></p>
            </div>
            <div>
                <p class="text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest opacity-80">Status Backlog</p>
                <p class="text-sm font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-2 py-0.5 rounded-md w-fit italic"><?= $rumah['status_backlog'] ?: 'Tidak Ada' ?></p>
            </div>
            <div>
                <p class="text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest opacity-80">Desil Nasional</p>
                <p class="text-sm font-black text-blue-950 dark:text-white"><?= $rumah['desil_nasional'] ?: '-' ?></p>
            </div>
        </div>
    </div>

    <!-- BAGIAN 3: FASILITAS & SANITASI -->
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-300">
        <div class="p-6 border-b dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 flex items-center justify-between">
            <h3 class="font-bold text-blue-950 dark:text-blue-400 uppercase tracking-[0.2em] text-xs flex items-center">
                <i data-lucide="droplets" class="w-4 h-4 mr-2 text-blue-900 dark:text-blue-400"></i> III. Fasilitas & Utilitas Sanitasi
            </h3>
        </div>
        <div class="p-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-y-10 gap-x-8">
            <div class="lg:col-span-2">
                <p class="text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest opacity-80">Sumber Penerangan</p>
                <p class="text-sm font-bold text-slate-800 dark:text-slate-300 italic"><?= $rumah['sumber_penerangan'] ?? '-' ?> <span class="text-blue-900 dark:text-blue-400 ml-2 font-black underline decoration-blue-900/20"><?= $rumah['sumber_penerangan_detail'] ?? '' ?></span></p>
            </div>
            <div>
                <p class="text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest opacity-80">Sumber Air Minum (SAM)</p>
                <p class="text-sm font-bold text-slate-800 dark:text-slate-300"><?= $rumah['sumber_air_minum'] ?? '-' ?></p>
            </div>
            <div>
                <p class="text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest opacity-80">Jarak SAM ke TPA Tinja</p>
                <p class="text-sm font-bold text-slate-800 dark:text-slate-300"><?= $rumah['jarak_sam_ke_tpa_tinja'] ?? '-' ?></p>
            </div>
            <div>
                <p class="text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest opacity-80">Kamar Mandi & Jamban</p>
                <p class="text-sm font-bold text-slate-800 dark:text-slate-300"><?= $rumah['kamar_mandi_dan_jamban'] ?? '-' ?></p>
            </div>
            <div>
                <p class="text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest opacity-80">Jenis Jamban / Kloset</p>
                <p class="text-sm font-bold text-slate-800 dark:text-slate-300"><?= $rumah['jenis_jamban_kloset'] ?? '-' ?></p>
            </div>
            <div>
                <p class="text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest opacity-80">Jenis TPA Tinja</p>
                <p class="text-sm font-bold text-slate-800 dark:text-slate-300"><?= $rumah['jenis_tpa_tinja'] ?? '-' ?></p>
            </div>
            <div class="lg:col-span-2">
                <p class="text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest opacity-80">Lokasi Koordinat</p>
                <p class="text-xs font-mono font-bold text-blue-900 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/50 p-2 rounded-lg border border-blue-100 dark:border-blue-900 italic w-fit"><?= $rumah['lokasi_koordinat'] ?? 'Point(0 0)' ?></p>
            </div>
        </div>
    </div>

    <!-- BAGIAN 4: PENILAIAN TEKNIS -->
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-300">
        <div class="p-6 border-b dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 flex items-center justify-between">
            <h3 class="font-bold text-blue-950 dark:text-blue-400 uppercase tracking-[0.2em] text-xs flex items-center">
                <i data-lucide="shield-check" class="w-4 h-4 mr-2 text-blue-900 dark:text-blue-400"></i> IV. Penilaian Teknis Fisik Bangunan
            </h3>
        </div>
        <div class="p-10 space-y-12">
            <!-- STRUKTUR UTAMA -->
            <div>
                <h4 class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-[0.3em] mb-6 flex items-center"><span class="w-8 h-1 bg-blue-900 dark:bg-blue-600 mr-3"></span> Komponen Struktur Utama</h4>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <?php 
                        $struk = ['Pondasi', 'Kolom', 'Balok', 'Sloof'];
                        foreach($struk as $s):
                            $field = 'st_' . strtolower($s);
                            $val = $ref[$kondisi[$field] ?? ''] ?? 'N/A';
                    ?>
                    <div class="p-5 border-2 border-blue-900/5 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-950/30 rounded-3xl transition-colors duration-300">
                        <p class="text-[9px] font-black text-blue-900 dark:text-blue-500 uppercase tracking-wider mb-4 opacity-70"><?= $s ?></p>
                        <span class="px-4 py-1.5 rounded-xl text-[10px] font-black border uppercase shadow-sm <?= getStatusBadge($val) ?>">
                            <?= $val ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- MATERIAL & KONDISI PENUTUP -->
            <div>
                <h4 class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-[0.3em] mb-6 flex items-center"><span class="w-8 h-1 bg-blue-900 dark:bg-blue-600 mr-3"></span> Material & Kondisi Penutup</h4>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <?php 
                        $mats = [
                            ['label' => 'Penutup Atap', 'mat' => $ref[$kondisi['mat_atap'] ?? ''] ?? '-', 'st' => $ref[$kondisi['st_atap'] ?? ''] ?? '-', 'icon' => 'tent'],
                            ['label' => 'Dinding Utama', 'mat' => $ref[$kondisi['mat_dinding'] ?? ''] ?? '-', 'st' => $ref[$kondisi['st_dinding'] ?? ''] ?? '-', 'icon' => 'layers'],
                            ['label' => 'Lantai Utama', 'mat' => $ref[$kondisi['mat_lantai'] ?? ''] ?? '-', 'st' => $ref[$kondisi['st_lantai'] ?? ''] ?? '-', 'icon' => 'grid-3x3'],
                        ];
                        foreach($mats as $m):
                    ?>
                    <div class="p-6 border-2 border-blue-900/5 dark:border-slate-800 rounded-[2rem] space-y-5 shadow-sm transition-colors duration-300">
                        <div class="flex items-center justify-between">
                            <span class="text-[9px] font-black text-blue-900 dark:text-blue-500 uppercase tracking-widest"><?= $m['label'] ?></span>
                            <i data-lucide="<?= $m['icon'] ?>" class="w-5 h-5 text-blue-900/20 dark:text-blue-400/20"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-blue-900 dark:text-blue-500 uppercase mb-1 opacity-60">Material</p>
                            <p class="text-lg font-black text-slate-800 dark:text-slate-200 italic uppercase"><?= $m['mat'] ?></p>
                        </div>
                        <div>
                            <span class="inline-block w-full text-center py-2 rounded-xl text-[10px] font-black border uppercase shadow-sm <?= getStatusBadge($m['st']) ?>">
                                <?= $m['st'] ?>
                            </span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- KOMPONEN PENDUKUNG -->
            <div>
                <h4 class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-[0.3em] mb-6 flex items-center"><span class="w-8 h-1 bg-blue-900 dark:bg-blue-600 mr-3"></span> Komponen Pendukung</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <?php 
                        $supp = [
                            ['lbl' => 'Rangka Atap', 'val' => $ref[$kondisi['st_rangka_atap'] ?? ''] ?? 'N/A'],
                            ['lbl' => 'Plafon', 'val' => $ref[$kondisi['st_plafon'] ?? ''] ?? 'N/A'],
                            ['lbl' => 'Daun Jendela', 'val' => $ref[$kondisi['st_jendela'] ?? ''] ?? 'N/A'],
                            ['lbl' => 'Ventilasi', 'val' => $ref[$kondisi['st_ventilasi'] ?? ''] ?? 'N/A'],
                        ];
                        foreach($supp as $sp):
                    ?>
                    <div class="p-5 border-2 border-blue-900/5 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-950/30 rounded-3xl text-center transition-colors duration-300">
                        <p class="text-[9px] font-black text-blue-900 dark:text-blue-500 uppercase mb-3 tracking-wider opacity-70"><?= $sp['lbl'] ?></p>
                        <span class="px-3 py-1 rounded-lg text-[9px] font-black border uppercase shadow-sm <?= getStatusBadge($sp['val']) ?>">
                            <?= $sp['val'] ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="bg-blue-950 rounded-[2.5rem] p-8 text-white shadow-2xl relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-700">
                    <i data-lucide="info" class="w-32 h-32"></i>
                </div>
                <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-blue-400 mb-4">Informasi Sistem</h4>
                <div class="space-y-4 relative z-10">
                    <div class="flex justify-between items-center text-[10px]">
                        <span class="font-bold text-blue-300/60 uppercase">Dibuat Pada</span>
                        <span class="font-black"><?= !empty($rumah['created_at']) ? date('d/m/Y H:i', strtotime($rumah['created_at'])) : '-' ?></span>
                    </div>
                    <div class="flex justify-between items-center text-[10px]">
                        <span class="font-bold text-blue-300/60 uppercase">Pembaruan Terakhir</span>
                        <span class="font-black"><?= !empty($rumah['updated_at']) ? date('d/m/Y H:i', strtotime($rumah['updated_at'])) : '-' ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- MODAL TUNTAS BANSOS -->
    <div id="modal-tuntas" class="fixed inset-0 z-[10001] flex items-center justify-center p-4 hidden">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModalTuntas()"></div>
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-10 max-w-lg w-full relative z-10 shadow-2xl border border-slate-100 dark:border-slate-800 transition-all">
            <div class="flex flex-col items-center text-center mb-8">
                <div class="w-20 h-20 rounded-3xl mb-6 flex items-center justify-center bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 shadow-inner">
                    <i data-lucide="award" class="w-10 h-10"></i>
                </div>
                <h3 class="text-2xl font-black text-blue-950 dark:text-white uppercase tracking-tight mb-2">Konfirmasi Tuntas Bantuan</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 font-medium leading-relaxed">Pastikan rumah ini telah benar-benar menerima dan menyelesaikan perbaikan melalui program bantuan.</p>
            </div>

            <form action="<?= base_url('rtlh/mark-tuntas/' . $rumah['id_survei']) ?>" method="POST" class="space-y-6">
                <?= csrf_field() ?>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Tahun Anggaran Bantuan</label>
                    <input type="number" name="tahun_bansos" value="<?= date('Y') ?>" min="2000" max="2099" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-white outline-none transition-all font-bold" required>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Nama Program / Sumber Dana</label>
                    <input type="text" name="program_bansos" placeholder="Contoh: BSPS, Bansos Provinsi, APBD Kab..." class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-white outline-none transition-all font-bold" required>
                </div>
                
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeModalTuntas()" class="flex-1 px-6 py-4 bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-100 dark:hover:bg-slate-700 transition-all active:scale-95">Batal</button>
                    <button type="submit" class="flex-1 px-6 py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg shadow-emerald-600/20 active:scale-95">Konfirmasi Tuntas</button>
                </div>
            </form>
        </div>
    </div>

<script>
    lucide.createIcons();

    function openModalTuntas() {
        const modal = document.getElementById('modal-tuntas');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModalTuntas() {
        const modal = document.getElementById('modal-tuntas');
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    function downloadPDF() {
        const element = document.getElementById('report-content');
        const opt = {
            margin:       [10, 10],
            filename:     'Laporan_RTLH_<?= $penerima["nama_kepala_keluarga"] ?? "Data" ?>.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, useCORS: true, logging: false },
            jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' },
            pagebreak:    { mode: ['avoid-all', 'css', 'legacy'] }
        };

        // Tambahkan class khusus untuk PDF sebelum generate
        document.body.classList.add('is-exporting');
        
        // Log ke server
        fetch('<?= base_url("rtlh/log-export/" . ($rumah["id_survei"] ?? 0)) ?>', { method: 'POST' });

        html2pdf().set(opt).from(element).toPdf().get('pdf').then(function (pdf) {
            document.body.classList.remove('is-exporting');
        }).save();
    }
</script>

<style>
    @media print {
        aside, header, nav, .no-print { display: none !important; }
        main { margin: 0 !important; padding: 0 !important; width: 100% !important; }
        .shadow-sm, .shadow-lg, .shadow-xl { box-shadow: none !important; }
        .bg-blue-900, .bg-blue-950, .bg-emerald-50, .bg-blue-50 { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .max-w-6xl { max-width: 100% !important; }
    }

    /* Sembunyikan elemen tertentu saat proses export PDF */
    .is-exporting .no-print {
        display: none !important;
    }
    
    .print-block { display: none; }
    .is-exporting .print-block { display: flex !important; }
</style>
<?= $this->endSection() ?>
