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
        <div class="flex flex-wrap items-center gap-2">
            <div id="debug-status" class="bg-blue-50 text-blue-600 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm border border-blue-100">
                Memuat Peta...
            </div>
            <?php if (has_permission('create_kumuh')): ?>
            <a href="<?= base_url('wilayah-kumuh/create') ?>" class="bg-blue-950 hover:bg-black text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-xl transition-all flex items-center gap-2 group">
                <i data-lucide="plus" class="w-3.5 h-3.5 group-hover:rotate-90 transition-transform"></i> Tambah Kawasan
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-900 p-6 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-800 flex items-center gap-4 group hover:border-rose-500 transition-all">
            <div class="w-12 h-12 bg-rose-50 dark:bg-rose-900/20 rounded-2xl flex items-center justify-center text-rose-600 group-hover:scale-110 transition-transform">
                <i data-lucide="alert-triangle" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Total Kawasan</p>
                <p class="text-lg font-black text-blue-950 dark:text-white"><?= is_array($kumuh_all) ? count($kumuh_all) : 0 ?> <span class="text-[10px] text-slate-400">Titik</span></p>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-800 flex items-center gap-4 group hover:border-blue-500 transition-all">
            <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/20 rounded-2xl flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                <i data-lucide="map" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Kecamatan</p>
                <p class="text-lg font-black text-blue-950 dark:text-white"><?= is_array($kumuh_all) ? count(array_unique(array_column($kumuh_all, 'Kecamatan'))) : 0 ?> <span class="text-[10px] text-slate-400">Wilayah</span></p>
            </div>
        </div>
    </div>

    <!-- Map Section -->
    <div class="relative group">
        <div class="absolute -inset-1 bg-gradient-to-r from-rose-600 to-orange-600 rounded-[2.5rem] blur opacity-10 transition duration-1000 group-hover:opacity-20"></div>
        <div class="relative bg-white dark:bg-slate-900 rounded-[2.5rem] overflow-hidden shadow-2xl border border-slate-100 dark:border-slate-800">
            <div id="map" class="w-full h-[60vh] z-10" style="min-height: 450px; background: #f8fafc;"></div>
            
            <!-- Floating Legend -->
            <div class="absolute bottom-6 left-6 z-[1000] bg-white/90 dark:bg-slate-950/90 backdrop-blur-md p-4 rounded-3xl shadow-2xl border border-white/20 dark:border-slate-800 w-52">
                <h4 class="text-[9px] font-black text-blue-950 dark:text-white uppercase tracking-[0.2em] mb-3 flex items-center gap-2">
                    <i data-lucide="layers" class="w-3 h-3 text-rose-600"></i> Kategori Kumuh
                </h4>
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-[#f43f5e] shadow-lg"></div>
                        <span class="text-[10px] font-bold text-slate-700 dark:text-slate-300 uppercase">Berat (> 60)</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-[#f97316] shadow-lg"></div>
                        <span class="text-[10px] font-bold text-slate-700 dark:text-slate-300 uppercase">Sedang (40-60)</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-[#f59e0b] shadow-lg"></div>
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

    <!-- Data Table Section -->
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
                    <?php if (!empty($kumuh)): ?>
                        <?php foreach ($kumuh as $item): ?>
                        <tr class="group hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-all duration-300">
                            <td class="px-8 py-5">
                                <div class="flex flex-col gap-1">
                                    <span class="text-xs font-black text-blue-950 dark:text-white uppercase tracking-tight"><?= $item['Kelurahan'] ?></span>
                                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-widest"><?= $item['Kecamatan'] ?></span>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <span class="text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-wider"><?= $item['Kawasan'] ?: '-' ?></span>
                            </td>
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
                            <td class="px-8 py-5">
                                <div class="flex items-center justify-center gap-2">
                                    <?php if(!empty($item['WKT'])): ?>
                                    <button onclick="focusMap('<?= $item['FID'] ?>')" class="p-2.5 bg-white dark:bg-slate-800 text-blue-600 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 hover:bg-rose-600 hover:text-white" title="Fokus Peta">
                                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                                    </button>
                                    <?php endif; ?>
                                    <a href="<?= base_url('wilayah-kumuh/detail/' . $item['FID']) ?>" class="p-2.5 bg-blue-950 text-white rounded-xl shadow-xl hover:bg-black" title="Lihat Detail">
                                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                    </a>
                                    <?php if (has_permission('delete_kumuh')): ?>
                                    <button onclick="confirmDelete('<?= $item['FID'] ?>', '<?= addslashes($item['Kelurahan']) ?>')" class="p-2.5 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white" title="Hapus Data">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="px-8 py-12 text-center">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Tidak ada data yang ditemukan.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php if (isset($pager)): ?>
        <div class="p-8 bg-slate-50/50 dark:bg-slate-800/50 flex justify-center">
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
    let polygonLayers = {};

    function initMap() {
        const debug = document.getElementById('debug-status');
        if (typeof wellknown === 'undefined' || typeof L === 'undefined') {
            setTimeout(initMap, 100);
            return;
        }

        try {
            const isDark = document.documentElement.classList.contains('dark');
            const tileUrl = isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png';
            
            map = L.map('map', { zoomControl: false }).setView([-5.1245, 120.2536], 14);
            
            L.tileLayer(tileUrl, { attribution: '&copy; Sibaruki' }).addTo(map);
            L.control.zoom({ position: 'topright' }).addTo(map);

            clusterGroup = L.markerClusterGroup({
                showCoverageOnHover: false,
                maxClusterRadius: 40,
                polygonOptions: {
                    fillColor: '#f43f5e',
                    color: '#f43f5e',
                    weight: 2,
                    opacity: 1,
                    fillOpacity: 0.1
                }
            });

            const kumuhData = <?= json_encode($kumuh_all ?? []) ?>;
            let count = 0;

            kumuhData.forEach(item => {
                if (item.WKT) {
                    try {
                        const geojson = wellknown.parse(item.WKT);
                        if (geojson) {
                            const color = item.skor_kumuh >= 60 ? '#f43f5e' : (item.skor_kumuh >= 40 ? '#f97316' : '#f59e0b');
                            
                            const layer = L.geoJSON(geojson, {
                                style: { color: color, weight: 2, fillOpacity: 0.5, fillColor: color }
                            });

                            layer.bindPopup(`
                                <div class="bg-blue-950 text-white p-4 rounded-t-xl border-b border-white/10">
                                    <p class="text-[8px] font-black uppercase tracking-[0.2em] text-rose-400 mb-1">Kawasan Kumuh</p>
                                    <h5 class="text-xs font-black uppercase leading-tight">${item.Kelurahan}</h5>
                                </div>
                                <div class="p-4 bg-white dark:bg-slate-900 space-y-3 rounded-b-xl">
                                    <div class="flex justify-between items-center">
                                        <span class="text-[9px] font-bold text-slate-400 uppercase">Kawasan</span>
                                        <span class="text-[10px] font-black text-slate-700 dark:text-slate-300 uppercase">${item.Kawasan || '-'}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-[9px] font-bold text-slate-400 uppercase">Skor</span>
                                        <span class="px-2 py-0.5 rounded-md bg-rose-50 dark:bg-rose-900/30 text-rose-600 text-[10px] font-black">${item.skor_kumuh}</span>
                                    </div>
                                    <a href="<?= base_url('wilayah-kumuh/detail/') ?>/${item.FID}" class="block w-full py-2 bg-blue-950 hover:bg-black text-white text-center text-[9px] font-black uppercase tracking-widest rounded-xl transition-all mt-2">Detail Kawasan</a>
                                </div>
                            `);

                            layer.on('mouseover', function() { this.setStyle({ fillOpacity: 0.8, weight: 3 }); });
                            layer.on('mouseout', function() { this.setStyle({ fillOpacity: 0.5, weight: 2 }); });

                            clusterGroup.addLayer(layer);
                            polygonLayers[item.FID] = layer;
                            count++;
                        }
                    } catch (e) { console.warn("Parse Error FID: " + item.FID); }
                }
            });

            map.addLayer(clusterGroup);
            debug.innerHTML = "✅ " + count + " Kawasan Terpetakan";
            debug.className = "bg-emerald-50 text-emerald-600 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm border border-emerald-100";
        } catch (err) {
            console.error(err);
            debug.innerHTML = "❌ Error Peta";
        }
    }

    function focusMap(fid) {
        if (polygonLayers[fid]) {
            const layer = polygonLayers[fid];
            const bounds = layer.getBounds();
            map.fitBounds(bounds, { padding: [50, 50], maxZoom: 18 });
            layer.openPopup();
            const mainContent = document.getElementById('main-content');
            if (mainContent) mainContent.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }

    function confirmDelete(id, name) {
        customConfirm('Hapus Kawasan?', `Apakah Anda yakin ingin menghapus data wilayah kumuh di ${name}? Data akan dipindahkan ke Recycle Bin.`, 'danger').then((confirmed) => {
            if (confirmed) {
                const form = document.getElementById('delete-form');
                form.action = `<?= base_url('wilayah-kumuh/delete') ?>/${id}`;
                form.submit();
            }
        });
    }

    // --- SCROLL PRESERVATION SYSTEM ---
    function submitWithScroll(el) {
        const mainContent = document.getElementById('main-content');
        if (mainContent) {
            localStorage.setItem('wilayahKumuhScrollPos', mainContent.scrollTop);
        }
        el.form.submit();
    }

    document.addEventListener('DOMContentLoaded', () => {
        const mainContent = document.getElementById('main-content');
        if (mainContent) {
            const scrollPos = localStorage.getItem('wilayahKumuhScrollPos');
            if (scrollPos) {
                setTimeout(() => {
                    mainContent.scrollTop = scrollPos;
                    localStorage.removeItem('wilayahKumuhScrollPos');
                }, 50);
            }

            const searchInput = document.querySelector('input[name="keyword"]');
            if (searchInput) {
                searchInput.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') {
                        localStorage.setItem('wilayahKumuhScrollPos', mainContent.scrollTop);
                    }
                });
            }

            // Simpan scroll saat klik pagination
            const paginationLinks = document.querySelectorAll('nav a');
            paginationLinks.forEach(link => {
                link.addEventListener('click', () => {
                    localStorage.setItem('wilayahKumuhScrollPos', mainContent.scrollTop);
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
    
    /* Marker Cluster Custom Style - Rose Theme */
    .marker-cluster-small { background-color: rgba(244, 63, 94, 0.4); }
    .marker-cluster-small div { background-color: rgba(244, 63, 94, 0.8); color: white; font-weight: 900; }
    .marker-cluster-medium { background-color: rgba(244, 63, 94, 0.4); }
    .marker-cluster-medium div { background-color: rgba(244, 63, 94, 0.8); color: white; font-weight: 900; }
    .marker-cluster-large { background-color: rgba(244, 63, 94, 0.4); }
    .marker-cluster-large div { background-color: rgba(244, 63, 94, 0.8); color: white; font-weight: 900; }
</style>
<?= $this->endSection() ?>
