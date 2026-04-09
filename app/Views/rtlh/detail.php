<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<!-- Library for PDF Download -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<!-- Leaflet Assets -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/wellknown@0.5.0/wellknown.js"></script>

<?php
    if (!function_exists('getStatusBadge')) {
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
    }
?>

<div id="report-content" class="max-w-7xl mx-auto space-y-6 pb-24 text-slate-900 dark:text-slate-200">
    
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-3 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 no-print">
        <a href="<?= base_url('dashboard') ?>" class="hover:text-blue-600 transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <a href="<?= base_url('rtlh') ?>" class="hover:text-blue-600 transition-colors">RTLH</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">Detail Penerima</span>
    </nav>

    <!-- MAIN HEADER -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 bg-white dark:bg-slate-900 p-8 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm no-print relative overflow-hidden">
        <div class="absolute top-0 right-0 w-48 h-48 bg-blue-600/5 rounded-full -mr-24 -mt-24 blur-3xl"></div>
        
        <div class="flex flex-col md:flex-row md:items-center gap-6 relative z-10">
            <a href="<?= base_url('rtlh') ?>" class="p-3 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl hover:bg-blue-600 hover:text-white transition-all active:scale-95" title="Kembali">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div class="flex flex-col md:flex-row md:items-center gap-6">
                <div class="w-16 h-16 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-600/20">
                    <i data-lucide="user" class="w-8 h-8" stroke-width="2"></i>
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="px-3 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full text-[9px] font-bold uppercase tracking-widest border border-blue-100 dark:border-blue-900/50">
                            SRV-<?= str_pad($rumah['id_survei'] ?? '0', 5, '0', STR_PAD_LEFT) ?>
                        </span>
                        <?php if (($rumah['status_bantuan'] ?? 'Belum Menerima') == 'Belum Menerima') : ?>
                            <span class="px-3 py-1 bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 rounded-full text-[9px] font-bold uppercase tracking-widest border border-rose-100 dark:border-rose-900/50">TARGET (RTLH)</span>
                        <?php else: ?>
                            <span class="px-3 py-1 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 rounded-full text-[9px] font-bold uppercase tracking-widest border border-emerald-100 dark:border-emerald-900/50">TUNTAS (RLH) - <?= $rumah['tahun_bansos'] ?></span>
                        <?php endif; ?>
                    </div>
                    <h1 class="text-2xl md:text-3xl font-bold text-blue-950 dark:text-white tracking-tighter uppercase mb-1"><?= $penerima['nama_kepala_keluarga'] ?? '-' ?></h1>
                    <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest flex items-center gap-2">
                        <i data-lucide="hash" class="w-3.5 h-3.5"></i> NIK: <?= $penerima['nik'] ?? '-' ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2 relative z-10">
            <?php if (has_permission('export_data')) : ?>
            <button onclick="downloadPDF()" class="p-3 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl hover:bg-blue-600 hover:text-white transition-all active:scale-95 shadow-sm" title="Download Laporan PDF">
                <i data-lucide="file-down" class="w-5 h-5"></i>
            </button>
            <?php endif; ?>
            
            <?php if (has_permission('edit_rtlh')) : ?>
                <?php if (($rumah['status_bantuan'] ?? 'Belum Menerima') == 'Belum Menerima') : ?>
                <button onclick="openModalTuntas()" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-[10px] font-bold uppercase tracking-widest shadow-lg shadow-emerald-600/10 transition-all active:scale-95 flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4"></i> Tandai Tuntas
                </button>
                <?php endif; ?>
                <a href="<?= base_url('rtlh/edit/' . ($rumah['id_survei'] ?? '')) ?>" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-[10px] font-bold uppercase tracking-widest shadow-lg shadow-blue-600/10 transition-all active:scale-95 flex items-center gap-2">
                    <i data-lucide="edit-3" class="w-4 h-4"></i> Perbarui Data
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- LEFT COLUMN: IDENTITAS & LOKASI -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Map Card -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden no-print">
                <div id="map-detail" class="w-full h-64 z-10" style="background: #ececec;"></div>
                <div class="p-4 bg-slate-50 dark:bg-slate-800/50 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-blue-600"></i>
                        <span id="coords-text" class="text-[10px] font-mono font-bold text-slate-500 uppercase tracking-widest">Memuat koordinat...</span>
                    </div>
                    <button onclick="focusMap()" class="text-[9px] font-bold text-blue-600 uppercase tracking-widest hover:underline">Focus Lokasi</button>
                </div>
            </div>

            <!-- I. Identitas Lengkap -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all duration-300">
                <div class="p-6 border-b dark:border-slate-800 flex items-center gap-3">
                    <div class="w-9 h-9 bg-slate-100 dark:bg-slate-800 rounded-lg flex items-center justify-center text-blue-600">
                        <i data-lucide="user" class="w-4.5 h-4.5"></i>
                    </div>
                    <div>
                        <h3 class="text-[11px] font-bold text-blue-950 dark:text-white uppercase tracking-[0.2em]">Identitas Pemilik</h3>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Data Personal & Kependudukan</p>
                    </div>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-10">
                    <div class="md:col-span-2">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Nomor Kartu Keluarga</p>
                        <p class="text-sm font-bold text-slate-700 dark:text-white uppercase tracking-wider"><?= $penerima['no_kk'] ?? '-' ?></p>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Tempat, Tanggal Lahir</p>
                        <p class="text-sm font-bold text-slate-700 dark:text-white uppercase"><?= $penerima['tempat_lahir'] ?? '-' ?>, <?= $penerima['tanggal_lahir'] ?? '-' ?></p>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Jenis Kelamin</p>
                        <p class="text-sm font-bold text-slate-700 dark:text-white uppercase"><?= ($penerima['jenis_kelamin'] ?? '') == 'L' ? 'Laki-laki' : 'Perempuan' ?></p>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Pendidikan</p>
                        <p class="text-sm font-bold text-slate-700 dark:text-white uppercase"><?= $ref[$penerima['pendidikan_id'] ?? ''] ?? '-' ?></p>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Pekerjaan Utama</p>
                        <p class="text-sm font-bold text-slate-700 dark:text-white uppercase"><?= $ref[$penerima['pekerjaan_id'] ?? ''] ?? '-' ?></p>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Penghasilan / Bulan</p>
                        <span class="px-2.5 py-1 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 rounded-lg text-xs font-bold border border-emerald-100 dark:border-emerald-900"><?= $penerima['penghasilan_per_bulan'] ?? '-' ?></span>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Jml Anggota Keluarga</p>
                        <p class="text-sm font-bold text-slate-700 dark:text-white uppercase"><?= $penerima['jumlah_anggota_keluarga'] ?? '0' ?> Orang</p>
                    </div>
                </div>
            </div>

            <!-- II. Lokasi & Aset -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all duration-300">
                <div class="p-6 border-b dark:border-slate-800 flex items-center gap-3">
                    <div class="w-9 h-9 bg-slate-100 dark:bg-slate-800 rounded-lg flex items-center justify-center text-blue-600">
                        <i data-lucide="map-pin" class="w-4.5 h-4.5"></i>
                    </div>
                    <div>
                        <h3 class="text-[11px] font-bold text-blue-950 dark:text-white uppercase tracking-[0.2em]">Lokasi & Aset Lahan</h3>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Informasi Geospasial & Kepemilikan</p>
                    </div>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-10">
                    <div class="md:col-span-2">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-2">Alamat Lengkap Rumah</p>
                        <p class="text-sm font-bold text-slate-700 dark:text-white uppercase leading-relaxed"><?= $rumah['alamat_detail'] ?? '-' ?></p>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Desa / Kelurahan</p>
                        <p class="text-sm font-bold text-blue-600 dark:text-blue-400 uppercase tracking-tighter"><?= $rumah['desa'] ?? '-' ?></p>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Jenis Kawasan</p>
                        <p class="text-sm font-bold text-slate-700 dark:text-white uppercase"><?= $rumah['jenis_kawasan'] ?? '-' ?></p>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Kepemilikan Rumah</p>
                        <p class="text-sm font-bold text-slate-700 dark:text-white uppercase"><?= $rumah['kepemilikan_rumah'] ?? '-' ?></p>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Kepemilikan Tanah</p>
                        <p class="text-sm font-bold text-slate-700 dark:text-white uppercase"><?= $rumah['kepemilikan_tanah'] ?? '-' ?></p>
                    </div>
                    <div class="bg-blue-600 p-5 rounded-2xl text-white shadow-lg shadow-blue-600/10">
                        <p class="text-[8px] font-bold text-blue-100 uppercase mb-1 tracking-[0.2em]">Luas Rumah</p>
                        <p class="text-2xl font-bold italic"><?= $rumah['luas_rumah_m2'] ?? '0' ?><span class="text-xs font-bold ml-1 opacity-60">m²</span></p>
                    </div>
                    <div class="bg-slate-900 dark:bg-slate-800 p-5 rounded-2xl text-white shadow-lg">
                        <p class="text-[8px] font-bold text-slate-400 uppercase mb-1 tracking-[0.2em]">Luas Lahan</p>
                        <p class="text-2xl font-bold italic"><?= $rumah['luas_lahan_m2'] ?? '0' ?><span class="text-xs font-bold ml-1 opacity-60">m²</span></p>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Status Backlog</p>
                        <p class="text-sm font-bold text-slate-700 dark:text-white uppercase"><?= $rumah['status_backlog'] ?: 'TIDAK' ?></p>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Desil Nasional</p>
                        <p class="text-sm font-bold text-blue-600 dark:text-blue-400 uppercase"><?= $rumah['desil_nasional'] ?: '-' ?></p>
                    </div>
                </div>
            </div>

            <!-- II.B BUKTI REALISASI PROGRAM (BEFORE-AFTER) -->
            <?php if (!empty($realisasi)) : ?>
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-emerald-100 dark:border-emerald-900/30 shadow-sm overflow-hidden transition-all duration-300">
                <div class="p-6 border-b border-emerald-50 dark:border-emerald-900/30 bg-emerald-50/30 dark:bg-emerald-950/30 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-emerald-600 rounded-lg flex items-center justify-center text-white shadow-lg shadow-emerald-600/20">
                            <i data-lucide="check-circle" class="w-4.5 h-4.5"></i>
                        </div>
                        <div>
                            <h3 class="text-[11px] font-bold text-emerald-900 dark:text-emerald-400 uppercase tracking-[0.2em]">Realisasi Program</h3>
                            <p class="text-[9px] text-emerald-600/70 font-bold uppercase tracking-widest">Bukti Penyelesaian & Dokumentasi After</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-400 rounded-full text-[8px] font-bold uppercase tracking-widest">TUNTAS <?= $realisasi['tahun_anggaran'] ?></span>
                </div>
                <div class="p-8 space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Sumber Dana / Program</p>
                            <p class="text-sm font-bold text-slate-700 dark:text-white uppercase"><?= $realisasi['sumber_dana'] ?></p>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Lokasi Realisasi (GPS)</p>
                            <p class="text-[10px] font-mono font-bold text-emerald-600 uppercase tracking-tighter italic"><?= $realisasi['wkt_realisasi'] ?: 'Sesuai Lokasi Awal' ?></p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Keterangan Pelaksanaan</p>
                            <p class="text-xs font-medium text-slate-600 dark:text-slate-400 leading-relaxed"><?= $realisasi['keterangan'] ?></p>
                        </div>
                    </div>

                    <!-- Before-After Gallery -->
                    <div class="space-y-4 pt-4 border-t border-slate-50 dark:border-slate-800">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                            <i data-lucide="images" class="w-3.5 h-3.5"></i> Komparasi Visual
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Comparison Card 1: Depan -->
                            <div class="bg-slate-50 dark:bg-slate-950 p-2 rounded-xl border border-slate-100 dark:border-slate-800">
                                <p class="text-[8px] font-bold text-center text-slate-400 uppercase mb-2">Tampak Depan</p>
                                <div class="grid grid-cols-2 gap-1 overflow-hidden rounded-lg">
                                    <div class="relative aspect-video bg-slate-200 dark:bg-slate-800">
                                        <?php if(!empty($rumah['foto_depan'])): ?>
                                            <img src="<?= base_url('uploads/rtlh/'.$rumah['foto_depan']) ?>" class="w-full h-full object-cover grayscale opacity-70">
                                            <span class="absolute bottom-1 left-1 px-1.5 py-0.5 bg-black/50 text-white text-[6px] font-bold uppercase rounded">Before</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="relative aspect-video bg-emerald-100 dark:bg-emerald-900/20">
                                        <?php if(!empty($realisasi['foto_setelah_depan'])): ?>
                                            <img src="<?= base_url('uploads/rtlh/'.$realisasi['foto_setelah_depan']) ?>" class="w-full h-full object-cover">
                                            <span class="absolute bottom-1 left-1 px-1.5 py-0.5 bg-emerald-600 text-white text-[6px] font-bold uppercase rounded">After</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <!-- Comparison Card 2: Samping -->
                            <div class="bg-slate-50 dark:bg-slate-950 p-2 rounded-xl border border-slate-100 dark:border-slate-800">
                                <p class="text-[8px] font-bold text-center text-slate-400 uppercase mb-2">Tampak Samping</p>
                                <div class="grid grid-cols-2 gap-1 overflow-hidden rounded-lg">
                                    <div class="relative aspect-video bg-slate-200 dark:bg-slate-800">
                                        <?php if(!empty($rumah['foto_samping'])): ?>
                                            <img src="<?= base_url('uploads/rtlh/'.$rumah['foto_samping']) ?>" class="w-full h-full object-cover grayscale opacity-70">
                                            <span class="absolute bottom-1 left-1 px-1.5 py-0.5 bg-black/50 text-white text-[6px] font-bold uppercase rounded">Before</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="relative aspect-video bg-emerald-100 dark:bg-emerald-900/20">
                                        <?php if(!empty($realisasi['foto_setelah_samping'])): ?>
                                            <img src="<?= base_url('uploads/rtlh/'.$realisasi['foto_setelah_samping']) ?>" class="w-full h-full object-cover">
                                            <span class="absolute bottom-1 left-1 px-1.5 py-0.5 bg-emerald-600 text-white text-[6px] font-bold uppercase rounded">After</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- III. Dokumentasi Visual -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all duration-300 no-print">
                <div class="p-6 border-b dark:border-slate-800 flex items-center gap-3">
                    <div class="w-9 h-9 bg-slate-100 dark:bg-slate-800 rounded-lg flex items-center justify-center text-blue-600">
                        <i data-lucide="camera" class="w-4.5 h-4.5"></i>
                    </div>
                    <div>
                        <h3 class="text-[11px] font-bold text-blue-950 dark:text-white uppercase tracking-[0.2em]">Galeri Dokumentasi</h3>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Kondisi Eksisting Visual</p>
                    </div>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php 
                        $fotos = [
                            'foto_depan' => 'Tampak Depan',
                            'foto_samping' => 'Tampak Samping',
                            'foto_belakang' => 'Tampak Belakang',
                            'foto_dalam' => 'Bagian Dalam'
                        ];
                        foreach($fotos as $f_key => $f_label):
                            $photoPath = !empty($rumah[$f_key]) && file_exists(FCPATH . 'uploads/rtlh/' . $rumah[$f_key]) 
                                ? base_url('uploads/rtlh/' . $rumah[$f_key]) 
                                : null;
                    ?>
                    <div class="group relative bg-slate-50 dark:bg-slate-950 rounded-xl overflow-hidden border border-slate-100 dark:border-slate-800 aspect-video">
                        <?php if($photoPath): ?>
                            <img src="<?= $photoPath ?>" alt="<?= $f_label ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            <div class="absolute inset-0 bg-blue-950/60 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col items-center justify-center p-4 text-center">
                                <p class="text-white font-bold uppercase tracking-widest text-[10px] mb-3"><?= $f_label ?></p>
                                <button onclick="window.open('<?= $photoPath ?>', '_blank')" class="px-4 py-2 bg-white text-blue-950 rounded-lg text-[9px] font-bold uppercase tracking-widest hover:scale-105 transition-all">Zoom</button>
                            </div>
                        <?php else: ?>
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-300 dark:text-slate-800">
                                <i data-lucide="image-off" class="w-10 h-10 mb-2 opacity-20"></i>
                                <span class="text-[9px] font-bold uppercase tracking-widest opacity-40">Belum Ada Foto</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: PENILAIAN TEKNIS -->
        <div class="space-y-6">
            
            <!-- IV. Penilaian Teknis -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="p-6 border-b dark:border-slate-800 bg-blue-600 text-white">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center">
                            <i data-lucide="shield-check" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="text-[11px] font-bold uppercase tracking-[0.2em]">Penilaian Teknis</h3>
                            <p class="text-[9px] text-blue-100 font-bold uppercase tracking-widest opacity-80">Standar Kelayakan Fisik</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Struktur & Komponen Lainnya -->
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                            <span class="w-4 h-[2px] bg-blue-600"></span> Struktur & Komponen
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-1 gap-2">
                            <?php 
                                $struk = [
                                    'st_pondasi' => 'Pondasi', 
                                    'st_kolom' => 'Kolom', 
                                    'st_balok' => 'Balok', 
                                    'st_sloof' => 'Sloof',
                                    'st_rangka_atap' => 'Rangka Atap',
                                    'st_plafon' => 'Plafon',
                                    'st_jendela' => 'Jendela',
                                    'st_ventilasi' => 'Ventilasi'
                                ];
                                foreach($struk as $field => $label):
                                    $val = $ref[$kondisi[$field] ?? ''] ?? 'N/A';
                            ?>
                            <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-100 dark:border-slate-800">
                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-tighter"><?= $label ?></span>
                                <span class="px-2.5 py-0.5 rounded-full text-[8px] font-bold uppercase border <?= getStatusBadge($val) ?>"><?= $val ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Material -->
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                            <span class="w-4 h-[2px] bg-emerald-500"></span> Material
                        </p>
                        <div class="space-y-3">
                            <?php 
                                $mats = [
                                    ['label' => 'Atap', 'mat' => $ref[$kondisi['mat_atap'] ?? ''] ?? '-', 'st' => $ref[$kondisi['st_atap'] ?? ''] ?? '-'],
                                    ['label' => 'Dinding', 'mat' => $ref[$kondisi['mat_dinding'] ?? ''] ?? '-', 'st' => $ref[$kondisi['st_dinding'] ?? ''] ?? '-'],
                                    ['label' => 'Lantai', 'mat' => $ref[$kondisi['mat_lantai'] ?? ''] ?? '-', 'st' => $ref[$kondisi['st_lantai'] ?? ''] ?? '-'],
                                ];
                                foreach($mats as $m):
                            ?>
                            <div class="p-4 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-100 dark:border-slate-800 space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-bold text-blue-900 dark:text-blue-400 uppercase tracking-widest"><?= $m['label'] ?></span>
                                    <span class="text-[9px] font-bold text-slate-400 italic"><?= $m['mat'] ?></span>
                                </div>
                                <div class="w-full text-center py-1.5 rounded-lg text-[8px] font-bold uppercase border <?= getStatusBadge($m['st']) ?>">
                                    <?= $m['st'] ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Sanitasi Mini Grid -->
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                            <span class="w-4 h-[2px] bg-blue-400"></span> Utilitas
                        </p>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="p-3 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-100 dark:border-slate-800">
                                <p class="text-[7px] font-bold text-slate-400 uppercase mb-1">Air Minum</p>
                                <p class="text-[9px] font-bold text-slate-700 dark:text-white uppercase leading-tight"><?= $rumah['sumber_air_minum'] ?? '-' ?></p>
                            </div>
                            <div class="p-3 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-100 dark:border-slate-800">
                                <p class="text-[7px] font-bold text-slate-400 uppercase mb-1">Penerangan</p>
                                <p class="text-[9px] font-bold text-slate-700 dark:text-white uppercase leading-tight"><?= $rumah['sumber_penerangan'] ?? '-' ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Meta Info -->
            <div class="bg-blue-950 rounded-2xl p-8 text-white shadow-2xl relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-700">
                    <i data-lucide="info" class="w-32 h-32"></i>
                </div>
                <h4 class="text-[9px] font-bold uppercase tracking-[0.3em] text-blue-400 mb-4 flex items-center gap-2">
                    Informasi Sistem
                </h4>
                <div class="space-y-4 relative z-10">
                    <div class="flex justify-between items-center text-[9px]">
                        <span class="font-bold text-blue-300/60 uppercase tracking-widest">Dibuat</span>
                        <span class="font-bold text-blue-50"><?= !empty($rumah['created_at']) ? date('d/m/y H:i', strtotime($rumah['created_at'])) : '-' ?></span>
                    </div>
                    <div class="flex justify-between items-center text-[9px]">
                        <span class="font-bold text-blue-300/60 uppercase tracking-widest">Update</span>
                        <span class="font-bold text-blue-50"><?= !empty($rumah['updated_at']) ? date('d/m/y H:i', strtotime($rumah['updated_at'])) : '-' ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL TUNTAS BANSOS -->
<div id="modal-tuntas" class="fixed inset-0 z-[10001] flex items-center justify-center p-4 hidden">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModalTuntas()"></div>
    <div class="bg-white dark:bg-slate-900 rounded-2xl p-8 max-w-md w-full relative z-10 shadow-2xl border border-slate-100 dark:border-slate-800 transition-all">
        <div class="flex flex-col items-center text-center mb-6">
            <div class="w-16 h-16 rounded-xl mb-4 flex items-center justify-center bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 shadow-inner">
                <i data-lucide="award" class="w-8 h-8"></i>
            </div>
            <h3 class="text-xl font-bold text-blue-950 dark:text-white uppercase tracking-tight mb-1">Tuntas Bantuan</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium leading-relaxed">Konfirmasi penyelesaian program perbaikan.</p>
        </div>

        <form action="<?= base_url('rtlh/mark-tuntas/' . ($rumah['id_survei'] ?? '')) ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
            <?= csrf_field() ?>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Tahun Anggaran</label>
                    <input type="number" name="tahun_bansos" value="<?= date('Y') ?>" min="2000" max="2099" class="w-full p-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-white outline-none transition-all font-bold" required>
                </div>
                <div>
                    <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Nama Program</label>
                    <input type="text" name="program_bansos" placeholder="Contoh: BSPS..." class="w-full p-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-white outline-none transition-all font-bold" required>
                </div>
            </div>

            <div>
                <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Koordinat Realisasi (GPS)</label>
                <div class="relative">
                    <input type="text" name="lokasi_realisasi" id="lokasi_realisasi" value="<?= $rumah['lokasi_koordinat'] ?? '' ?>" class="w-full p-3 pl-10 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-[10px] font-mono font-bold text-emerald-600 outline-none">
                    <i data-lucide="map-pin" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                </div>
            </div>

            <div class="space-y-3">
                <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest ml-1">Dokumentasi Hasil (After)</label>
                <div class="grid grid-cols-3 gap-2">
                    <?php foreach(['foto_setelah_depan' => 'Depan', 'foto_setelah_samping' => 'Samping', 'foto_setelah_dalam' => 'Dalam'] as $fkey => $flabel): ?>
                    <div class="relative group h-20 bg-slate-50 dark:bg-slate-950 border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-lg flex flex-col items-center justify-center overflow-hidden">
                        <input type="file" name="<?= $fkey ?>" accept="image/*" class="absolute inset-0 opacity-0 z-10 cursor-pointer">
                        <i data-lucide="camera" class="w-4 h-4 text-slate-300"></i>
                        <span class="text-[7px] font-bold text-slate-400 uppercase mt-1"><?= $flabel ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div>
                <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Keterangan Realisasi</label>
                <textarea name="keterangan_realisasi" rows="2" class="w-full p-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-[10px] font-bold outline-none" placeholder="Catatan hasil perbaikan..."></textarea>
            </div>
            
            <div class="flex gap-2 pt-2">
                <button type="button" onclick="closeModalTuntas()" class="flex-1 py-3 text-[10px] font-bold text-slate-400 hover:text-slate-600 transition-colors uppercase tracking-widest">Batal</button>
                <button type="submit" class="flex-[2] py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-[10px] font-bold uppercase tracking-widest shadow-lg shadow-emerald-600/20 active:scale-95 transition-all flex items-center justify-center gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4"></i> Simpan Realisasi
                </button>
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

        document.body.classList.add('is-exporting');
        fetch('<?= base_url("rtlh/log-export/" . ($rumah["id_survei"] ?? 0)) ?>', { method: 'POST' });

        html2pdf().set(opt).from(element).toPdf().get('pdf').then(function (pdf) {
            document.body.classList.remove('is-exporting');
        }).save();
    }

    let map;
    function initMap() {
        const coordsStr = "<?= $rumah['wkt'] ?? '' ?>";
        if (!coordsStr) {
            document.getElementById('coords-text').innerText = "KOORDINAT TIDAK TERSEDIA";
            return;
        }

        const match = coordsStr.match(/POINT\s*\(\s*([-\d.]+)\s+([-\d.]+)\s*\)/i);
        if (!match) return;
        const lng = parseFloat(match[1]);
        const lat = parseFloat(match[2]);

        document.getElementById('coords-text').innerText = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;

        if (typeof L === 'undefined') { setTimeout(initMap, 100); return; }
        const isDark = document.documentElement.classList.contains('dark');
        const tiles = L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { attribution: '&copy; Sibaruki' });

        map = L.map('map-detail', { zoomControl: false, layers: [tiles] }).setView([lat, lng], 17);
        L.marker([lat, lng]).addTo(map);
        setTimeout(() => map.invalidateSize(), 500);
    }

    function focusMap() {
        const coordsStr = "<?= $rumah['wkt'] ?? '' ?>";
        const match = coordsStr.match(/POINT\s*\(\s*([-\d.]+)\s+([-\d.]+)\s*\)/i);
        if (match) map.setView([parseFloat(match[2]), parseFloat(match[1])], 18, { animate: true });
    }

    window.addEventListener('load', initMap);
</script>

<style>
    @media print {
        aside, header, nav, .no-print { display: none !important; }
        main { margin: 0 !important; padding: 0 !important; width: 100% !important; }
        .shadow-sm, .shadow-lg, .shadow-xl { box-shadow: none !important; }
        .bg-blue-900, .bg-blue-950, .bg-emerald-50, .bg-blue-50 { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .max-w-7xl { max-width: 100% !important; }
    }
    .is-exporting .no-print { display: none !important; }
</style>
<?= $this->endSection() ?>
