<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="space-y-8 pb-20">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <div class="flex items-center gap-3">
                <a href="<?= base_url('perumahan-formal') ?>" class="p-3 bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 hover:bg-blue-50 dark:hover:bg-slate-800 transition-all group">
                    <i data-lucide="arrow-left" class="w-5 h-5 text-slate-400 group-hover:text-blue-600"></i>
                </a>
                <div>
                    <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.3em]">Detail Perumahan Formal</p>
                    <h1 class="text-3xl font-black text-blue-950 dark:text-white uppercase tracking-tight"><?= $item['nama_perumahan'] ?></h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Ringkasan Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-indigo-600 rounded-[2rem] p-6 text-white shadow-xl shadow-indigo-900/20 relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-[9px] font-black uppercase tracking-[0.2em] opacity-80 mb-1">Luas Kawasan</p>
                <h4 class="text-2xl font-black italic"><?= number_format($item['luas_kawasan_ha'], 2) ?> <span class="text-xs font-medium not-italic opacity-70">Ha</span></h4>
            </div>
            <i data-lucide="maximize" class="absolute top-1/2 right-6 -translate-y-1/2 w-16 h-16 opacity-10 group-hover:scale-110 transition-transform duration-500"></i>
        </div>
        <div class="bg-white dark:bg-slate-900 rounded-[2rem] p-6 border border-slate-100 dark:border-slate-800 shadow-sm flex items-center gap-5">
            <div class="w-12 h-12 bg-blue-50 dark:bg-blue-950/30 rounded-2xl flex items-center justify-center text-blue-600">
                <i data-lucide="building" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[9px] text-slate-400 font-black uppercase tracking-[0.2em]">Tahun Pembangunan</p>
                <p class="text-sm font-black text-blue-950 dark:text-white uppercase"><?= $item['tahun_pembangunan'] ?></p>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 rounded-[2rem] p-6 border border-slate-100 dark:border-slate-800 shadow-sm flex items-center gap-5">
            <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-950/30 rounded-2xl flex items-center justify-center text-emerald-600">
                <i data-lucide="user-check" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[9px] text-slate-400 font-black uppercase tracking-[0.2em]">Pengembang</p>
                <p class="text-sm font-black text-blue-950 dark:text-white truncate max-w-[150px]"><?= $item['pengembang'] ?></p>
            </div>
        </div>
    </div>

    <!-- Konten Utama -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Peta (Visualisasi) -->
        <div class="lg:col-span-8">
            <div class="relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-[3rem] blur opacity-10 group-hover:opacity-20 transition duration-1000"></div>
                <div class="relative bg-white dark:bg-slate-900 rounded-[3rem] overflow-hidden shadow-2xl border border-slate-100 dark:border-slate-800">
                    <div id="map" class="w-full h-[60vh] z-10" style="min-height: 500px;"></div>
                </div>
            </div>
        </div>

        <!-- Detail Atribut -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-10 shadow-sm border border-slate-100 dark:border-slate-800 relative overflow-hidden">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 bg-blue-950 rounded-xl flex items-center justify-center text-white">
                        <i data-lucide="info" class="w-5 h-5"></i>
                    </div>
                    <h3 class="text-xs font-black text-blue-950 dark:text-white uppercase tracking-widest">Informasi Lengkap</h3>
                </div>

                <div class="space-y-6">
                    <div>
                        <p class="text-[9px] text-slate-400 font-black uppercase tracking-[0.2em] mb-1">Nama Perumahan</p>
                        <p class="text-sm font-bold text-blue-950 dark:text-white uppercase"><?= $item['nama_perumahan'] ?></p>
                    </div>
                    <div>
                        <p class="text-[9px] text-slate-400 font-black uppercase tracking-[0.2em] mb-1">Pengembang</p>
                        <p class="text-sm font-bold text-blue-950 dark:text-white uppercase"><?= $item['pengembang'] ?></p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-[9px] text-slate-400 font-black uppercase tracking-[0.2em] mb-1">Longitude</p>
                            <p class="text-xs font-bold text-slate-600 dark:text-slate-400"><?= $item['longitude'] ?></p>
                        </div>
                        <div>
                            <p class="text-[9px] text-slate-400 font-black uppercase tracking-[0.2em] mb-1">Latitude</p>
                            <p class="text-xs font-bold text-slate-600 dark:text-slate-400"><?= $item['latitude'] ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-slate-950 rounded-[2.5rem] p-10 text-white shadow-2xl flex flex-col justify-center gap-4">
                <a href="<?= base_url('perumahan-formal/edit/' . $item['id']) ?>" class="w-full py-4 bg-amber-500 hover:bg-amber-400 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center justify-center gap-3 shadow-lg shadow-amber-900/20">
                    <i data-lucide="edit-3" class="w-4 h-4"></i> Edit Data Perumahan
                </a>
                <button onclick="map.setView([<?= $item['latitude'] ?>, <?= $item['longitude'] ?>], 18)" class="w-full py-4 bg-blue-600 hover:bg-blue-500 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center justify-center gap-3">
                    <i data-lucide="map-pin" class="w-4 h-4"></i> Fokus Lokasi
                </button>
                <a href="<?= base_url('perumahan-formal') ?>" class="w-full py-4 bg-white/5 hover:bg-white/10 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center justify-center gap-3 border border-white/5">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    let map;
    function initMap() {
        if (typeof L === 'undefined') { setTimeout(initMap, 100); return; }
        const isDark = document.documentElement.classList.contains('dark');
        const standard = L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { attribution: '&copy; Sibaruki' });
        
        const coords = [<?= $item['latitude'] ?>, <?= $item['longitude'] ?>];
        map = L.map('map', { layers: [standard], zoomControl: false }).setView(coords, 17);
        L.control.zoom({ position: 'topright' }).addTo(map);

        L.circleMarker(coords, { radius: 12, fillColor: "#1e1b4b", color: "#fff", weight: 3, fillOpacity: 1 }).addTo(map);
        
        setTimeout(() => { map.invalidateSize(); }, 500);
    }
    window.addEventListener('load', initMap);
</script>
<?= $this->endSection() ?>
