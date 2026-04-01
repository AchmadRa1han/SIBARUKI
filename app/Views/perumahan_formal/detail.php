<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<!-- Leaflet Assets -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="max-w-7xl mx-auto space-y-8 pb-12">
    
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-3 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 no-print">
        <a href="<?= base_url('dashboard') ?>" class="hover:text-blue-600 transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <a href="<?= base_url('perumahan-formal') ?>" class="hover:text-blue-600 transition-colors">Perumahan Formal</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">Detail Kawasan</span>
    </nav>

    <!-- Header Action -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white dark:bg-slate-900 p-10 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm transition-all duration-300 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-blue-600/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
        <div class="relative z-10 flex items-center gap-6">
            <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-blue-600/20">
                <i data-lucide="building-2" class="w-8 h-8"></i>
            </div>
            <div>
                <h1 class="text-3xl md:text-4xl font-black text-blue-950 dark:text-white uppercase tracking-tighter"><?= $item['nama_perumahan'] ?></h1>
                <p class="text-sm text-slate-500 font-bold uppercase tracking-widest mt-1">Pengembang: <?= $item['pengembang'] ?></p>
            </div>
        </div>
        <div class="flex items-center gap-3 relative z-10">
            <?php if (has_permission('edit_rtlh')): ?>
            <a href="<?= base_url('perumahan-formal/edit/'.$item['id']) ?>" class="px-8 py-4 bg-amber-500 hover:bg-amber-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-amber-500/20 transition-all active:scale-95 flex items-center gap-3">
                <i data-lucide="edit-3" class="w-5 h-5"></i> Edit Data
            </a>
            <?php endif; ?>
            <a href="<?= base_url('perumahan-formal') ?>" class="px-8 py-4 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-slate-700 transition-all active:scale-95">Kembali</a>
        </div>
    </div>

    <!-- Ringkasan Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white dark:bg-slate-900 p-10 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm group relative overflow-hidden transition-all hover:shadow-xl hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-600/5 rounded-full -mr-16 -mt-16 blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="flex items-center justify-between mb-6 relative z-10">
                <div class="w-14 h-14 bg-blue-50 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center text-blue-600 dark:text-blue-400 shadow-inner">
                    <i data-lucide="maximize" class="w-7 h-7"></i>
                </div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Luas Kawasan</span>
            </div>
            <h3 class="text-5xl font-black text-blue-950 dark:text-white tracking-tighter mb-2 relative z-10"><?= number_format($item['luas_kawasan_ha'], 2) ?><span class="text-xl ml-1 text-slate-400">Ha</span></h3>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest relative z-10">Total Area Perumahan</p>
        </div>

        <div class="bg-white dark:bg-slate-900 p-10 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm group relative overflow-hidden transition-all hover:shadow-xl hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-600/5 rounded-full -mr-16 -mt-16 blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="flex items-center justify-between mb-6 relative z-10">
                <div class="w-14 h-14 bg-emerald-50 dark:bg-emerald-900/30 rounded-2xl flex items-center justify-center text-emerald-600 dark:text-emerald-400 shadow-inner">
                    <i data-lucide="calendar" class="w-7 h-7"></i>
                </div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Tahun Bangun</span>
            </div>
            <h3 class="text-5xl font-black text-emerald-600 tracking-tighter mb-2 relative z-10"><?= $item['tahun_pembangunan'] ?></h3>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest relative z-10">Periode Pelaksanaan</p>
        </div>

        <div class="bg-blue-950 p-10 rounded-[2.5rem] shadow-2xl shadow-blue-950/20 relative overflow-hidden transition-all hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-400/10 rounded-full -mr-16 -mt-16 blur-2xl"></div>
            <div class="flex items-center justify-between mb-6 relative z-10">
                <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center text-white">
                    <i data-lucide="map-pin" class="w-7 h-7"></i>
                </div>
                <span class="text-[10px] font-black text-blue-300/40 uppercase tracking-[0.2em]">Status Spasial</span>
            </div>
            <h3 class="text-2xl font-black text-white tracking-tight mb-2 relative z-10 uppercase"><?= $item['wkt'] ? 'Polygon Map' : 'Point Only' ?></h3>
            <p class="text-[10px] text-blue-300/60 font-bold uppercase tracking-widest relative z-10">Visualisasi Geospasial</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Peta (Visualisasi) -->
        <div class="lg:col-span-8">
            <div class="bg-white dark:bg-slate-900 rounded-[3rem] overflow-hidden shadow-2xl border border-slate-100 dark:border-slate-800 relative group aspect-video lg:aspect-auto lg:h-[600px]">
                <div id="map" class="w-full h-full z-10" style="background: #f8fafc;"></div>
                
                <div class="absolute top-8 left-8 z-[1000] flex flex-col gap-2">
                    <div class="bg-blue-950/90 backdrop-blur-xl text-white px-5 py-3 rounded-2xl shadow-2xl border border-white/10 flex items-center gap-4">
                        <div class="w-2 h-2 bg-blue-400 rounded-full animate-ping"></div>
                        <span class="text-[10px] font-black uppercase tracking-[0.2em]">Live Map Preview</span>
                    </div>
                </div>

                <div class="absolute bottom-8 right-8 z-[1000]">
                    <button onclick="focusLocation()" class="p-4 bg-white dark:bg-slate-900 rounded-2xl shadow-2xl text-blue-600 hover:scale-110 active:scale-95 transition-all border border-slate-100 dark:border-slate-800">
                        <i data-lucide="maximize" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Detail Atribut -->
        <div class="lg:col-span-4 space-y-8">
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-10 shadow-sm border border-slate-100 dark:border-slate-800 relative overflow-hidden transition-all duration-300">
                <div class="absolute top-0 right-0 p-10 opacity-[0.03] pointer-events-none text-blue-950 dark:text-white">
                    <i data-lucide="building" class="w-40 h-40"></i>
                </div>

                <h3 class="text-xs font-black text-blue-950 dark:text-white uppercase tracking-[0.3em] mb-10 flex items-center gap-4">
                    <span class="w-10 h-[2px] bg-blue-600"></span> Informasi Kawasan
                </h3>

                <div class="space-y-10 relative z-10">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Nama Perumahan</p>
                        <p class="text-base font-black text-blue-950 dark:text-white uppercase leading-relaxed"><?= $item['nama_perumahan'] ?></p>
                    </div>

                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Pengembang / Developer</p>
                        <p class="text-sm font-bold text-slate-700 dark:text-slate-200 uppercase leading-relaxed"><?= $item['pengembang'] ?></p>
                    </div>

                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Latitude</p>
                            <p class="text-sm font-mono font-bold text-slate-700 dark:text-slate-300"><?= $item['latitude'] ?></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Longitude</p>
                            <p class="text-sm font-mono font-bold text-slate-700 dark:text-slate-300"><?= $item['longitude'] ?></p>
                        </div>
                    </div>

                    <div class="pt-8 border-t border-slate-50 dark:border-slate-800">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Metode Pemetaan</p>
                        <div class="bg-slate-50 dark:bg-slate-800/50 p-5 rounded-[1.5rem] border border-slate-100 dark:border-slate-800 flex items-center gap-4">
                            <div class="w-10 h-10 bg-white dark:bg-slate-900 rounded-xl flex items-center justify-center text-blue-600 shadow-sm">
                                <i data-lucide="<?= $item['wkt'] ? 'layers' : 'map-pin' ?>" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-blue-950 dark:text-white uppercase tracking-tight"><?= $item['wkt'] ? 'POLYGON (WKT)' : 'POINT DATA' ?></p>
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">WGS84 Projection</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kartu Sistem Info -->
            <div class="bg-slate-900 rounded-[2.5rem] p-10 text-white shadow-2xl relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:scale-110 transition-transform duration-700">
                    <i data-lucide="info" class="w-48 h-48"></i>
                </div>
                <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-blue-400 mb-8 flex items-center gap-3">
                    Metadata Inventaris
                </h4>
                <div class="space-y-6 relative z-10">
                    <div class="flex justify-between items-center text-[10px]">
                        <span class="font-bold text-slate-500 uppercase tracking-widest">Entry Date</span>
                        <span class="font-black text-slate-300 tracking-wider"><?= date('d/m/Y H:i', strtotime($item['created_at'])) ?></span>
                    </div>
                    <div class="flex justify-between items-center text-[10px]">
                        <span class="font-bold text-slate-500 uppercase tracking-widest">Last Update</span>
                        <span class="font-black text-blue-400 tracking-wider"><?= date('d/m/Y H:i', strtotime($item['updated_at'])) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let map;
    function initMap() {
        if (typeof L === 'undefined') { setTimeout(initMap, 100); return; }
        const isDark = document.documentElement.classList.contains('dark');
        const standard = L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { attribution: '&copy; Sibaruki', maxZoom: 20 });
        
        const coords = [<?= $item['latitude'] ?>, <?= $item['longitude'] ?>];
        map = L.map('map', { layers: [standard], zoomControl: false }).setView(coords, 17);
        L.control.zoom({ position: 'bottomright' }).addTo(map);

        L.circleMarker(coords, { radius: 12, fillColor: "#2563eb", color: "#fff", weight: 4, fillOpacity: 1, opacity: 1 }).addTo(map);
        
        setTimeout(() => { map.invalidateSize(); }, 500);
    }

    function focusLocation() {
        map.setView([<?= $item['latitude'] ?>, <?= $item['longitude'] ?>], 18, { animate: true, duration: 1 });
    }

    window.addEventListener('load', initMap);
</script>
<?= $this->endSection() ?>
