<?= $this->extend('visitor_layout') ?>

<?= $this->section('content') ?>
<!-- GIS Assets - Using robust CDNs -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/MarkerCluster.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/MarkerCluster.Default.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/leaflet.markercluster.js"></script>
<script src="https://cdn.jsdelivr.net/npm/wellknown@0.5.0/wellknown.js"></script>

<div class="relative overflow-hidden text-slate-900 dark:text-slate-200 bg-slate-50 dark:bg-slate-950">
    
    <!-- Background Decor -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden z-0 opacity-20">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-600/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-indigo-600/10 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <!-- 1. HERO SECTION -->
    <section id="hero" class="relative min-h-[75vh] flex items-center pt-12 pb-4">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center relative z-10">
            <div class="reveal-stagger">
                <div class="flex items-center gap-3 mb-6 reveal-item">
                    <span class="px-3 py-1 bg-blue-600 text-white text-[8px] font-black uppercase tracking-[0.3em] rounded-full shadow-lg">Official Portal</span>
                    <div class="flex items-center gap-2">
                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                        <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Database Terpadu</span>
                    </div>
                </div>
                <h1 class="text-4xl lg:text-5xl font-black text-blue-950 dark:text-white leading-[1.1] mb-6 tracking-tighter uppercase reveal-item">
                    Membangun <br/>
                    <span class="text-blue-600">Hunian Layak</span> <br/>
                    Untuk Semua.
                </h1>
                <p class="text-sm md:text-base text-slate-500 dark:text-slate-400 leading-relaxed mb-8 max-w-md font-medium reveal-item">
                    Satu platform terintegrasi untuk visualisasi dan manajemen data perumahan Kabupaten Sinjai secara transparan dan akuntabel.
                </p>
                <div class="flex flex-wrap gap-4 reveal-item">
                    <a href="#map" class="px-8 py-3.5 bg-blue-600 text-white rounded-xl font-bold uppercase tracking-[0.2em] text-[10px] shadow-xl shadow-blue-600/30 hover:scale-105 active:scale-95 transition-all flex items-center gap-2.5">
                        Jelajahi Peta <i data-lucide="map" class="w-3.5 h-3.5"></i>
                    </a>
                    <a href="#summary" class="px-8 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-blue-950 dark:text-white rounded-xl font-bold uppercase tracking-[0.2em] text-[10px] shadow-sm hover:bg-slate-50 transition-all">Statistik</a>
                </div>
            </div>

            <!-- Carousel Section -->
            <div class="relative reveal hidden lg:block">
                <div class="swiper heroSwiper rounded-[2rem] overflow-hidden shadow-2xl border-[6px] border-white dark:border-slate-900 bg-white dark:bg-slate-900 relative z-10">
                    <div class="swiper-wrapper">
                        <?php if(!empty($carousel)): ?>
                            <?php foreach($carousel as $item): ?>
                            <div class="swiper-slide relative h-[420px]">
                                <img src="<?= base_url($item['image']) ?>" class="w-full h-full object-cover">
                                <div class="absolute inset-x-0 bottom-0 p-6 bg-black/25 backdrop-blur-[1px] border-t border-white/10">
                                    <p class="text-blue-400 font-black uppercase tracking-[0.3em] text-[10px] mb-1">Dokumentasi</p>
                                    <h4 class="text-white font-black uppercase tracking-tight text-lg leading-tight drop-shadow-[0_2px_3px_rgba(0,0,0,1)]"><?= $item['caption'] ?></h4>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="swiper-slide relative h-[420px] flex items-center justify-center bg-slate-100 dark:bg-slate-800">
                                <div class="text-center">
                                    <i data-lucide="image" class="w-8 h-8 text-slate-300 mx-auto mb-3"></i>
                                    <p class="text-slate-400 font-bold uppercase tracking-[0.2em] text-[8px]">Visualisasi Database</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="swiper-pagination !bottom-2"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. SUMMARY & INSIGHT SECTION -->
    <section id="summary" class="scroll-mt-20 py-16 bg-white dark:bg-slate-900/50 relative border-y border-slate-100 dark:border-slate-800/50 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 grid grid-cols-1 lg:grid-cols-12 gap-12">
            <div class="lg:col-span-4 reveal">
                <div class="h-full flex flex-col justify-center">
                    <h2 class="text-[9px] font-black text-blue-600 uppercase tracking-[0.5em] mb-4">Dashboard Statistik</h2>
                    <h3 class="text-3xl font-black text-blue-950 dark:text-white uppercase tracking-tighter mb-6 leading-tight">Data Presisi <br/>Untuk Kebijakan <br/>Yang Tepat.</h3>
                    <p class="text-xs text-slate-500 font-medium leading-relaxed mb-8">Informasi yang disajikan merupakan hasil sinkronisasi data lapangan yang diverifikasi secara berkala oleh tim teknis SIBARUKI.</p>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 bg-slate-50 dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700">
                            <p class="text-xl font-bold text-blue-600">100%</p>
                            <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Validasi</p>
                        </div>
                        <div class="p-4 bg-slate-50 dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700">
                            <p class="text-xl font-bold text-emerald-500">Live</p>
                            <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Update</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-8 grid grid-cols-2 md:grid-cols-4 gap-4">
                <?php 
                $metrics = [
                    ['rtlh', 'home', 'amber', 'RTLH'],
                    ['kumuh', 'map-pin', 'rose', 'Kumuh'],
                    ['formal', 'building-2', 'indigo', 'Formal'],
                    ['psu', 'route', 'emerald', 'PSU'],
                    ['arsinum', 'droplets', 'blue', 'Arsinum'],
                    ['pisew', 'hard-hat', 'orange', 'PISEW'],
                    ['aset', 'land-plot', 'cyan', 'Aset'],
                    ['total', 'database', 'slate', 'Database']
                ];
                foreach($metrics as $idx => $m): ?>
                <div class="reveal" style="transition-delay: <?= $idx * 50 ?>ms">
                    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-lg hover:shadow-xl transition-all duration-300 text-center flex flex-col items-center justify-center min-h-[160px] group overflow-hidden relative">
                        <div class="absolute top-0 right-0 p-2 opacity-[0.03] group-hover:scale-150 transition-transform duration-700 pointer-events-none">
                            <i data-lucide="<?= $m[1] ?>" class="w-16 h-16"></i>
                        </div>
                        <div class="w-10 h-10 mx-auto rounded-xl bg-<?= $m[2] ?>-50 dark:bg-<?= $m[2] ?>-950/30 text-<?= $m[2] ?>-600 flex items-center justify-center mb-4 shadow-inner">
                            <i data-lucide="<?= $m[1] ?>" class="w-5 h-5" stroke-width="2.5"></i>
                        </div>
                        <h4 class="text-2xl font-bold text-blue-950 dark:text-white mb-1 tracking-tighter count-up" data-target="<?= ($m[0]=='total') ? array_sum($rekap) : ($rekap[$m[0]] ?? 0) ?>">0</h4>
                        <p class="text-[8px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest"><?= $m[3] ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- 3. MAP SECTION -->
    <section id="map" class="scroll-mt-20 py-16 bg-slate-50 dark:bg-slate-950">
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
            <div class="bg-white dark:bg-slate-900 p-3 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-2xl relative">
                <div id="publicMap" class="h-[550px] w-full rounded-[1.8rem] z-0 bg-slate-100 dark:bg-slate-950 border border-slate-50 dark:border-slate-800 overflow-hidden shadow-inner"></div>
                <div id="map-error" class="absolute inset-3 rounded-[1.8rem] bg-white dark:bg-slate-900 flex flex-col items-center justify-center z-[1001] hidden">
                    <i data-lucide="alert-triangle" class="w-12 h-12 text-amber-500 mb-4"></i>
                    <p class="text-sm font-bold text-slate-500 uppercase tracking-widest text-center px-8">Maaf, modul peta geospasial gagal dimuat.<br/>Pastikan koneksi internet aktif.</p>
                    <button onclick="window.location.reload()" class="mt-6 px-8 py-2.5 bg-blue-600 text-white rounded-xl text-[10px] font-bold uppercase tracking-widest shadow-lg shadow-blue-600/20">Muat Ulang Halaman</button>
                </div>
            </div>
        </div>
    </section>

</div>

<style>
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

    .leaflet-popup-content-wrapper { border-radius: 1rem; padding: 0; overflow: hidden; border: none; }
    .leaflet-popup-content { margin: 0; width: 200px !important; }
    .swiper-pagination-bullet-active { background: #2563eb !important; }

    /* Custom Tooltip Styles for Desa Names */
    .custom-tooltip {
        background: rgba(15, 23, 42, 0.9) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-radius: 8px !important;
        color: white !important;
        font-weight: 800 !important;
        font-size: 9px !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3) !important;
        padding: 4px 8px !important;
    }
    .leaflet-tooltip-top:before, .leaflet-tooltip-bottom:before, .leaflet-tooltip-left:before, .leaflet-tooltip-right:before {
        border: none !important;
    }
</style>

<script>
    // --- GIS Logic (Improved Stability) ---
    const spasialData = <?= json_encode($spasial) ?>;
    let map = null, clusterGroup, kecLayerGroup, activeDataGroup;

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

    function initMap() {
        if (map) return;
        if (typeof L === 'undefined' || typeof L.markerClusterGroup !== 'function') return;
        try {
            const isDark = document.documentElement.classList.contains('dark');
            const cartoDB = L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { 
                attribution: '&copy; CartoDB' 
            });
            const googleSat = L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains:['mt0','mt1','mt2','mt3'],
                attribution: '&copy; Google'
            });
            
            map = L.map('publicMap', { zoomControl: false, layers: [cartoDB] }).setView([-5.1245, 120.2536], 11);
            
            L.control.layers({
                "Default View": cartoDB,
                "Satellite View": googleSat
            }, null, { position: 'topright' }).addTo(map);

            L.control.zoom({ position: 'topright' }).addTo(map);

            clusterGroup = L.markerClusterGroup({ showCoverageOnHover: false, maxClusterRadius: 50 }).addTo(map);
            activeDataGroup = L.featureGroup().addTo(map);
            kecLayerGroup = L.featureGroup().addTo(map);

            const kecColors = ['#1e1b4b', '#1e40af', '#2563eb', '#1d4ed8', '#0ea5e9'];
            if (spasialData.kecamatan && typeof wellknown !== 'undefined') {
                spasialData.kecamatan.forEach((k, idx) => {
                    try {
                        const geojson = wellknown.parse(k.wkt);
                        if (geojson) {
                            L.geoJSON(geojson, { 
                                style: { color: isDark ? '#0f172a' : '#ffffff', fillColor: kecColors[idx % 5], weight: 0.5, fillOpacity: 0.15 } 
                            }).addTo(kecLayerGroup).bindTooltip(`<p class="font-bold uppercase text-[8px] text-white">${k.desa_nama}</p>`, { sticky: true, className: 'custom-tooltip' });
                        }
                    } catch(e) {}
                });
            }
            kecLayerGroup.bringToBack();
            switchLayer('rtlh');
            setTimeout(() => map.invalidateSize(), 500);
        } catch(e) { console.error(e); }
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
                // COORDINATE HEALING
                if (item.latitude && item.longitude) { 
                    geojson = { type: 'Point', coordinates: [parseFloat(item.longitude), parseFloat(item.latitude)] }; 
                } else if (item.coords) {
                    const parts = item.coords.toString().split(',');
                    if (parts.length === 2) {
                        const lat = parseFloat(parts[0].trim());
                        const lng = parseFloat(parts[1].trim());
                        if (!isNaN(lat) && !isNaN(lng)) geojson = { type: 'Point', coordinates: [lng, lat] };
                    }
                } else if (item.wkt && typeof wellknown !== 'undefined') {
                    geojson = wellknown.parse(item.wkt);
                    if (geojson && type === 'psu') {
                        const convert = (c) => (typeof c[0] === 'number') ? (([la, lo] = utmToLatLng(c[0], c[1])), [lo, la]) : c.map(convert);
                        geojson.coordinates = convert(geojson.coordinates);
                    }
                }
                if (!geojson) return;

                // DYNAMIC COLOR FOR KUMUH
                let markerColor = colorMap[type];
                let layerStyle = { color: markerColor, weight: 2, fillOpacity: 0.4 };
                if (type === 'kumuh' && item.skor_kumuh) {
                    const skor = parseFloat(item.skor_kumuh);
                    markerColor = skor >= 60 ? '#e11d48' : (skor >= 40 ? '#f97316' : '#f59e0b');
                    layerStyle = { color: markerColor, fillColor: markerColor, weight: 2, fillOpacity: 0.6 };
                }

                const popup = `<div class="bg-blue-950 text-white p-3 rounded-t-xl"><h5 class="text-[9px] font-bold uppercase leading-tight">${item.name}</h5></div><div class="p-3 bg-white dark:bg-slate-900 rounded-b-xl border-t dark:border-slate-800"><p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">${type==='kumuh' ? 'Skor: ' + item.skor_kumuh : 'Informasi Terverifikasi'}</p></div>`;

                if (geojson.type === 'Point') {
                    L.circleMarker([geojson.coordinates[1], geojson.coordinates[0]], { radius: 5, fillColor: markerColor, color: '#fff', weight: 1.5, fillOpacity: 0.8 }).bindPopup(popup).addTo(clusterGroup);
                } else {
                    L.geoJSON(geojson, { style: layerStyle }).bindPopup(popup).addTo(activeDataGroup);
                }
            } catch (e) {}
        });

        const bounds = L.latLngBounds();
        let valid = false;
        [clusterGroup, activeDataGroup].forEach(g => { if(g.getLayers().length > 0) { bounds.extend(g.getBounds()); valid = true; } });
        if (valid && bounds.isValid()) map.fitBounds(bounds, { padding: [50, 50], maxZoom: 16 });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('active'); });
        }, { threshold: 0.1 });

        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
                    const target = parseInt(entry.target.getAttribute('data-target'));
                    let count = 0;
                    const increment = Math.max(1, target / 100);
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
        
        new Swiper('.heroSwiper', { loop: true, effect: 'fade', autoplay: { delay: 5000 }, pagination: { el: '.swiper-pagination', clickable: true } });
        
        let attempts = 0;
        const checkLibrary = setInterval(() => {
            attempts++;
            if (typeof L !== 'undefined' && typeof L.markerClusterGroup === 'function' && typeof wellknown !== 'undefined') {
                initMap();
                clearInterval(checkLibrary);
            } else if (attempts > 30) {
                clearInterval(checkLibrary);
                document.getElementById('map-error').classList.remove('hidden');
            }
        }, 200);
    });
</script>
<?= $this->endSection() ?>
