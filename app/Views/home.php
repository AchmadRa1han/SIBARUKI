<?= $this->extend('visitor_layout') ?>

<?= $this->section('content') ?>
<!-- External Assets for Map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/wellknown@0.5.0/wellknown.js"></script>

<div class="relative overflow-hidden">
    
    <!-- 1. HERO SECTION -->
    <section id="hero" class="relative min-h-[80vh] flex items-center pt-20">
        <div class="absolute inset-0 bg-slate-50 dark:bg-slate-950"></div>
        <div class="max-w-7xl mx-auto px-6 lg:px-12 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center relative z-10">
            <div class="animate-in slide-in-from-left duration-1000">
                <div class="flex items-center gap-3 mb-6">
                    <span class="px-4 py-1.5 bg-blue-600/10 text-blue-600 text-[10px] font-black uppercase tracking-widest rounded-full border border-blue-600/20">Official Portal</span>
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                </div>
                <h1 class="text-5xl lg:text-7xl font-black text-blue-950 dark:text-white leading-[1.1] mb-8 tracking-tighter">
                    Membangun <span class="text-blue-600">Hunian Layak</span> Untuk Semua.
                </h1>
                <p class="text-lg text-slate-500 dark:text-slate-400 leading-relaxed mb-10 max-w-xl font-medium">
                    SIBARUKI adalah sebuah platform transparansi data perumahan dan permukiman Kabupaten Sinjai. Temukan informasi bantuan, aset, dan infrastruktur dalam satu peta terpadu.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="#map" class="px-8 py-4 bg-blue-600 text-white rounded-[2rem] font-black uppercase tracking-widest text-xs shadow-2xl shadow-blue-600/30 hover:scale-105 active:scale-95 transition-all">Jelajahi Peta</a>
                    <a href="#summary" class="px-8 py-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2rem] font-black uppercase tracking-widest text-xs shadow-lg hover:bg-slate-50 transition-all">Lihat Statistik</a>
                </div>
            </div>

            <!-- Dynamic Carousel Section -->
            <div class="relative animate-in zoom-in duration-1000">
                <div class="swiper heroSwiper rounded-[3rem] overflow-hidden shadow-2xl border-8 border-white dark:border-slate-900 bg-white dark:bg-slate-900">
                    <div class="swiper-wrapper">
                        <?php if(!empty($carousel)): ?>
                            <?php foreach($carousel as $item): ?>
                            <div class="swiper-slide relative h-[500px]">
                                <img src="<?= base_url($item['image']) ?>" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-blue-950/80 via-transparent to-transparent flex items-end p-12">
                                    <p class="text-white font-black uppercase tracking-widest text-sm"><?= $item['caption'] ?></p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="swiper-slide relative h-[500px] flex items-center justify-center bg-slate-100 dark:bg-slate-800">
                                <div class="text-center">
                                    <i data-lucide="image" class="w-16 h-16 text-slate-300 mx-auto mb-4"></i>
                                    <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Belum Ada Gambar</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. SUMMARY SECTION -->
    <section id="summary" class="py-24 bg-white dark:bg-slate-900/50">
        <div class="mx-auto px-6 lg:px-12 text-center mb-20" style="max-width: 1440px;">
            <h2 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.4em] mb-4">Statistik Terpadu</h2>
            <h3 class="text-3xl lg:text-4xl font-black text-blue-950 dark:text-white uppercase tracking-tight">Pembangunan Sinjai</h3>
        </div>

        <div class="mx-auto px-6 lg:px-12 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-7 gap-6" style="max-width: 1440px;">
            <?php 
            $metrics = [
                ['rtlh', 'home', 'amber', 'Rumah Tak Layak'],
                ['kumuh', 'map-pin', 'rose', 'Wilayah Kumuh'],
                ['formal', 'building-2', 'indigo', 'Perumahan'],
                ['psu', 'route', 'emerald', 'Infrastruktur PSU'],
                ['arsinum', 'droplets', 'blue', 'Air Siap Minum'],
                ['pisew', 'hard-hat', 'orange', 'PISEW'],
                ['aset', 'land-plot', 'cyan', 'Aset Tanah'],
            ];
            foreach($metrics as $m): ?>
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-xl transition-all duration-500 group text-center flex flex-col items-center justify-center min-h-[200px]">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-<?= $m[2] ?>-50 dark:bg-<?= $m[2] ?>-950/30 text-<?= $m[2] ?>-600 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform shadow-inner">
                    <i data-lucide="<?= $m[1] ?>" class="w-7 h-7"></i>
                </div>
                <h4 class="text-3xl font-black text-blue-950 dark:text-white mb-2 leading-none"><?= number_format($rekap[$m[0]] ?? 0) ?></h4>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.1em] leading-tight max-w-[100px]"><?= $m[3] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- 3. MAP SECTION -->
    <section id="map" class="py-16">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <h2 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.4em] mb-4">Data Geospasial Publik</h2>
                <h3 class="text-3xl lg:text-4xl font-black text-blue-950 dark:text-white uppercase tracking-tight">E-Peta SIBARUKI</h3>
            </div>
            <div class="flex flex-wrap gap-2">
                <?php foreach(['rtlh', 'kumuh', 'formal', 'psu', 'arsinum', 'pisew', 'aset'] as $l): ?>
                <button onclick="switchLayer('<?= $l ?>')" class="layer-btn <?= $l=='rtlh'?'active':'' ?> px-5 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm" data-layer="<?= $l ?>">
                    <?php 
                        $labels = ['rtlh'=>'RTLH', 'kumuh'=>'Kumuh', 'formal'=>'Perumahan', 'psu'=>'PSU', 'arsinum'=>'Arsinum', 'pisew'=>'PISEW', 'aset'=>'Aset'];
                        echo $labels[$l];
                    ?>
                </button>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="bg-white dark:bg-slate-900 p-4 rounded-[3rem] border border-slate-100 dark:border-slate-800 shadow-2xl">
                <div id="publicMap" class="h-[600px] w-full rounded-[2.5rem] z-0 bg-slate-100 dark:bg-slate-950 border border-slate-100 dark:border-slate-800"></div>
            </div>
        </div>
    </section>

</div>

<style>
    .layer-btn.active { background: #2563eb !important; color: white !important; border-color: #2563eb !important; box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.4); }
    .leaflet-popup-content-wrapper { border-radius: 2rem; padding: 0; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3); border: none; }
    .leaflet-popup-content { margin: 0; width: 220px !important; }
    .custom-tooltip { background: rgba(30, 27, 75, 0.9) !important; color: white !important; border: none !important; border-radius: 8px !important; font-weight: 900 !important; font-size: 8px !important; text-transform: uppercase !important; }
</style>

<script>
    // Initialize Swiper
    const carouselCount = <?= is_array($carousel) ? count($carousel) : 0 ?>;
    new Swiper('.heroSwiper', {
        loop: carouselCount > 1,
        autoplay: carouselCount > 1 ? { delay: 5000 } : false,
        pagination: { el: '.swiper-pagination', clickable: true },
    });

    const spasialData = <?= json_encode($spasial) ?>;
    let map, kecLayerGroup, activeDataGroup;

    document.addEventListener('DOMContentLoaded', () => {
        initMap();
        setTimeout(() => switchLayer('rtlh'), 600);
    });

    function initMap() {
        const isDark = document.documentElement.classList.contains('dark');
        const tiles = L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png');
                    
        map = L.map('publicMap', { zoomControl: false, layers: [tiles] }).setView([-5.1245, 120.2536], 11);
        L.control.zoom({ position: 'topright' }).addTo(map);

        activeDataGroup = L.featureGroup().addTo(map);
        kecLayerGroup = L.featureGroup().addTo(map);

        const kecColors = ['#1e1b4b', '#1e40af', '#2563eb', '#1d4ed8', '#0ea5e9'];
        
        spasialData.kecamatan.forEach((k, idx) => {
            try {
                if (k.wkt) {
                    const geojson = wellknown.parse(k.wkt);
                    L.geoJSON(geojson, { 
                        style: { color: isDark ? '#0f172a' : '#ffffff', fillColor: kecColors[idx % 5], weight: 0.5, fillOpacity: 0.4 } 
                    }).addTo(kecLayerGroup).bindTooltip(`<div class="p-2"><p class="font-black uppercase text-[10px] text-white">${k.desa_nama}</p></div>`, { sticky: true, className: 'custom-tooltip' });
                }
            } catch (e) {}
        });
        kecLayerGroup.bringToBack();
    }

    function switchLayer(type) {
        activeDataGroup.clearLayers();
        document.querySelectorAll('.layer-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelector(`[data-layer="${type}"]`)?.classList.add('active');

        const items = spasialData[type] || [];
        const colorMap = { rtlh: '#f59e0b', kumuh: '#ef4444', formal: '#6366f1', psu: '#10b981', arsinum: '#3b82f6', pisew: '#f97316', aset: '#06b6d4' };

        items.forEach(item => {
            try {
                let geojson = null;

                if (item.latitude && item.longitude) {
                    geojson = { type: 'Point', coordinates: [parseFloat(item.longitude), parseFloat(item.latitude)] };
                } else if (item.coords) {
                    const parts = item.coords.replace(/[^\d.,-]/g, '').split(',');
                    if (parts.length === 2) geojson = { type: 'Point', coordinates: [parseFloat(parts[1]), parseFloat(parts[0])] };
                } else if (item.wkt) {
                    geojson = wellknown.parse(item.wkt);
                }

                if (!geojson) return;

                const popupContent = `
                    <div class="bg-blue-950 text-white p-5 rounded-t-2xl"><h5 class="text-[10px] font-black uppercase leading-tight">${item.name}</h5></div>
                    <div class="p-5 bg-white dark:bg-slate-900 rounded-b-2xl"><p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Informasi Publik Terverifikasi</p></div>`;

                if (geojson.type === 'Point') {
                    L.circleMarker([geojson.coordinates[1], geojson.coordinates[0]], { radius: 6, fillColor: colorMap[type], color: '#fff', weight: 2, fillOpacity: 0.8 }).bindPopup(popupContent).addTo(activeDataGroup);
                } else {
                    L.geoJSON(geojson, { style: { color: colorMap[type], weight: 3, fillOpacity: 0.4 } }).bindPopup(popupContent).addTo(activeDataGroup);
                }
            } catch (e) {}
        });

        if (activeDataGroup.getLayers().length > 0) map.fitBounds(activeDataGroup.getBounds(), { padding: [50, 50], maxZoom: 16 });
    }
</script>
<?= $this->endSection() ?>

