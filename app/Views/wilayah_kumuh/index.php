<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<!-- Leaflet & WKT Parser Assets -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
<script src="https://cdn.jsdelivr.net/npm/wellknown@0.5.0/wellknown.js"></script>

<div class="space-y-6 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-blue-950 dark:text-white uppercase tracking-tight">Wilayah Kumuh</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium text-sm">Monitoring dan pemetaan kawasan permukiman kumuh Kabupaten Sinjai.</p>
        </div>
        <div class="flex flex-wrap md:flex-nowrap items-center justify-end gap-2 flex-shrink-0">
            <div id="debug-status" class="bg-blue-50 text-blue-600 px-3 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest border border-blue-100 transition-all whitespace-nowrap">
                Memuat Peta...
            </div>
            <a href="<?= base_url('wilayah-kumuh/export-excel') ?>" class="bg-emerald-50 text-emerald-600 px-3 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest border border-emerald-100 hover:bg-emerald-600 hover:text-white transition-all flex items-center gap-2 whitespace-nowrap">
                <i data-lucide="download" class="w-3 h-3"></i> Export Excel
            </a>
            <?php if (has_permission('create_kumuh')): ?>
            <a href="<?= base_url('wilayah-kumuh/create') ?>" class="bg-blue-950 hover:bg-black text-white px-3 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest shadow-lg transition-all flex items-center gap-2 group whitespace-nowrap">
                <i data-lucide="plus" class="w-3 h-3 group-hover:rotate-90 transition-transform"></i> Tambah Kawasan
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Map Section -->
    <div class="relative group">
        <div class="absolute -inset-1 bg-gradient-to-r from-rose-600 to-orange-600 rounded-[2.5rem] blur opacity-10 transition duration-1000"></div>
        <div class="relative bg-white dark:bg-slate-900 rounded-[2.5rem] overflow-hidden shadow-2xl border border-slate-100 dark:border-slate-800">
            <div id="map" class="w-full h-[60vh] z-10" style="min-height: 450px; background: #ececec;"></div>
            
            <!-- Floating Legend -->
            <div class="absolute bottom-6 left-6 z-[1000] bg-white/90 dark:bg-slate-950/90 backdrop-blur-md p-4 rounded-3xl shadow-2xl border border-white/20 dark:border-slate-800 w-52">
                <h4 class="text-[9px] font-black text-blue-950 dark:text-white uppercase tracking-[0.2em] mb-3 flex items-center gap-2">
                    <i data-lucide="layers" class="w-3 h-3 text-rose-600"></i> Kategori Kumuh
                </h4>
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-[#f43f5e] shadow-lg shadow-rose-200"></div>
                        <span class="text-[10px] font-bold text-slate-700 dark:text-slate-300 uppercase">Berat (> 60)</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-[#f97316] shadow-lg shadow-orange-200"></div>
                        <span class="text-[10px] font-bold text-slate-700 dark:text-slate-300 uppercase">Sedang (40-60)</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-[#f59e0b] shadow-lg shadow-amber-200"></div>
                        <span class="text-[10px] font-bold text-slate-700 dark:text-slate-300 uppercase">Ringan (< 40)</span>
                    </div>
                </div>
            </div>

            <!-- Info Card -->
            <div class="absolute top-6 left-6 z-[1000] hidden md:block">
                <div class="bg-blue-950/80 backdrop-blur-md text-white px-4 py-2.5 rounded-2xl text-[9px] font-black uppercase tracking-widest shadow-2xl border border-white/10 flex items-center gap-3">
                    <div class="w-1.5 h-1.5 bg-rose-500 rounded-full animate-ping"></div>
                    Pemetaan Spasial Real-time
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="p-8 border-b border-slate-50 dark:border-slate-800 flex flex-col lg:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-rose-600 rounded-2xl flex items-center justify-center text-white shadow-xl">
                    <i data-lucide="database" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black text-blue-950 dark:text-white uppercase tracking-tight">Daftar Kawasan Kumuh</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Database Terintegrasi PKP</p>
                </div>
            </div>

            <form action="" method="get" class="flex flex-col md:flex-row items-center gap-3 w-full lg:w-auto" id="filter-form">
                <select name="per_page" onchange="submitWithScroll(this)" class="w-full md:w-24 bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-[9px] font-black px-4 py-3 focus:ring-2 focus:ring-rose-500 transition-all uppercase cursor-pointer">
                    <?php foreach([5, 10, 25, 50, 100] as $p): ?>
                        <option value="<?= $p ?>" <?= ($perPage ?? 10) == $p ? 'selected' : '' ?>><?= $p ?> Baris</option>
                    <?php endforeach; ?>
                </select>
                <div class="relative w-full md:w-80">
                    <input type="text" name="keyword" value="<?= service('request')->getGet('keyword') ?? '' ?>" placeholder="Cari Kelurahan / Kawasan..." class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-[10px] font-bold px-4 py-3 pl-11 focus:ring-2 focus:ring-rose-500 transition-all uppercase">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2"></i>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/50 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">
                        <th class="px-8 py-5">Lokasi</th>
                        <th class="px-8 py-5">Nama Kawasan</th>
                        <th class="px-8 py-5 text-center">Luas (Ha)</th>
                        <th class="px-8 py-5 text-center">Skor Kumuh</th>
                        <th class="px-8 py-5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                    <?php if (!empty($kumuh)): foreach ($kumuh as $item): ?>
                    <tr class="group hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-all duration-300">
                        <td class="px-8 py-5">
                            <div class="flex flex-col gap-1">
                                <span class="text-xs font-black text-blue-950 dark:text-white uppercase tracking-tight"><?= $item['Kelurahan'] ?></span>
                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-widest"><?= $item['Kecamatan'] ?></span>
                            </div>
                        </td>
                        <td class="px-8 py-5"><span class="text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-wider"><?= $item['Kawasan'] ?: '-' ?></span></td>
                        <td class="px-8 py-5 text-center">
                            <div class="inline-flex items-center gap-2 px-3 py-1 bg-white dark:bg-slate-800 rounded-lg border border-slate-100 dark:border-slate-700 shadow-sm">
                                <span class="text-[10px] font-black text-slate-700 dark:text-slate-300"><?= number_format($item['Luas_kumuh'] ?? 0, 2, ',', '.') ?></span>
                                <span class="text-[8px] font-black text-slate-400 uppercase">Ha</span>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <?php 
                                $skor = (float)($item['skor_kumuh'] ?? 0);
                                $color = $skor >= 60 ? 'bg-rose-100 text-rose-600 border-rose-200' : ($skor >= 40 ? 'bg-orange-100 text-orange-600 border-orange-200' : 'bg-amber-100 text-amber-600 border-amber-200');
                            ?>
                            <span class="px-3 py-1 rounded-lg border text-[10px] font-black <?= $color ?>"><?= $skor ?></span>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <?php if(!empty($item['WKT'])): ?>
                                <button onclick="focusMap('<?= $item['FID'] ?>')" class="p-2.5 bg-white dark:bg-slate-800 text-blue-600 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 hover:bg-rose-600 hover:text-white transition-all active:scale-95" title="Fokus Peta"><i data-lucide="map-pin" class="w-4 h-4"></i></button>
                                <?php endif; ?>
                                <a href="<?= base_url('wilayah-kumuh/detail/' . $item['FID']) ?>" class="p-2.5 bg-blue-950 text-white rounded-xl shadow-xl hover:bg-black transition-all active:scale-95" title="Lihat Detail"><i data-lucide="eye" class="w-4 h-4"></i></a>
                                <?php if (has_permission('delete_kumuh')): ?>
                                <button onclick="confirmDelete('<?= $item['FID'] ?>', '<?= addslashes($item['Kelurahan']) ?>')" class="p-2.5 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition-all active:scale-95" title="Hapus Data"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="5" class="px-8 py-12 text-center text-slate-400 font-bold uppercase text-[10px]">Data tidak ditemukan</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if (isset($pager)): ?><div class="p-8 bg-slate-50/50 dark:bg-slate-800/50 flex justify-center border-t border-slate-100 dark:border-slate-800"><?= $pager->links('group1', 'tailwind_full') ?></div><?php endif; ?>
    </div>
</div>

<form id="delete-form" action="" method="post" class="hidden"><?= csrf_field() ?></form>

<script>
    let map;
    let clusterGroup;
    let polygonLayers = {};
    let rot = 0;

    function initMap() {
        if (typeof L === 'undefined') { setTimeout(initMap, 100); return; }

        try {
            const isDark = document.documentElement.classList.contains('dark');
            const standard = L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { attribution: '&copy; Sibaruki' });
            const satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: '&copy; Esri' });
            
            map = L.map('map', { zoomControl: false, layers: [standard] }).setView([-5.1245, 120.2536], 14);
            L.control.zoom({ position: 'topright' }).addTo(map);

            // --- Custom Layer Toggle (FIXED SVG) ---
            const LayerToggle = L.Control.extend({
                onAdd: function(map) {
                    const btn = L.DomUtil.create('button', 'bg-white dark:bg-slate-900 rounded-xl shadow-xl border border-slate-100 dark:border-slate-800 transition-all duration-300 active:scale-90 mt-2 flex items-center justify-center');
                    btn.style.width = '44px';
                    btn.style.height = '44px';
                    btn.style.cursor = 'pointer';
                    
                    const svgColor = isDark ? '#60a5fa' : '#2563eb';
                    btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="${svgColor}" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:block; transition: transform 0.8s cubic-bezier(0.65, 0, 0.35, 1);"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>`;
                    
                    L.DomEvent.disableClickPropagation(btn);
                    L.DomEvent.on(btn, 'click', function(e) {
                        rot += 360;
                        const svg = btn.querySelector('svg');
                        svg.style.transform = `rotate(${rot}deg)`;
                        
                        setTimeout(() => {
                            if (map.hasLayer(standard)) {
                                map.removeLayer(standard); map.addLayer(satellite);
                                btn.style.backgroundColor = '#2563eb';
                                svg.setAttribute('stroke', '#ffffff');
                            } else {
                                map.removeLayer(satellite); map.addLayer(standard);
                                btn.style.backgroundColor = isDark ? '#0f172a' : '#ffffff';
                                svg.setAttribute('stroke', svgColor);
                            }
                        }, 200);
                    });
                    return btn;
                }
            });
            map.addControl(new LayerToggle({ position: 'topright' }));

            clusterGroup = L.markerClusterGroup({ showCoverageOnHover: false, maxClusterRadius: 40 });
            const kumuhData = <?= json_encode($kumuh_all ?? []) ?>;
            let count = 0;

            kumuhData.forEach(item => {
                if (item.WKT && typeof wellknown !== 'undefined') {
                    try {
                        const geojson = wellknown.parse(item.WKT);
                        if (geojson) {
                            const color = item.skor_kumuh >= 60 ? '#f43f5e' : (item.skor_kumuh >= 40 ? '#f97316' : '#f59e0b');
                            
                            const popupContent = `
                                <div class="bg-blue-950 text-white p-4 rounded-t-xl border-b border-white/10">
                                    <p class="text-[8px] font-black uppercase tracking-widest text-rose-400 mb-1">Kawasan Kumuh</p>
                                    <h5 class="text-xs font-black uppercase leading-tight">${item.Kelurahan}</h5>
                                </div>
                                <div class="p-4 bg-white dark:bg-slate-900 space-y-3 rounded-b-xl">
                                    <div class="flex justify-between items-center"><span class="text-[9px] font-bold text-slate-400 uppercase">Kawasan</span><span class="text-[10px] font-black text-slate-700 dark:text-slate-300 uppercase">${item.Kawasan || '-'}</span></div>
                                    <div class="flex justify-between items-center"><span class="text-[9px] font-bold text-slate-400 uppercase">Skor</span><span class="px-2 py-0.5 rounded-md bg-rose-50 dark:bg-rose-900/30 text-rose-600 text-[10px] font-black">${item.skor_kumuh}</span></div>
                                    <a href="<?= base_url('wilayah-kumuh/detail/') ?>/${item.FID}" class="block w-full py-2 bg-blue-950 hover:bg-black text-white text-center text-[9px] font-black uppercase tracking-widest rounded-xl transition-all mt-2">Detail Kawasan</a>
                                </div>
                            `;

                            // 1. Poligon Utama (Ditambahkan langsung ke map)
                            const polyLayer = L.geoJSON(geojson, { 
                                style: { color: color, weight: 2, fillOpacity: 0.45, fillColor: color },
                                interactive: true 
                            }).bindPopup(popupContent);
                            
                            polyLayer.addTo(map);
                            polygonLayers[item.FID] = polyLayer;

                            // 2. Invisible Point untuk Clustering
                            const centroid = polyLayer.getBounds().getCenter();
                            const clusterMarker = L.circleMarker(centroid, { radius: 1, opacity: 0, fillOpacity: 0, interactive: false });
                            clusterGroup.addLayer(clusterMarker);

                            count++;
                        }
                    } catch (e) {}
                }
            });

            map.addLayer(clusterGroup);
            debug.innerHTML = "✅ " + count + " Kawasan Terpetakan";
            debug.className = "bg-emerald-50 text-emerald-600 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm border border-emerald-100";
            if (typeof lucide !== 'undefined') lucide.createIcons();
        } catch (err) { debug.innerHTML = "❌ Error Peta"; }
    }

    function focusMap(fid) {
        if (polygonLayers[fid]) {
            const layer = polygonLayers[fid];
            map.fitBounds(layer.getBounds(), { padding: [50, 50], maxZoom: 18 });
            setTimeout(() => layer.openPopup(), 400);
            const mc = document.getElementById('main-content');
            if (mc) mc.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }

    function confirmDelete(id, name) {
        customConfirm('Hapus Kawasan?', `Hapus data wilayah kumuh di ${name}?`, 'danger').then(conf => {
            if (conf) { const f = document.getElementById('delete-form'); f.action = `<?= base_url('wilayah-kumuh/delete') ?>/${id}`; f.submit(); }
        });
    }

    function submitWithScroll(el) {
        const mc = document.getElementById('main-content');
        if (mc) localStorage.setItem('wilayahKumuhScrollPos', mc.scrollTop);
        const form = el.tagName === 'FORM' ? el : el.form;
        if (form) form.submit();
    }

    document.addEventListener('DOMContentLoaded', () => {
        const mc = document.getElementById('main-content');
        if (mc) {
            const sp = localStorage.getItem('wilayahKumuhScrollPos');
            if (sp) { setTimeout(() => { mc.scrollTop = sp; localStorage.removeItem('wilayahKumuhScrollPos'); }, 150); }
            document.querySelectorAll('nav a').forEach(link => link.addEventListener('click', () => localStorage.setItem('wilayahKumuhScrollPos', mc.scrollTop)));
        }
    });

    window.addEventListener('load', initMap);
</script>

<style>
    .leaflet-popup-content-wrapper { border-radius: 1.5rem; padding: 0; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3); border: none; }
    .leaflet-popup-content { margin: 0; width: 240px !important; }
    .leaflet-container { font-family: inherit; }
    .marker-cluster-small div, .marker-cluster-medium div, .marker-cluster-large div { background-color: rgba(244, 63, 94, 0.85); color: white; font-weight: 900; }
</style>
<?= $this->endSection() ?>
