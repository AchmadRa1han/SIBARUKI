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

<div id="report-content" class="max-w-7xl mx-auto space-y-8 pb-24 text-slate-900 dark:text-slate-200">
    
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-3 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 no-print">
        <a href="<?= base_url('dashboard') ?>" class="hover:text-blue-600 transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <a href="<?= base_url('rtlh') ?>" class="hover:text-blue-600 transition-colors">RTLH</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">Detail Penerima</span>
    </nav>

    <!-- MAIN HEADER -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 bg-white dark:bg-slate-900 p-10 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm no-print relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-blue-600/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
        
        <div class="flex flex-col md:flex-row md:items-center gap-8 relative z-10">
            <div class="w-24 h-24 bg-blue-600 rounded-[2rem] flex items-center justify-center text-white shadow-2xl shadow-blue-600/20 rotate-3">
                <i data-lucide="user" class="w-12 h-12" stroke-width="2.5"></i>
            </div>
            <div>
                <div class="flex flex-wrap items-center gap-3 mb-3">
                    <span class="px-4 py-1.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full text-[10px] font-black uppercase tracking-widest border border-blue-100 dark:border-blue-900/50">
                        SRV-<?= str_pad($rumah['id_survei'] ?? '0', 5, '0', STR_PAD_LEFT) ?>
                    </span>
                    <?php if (($rumah['status_bantuan'] ?? 'Belum Menerima') == 'Belum Menerima') : ?>
                        <span class="px-4 py-1.5 bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 rounded-full text-[10px] font-black uppercase tracking-widest border border-rose-100 dark:border-rose-900/50">TARGET (RTLH)</span>
                    <?php else: ?>
                        <span class="px-4 py-1.5 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 rounded-full text-[10px] font-black uppercase tracking-widest border border-emerald-100 dark:border-emerald-900/50">TUNTAS (RLH) - <?= $rumah['tahun_bansos'] ?></span>
                    <?php endif; ?>
                </div>
                <h1 class="text-3xl md:text-4xl font-black text-blue-950 dark:text-white tracking-tighter uppercase mb-1"><?= $penerima['nama_kepala_keluarga'] ?? '-' ?></h1>
                <p class="text-sm font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest flex items-center gap-2">
                    <i data-lucide="hash" class="w-4 h-4"></i> NIK: <?= $penerima['nik'] ?? '-' ?>
                </p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3 relative z-10">
            <?php if (has_permission('export_data')) : ?>
            <button onclick="downloadPDF()" class="p-4 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-2xl hover:bg-blue-600 hover:text-white transition-all active:scale-95 shadow-sm group" title="Download Laporan PDF">
                <i data-lucide="file-down" class="w-6 h-6"></i>
            </button>
            <?php endif; ?>
            
            <?php if (has_permission('edit_rtlh')) : ?>
                <?php if (($rumah['status_bantuan'] ?? 'Belum Menerima') == 'Belum Menerima') : ?>
                <button onclick="openModalTuntas()" class="px-8 py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-emerald-600/20 transition-all active:scale-95 flex items-center gap-3">
                    <i data-lucide="check-circle" class="w-5 h-5"></i> Tandai Tuntas
                </button>
                <?php endif; ?>
                <a href="<?= base_url('rtlh/edit/' . ($rumah['id_survei'] ?? '')) ?>" class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-blue-600/20 transition-all active:scale-95 flex items-center gap-3">
                    <i data-lucide="edit-3" class="w-5 h-5"></i> Perbarui Data
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- LEFT COLUMN: IDENTITAS & LOKASI -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- I. Identitas Lengkap -->
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all duration-300">
                <div class="p-8 border-b dark:border-slate-800 flex items-center gap-4">
                    <div class="w-10 h-10 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center text-blue-600">
                        <i data-lucide="user" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-xs font-black text-blue-950 dark:text-white uppercase tracking-[0.2em]">Identitas Pemilik</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Data Personal & Kependudukan</p>
                    </div>
                </div>
                <div class="p-10 grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12">
                    <div class="md:col-span-2">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Nomor Kartu Keluarga</p>
                        <p class="text-sm font-black text-slate-700 dark:text-white uppercase tracking-wider"><?= $penerima['no_kk'] ?? '-' ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Tempat, Tanggal Lahir</p>
                        <p class="text-sm font-black text-slate-700 dark:text-white uppercase"><?= $penerima['tempat_lahir'] ?? '-' ?>, <?= $penerima['tanggal_lahir'] ?? '-' ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Jenis Kelamin</p>
                        <p class="text-sm font-black text-slate-700 dark:text-white uppercase"><?= ($penerima['jenis_kelamin'] ?? '') == 'L' ? 'Laki-laki' : 'Perempuan' ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Pendidikan</p>
                        <p class="text-sm font-black text-slate-700 dark:text-white uppercase"><?= $ref[$penerima['pendidikan_id'] ?? ''] ?? '-' ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Pekerjaan Utama</p>
                        <p class="text-sm font-black text-slate-700 dark:text-white uppercase"><?= $ref[$penerima['pekerjaan_id'] ?? ''] ?? '-' ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Penghasilan / Bulan</p>
                        <span class="px-3 py-1 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 rounded-full text-[11px] font-black border border-emerald-100 dark:border-emerald-900"><?= $penerima['penghasilan_per_bulan'] ?? '-' ?></span>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Jml Anggota Keluarga</p>
                        <p class="text-sm font-black text-slate-700 dark:text-white uppercase"><?= $penerima['jumlah_anggota_keluarga'] ?? '0' ?> Orang</p>
                    </div>
                </div>
            </div>

            <!-- II. Lokasi & Aset -->
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all duration-300">
                <div class="p-8 border-b dark:border-slate-800 flex items-center gap-4">
                    <div class="w-10 h-10 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center text-blue-600">
                        <i data-lucide="map-pin" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-xs font-black text-blue-950 dark:text-white uppercase tracking-[0.2em]">Lokasi & Aset Lahan</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Informasi Geospasial & Kepemilikan</p>
                    </div>
                </div>
                <div class="p-10 grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12">
                    <div class="md:col-span-2">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Alamat Lengkap Rumah</p>
                        <p class="text-sm font-black text-slate-700 dark:text-white uppercase leading-relaxed"><?= $rumah['alamat_detail'] ?? '-' ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Desa / Kelurahan</p>
                        <p class="text-sm font-black text-blue-600 dark:text-blue-400 uppercase tracking-tighter"><?= $rumah['desa'] ?? '-' ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Jenis Kawasan</p>
                        <p class="text-sm font-black text-slate-700 dark:text-white uppercase"><?= $rumah['jenis_kawasan'] ?? '-' ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Kepemilikan Rumah</p>
                        <p class="text-sm font-black text-slate-700 dark:text-white uppercase"><?= $rumah['kepemilikan_rumah'] ?? '-' ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Kepemilikan Tanah</p>
                        <p class="text-sm font-black text-slate-700 dark:text-white uppercase"><?= $rumah['kepemilikan_tanah'] ?? '-' ?></p>
                    </div>
                    <div class="bg-blue-600 p-6 rounded-[2rem] text-white shadow-xl shadow-blue-600/20">
                        <p class="text-[9px] font-black text-blue-100 uppercase mb-1 tracking-[0.2em]">Luas Rumah</p>
                        <p class="text-3xl font-black italic"><?= $rumah['luas_rumah_m2'] ?? '0' ?><span class="text-xs font-bold ml-1 opacity-60">m²</span></p>
                    </div>
                    <div class="bg-slate-900 dark:bg-slate-800 p-6 rounded-[2rem] text-white shadow-xl">
                        <p class="text-[9px] font-black text-slate-400 uppercase mb-1 tracking-[0.2em]">Luas Lahan</p>
                        <p class="text-3xl font-black italic"><?= $rumah['luas_lahan_m2'] ?? '0' ?><span class="text-xs font-bold ml-1 opacity-60">m²</span></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Status Backlog</p>
                        <p class="text-sm font-black text-slate-700 dark:text-white uppercase"><?= $rumah['status_backlog'] ?: 'TIDAK' ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Desil Nasional</p>
                        <p class="text-sm font-black text-blue-600 dark:text-blue-400 uppercase"><?= $rumah['desil_nasional'] ?: '-' ?></p>
                    </div>
                </div>
            </div>

            <!-- III. Dokumentasi Visual -->
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all duration-300 no-print">
                <div class="p-8 border-b dark:border-slate-800 flex items-center gap-4">
                    <div class="w-10 h-10 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center text-blue-600">
                        <i data-lucide="camera" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-xs font-black text-blue-950 dark:text-white uppercase tracking-[0.2em]">Galeri Dokumentasi</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Kondisi Eksisting Visual</p>
                    </div>
                </div>
                <div class="p-10 grid grid-cols-1 md:grid-cols-2 gap-6">
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
                    <div class="group relative bg-slate-50 dark:bg-slate-950 rounded-3xl overflow-hidden border border-slate-100 dark:border-slate-800 aspect-video">
                        <?php if($photoPath): ?>
                            <img src="<?= $photoPath ?>" alt="<?= $f_label ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            <div class="absolute inset-0 bg-blue-950/60 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col items-center justify-center p-6 text-center">
                                <p class="text-white font-black uppercase tracking-widest text-xs mb-4"><?= $f_label ?></p>
                                <button onclick="window.open('<?= $photoPath ?>', '_blank')" class="px-6 py-2 bg-white text-blue-950 rounded-xl text-[10px] font-black uppercase tracking-widest hover:scale-105 transition-all">Zoom Gambar</button>
                            </div>
                        <?php else: ?>
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-300 dark:text-slate-800">
                                <i data-lucide="image-off" class="w-12 h-12 mb-3 opacity-20"></i>
                                <span class="text-[10px] font-black uppercase tracking-widest opacity-40">Belum Ada Foto</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: PENILAIAN TEKNIS -->
        <div class="space-y-8">
            
            <!-- IV. Penilaian Teknis -->
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden sticky top-24">
                <div class="p-8 border-b dark:border-slate-800 bg-blue-600 text-white">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center">
                            <i data-lucide="shield-check" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h3 class="text-xs font-black uppercase tracking-[0.2em]">Penilaian Teknis</h3>
                            <p class="text-[10px] text-blue-100 font-bold uppercase tracking-widest opacity-80">Standar Kelayakan Fisik</p>
                        </div>
                    </div>
                </div>
                <div class="p-8 space-y-8">
                    <!-- Struktur Utama -->
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <span class="w-6 h-[2px] bg-blue-600"></span> Komponen Struktur
                        </p>
                        <div class="space-y-3">
                            <?php 
                                $struk = ['Pondasi', 'Kolom', 'Balok', 'Sloof'];
                                foreach($struk as $s):
                                    $field = 'st_' . strtolower($s);
                                    $val = $ref[$kondisi[$field] ?? ''] ?? 'N/A';
                            ?>
                            <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800">
                                <span class="text-[11px] font-black text-slate-500 uppercase tracking-tighter"><?= $s ?></span>
                                <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase border <?= getStatusBadge($val) ?>"><?= $val ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Material -->
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <span class="w-6 h-[2px] bg-emerald-500"></span> Material & Kondisi
                        </p>
                        <div class="space-y-4">
                            <?php 
                                $mats = [
                                    ['label' => 'Atap', 'mat' => $ref[$kondisi['mat_atap'] ?? ''] ?? '-', 'st' => $ref[$kondisi['st_atap'] ?? ''] ?? '-'],
                                    ['label' => 'Dinding', 'mat' => $ref[$kondisi['mat_dinding'] ?? ''] ?? '-', 'st' => $ref[$kondisi['st_dinding'] ?? ''] ?? '-'],
                                    ['label' => 'Lantai', 'mat' => $ref[$kondisi['mat_lantai'] ?? ''] ?? '-', 'st' => $ref[$kondisi['st_lantai'] ?? ''] ?? '-'],
                                ];
                                foreach($mats as $m):
                            ?>
                            <div class="p-5 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800 space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-widest"><?= $m['label'] ?></span>
                                    <span class="text-[10px] font-bold text-slate-400 italic"><?= $m['mat'] ?></span>
                                </div>
                                <div class="w-full text-center py-2 rounded-xl text-[9px] font-black uppercase border <?= getStatusBadge($m['st']) ?>">
                                    <?= $m['st'] ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Sanitasi Mini Grid -->
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <span class="w-6 h-[2px] bg-blue-400"></span> Fasilitas & Sanitasi
                        </p>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="p-4 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800">
                                <p class="text-[8px] font-black text-slate-400 uppercase mb-1">Air Minum</p>
                                <p class="text-[10px] font-black text-slate-700 dark:text-white uppercase leading-tight"><?= $rumah['sumber_air_minum'] ?? '-' ?></p>
                            </div>
                            <div class="p-4 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800">
                                <p class="text-[8px] font-black text-slate-400 uppercase mb-1">Penerangan</p>
                                <p class="text-[10px] font-black text-slate-700 dark:text-white uppercase leading-tight"><?= $rumah['sumber_penerangan'] ?? '-' ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
