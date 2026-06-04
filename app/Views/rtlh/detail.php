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
                    <h1 class="text-2xl md:text-3xl font-bold text-blue-950 dark:text-white tracking-tighter uppercase mb-1"><?= $penerima['nama_kepala_keluarga'] ?? 'DATA TIDAK DITEMUKAN' ?></h1>
                    <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest flex items-center gap-2">
                        <i data-lucide="hash" class="w-3.5 h-3.5"></i> NIK: <?= $rumah['nik_pemilik'] ?? '-' ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2 relative z-10">
            <?php if (has_permission('export_data')) : ?>
            <button onclick="downloadPDF()" class="p-2.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl hover:bg-blue-600 hover:text-white transition-all active:scale-95 shadow-sm" title="Download PDF">
                <i data-lucide="printer" class="w-4 h-4"></i>
            </button>
            <?php endif; ?>
            
            <?php if (has_permission('edit_rtlh')) : ?>
                <?php if (($rumah['status_bantuan'] ?? 'Belum Menerima') == 'Belum Menerima') : ?>
                <button onclick="openModalTuntas()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-[9px] font-bold uppercase tracking-widest shadow-lg shadow-emerald-600/10 transition-all active:scale-95 flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4"></i> Tandai Tuntas
                </button>
                <?php endif; ?>
                <button onclick='openEditModal()' class="px-4 py-2 bg-blue-950 dark:bg-blue-600 hover:bg-black text-white rounded-xl text-[9px] font-bold uppercase tracking-widest shadow-lg shadow-blue-600/10 transition-all active:scale-95 flex items-center gap-2">
                    <i data-lucide="edit-3" class="w-4 h-4"></i> Perbarui Data
                </button>
            <?php endif; ?>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- LEFT COLUMN -->
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

            <!-- Identitas Pemilik -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
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
                        <span class="px-2.5 py-1 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 rounded-lg text-xs font-bold border border-emerald-100 dark:border-emerald-900"><?= $ref[$penerima['penghasilan_per_bulan']] ?? $penerima['penghasilan_per_bulan'] ?? '-' ?></span>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Jml Anggota Keluarga</p>
                        <p class="text-sm font-bold text-slate-700 dark:text-white uppercase"><?= $penerima['jumlah_anggota_keluarga'] ?? '0' ?> Orang</p>
                    </div>
                </div>
            </div>

            <!-- Lokasi & Aset -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
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
                        <p class="text-sm font-bold text-slate-700 dark:text-white uppercase"><?= $ref[$rumah['jenis_kawasan']] ?? $rumah['jenis_kawasan'] ?? '-' ?></p>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Kepemilikan Rumah</p>
                        <p class="text-sm font-bold text-slate-700 dark:text-white uppercase"><?= $ref[$rumah['kepemilikan_rumah']] ?? $rumah['kepemilikan_rumah'] ?? '-' ?></p>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Kepemilikan Tanah</p>
                        <p class="text-sm font-bold text-slate-700 dark:text-white uppercase"><?= $ref[$rumah['kepemilikan_tanah']] ?? $rumah['kepemilikan_tanah'] ?? '-' ?></p>
                    </div>
                    <div class="bg-blue-600 p-5 rounded-2xl text-white shadow-lg shadow-blue-600/10">
                        <p class="text-[8px] font-bold text-blue-100 uppercase mb-1 tracking-[0.2em]">Luas Rumah</p>
                        <p class="text-2xl font-bold italic"><?= $rumah['luas_rumah_m2'] ?? '0' ?><span class="text-xs font-bold ml-1 opacity-60">m²</span></p>
                    </div>
                    <div class="bg-slate-900 dark:bg-slate-800 p-5 rounded-2xl text-white shadow-lg">
                        <p class="text-[8px] font-bold text-slate-400 uppercase mb-1 tracking-[0.2em]">Luas Lahan</p>
                        <p class="text-2xl font-bold italic"><?= $rumah['luas_lahan_m2'] ?? '0' ?><span class="text-xs font-bold ml-1 opacity-60">m²</span></p>
                    </div>
                </div>
            </div>

            <!-- Dokumentasi Foto -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
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
                        $fotos = ['foto_depan' => 'Tampak Depan', 'foto_samping' => 'Tampak Samping', 'foto_belakang' => 'Tampak Belakang', 'foto_dalam' => 'Tampak Dalam'];
                        foreach($fotos as $f_key => $f_label):
                            $photoPath = !empty($rumah[$f_key]) ? base_url('uploads/rtlh/' . $rumah[$f_key]) : null;
                    ?>
                    <div class="group relative bg-slate-50 dark:bg-slate-950 rounded-xl overflow-hidden aspect-video border border-slate-100 dark:border-slate-800">
                        <?php if($photoPath): ?>
                            <img src="<?= $photoPath ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-slate-300 opacity-20"><i data-lucide="image" class="w-12 h-12"></i></div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="space-y-6">
            <!-- Penilaian Teknis -->
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
                <div class="p-6 space-y-4">
                    <?php 
                        $struk = ['st_pondasi' => 'Pondasi', 'st_kolom' => 'Kolom', 'st_balok' => 'Balok', 'st_sloof' => 'Sloof', 'st_rangka_atap' => 'Rangka Atap', 'st_plafon' => 'Plafon', 'st_jendela' => 'Jendela', 'st_ventilasi' => 'Ventilasi'];
                        foreach($struk as $f => $l):
                            $val = $ref[$kondisi[$f] ?? ''] ?? 'N/A';
                    ?>
                    <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-100 dark:border-slate-800 text-[10px]">
                        <span class="font-bold text-slate-500 uppercase"><?= $l ?></span>
                        <span class="px-2.5 py-0.5 rounded-full font-bold uppercase border <?= getStatusBadge($val) ?>"><?= $val ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL TUNTAS BANSOS -->
<div id="modal-tuntas" class="fixed inset-0 z-[10001] flex items-center justify-center p-4 hidden">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModalTuntas()"></div>
    <div class="bg-white dark:bg-slate-900 rounded-2xl p-8 max-w-md w-full relative z-10 shadow-2xl border border-slate-100 dark:border-slate-800">
        <form action="<?= base_url('rtlh/mark-tuntas/' . ($rumah['id_survei'] ?? '')) ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
            <?= csrf_field() ?>
            <h3 class="text-xl font-black uppercase text-blue-950 dark:text-white">Tandai Tuntas</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[9px] font-black text-slate-400 uppercase">Tahun</label>
                    <input type="number" name="tahun_bansos" value="<?= date('Y') ?>" class="w-full p-3 bg-slate-50 dark:bg-slate-800 border-none rounded-lg font-bold">
                </div>
                <div>
                    <label class="text-[9px] font-black text-slate-400 uppercase">Program</label>
                    <input type="text" name="program_bansos" required class="w-full p-3 bg-slate-50 dark:bg-slate-800 border-none rounded-lg font-bold">
                </div>
            </div>
            <div class="flex gap-2 pt-4">
                <button type="button" onclick="closeModalTuntas()" class="flex-1 py-3 text-xs font-bold text-slate-400 uppercase">Batal</button>
                <button type="submit" class="flex-[2] py-3 bg-emerald-600 text-white rounded-xl text-xs font-black uppercase shadow-lg">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- MULTI-STEP MODAL RTLH (EDIT) -->
<div id="modal-rtlh" class="fixed inset-0 z-[10002] hidden overflow-y-auto py-10">
    <div class="fixed inset-0 bg-blue-950/60 backdrop-blur-sm transition-opacity" onclick="closeModalRtlh()"></div>
    <div class="relative flex items-center justify-center p-4 min-h-full">
        <div class="bg-white dark:bg-slate-900 w-full max-w-5xl rounded-[2.5rem] shadow-2xl overflow-hidden animate-in zoom-in duration-300">
            <!-- Modal Header -->
            <div class="p-8 bg-blue-950 text-white flex justify-between items-center border-b border-white/10 sticky top-0 z-30">
                <div class="flex items-center gap-5">
                    <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-md border border-white/10">
                        <i data-lucide="file-edit" class="w-6 h-6 text-blue-400"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black uppercase tracking-tighter">Edit Data Rumah</h3>
                        <div class="flex items-center gap-4 mt-2">
                            <span class="step-dot w-5 h-5 rounded-full bg-blue-600 text-[10px] font-black flex items-center justify-center text-white" data-step="1">1</span>
                            <span class="step-dot w-5 h-5 rounded-full bg-white/10 text-[10px] font-black flex items-center justify-center text-white/40" data-step="2">2</span>
                            <span class="step-dot w-5 h-5 rounded-full bg-white/10 text-[10px] font-black flex items-center justify-center text-white/40" data-step="3">3</span>
                        </div>
                    </div>
                </div>
                <button onclick="closeModalRtlh()" class="p-2 hover:bg-white/10 rounded-xl transition-colors"><i data-lucide="x" class="w-6 h-6"></i></button>
            </div>

            <form id="form-rtlh" action="<?= base_url('rtlh/update/' . ($rumah['id_survei'] ?? 0)) ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                
                <!-- STEP 1 -->
                <div class="modal-step" id="step-1">
                    <div class="p-10 grid grid-cols-1 lg:grid-cols-2 gap-10">
                        <div class="space-y-6">
                            <div><label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Nama</label><input type="text" name="nama_kepala_keluarga" id="inp_nama" required class="w-full p-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl font-bold"></div>
                            <div class="grid grid-cols-2 gap-4">
                                <div><label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">NIK</label><input type="text" name="nik" id="inp_nik" readonly class="w-full p-4 bg-slate-100 dark:bg-slate-800 border-none rounded-2xl font-bold opacity-60"></div>
                                <div><label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Desa</label>
                                    <select name="desa_id" id="inp_desa_id" required class="w-full p-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl font-bold">
                                        <?php foreach($desa_list as $d): ?><option value="<?= $d['desa_id'] ?>"><?= $d['desa'] ?></option><?php endforeach; ?>
                                    </select>
                                    <input type="hidden" name="desa" id="inp_desa_nama">
                                </div>
                            </div>
                            <div><label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Koordinat</label><input type="text" name="lokasi_koordinat" id="inp_coords" class="w-full p-4 bg-blue-50 dark:bg-blue-900/20 border-none rounded-2xl font-mono text-xs"></div>
                        </div>
                        <div id="modalMap" class="h-full min-h-[350px] w-full rounded-[2rem] border-4 border-slate-100 dark:border-slate-800 shadow-inner"></div>
                    </div>
                </div>

                <!-- STEP 2 -->
                <div class="modal-step hidden" id="step-2">
                    <div class="p-10 grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="space-y-6">
                            <h4 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] border-b pb-2">Status Hunian</h4>
                            <div><label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Milik Rumah</label>
                                <select name="kepemilikan_rumah" id="inp_milik_rumah" class="w-full mt-1.5 p-3 bg-slate-50 dark:bg-slate-800 border-none rounded-xl font-bold text-xs">
                                    <option value="">Pilih</option>
                                    <?php foreach(($master['KEPEMILIKAN_RUMAH'] ?? []) as $rm): ?><option value="<?= $rm['id'] ?>"><?= $rm['nama_pilihan'] ?></option><?php endforeach; ?>
                                </select>
                            </div>
                            <div><label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Milik Tanah</label>
                                <select name="kepemilikan_tanah" id="inp_milik_tanah" class="w-full mt-1.5 p-3 bg-slate-50 dark:bg-slate-800 border-none rounded-xl font-bold text-xs">
                                    <option value="">Pilih</option>
                                    <?php foreach(($master['KEPEMILIKAN_TANAH'] ?? []) as $rt): ?><option value="<?= $rt['id'] ?>"><?= $rt['nama_pilihan'] ?></option><?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="space-y-6">
                            <h4 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] border-b pb-2">Fasilitas Dasar</h4>
                            <div><label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Penerangan</label>
                                <select name="sumber_penerangan" id="inp_listrik" class="w-full mt-1.5 p-3 bg-slate-50 dark:bg-slate-800 border-none rounded-xl font-bold text-xs">
                                    <option value="">Pilih</option>
                                    <?php foreach(($master['SUMBER_PENERANGAN'] ?? []) as $sp): ?><option value="<?= $sp['id'] ?>"><?= $sp['nama_pilihan'] ?></option><?php endforeach; ?>
                                </select>
                            </div>
                            <div><label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Air Minum</label>
                                <select name="sumber_air_minum" id="inp_air" class="w-full mt-1.5 p-3 bg-slate-50 dark:bg-slate-800 border-none rounded-xl font-bold text-xs">
                                    <option value="">Pilih</option>
                                    <?php foreach(($master['SUMBER_AIR_MINUM'] ?? []) as $sa): ?><option value="<?= $sa['id'] ?>"><?= $sa['nama_pilihan'] ?></option><?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="space-y-6">
                            <h4 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] border-b pb-2">Sanitasi</h4>
                            <div><label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Jamban</label>
                                <select name="kamar_mandi_dan_jamban" id="inp_bab" class="w-full mt-1.5 p-3 bg-slate-50 dark:bg-slate-800 border-none rounded-xl font-bold text-xs">
                                    <option value="SENDIRI">Sendiri</option><option value="BERSAMA">Bersama</option><option value="TIDAK ADA">Tidak Ada</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 3 -->
                <div class="modal-step hidden" id="step-3">
                    <div class="p-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <?php 
                            $komp = [['st_pondasi', 'Pondasi'], ['st_dinding', 'Dinding'], ['st_atap', 'Atap'], ['st_lantai', 'Lantai']];
                            foreach($komp as $k):
                        ?>
                        <div><label class="text-[9px] font-black text-slate-400 uppercase tracking-widest"><?= $k[1] ?></label>
                            <select name="<?= $k[0] ?>" id="inp_<?= $k[0] ?>" class="w-full mt-1.5 p-3 bg-slate-50 dark:bg-slate-800 border-none rounded-xl font-bold text-xs">
                                <option value="">Pilih</option>
                                <?php foreach(($master['KONDISI'] ?? []) as $opt): ?><option value="<?= $opt['id'] ?>"><?= $opt['nama_pilihan'] ?></option><?php endforeach; ?>
                            </select>
                        </div>
                        <?php endforeach; ?>
                        
                        <div class="col-span-full pt-10"><p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Foto Dokumentasi</p></div>
                        <?php foreach(['foto_depan', 'foto_samping'] as $f): ?>
                        <div class="space-y-3">
                            <div class="relative aspect-video rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-800 flex items-center justify-center overflow-hidden cursor-pointer" onclick="document.getElementById('inp_<?= $f ?>').click()">
                                <img id="prev_<?= $f ?>" class="absolute inset-0 w-full h-full object-cover hidden">
                                <div id="placeholder_<?= $f ?>" class="text-slate-300"><i data-lucide="image-plus" class="w-8 h-8"></i></div>
                                <input type="file" name="<?= $f ?>" id="inp_<?= $f ?>" class="hidden" onchange="previewImg(this, '<?= $f ?>')">
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="p-8 bg-slate-50 dark:bg-slate-900 border-t dark:border-slate-800 flex justify-between">
                    <button type="button" id="btn-prev" onclick="moveStep(-1)" class="hidden px-8 py-3 bg-white dark:bg-slate-800 text-slate-400 rounded-2xl font-black uppercase text-[10px]">Sebelumnya</button>
                    <div class="flex gap-3 ml-auto">
                        <button type="button" onclick="closeModalRtlh()" class="px-8 py-3 text-slate-400 font-black uppercase text-[10px]">Batal</button>
                        <button type="button" id="btn-next" onclick="moveStep(1)" class="px-10 py-3 bg-blue-950 dark:bg-blue-600 text-white rounded-2xl font-black uppercase text-[10px] shadow-xl">Selanjutnya</button>
                        <button type="submit" id="btn-save" class="hidden px-10 py-3 bg-emerald-600 text-white rounded-2xl font-black uppercase text-[10px] shadow-xl">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let map, m_map, m_marker;
    let m_currentStep = 1;

    function initMap() {
        const coordsStr = <?= json_encode($rumah['wkt'] ?? '') ?>;
        if (!coordsStr) return;
        const match = coordsStr.match(/POINT\s*\(\s*([-\d.]+)\s+([-\d.]+)\s*\)/i);
        if (!match) return;
        const lng = parseFloat(match[1]), lat = parseFloat(match[2]);
        document.getElementById('coords-text').innerText = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
        
        map = L.map('map-detail', { zoomControl: false }).setView([lat, lng], 17);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png').addTo(map);
        L.circleMarker([lat, lng], { radius: 8, fillColor: '#2563eb', color: '#fff', weight: 3, fillOpacity: 1 }).addTo(map);
        setTimeout(() => map.invalidateSize(), 500);
    }

    function openEditModal() {
        document.getElementById('modal-rtlh').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        const p = <?= json_encode($penerima) ?>;
        const r = <?= json_encode($rumah) ?>;
        
        document.getElementById('inp_nama').value = p.nama_kepala_keluarga || '';
        document.getElementById('inp_nik').value = r.nik_pemilik;
        document.getElementById('inp_desa_id').value = r.desa_id;
        document.getElementById('inp_coords').value = r.wkt || '';
        
        setTimeout(() => {
            if (!m_map) {
                m_map = L.map('modalMap', { zoomControl: false }).setView([-5.1245, 120.2536], 12);
                L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', { subdomains:['mt0','mt1','mt2','mt3'] }).addTo(m_map);
            }
            m_map.invalidateSize();
            if (r.wkt) {
                const geo = wellknown.parse(r.wkt);
                if (geo) {
                    if (m_marker) m_marker.setLatLng([geo.coordinates[1], geo.coordinates[0]]);
                    else m_marker = L.marker([geo.coordinates[1], geo.coordinates[0]], { draggable: true }).addTo(m_map);
                    m_map.setView([geo.coordinates[1], geo.coordinates[0]], 18);
                }
            }
        }, 300);
    }

    function moveStep(delta) { m_currentStep += delta; showStep(m_currentStep); }
    function showStep(s) {
        document.querySelectorAll('.modal-step').forEach((el, i) => el.classList.toggle('hidden', i + 1 !== s));
        document.getElementById('btn-prev').classList.toggle('hidden', s === 1);
        document.getElementById('btn-next').classList.toggle('hidden', s === 3);
        document.getElementById('btn-save').classList.toggle('hidden', s !== 3);
        if (s === 1 && m_map) setTimeout(() => m_map.invalidateSize(), 100);
    }

    function previewImg(input, id) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('prev_' + id).src = e.target.result;
                document.getElementById('prev_' + id).classList.remove('hidden');
                document.getElementById('placeholder_' + id).classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function downloadPDF() {
        const element = document.getElementById('report-content');
        const opt = {
            margin: [10, 10], filename: 'Laporan_RTLH.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };
        document.body.classList.add('is-exporting');
        html2pdf().set(opt).from(element).save().then(() => document.body.classList.remove('is-exporting'));
    }

    function closeModalRtlh() { document.getElementById('modal-rtlh').classList.add('hidden'); document.body.style.overflow = ''; }
    function openModalTuntas() { document.getElementById('modal-tuntas').classList.remove('hidden'); }
    function closeModalTuntas() { document.getElementById('modal-tuntas').classList.add('hidden'); }
    function focusMap() { if(map) map.setView(map.getCenter(), 18); }

    window.addEventListener('load', initMap);
    lucide.createIcons();
</script>

<style>
    @media print { .no-print { display: none !important; } }
</style>
<?= $this->endSection() ?>
