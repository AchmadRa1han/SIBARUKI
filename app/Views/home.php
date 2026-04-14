<?= $this->extend('visitor_layout') ?>

<?= $this->section('content') ?>
<!-- External Assets for Map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.js"></script>
<script src="https://cdn.jsdelivr.net/npm/wellknown@0.5.0/wellknown.js"></script>

<div class="relative overflow-hidden text-slate-900 dark:text-slate-200 bg-slate-50 dark:bg-slate-950">
    
    <!-- Animated Background Decor - Reduced Opacity & Scale -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute top-[-5%] left-[-5%] w-[30%] h-[30%] bg-blue-600/5 rounded-full blur-[100px] animate-pulse"></div>
        <div class="absolute bottom-[-5%] right-[-5%] w-[30%] h-[30%] bg-indigo-600/5 rounded-full blur-[100px] animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <!-- 1. HERO SECTION - Compacted -->
    <section id="hero" class="relative min-h-[80vh] flex items-center pt-16 pb-4">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center relative z-10">
            <div class="reveal-stagger">
                <div class="flex items-center gap-3 mb-6 reveal-item">
                    <span class="px-3 py-1 bg-blue-600 text-white text-[8px] font-bold uppercase tracking-[0.2em] rounded-full shadow-lg shadow-blue-600/20">Official Portal</span>
                    <div class="flex items-center gap-2">
                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                        <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Live Data</span>
                    </div>
                </div>
                <!-- Fixed Color Clash: Use solid colors or better contrast gradient -->
                <h1 class="text-4xl lg:text-5xl font-black text-blue-950 dark:text-white leading-[1.1] mb-6 tracking-tighter uppercase reveal-item">
                    Membangun <br/>
                    <span class="text-blue-600 dark:text-blue-400">Hunian Layak</span> <br/>
                    Untuk Semua.
                </h1>
                <p class="text-base text-slate-500 dark:text-slate-400 leading-relaxed mb-8 max-w-md font-medium reveal-item">
                    Integrasi data spasial perumahan dan permukiman Kabupaten Sinjai untuk perencanaan pembangunan yang presisi dan transparan.
                </p>
                <div class="flex flex-wrap gap-4 reveal-item">
                    <a href="#map" class="px-8 py-3.5 bg-blue-600 text-white rounded-xl font-bold uppercase tracking-[0.15em] text-[10px] shadow-xl shadow-blue-600/30 hover:bg-blue-700 active:scale-95 transition-all flex items-center gap-2.5">
                        Jelajahi Peta <i data-lucide="map" class="w-3.5 h-3.5"></i>
                    </a>
                    <a href="#summary" class="px-8 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-blue-950 dark:text-white rounded-xl font-bold uppercase tracking-[0.15em] text-[10px] shadow-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-all">Statistik</a>
                </div>
            </div>

            <!-- Compact Carousel -->
            <div class="relative reveal hidden lg:block">
                <div class="swiper heroSwiper rounded-3xl overflow-hidden shadow-2xl border-[6px] border-white dark:border-slate-900 bg-white dark:bg-slate-900 relative z-10">
                    <div class="swiper-wrapper">
                        <?php if(!empty($carousel)): ?>
                            <?php foreach($carousel as $item): ?>
                            <div class="swiper-slide relative h-[450px]">
                                <img src="<?= base_url($item['image']) ?>" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-blue-950/80 via-transparent to-transparent flex items-end p-8">
                                    <div class="max-w-xs">
                                        <p class="text-blue-400 font-bold uppercase tracking-[0.2em] text-[8px] mb-1">Dokumentasi</p>
                                        <h4 class="text-white font-bold uppercase tracking-tight text-lg leading-tight"><?= $item['caption'] ?></h4>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="swiper-slide relative h-[450px] flex items-center justify-center bg-slate-100 dark:bg-slate-800">
                                <div class="text-center">
                                    <i data-lucide="image" class="w-8 h-8 text-slate-300 mx-auto mb-3"></i>
                                    <p class="text-slate-400 font-bold uppercase tracking-[0.2em] text-[8px]">Visualisasi Data</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. SUMMARY SECTION - Tighter Spacing -->
    <section id="summary" class="py-16 bg-white dark:bg-slate-900/50 relative border-y border-slate-100 dark:border-slate-800/50">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 text-center mb-12 reveal">
            <h2 class="text-[9px] font-black text-blue-600 uppercase tracking-[0.4em] mb-3">Rekapitulasi Data</h2>
            <h3 class="text-3xl lg:text-4xl font-black text-blue-950 dark:text-white uppercase tracking-tighter">Sinjai Dalam Angka</h3>
        </div>

        <div class="max-w-[1400px] mx-auto px-6 lg:px-12 grid grid-cols-2 md:grid-cols-4 xl:grid-cols-7 gap-4">
            <?php 
            $metrics = [
                ['rtlh', 'home', 'amber', 'Rumah Tak Layak'],
                ['kumuh', 'map-pin', 'rose', 'Wilayah Kumuh'],
                ['formal', 'building-2', 'indigo', 'Perumahan'],
                ['psu', 'route', 'emerald', 'PSU'],
                ['arsinum', 'droplets', 'blue', 'Arsinum'],
                ['pisew', 'hard-hat', 'orange', 'PISEW'],
                ['aset', 'land-plot', 'cyan', 'Aset Tanah'],
            ];
            foreach($metrics as $idx => $m): ?>
            <div class="reveal" style="transition-delay: <?= $idx * 50 ?>ms">
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl transition-all duration-300 text-center flex flex-col items-center justify-center min-h-[180px]">
                    <div class="w-10 h-10 mx-auto rounded-xl bg-<?= $m[2] ?>-50 dark:bg-<?= $m[2] ?>-950/30 text-<?= $m[2] ?>-600 flex items-center justify-center mb-4 shadow-inner">
                        <i data-lucide="<?= $m[1] ?>" class="w-5 h-5" stroke-width="2.5"></i>
                    </div>
                    <h4 class="text-2xl font-bold text-blue-950 dark:text-white mb-1 tracking-tighter count-up" data-target="<?= $rekap[$m[0]] ?? 0 ?>">0</h4>
                    <p class="text-[8px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest leading-tight"><?= $m[3] ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- 3. MAP SECTION - Optimized Height -->
    <section id="map" class="py-16 bg-slate-50 dark:bg-slate-950">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6 reveal">
            <div>
                <h2 class="text-[9px] font-black text-blue-600 uppercase tracking-[0.4em] mb-3">Interaktif GIS</h2>
                <h3 class="text-3xl lg:text-4xl font-black text-blue-950 dark:text-white uppercase tracking-tighter">E-Peta SIBARUKI</h3>
            </div>
            <div class="flex flex-wrap gap-2">
                <?php foreach(['rtlh', 'kumuh', 'formal', 'psu', 'arsinum', 'pisew', 'aset'] as $l): ?>
                <button onclick="switchLayer('<?= $l ?>')" class="layer-btn <?= $l=='rtlh'?'active':'' ?> px-5 py-2.5 rounded-lg text-[9px] font-bold uppercase tracking-widest transition-all border border-white dark:border-slate-800 bg-white dark:bg-slate-900 shadow-md" data-layer="<?= $l ?>">
                    <?php 
                        $labels = ['rtlh'=>'RTLH', 'kumuh'=>'Kumuh', 'formal'=>'Formal', 'psu'=>'PSU', 'arsinum'=>'Arsinum', 'pisew'=>'PISEW', 'aset'=>'Aset'];
                        echo $labels[$l];
                    ?>
                </button>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 lg:px-12 reveal">
            <div class="bg-white dark:bg-slate-900 p-3 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-2xl">
                <div id="publicMap" class="h-[550px] w-full rounded-[1.5rem] z-0 bg-slate-100 dark:bg-slate-950 border border-slate-50 dark:border-slate-800 overflow-hidden"></div>
            </div>
        </div>
    </section>

</div>

<style>
    /* Compact Animations */
    .reveal { opacity: 0; transform: translateY(20px); transition: all 0.8s cubic-bezier(0.22, 1, 0.36, 1); }
    .reveal.active { opacity: 1; transform: translateY(0); }

    .reveal-stagger .reveal-item { opacity: 0; transform: translateY(15px); transition: all 0.6s cubic-bezier(0.22, 1, 0.36, 1); }
    .reveal-stagger.active .reveal-item { opacity: 1; transform: translateY(0); }
    .reveal-stagger.active .reveal-item:nth-child(1) { transition-delay: 50ms; }
    .reveal-stagger.active .reveal-item:nth-child(2) { transition-delay: 150ms; }
    .reveal-stagger.active .reveal-item:nth-child(3) { transition-delay: 250ms; }
    .reveal-stagger.active .reveal-item:nth-child(4) { transition-delay: 350ms; }

    .layer-btn.active { background: #1e1b4b !important; color: white !important; border-color: #1e1b4b !important; }
    .dark .layer-btn.active { background: #2563eb !important; border-color: #2563eb !important; }

    .leaflet-popup-content-wrapper { border-radius: 1rem; padding: 0; overflow: hidden; box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.3); border: none; }
    .leaflet-popup-content { margin: 0; width: 200px !important; }
</style>

<script>
    // Pure Map & Animation Logic
    const spasialData = <?= json_encode($spasial) ?>;
    let map, clusterGroup, kecLayerGroup, activeDataGroup;

    // UTM Converter Logic
    function utmToLatLng(easting, northing) {
        const a = 6378137, f = 1 / 298.257223563;
        const b = a * (1 - f), e = Math.sqrt(1 - (b * b) / (a * a)), e1sq = (e * e) / (1 - e * e);
        const k0 = 0.9996, falseEasting = 500000, falseNorthing = 10000000;
        const zoneCentralMeridian = 123 * (Math.PI / 180); 
        let x = easting - falseEasting, y = northing - falseNorthing;
        let M = y / k0, mu = M / (a * (1 - e * e / 4 - 3 * e * e * e * e / 64 - 5 * e * e * e * e * e * e / 256));
        let phi1Rad = mu + (3 * e1sq / 2 - 27 * e1sq * e1sq * e1sq / 32) * Math.sin(2 * mu) + (21 * e1sq * e1sq / 16 - 55 * e1sq * e1sq * e1sq / 32) * Math.sin(4 * mu) + (151 * e1sq * e1sq * e1sq / 96) * Math.sin(6 * mu);
        let N1 = a / Math.sqrt(1 - e * e * Math.sin(phi1Rad) * Math.sin(phi1Rad)), T1 = Math.tan(phi1Rad) * Math.tan(phi1Rad), C1 = e1sq * Math.cos(phi1Rad) * Math.cos(phi1Rad), R1 = a * (1 - e * e) / Math.pow(1 - e * e * Math.sin(phi1Rad) * Math.sin(phi1Rad), 1.5);
        let D = x / (N1 * k0);
        let lat = phi1Rad - (N1 * Math.tan(phi1Rad) / R1) * (D * D / 2 - (5 + 3 * T1 + 10 * C1 - 4 * C1 * C1 - 9 * e1sq) * D * D * D * D / 24 + (61 + 90 * T1 + 298 * C1 + 45 * T1 * T1 - 252 * e1sq - 3 * C1 * C1) * D * D * D * D * D * D / 720);
        let lon = zoneCentralMeridian + (D - (1 + 2 * T1 + C1) * D * D * D / 6 + (5 - 2 * C1 + 28 * T1 - 3 * C1 * C1 + 8 * e1sq + 24 * T1 * T1) * D * D * D * D * D / 120) / Math.cos(phi1Rad);
        return [lat * (180 / Math.PI), lon * (180 / Math.PI)];
    }

    function parseWKTUniversal(wkt, isUTM = false) {
        if (!wkt || typeof wkt !== 'string' || typeof wells === 'undefined') {
            // Fallback manually if wellknown not loaded yet
            if (typeof wellknown !== 'undefined') return wellknown.parse(wkt);
            return null;
        }
        try {
            let geojson = wellknown.parse(wkt);
            if (geojson && isUTM) {
                const convert = (c) => (typeof c[0] === 'number') ? (([la, lo] = utmToLatLng(c[0], c[1])), [lo, la]) : c.map(convert);
                geojson.coordinates = convert(geojson.coordinates);
            }
            return geojson;
        } catch(e) { return null; }
    }

    function initMap() {
        try {
            const isDark = document.documentElement.classList.contains('dark');
            const tiles = L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png');
            
            map = L.map('publicMap', { zoomControl: false, layers: [tiles] }).setView([-5.1245, 120.2536], 11);
            L.control.zoom({ position: 'topright' }).addTo(map);

            clusterGroup = L.markerClusterGroup({ showCoverageOnHover: false, maxClusterRadius: 50 }).addTo(map);
            activeDataGroup = L.featureGroup().addTo(map);
            kecLayerGroup = L.featureGroup().addTo(map);

            const kecColors = ['#1e1b4b', '#1e40af', '#2563eb', '#1d4ed8', '#0ea5e9'];
            if (spasialData.kecamatan) {
                spasialData.kecamatan.forEach((k, idx) => {
                    if (typeof wellknown !== 'undefined') {
                        const geojson = wellknown.parse(k.wkt);
                        if (geojson) {
                            L.geoJSON(geojson, { 
                                style: { color: isDark ? '#0f172a' : '#ffffff', fillColor: kecColors[idx % 5], weight: 0.5, fillOpacity: 0.2 } 
                            }).addTo(kecLayerGroup).bindTooltip(`<div class="p-1"><p class="font-bold uppercase text-[8px] text-white">${k.desa_nama}</p></div>`, { sticky: true, className: 'custom-tooltip' });
                        }
                    }
                });
            }
            kecLayerGroup.bringToBack();
            switchLayer('rtlh');
        } catch(e) { console.error("Map Error:", e); }
    }

    function switchLayer(type) {
        if (!map) return;
        clusterGroup.clearLayers();
        activeDataGroup.clearLayers();
        document.querySelectorAll('.layer-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelector(`[data-layer="${type}"]`)?.classList.add('active');

        const items = spasialData[type] || [];
        const colorMap = { rtlh: '#f59e0b', kumuh: '#ef4444', formal: '#6366f1', psu: '#10b981', arsinum: '#3b82f6', pisew: '#f97316', aset: '#06b6d4' };

        items.forEach(item => {
            try {
                let geojson = null;
                if (item.latitude && item.longitude) { geojson = { type: 'Point', coordinates: [parseFloat(item.longitude), parseFloat(item.latitude)] }; }
                else if (item.wkt && typeof wellknown !== 'undefined') {
                    geojson = wellknown.parse(item.wkt);
                    if (geojson && type === 'psu') { // Convert UTM for PSU
                        const convert = (c) => (typeof c[0] === 'number') ? (([la, lo] = utmToLatLng(c[0], c[1])), [lo, la]) : c.map(convert);
                        geojson.coordinates = convert(geojson.coordinates);
                    }
                }
                if (!geojson) return;

                const popupContent = `<div class="bg-blue-950 text-white p-3 rounded-t-xl"><h5 class="text-[9px] font-bold uppercase leading-tight">${item.name}</h5></div><div class="p-3 bg-white dark:bg-slate-900 rounded-b-xl border-t border-slate-50 dark:border-slate-800"><p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Informasi Terverifikasi</p></div>`;

                if (geojson.type === 'Point' || geojson.type === 'MultiPoint') {
                    const coords = geojson.type === 'Point' ? geojson.coordinates : geojson.coordinates[0];
                    L.circleMarker([coords[1], coords[0]], { radius: 5, fillColor: colorMap[type], color: '#fff', weight: 1.5, fillOpacity: 0.8 }).bindPopup(popupContent).addTo(clusterGroup);
                } else {
                    L.geoJSON(geojson, { style: { color: colorMap[type], weight: 2, fillOpacity: 0.4 } }).bindPopup(popupContent).addTo(activeDataGroup);
                }
            } catch (e) {}
        });

        const bounds = L.latLngBounds();
        let valid = false;
        [clusterGroup, activeDataGroup].forEach(g => { if(g.getLayers().length > 0) { bounds.extend(g.getBounds()); valid = true; } });
        if (valid && bounds.isValid()) map.fitBounds(bounds, { padding: [50, 50], maxZoom: 16 });
    }

    // Animation Observers
    document.addEventListener('DOMContentLoaded', () => {
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('active'); });
        }, { threshold: 0.1 });

        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
                    const target = parseInt(entry.target.getAttribute('data-target'));
                    let count = 0;
                    const increment = target / 100;
                    const update = () => {
                        if (count < target) { count += increment; entry.target.innerText = Math.ceil(count).toLocaleString(); requestAnimationFrame(update); }
                        else { entry.target.innerText = target.toLocaleString(); }
                    };
                    update();
                    entry.target.classList.add('counted');
                }
            });
        }, { threshold: 0.5 });

        document.querySelectorAll('.reveal, .reveal-stagger').forEach(el => revealObserver.observe(el));
        document.querySelectorAll('.count-up').forEach(el => counterObserver.observe(el));
        if (typeof lucide !== 'undefined') lucide.createIcons();
        
        new Swiper('.heroSwiper', {
            loop: true, effect: 'fade', autoplay: { delay: 5000 },
            pagination: { el: '.swiper-pagination', clickable: true },
        });
        
        setTimeout(initMap, 500);
    });
</script>
<?= $this->endSection() ?>
