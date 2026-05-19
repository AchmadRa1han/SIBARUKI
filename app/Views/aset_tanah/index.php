<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<!-- Leaflet Assets -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>

<script src="https://cdn.jsdelivr.net/npm/wellknown@0.5.0/wellknown.js"></script>

<div class="space-y-6 pb-12 text-slate-900 dark:text-slate-200">
    
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-3 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 no-print">
        <a href="<?= base_url('dashboard') ?>" class="hover:text-blue-600 transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">Aset Tanah</span>
    </nav>

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm relative overflow-hidden transition-all duration-300">
        <div class="absolute top-0 right-0 w-48 h-48 bg-blue-600/5 rounded-full -mr-24 -mt-24 blur-3xl"></div>
        <div class="relative z-10 flex items-center gap-4">
            <a href="<?= base_url('dashboard') ?>" class="p-3 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl hover:bg-blue-600 hover:text-white transition-all active:scale-95" title="Kembali">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-blue-950 dark:text-white uppercase tracking-tighter">Aset Tanah Pemda</h1>
                <p class="text-slate-500 dark:text-slate-400 font-medium text-xs mt-1">Manajemen & monitoring aset tanah Pemerintah Daerah Kabupaten Sinjai.</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2 relative z-10">
            <a href="<?= base_url('aset-tanah/export-excel') ?>" class="bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 px-4 py-2 rounded-xl text-[9px] font-bold uppercase tracking-widest border border-emerald-100 dark:border-emerald-800/50 hover:bg-emerald-600 hover:text-white transition-all active:scale-95 flex items-center gap-2 shadow-sm">
                <i data-lucide="download" class="w-3.5 h-3.5"></i> Export
            </a>
            <?php if (has_permission('create_rtlh')): ?>
            <a href="<?= base_url('aset-tanah/create') ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-[9px] font-bold uppercase tracking-widest shadow-lg shadow-blue-600/20 transition-all active:scale-95 flex items-center gap-2 group">
                <i data-lucide="plus" class="w-4 h-4 group-hover:rotate-90 transition-transform"></i> Tambah Data
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Mini Dashboard Statistics -->
    <div id="mini-dashboard" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white dark:bg-slate-900 p-4 rounded-[1.5rem] border border-slate-100 dark:border-slate-800 shadow-sm flex items-center justify-between relative overflow-hidden group">
            <div class="absolute -right-2 -bottom-2 opacity-5 group-hover:scale-110 transition-transform duration-700">
                <i data-lucide="check-circle" class="w-16 h-16 text-blue-600"></i>
            </div>
            <div class="relative z-10 flex-1">
                <p class="text-[8px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1">Aset Bersertifikat</p>
                <div class="flex items-baseline gap-1.5">
                    <h3 id="stat-bersertifikat-count" class="text-2xl font-black text-blue-600 tracking-tighter"><?= number_format($count_bersertifikat) ?></h3>
                    <span class="text-[9px] font-bold text-slate-400 uppercase">Unit</span>
                </div>
                <div class="mt-2.5 flex items-center gap-2 pr-6">
                    <div class="flex-1 h-1.5 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                        <div id="stat-bersertifikat-bar" class="h-full bg-blue-600 rounded-full" style="width: <?= $pct_bersertifikat ?>%"></div>
                    </div>
                    <span id="stat-bersertifikat-pct" class="text-[9px] font-black text-blue-600 w-8 text-right"><?= round($pct_bersertifikat, 1) ?>%</span>
                </div>
            </div>
            <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/30 rounded-xl flex items-center justify-center text-blue-600 shadow-inner shrink-0 relative z-10">
                <i data-lucide="shield-check" class="w-5 h-5"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-4 rounded-[1.5rem] border border-slate-100 dark:border-slate-800 shadow-sm flex items-center justify-between relative overflow-hidden group">
            <div class="absolute -right-2 -bottom-2 opacity-5 group-hover:scale-110 transition-transform duration-700">
                <i data-lucide="alert-circle" class="w-16 h-16 text-amber-500"></i>
            </div>
            <div class="relative z-10 flex-1">
                <p class="text-[8px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1">Belum Bersertifikat</p>
                <div class="flex items-baseline gap-1.5">
                    <h3 id="stat-belum-bersertifikat-count" class="text-2xl font-black text-amber-500 tracking-tighter"><?= number_format($count_belum_bersertifikat) ?></h3>
                    <span class="text-[9px] font-bold text-slate-400 uppercase">Unit</span>
                </div>
                <div class="mt-2.5 flex items-center gap-2 pr-6">
                    <div class="flex-1 h-1.5 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                        <div id="stat-belum-bersertifikat-bar" class="h-full bg-amber-500 rounded-full" style="width: <?= $pct_belum_bersertifikat ?>%"></div>
                    </div>
                    <span id="stat-belum-bersertifikat-pct" class="text-[9px] font-black text-amber-500 w-8 text-right"><?= round($pct_belum_bersertifikat, 1) ?>%</span>
                </div>
            </div>
            <div class="w-10 h-10 bg-amber-50 dark:bg-amber-900/30 rounded-xl flex items-center justify-center text-amber-500 shadow-inner shrink-0 relative z-10">
                <i data-lucide="file-warning" class="w-5 h-5"></i>
            </div>
        </div>
    </div>

    <!-- Map Section -->
    <div class="relative">
        <div class="bg-white dark:bg-slate-900 rounded-2xl overflow-hidden shadow-md border border-slate-100 dark:border-slate-800">
            <div id="map" class="w-full h-[450px] z-10" style="background: #ececec;"></div>
            <div class="absolute top-6 left-6 z-[1000] hidden md:block">
                <div class="bg-blue-950/80 backdrop-blur-md text-white px-4 py-2 rounded-xl text-[9px] font-bold uppercase tracking-[0.2em] shadow-2xl border border-white/10 flex items-center gap-3">
                    <div class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-ping"></div>
                    Database Pertanahan
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Card -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-100 dark:border-slate-800 p-3">
        <div class="flex flex-col lg:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-3 w-full lg:w-auto">
                <div id="status-tabs" class="flex bg-slate-100 dark:bg-slate-800 p-1 rounded-xl w-full md:w-auto">
                    <button data-status="Bersertifikat" class="status-tab flex-1 md:flex-none px-4 py-2 rounded-lg text-[9px] font-bold uppercase tracking-widest transition-all <?= $status_sertifikat == 'Bersertifikat' ? 'bg-white dark:bg-slate-700 text-blue-600 shadow-sm' : 'text-slate-400 hover:text-slate-600' ?>">Bersertifikat</button>
                    <button data-status="Belum Bersertifikat" class="status-tab flex-1 md:flex-none px-4 py-2 rounded-lg text-[9px] font-bold uppercase tracking-widest transition-all <?= $status_sertifikat == 'Belum Bersertifikat' ? 'bg-white dark:bg-slate-700 text-amber-600 shadow-sm' : 'text-slate-400 hover:text-slate-600' ?>">Belum Sertifikat</button>
                    <button data-status="semua" class="status-tab flex-1 md:flex-none px-4 py-2 rounded-lg text-[9px] font-bold uppercase tracking-widest transition-all <?= $status_sertifikat == 'semua' ? 'bg-white dark:bg-slate-700 text-slate-600 shadow-sm' : 'text-slate-400 hover:text-slate-600' ?>">Semua</button>
                </div>
            </div>

            <form action="<?= base_url('aset-tanah') ?>" method="get" class="flex flex-col md:flex-row items-center gap-2 w-full lg:w-auto" id="filter-form">
                <input type="hidden" name="sort_by" value="<?= $sortBy ?>">
                <input type="hidden" name="sort_order" value="<?= $sortOrder ?>">
                <input type="hidden" id="status_sertifikat_input" name="status_sertifikat" value="<?= $status_sertifikat ?>">
                
                <div class="relative w-full md:w-28">
                    <select name="per_page" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-[9px] font-bold uppercase px-3 py-2 focus:ring-2 focus:ring-blue-500 cursor-pointer appearance-none filter-input">
                        <?php foreach([5, 10, 25, 50, 100] as $p): ?>
                            <option value="<?= $p ?>" <?= ($perPage ?? 10) == $p ? 'selected' : '' ?>><?= $p ?> Baris</option>
                        <?php endforeach; ?>
                    </select>
                    <i data-lucide="chevron-down" class="w-3 h-3 text-slate-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                </div>

                <div class="relative w-full md:w-40">
                    <select name="kecamatan" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-[9px] font-bold uppercase px-3 py-2 focus:ring-2 focus:ring-blue-500 cursor-pointer appearance-none filter-input">
                        <option value="">Semua Wilayah</option>
                        <?php foreach($kecamatans as $k): ?>
                            <option value="<?= $k['kecamatan'] ?>" <?= $selected_kecamatan == $k['kecamatan'] ? 'selected' : '' ?>><?= $k['kecamatan'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                </div>

                <div class="relative w-full md:w-64">
                    <input type="text" name="search" value="<?= $search ?>" placeholder="Cari aset..." class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-[9px] font-bold uppercase px-3 py-2 pl-10 focus:ring-2 focus:ring-blue-500 transition-all filter-input">
                    <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Section -->
    <div id="table-container" class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden relative">
        <!-- Floating Bulk Action Bar -->
        <div id="bulk-action-bar" class="absolute top-0 left-0 right-0 z-50 bg-blue-950 text-white p-4 transform -translate-y-full transition-transform duration-500 flex items-center justify-between px-8">
            <div class="flex items-center gap-4">
                <span id="selected-count" class="bg-blue-600 px-3 py-1 rounded-lg text-[9px] font-bold tracking-widest shadow-lg shadow-blue-600/20">0 TERPILIH</span>
                <p class="text-[9px] font-bold uppercase tracking-widest opacity-70 hidden md:block">Aksi massal tersedia</p>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="handleBulkDelete()" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-[9px] font-bold uppercase tracking-widest transition-all active:scale-95 flex items-center gap-2 shadow-lg shadow-rose-600/20">
                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Hapus
                </button>
                <button onclick="clearSelection()" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg text-[9px] font-bold uppercase tracking-widest transition-all active:scale-95">Batal</button>
            </div>
        </div>

        <div class="p-6 border-b border-slate-50 dark:border-slate-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-600/20">
                    <i data-lucide="database" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-blue-950 dark:text-white uppercase tracking-tight">Inventaris Aset Tanah</h3>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-[0.2em]">Data Legalitas & Luas Bidang</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto relative">
            <div id="table-loader" class="absolute inset-0 bg-white/50 dark:bg-slate-900/50 backdrop-blur-[2px] z-[60] flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300">
                <div class="w-8 h-8 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
            </div>
            <table class="w-full text-left border-collapse table-fixed" id="main-table">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/50 text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                        <th class="px-6 py-4 w-16 text-center">
                            <input type="checkbox" id="select-all" class="w-4.5 h-4.5 rounded-lg border-2 border-slate-200 text-blue-600 focus:ring-blue-600/20 cursor-pointer transition-all">
                        </th>
                        <th class="px-4 py-4 w-36 cursor-pointer hover:text-blue-600 transition-colors" onclick="applySort('no_sertifikat')">
                            No. Sertifikat
                        </th>
                        <th class="px-4 py-4 w-64 cursor-pointer hover:text-blue-600 transition-colors" onclick="applySort('nama_pemilik')">
                            Pemilik / Instansi
                        </th>
                        <th class="px-4 py-4 w-32 text-center cursor-pointer hover:text-blue-600 transition-colors" onclick="applySort('luas_m2')">
                            Luas (M²)
                        </th>
                        <th class="px-4 py-4 w-48">Kecamatan</th>
                        <th class="px-6 py-4 text-center w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800 text-[10px]" id="table-body">
                    <?php if (!empty($aset)): foreach($aset as $item): ?>
                    <tr class="group hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-all duration-200">
                        <td class="px-6 py-3 text-center">
                            <input type="checkbox" name="ids[]" value="<?= $item['id'] ?>" class="row-checkbox w-4.5 h-4.5 rounded-lg border-2 border-slate-200 text-blue-600 focus:ring-blue-600/20 cursor-pointer transition-all">
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-col gap-1">
                                <span class="font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider"><?= $item['no_sertifikat'] ?></span>
                                <?php if($item['no_sertifikat'] === 'Belum Bersertifikat'): ?>
                                    <span class="px-2 py-0.5 bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 rounded-md font-bold uppercase text-[7px] border border-amber-100 dark:border-amber-900 w-fit">BELUM SERTIFIKAT</span>
                                <?php else: ?>
                                    <span class="px-2 py-0.5 bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 rounded-md font-bold uppercase text-[7px] border border-blue-100 dark:border-blue-900 w-fit">BERSERTIFIKAT</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-col gap-0.5">
                                <span class="font-bold text-blue-950 dark:text-white uppercase truncate block text-xs mb-0.5" title="<?= $item['nama_pemilik'] ?>"><?= $item['nama_pemilik'] ?></span>
                                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest truncate"><?= $item['lokasi'] ?></span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="font-bold text-slate-700 dark:text-slate-300"><?= number_format($item['luas_m2'], 0, ',', '.') ?></span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="font-bold text-slate-700 dark:text-slate-200 uppercase tracking-tight"><?= $item['kecamatan'] ?></span>
                        </td>
                        <td class="px-6 py-3 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <?php if($item['koordinat']): ?>
                                <button onclick="focusMap('<?= $item['koordinat'] ?>')" class="p-2 bg-white dark:bg-slate-800 text-blue-600 rounded-lg shadow-sm border border-slate-100 dark:border-slate-700 hover:bg-blue-600 hover:text-white transition-all active:scale-95" title="Peta"><i data-lucide="map-pin" class="w-3.5 h-3.5"></i></button>
                                <?php endif; ?>
                                <a href="<?= base_url('aset-tanah/detail/'.$item['id']) ?>" class="p-2 bg-blue-950 dark:bg-blue-600 text-white rounded-lg shadow-md hover:scale-110 transition-all active:scale-95" title="Detail"><i data-lucide="eye" class="w-3.5 h-3.5"></i></a>
                                <button onclick="confirmDelete(<?= $item['id'] ?>)" class="p-2 bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 rounded-lg hover:bg-rose-600 hover:text-white transition-all active:scale-95" title="Hapus"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                        <tr>
                            <td colspan="6" class="px-8 py-16 text-center">
                                <div class="flex flex-col items-center justify-center opacity-40">
                                    <i data-lucide="package-search" class="w-12 h-12 mb-3"></i>
                                    <p class="font-bold uppercase text-[9px] tracking-[0.3em]">Data Tidak Ditemukan</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div id="pagination-container">
            <?php if (isset($pager)): ?>
            <div class="p-6 bg-slate-50/50 dark:bg-slate-800/50 flex justify-center border-t border-slate-100 dark:border-slate-800">
                <?= $pager->links('group1', 'tailwind_full') ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    </div>
</div>

<form id="delete-form" action="" method="post" class="hidden"><?= csrf_field() ?></form>

<script>
    let map, clusterGroup, kecLayerGroup;
    let rot = 0;

    function parseWKTUniversal(wkt) {
        if (!wkt || typeof wkt !== 'string' || typeof wellknown === 'undefined') return null;
        try {
            let cleanWkt = wkt.includes(';') ? wkt.split(';')[1] : wkt;
            let geojson = wellknown.parse(cleanWkt);
            return geojson;
        } catch(e) { return null; }
    }

    function initMap() {
        if (typeof L === 'undefined' || typeof wellknown === 'undefined') { setTimeout(initMap, 100); return; }
        if (map) return; // Guard: prevent double initialization error

        try {
            const isDark = document.documentElement.classList.contains('dark');
            const mapContainer = document.getElementById('map');
            if (!mapContainer) return;

            const cartoDB = L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { 
                attribution: '&copy; CartoDB' 
            });
            const googleSat = L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains:['mt0','mt1','mt2','mt3'],
                attribution: '&copy; Google'
            });

            map = L.map('map', { zoomControl: false, layers: [cartoDB] }).setView([-5.1245, 120.2536], 12);
            L.control.zoom({ position: 'topright' }).addTo(map);

            const LayerToggle = L.Control.extend({
                onAdd: function(map) {
                    const btn = L.DomUtil.create('button', 'bg-white dark:bg-slate-900 rounded-lg shadow-xl border border-slate-100 dark:border-slate-800 transition-all duration-300 active:scale-90 mt-2 flex items-center justify-center');
                    btn.type = 'button'; btn.style.width = '38px'; btn.style.height = '38px'; btn.style.cursor = 'pointer';
                    const svgColor = isDark ? '#60a5fa' : '#2563eb';
                    btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="${svgColor}" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:block; transition: transform 0.8s cubic-bezier(0.65, 0, 0.35, 1);"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>`;
                    L.DomEvent.disableClickPropagation(btn);
                    L.DomEvent.on(btn, 'click', function(e) {
                        rot += 360; btn.querySelector('svg').style.transform = `rotate(${rot}deg)`;
                        setTimeout(() => {
                            if (map.hasLayer(cartoDB)) { map.removeLayer(cartoDB); map.addLayer(googleSat); btn.style.backgroundColor = '#2563eb'; btn.querySelector('svg').setAttribute('stroke', '#ffffff'); }
                            else { map.removeLayer(googleSat); map.addLayer(cartoDB); btn.style.backgroundColor = isDark ? '#0f172a' : '#ffffff'; btn.querySelector('svg').setAttribute('stroke', svgColor); }
                        }, 200);
                    });
                    return btn;
                }
            });
            map.addControl(new LayerToggle({ position: 'topright' }));

            clusterGroup = L.markerClusterGroup({ showCoverageOnHover: false, maxClusterRadius: 50 }).addTo(map);
            kecLayerGroup = L.featureGroup().addTo(map);

            // Render Kecamatan Boundaries (Dashboard Logic)
            const kecData = <?= json_encode($kecamatans_spasial ?? []) ?>;
            const kecColors = ['#1e1b4b', '#1e40af', '#2563eb', '#1d4ed8', '#0ea5e9'];
            kecData.forEach((k, idx) => {
                const geojson = parseWKTUniversal(k.wkt);
                if (geojson) {
                    L.geoJSON(geojson, { 
                        style: { color: isDark ? '#0f172a' : '#ffffff', fillColor: kecColors[idx % 5], weight: 0.5, fillOpacity: 0.2 } 
                    }).addTo(kecLayerGroup).bindTooltip(`<p class="font-bold uppercase text-[8px] text-white">${k.desa}</p>`, { sticky: true, className: 'custom-tooltip' });
                }
            });
            kecLayerGroup.bringToBack();

            renderMarkers(<?= json_encode($aset_all ?? []) ?>);
            if (typeof lucide !== 'undefined') lucide.createIcons();
        } catch(err) { console.error(err); }
    }

    function renderMarkers(data) {
        clusterGroup.clearLayers();
        data.forEach(item => {
            if (item.koordinat) {
                const coords = item.koordinat.split(',').map(c => parseFloat(c.trim()));
                const noSertif = (item.no_sertifikat || '').toString().toUpperCase().trim();
                const isBelum = noSertif === 'BELUM BERSERTIFIKAT' || noSertif === '-' || noSertif === '';
                const markerColor = isBelum ? "#f59e0b" : "#1e1b4b"; // Amber for Belum, Dark Blue for Certified
                
                const icon = L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div class="w-6 h-6 rounded-full border-4 border-white shadow-xl flex items-center justify-center" style="background-color: ${markerColor};"><div class="w-1 h-1 bg-white rounded-full"></div></div>`,
                    iconSize: [24, 24],
                    iconAnchor: [12, 12]
                });

                const marker = L.marker(coords, { icon: icon });
                marker.bindPopup(`
                    <div class="bg-blue-950 text-white p-3 rounded-t-xl border-b border-white/10">
                        <p class="text-[7px] font-bold uppercase tracking-[0.2em] ${isBelum ? 'text-amber-400' : 'text-blue-400'} mb-1">Aset Tanah</p>
                        <h5 class="text-[11px] font-bold uppercase leading-tight">${item.nama_pemilik}</h5>
                    </div>
                    <div class="p-3 bg-white dark:bg-slate-900 space-y-2 rounded-b-xl">
                        <p class="text-[9px] font-bold ${isBelum ? 'text-amber-600' : 'text-blue-600'} uppercase">${item.no_sertifikat}</p>
                        <a href="<?= base_url('aset-tanah/detail/') ?>/${item.id}" class="block w-full py-2.5 bg-blue-950 hover:bg-blue-800 text-white text-center text-[10px] font-black uppercase tracking-[0.2em] rounded-xl shadow-xl transition-all">Detail</a>
                    </div>
                `);
                clusterGroup.addLayer(marker);
            }
        });
        if (data.length > 0) map.fitBounds(clusterGroup.getBounds().pad(0.1));
    }

    function focusMap(coordsStr) {
        if (!coordsStr) return;
        const [lat, lng] = coordsStr.split(',').map(c => parseFloat(c.trim()));
        map.setView([lat, lng], 18);
        const mc = document.getElementById('main-content');
        if (mc) mc.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function confirmDelete(id) {
        customConfirm('Hapus Aset?', 'Pindahkan data ke Recycle Bin?', 'danger').then(conf => {
            if (conf) { const f = document.getElementById('delete-form'); f.action = `<?= base_url('aset-tanah/delete') ?>/${id}`; f.submit(); }
        });
    }

    function submitWithScroll(el) {
        const mc = document.getElementById('main-content');
        if (mc) localStorage.setItem('asetTanahScrollPos', mc.scrollTop);
        const form = el.tagName === 'FORM' ? el : el.form;
        if (form) form.submit();
    }

    function applySort(column) {
        const f = document.getElementById('filter-form');
        const b = f.querySelector('input[name="sort_by"]');
        const o = f.querySelector('input[name="sort_order"]');
        if (b.value === column) o.value = o.value === 'asc' ? 'desc' : 'asc';
        else { b.value = column; o.value = 'asc'; }
        submitWithScroll(f);
    }

    function updateBulkBar() {
        const checked = document.querySelectorAll('.row-checkbox:checked');
        const bulkBar = document.getElementById('bulk-action-bar');
        const selectedCount = document.getElementById('selected-count');
        if (checked.length > 0) { bulkBar.classList.remove('-translate-y-full'); selectedCount.innerText = `${checked.length} TERPILIH`; }
        else { bulkBar.classList.add('-translate-y-full'); }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const mc = document.getElementById('main-content');
        if (mc) {
            const sp = localStorage.getItem('asetTanahScrollPos');
            if (sp) { setTimeout(() => { mc.scrollTop = sp; localStorage.removeItem('asetTanahScrollPos'); }, 100); }
        }
        const selectAll = document.getElementById('select-all');
        const rowCheckboxes = document.querySelectorAll('.row-checkbox');
        if (selectAll) {
            selectAll.addEventListener('change', function() {
                rowCheckboxes.forEach(cb => {
                    cb.checked = this.checked;
                    cb.closest('tr').classList.toggle('bg-blue-50/50', this.checked);
                    cb.closest('tr').classList.toggle('dark:bg-blue-900/10', this.checked);
                });
                updateBulkBar();
            });
        }
        rowCheckboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                this.closest('tr').classList.toggle('bg-blue-50/50', this.checked);
                this.closest('tr').classList.toggle('dark:bg-blue-900/10', this.checked);
                const allChecked = document.querySelectorAll('.row-checkbox:checked').length === rowCheckboxes.length;
                if(selectAll) selectAll.checked = allChecked;
                updateBulkBar();
            });
        });
        initMap();
    });

    async function handleBulkDelete() {
        const checked = document.querySelectorAll('.row-checkbox:checked');
        const ids = Array.from(checked).map(cb => cb.value);
        const ok = await window.customConfirm('Hapus Massal?', `Apakah Anda yakin ingin menghapus ${ids.length} data aset yang dipilih?`, 'danger');
        if (ok) {
            const formData = new FormData();
            ids.forEach(id => formData.append('ids[]', id));
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
            try {
                const response = await fetch('<?= base_url('aset-tanah/bulk-delete') ?>', { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const result = await response.json();
                if (result.status === 'success') { showToast(result.message, 'success'); setTimeout(() => window.location.reload(), 1000); }
                else { showToast(result.message, 'error'); }
            } catch (error) { showToast('Terjadi kesalahan sistem.', 'error'); }
        }
    }

    function clearSelection() {
        document.getElementById('select-all').checked = false;
        document.querySelectorAll('.row-checkbox').forEach(cb => { cb.checked = false; cb.closest('tr').classList.remove('bg-blue-50/50', 'dark:bg-blue-900/10'); });
        updateBulkBar();
    }

    window.addEventListener('load', initMap);

    // AJAX Dynamic Filtering
    async function updateData(url) {
        const loader = document.getElementById('table-loader');
        loader.classList.remove('opacity-0', 'pointer-events-none');
        
        try {
            const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const result = await response.json();
            
            if (result.status === 'success') {
                // Parse the returned HTML to extract components
                const parser = new DOMParser();
                const doc = parser.parseFromString(result.html, 'text/html');
                
                // Update Table Body
                document.getElementById('table-body').innerHTML = doc.getElementById('table-body').innerHTML;
                
                // Update Pagination
                document.getElementById('pagination-container').innerHTML = doc.getElementById('pagination-container').innerHTML;
                
                // Update Mini Dashboard Stats
                if (result.data) {
                    document.getElementById('stat-bersertifikat-count').innerText = new Intl.NumberFormat().format(result.data.count_bersertifikat);
                    document.getElementById('stat-belum-bersertifikat-count').innerText = new Intl.NumberFormat().format(result.data.count_belum_bersertifikat);
                    document.getElementById('stat-bersertifikat-bar').style.width = result.data.pct_bersertifikat + '%';
                    document.getElementById('stat-belum-bersertifikat-bar').style.width = result.data.pct_belum_bersertifikat + '%';
                    document.getElementById('stat-bersertifikat-pct').innerText = result.data.pct_bersertifikat + '%';
                    document.getElementById('stat-belum-bersertifikat-pct').innerText = result.data.pct_belum_bersertifikat + '%';
                }
                
                // Re-initialize Lucide Icons and Tooltips in the new content
                if (typeof lucide !== 'undefined') lucide.createIcons();
                
                // Update Map Markers if aset_all is provided
                if (result.data.aset_all && map && clusterGroup) {
                    renderMarkers(result.data.aset_all);
                }
                
                // Update Browser URL
                window.history.pushState({}, '', url);
                
                // Re-attach checkbox listeners if needed
                attachCheckboxListeners();
            }
        } catch (error) {
            console.error('AJAX Update Error:', error);
            showToast('Gagal memuat data.', 'error');
        } finally {
            loader.classList.add('opacity-0', 'pointer-events-none');
        }
    }

    function attachCheckboxListeners() {
        const selectAll = document.getElementById('select-all');
        const rowCheckboxes = document.querySelectorAll('.row-checkbox');
        if (selectAll) {
            selectAll.checked = false;
            selectAll.onclick = function() {
                rowCheckboxes.forEach(cb => {
                    cb.checked = this.checked;
                    cb.closest('tr').classList.toggle('bg-blue-50/50', this.checked);
                    cb.closest('tr').classList.toggle('dark:bg-blue-900/10', this.checked);
                });
                updateBulkBar();
            };
        }
        rowCheckboxes.forEach(cb => {
            cb.onchange = function() {
                this.closest('tr').classList.toggle('bg-blue-50/50', this.checked);
                this.closest('tr').classList.toggle('dark:bg-blue-900/10', this.checked);
                updateBulkBar();
            };
        });
    }

    document.addEventListener('click', (e) => {
        // Handle Tab Clicks
        if (e.target.classList.contains('status-tab')) {
            const status = e.target.getAttribute('data-status');
            document.getElementById('status_sertifikat_input').value = status;
            
            // UI Active State
            document.querySelectorAll('.status-tab').forEach(btn => {
                btn.classList.remove('bg-white', 'dark:bg-slate-700', 'text-blue-600', 'text-amber-600', 'text-slate-600', 'shadow-sm');
                btn.classList.add('text-slate-400', 'hover:text-slate-600');
            });
            const activeColor = status === 'Bersertifikat' ? 'text-blue-600' : (status === 'Belum Bersertifikat' ? 'text-amber-600' : 'text-slate-600');
            e.target.classList.add('bg-white', 'dark:bg-slate-700', activeColor, 'shadow-sm');
            e.target.classList.remove('text-slate-400', 'hover:text-slate-600');
            
            const form = document.getElementById('filter-form');
            const url = new URL(form.action);
            const formData = new FormData(form);
            for (let [key, val] of formData.entries()) url.searchParams.set(key, val);
            updateData(url.toString());
        }
        
        // Handle Pagination Links
        const paginationLink = e.target.closest('#pagination-container a');
        if (paginationLink) {
            e.preventDefault();
            updateData(paginationLink.href);
        }
    });

    document.querySelectorAll('.filter-input').forEach(input => {
        input.addEventListener('change', () => {
            const form = document.getElementById('filter-form');
            const url = new URL(form.action);
            const formData = new FormData(form);
            for (let [key, val] of formData.entries()) url.searchParams.set(key, val);
            updateData(url.toString());
        });
        if (input.type === 'text') {
            let timeout;
            input.addEventListener('keyup', () => {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    input.dispatchEvent(new Event('change'));
                }, 500);
            });
        }
    });

    // Initial attach
    document.addEventListener('DOMContentLoaded', attachCheckboxListeners);
</script>

<style>
    .leaflet-popup-content-wrapper { border-radius: 1rem; padding: 0; overflow: hidden; box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.2); border: none; }
    .leaflet-popup-content { margin: 0; width: 200px !important; }
    .leaflet-container { font-family: inherit; }
    .marker-cluster-small div, .marker-cluster-medium div, .marker-cluster-large div { background-color: rgba(30, 27, 75, 0.9); color: white; font-weight: 900; font-size: 10px; }

    .custom-div-icon {
        background: transparent;
        border: none;
    }
    .custom-div-icon div {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .custom-div-icon:hover div {
        transform: scale(1.2);
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
    }

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
<?= $this->endSection() ?>
