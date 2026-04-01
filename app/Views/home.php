<?= $this->extend('visitor_layout') ?>

<?= $this->section('content') ?>
<!-- External Assets for Map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
<script src="https://cdn.jsdelivr.net/npm/wellknown@0.5.0/wellknown.js"></script>

<div class="relative overflow-hidden text-slate-900 dark:text-slate-200">
    
    <!-- 1. HERO SECTION -->
    <section id="hero" class="relative min-h-[85vh] flex items-center pt-20 pb-8">
        <div class="absolute inset-0 bg-slate-50 dark:bg-slate-950"></div>
        <div class="max-w-7xl mx-auto px-6 lg:px-12 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center relative z-10">
            <div class="animate-in slide-in-from-left duration-1000">
                <div class="flex items-center gap-3 mb-6">
                    <span class="px-4 py-1.5 bg-blue-600 text-white text-[9px] font-black uppercase tracking-[0.3em] rounded-full shadow-lg shadow-blue-600/20">Official Portal</span>
                    <div class="flex items-center gap-2">
                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Live DB</span>
                    </div>
                </div>
                <h1 class="text-4xl lg:text-6xl font-black text-blue-950 dark:text-white leading-[1.05] mb-8 tracking-tighter uppercase">
                    Membangun <br/><span class="text-blue-600">Hunian Layak</span> <br/>Untuk Semua.
                </h1>
                <p class="text-lg text-slate-500 dark:text-slate-400 leading-relaxed mb-10 max-w-lg font-medium">
                    SIBARUKI adalah platform transparansi data perumahan dan permukiman Kabupaten Sinjai. Satu peta, sejuta informasi.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="#map" class="px-8 py-4 bg-blue-600 text-white rounded-[2rem] font-black uppercase tracking-[0.2em] text-[11px] shadow-xl shadow-blue-600/30 hover:scale-105 active:scale-95 transition-all flex items-center gap-2.5">
                        Jelajahi Peta <i data-lucide="map" class="w-4 h-4"></i>
                    </a>
                    <a href="#summary" class="px-8 py-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-blue-950 dark:text-white rounded-[2rem] font-black uppercase tracking-[0.2em] text-[11px] shadow-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-all">Statistik</a>
                </div>
            </div>

            <!-- Dynamic Carousel Section -->
            <div class="relative animate-in zoom-in duration-1000 hidden lg:block">
                <div class="swiper heroSwiper rounded-[3rem] overflow-hidden shadow-2xl border-[8px] border-white dark:border-slate-900 bg-white dark:bg-slate-900">
                    <div class="swiper-wrapper">
                        <?php if(!empty($carousel)): ?>
                            <?php foreach($carousel as $item): ?>
                            <div class="swiper-slide relative h-[500px]">
                                <img src="<?= base_url($item['image']) ?>" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-blue-950/90 via-transparent to-transparent flex items-end p-12">
                                    <div class="max-w-sm">
                                        <p class="text-blue-400 font-black uppercase tracking-[0.3em] text-[9px] mb-1.5">Dokumentasi</p>
                                        <h4 class="text-white font-black uppercase tracking-tight text-xl leading-tight"><?= $item['caption'] ?></h4>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="swiper-slide relative h-[500px] flex items-center justify-center bg-slate-100 dark:bg-slate-800">
                                <div class="text-center">
                                    <i data-lucide="image" class="w-10 h-10 text-slate-300 mx-auto mb-4"></i>
                                    <p class="text-slate-400 font-black uppercase tracking-[0.3em] text-[9px]">Belum Ada Dokumentasi</p>
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
    <section id="summary" class="py-24 bg-white dark:bg-slate-900/50 relative border-y border-slate-100 dark:border-slate-800/50">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 text-center mb-16">
            <h2 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.5em] mb-4">Data Strategis</h2>
            <h3 class="text-3xl lg:text-5xl font-black text-blue-950 dark:text-white uppercase tracking-tighter leading-none">Kabupaten Sinjai <br/><span class="text-slate-300 dark:text-slate-700 italic">Dalam Angka</span></h3>
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
            foreach($metrics as $m): ?>
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-xl transition-all duration-500 group text-center flex flex-col items-center justify-center min-h-[220px]">
                <div class="w-12 h-12 mx-auto rounded-xl bg-<?= $m[2] ?>-50 dark:bg-<?= $m[2] ?>-950/30 text-<?= $m[2] ?>-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-all duration-500 shadow-inner">
                    <i data-lucide="<?= $m[1] ?>" class="w-6 h-6" stroke-width="2.5"></i>
                </div>
                <h4 class="text-3xl font-black text-blue-950 dark:text-white mb-2 tracking-tighter"><?= number_format($rekap[$m[0]] ?? 0) ?></h4>
                <p class="text-[8px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] leading-tight"><?= $m[3] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- 3. MAP SECTION -->
    <section id="map" class="py-20 bg-slate-50 dark:bg-slate-950">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 mb-12 flex flex-col md:flex-row md:items-end justify-between gap-8">
            <div>
                <h2 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.5em] mb-4 border-l-4 border-blue-600 pl-4">Interaktif GIS</h2>
                <h3 class="text-3xl lg:text-5xl font-black text-blue-950 dark:text-white uppercase tracking-tighter">E-Peta SIBARUKI</h3>
            </div>
            <div class="flex flex-wrap gap-2">
                <?php foreach(['rtlh', 'kumuh', 'formal', 'psu', 'arsinum', 'pisew', 'aset'] as $l): ?>
                <button onclick="switchLayer('<?= $l ?>')" class="layer-btn <?= $l=='rtlh'?'active':'' ?> px-6 py-3 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all border border-white dark:border-slate-800 bg-white dark:bg-slate-900 shadow-lg hover:scale-105 active:scale-95" data-layer="<?= $l ?>">
                    <?php 
                        $labels = ['rtlh'=>'RTLH', 'kumuh'=>'Kumuh', 'formal'=>'Formal', 'psu'=>'PSU', 'arsinum'=>'Arsinum', 'pisew'=>'PISEW', 'aset'=>'Aset'];
                        echo $labels[$l];
                    ?>
                </button>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="bg-white dark:bg-slate-900 p-4 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-2xl">
                <div id="publicMap" class="h-[600px] w-full rounded-[2rem] z-0 bg-slate-50 dark:bg-slate-950 border border-slate-50 dark:border-slate-800"></div>
            </div>
        </div>
    </section>

</div>

<style>
    .layer-btn.active { background: #1e1b4b !important; color: white !important; border-color: #1e1b4b !important; box-shadow: 0 15px 30px -10px rgba(30, 27, 75, 0.4); }
    .dark .layer-btn.active { background: #2563eb !important; border-color: #2563eb !important; box-shadow: 0 15px 30px -10px rgba(37, 99, 235, 0.4); }
    .leaflet-popup-content-wrapper { border-radius: 1rem; padding: 0; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3); border: none; }
    .leaflet-popup-content { margin: 0; width: 200px !important; }
    .marker-cluster-small div, .marker-cluster-medium div, .marker-cluster-large div { background-color: rgba(30, 27, 75, 0.9); color: white; font-weight: 900; font-size: 10px; }
</style>

<script>
    // Swiper Initialization
    document.addEventListener('DOMContentLoaded', () => {
        const carouselCount = <?= is_array($carousel) ? count($carousel) : 0 ?>;
        new Swiper('.heroSwiper', {
            loop: carouselCount > 1,
            effect: 'fade',
            autoplay: carouselCount > 1 ? { delay: 5000 } : false,
            pagination: { el: '.swiper-pagination', clickable: true },
        });
        
        initMap();
        setTimeout(() => switchLayer('rtlh'), 800);
    });

    const spasialData = <?= json_encode($spasial) ?>;
    let map, clusterGroup, kecLayerGroup, activeDataGroup;

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
        if (!wkt || typeof wkt !== 'string' || typeof wellknown === 'undefined') return null;
        try {
            let cleanWkt = wkt.includes(';') ? wkt.split(';')[1] : wkt;
            if (cleanWkt.length >= 32760) {
                const lastComma = cleanWkt.lastIndexOf(',');
                if (lastComma > 0) {
                    cleanWkt = cleanWkt.substring(0, lastComma);
                    const openParen = (cleanWkt.match(/\(/g) || []).length;
                    const closeParen = (cleanWkt.match(/\)/g) || []).length;
                    cleanWkt += ')'.repeat(openParen - closeParen);
                }
            }
            let geojson = wellknown.parse(cleanWkt);
            if (!geojson) return null;
            if (isUTM) {
                const convert = (c) => (typeof c[0] === 'number') ? (([lat, lon] = utmToLatLng(c[0], c[1])), [lon, lat]) : c.map(convert);
                geojson.coordinates = convert(geojson.coordinates);
            }
            return geojson;
        } catch(e) { return null; }
    }

    function healCoordinate(val, isLat) {
        if (!val) return null;
        let s = val.toString().replace(/[ "]/g, '').replace(/,/g, '.');
        let digits = s.replace(/[^0-9-]/g, '');
        if (digits.length > 3) {
            let dotPos = digits.startsWith('-') ? 2 : 3;
            return parseFloat(digits.substring(0, dotPos) + '.' + digits.substring(dotPos));
        }
        return parseFloat(s);
    }

    function initMap() {
        const isDark = document.documentElement.classList.contains('dark');
        const tiles = L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png');
                    
        map = L.map('publicMap', { zoomControl: false, layers: [tiles] }).setView([-5.1245, 120.2536], 11);
        L.control.zoom({ position: 'topright' }).addTo(map);

        clusterGroup = L.markerClusterGroup({ showCoverageOnHover: false, maxClusterRadius: 50 }).addTo(map);
        activeDataGroup = L.featureGroup().addTo(map);
        kecLayerGroup = L.featureGroup().addTo(map);

        const kecColors = ['#1e1b4b', '#1e40af', '#2563eb', '#1d4ed8', '#0ea5e9'];
        spasialData.kecamatan.forEach((k, idx) => {
            const geojson = parseWKTUniversal(k.wkt);
            if (geojson) {
                L.geoJSON(geojson, { 
                    style: { color: isDark ? '#0f172a' : '#ffffff', fillColor: kecColors[idx % 5], weight: 0.5, fillOpacity: 0.3 } 
                }).addTo(kecLayerGroup).bindTooltip(`<div class="p-1"><p class="font-black uppercase text-[8px] text-white">${k.desa_nama}</p></div>`, { sticky: true, className: 'custom-tooltip' });
            }
        });
        kecLayerGroup.bringToBack();
    }

    function switchLayer(type) {
        clusterGroup.clearLayers();
        activeDataGroup.clearLayers();
        document.querySelectorAll('.layer-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelector(`[data-layer="${type}"]`)?.classList.add('active');

        const items = spasialData[type] || [];
        const colorMap = { rtlh: '#f59e0b', kumuh: '#ef4444', formal: '#6366f1', psu: '#10b981', arsinum: '#3b82f6', pisew: '#f97316', aset: '#06b6d4' };

        items.forEach(item => {
            try {
                let geojson = null;
                let lat = null, lon = null;
                if (item.latitude && item.longitude) { lat = parseFloat(item.latitude); lon = parseFloat(item.longitude); }
                else if (item.coords) {
                    let p = item.coords.toString().split(',');
                    if (p.length === 2) { lat = healCoordinate(p[0], true); lon = healCoordinate(p[1], false); }
                }
                if (lat && lon && !isNaN(lat) && !isNaN(lon) && Math.abs(lat) < 90) { geojson = { type: 'Point', coordinates: [lon, lat] }; }
                else if (item.wkt) { geojson = parseWKTUniversal(item.wkt, (type === 'psu')); }
                if (!geojson) return;

                const popupContent = `<div class="bg-blue-950 text-white p-4 rounded-t-xl"><h5 class="text-[10px] font-black uppercase leading-tight">${item.name}</h5></div><div class="p-4 bg-white dark:bg-slate-900 rounded-b-xl border-t border-slate-50 dark:border-slate-800"><p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Informasi Terverifikasi</p></div>`;

                if (geojson.type === 'Point') { L.circleMarker([geojson.coordinates[1], geojson.coordinates[0]], { radius: 6, fillColor: colorMap[type], color: '#fff', weight: 2, fillOpacity: 0.8 }).bindPopup(popupContent).addTo(clusterGroup); }
                else { L.geoJSON(geojson, { style: { color: colorMap[type], weight: 2, fillOpacity: 0.4 } }).bindPopup(popupContent).addTo(activeDataGroup); }
            } catch (e) {}
        });

        const bounds = L.latLngBounds();
        let valid = false;
        [clusterGroup, activeDataGroup].forEach(g => { if(g.getLayers().length > 0) { bounds.extend(g.getBounds()); valid = true; } });
        if (valid && bounds.isValid()) map.fitBounds(bounds, { padding: [80, 80], maxZoom: 16 });
    }
</script>
<?= $this->endSection() ?>
