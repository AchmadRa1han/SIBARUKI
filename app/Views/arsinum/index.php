<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<!-- Leaflet Assets -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>

<div class="space-y-6 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-blue-950 dark:text-white uppercase tracking-tight">ARSINUM</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium text-sm">Pemetaan Teknologi Air Siap Minum Kabupaten Sinjai.</p>
        </div>
        <div class="flex flex-wrap md:flex-nowrap items-center justify-end gap-2 flex-shrink-0">
            <div class="bg-blue-50 text-blue-600 px-3 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest border border-blue-100 whitespace-nowrap">
                <?= number_format($total_unit ?? 0) ?> Unit
            </div>
            <a href="<?= base_url('arsinum/export-excel') ?>" class="bg-emerald-50 text-emerald-600 px-3 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest border border-emerald-100 hover:bg-emerald-600 hover:text-white transition-all flex items-center gap-2 whitespace-nowrap">
                <i data-lucide="download" class="w-3 h-3"></i> Export Excel
            </a>
            <?php if (has_permission('create_rtlh')): ?>
            <a href="<?= base_url('arsinum/create') ?>" class="bg-blue-950 hover:bg-black text-white px-3 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest shadow-lg transition-all flex items-center gap-2 group whitespace-nowrap">
                <i data-lucide="plus" class="w-3 h-3 group-hover:rotate-90 transition-transform"></i> Tambah Data
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Map Section -->
    <div class="relative group">
        <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-cyan-600 rounded-[2.5rem] blur opacity-10 transition duration-1000"></div>
        <div class="relative bg-white dark:bg-slate-900 rounded-[2.5rem] overflow-hidden shadow-2xl border border-slate-100 dark:border-slate-800">
            <div id="map" class="w-full h-[60vh] z-10" style="min-height: 450px; background: #ececec;"></div>
            <div class="absolute top-6 left-6 z-[1000] hidden md:block">
                <div class="bg-blue-950/80 backdrop-blur-md text-white px-4 py-2.5 rounded-2xl text-[9px] font-black uppercase tracking-widest shadow-2xl border border-white/10 flex items-center gap-3">
                    <div class="w-1.5 h-1.5 bg-blue-400 rounded-full animate-ping"></div>
                    Database ARSINUM Terintegrasi
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="p-6 border-b border-slate-50 dark:border-slate-800 flex flex-col lg:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-blue-950 rounded-2xl flex items-center justify-center text-white shadow-xl">
                    <i data-lucide="database" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-xs font-black text-blue-950 dark:text-white uppercase tracking-tight">Daftar Pekerjaan</h3>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Informasi Proyek Terkini</p>
                </div>
            </div>

            <form action="<?= base_url('arsinum') ?>" method="get" class="flex flex-col md:flex-row items-center gap-2 w-full lg:w-auto" id="filter-form">
                <input type="hidden" name="sort_by" value="<?= $sortBy ?>">
                <input type="hidden" name="sort_order" value="<?= $sortOrder ?>">
                <select name="per_page" onchange="submitWithScroll(this)" class="w-full md:w-24 bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-[9px] font-black px-3 py-2.5 focus:ring-2 focus:ring-blue-500 cursor-pointer">
                    <?php foreach([5, 10, 25, 50, 100] as $p): ?>
                        <option value="<?= $p ?>" <?= $perPage == $p ? 'selected' : '' ?>><?= $p ?> Baris</option>
                    <?php endforeach; ?>
                </select>
                <select name="kecamatan" onchange="submitWithScroll(this)" class="w-full md:w-40 bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-[9px] font-black px-3 py-2.5 focus:ring-2 focus:ring-blue-500 cursor-pointer">
                    <option value="">Semua Kecamatan</option>
                    <?php foreach($kecamatans as $k): ?>
                        <option value="<?= $k['kecamatan'] ?>" <?= $selected_kecamatan == $k['kecamatan'] ? 'selected' : '' ?>><?= $k['kecamatan'] ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="relative w-full md:w-56">
                    <input type="text" name="search" value="<?= $search ?>" placeholder="Cari pekerjaan..." class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-[9px] font-black px-3 py-2.5 pl-9 focus:ring-2 focus:ring-blue-500 transition-all uppercase">
                    <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse table-fixed">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/50 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                        <th class="px-4 py-4 w-64 cursor-pointer hover:text-blue-600" onclick="applySort('jenis_pekerjaan')">Jenis Pekerjaan</th>
                        <th class="px-4 py-4 w-24 text-center">Volume</th>
                        <th class="px-4 py-4 w-48">Wilayah (Desa/Kec)</th>
                        <th class="px-4 py-4 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800 text-[10px]">
                    <?php foreach($arsinum as $item): ?>
                    <tr class="group hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-all duration-200">
                        <td class="px-4 py-3 font-black text-blue-950 dark:text-white uppercase truncate" title="<?= $item['jenis_pekerjaan'] ?>"><?= $item['jenis_pekerjaan'] ?></td>
                        <td class="px-4 py-3 text-center"><span class="px-2 py-0.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 rounded-md font-bold"><?= $item['volume'] ?></span></td>
                        <td class="px-4 py-3">
                            <div class="flex flex-col"><span class="font-bold text-slate-700 dark:text-slate-200 uppercase truncate"><?= $item['desa'] ?></span><span class="text-[8px] text-slate-400 font-bold uppercase"><?= $item['kecamatan'] ?></span></div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <?php if($item['koordinat']): ?>
                                <button onclick="focusMap(<?= $item['koordinat'] ?>)" class="p-2 bg-white dark:bg-slate-800 text-blue-600 rounded-lg shadow-sm border border-slate-100 dark:border-slate-700 hover:bg-blue-600 hover:text-white transition-all active:scale-95"><i data-lucide="map-pin" class="w-3.5 h-3.5"></i></button>
                                <?php endif; ?>
                                <a href="<?= base_url('arsinum/detail/'.$item['id']) ?>" class="p-2 bg-blue-950 text-white rounded-lg shadow-md hover:bg-black transition-all active:scale-95" title="Detail"><i data-lucide="eye" class="w-3.5 h-3.5"></i></a>
                                <button onclick="confirmDelete(<?= $item['id'] ?>)" class="p-2 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-600 hover:text-white transition-all active:scale-95"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php if (isset($pager)): ?><div class="p-6 bg-slate-50/50 dark:bg-slate-800/50 flex justify-center border-t border-slate-100 dark:border-slate-800"><?= $pager->links('group1', 'tailwind_full') ?></div><?php endif; ?>
    </div>
</div>

<form id="delete-form" action="" method="post" class="hidden"><?= csrf_field() ?></form>

<script>
    let map;
    let clusterGroup;
    let rot = 0;

    function initMap() {
        if (typeof L === 'undefined') { setTimeout(initMap, 100); return; }
        
        try {
            const isDark = document.documentElement.classList.contains('dark');
            const standard = L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { attribution: '&copy; Sibaruki' });
            const satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: '&copy; Esri' });

            map = L.map('map', { zoomControl: false, layers: [standard] }).setView([-5.1245, 120.2536], 11);
            L.control.zoom({ position: 'topright' }).addTo(map);

            // --- STANDARDIZED LAYER TOGGLE ---
            const LayerToggle = L.Control.extend({
                onAdd: function(map) {
                    const btn = L.DomUtil.create('button', 'bg-white dark:bg-slate-900 rounded-xl shadow-xl border border-slate-100 dark:border-slate-800 transition-all duration-300 active:scale-90 mt-2 flex items-center justify-center');
                    btn.style.width = '44px'; btn.style.height = '44px'; btn.style.cursor = 'pointer';
                    
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
                                btn.style.backgroundColor = '#2563eb'; svg.setAttribute('stroke', '#ffffff');
                            } else {
                                map.removeLayer(satellite); map.addLayer(standard);
                                btn.style.backgroundColor = isDark ? '#0f172a' : '#ffffff'; svg.setAttribute('stroke', svgColor);
                            }
                        }, 200);
                    });
                    return btn;
                }
            });
            map.addControl(new LayerToggle({ position: 'topright' }));

            clusterGroup = L.markerClusterGroup({ showCoverageOnHover: false, maxClusterRadius: 50 });
            const arsinumData = <?= json_encode($arsinum_all ?? []) ?>;
            arsinumData.forEach(item => {
                if (item.koordinat) {
                    const coords = item.koordinat.split(',').map(c => parseFloat(c.trim()));
                    if (coords.length === 2 && !isNaN(coords[0]) && !isNaN(coords[1])) {
                        const marker = L.circleMarker(coords, { radius: 8, fillColor: "#2563eb", color: "#fff", weight: 2, fillOpacity: 0.8 });
                        marker.bindPopup(`
                            <div class="bg-blue-950 text-white p-4 rounded-t-xl"><p class="text-[8px] font-black uppercase tracking-widest text-blue-400 mb-1">ARSINUM</p><h5 class="text-xs font-black uppercase leading-tight">${item.jenis_pekerjaan}</h5></div>
                            <div class="p-4 bg-white dark:bg-slate-900 space-y-2 rounded-b-xl"><p class="text-[10px] font-bold text-slate-700">📍 ${item.desa}</p><a href="<?= base_url('arsinum/detail/') ?>/${item.id}" class="block w-full py-2 bg-blue-950 text-white text-center text-[9px] font-black uppercase tracking-widest rounded-xl transition-all">Detail</a></div>
                        `);
                        clusterGroup.addLayer(marker);
                    }
                }
            });
            map.addLayer(clusterGroup);
            if (typeof lucide !== 'undefined') lucide.createIcons();
        } catch(err) { console.error(err); }
    }

    function focusMap(lat, lng) {
        map.setView([lat, lng], 18);
        const mc = document.getElementById('main-content');
        if (mc) mc.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function confirmDelete(id) {
        customConfirm('Hapus Data?', 'Hapus data ARSINUM ini?', 'danger').then(conf => {
            if (conf) { document.getElementById('delete-form').action = `<?= base_url('arsinum/delete') ?>/${id}`; document.getElementById('delete-form').submit(); }
        });
    }

    function submitWithScroll(el) {
        const mc = document.getElementById('main-content');
        if (mc) localStorage.setItem('arsinumScrollPos', mc.scrollTop);
        const form = el.tagName === 'FORM' ? el : el.form;
        if (form) form.submit();
    }

    function applySort(col) {
        const f = document.getElementById('filter-form');
        const b = f.querySelector('input[name="sort_by"]');
        const o = f.querySelector('input[name="sort_order"]');
        if (b.value === col) o.value = o.value === 'asc' ? 'desc' : 'asc';
        else { b.value = col; o.value = 'asc'; }
        submitWithScroll(f);
    }

    document.addEventListener('DOMContentLoaded', () => {
        const mc = document.getElementById('main-content');
        if (mc) {
            const sp = localStorage.getItem('arsinumScrollPos');
            if (sp) { setTimeout(() => { mc.scrollTop = sp; localStorage.removeItem('arsinumScrollPos'); }, 100); }
            document.querySelectorAll('nav a').forEach(link => link.addEventListener('click', () => localStorage.setItem('arsinumScrollPos', mc.scrollTop)));
        }
    });

    window.addEventListener('load', initMap);
</script>

<style>
    .leaflet-popup-content-wrapper { border-radius: 1.5rem; padding: 0; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3); border: none; }
    .leaflet-popup-content { margin: 0; width: 240px !important; }
    .leaflet-container { font-family: inherit; }
    .marker-cluster-small div, .marker-cluster-medium div, .marker-cluster-large div { background-color: rgba(30, 27, 75, 0.9); color: white; font-weight: 900; }
</style>
<?= $this->endSection() ?>
