<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<!-- External Assets -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
<script src="https://cdn.jsdelivr.net/npm/wellknown@0.5.0/wellknown.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<?php 
    $hour = date('H');
    $greet = ($hour < 12) ? 'Selamat Pagi' : (($hour < 17) ? 'Selamat Siang' : 'Selamat Malam');
?>

<div class="space-y-8 pb-12 animate-in fade-in duration-700">
    
    <!-- 1. HEADER -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                <span class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 dark:text-slate-500">Command Center SIBARUKI v1.0</span>
            </div>
            <h1 class="text-3xl lg:text-4xl font-black tracking-tight text-blue-950 dark:text-white"><?= $greet ?>, <?= explode(' ', session()->get('username'))[0] ?>.</h1>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex flex-col items-end mr-4">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest"><?= date('l') ?></span>
                <span class="text-sm font-bold text-blue-900 dark:text-blue-400"><?= date('d F Y') ?></span>
            </div>
        </div>
    </div>

    <!-- 2. METRICS GRID -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4">
        <?php 
        $metrics = [
            ['rtlh', 'home', 'amber', 'RTLH', base_url('rtlh')],
            ['kumuh', 'map-pin', 'rose', 'KUMUH', base_url('wilayah-kumuh')],
            ['formal', 'building-2', 'indigo', 'FORMAL', base_url('perumahan-formal')],
            ['psu', 'route', 'slate', 'PSU', base_url('psu')],
            ['pisew', 'map', 'orange', 'PISEW', base_url('pisew')],
            ['aset', 'layers', 'emerald', 'ASET', base_url('aset-tanah')],
            ['arsinum', 'droplet', 'cyan', 'ARSINUM', base_url('arsinum')],
        ];
        foreach($metrics as $m): ?>
        <a href="<?= $m[4] ?>" class="bg-white dark:bg-slate-900 p-5 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group block">
            <div class="w-10 h-10 rounded-xl bg-<?= $m[2] ?>-50 dark:bg-<?= $m[2] ?>-950/30 text-<?= $m[2] ?>-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform"><i data-lucide="<?= $m[1] ?>" class="w-5 h-5"></i></div>
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1"><?= $m[3] ?></p>
            <h3 class="text-xl font-black text-blue-950 dark:text-white"><?= number_format($rekap[$m[0]]) ?></h3>
        </a>
        <?php endforeach; ?>
    </div>

    <!-- 3. TACTICAL COMMAND MAP -->
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-2xl overflow-hidden flex flex-col transition-all duration-300">
        <div class="p-6 border-b border-slate-50 dark:border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-6 bg-slate-50/50 dark:bg-slate-800/30">
            <div>
                <h3 class="text-[10px] font-black text-blue-950 dark:text-blue-400 uppercase tracking-[0.2em] mb-1">Database Geospasial Terpadu</h3>
                <div class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                    <span id="activeLayerLabel" class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Memuat Peta...</span>
                </div>
            </div>
            <div class="flex flex-wrap gap-2">
                <?php foreach(['rtlh', 'kumuh', 'formal', 'psu', 'aset', 'arsinum', 'pisew'] as $l): ?>
                <button onclick="switchLayer('<?= $l ?>')" class="layer-btn <?= $l=='rtlh'?'active':'' ?> px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all border border-white dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm flex items-center gap-2" data-layer="<?= $l ?>">
                    <i data-lucide="<?= $l=='rtlh'?'home':($l=='kumuh'?'map-pin':($l=='formal'?'building-2':($l=='psu'?'route':($l=='aset'?'layers':($l=='arsinum'?'droplet':'map'))))) ?>" class="w-3 h-3"></i> <?= strtoupper($l) ?>
                </button>
                <?php endforeach; ?>
            </div>
        </div>
        <div id="tacticalMap" class="h-[65vh] w-full z-0 bg-slate-100 dark:bg-slate-950"></div>
    </div>

    <!-- 4. BOTTOM ANALYTICS -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-slate-800 shadow-sm">
            <h3 class="text-[10px] font-black text-blue-950 dark:text-blue-400 uppercase tracking-widest mb-8">Status Kelayakan RTLH</h3>
            <div id="conditionChart"></div>
        </div>
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-slate-800 shadow-sm">
            <h3 class="text-[10px] font-black text-rose-600 uppercase tracking-widest mb-6 px-2">Kawasan Kumuh Prioritas</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php foreach($topKumuh as $k): ?>
                <div class="p-5 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-800/50 border border-slate-100 dark:border-slate-800 rounded-3xl transition-all group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/30 text-rose-600 flex items-center justify-center font-black text-sm group-hover:scale-110 transition-transform"><?= substr($k['Kelurahan'], 0, 1) ?></div>
                        <div>
                            <p class="text-xs font-black text-slate-800 dark:text-slate-200 uppercase tracking-tight"><?= $k['Kelurahan'] ?></p>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest"><?= $k['Kawasan'] ?></p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-lg font-black text-rose-600 leading-none"><?= number_format($k['skor_kumuh'], 0) ?></span>
                        <span class="text-[8px] font-bold text-slate-400 uppercase block">Skor</span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<style>
    .layer-btn.active { background: #1e1b4b !important; color: white !important; border-color: #1e1b4b !important; box-shadow: 0 10px 15px -3px rgba(30, 27, 75, 0.4); }
    .dark .layer-btn.active { background: #2563eb !important; border-color: #2563eb !important; box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.4); }
    .leaflet-popup-content-wrapper { border-radius: 1.5rem; padding: 0; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3); border: none; }
    .leaflet-popup-content { margin: 0; width: 240px !important; }
    .marker-cluster-small div, .marker-cluster-medium div, .marker-cluster-large div { background-color: rgba(30, 27, 75, 0.9); color: white; font-weight: 900; }
    .custom-tooltip { background: rgba(30, 27, 75, 0.9) !important; color: white !important; border: none !important; border-radius: 8px !important; font-weight: 900 !important; font-size: 8px !important; text-transform: uppercase !important; }
</style>

<script>
    const spasialData = <?= json_encode($spasial) ?>;
    let map, clusterGroup, kecLayerGroup, activeDataGroup;
    let standard, satellite;
    let rot = 0;

    function utmToLatLng(easting, northing) {
        const a = 6378137, f = 1 / 298.257223563;
        const b = a * (1 - f), e = Math.sqrt(1 - (b * b) / (a * a)), e1sq = (e * e) / (1 - e * e);
        const k0 = 0.9996, falseEasting = 500000, falseNorthing = 10000000;
        const zoneCentralMeridian = 123 * (Math.PI / 180); 
        let x = easting - falseEasting, y = northing - falseNorthing;
        let M = y / k0, mu = M / (a * (1 - e * e / 4 - 3 * e * e * e * e / 64 - 5 * e * e * e * e * e * e / 256));
        let phi1Rad = mu + (3 * e1sq / 2 - 27 * e1sq * e1sq * e1sq / 32) * Math.sin(2 * mu) + (21 * e1sq * e1sq / 16 - 55 * e1sq * e1sq * e1sq * e1sq / 32) * Math.sin(4 * mu) + (151 * e1sq * e1sq * e1sq / 96) * Math.sin(6 * mu);
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
            
            // --- HEALING LOGIC UNTUK DATA TERPOTONG (32KB LIMIT) ---
            if (cleanWkt.length >= 32760) {
                // Cari angka terakhir sebelum pemotongan yang mungkin merusak JSON
                // Kita coba potong sampai koma terakhir agar koordinatnya pas
                const lastComma = cleanWkt.lastIndexOf(',');
                if (lastComma > 0) {
                    cleanWkt = cleanWkt.substring(0, lastComma);
                    // Hitung jumlah kurung buka dan tutup
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
        } catch(e) { 
            console.warn('WKT Repair Failed:', e);
            return null; 
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
        initMap();
        initChart();
        setTimeout(() => switchLayer('rtlh'), 600);
    });

    function initMap() {
        if (typeof L === 'undefined') { setTimeout(initMap, 100); return; }
        const isDark = document.documentElement.classList.contains('dark');
        standard = L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png');
        satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}');

        map = L.map('tacticalMap', { zoomControl: false, layers: [standard] }).setView([-5.1245, 120.2536], 11);
        L.control.zoom({ position: 'topright' }).addTo(map);

        const LayerToggle = L.Control.extend({
            onAdd: () => {
                const btn = L.DomUtil.create('button', 'bg-white dark:bg-slate-900 rounded-xl shadow-xl border border-slate-100 dark:border-slate-800 transition-all duration-300 active:scale-90 mt-2 flex items-center justify-center');
                btn.style.width = '44px'; btn.style.height = '44px';
                btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="${isDark?'#60a5fa':'#2563eb'}" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>`;
                L.DomEvent.disableClickPropagation(btn);
                L.DomEvent.on(btn, 'click', () => {
                    if (map.hasLayer(standard)) { map.removeLayer(standard); map.addLayer(satellite); btn.style.backgroundColor = '#2563eb'; btn.querySelector('svg').setAttribute('stroke', '#fff'); }
                    else { map.removeLayer(satellite); map.addLayer(standard); btn.style.backgroundColor = isDark ? '#0f172a' : '#fff'; btn.querySelector('svg').setAttribute('stroke', isDark?'#60a5fa':'#2563eb'); }
                });
                return btn;
            }
        });
        map.addControl(new LayerToggle({ position: 'topright' }));

        clusterGroup = L.markerClusterGroup({ showCoverageOnHover: false, maxClusterRadius: 50 }).addTo(map);
        activeDataGroup = L.featureGroup().addTo(map);
        kecLayerGroup = L.featureGroup().addTo(map);

        // Draw Village Boundaries (Unified as Kecamatan Map)
        let kecCount = 0;
        
        // Custom High-Contrast Palette for Sinjai (Mapping by ID for Stability)
        const kecColorMap = {
            '730701': '#1e1b4b', // SINJAI BARAT
            '730702': '#1e40af', // SINJAI BORONG
            '730703': '#2563eb', // SINJAI SELATAN
            '730704': '#1d4ed8', // TELLU LIMPOE
            '730705': '#0ea5e9', // SINJAI TIMUR
            '730706': '#3b82f6', // SINJAI TENGAH
            '730707': '#0891b2', // SINJAI UTARA
            '730708': '#4338ca', // BULUPODDO
            '730709': '#0369a1'  // PULAU SEMBILAN
        };

        const defaultPalette = ['#1e3a8a', '#2563eb', '#3b82f6', '#60a5fa', '#1d4ed8', '#1e40af'];
        let colorIndex = 0;
        
        spasialData.kecamatan.forEach(k => {
            let fillColor = kecColorMap[k.kecamatan_id];
            
            if (!fillColor) {
                fillColor = defaultPalette[colorIndex % defaultPalette.length];
                colorIndex++;
            }

            const geojson = parseWKTUniversal(k.wkt, false);
            if (geojson) {
                L.geoJSON(geojson, { 
                    style: { 
                        color: isDark ? '#0f172a' : '#ffffff', 
                        fillColor: fillColor, 
                        weight: 0.5, 
                        fillOpacity: isDark ? 0.6 : 0.5 
                    } 
                }).addTo(kecLayerGroup).bindTooltip(`
                    <div class="p-2">
                        <p class="font-black uppercase text-[10px] text-white">${k.desa_nama}</p>
                        <p class="text-[8px] font-bold text-blue-200 uppercase">Kec. ${k.kecamatan_nama}</p>
                    </div>`, { sticky: true, className: 'custom-tooltip' });
                kecCount++;
            }
        });
    }

    function switchLayer(type) {
        clusterGroup.clearLayers();
        activeDataGroup.clearLayers();
        
        document.querySelectorAll('.layer-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelector(`[data-layer="${type}"]`)?.classList.add('active');
        document.getElementById('activeLayerLabel').innerText = `Database: ${type.toUpperCase()}`;

        const items = spasialData[type];
        const colorMap = { rtlh: '#f59e0b', formal: '#6366f1', aset: '#1e1b4b', arsinum: '#06b6d4', pisew: '#f97316', psu: '#3b82f6' };

        items.forEach(item => {
            let geojson = null;
            if (type === 'formal' && item.latitude) geojson = { type: 'Point', coordinates: [parseFloat(item.longitude), parseFloat(item.latitude)] };
            else if (item.wkt) {
                if (type === 'rtlh' && item.wkt.indexOf(',') !== -1 && item.wkt.indexOf('POINT') === -1) {
                    const p = item.wkt.split(',').map(x => parseFloat(x.trim()));
                    if (!isNaN(p[0])) geojson = { type: 'Point', coordinates: [p[1], p[0]] };
                } else geojson = parseWKTUniversal(item.wkt, (type==='psu'));
            } else if (item.coords) {
                const p = item.coords.split(',').map(x => parseFloat(x.trim()));
                geojson = { type: 'Point', coordinates: [p[1], p[0]] };
            }

            if (!geojson) return;

            // Mapping URL Detail berdasarkan Tipe Data
            const detailUrls = {
                rtlh: '<?= base_url('rtlh/detail') ?>',
                kumuh: '<?= base_url('wilayah-kumuh/detail') ?>',
                formal: '<?= base_url('perumahan-formal/detail') ?>',
                psu: '<?= base_url('psu/detail') ?>',
                aset: '<?= base_url('aset-tanah/detail') ?>',
                arsinum: '<?= base_url('arsinum/detail') ?>',
                pisew: '<?= base_url('pisew/detail') ?>'
            };

            const popupContent = `
                <div class="bg-blue-950 text-white p-4 rounded-t-xl">
                    <p class="text-[8px] font-black uppercase tracking-widest text-blue-400 mb-1">SIBARUKI: ${type.toUpperCase()}</p>
                    <h5 class="text-xs font-black uppercase leading-tight">${item.name}</h5>
                </div>
                <div class="p-4 bg-white dark:bg-slate-900 space-y-3 rounded-b-xl">
                    ${type === 'kumuh' ? `
                        <div class="flex justify-between items-center bg-slate-50 dark:bg-slate-800 p-2 rounded-lg border border-slate-100 dark:border-slate-700">
                            <span class="text-[9px] font-bold text-slate-400 uppercase">Skor Kumuh</span>
                            <span class="text-xs font-black ${item.skor_kumuh >= 60 ? 'text-rose-600' : (item.skor_kumuh >= 40 ? 'text-orange-500' : 'text-amber-500')}">${parseFloat(item.skor_kumuh).toFixed(1)}</span>
                        </div>
                    ` : ''}
                    <div class="flex items-center gap-2 text-[10px] font-bold text-slate-700 dark:text-slate-300">
                        📍 Kabupaten Sinjai
                    </div>
                    <a href="${detailUrls[type]}/${item.id}" class="block w-full py-2.5 bg-blue-950 hover:bg-black text-white text-center text-[9px] font-black uppercase tracking-widest rounded-xl transition-all shadow-lg active:scale-95">
                        <i data-lucide="external-link" class="w-3 h-3 inline-block mr-1"></i> Lihat Detail Data
                    </a>
                </div>`;

            let layer;
            if (geojson.type === 'Point') {
                layer = L.circleMarker([geojson.coordinates[1], geojson.coordinates[0]], { 
                    radius: 8, fillColor: colorMap[type], color: '#fff', weight: 2, fillOpacity: 0.8 
                });
                clusterGroup.addLayer(layer);
            } else {
                let style = { color: colorMap[type], weight: type==='psu'?4:2, fillOpacity: 0.5 };
                if (type === 'kumuh') {
                    const s = parseFloat(item.skor_kumuh);
                    const c = s >= 60 ? '#ef4444' : (s >= 40 ? '#f97316' : '#f59e0b');
                    style = { color: c, fillColor: c, weight: 2, fillOpacity: 0.6 };
                }
                layer = L.geoJSON(geojson, { style: style });
                activeDataGroup.addLayer(layer);
            }

            layer.bindPopup(popupContent);

            layer.on('popupopen', () => {
                lucide.createIcons();
            });
        });

        const allLayers = L.featureGroup([clusterGroup, activeDataGroup]);
        if (allLayers.getLayers().length > 0) map.fitBounds(allLayers.getBounds(), { padding: [50, 50] });
    }

    function initChart() {
        const s = <?= json_encode($statusLayak) ?>;
        new ApexCharts(document.querySelector("#conditionChart"), {
            series: [parseInt(s.layak||0), parseInt(s.menuju_layak||0), parseInt(s.tidak_layak||0)],
            chart: { type: 'donut', height: 320, fontFamily: 'Plus Jakarta Sans' },
            labels: ['LAYAK', 'MENUJU LAYAK', 'TIDAK LAYAK'],
            colors: ['#10b981', '#f59e0b', '#ef4444'],
            plotOptions: { pie: { donut: { size: '80%', labels: { show: true, total: { show: true, label: 'TOTAL', color: '#94a3b8' } } } } },
            legend: { position: 'bottom' }
        }).render();
    }
</script>
<?= $this->endSection() ?>
