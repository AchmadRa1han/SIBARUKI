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
    <!-- DEBUG MARKER: v2.0-REUSABLE-MODAL -->
    <div class="hidden">SIBARUKI_DEBUG: REUSABLE_MODAL_ACTIVE</div>
    
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
        rumah: <?= json_encode($rumah ?: (object)[], JSON_UNESCAPED_UNICODE) ?: '{}' ?>,
        penerima: <?= json_encode($penerima ?: (object)[], JSON_UNESCAPED_UNICODE) ?: '{}' ?>,
        kondisi: <?= json_encode($kondisi ?: (object)[], JSON_UNESCAPED_UNICODE) ?: '{}' ?>
    };

    let map;

    function initMap() {
        if (typeof L === 'undefined') { setTimeout(initMap, 100); return; }
        try {
            const wkt = (PAGE_DATA.rumah && PAGE_DATA.rumah.wkt) || '';
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

    function downloadPDF() {
        if (typeof html2pdf === 'undefined') { alert('PDF library not loaded'); return; }
        const element = document.getElementById('report-content');
        document.body.classList.add('is-exporting');
        const opt = {
            margin: 0.5,
            filename: `Laporan_RTLH_${(PAGE_DATA.penerima && PAGE_DATA.penerima.nama_kepala_keluarga) || 'Data'}.pdf`,
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true },
            jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
        };
        html2pdf().set(opt).from(element).save().then(() => document.body.classList.remove('is-exporting'));
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
