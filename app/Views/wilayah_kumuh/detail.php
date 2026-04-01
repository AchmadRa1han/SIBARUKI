<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<!-- Library Peta -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="max-w-7xl mx-auto space-y-8 pb-12">
    
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-3 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 no-print">
        <a href="<?= base_url('dashboard') ?>" class="hover:text-blue-600 transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <a href="<?= base_url('wilayah-kumuh') ?>" class="hover:text-blue-600 transition-colors">Wilayah Kumuh</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">Detail Delineasi</span>
    </nav>

    <!-- Header Action -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white dark:bg-slate-900 p-10 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm transition-all duration-300 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-rose-600/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
        <div class="relative z-10 flex items-center gap-6">
            <div class="w-16 h-16 bg-rose-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-rose-600/20">
                <i data-lucide="layers" class="w-8 h-8"></i>
            </div>
            <div>
                <h1 class="text-3xl md:text-4xl font-black text-blue-950 dark:text-white uppercase tracking-tighter"><?= $kumuh['Kelurahan'] ?></h1>
                <p class="text-sm text-slate-500 font-bold uppercase tracking-widest mt-1">Kecamatan <?= $kumuh['Kecamatan'] ?></p>
            </div>
        </div>
        <div class="flex items-center gap-3 relative z-10">
            <?php if (has_permission('edit_kumuh')) : ?>
            <a href="<?= base_url('wilayah-kumuh/edit/' . $kumuh['FID']) ?>" class="px-8 py-4 bg-amber-500 hover:bg-amber-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-amber-500/20 transition-all active:scale-95 flex items-center gap-3">
                <i data-lucide="edit-3" class="w-5 h-5"></i> Edit Data
            </a>
            <?php endif; ?>
            <button onclick="window.print()" class="p-4 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-2xl hover:bg-blue-600 hover:text-white transition-all active:scale-95 shadow-sm no-print" title="Cetak Laporan">
                <i data-lucide="printer" class="w-6 h-6"></i>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar Info -->
        <div class="space-y-8">
            <!-- Skor Kumuh -->
            <div class="bg-rose-600 p-10 rounded-[2.5rem] text-white shadow-2xl shadow-rose-900/20 relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-700">
                    <i data-lucide="trending-up" class="w-48 h-48"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-rose-100 mb-2">Total Skor Kekumuhan</p>
                    <div class="flex items-baseline gap-2">
                        <span class="text-6xl font-black italic"><?= $kumuh['skor_kumuh'] ?></span>
                        <span class="text-sm font-bold uppercase opacity-60">Poin</span>
                    </div>
                    <div class="mt-6 pt-6 border-t border-white/10">
                        <p class="text-[10px] font-bold text-rose-100 uppercase tracking-widest">Status Kerawanan</p>
                        <p class="text-sm font-black uppercase mt-1">
                            <?php if($kumuh['skor_kumuh'] >= 60): ?>Sangat Berat
                            <?php elseif($kumuh['skor_kumuh'] >= 40): ?>Sedang
                            <?php else: ?>Ringan<?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Atribut Wilayah -->
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-10 shadow-sm border border-slate-100 dark:border-slate-800 transition-all duration-300">
                <h3 class="text-xs font-black text-blue-950 dark:text-white uppercase tracking-[0.3em] mb-10 flex items-center gap-4">
                    <span class="w-10 h-[2px] bg-blue-600"></span> Identitas Administrasi
                </h3>
                <div class="space-y-10">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Nama Kawasan</p>
                        <p class="text-sm font-black text-slate-700 dark:text-white uppercase leading-relaxed"><?= $kumuh['Kawasan'] ?: '-' ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Luas Delineasi</p>
                        <p class="text-2xl font-black text-blue-600 italic"><?= number_format($kumuh['Luas_kumuh'], 2) ?><span class="text-xs ml-1 opacity-60">Ha</span></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Kode RT / RW</p>
                        <p class="text-sm font-black text-slate-700 dark:text-white uppercase"><?= $kumuh['Kode_RT_RW'] ?: '-' ?></p>
                    </div>
                    <div class="pt-6 border-t dark:border-slate-800 grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Kode Kel.</p>
                            <p class="text-xs font-mono font-bold text-slate-600 dark:text-slate-400"><?= $kumuh['Kode_Kel'] ?></p>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Kode Kec.</p>
                            <p class="text-xs font-mono font-bold text-slate-600 dark:text-slate-400"><?= $kumuh['Kode_Kec'] ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Map Content -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white dark:bg-slate-900 rounded-[3rem] overflow-hidden shadow-2xl border border-slate-100 dark:border-slate-800 relative group lg:h-[750px]">
                <div id="map" class="w-full h-full z-10" style="background: #f8fafc;"></div>
                
                <div class="absolute top-8 left-8 z-[1000] flex flex-col gap-2">
                    <div class="bg-blue-950/90 backdrop-blur-xl text-white px-5 py-3 rounded-2xl shadow-2xl border border-white/10 flex items-center gap-4">
                        <div class="w-2 h-2 bg-rose-500 rounded-full animate-ping"></div>
                        <span class="text-[10px] font-black uppercase tracking-[0.2em]">Visualisasi Spasial</span>
                    </div>
                </div>

                <div class="absolute bottom-8 right-8 z-[1000] flex flex-col gap-3">
                    <button onclick="map.fitBounds(polyLayer.getBounds(), {padding:[50,50]})" class="p-4 bg-white dark:bg-slate-900 rounded-2xl shadow-2xl text-blue-600 hover:scale-110 active:scale-95 transition-all border border-slate-100 dark:border-slate-800">
                        <i data-lucide="maximize" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Dokumen Dasar Hukum</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-slate-50 dark:bg-slate-800 rounded-2xl flex items-center justify-center text-blue-600">
                            <i data-lucide="file-text" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">No. SK Penetapan</p>
                            <p class="text-sm font-black text-blue-950 dark:text-white uppercase"><?= $kumuh['Sk_Kumuh'] ?: 'BELUM TERDAFTAR' ?></p>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Verifikasi Informasi</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-slate-50 dark:bg-slate-800 rounded-2xl flex items-center justify-center text-emerald-600">
                            <i data-lucide="database" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Sumber Basis Data</p>
                            <p class="text-sm font-black text-blue-950 dark:text-white uppercase"><?= $kumuh['Sumber_data'] ?: '-' ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
        initMap();
    });

    let map, polyLayer;
    function initMap() {
        const rawWkt = <?= json_encode($kumuh['WKT']) ?>;
        if (!rawWkt) return;

        function extractCoordinates(text) {
            const regex = /(-?\d+\.\d+)\s+(-?\d+\.\d+)/g;
            let match;
            const points = [];
            while ((match = regex.exec(text)) !== null) points.push([parseFloat(match[2]), parseFloat(match[1])]);
            return points;
        }

        try {
            const coords = extractCoordinates(rawWkt);
            if (coords.length === 0) return;

            const isDark = document.documentElement.classList.contains('dark');
            const tileUrl = isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png';
            const satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: 'Esri' });

            map = L.map('map', { zoomControl: false, layers: [satellite] }).setView(coords[0], 17);
            L.control.zoom({ position: 'bottomright' }).addTo(map);

            const color = <?= $kumuh['skor_kumuh'] ?> >= 60 ? '#f43f5e' : (<?= $kumuh['skor_kumuh'] ?> >= 40 ? '#f97316' : '#f59e0b');

            if (coords.length > 1) {
                polyLayer = L.polygon(coords, { color: color, weight: 4, fillOpacity: 0.4, fillColor: color }).addTo(map);
                // Glow effect
                L.polygon(coords, { color: color, weight: 10, fillOpacity: 0, opacity: 0.2 }).addTo(map);
                map.fitBounds(polyLayer.getBounds(), { padding: [50, 50], animate: true, duration: 1.5 });
            } else {
                polyLayer = L.circleMarker(coords[0], { radius: 15, fillColor: color, color: '#fff', weight: 4, fillOpacity: 1 }).addTo(map);
                map.setView(coords[0], 18);
            }

            setTimeout(() => map.invalidateSize(), 500);
        } catch (e) { console.error('Map Error:', e); }
    }
</script>

<style>
    @media print {
        header, aside, .no-print, .shadow-sm, .shadow-2xl { display: none !important; }
        .max-w-7xl { max-width: 100% !important; padding: 0 !important; }
        .grid { display: block !important; }
        .bg-rose-600 { background-color: #f43f5e !important; color: white !important; -webkit-print-color-adjust: exact; }
        #map { height: 500px !important; }
    }
</style>
<?= $this->endSection() ?>
