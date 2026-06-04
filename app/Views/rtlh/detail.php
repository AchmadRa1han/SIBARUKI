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
            <a href="<?= base_url('rtlh/print/' . ($rumah['id_survei'] ?? '')) ?>" target="_blank" class="p-3 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl hover:bg-blue-600 hover:text-white transition-all active:scale-95 shadow-sm" title="Cetak Laporan Formal">
                <i data-lucide="printer" class="w-5 h-5"></i>
            </a>
            <?php endif; ?>
            
            <?php if (has_permission('edit_rtlh')) : ?>
                <?php if (($rumah['status_bantuan'] ?? 'Belum Menerima') == 'Belum Menerima') : ?>
                <button onclick="openModalTuntas()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-[9px] font-bold uppercase tracking-widest shadow-lg shadow-emerald-600/10 transition-all active:scale-95 flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4"></i> Tandai Tuntas
                </button>
                <?php endif; ?>
                <button onclick='editRtlh(<?= json_encode($rumah) ?>)' class="px-4 py-2 bg-blue-950 dark:bg-blue-600 hover:bg-black text-white rounded-xl text-[9px] font-bold uppercase tracking-widest shadow-lg shadow-blue-600/10 transition-all active:scale-95 flex items-center gap-2">
                    <i data-lucide="edit-3" class="w-4 h-4"></i> Perbarui Data
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- ... (rest of detail grid) ... -->

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
                            <h3 class="text-xl font-black uppercase tracking-tighter" id="modal-title">Edit Data Rumah</h3>
                            <!-- Stepper Progress -->
                            <div class="flex items-center gap-4 mt-2">
                                <div class="flex items-center gap-2">
                                    <span class="step-dot w-5 h-5 rounded-full bg-blue-600 text-[10px] font-black flex items-center justify-center text-white" data-step="1">1</span>
                                    <span class="text-[8px] font-bold uppercase tracking-widest text-white">Identitas & Lokasi</span>
                                </div>
                                <div class="w-4 h-px bg-white/20"></div>
                                <div class="flex items-center gap-2">
                                    <span class="step-dot w-5 h-5 rounded-full bg-white/10 text-[10px] font-black flex items-center justify-center text-white/40" data-step="2">2</span>
                                    <span class="text-[8px] font-bold uppercase tracking-widest text-white/40">Fasilitas</span>
                                </div>
                                <div class="w-4 h-px bg-white/20"></div>
                                <div class="flex items-center gap-2">
                                    <span class="step-dot w-5 h-5 rounded-full bg-white/10 text-[10px] font-black flex items-center justify-center text-white/40" data-step="3">3</span>
                                    <span class="text-[8px] font-bold uppercase tracking-widest text-white/40">Teknis & Foto</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button onclick="closeModalRtlh()" class="p-2 hover:bg-white/10 rounded-xl transition-colors"><i data-lucide="x" class="w-6 h-6"></i></button>
                </div>

                <form id="form-rtlh" action="<?= base_url('rtlh/update/' . $rumah['id_survei']) ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    
                    <!-- STEP 1: IDENTITAS & LOKASI -->
                    <div class="modal-step" id="step-1">
                        <div class="p-10 grid grid-cols-1 lg:grid-cols-2 gap-10">
                            <div class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="md:col-span-2">
                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Kepala Keluarga</label>
                                        <input type="text" name="nama_kepala_keluarga" id="inp_nama" required class="w-full p-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl font-bold text-sm focus:ring-2 focus:ring-blue-600 outline-none">
                                    </div>
                                    <div>
                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">NIK Pemilik</label>
                                        <input type="text" name="nik" id="inp_nik" readonly class="w-full p-4 bg-slate-100 dark:bg-slate-800 border-none rounded-2xl font-bold text-sm outline-none cursor-not-allowed">
                                    </div>
                                    <div>
                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Nomor KK</label>
                                        <input type="text" name="no_kk" id="inp_no_kk" maxlength="16" class="w-full p-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl font-bold text-sm focus:ring-2 focus:ring-blue-600 outline-none">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Desa/Kelurahan</label>
                                        <select name="desa_id" id="inp_desa_id" required class="w-full p-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl font-bold text-sm focus:ring-2 focus:ring-blue-600 outline-none appearance-none">
                                            <option value="">Pilih Lokasi</option>
                                            <?php foreach($desa_list as $d): ?>
                                                <option value="<?= $d['desa_id'] ?>"><?= $d['desa'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <input type="hidden" name="desa" id="inp_desa_nama">
                                    </div>
                                    <div>
                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Alamat Detail</label>
                                        <input type="text" name="alamat_detail" id="inp_alamat" class="w-full p-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl font-bold text-sm focus:ring-2 focus:ring-blue-600 outline-none">
                                    </div>
                                </div>
                                <div>
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Koordinat (WKT POINT)</label>
                                    <input type="text" name="lokasi_koordinat" id="inp_coords" placeholder="POINT(lng lat)" class="w-full p-4 bg-blue-50 dark:bg-blue-950/30 border border-blue-100 dark:border-blue-900 rounded-2xl font-mono text-xs focus:ring-2 focus:ring-blue-600 outline-none">
                                </div>
                            </div>
                            <div class="space-y-4">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Pilih Lokasi di Peta (Klik/Geser Pin)</label>
                                <div id="modalMap" class="h-full min-h-[350px] w-full rounded-[2rem] border-4 border-slate-100 dark:border-slate-800 shadow-inner bg-slate-100"></div>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 2: PROFIL & FASILITAS -->
                    <div class="modal-step hidden" id="step-2">
                        <div class="p-10 grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div class="md:col-span-3 grid grid-cols-1 md:grid-cols-4 gap-6 bg-slate-50 dark:bg-slate-800/50 p-6 rounded-3xl border border-slate-100 dark:border-slate-800">
                                <div>
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Pendidikan</label>
                                    <select name="pendidikan_id" id="inp_pendidikan" class="w-full mt-1.5 p-3 bg-white dark:bg-slate-900 border-none rounded-xl font-bold text-xs outline-none focus:ring-2 focus:ring-blue-600">
                                        <option value="">Pilih</option>
                                        <?php foreach(($master['PENDIDIKAN'] ?? []) as $rp): ?><option value="<?= $rp['id'] ?>"><?= $rp['nama_pilihan'] ?></option><?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Pekerjaan</label>
                                    <select name="pekerjaan_id" id="inp_pekerjaan" class="w-full mt-1.5 p-3 bg-white dark:bg-slate-900 border-none rounded-xl font-bold text-xs outline-none focus:ring-2 focus:ring-blue-600">
                                        <option value="">Pilih</option>
                                        <?php foreach(($master['PEKERJAAN'] ?? []) as $rj): ?><option value="<?= $rj['id'] ?>"><?= $rj['nama_pilihan'] ?></option><?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Anggota Keluarga</label>
                                    <input type="number" name="jumlah_anggota_keluarga" id="inp_jml_kel" min="0" class="w-full mt-1.5 p-3 bg-white dark:bg-slate-900 border-none rounded-xl font-bold text-xs outline-none focus:ring-2 focus:ring-blue-600">
                                </div>
                                <div>
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Desil Nasional</label>
                                    <input type="text" name="desil_nasional" id="inp_desil" class="w-full mt-1.5 p-3 bg-white dark:bg-slate-900 border-none rounded-xl font-bold text-xs outline-none focus:ring-2 focus:ring-blue-600">
                                </div>
                            </div>

                            <div class="space-y-6">
                                <h4 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] border-b pb-2">Status Hunian</h4>
                                <div>
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Kepemilikan Rumah</label>
                                    <select name="kepemilikan_rumah" id="inp_milik_rumah" class="w-full mt-1.5 p-3 bg-slate-50 dark:bg-slate-800 border-none rounded-xl font-bold text-xs outline-none">
                                        <option value="">Pilih</option>
                                        <?php foreach(($master['KEPEMILIKAN_RUMAH'] ?? []) as $rm): ?><option value="<?= $rm['id'] ?>"><?= $rm['nama_pilihan'] ?></option><?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Kepemilikan Tanah</label>
                                    <select name="kepemilikan_tanah" id="inp_milik_tanah" class="w-full mt-1.5 p-3 bg-slate-50 dark:bg-slate-800 border-none rounded-xl font-bold text-xs outline-none">
                                        <option value="">Pilih</option>
                                        <?php foreach(($master['KEPEMILIKAN_TANAH'] ?? []) as $rt): ?><option value="<?= $rt['id'] ?>"><?= $rt['nama_pilihan'] ?></option><?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Luas Rumah (m²)</label>
                                        <input type="number" step="0.1" name="luas_rumah_m2" id="inp_luas_r" class="w-full mt-1.5 p-3 bg-slate-50 dark:bg-slate-800 border-none rounded-xl font-bold text-xs outline-none">
                                    </div>
                                    <div>
                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Luas Tanah (m²)</label>
                                        <input type="number" step="0.1" name="luas_lahan_m2" id="inp_luas_t" class="w-full mt-1.5 p-3 bg-slate-50 dark:bg-slate-800 border-none rounded-xl font-bold text-xs outline-none">
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <h4 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] border-b pb-2">Fasilitas Dasar</h4>
                                <div>
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Sumber Penerangan</label>
                                    <select name="sumber_penerangan" id="inp_listrik" class="w-full mt-1.5 p-3 bg-slate-50 dark:bg-slate-800 border-none rounded-xl font-bold text-xs outline-none">
                                        <option value="">Pilih</option>
                                        <?php foreach(($master['SUMBER_PENERANGAN'] ?? []) as $sp): ?><option value="<?= $sp['id'] ?>"><?= $sp['nama_pilihan'] ?></option><?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Sumber Air Minum</label>
                                    <select name="sumber_air_minum" id="inp_air" class="w-full mt-1.5 p-3 bg-slate-50 dark:bg-slate-800 border-none rounded-xl font-bold text-xs outline-none">
                                        <option value="">Pilih</option>
                                        <?php foreach(($master['SUMBER_AIR_MINUM'] ?? []) as $sa): ?><option value="<?= $sa['id'] ?>"><?= $sa['nama_pilihan'] ?></option><?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Bantuan Pernah Diterima</label>
                                    <input type="text" name="bantuan_perumahan" id="inp_bantuan" placeholder="Sebutkan jika ada..." class="w-full mt-1.5 p-3 bg-slate-50 dark:bg-slate-800 border-none rounded-xl font-bold text-xs outline-none uppercase">
                                </div>
                            </div>

                            <div class="space-y-6">
                                <h4 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] border-b pb-2">Sanitasi</h4>
                                <div>
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Fasilitas BAB</label>
                                    <select name="kamar_mandi_dan_jamban" id="inp_bab" class="w-full mt-1.5 p-3 bg-slate-50 dark:bg-slate-800 border-none rounded-xl font-bold text-xs outline-none">
                                        <option value="SENDIRI">Sendiri</option>
                                        <option value="BERSAMA">Bersama/Umum</option>
                                        <option value="TIDAK ADA">Tidak Ada</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Jenis Kloset</label>
                                    <select name="jenis_jamban_kloset" id="inp_kloset" class="w-full mt-1.5 p-3 bg-slate-50 dark:bg-slate-800 border-none rounded-xl font-bold text-xs outline-none">
                                        <option value="">Pilih</option>
                                        <?php foreach(($master['JENIS_JAMBAN'] ?? []) as $jj): ?><option value="<?= $jj['id'] ?>"><?= $jj['nama_pilihan'] ?></option><?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Pembuangan Akhir Tinja</label>
                                    <input type="text" name="jenis_tpa_tinja" id="inp_tpa" class="w-full mt-1.5 p-3 bg-slate-50 dark:bg-slate-800 border-none rounded-xl font-bold text-xs outline-none uppercase">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 3: TEKNIS & DOKUMENTASI -->
                    <div class="modal-step hidden" id="step-3">
                        <div class="p-10 space-y-10">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                <div class="lg:col-span-4 flex items-center gap-3">
                                    <span class="w-8 h-px bg-slate-200"></span>
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Penilaian Teknis Komponen</span>
                                </div>
                                <?php 
                                    $komponen = [
                                        ['st_pondasi', 'Kondisi Pondasi'], ['st_kolom', 'Kondisi Kolom'], ['st_balok', 'Kondisi Balok'], ['st_sloof', 'Kondisi Sloof'],
                                        ['st_rangka_atap', 'Kondisi Rangka'], ['st_plafon', 'Kondisi Plafon'], ['st_jendela', 'Kondisi Jendela'], ['st_ventilasi', 'Kondisi Ventilasi'],
                                        ['mat_atap', 'Material Atap', 'MATERIAL_ATAP'], ['st_atap', 'Kondisi Atap'], ['mat_dinding', 'Material Dinding', 'MATERIAL_DINDING'], ['st_dinding', 'Kondisi Dinding'],
                                        ['mat_lantai', 'Material Lantai', 'MATERIAL_LANTAI'], ['st_lantai', 'Kondisi Lantai']
                                    ];
                                    foreach($komponen as $k):
                                ?>
                                <div>
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1"><?= $k[1] ?></label>
                                    <select name="<?= $k[0] ?>" id="inp_<?= $k[0] ?>" class="w-full mt-1.5 p-3 bg-slate-50 dark:bg-slate-800 border-none rounded-xl font-bold text-xs outline-none focus:ring-2 focus:ring-blue-600">
                                        <option value="">Pilih</option>
                                        <?php 
                                            $cat = $k[2] ?? 'KONDISI';
                                            foreach(($master[$cat] ?? []) as $opt): 
                                        ?>
                                            <option value="<?= $opt['id'] ?>"><?= $opt['nama_pilihan'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <?php endforeach; ?>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                                <div class="col-span-full flex items-center gap-3">
                                    <span class="w-8 h-px bg-slate-200"></span>
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Dokumentasi Visual (Unggah Foto)</span>
                                </div>
                                <?php 
                                    $fotos = [['foto_depan', 'Foto Depan'], ['foto_samping', 'Foto Samping'], ['foto_belakang', 'Foto Belakang'], ['foto_dalam', 'Foto Dalam']];
                                    foreach($fotos as $f):
                                ?>
                                <div class="space-y-3">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1"><?= $f[1] ?></label>
                                    <div class="relative group aspect-square rounded-3xl border-2 border-dashed border-slate-200 dark:border-slate-800 flex flex-col items-center justify-center overflow-hidden transition-all hover:border-blue-500 cursor-pointer" onclick="document.getElementById('inp_<?= $f[0] ?>').click()">
                                        <img id="prev_<?= $f[0] ?>" class="absolute inset-0 w-full h-full object-cover hidden">
                                        <div class="text-center z-10 p-4" id="placeholder_<?= $f[0] ?>">
                                            <i data-lucide="image-plus" class="w-8 h-8 text-slate-300 mb-2 mx-auto"></i>
                                            <p class="text-[7px] font-bold text-slate-400 uppercase tracking-widest">Pilih Gambar</p>
                                        </div>
                                        <input type="file" name="<?= $f[0] ?>" id="inp_<?= $f[0] ?>" class="hidden" accept="image/*" onchange="previewImg(this, '<?= $f[0] ?>')">
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="p-8 bg-slate-50 dark:bg-slate-900 border-t dark:border-slate-800 flex justify-between items-center">
                        <button type="button" id="btn-prev" onclick="moveStep(-1)" class="hidden px-8 py-3 bg-white dark:bg-slate-800 text-slate-500 rounded-2xl font-black uppercase tracking-widest text-[10px] shadow-sm border border-slate-200 dark:border-slate-700 hover:bg-slate-100 transition-all">Sebelumnya</button>
                        <div class="flex-grow"></div>
                        <div class="flex gap-3">
                            <button type="button" onclick="closeModalRtlh()" class="px-8 py-3 bg-white dark:bg-slate-800 text-slate-400 rounded-2xl font-black uppercase tracking-widest text-[10px] border border-transparent hover:text-rose-500 transition-all">Batal</button>
                            <button type="button" id="btn-next" onclick="moveStep(1)" class="px-10 py-3 bg-blue-950 dark:bg-blue-600 text-white rounded-2xl font-black uppercase tracking-widest text-[10px] shadow-xl shadow-blue-950/20 active:scale-95 transition-all">Selanjutnya</button>
                            <button type="submit" id="btn-save" class="hidden px-10 py-3 bg-emerald-600 text-white rounded-2xl font-black uppercase tracking-widest text-[10px] shadow-xl shadow-emerald-600/20 active:scale-95 transition-all">Simpan Perubahan</button>
                        </div>
                    </div>
                </form>
            </div>
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
                        <span class="px-2.5 py-1 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 rounded-lg text-xs font-bold border border-emerald-100 dark:border-emerald-900"><?= $ref[$penerima['penghasilan_per_bulan']] ?? $penerima['penghasilan_per_bulan'] ?? '-' ?></span>
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
                    <div class="col-span-2">
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
                            'foto_samping' => 'Tampak Samping Kiri',
                            'foto_belakang' => 'Tampak Belakang',
                            'foto_dalam' => 'Tampak Samping Kanan'
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
                                <p class="text-[9px] font-bold text-slate-700 dark:text-white uppercase leading-tight"><?= $ref[$rumah['sumber_air_minum']] ?? $rumah['sumber_air_minum'] ?? '-' ?></p>
                            </div>
                            <div class="p-3 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-100 dark:border-slate-800">
                                <p class="text-[7px] font-bold text-slate-400 uppercase mb-1">Penerangan</p>
                                <p class="text-[9px] font-bold text-slate-700 dark:text-white uppercase leading-tight"><?= $ref[$rumah['sumber_penerangan']] ?? $rumah['sumber_penerangan'] ?? '-' ?></p>
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
                   <input type="text" name="lokasi_realisasi" id="lokasi_realisasi" value="<?= $rumah['wkt'] ?? '' ?>" class="w-full p-3 pl-10 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-[10px] font-mono font-bold text-emerald-600 outline-none">
                   <i data-lucide="map-pin" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
               </div>
            </div>
            <div class="space-y-3">
                <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest ml-1">Dokumentasi Hasil (After)</label>
                <div class="grid grid-cols-3 gap-2">
                    <?php foreach(['foto_setelah_depan' => 'Depan', 'foto_setelah_samping' => 'Samping', 'foto_setelah_dalam' => 'Dalam'] as $fkey => $flabel): ?>
                    <div id="preview_<?= $fkey ?>" class="relative group h-20 bg-slate-50 dark:bg-slate-950 border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-lg flex flex-col items-center justify-center overflow-hidden transition-all">
                        <input type="file" name="<?= $fkey ?>" accept="image/*" class="absolute inset-0 opacity-0 z-10 cursor-pointer" onchange="previewTuntasImage(this, 'preview_<?= $fkey ?>')">
                        <div class="flex flex-col items-center justify-center p-2 text-center pointer-events-none">
                            <i data-lucide="camera" class="w-4 h-4 text-slate-300 mb-1"></i>
                            <span class="text-[7px] font-bold text-slate-400 uppercase leading-tight"><?= $flabel ?></span>
                        </div>
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

    function previewTuntasImage(input, previewId) {
        const container = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Hapus konten lama
                container.innerHTML = `
                    <input type="file" name="${input.name}" accept="image/*" class="absolute inset-0 opacity-0 z-10 cursor-pointer" onchange="previewTuntasImage(this, '${previewId}')">
                    <img src="${e.target.result}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <i data-lucide="refresh-cw" class="w-5 h-5 text-white"></i>
                    </div>
                `;
                lucide.createIcons();
                container.classList.remove('border-dashed');
                container.classList.add('border-solid', 'border-emerald-500');
            }
            reader.readAsDataURL(input.files[0]);
        }
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
        const cartoDB = L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { 
            attribution: '&copy; CartoDB' 
        });
        const googleSat = L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains:['mt0','mt1','mt2','mt3'],
            attribution: '&copy; Google'
        });

        map = L.map('map-detail', { 
            zoomControl: false, 
            layers: [cartoDB] 
        }).setView([lat, lng], 17);

        L.control.zoom({ position: 'topright' }).addTo(map);

        let rot = 0;
        const LayerToggle = L.Control.extend({
            onAdd: function(map) {
                const btn = L.DomUtil.create('button', 'bg-white dark:bg-slate-900 rounded-lg shadow-xl border border-slate-100 dark:border-slate-800 transition-all duration-300 active:scale-90 mt-2 flex items-center justify-center');
                btn.style.width = '38px'; btn.style.height = '38px'; btn.style.cursor = 'pointer';
                btn.type = 'button';
                const isDark = document.documentElement.classList.contains('dark');
                const svgColor = isDark ? '#60a5fa' : '#2563eb';
                btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="${svgColor}" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:block; transition: transform 0.8s cubic-bezier(0.65, 0, 0.35, 1);"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>`;
                L.DomEvent.disableClickPropagation(btn);
                L.DomEvent.on(btn, 'click', function(e) {
                    L.DomEvent.stopPropagation(e);
                    L.DomEvent.preventDefault(e);
                    rot += 360;
                    const svg = btn.querySelector('svg');
                    svg.style.transform = `rotate(${rot}deg)`;
                    setTimeout(() => {
                        if (map.hasLayer(cartoDB)) { 
                            map.removeLayer(cartoDB); 
                            map.addLayer(googleSat); 
                            btn.style.backgroundColor = '#2563eb'; 
                            svg.setAttribute('stroke', '#ffffff'); 
                        }
                        else { 
                            map.removeLayer(googleSat); 
                            map.addLayer(cartoDB); 
                            btn.style.backgroundColor = isDark ? '#0f172a' : '#ffffff'; 
                            svg.setAttribute('stroke', svgColor); 
                        }
                    }, 200);
                });
                return btn;
            }
        });
        map.addControl(new LayerToggle({ position: 'topright' }));

        const dotIcon = L.divIcon({
            className: 'custom-marker',
            html: `<div class="w-6 h-6 bg-blue-600 rounded-full border-4 border-white shadow-xl"></div>`,
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        });

        L.circleMarker([lat, lng], { radius: 8, fillColor: '#2563eb', color: '#fff', weight: 3, fillOpacity: 1 }).addTo(map);
        setTimeout(() => map.invalidateSize(), 500);
    }

    function focusMap() {
        const coordsStr = "<?= $rumah['wkt'] ?? '' ?>";
        const match = coordsStr.match(/POINT\s*\(\s*([-\d.]+)\s+([-\d.]+)\s*\)/i);
        if (match) map.setView([parseFloat(match[2]), parseFloat(match[1])], 18, { animate: true });
    }

    window.addEventListener('load', initMap);

    // --- MODAL EDIT LOGIC ---
    let m_map, m_marker;
    let m_currentStep = 1;

    function initModalMap() {
        if (m_map) return;
        m_map = L.map('modalMap', { zoomControl: false }).setView([-5.1245, 120.2536], 12);
        L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
            maxZoom: 20, subdomains:['mt0','mt1','mt2','mt3'], attribution: '&copy; Google'
        }).addTo(m_map);

        m_map.on('click', (e) => {
            setModalMarker(e.latlng.lat, e.latlng.lng);
        });
    }

    function setModalMarker(lat, lng) {
        if (m_marker) m_marker.setLatLng([lat, lng]);
        else m_marker = L.marker([lat, lng], { draggable: true }).addTo(m_map).on('dragend', (e) => {
            const pos = e.target.getLatLng();
            document.getElementById('inp_coords').value = `POINT(${pos.lng} ${pos.lat})`;
        });
        document.getElementById('inp_coords').value = `POINT(${lng} ${lat})`;
    }

    async function editRtlh(item) {
        m_currentStep = 1;
        document.getElementById('form-rtlh').reset();
        document.getElementById('form-rtlh').action = "<?= base_url('rtlh/update') ?>/" + item.id_survei;
        
        // Populate Step 1
        document.getElementById('inp_nama').value = "<?= $penerima['nama_kepala_keluarga'] ?? '' ?>";
        document.getElementById('inp_nik').value = item.nik_pemilik;
        document.getElementById('inp_no_kk').value = "<?= $penerima['no_kk'] ?? '' ?>";
        document.getElementById('inp_desa_id').value = item.desa_id;
        document.getElementById('inp_alamat').value = item.alamat_detail;
        document.getElementById('inp_coords').value = item.wkt;

        // Step 2
        document.getElementById('inp_pendidikan').value = "<?= $penerima['pendidikan_id'] ?? '' ?>";
        document.getElementById('inp_pekerjaan').value = "<?= $penerima['pekerjaan_id'] ?? '' ?>";
        document.getElementById('inp_jml_kel').value = "<?= $penerima['jumlah_anggota_keluarga'] ?? '0' ?>";
        document.getElementById('inp_desil').value = "<?= $rumah['desil_nasional'] ?? '' ?>";
        document.getElementById('inp_milik_rumah').value = "<?= $rumah['kepemilikan_rumah'] ?? '' ?>";
        document.getElementById('inp_milik_tanah').value = "<?= $rumah['kepemilikan_tanah'] ?? '' ?>";
        document.getElementById('inp_luas_r').value = "<?= $rumah['luas_rumah_m2'] ?? '' ?>";
        document.getElementById('inp_luas_t').value = "<?= $rumah['luas_lahan_m2'] ?? '' ?>";
        document.getElementById('inp_listrik').value = "<?= $rumah['sumber_penerangan'] ?? '' ?>";
        document.getElementById('inp_air').value = "<?= $rumah['sumber_air_minum'] ?? '' ?>";
        document.getElementById('inp_bantuan').value = "<?= $rumah['bantuan_perumahan'] ?? '' ?>";
        document.getElementById('inp_bab').value = "<?= $rumah['kamar_mandi_dan_jamban'] ?? '' ?>";
        document.getElementById('inp_kloset').value = "<?= $rumah['jenis_jamban_kloset'] ?? '' ?>";
        document.getElementById('inp_tpa').value = "<?= $rumah['jenis_tpa_tinja'] ?? '' ?>";

        // Step 3
        const cond = <?= json_encode($kondisi ?? []) ?>;
        const fields = ['st_pondasi', 'st_kolom', 'st_balok', 'st_sloof', 'st_rangka_atap', 'st_plafon', 'st_jendela', 'st_ventilasi', 'mat_atap', 'st_atap', 'mat_dinding', 'st_dinding', 'mat_lantai', 'st_lantai'];
        fields.forEach(f => {
            const el = document.getElementById('inp_' + f);
            if(el && cond[f]) el.value = cond[f];
        });

        // Photos
        const r = <?= json_encode($rumah ?? []) ?>;
        ['foto_depan', 'foto_samping', 'foto_belakang', 'foto_dalam'].forEach(f => {
            if(r[f]) {
                const img = document.getElementById('prev_' + f);
                img.src = `<?= base_url('uploads/rtlh/') ?>/${r[f]}`;
                img.classList.remove('hidden');
                document.getElementById('placeholder_' + f).classList.add('hidden');
            }
        });
        
        showStep(1);
        document.getElementById('modal-rtlh').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        setTimeout(() => {
            initModalMap();
            m_map.invalidateSize();
            if (item.wkt) {
                const geo = wellknown.parse(item.wkt);
                if (geo) {
                    setModalMarker(geo.coordinates[1], geo.coordinates[0]);
                    m_map.setView([geo.coordinates[1], geo.coordinates[0]], 18);
                }
            }
        }, 300);
    }

    function moveStep(delta) {
        const next = m_currentStep + delta;
        if (next >= 1 && next <= 3) showStep(next);
    }

    function showStep(step) {
        m_currentStep = step;
        document.querySelectorAll('.modal-step').forEach(s => s.classList.add('hidden'));
        document.getElementById('step-' + step).classList.remove('hidden');

        // Update Stepper UI
        document.querySelectorAll('.step-dot').forEach(dot => {
            const dStep = parseInt(dot.dataset.step);
            if (dStep === step) {
                dot.className = "step-dot w-5 h-5 rounded-full bg-blue-600 text-[10px] font-black flex items-center justify-center text-white";
                dot.nextElementSibling.className = "text-[8px] font-bold uppercase tracking-widest text-white";
                dot.innerHTML = dStep;
            } else if (dStep < step) {
                dot.className = "step-dot w-5 h-5 rounded-full bg-emerald-500 text-[10px] font-black flex items-center justify-center text-white";
                dot.nextElementSibling.className = "text-[8px] font-bold uppercase tracking-widest text-white/60";
                dot.innerHTML = '✓';
            } else {
                dot.className = "step-dot w-5 h-5 rounded-full bg-white/10 text-[10px] font-black flex items-center justify-center text-white/40";
                dot.nextElementSibling.className = "text-[8px] font-bold uppercase tracking-widest text-white/40";
                dot.innerHTML = dStep;
            }
        });

        // Update Buttons
        document.getElementById('btn-prev').classList.toggle('hidden', step === 1);
        document.getElementById('btn-next').classList.toggle('hidden', step === 3);
        document.getElementById('btn-save').classList.toggle('hidden', step !== 3);
        
        if (step === 1 && m_map) setTimeout(() => m_map.invalidateSize(), 100);
    }

    function previewImg(input, id) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('prev_' + id).src = e.target.result;
                document.getElementById('prev_' + id).classList.remove('hidden');
                document.getElementById('placeholder_' + id).classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function closeModalRtlh() {
        document.getElementById('modal-rtlh').classList.add('hidden');
        document.body.style.overflow = '';
    }

    document.getElementById('inp_desa_id')?.addEventListener('change', function() {
        const text = this.options[this.selectedIndex].text;
        document.getElementById('inp_desa_nama').value = text;
    });
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
