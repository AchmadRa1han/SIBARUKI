<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
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
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 bg-blue-950 p-7 rounded-[2.5rem] text-white shadow-2xl shadow-blue-950/20 relative overflow-hidden transition-all duration-500 no-print">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
        
        <div class="flex flex-col md:flex-row md:items-center gap-6 relative z-10">
            <a href="<?= base_url('rtlh') ?>" class="p-2.5 bg-white/10 text-white rounded-xl hover:bg-white hover:text-blue-950 transition-all active:scale-95" title="Kembali">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div class="flex flex-col md:flex-row md:items-center gap-5">
                <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-md border border-white/10">
                    <i data-lucide="user" class="w-6 h-6 text-blue-400"></i>
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <span class="px-2 py-0.5 bg-blue-500/20 text-blue-300 rounded-full text-[8px] font-bold uppercase tracking-widest border border-blue-500/30">SRV-<?= str_pad($rumah['id_survei'] ?? '0', 5, '0', STR_PAD_LEFT) ?></span>
                        <?php if (in_array($rumah['status_bantuan'] ?? '', ['Rlh', 'Sudah Menerima'])) : ?>
                            <span class="px-2 py-0.5 bg-emerald-500/20 text-emerald-300 rounded-full text-[8px] font-bold uppercase tracking-widest border border-emerald-500/30">TUNTAS (RLH) - <?= $rumah['tahun_bansos'] ?></span>
                        <?php elseif (($rumah['status_bantuan'] ?? '') == 'Unknown'): ?>
                            <span class="px-2 py-0.5 bg-slate-500/20 text-slate-300 rounded-full text-[8px] font-bold uppercase tracking-widest border border-slate-500/30">BELUM TERDATA (UNKNOWN)</span>
                        <?php else: ?>
                            <span class="px-2 py-0.5 bg-rose-500/20 text-rose-300 rounded-full text-[8px] font-bold uppercase tracking-widest border border-rose-500/30">TARGET (RTLH)</span>
                        <?php endif; ?>
                    </div>
                    <h1 class="text-2xl md:text-3xl font-black uppercase tracking-tighter leading-none"><?= $penerima['nama_kepala_keluarga'] ?? 'DATA TIDAK DITEMUKAN' ?></h1>
                    <p class="text-white/60 font-medium text-[10px] mt-1 tracking-wide uppercase">NIK: <?= $rumah['nik_pemilik'] ?? '-' ?></p>
                </div>
            </div>
        </div>
 
        <div class="flex flex-wrap items-center gap-2 relative z-10">
            <?php if (has_permission('export_data')) : ?>
            <a href="<?= base_url('rtlh/print/' . ($rumah['id_survei'] ?? 0)) ?>" target="_blank" class="p-2 bg-white/10 text-white rounded-xl hover:bg-white hover:text-blue-950 transition-all shadow-sm flex items-center justify-center" title="Cetak/Download Laporan PDF">
                <i data-lucide="printer" class="w-4 h-4"></i>
            </a>
            <?php endif; ?>
            
            <?php if (has_permission('edit_rtlh')) : ?>
                <?php if (in_array($rumah['status_bantuan'] ?? '', ['Rtlh', 'Target', 'Belum Menerima'])) : ?>
                <button onclick="openModalTuntas()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-[9px] font-bold uppercase tracking-widest shadow-lg shadow-emerald-600/10 transition-all active:scale-95 flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4"></i> Tandai Tuntas
                </button>
                <?php endif; ?>
                <button onclick="openEditModal()" class="px-4 py-2 bg-white text-blue-950 rounded-xl text-[9px] font-black uppercase tracking-widest shadow-xl hover:scale-105 active:scale-95 transition-all flex items-center gap-2 group">
                    <i data-lucide="edit-3" class="w-4 h-4"></i> Perbarui Data
                </button>
            <?php endif; ?>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- LEFT COLUMN -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Map Card -->
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden no-print">
                <div id="map-detail" class="w-full h-80 z-10" style="background: #ececec;"></div>
                <div class="p-4 bg-slate-50 dark:bg-slate-800/50 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></div>
                        <span id="coords-text" class="text-[10px] font-mono font-bold text-slate-500 uppercase tracking-widest">
                            <?= !empty($rumah['wkt']) ? htmlspecialchars($rumah['wkt']) : 'WKT KOSONG DARI PHP' ?>
                        </span>
                    </div>
                    <button onclick="focusMap()" class="text-[9px] font-bold text-blue-600 uppercase tracking-widest hover:underline flex items-center gap-1">
                        <i data-lucide="target" class="w-3 h-3"></i> Focus Lokasi
                    </button>
                </div>
            </div>

            <!-- Identitas Pemilik -->
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="p-6 border-b dark:border-slate-800 flex items-center gap-3">
                    <div class="w-9 h-9 bg-blue-50 dark:bg-blue-950/30 rounded-lg flex items-center justify-center text-blue-600"><i data-lucide="user" class="w-4.5 h-4.5"></i></div>
                    <div><h3 class="text-[11px] font-bold text-blue-950 dark:text-white uppercase tracking-[0.2em]">Identitas Pemilik</h3><p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Data Personal & Kependudukan</p></div>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-10">
                    <div class="md:col-span-2"><p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Nomor KK</p><p class="text-sm font-bold text-slate-700 dark:text-white uppercase"><?= $penerima['no_kk'] ?? '-' ?></p></div>
                    <div><p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Tempat, Tanggal Lahir</p><p class="text-sm font-bold text-slate-700 dark:text-white uppercase"><?= $penerima['tempat_lahir'] ?? '-' ?>, <?= $penerima['tanggal_lahir'] ?? '-' ?></p></div>
                    <div><p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Jenis Kelamin</p><p class="text-sm font-bold text-slate-700 dark:text-white uppercase"><?= ($penerima['jenis_kelamin'] ?? '') == 'L' ? 'Laki-laki' : 'Perempuan' ?></p></div>
                    <div><p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Pendidikan</p><p class="text-sm font-bold text-slate-700 dark:text-white uppercase"><?= $ref[$penerima['pendidikan_id'] ?? ''] ?? '-' ?></p></div>
                    <div><p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Pekerjaan</p><p class="text-sm font-bold text-slate-700 dark:text-white uppercase"><?= $ref[$penerima['pekerjaan_id'] ?? ''] ?? '-' ?></p></div>
                    <div><p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Penghasilan Per Bulan</p><p class="text-sm font-bold text-slate-700 dark:text-white uppercase"><?= $penerima['penghasilan_per_bulan'] ?? '-' ?></p></div>
                    <div><p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Jumlah Anggota Keluarga</p><p class="text-sm font-bold text-slate-700 dark:text-white uppercase"><?= $penerima['jumlah_anggota_keluarga'] ?? '0' ?> Orang</p></div>
                </div>
            </div>

            <!-- Lokasi & Aset -->
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="p-6 border-b dark:border-slate-800 flex items-center gap-3">
                    <div class="w-9 h-9 bg-indigo-50 dark:bg-indigo-950/30 rounded-lg flex items-center justify-center text-indigo-600"><i data-lucide="map-pin" class="w-4.5 h-4.5"></i></div>
                    <div><h3 class="text-[11px] font-bold text-blue-950 dark:text-white uppercase tracking-[0.2em]">Lokasi & Aset Lahan</h3><p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Informasi Geospasial & Kepemilikan</p></div>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-10">
                    <div class="md:col-span-2"><p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-2">Alamat Lengkap</p><p class="text-sm font-bold text-slate-700 dark:text-white uppercase leading-relaxed"><?= $rumah['alamat_detail'] ?? '-' ?></p></div>
                    <div><p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Desa / Kelurahan</p><p class="text-sm font-bold text-blue-600 dark:text-blue-400 uppercase"><?= !empty($rumah['desa']) ? $rumah['desa'] : '-' ?></p></div>
                    <div><p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Kepemilikan Rumah</p><p class="text-sm font-bold text-slate-700 dark:text-white uppercase"><?= $ref[$rumah['kepemilikan_rumah'] ?? ''] ?? '-' ?></p></div>
                    <div><p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Kepemilikan Tanah</p><p class="text-sm font-bold text-slate-700 dark:text-white uppercase"><?= $ref[$rumah['kepemilikan_tanah'] ?? ''] ?? '-' ?></p></div>
                    <div class="bg-blue-600 p-6 rounded-[2rem] text-white shadow-xl shadow-blue-600/20">
                        <p class="text-[8px] font-bold text-blue-100 uppercase mb-1 tracking-[0.2em]">Luas Rumah</p>
                        <p class="text-3xl font-black italic"><?= $rumah['luas_rumah_m2'] ?? '0' ?><span class="text-xs font-bold ml-1 opacity-60">m²</span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="space-y-6">
            <!-- Penilaian Teknis -->
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="p-6 border-b dark:border-slate-800 bg-blue-600 text-white flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center"><i data-lucide="shield-check" class="w-5 h-5"></i></div>
                    <div><h3 class="text-[11px] font-bold uppercase tracking-[0.2em]">Penilaian Teknis</h3><p class="text-[9px] text-blue-100 font-bold uppercase tracking-widest opacity-80">Kelayakan Fisik</p></div>
                </div>
                <div class="p-6 space-y-3">
                    <?php 
                        $struk = [
                            'st_pondasi' => 'Pondasi', 
                            'st_kolom' => 'Tiang/Kolom', 
                            'st_balok' => 'Balok', 
                            'st_sloof' => 'Sloof', 
                            'st_rangka_atap' => 'Rangka Atap', 
                            'st_plafon' => 'Plafon', 
                            'st_jendela' => 'Jendela', 
                            'st_ventilasi' => 'Ventilasi',
                            'mat_atap' => 'Material Atap',
                            'st_atap' => 'Kondisi Atap',
                            'mat_dinding' => 'Material Dinding',
                            'st_dinding' => 'Kondisi Dinding',
                            'mat_lantai' => 'Material Lantai',
                            'st_lantai' => 'Kondisi Lantai'
                        ];
                        foreach($struk as $f => $l):
                            $val = $ref[$kondisi[$f] ?? ''] ?? 'BELUM DINILAI';
                    ?>
                    <div class="flex items-center justify-between p-3.5 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800 text-[10px]">
                        <span class="font-bold text-slate-500 uppercase"><?= $l ?></span>
                        <span class="px-3 py-1 rounded-full font-black uppercase text-[8px] border <?= getStatusBadge($val) ?>"><?= $val ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Dokumentasi Foto -->
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden no-print">
                <div class="p-6 border-b dark:border-slate-800 flex items-center gap-3">
                    <div class="w-9 h-9 bg-rose-50 dark:bg-rose-950/30 rounded-lg flex items-center justify-center text-rose-600"><i data-lucide="camera" class="w-4.5 h-4.5"></i></div>
                    <div><h3 class="text-[11px] font-bold text-blue-950 dark:text-white uppercase tracking-[0.2em]">Dokumentasi Visual</h3><p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Kondisi Lapangan</p></div>
                </div>
                <div class="p-6 grid grid-cols-2 gap-4">
                    <?php 
                        $fotos = ['foto_depan' => 'Tampak Depan', 'foto_samping' => 'Samping', 'foto_belakang' => 'Belakang', 'foto_dalam' => 'Interior'];
                        foreach($fotos as $f => $lbl):
                            $path = !empty($rumah[$f]) ? base_url('uploads/rtlh/' . $rumah[$f]) : null;
                    ?>
                    <div class="group relative">
                        <div class="aspect-square rounded-2xl overflow-hidden border border-slate-100 dark:border-slate-800 bg-slate-100 dark:bg-slate-800">
                            <?php if($path): ?>
                                <img src="<?= $path ?>" class="w-full h-full object-cover cursor-pointer hover:scale-110 transition-transform duration-500" onclick="viewImage('<?= $path ?>', '<?= $lbl ?>')">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center opacity-20"><i data-lucide="image" class="w-8 h-8 text-slate-400"></i></div>
                            <?php endif; ?>
                        </div>
                        <p class="text-[7px] font-black text-slate-400 uppercase tracking-widest mt-2 text-center"><?= $lbl ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <?php if (!empty($realisasi)): ?>
            <!-- Dokumentasi Foto Setelah Perbaikan (After) -->
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden no-print">
                <div class="p-6 border-b dark:border-slate-800 flex items-center gap-3 bg-emerald-600 text-white">
                    <div class="w-10 h-10 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center text-white"><i data-lucide="award" class="w-5 h-5"></i></div>
                    <div>
                        <h3 class="text-[11px] font-bold uppercase tracking-[0.2em]">Realisasi Bantuan</h3>
                        <p class="text-[9px] text-emerald-100 font-bold uppercase tracking-widest opacity-80">Tuntas & Layak Huni</p>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div class="space-y-2.5">
                        <div class="flex items-center justify-between p-3.5 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800 text-[10px]">
                            <span class="font-bold text-slate-500 uppercase">Program / Sumber Dana</span>
                            <span class="font-black text-blue-950 dark:text-white uppercase"><?= $realisasi['sumber_dana'] ?></span>
                        </div>
                        <div class="flex items-center justify-between p-3.5 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800 text-[10px]">
                            <span class="font-bold text-slate-500 uppercase">Tanggal Realisasi</span>
                            <span class="font-black text-blue-950 dark:text-white uppercase"><?= date('d/m/Y', strtotime($realisasi['created_at'])) ?></span>
                        </div>
                        <?php if (!empty($realisasi['keterangan'])): ?>
                        <div class="p-3.5 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800 text-[10px] space-y-1">
                            <span class="font-bold text-slate-500 uppercase block">Keterangan Tambahan</span>
                            <span class="font-medium text-slate-700 dark:text-slate-300 block leading-relaxed"><?= $realisasi['keterangan'] ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-3 pt-2">
                        <?php 
                            $afterFotos = [
                                'foto_setelah_depan' => 'Depan (After)', 
                                'foto_setelah_samping' => 'Samping (After)', 
                                'foto_setelah_dalam' => 'Dalam (After)'
                            ];
                            foreach($afterFotos as $f => $lbl):
                                $path = !empty($realisasi[$f]) ? base_url('uploads/rtlh/' . $realisasi[$f]) : null;
                        ?>
                        <div class="group relative">
                            <div class="aspect-square rounded-2xl overflow-hidden border border-slate-100 dark:border-slate-800 bg-slate-100 dark:bg-slate-800">
                                <?php if($path): ?>
                                    <img src="<?= $path ?>" class="w-full h-full object-cover cursor-pointer hover:scale-110 transition-transform duration-500" onclick="viewImage('<?= $path ?>', '<?= $lbl ?>')">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center opacity-20"><i data-lucide="image" class="w-6 h-6 text-slate-400"></i></div>
                                <?php endif; ?>
                            </div>
                            <p class="text-[6px] font-black text-slate-400 uppercase tracking-wider mt-1.5 text-center"><?= $lbl ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
 
<!-- MODAL TUNTAS BANSOS -->
<div id="modal-tuntas" class="fixed inset-0 z-[10002] hidden overflow-y-auto py-10 px-4">
    <div class="fixed inset-0 bg-blue-950/60 backdrop-blur-sm transition-opacity" onclick="closeModalTuntas()"></div>
    <div class="relative flex items-center justify-center p-4 min-h-full">
        <div class="bg-white dark:bg-slate-900 w-full max-w-4xl rounded-[2.5rem] shadow-2xl overflow-hidden animate-in zoom-in duration-300">
            <!-- Modal Header -->
            <div class="p-8 bg-blue-950 text-white flex justify-between items-center border-b border-white/10 sticky top-0 z-30">
                <div class="flex items-center gap-5">
                    <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-md border border-white/10">
                        <i data-lucide="award" class="w-6 h-6 text-emerald-400"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black uppercase tracking-tighter">Konfirmasi Tuntas Bantuan</h3>
                        <p class="text-[8px] font-bold uppercase tracking-widest text-white/60 mt-1">Catat Detail Realisasi dan Penyelesaian Bantuan RTLH</p>
                    </div>
                </div>
                <button type="button" onclick="closeModalTuntas()" class="p-2 hover:bg-white/10 rounded-xl transition-colors"><i data-lucide="x" class="w-6 h-6"></i></button>
            </div>

            <form action="<?= base_url('rtlh/mark-tuntas/' . ($rumah['id_survei'] ?? '')) ?>" method="POST" enctype="multipart/form-data" class="p-10 space-y-8">
                <?= csrf_field() ?>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Program / Sumber Dana -->
                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Jenis / Sumber Dana Bantuan</label>
                        <input type="text" name="program_bansos" placeholder="Contoh: BSPS, APBD Sinjai, DAK Bidang Perumahan" class="w-full p-4 bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-white outline-none transition-all font-bold text-xs uppercase" required>
                    </div>
                    
                    <!-- Tanggal Penyerahan Bantuan -->
                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Tanggal Realisasi Bantuan</label>
                        <input type="date" name="tanggal_bantuan" value="<?= date('Y-m-d') ?>" class="w-full p-4 bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-white outline-none transition-all font-bold text-xs" required>
                    </div>

                    <!-- Tahun Anggaran -->
                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Tahun Anggaran</label>
                        <input type="number" name="tahun_bansos" value="<?= date('Y') ?>" min="2000" max="2099" class="w-full p-4 bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-white outline-none transition-all font-bold text-xs" required>
                    </div>

                    <!-- Tambahan Keterangan -->
                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Keterangan Realisasi</label>
                        <input type="text" name="keterangan_realisasi" placeholder="Masukkan keterangan tambahan realisasi bantuan..." class="w-full p-4 bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-white outline-none transition-all font-bold text-xs">
                    </div>
                </div>

                <!-- Dokumentasi Realisasi (After) -->
                <div class="space-y-4">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] flex items-center gap-3">
                        <span class="w-8 h-[2px] bg-slate-200 dark:bg-slate-800"></span> Dokumentasi Realisasi (Setelah Perbaikan)
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <?php 
                            $afterFotos = [
                                ['foto_setelah_depan', 'Tampak Depan (After)'], 
                                ['foto_setelah_samping', 'Tampak Samping (After)'], 
                                ['foto_setelah_dalam', 'Interior / Dalam (After)']
                            ];
                            foreach($afterFotos as $f):
                        ?>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1"><?= $f[1] ?></label>
                            <div class="relative group aspect-[4/3] bg-slate-100 dark:bg-slate-950 border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-2xl flex flex-col items-center justify-center overflow-hidden transition-all hover:border-emerald-500/50">
                                <input type="file" name="<?= $f[0] ?>" accept="image/*" class="absolute inset-0 opacity-0 z-10 cursor-pointer" onchange="previewImgTuntas(this, '<?= $f[0] ?>')">
                                <div id="placeholder_tuntas_<?= $f[0] ?>" class="flex flex-col items-center justify-center">
                                    <i data-lucide="camera" class="w-6 h-6 text-slate-300 mb-2"></i>
                                    <span class="text-[8px] font-bold text-slate-400 uppercase tracking-wider">Pilih Foto</span>
                                </div>
                                <img id="img_tuntas_<?= $f[0] ?>" class="absolute inset-0 w-full h-full object-cover hidden">
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="flex gap-4 pt-6 border-t border-slate-50 dark:border-slate-800">
                    <button type="button" onclick="closeModalTuntas()" class="flex-1 py-4 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-500 dark:text-slate-400 rounded-2xl text-xs font-bold uppercase tracking-wider transition-all active:scale-95">Batal</button>
                    <button type="submit" class="flex-[2] py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl shadow-emerald-600/20 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <i data-lucide="check-circle" class="w-4.5 h-4.5"></i> Simpan & Tandai Tuntas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SHARED RTLH MODAL COMPONENT -->
<?= view('rtlh/partials/_modal_edit') ?>

<!-- Image Viewer -->
<div id="image-viewer" class="fixed inset-0 z-[10005] hidden flex items-center justify-center p-10 bg-black/95 backdrop-blur-md" onclick="closeImageViewer()">
    <img id="viewer-img" class="max-w-full max-h-full object-contain rounded-xl">
    <p id="viewer-lbl" class="absolute bottom-10 text-white font-black uppercase tracking-widest"></p>
</div>

<script>
    // Local Page Data with hard fallbacks to prevent SyntaxError
    const PAGE_DATA = {
        rumah: <?= json_encode($rumah ?: (object)[], JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR) ?: '{}' ?>,
        penerima: <?= json_encode($penerima ?: (object)[], JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR) ?: '{}' ?>,
        kondisi: <?= json_encode($kondisi ?: (object)[], JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR) ?: '{}' ?>
    };

    let map;

    function initMap() {
        if (typeof L === 'undefined') { setTimeout(initMap, 100); return; }
        try {
            const wkt = (PAGE_DATA.rumah && PAGE_DATA.rumah.wkt) || '';
            let lat = -5.1245, lng = 120.2536; // Fallback Sinjai
            let hasCoords = false;

            if (wkt && typeof wellknown !== 'undefined') {
                const geo = wellknown.parse(wkt);
                if (geo && geo.coordinates) {
                    lng = geo.coordinates[0];
                    lat = geo.coordinates[1];
                    hasCoords = true;
                }
            }
            
            const el = document.getElementById('coords-text');
            if (el) el.innerText = hasCoords ? `${lat.toFixed(6)}, ${lng.toFixed(6)}` : 'Koordinat belum tersedia';
            
            const isDark = document.documentElement.classList.contains('dark');
            const cartoDB = L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; CartoDB'
            });
            const googleSat = L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains:['mt0','mt1','mt2','mt3'],
                attribution: '&copy; Google'
            });
            
            map = L.map('map-detail', { zoomControl: false, layers: [googleSat] }).setView([lat, lng], hasCoords ? 18 : 12);
            L.control.zoom({ position: 'topright' }).addTo(map);

            let rot = 0;
            const LayerToggle = L.Control.extend({
                onAdd: function(map) {
                    const btn = L.DomUtil.create('button', 'rounded-lg shadow-xl border transition-all duration-300 active:scale-90 mt-2 flex items-center justify-center');
                    btn.style.width = '38px'; btn.style.height = '38px'; btn.style.cursor = 'pointer';
                    btn.type = 'button';
                    btn.style.backgroundColor = '#2563eb';
                    const standardSvgColor = isDark ? '#60a5fa' : '#2563eb';
                    
                    btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:block; transition: transform 0.8s cubic-bezier(0.65, 0, 0.35, 1);"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>`;
                    L.DomEvent.disableClickPropagation(btn);
                    L.DomEvent.on(btn, 'click', function(e) {
                        L.DomEvent.stopPropagation(e);
                        L.DomEvent.preventDefault(e);
                        rot += 360;
                        const svg = btn.querySelector('svg');
                        svg.style.transform = `rotate(${rot}deg)`;
                        setTimeout(() => {
                            if (map.hasLayer(googleSat)) { 
                                map.removeLayer(googleSat); 
                                map.addLayer(cartoDB); 
                                btn.style.backgroundColor = isDark ? '#0f172a' : '#ffffff'; 
                                svg.setAttribute('stroke', standardSvgColor); 
                            }
                            else { 
                                map.removeLayer(cartoDB); 
                                map.addLayer(googleSat); 
                                btn.style.backgroundColor = '#2563eb'; 
                                svg.setAttribute('stroke', '#ffffff'); 
                            }
                        }, 200);
                    });
                    return btn;
                }
            });
            new LayerToggle({ position: 'topright' }).addTo(map);

            if (hasCoords) {
                L.circleMarker([lat, lng], { radius: 10, fillColor: '#2563eb', color: '#fff', weight: 4, fillOpacity: 1 }).addTo(map);
            }
            setTimeout(() => map.invalidateSize(), 500);
        } catch (e) { console.error('Map init error:', e); }
    }

    // Trigger Edit Modal using shared component
    function openEditModal() {
        console.log('SIBARUKI DEBUG: openEditModal called');
        if (window.rtlhModal && typeof window.rtlhModal.openEdit === 'function') {
            window.rtlhModal.openEdit({
                r: PAGE_DATA.rumah,
                p: PAGE_DATA.penerima,
                c: PAGE_DATA.kondisi
            });
        } else {
            console.error('rtlhModal component not ready');
            alert('Gagal memuat modul edit. Silakan refresh halaman.');
        }
    }

    function viewImage(src, lbl) {
        const viewer = document.getElementById('image-viewer');
        const img = document.getElementById('viewer-img');
        const text = document.getElementById('viewer-lbl');
        if (viewer && img) {
            img.src = src;
            if (text) text.innerText = lbl;
            viewer.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeImageViewer() {
        const viewer = document.getElementById('image-viewer');
        if (viewer) {
            viewer.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }

    function focusMap() { if (map) map.setView(map.getCenter(), 18); }
    function openModalTuntas() { 
        const el = document.getElementById('modal-tuntas');
        if (el) { el.classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
    }
    function closeModalTuntas() { 
        const el = document.getElementById('modal-tuntas');
        if (el) { el.classList.add('hidden'); document.body.style.overflow = ''; }
    }
    function previewImgTuntas(input, id) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('img_tuntas_' + id);
                const ph = document.getElementById('placeholder_tuntas_' + id);
                if (img) { img.src = e.target.result; img.classList.remove('hidden'); }
                if (ph) ph.classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        initMap();
        if (window.lucide) lucide.createIcons();
    });
</script>

<style>
    .is-exporting .no-print { display: none !important; }
    @media print { .no-print { display: none !important; } }
</style>
<?= $this->endSection() ?>
