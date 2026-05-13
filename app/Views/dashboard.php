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

<div class="space-y-6 pb-12 animate-in fade-in duration-700">
    
    <!-- 1. HEADER -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-2 mb-1.5 ml-1">
                <div class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></div>
                <span class="text-[9px] font-bold uppercase tracking-[0.3em] text-slate-400 dark:text-slate-500">Command Center SIBARUKI v1.0</span>
            </div>
            <h1 class="text-2xl lg:text-4xl font-bold tracking-tighter text-blue-950 dark:text-white uppercase leading-none">
                <?= $greet ?>, <span class="text-blue-600"><?= explode(' ', session()->get('username'))[0] ?>.</span>
            </h1>
        </div>
        <div class="flex items-center gap-3 bg-white dark:bg-slate-900 px-5 py-2.5 rounded-xl border border-slate-100 dark:border-slate-800 shadow-sm">
            <div class="flex flex-col items-end">
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em]"><?= date('l') ?></span>
                <span class="text-xs font-bold text-blue-950 dark:text-white"><?= date('d F Y') ?></span>
            </div>
            <div class="w-px h-6 bg-slate-100 dark:bg-slate-800"></div>
            <div class="w-8 h-8 bg-blue-50 dark:bg-blue-900/30 rounded-lg flex items-center justify-center text-blue-600">
                <i data-lucide="calendar" class="w-4 h-4"></i>
            </div>
        </div>
    </div>

    <!-- 2. METRICS GRID -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
        <?php 
        $metrics = [
            ['rumah', 'home', 'blue', 'TOTAL RUMAH', base_url('rtlh/rekap-desa')],
            ['rlh', 'check-circle', 'emerald', 'RUMAH LAYAK', base_url('rtlh/rekap-desa')],
            ['backlog', 'alert-triangle', 'rose', 'BACKLOG', base_url('rtlh/rekap-desa')],
            ['rtlh', 'home', 'amber', 'RTLH (SASARAN)', base_url('rtlh')],
            ['kumuh', 'map-pin', 'rose', 'KUMUH', base_url('wilayah-kumuh')],
            ['formal', 'building-2', 'indigo', 'PERUMAHAN', base_url('perumahan-formal')],
            ['psu', 'route', 'slate', 'PSU', base_url('psu')],
            ['pisew', 'map', 'orange', 'PISEW', base_url('pisew')],
            ['aset', 'layers', 'emerald', 'ASET', base_url('aset-tanah')],
            ['arsinum', 'droplet', 'cyan', 'ARSINUM', base_url('arsinum')],
        ];
        foreach($metrics as $m): ?>
        <a href="<?= $m[4] ?>" class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-500 group block relative overflow-hidden">
            <div class="w-10 h-10 rounded-xl bg-<?= $m[2] ?>-50 dark:bg-<?= $m[2] ?>-950/30 text-<?= $m[2] ?>-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-all duration-500 shadow-inner">
                <i data-lucide="<?= $m[1] ?>" class="w-5 h-5" stroke-width="2"></i>
            </div>
            <p class="text-[8px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-0.5"><?= $m[3] ?></p>
            <h3 class="text-xl font-bold text-blue-950 dark:text-white tracking-tighter"><?= number_format($rekap[$m[0]]) ?></h3>
        </a>
        <?php endforeach; ?>
    </div>

    <!-- 3. TACTICAL COMMAND MAP -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-xl overflow-hidden flex flex-col transition-all duration-500 relative">
        <div class="p-6 border-b border-slate-50 dark:border-slate-800 flex flex-col lg:flex-row lg:items-center justify-between gap-6 bg-white dark:bg-slate-900 relative z-10">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-600/20">
                    <i data-lucide="map" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-blue-950 dark:text-white uppercase tracking-tight">Database Spasial Terpadu</h3>
                    <div class="flex items-center gap-2 mt-0.5">
                        <div class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-ping"></div>
                        <span id="activeLayerLabel" class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Memuat...</span>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap gap-1.5">
                <?php foreach(['rtlh', 'bansos', 'kumuh', 'formal', 'psu', 'aset', 'arsinum', 'pisew'] as $l): ?>
                <button onclick="switchLayer('<?= $l ?>')" class="layer-btn <?= $l=='rtlh'?'active':'' ?> px-4 py-2 rounded-xl text-[8px] font-bold uppercase tracking-widest transition-all border border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm flex items-center gap-1.5 hover:border-blue-200 active:scale-95" data-layer="<?= $l ?>">
                    <i data-lucide="<?= $l=='rtlh'?'home':($l=='bansos'?'check-circle':($l=='kumuh'?'map-pin':($l=='formal'?'building-2':($l=='psu'?'route':($l=='aset'?'layers':($l=='arsinum'?'droplet':'map')))))) ?>" class="w-3 h-3"></i> <?= $l == 'formal' ? 'PERUMAHAN' : strtoupper($l) ?>
                </button>
                <?php endforeach; ?>
            </div>
        </div>
        <div id="tacticalMap" class="h-[55vh] lg:h-[65vh] w-full z-0 bg-slate-50 dark:bg-slate-950"></div>
    </div>

    <!-- 4. BOTTOM ANALYTICS -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-8 border border-slate-100 dark:border-slate-800 shadow-sm">
            <h3 class="text-[9px] font-bold text-blue-950 dark:text-white uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                <span class="w-6 h-[2px] bg-blue-600"></span> Analisis Kelayakan
            </h3>
            <div id="conditionChart" class="flex justify-center"></div>
        </div>
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-2xl p-8 border border-slate-100 dark:border-slate-800 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 right-0 p-8 opacity-5 pointer-events-none">
                <i data-lucide="alert-triangle" class="w-32 h-32 text-rose-600"></i>
            </div>
            <h3 class="text-[9px] font-bold text-rose-600 uppercase tracking-[0.2em] mb-6 flex items-center gap-3">
                <span class="w-6 h-[2px] bg-rose-600"></span> Prioritas Kawasan Kumuh
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 relative z-10">
                <?php foreach($topKumuh as $k): ?>
                <a href="<?= base_url('wilayah-kumuh/detail/' . $k['FID']) ?>" class="p-4 flex items-center justify-between bg-slate-50 dark:bg-slate-800/50 hover:bg-white dark:hover:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl transition-all duration-500 group shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-rose-600 text-white flex items-center justify-center font-bold text-base group-hover:scale-110 transition-all duration-500 shadow-lg shadow-rose-600/20"><?= substr($k['Kelurahan'], 0, 1) ?></div>
                        <div>
                            <p class="text-xs font-bold text-blue-950 dark:text-white uppercase tracking-tight"><?= $k['Kelurahan'] ?></p>
                            <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-0.5"><?= $k['Kawasan'] ?: 'Kawasan Kumuh' ?></p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xl font-bold text-rose-600 leading-none italic"><?= number_format($k['skor_kumuh'], 0) ?></p>
                        <p class="text-[7px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Skor</p>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<style>
    .layer-btn.active { background: #1e1b4b !important; color: white !important; border-color: #1e1b4b !important; box-shadow: 0 10px 20px -5px rgba(30, 27, 75, 0.4); transform: translateY(-1px); }
    .dark .layer-btn.active { background: #2563eb !important; border-color: #2563eb !important; box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.4); }
    .leaflet-popup-content-wrapper { border-radius: 1rem; padding: 0; overflow: hidden; box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.2); border: none; }
    .leaflet-popup-content { margin: 0; width: 200px !important; }
    .marker-cluster-small div, .marker-cluster-medium div, .marker-cluster-large div { background-color: rgba(30, 27, 75, 0.9); color: white; font-weight: 900; font-size: 10px; }

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
    const spasialData = <?= json_encode($spasial) ?>;
    let map, clusterGroup, kecLayerGroup, activeDataGroup;
    let standard, satellite;

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

    function parseWKTUniversal(wkt) {
        if (!wkt || typeof wkt !== 'string' || typeof wellknown === 'undefined') return null;
        try {
            let cleanWkt = wkt.includes(';') ? wkt.split(';')[1] : wkt;
            let geojson = wellknown.parse(cleanWkt);
            if (!geojson) return null;
            
            // Intelligent UTM detection
            const convert = (c) => {
                if (typeof c[0] === 'number') {
                    if (Math.abs(c[0]) > 500) { // Likely UTM Easting/Northing
                        const [lat, lon] = utmToLatLng(c[0], c[1]);
                        return [lon, lat];
                    }
                    return c;
                }
                return c.map(convert);
            };
            geojson.coordinates = convert(geojson.coordinates);
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

    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
        initMap();
        initChart();
        setTimeout(() => switchLayer('rtlh'), 600);
    });

    let rot = 0;
    function initMap() {
        if (typeof L === 'undefined') { setTimeout(initMap, 100); return; }
        const isDark = document.documentElement.classList.contains('dark');
        const cartoDB = L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { 
            attribution: '&copy; CartoDB' 
        });
        const googleSat = L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains:['mt0','mt1','mt2','mt3'],
            attribution: '&copy; Google'
        });
        
        map = L.map('tacticalMap', { zoomControl: false, layers: [cartoDB] }).setView([-5.1245, 120.2536], 11);
        
        const LayerToggle = L.Control.extend({
            onAdd: function(map) {
                const btn = L.DomUtil.create('button', 'bg-white dark:bg-slate-900 rounded-lg shadow-xl border border-slate-100 dark:border-slate-800 transition-all duration-300 active:scale-90 mt-2 flex items-center justify-center');
                btn.type = 'button';
                btn.style.width = '38px'; btn.style.height = '38px'; btn.style.cursor = 'pointer';
                const isDark = document.documentElement.classList.contains('dark');
                const svgColor = isDark ? '#60a5fa' : '#2563eb';
                btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="${svgColor}" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:block; transition: transform 0.8s cubic-bezier(0.65, 0, 0.35, 1);"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>`;
                L.DomEvent.disableClickPropagation(btn);
                L.DomEvent.on(btn, 'click', function(e) {
                    L.DomEvent.stopPropagation(e);
                    L.DomEvent.preventDefault(e);
                    rot += 360;
                    const svg = btn.querySelector('svg');
                    svg.style.transform = `rotate(${rot}deg)`;
                    setTimeout(() => {
                        if (map.hasLayer(cartoDB)) { 
                            map.removeLayer(cartoDB); 
                            map.addLayer(googleSat); 
                            btn.style.backgroundColor = '#2563eb'; 
                            svg.setAttribute('stroke', '#ffffff'); 
                        }
                        else { 
                            map.removeLayer(googleSat); 
                            map.addLayer(cartoDB); 
                            btn.style.backgroundColor = isDark ? '#0f172a' : '#ffffff'; 
                            svg.setAttribute('stroke', svgColor); 
                        }
                    }, 200);
                });
                return btn;
            }
        });
        map.addControl(new LayerToggle({ position: 'topright' }));

        L.control.zoom({ position: 'topright' }).addTo(map);

        clusterGroup = L.markerClusterGroup({ showCoverageOnHover: false, maxClusterRadius: 50 }).addTo(map);
        activeDataGroup = L.featureGroup().addTo(map);
        kecLayerGroup = L.featureGroup().addTo(map);

        const kecColors = ['#1e1b4b', '#1e40af', '#2563eb', '#1d4ed8', '#0ea5e9'];
        spasialData.kecamatan.forEach((k, idx) => {
            try {
                const geojson = parseWKTUniversal(k.wkt);
                if (geojson) {
                    L.geoJSON(geojson, { 
                        style: { color: isDark ? '#0f172a' : '#ffffff', fillColor: kecColors[idx % 5], weight: 0.5, fillOpacity: 0.4 } 
                    }).addTo(kecLayerGroup).bindTooltip(`<p class="font-bold uppercase text-[8px] text-white">${k.desa_nama}</p>`, { sticky: true, className: 'custom-tooltip' });
                }
            } catch (e) {}
        });
        kecLayerGroup.bringToBack();
    }

    function switchLayer(type) {
        clusterGroup.clearLayers();
        activeDataGroup.clearLayers();
        document.querySelectorAll('.layer-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelector(`[data-layer="${type}"]`)?.classList.add('active');
        document.getElementById('activeLayerLabel').innerText = `Database: ${type === 'formal' ? 'PERUMAHAN' : type.toUpperCase()}`;

        const items = spasialData[type] || [];
        const colorMap = { rtlh: '#f59e0b', bansos: '#10b981', kumuh: '#ef4444', formal: '#6366f1', psu: '#3b82f6', arsinum: '#06b6d4', pisew: '#f97316', aset: '#1e1b4b' };
        const detailUrls = { rtlh: '<?= base_url("rtlh/detail") ?>', bansos: '<?= base_url("bansos-rtlh/detail") ?>', kumuh: '<?= base_url("wilayah-kumuh/detail") ?>', formal: '<?= base_url("perumahan-formal/detail") ?>', psu: '<?= base_url("psu/detail") ?>', aset: '<?= base_url("aset-tanah/detail") ?>', arsinum: '<?= base_url("arsinum/detail") ?>', pisew: '<?= base_url("pisew/detail") ?>' };

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
                else if (item.wkt) { geojson = parseWKTUniversal(item.wkt); }
                if (!geojson) return;

                let detailsHtml = '';
                if (type === 'kumuh') {
                    detailsHtml = `<p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1">Luasan: ${item.Luas_kumuh || '-'} Ha</p>
                                   <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-2">RT/RW: ${item.Kode_RT_RW || '-'}</p>`;
                } else if (type === 'aset') {
                    const year = item.tgl_terbit ? new Date(item.tgl_terbit).getFullYear() : '-';
                    detailsHtml = `<p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1">Luasan: ${item.luas_m2 || '-'} m²</p>
                                   <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-2">Tahun: ${year}</p>`;
                } else if (type === 'arsinum') {
                    detailsHtml = `<p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1">Anggaran: Rp ${item.anggaran ? parseInt(item.anggaran).toLocaleString('id-ID') : '-'}</p>
                                   <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-2">Tahun: ${item.tahun || '-'}</p>`;
                } else if (type === 'psu') {
                    detailsHtml = `<p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1">Anggaran/Nilai: Rp ${item.nilai ? parseInt(item.nilai).toLocaleString('id-ID') : '-'}</p>
                                   <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-2">Tahun: ${item.tahun || '-'}</p>`;
                } else if (type === 'formal') {
                    detailsHtml = `<p class="text-[8px] font-bold text-emerald-500 uppercase tracking-widest mb-2">Informasi Terverifikasi</p>`;
                } else if (type === 'rtlh' || type === 'bansos') {
                    detailsHtml = `<p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1">Desa: ${item.desa || '-'}</p>
                                   <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-2">Status: ${type === 'rtlh' ? 'Belum Menerima' : 'Sudah Menerima'}</p>`;
                } else {
                    detailsHtml = `<p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-2">Informasi Terverifikasi</p>`;
                }

                const popupContent = `<div class="bg-blue-950 text-white p-3 rounded-t-xl"><h5 class="text-[11px] font-bold uppercase leading-tight">${item.name}</h5></div><div class="p-3 bg-white dark:bg-slate-900 rounded-b-xl border-t border-slate-50 dark:border-slate-800">${detailsHtml}<a href="${detailUrls[type]}/${item.id}" class="block w-full py-2 bg-blue-600 hover:bg-blue-700 text-white text-center text-[10px] font-black uppercase tracking-[0.1em] rounded-xl shadow-lg transition-all">Detail</a></div>`;

                if (geojson.type === 'Point') { 
                    const icon = L.divIcon({
                        className: 'custom-div-icon',
                        html: `<div class="w-6 h-6 rounded-full border-4 border-white shadow-xl flex items-center justify-center" style="background-color: ${colorMap[type] || '#ef4444'};"><div class="w-1 h-1 bg-white rounded-full"></div></div>`,
                        iconSize: [24, 24],
                        iconAnchor: [12, 12]
                    });
                    L.marker([geojson.coordinates[1], geojson.coordinates[0]], { icon: icon }).bindPopup(popupContent).addTo(clusterGroup); 
                }
                else { L.geoJSON(geojson, { style: { color: colorMap[type] || '#ef4444', weight: 3, fillOpacity: 0.5 } }).bindPopup(popupContent).addTo(activeDataGroup); }
            } catch (e) {}
        });

        const bounds = L.latLngBounds();
        let valid = false;
        [clusterGroup, activeDataGroup].forEach(g => { if(g.getLayers().length > 0) { bounds.extend(g.getBounds()); valid = true; } });
        if (valid && bounds.isValid()) map.fitBounds(bounds, { padding: [50, 50], maxZoom: 16 });
    }

    function initChart() {
        const s = <?= json_encode($statusLayak) ?>;
        new ApexCharts(document.querySelector("#conditionChart"), {
            series: [parseInt(s.layak||0), parseInt(s.menuju_layak||0), parseInt(s.tidak_layak||0)],
            chart: { type: 'donut', height: 300, fontFamily: 'inherit' },
            labels: ['LAYAK', 'MENUJU LAYAK', 'TIDAK LAYAK'],
            colors: ['#10b981', '#f59e0b', '#ef4444'],
            plotOptions: { pie: { donut: { size: '85%', labels: { show: true, total: { show: true, label: 'DATA', color: '#94a3b8', fontSize: '9px', fontWeight: 900 } } } } },
            legend: { position: 'bottom', fontSize: '9px', fontWeight: 700 },
            stroke: { show: false }
        }).render();
    }
</script>
<?= $this->endSection() ?>
