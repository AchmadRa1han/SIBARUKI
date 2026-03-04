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
            <h1 class="text-3xl font-black text-blue-950 dark:text-white uppercase tracking-tight">Aset Tanah Pemda</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium text-sm">Manajemen & monitoring aset tanah Pemerintah Daerah Kabupaten Sinjai.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <div class="bg-blue-50 text-blue-600 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm border border-blue-100">
                <?= number_format($total_aset, 0, ',', '.') ?> Bidang
            </div>
            <a href="<?= base_url('aset-tanah/create') ?>" class="bg-blue-950 hover:bg-black text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-xl transition-all flex items-center gap-2 group">
                <i data-lucide="plus" class="w-3.5 h-3.5 group-hover:rotate-90 transition-transform"></i> Tambah Aset
            </a>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-900 p-6 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-800 flex items-center gap-4 group hover:border-blue-500 transition-all">
            <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/20 rounded-2xl flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                <i data-lucide="map" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Total Luas</p>
                <p class="text-lg font-black text-blue-950 dark:text-white"><?= number_format($total_luas, 0, ',', '.') ?> <span class="text-[10px] text-slate-400">m²</span></p>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-800 flex items-center gap-4 group hover:border-emerald-500 transition-all">
            <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform">
                <i data-lucide="banknote" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Nilai Aset</p>
                <p class="text-lg font-black text-blue-950 dark:text-white"><span class="text-[10px] text-slate-400">Rp</span> <?= number_format($total_nilai / 1000000000, 2, ',', '.') ?> <span class="text-[10px] text-slate-400">M</span></p>
            </div>
        </div>
    </div>

    <!-- Map Section -->
    <div class="relative group">
        <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-[2.5rem] blur opacity-10 transition duration-1000"></div>
        <div class="relative bg-white dark:bg-slate-900 rounded-[2.5rem] overflow-hidden shadow-2xl border border-slate-100 dark:border-slate-800">
            <div id="map" class="w-full h-[60vh] z-10" style="min-height: 450px; background: #f8fafc;"></div>
            
            <!-- Info Card (Top Left) -->
            <div class="absolute top-6 left-6 z-[1000] hidden md:block">
                <div class="bg-blue-950/80 backdrop-blur-md text-white px-4 py-2.5 rounded-2xl text-[9px] font-black uppercase tracking-widest shadow-2xl border border-white/10 flex items-center gap-3">
                    <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-ping"></div>
                    Database Pertanahan Terkini
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table Section -->
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="p-6 border-b border-slate-50 dark:border-slate-800 flex flex-col lg:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-blue-950 rounded-2xl flex items-center justify-center text-white shadow-xl">
                    <i data-lucide="database" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-xs font-black text-blue-950 dark:text-white uppercase tracking-tight">Inventaris Tanah</h3>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Informasi Berbasis Sertifikat</p>
                </div>
            </div>

            <form action="<?= base_url('aset-tanah') ?>" method="get" class="flex flex-col md:flex-row items-center gap-2 w-full lg:w-auto" id="filter-form">
                <input type="hidden" name="sort_by" value="<?= $sortBy ?>">
                <input type="hidden" name="sort_order" value="<?= $sortOrder ?>">
                
                <select name="per_page" onchange="submitWithScroll(this)" class="w-full md:w-24 bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-[9px] font-black px-3 py-2.5 focus:ring-2 focus:ring-blue-500 transition-all uppercase cursor-pointer">
                    <?php foreach([5, 10, 25, 50, 100] as $p): ?>
                        <option value="<?= $p ?>" <?= $perPage == $p ? 'selected' : '' ?>><?= $p ?> Baris</option>
                    <?php endforeach; ?>
                </select>
                <select name="kecamatan" onchange="submitWithScroll(this)" class="w-full md:w-40 bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-[9px] font-black px-3 py-2.5 focus:ring-2 focus:ring-blue-500 transition-all uppercase cursor-pointer">
                    <option value="">Semua Wilayah</option>
                    <?php foreach($kecamatans as $k): ?>
                        <option value="<?= $k['kecamatan'] ?>" <?= $selected_kecamatan == $k['kecamatan'] ? 'selected' : '' ?>><?= $k['kecamatan'] ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="relative w-full md:w-56">
                    <input type="text" name="search" value="<?= $search ?>" placeholder="Cari aset..." class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-[9px] font-black px-3 py-2.5 pl-9 focus:ring-2 focus:ring-blue-500 transition-all uppercase">
                    <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse table-fixed">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/50 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                        <th class="px-4 py-4 w-28 cursor-pointer hover:text-blue-600 transition-colors" onclick="applySort('no_sertifikat')">
                            <div class="flex items-center gap-2">
                                Sertifikat
                                <?= $sortBy === 'no_sertifikat' ? ($sortOrder === 'asc' ? '<i data-lucide="chevron-up" class="w-3 h-3 text-blue-600"></i>' : '<i data-lucide="chevron-down" class="w-3 h-3 text-blue-600"></i>') : '<i data-lucide="chevrons-up-down" class="w-3 h-3 opacity-30"></i>' ?>
                            </div>
                        </th>
                        <th class="px-4 py-4 w-64 cursor-pointer hover:text-blue-600 transition-colors" onclick="applySort('nama_pemilik')">
                            <div class="flex items-center gap-2">
                                Nama Pemilik / Instansi
                                <?= $sortBy === 'nama_pemilik' ? ($sortOrder === 'asc' ? '<i data-lucide="chevron-up" class="w-3 h-3 text-blue-600"></i>' : '<i data-lucide="chevron-down" class="w-3 h-3 text-blue-600"></i>') : '<i data-lucide="chevrons-up-down" class="w-3 h-3 opacity-30"></i>' ?>
                            </div>
                        </th>
                        <th class="px-4 py-4 w-24 cursor-pointer hover:text-blue-600 transition-colors" onclick="applySort('luas_m2')">
                            <div class="flex items-center gap-2">
                                Luas
                                <?= $sortBy === 'luas_m2' ? ($sortOrder === 'asc' ? '<i data-lucide="chevron-up" class="w-3 h-3 text-blue-600"></i>' : '<i data-lucide="chevron-down" class="w-3 h-3 text-blue-600"></i>') : '<i data-lucide="chevrons-up-down" class="w-3 h-3 opacity-30"></i>' ?>
                            </div>
                        </th>
                        <th class="px-4 py-4 w-32">Kecamatan</th>
                        <th class="px-4 py-4 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800 text-[10px]">
                    <?php if (!empty($aset)): ?>
                        <?php foreach($aset as $item): ?>
                        <tr class="group hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-all duration-200">
                            <td class="px-4 py-3">
                                <span class="font-black text-blue-600 dark:text-blue-400 uppercase tracking-tight"><?= $item['no_sertifikat'] ?></span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col gap-0.5">
                                    <span class="font-black text-blue-950 dark:text-white uppercase truncate" title="<?= $item['nama_pemilik'] ?>"><?= $item['nama_pemilik'] ?></span>
                                    <span class="text-[8px] text-slate-400 font-bold uppercase truncate" title="<?= $item['lokasi'] ?>"><?= $item['lokasi'] ?></span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-1">
                                    <span class="font-black text-slate-700 dark:text-slate-300"><?= number_format($item['luas_m2'], 0, ',', '.') ?></span>
                                    <span class="text-[7px] font-black text-slate-400">M²</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="font-bold text-slate-500 dark:text-slate-400 uppercase tracking-tight truncate block"><?= $item['kecamatan'] ?></span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-1.5">
                                    <?php if($item['koordinat']): ?>
                                    <button onclick="focusMap(<?= $item['koordinat'] ?>)" class="p-2 bg-white dark:bg-slate-800 text-blue-600 rounded-lg shadow-sm border border-slate-100 dark:border-slate-700 hover:bg-blue-600 hover:text-white transition-all" title="Fokus Peta">
                                        <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                                    </button>
                                    <?php endif; ?>
                                    <a href="<?= base_url('aset-tanah/detail/'.$item['id']) ?>" class="p-2 bg-blue-950 text-white rounded-lg shadow-md hover:bg-black transition-all" title="Detail">
                                        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                                    </a>
                                    <button onclick="confirmDelete(<?= $item['id'] ?>)" class="p-2 bg-rose-50 text-rose-600 rounded-lg border border-rose-100 hover:bg-rose-600 hover:text-white transition-all" title="Hapus">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="px-8 py-12 text-center text-slate-400 font-bold uppercase text-[10px]">Data tidak ditemukan</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if (isset($pager)): ?>
        <div class="p-6 bg-slate-50/50 dark:bg-slate-800/50 flex justify-center border-t border-slate-100 dark:border-slate-800">
            <?= $pager->links('group1', 'tailwind_full') ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<form id="delete-form" action="" method="post" class="hidden">
    <?= csrf_field() ?>
</form>

<script>
    let map;
    let clusterGroup;
    let baseLayers = {};

    function initMap() {
        const isDark = document.documentElement.classList.contains('dark');
        
        // Base Layers
        const standard = L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; Sibaruki'
        });

        const satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: '&copy; Esri'
        });

        map = L.map('map', { 
            zoomControl: false,
            layers: [standard]
        }).setView([-5.1245, 120.2536], 13);
        
        L.control.zoom({ position: 'topright' }).addTo(map);

        // Layer Toggle Control
        const layerToggleBtn = document.createElement('button');
        layerToggleBtn.innerHTML = '<i data-lucide="layers" class="w-4 h-4"></i>';
        layerToggleBtn.className = 'bg-white dark:bg-slate-900 p-3 rounded-xl shadow-xl border border-slate-100 dark:border-slate-800 text-blue-600 hover:bg-blue-50 transition-all m-2';
        
        layerToggleBtn.onclick = function() {
            if (map.hasLayer(standard)) {
                map.removeLayer(standard);
                map.addLayer(satellite);
                this.classList.add('bg-blue-600', 'text-white');
                this.classList.remove('text-blue-600');
            } else {
                map.removeLayer(satellite);
                map.addLayer(standard);
                this.classList.remove('bg-blue-600', 'text-white');
                this.classList.add('text-blue-600');
            }
        };

        const customControl = L.Control.extend({
            options: { position: 'topright' },
            onAdd: function() { return layerToggleBtn; }
        });
        map.addControl(new customControl());

        clusterGroup = L.markerClusterGroup({
            showCoverageOnHover: false,
            maxClusterRadius: 50,
            polygonOptions: {
                fillColor: '#1e1b4b',
                color: '#1e1b4b',
                weight: 2,
                opacity: 1,
                fillOpacity: 0.1
            }
        });

        const asetData = <?= json_encode($aset_all ?? []) ?>;
        
        asetData.forEach(item => {
            if (item.koordinat) {
                const coords = item.koordinat.split(',').map(c => parseFloat(c.trim()));
                if (coords.length === 2 && !isNaN(coords[0]) && !isNaN(coords[1])) {
                    const marker = L.circleMarker(coords, {
                        radius: 8,
                        fillColor: "#1e1b4b",
                        color: "#fff",
                        weight: 2,
                        opacity: 1,
                        fillOpacity: 0.8
                    });

                    marker.bindPopup(`
                        <div class="bg-blue-950 text-white p-4 rounded-t-xl border-b border-white/10">
                            <p class="text-[8px] font-black uppercase tracking-[0.2em] text-blue-400 mb-1">Aset Tanah Pemda</p>
                            <h5 class="text-xs font-black uppercase leading-tight">${item.nama_pemilik}</h5>
                        </div>
                        <div class="p-4 bg-white dark:bg-slate-900 space-y-3 rounded-b-xl">
                            <div class="flex justify-between items-center">
                                <span class="text-[9px] font-bold text-slate-400 uppercase">Sertifikat</span>
                                <span class="text-[10px] font-black text-blue-600 uppercase">${item.no_sertifikat}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[9px] font-bold text-slate-400 uppercase">Luas</span>
                                <span class="text-[10px] font-black text-slate-700 dark:text-slate-300 uppercase">${new Intl.NumberFormat('id-ID').format(item.luas_m2)} M²</span>
                            </div>
                            <a href="<?= base_url('aset-tanah/detail/') ?>/${item.id}" class="block w-full py-2 bg-blue-950 hover:bg-black text-white text-center text-[9px] font-black uppercase tracking-widest rounded-xl transition-all mt-2">Detail Aset</a>
                        </div>
                    `);
                    
                    clusterGroup.addLayer(marker);
                }
            }
        });

        map.addLayer(clusterGroup);
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function focusMap(lat, lng) {
        map.setView([lat, lng], 18);
        const mainContent = document.getElementById('main-content');
        if (mainContent) mainContent.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function confirmDelete(id) {
        customConfirm('Hapus Aset?', 'Apakah Anda yakin ingin menghapus data aset tanah ini? Data akan dipindahkan ke Recycle Bin.', 'danger').then((confirmed) => {
            if (confirmed) {
                const form = document.getElementById('delete-form');
                form.action = `<?= base_url('aset-tanah/delete') ?>/${id}`;
                form.submit();
            }
        });
    }

    // --- SORTING & SCROLL PRESERVATION SYSTEM ---
    function submitWithScroll(el) {
        const mainContent = document.getElementById('main-content');
        if (mainContent) {
            localStorage.setItem('asetTanahScrollPos', mainContent.scrollTop);
        }
        const form = el.tagName === 'FORM' ? el : el.form;
        if (form) form.submit();
    }

    function applySort(column) {
        const form = document.getElementById('filter-form');
        const sortByInput = form.querySelector('input[name="sort_by"]');
        const sortOrderInput = form.querySelector('input[name="sort_order"]');

        if (sortByInput.value === column) {
            sortOrderInput.value = sortOrderInput.value === 'asc' ? 'desc' : 'asc';
        } else {
            sortByInput.value = column;
            sortOrderInput.value = 'asc';
        }

        submitWithScroll(form);
    }

    document.addEventListener('DOMContentLoaded', () => {
        const mainContent = document.getElementById('main-content');
        if (mainContent) {
            const scrollPos = localStorage.getItem('asetTanahScrollPos');
            if (scrollPos) {
                setTimeout(() => {
                    mainContent.scrollTop = scrollPos;
                    localStorage.removeItem('asetTanahScrollPos');
                }, 50);
            }

            const searchInput = document.querySelector('input[name="search"]');
            if (searchInput) {
                searchInput.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') {
                        localStorage.setItem('asetTanahScrollPos', mainContent.scrollTop);
                    }
                });
            }

            // Simpan scroll saat klik pagination
            const paginationLinks = document.querySelectorAll('nav a');
            paginationLinks.forEach(link => {
                link.addEventListener('click', () => {
                    localStorage.setItem('asetTanahScrollPos', mainContent.scrollTop);
                });
            });
        }
    });

    window.addEventListener('load', initMap);
</script>

<style>
    .leaflet-popup-content-wrapper { border-radius: 1.5rem; padding: 0; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3); border: none; }
    .leaflet-popup-content { margin: 0; width: 240px !important; }
    .leaflet-container { font-family: inherit; }
    
    /* Marker Cluster Custom Style */
    .marker-cluster-small { background-color: rgba(30, 27, 75, 0.6); }
    .marker-cluster-small div { background-color: rgba(30, 27, 75, 0.9); color: white; font-weight: 900; }
    .marker-cluster-medium { background-color: rgba(30, 27, 75, 0.6); }
    .marker-cluster-medium div { background-color: rgba(30, 27, 75, 0.9); color: white; font-weight: 900; }
    .marker-cluster-large { background-color: rgba(30, 27, 75, 0.6); }
    .marker-cluster-large div { background-color: rgba(30, 27, 75, 0.9); color: white; font-weight: 900; }
</style>
<?= $this->endSection() ?>
