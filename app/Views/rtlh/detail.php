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
                        <?php if (($rumah['status_bantuan'] ?? 'Belum Menerima') == 'Belum Menerima') : ?>
                            <span class="px-2 py-0.5 bg-rose-500/20 text-rose-300 rounded-full text-[8px] font-bold uppercase tracking-widest border border-rose-500/30">TARGET (RTLH)</span>
                        <?php else: ?>
                            <span class="px-2 py-0.5 bg-emerald-500/20 text-emerald-300 rounded-full text-[8px] font-bold uppercase tracking-widest border border-emerald-500/30">TUNTAS (RLH) - <?= $rumah['tahun_bansos'] ?></span>
                        <?php endif; ?>
                    </div>
                    <h1 class="text-2xl md:text-3xl font-black uppercase tracking-tighter leading-none"><?= $penerima['nama_kepala_keluarga'] ?? 'DATA TIDAK DITEMUKAN' ?></h1>
                    <p class="text-white/60 font-medium text-[10px] mt-1 tracking-wide uppercase">NIK: <?= $rumah['nik_pemilik'] ?? '-' ?></p>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2 relative z-10">
            <?php if (has_permission('export_data')) : ?>
            <button onclick="downloadPDF()" class="p-2 bg-white/10 text-white rounded-xl hover:bg-white hover:text-blue-950 transition-all shadow-sm" title="Download PDF">
                <i data-lucide="printer" class="w-4 h-4"></i>
            </button>
            <?php endif; ?>
            
            <?php if (has_permission('edit_rtlh')) : ?>
                <?php if (($rumah['status_bantuan'] ?? 'Belum Menerima') == 'Belum Menerima') : ?>
                <button onclick="openModalTuntas()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-[9px] font-bold uppercase tracking-widest shadow-lg shadow-emerald-600/10 transition-all active:scale-95 flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4"></i> Tandai Tuntas
                </button>
                <?php endif; ?>
                <button onclick='openEditModal()' class="px-4 py-2 bg-white text-blue-950 rounded-xl text-[9px] font-black uppercase tracking-widest shadow-xl hover:scale-105 active:scale-95 transition-all flex items-center gap-2 group">
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
                        <span id="coords-text" class="text-[10px] font-mono font-bold text-slate-500 uppercase tracking-widest">Memuat koordinat...</span>
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
                    <div><p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Desa / Kelurahan</p><p class="text-sm font-bold text-blue-600 dark:text-blue-400 uppercase"><?= $rumah['desa'] ?? '-' ?></p></div>
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
                            'st_tiang' => 'Tiang/Kolom', 
                            'st_balok' => 'Balok', 
                            'st_sloof' => 'Sloof', 
                            'st_rangka_atap' => 'Rangka Atap', 
                            'st_plafon' => 'Plafon', 
                            'st_jendela' => 'Jendela', 
                            'st_ventilasi' => 'Ventilasi',
                            'st_lantai' => 'Kondisi Lantai',
                            'st_dinding' => 'Kondisi Dinding',
                            'st_atap' => 'Kondisi Atap'
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
        </div>
    </div>
</div>

<!-- MODAL TUNTAS BANSOS -->
<div id="modal-tuntas" class="fixed inset-0 z-[10001] flex items-center justify-center p-4 hidden">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModalTuntas()"></div>
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-8 max-w-md w-full relative z-10 shadow-2xl border border-slate-100 dark:border-slate-800">
        <form action="<?= base_url('rtlh/mark-tuntas/' . ($rumah['id_survei'] ?? '')) ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
            <?= csrf_field() ?>
            <h3 class="text-xl font-black uppercase text-blue-950 dark:text-white">Tandai Tuntas</h3>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="text-[9px] font-black text-slate-400 uppercase">Tahun</label><input type="number" name="tahun_bansos" value="<?= date('Y') ?>" class="w-full p-3 bg-slate-50 dark:bg-slate-800 border-none rounded-lg font-bold"></div>
                <div><label class="text-[9px] font-black text-slate-400 uppercase">Program</label><input type="text" name="program_bansos" required class="w-full p-3 bg-slate-50 dark:bg-slate-800 border-none rounded-lg font-bold"></div>
            </div>
            <div class="flex gap-2 pt-4"><button type="button" onclick="closeModalTuntas()" class="flex-1 py-3 text-xs font-bold uppercase">Batal</button><button type="submit" class="flex-[2] py-3 bg-emerald-600 text-white rounded-xl text-xs font-black uppercase shadow-lg">Simpan</button></div>
        </form>
    </div>
</div>

<!-- MULTI-STEP MODAL RTLH (EDIT) -->
<div id="modal-rtlh" class="fixed inset-0 z-[10002] hidden overflow-y-auto py-10">
    <div class="fixed inset-0 bg-blue-950/60 backdrop-blur-sm transition-opacity" onclick="closeModalRtlh()"></div>
    <div class="relative flex items-center justify-center p-4 min-h-full">
        <div class="bg-white dark:bg-slate-900 w-full max-w-5xl rounded-[2.5rem] shadow-2xl overflow-hidden animate-in zoom-in duration-300">
            <div class="p-8 bg-blue-950 text-white flex justify-between items-center border-b border-white/10 sticky top-0 z-30">
                <div class="flex items-center gap-5">
                    <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-md border border-white/10"><i data-lucide="file-edit" class="w-6 h-6 text-blue-400"></i></div>
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
                <div class="modal-step" id="step-1">
                    <div class="p-10 grid grid-cols-1 lg:grid-cols-2 gap-10">
                        <div class="space-y-6">
                            <div><label class="text-[9px] font-black text-slate-400 uppercase">Nama</label><input type="text" name="nama_kepala_keluarga" id="inp_nama" required class="w-full p-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl font-bold"></div>
                            <div class="grid grid-cols-2 gap-4">
                                <div><label class="text-[9px] font-black text-slate-400 uppercase">NIK</label><input type="text" name="nik" id="inp_nik" readonly class="w-full p-4 bg-slate-100 dark:bg-slate-800 border-none rounded-2xl font-bold opacity-60"></div>
                                <div><label class="text-[9px] font-black text-slate-400 uppercase">No. KK</label><input type="text" name="no_kk" id="inp_no_kk" class="w-full p-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl font-bold"></div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div><label class="text-[9px] font-black text-slate-400 uppercase">Desa</label>
                                    <select name="desa_id" id="inp_desa_id" required class="w-full p-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl font-bold">
                                        <?php foreach($desa_list as $d): ?><option value="<?= $d['desa_id'] ?>"><?= $d['desa'] ?></option><?php endforeach; ?>
                                    </select>
                                </div>
                                <div><label class="text-[9px] font-black text-slate-400 uppercase">Luas Rumah (m²)</label><input type="number" step="0.01" name="luas_rumah_m2" id="inp_luas_rumah" class="w-full p-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl font-bold"></div>
                            </div>
                            <div><label class="text-[9px] font-black text-slate-400 uppercase">Alamat Detail</label><input type="text" name="alamat_detail" id="inp_alamat" class="w-full p-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl font-bold"></div>
                            <div><label class="text-[9px] font-black text-slate-400 uppercase">Koordinat</label><input type="text" name="lokasi_koordinat" id="inp_coords" readonly class="w-full p-4 bg-blue-50 dark:bg-blue-900/20 border-none rounded-2xl font-mono text-xs text-blue-600"></div>
                        </div>
                        <div id="modalMap" class="h-full min-h-[350px] w-full rounded-[2rem] border-4 border-slate-100 dark:border-slate-800 shadow-inner bg-slate-100"></div>
                    </div>
                </div>

                <div class="modal-step hidden" id="step-2">
                    <div class="p-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div class="space-y-6">
                            <h4 class="text-[10px] font-black text-blue-600 uppercase border-b pb-3 tracking-widest">Aset & Kawasan</h4>
                            <div><label class="text-[9px] font-black text-slate-400 uppercase">Milik Rumah</label><select name="kepemilikan_rumah" id="inp_milik_rumah" class="w-full p-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl font-bold text-xs"><?php foreach(($master['KEPEMILIKAN_RUMAH'] ?? []) as $rm): ?><option value="<?= $rm['id'] ?>"><?= $rm['nama_pilihan'] ?></option><?php endforeach; ?></select></div>
                            <div><label class="text-[9px] font-black text-slate-400 uppercase">Milik Tanah</label><select name="kepemilikan_tanah" id="inp_milik_tanah" class="w-full p-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl font-bold text-xs"><?php foreach(($master['KEPEMILIKAN_TANAH'] ?? []) as $rt): ?><option value="<?= $rt['id'] ?>"><?= $rt['nama_pilihan'] ?></option><?php endforeach; ?></select></div>
                            <div><label class="text-[9px] font-black text-slate-400 uppercase">Kawasan</label><select name="jenis_kawasan" id="inp_kawasan" class="w-full p-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl font-bold text-xs"><?php foreach(($master['JENIS_KAWASAN'] ?? []) as $jk): ?><option value="<?= $jk['id'] ?>"><?= $jk['nama_pilihan'] ?></option><?php endforeach; ?></select></div>
                        </div>
                        <div class="space-y-6">
                            <h4 class="text-[10px] font-black text-blue-600 uppercase border-b pb-3 tracking-widest">Utilitas</h4>
                            <div><label class="text-[9px] font-black text-slate-400 uppercase">Penerangan</label><select name="sumber_penerangan" id="inp_listrik" class="w-full p-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl font-bold text-xs"><?php foreach(($master['SUMBER_PENERANGAN'] ?? []) as $sp): ?><option value="<?= $sp['id'] ?>"><?= $sp['nama_pilihan'] ?></option><?php endforeach; ?></select></div>
                            <div><label class="text-[9px] font-black text-slate-400 uppercase">Air Minum</label><select name="sumber_air_minum" id="inp_air" class="w-full p-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl font-bold text-xs"><?php foreach(($master['SUMBER_AIR_MINUM'] ?? []) as $sa): ?><option value="<?= $sa['id'] ?>"><?= $sa['nama_pilihan'] ?></option><?php endforeach; ?></select></div>
                            <div><label class="text-[9px] font-black text-slate-400 uppercase">Desil</label><input type="text" name="desil_nasional" id="inp_desil" class="w-full p-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl font-bold text-xs"></div>
                        </div>
                        <div class="space-y-6">
                            <h4 class="text-[10px] font-black text-blue-600 uppercase border-b pb-3 tracking-widest">Kesehatan</h4>
                            <div><label class="text-[9px] font-black text-slate-400 uppercase">Jamban</label><select name="kamar_mandi_dan_jamban" id="inp_bab" class="w-full p-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl font-bold text-xs"><option value="SENDIRI">SENDIRI</option><option value="BERSAMA">BERSAMA</option><option value="TIDAK ADA">TIDAK ADA</option></select></div>
                            <div><label class="text-[9px] font-black text-slate-400 uppercase">Pembuangan Tinja</label><input type="text" name="jenis_tpa_tinja" id="inp_tpa" class="w-full p-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl font-bold text-xs"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-step hidden" id="step-3">
                    <div class="p-10 space-y-12">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                            <div class="col-span-full"><h4 class="text-xs font-black text-blue-600 uppercase tracking-widest border-l-4 border-blue-600 pl-4">III. Kondisi Fisik</h4></div>
                            <?php 
                                $fields = [['st_pondasi', 'Pondasi'], ['st_tiang', 'Tiang'], ['st_balok', 'Balok'], ['st_sloof', 'Sloof'], ['st_rangka_atap', 'Rangka'], ['st_plafon', 'Plafon'], ['st_jendela', 'Jendela'], ['st_ventilasi', 'Ventilasi'], ['st_dinding', 'Dinding'], ['st_lantai', 'Lantai'], ['st_atap', 'Atap']];
                                foreach($fields as $k):
                            ?>
                            <div><label class="text-[9px] font-black text-slate-400 uppercase"><?= $k[1] ?></label><select name="<?= $k[0] ?>" id="inp_<?= $k[0] ?>" class="w-full p-3 bg-slate-50 dark:bg-slate-800 border-none rounded-xl font-bold text-[10px]"><?php foreach(($master['KONDISI'] ?? []) as $opt): ?><option value="<?= $opt['id'] ?>"><?= $opt['nama_pilihan'] ?></option><?php endforeach; ?></select></div>
                            <?php endforeach; ?>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                            <div class="col-span-full"><h4 class="text-xs font-black text-rose-600 uppercase border-l-4 border-rose-600 pl-4 uppercase">IV. Foto</h4></div>
                            <?php foreach(['foto_depan', 'foto_samping', 'foto_belakang', 'foto_dalam'] as $f): ?>
                            <div class="relative aspect-video rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-800 flex items-center justify-center overflow-hidden cursor-pointer" onclick="document.getElementById('inp_<?= $f ?>').click()"><img id="prev_<?= $f ?>" class="absolute inset-0 w-full h-full object-cover hidden"><div id="placeholder_<?= $f ?>" class="text-slate-300"><i data-lucide="image-plus" class="w-6 h-6"></i></div><input type="file" name="<?= $f ?>" id="inp_<?= $f ?>" class="hidden" onchange="previewImg(this, '<?= $f ?>')"></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="p-8 bg-slate-50 dark:bg-slate-900 border-t dark:border-slate-800 flex justify-between items-center">
                    <button type="button" id="btn-prev" onclick="moveStep(-1)" class="hidden px-8 py-3 bg-white dark:bg-slate-800 text-slate-500 rounded-2xl font-black uppercase text-[10px] border border-slate-200">Sebelumnya</button>
                    <div class="flex gap-4 ml-auto">
                        <button type="button" onclick="closeModalRtlh()" class="px-6 py-3 text-slate-400 font-black uppercase text-[10px]">Batal</button>
                        <button type="button" id="btn-next" onclick="moveStep(1)" class="px-10 py-3 bg-blue-950 dark:bg-blue-600 text-white rounded-2xl font-black uppercase text-[10px] shadow-xl">Selanjutnya</button>
                        <button type="submit" id="btn-save" class="hidden px-10 py-3 bg-emerald-600 text-white rounded-2xl font-black uppercase text-[10px] shadow-xl">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Image Viewer -->
<div id="image-viewer" class="fixed inset-0 z-[10005] hidden flex items-center justify-center p-10 bg-black/95 backdrop-blur-md" onclick="closeImageViewer()">
    <img id="viewer-img" class="max-w-full max-h-full object-contain rounded-xl">
    <p id="viewer-lbl" class="absolute bottom-10 text-white font-black uppercase tracking-widest"></p>
</div>

<script>
    // Centralized Data from PHP
    const DATA = {
        rumah: <?= json_encode($rumah ?: (object)[]) ?>,
        penerima: <?= json_encode($penerima ?: (object)[]) ?>,
        kondisi: <?= json_encode($kondisi ?: (object)[]) ?>,
        uploadUrl: <?= json_encode(base_url('uploads/rtlh/')) ?>
    };

    let map, m_map, m_marker;
    let m_currentStep = 1;

    function initMap() {
        if (typeof L === 'undefined') { setTimeout(initMap, 100); return; }
        const wkt = (DATA.rumah && DATA.rumah.wkt) || '';
        const match = wkt.match(/POINT\s*\(\s*([-\d.]+)\s+([-\d.]+)\s*\)/i);
        if (!match) return;
        
        const lng = parseFloat(match[1]), lat = parseFloat(match[2]);
        const el = document.getElementById('coords-text');
        if (el) el.innerText = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
        
        const isDark = document.documentElement.classList.contains('dark');
        const tile = L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png');
        
        map = L.map('map-detail', { zoomControl: false, layers: [tile] }).setView([lat, lng], 18);
        L.control.zoom({ position: 'topright' }).addTo(map);
        L.circleMarker([lat, lng], { radius: 10, fillColor: '#2563eb', color: '#fff', weight: 4, fillOpacity: 1 }).addTo(map);
        setTimeout(() => map.invalidateSize(), 500);
    }

    function openEditModal() {
        try {
            const modal = document.getElementById('modal-rtlh');
            if (!modal) {
                console.error('Modal element not found');
                return;
            }
            
            // Show modal first so user sees something is happening
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            const r = DATA.rumah || {};
            const p = DATA.penerima || {};
            const c = DATA.kondisi || {};

            // Populate basic fields
            const fields = {
                'inp_nama': p.nama_kepala_keluarga || '',
                'inp_nik': r.nik_pemilik || '',
                'inp_no_kk': p.no_kk || '',
                'inp_desa_id': r.desa_id || '',
                'inp_luas_rumah': r.luas_rumah_m2 || '',
                'inp_alamat': r.alamat_detail || '',
                'inp_coords': r.wkt || '',
                'inp_milik_rumah': r.kepemilikan_rumah || '',
                'inp_milik_tanah': r.kepemilikan_tanah || '',
                'inp_kawasan': r.jenis_kawasan || '',
                'inp_listrik': r.sumber_penerangan || '',
                'inp_air': r.sumber_air_minum || '',
                'inp_desil': r.desil_nasional || '',
                'inp_bab': r.kamar_mandi_dan_jamban || 'SENDIRI',
                'inp_tpa': r.jenis_tpa_tinja || ''
            };

            for (const [id, val] of Object.entries(fields)) {
                const el = document.getElementById(id);
                if (el) el.value = val;
            }

            // Technical fields - Note: mapping st_kolom (DB) to inp_st_tiang (Form)
            const techFields = ['st_pondasi', 'st_balok', 'st_sloof', 'st_rangka_atap', 'st_plafon', 'st_jendela', 'st_ventilasi', 'st_dinding', 'st_lantai', 'st_atap'];
            techFields.forEach(f => {
                const el = document.getElementById('inp_' + f);
                if (el) el.value = c[f] || '';
            });
            
            // Special handle for st_kolom/st_tiang
            const elTiang = document.getElementById('inp_st_tiang');
            if (elTiang) elTiang.value = c.st_kolom || c.st_tiang || '';

            // Photos
            ['foto_depan', 'foto_samping', 'foto_belakang', 'foto_dalam'].forEach(f => {
                const prev = document.getElementById('prev_' + f);
                const placeholder = document.getElementById('placeholder_' + f);
                if (prev) {
                    if (r[f]) {
                        prev.src = DATA.uploadUrl + r[f];
                        prev.classList.remove('hidden');
                        if (placeholder) placeholder.classList.add('hidden');
                    } else {
                        prev.classList.add('hidden');
                        if (placeholder) placeholder.classList.remove('hidden');
                    }
                }
            });

            showStep(1);
            if (typeof lucide !== 'undefined') lucide.createIcons();
            
            setTimeout(() => {
                try {
                    if (!m_map) {
                        m_map = L.map('modalMap', { zoomControl: false }).setView([-5.1245, 120.2536], 12);
                        L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', { 
                            subdomains:['mt0','mt1','mt2','mt3'],
                            maxZoom: 20
                        }).addTo(m_map);
                        
                        m_map.on('click', (e) => {
                            const latlng = e.latlng;
                            if (m_marker) m_marker.setLatLng(latlng);
                            else m_marker = L.marker(latlng, { draggable: true }).addTo(m_map);
                            document.getElementById('inp_coords').value = `POINT(${latlng.lng.toFixed(7)} ${latlng.lat.toFixed(7)})`;
                        });
                    }
                    
                    m_map.invalidateSize();
                    
                    if (r.wkt && typeof wellknown !== 'undefined') {
                        const geo = wellknown.parse(r.wkt);
                        if (geo && geo.coordinates) {
                            const ll = [geo.coordinates[1], geo.coordinates[0]];
                            if (m_marker) m_marker.setLatLng(ll);
                            else m_marker = L.marker(ll, { draggable: true }).addTo(m_map);
                            m_map.setView(ll, 19);
                        }
                    }
                } catch (mapErr) {
                    console.error('Error initializing modal map:', mapErr);
                }
            }, 300);
        } catch (err) {
            console.error('Error opening edit modal:', err);
            if (typeof showToast === 'function') showToast('Gagal memuat form edit: ' + err.message, 'error');
        }
    }

    function moveStep(delta) { m_currentStep += delta; showStep(m_currentStep); }
    function showStep(s) {
        m_currentStep = s;
        document.querySelectorAll('.modal-step').forEach((el, i) => el.classList.toggle('hidden', i + 1 !== s));
        const dots = document.querySelectorAll('.step-dot');
        dots.forEach((dot, i) => {
            if (i+1 === s) dot.className = "step-dot w-6 h-6 rounded-full bg-blue-600 text-[11px] font-black flex items-center justify-center text-white ring-4 ring-blue-500/20";
            else if (i+1 < s) { dot.className = "step-dot w-6 h-6 rounded-full bg-emerald-500 text-[11px] font-black flex items-center justify-center text-white"; dot.innerHTML = "✓"; }
            else { dot.className = "step-dot w-6 h-6 rounded-full bg-white/10 text-[11px] font-black flex items-center justify-center text-white/40"; dot.innerHTML = i+1; }
        });
        document.getElementById('btn-prev').classList.toggle('hidden', s === 1);
        document.getElementById('btn-next').classList.toggle('hidden', s === 3);
        document.getElementById('btn-save').classList.toggle('hidden', s !== 3);
        if (s === 1 && m_map) setTimeout(() => m_map.invalidateSize(), 100);
    }

    function previewImg(input, target) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const prev = document.getElementById('prev_' + target);
                const placeholder = document.getElementById('placeholder_' + target);
                if (prev) { prev.src = e.target.result; prev.classList.remove('hidden'); }
                if (placeholder) placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function viewImage(src, lbl) {
        document.getElementById('viewer-img').src = src;
        document.getElementById('viewer-lbl').innerText = lbl;
        document.getElementById('image-viewer').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeImageViewer() {
        document.getElementById('image-viewer').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function focusMap() { if (map) map.setView(map.getCenter(), 18); }
    function closeModalRtlh() { document.getElementById('modal-rtlh').classList.add('hidden'); document.body.style.overflow = ''; }
    function openModalTuntas() { document.getElementById('modal-tuntas').classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
    function closeModalTuntas() { document.getElementById('modal-tuntas').classList.add('hidden'); document.body.style.overflow = ''; }

    function downloadPDF() {
        const element = document.getElementById('report-content');
        document.body.classList.add('is-exporting');
        const opt = {
            margin: 0.5,
            filename: `Laporan_RTLH_${DATA.penerima.nama_kepala_keluarga || 'Data'}.pdf`,
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true },
            jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
        };
        html2pdf().set(opt).from(element).save().then(() => document.body.classList.remove('is-exporting'));
    }

    window.addEventListener('load', initMap);
    lucide.createIcons();
</script>

<style>
    .is-exporting .no-print { display: none !important; }
    @media print { .no-print { display: none !important; } }
</style>
<?= $this->endSection() ?>
