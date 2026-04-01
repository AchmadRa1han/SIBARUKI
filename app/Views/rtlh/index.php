<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<!-- Leaflet Assets -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>

<div class="space-y-8 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl md:text-4xl font-black text-blue-950 dark:text-white uppercase tracking-tighter">Data RTLH</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium text-sm">Monitoring Rumah Tidak Layak Huni Kabupaten Sinjai.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <div class="bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 px-4 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-sm border border-blue-100 dark:border-blue-800/50">
                <?= count($rumah_all ?? []) ?> Unit Terverifikasi
            </div>
            <a href="<?= base_url('rtlh/export-excel') ?>" class="bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 px-5 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-sm border border-emerald-100 dark:border-emerald-800/50 hover:bg-emerald-600 hover:text-white transition-all active:scale-95 flex items-center gap-2">
                <i data-lucide="download" class="w-3.5 h-3.5"></i> Export Excel
            </a>
            <?php if (has_permission('create_rtlh')): ?>
            <a href="<?= base_url('rtlh/create') ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-blue-600/20 transition-all active:scale-95 flex items-center gap-2 group">
                <i data-lucide="plus" class="w-4 h-4 group-hover:rotate-90 transition-transform"></i> Tambah Data
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Map Section -->
    <div class="relative">
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] overflow-hidden shadow-2xl border border-slate-100 dark:border-slate-800">
            <div id="map" class="w-full h-[500px] z-10" style="background: #ececec;"></div>
            <div class="absolute top-8 left-8 z-[1000] hidden md:block">
                <div class="bg-blue-950/80 backdrop-blur-md text-white px-5 py-3 rounded-[1.5rem] text-[10px] font-black uppercase tracking-[0.2em] shadow-2xl border border-white/10 flex items-center gap-3">
                    <div class="w-2 h-2 bg-blue-400 rounded-full animate-ping"></div>
                    Database Geospasial RTLH
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Card -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 p-4">
        <div class="flex flex-col lg:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-4 w-full lg:w-auto">
                <div class="flex bg-slate-100 dark:bg-slate-800 p-1.5 rounded-xl w-full md:w-auto">
                    <a href="<?= base_url('rtlh?status=Belum Menerima&keyword='.$keyword) ?>" class="flex-1 md:flex-none px-5 py-2.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all <?= $status == 'Belum Menerima' ? 'bg-white dark:bg-slate-700 text-blue-600 shadow-sm' : 'text-slate-400 hover:text-slate-600' ?>">Target RTLH</a>
                    <a href="<?= base_url('rtlh?status=Sudah Menerima&keyword='.$keyword) ?>" class="flex-1 md:flex-none px-5 py-2.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all <?= $status == 'Sudah Menerima' ? 'bg-white dark:bg-slate-700 text-emerald-600 shadow-sm' : 'text-slate-400 hover:text-slate-600' ?>">Tuntas RLH</a>
                    <a href="<?= base_url('rtlh?status=semua&keyword='.$keyword) ?>" class="flex-1 md:flex-none px-5 py-2.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all <?= $status == 'semua' ? 'bg-white dark:bg-slate-700 text-slate-600 shadow-sm' : 'text-slate-400 hover:text-slate-600' ?>">Semua</a>
                </div>
            </div>

            <form action="<?= base_url('rtlh') ?>" method="get" class="flex flex-col md:flex-row items-center gap-3 w-full lg:w-auto" id="filter-form">
                <input type="hidden" name="status" value="<?= $status ?>">
                <div class="relative w-full md:w-32">
                    <select name="per_page" onchange="submitWithScroll(this)" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-[10px] font-black uppercase tracking-widest px-4 py-3 focus:ring-2 focus:ring-blue-500 cursor-pointer appearance-none">
                        <?php foreach([5, 10, 25, 50, 100] as $p): ?>
                            <option value="<?= $p ?>" <?= ($perPage ?? 10) == $p ? 'selected' : '' ?>><?= $p ?> Baris</option>
                        <?php endforeach; ?>
                    </select>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                </div>
                <div class="relative w-full md:w-72">
                    <input type="text" name="keyword" value="<?= $keyword ?? '' ?>" placeholder="Cari Nama / Desa..." class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-[10px] font-black uppercase tracking-widest px-4 py-3 pl-11 focus:ring-2 focus:ring-blue-500 transition-all">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2"></i>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden relative">
        <!-- Floating Bulk Action Bar -->
        <div id="bulk-action-bar" class="absolute top-0 left-0 right-0 z-50 bg-blue-950 text-white p-5 transform -translate-y-full transition-transform duration-500 flex items-center justify-between px-10">
            <div class="flex items-center gap-5">
                <span id="selected-count" class="bg-blue-600 px-4 py-1.5 rounded-full text-[10px] font-black tracking-widest shadow-lg shadow-blue-600/20">0 TERPILIH</span>
                <p class="text-[10px] font-bold uppercase tracking-widest opacity-70 hidden md:block">Aksi massal untuk data yang dipilih</p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="handleBulkDelete()" class="px-6 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all active:scale-95 flex items-center gap-2 shadow-lg shadow-rose-600/20">
                    <i data-lucide="trash-2" class="w-4 h-4"></i> Hapus Terpilih
                </button>
                <button onclick="clearSelection()" class="px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all active:scale-95">Batal</button>
            </div>
        </div>

        <div class="p-8 border-b border-slate-50 dark:border-slate-800">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-blue-600/20">
                    <i data-lucide="database" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="text-lg font-black text-blue-950 dark:text-white uppercase tracking-tight">Daftar Penerima RTLH</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em]">Informasi Terpadu Kepemilikan & Kondisi</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse table-fixed">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/50 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                        <th class="px-8 py-5 w-20 text-center">
                            <input type="checkbox" id="select-all" class="w-5 h-5 rounded-lg border-2 border-slate-200 text-blue-600 focus:ring-blue-600/20 cursor-pointer transition-all">
                        </th>
                        <th class="px-4 py-5 w-64">Kepala Keluarga</th>
                        <th class="px-4 py-5 w-48">Wilayah (Desa/Kelurahan)</th>
                        <th class="px-4 py-5 w-36 text-center">Status Hunian</th>
                        <th class="px-8 py-5 text-center w-40">Aksi Pengelola</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800 text-[11px]">
                    <?php if (!empty($rumah)): foreach($rumah as $item): ?>
                    <tr class="group hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-all duration-300">
                        <td class="px-8 py-4 text-center">
                            <input type="checkbox" name="rtlh_ids[]" value="<?= $item['id_survei'] ?>" class="row-checkbox w-5 h-5 rounded-lg border-2 border-slate-200 text-blue-600 focus:ring-blue-600/20 cursor-pointer transition-all">
                        </td>
                        <td class="px-4 py-4">
                            <span class="font-black text-blue-950 dark:text-white uppercase truncate block text-sm mb-0.5"><?= $item['pemilik'] ?? '-' ?></span>
                            <?php if($item['status_bantuan'] == 'Sudah Menerima'): ?>
                                <span class="text-[9px] font-bold text-emerald-500 uppercase tracking-widest">Tuntas Bansos <?= $item['tahun_bansos'] ?></span>
                            <?php else: ?>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Belum Tersentuh Bantuan</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-4"><span class="font-black text-blue-600 dark:text-blue-400 uppercase tracking-tight"><?= $item['desa'] ?></span></td>
                        <td class="px-4 py-4 text-center">
                            <?php if($item['status_bantuan'] == 'Sudah Menerima'): ?>
                                <span class="px-3 py-1 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 rounded-full font-black uppercase text-[9px] border border-emerald-100 dark:border-emerald-900 tracking-wider">TUNTAS (RLH)</span>
                            <?php else: ?>
                                <span class="px-3 py-1 bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 rounded-full font-black uppercase text-[9px] border border-amber-100 dark:border-amber-900 tracking-wider">TARGET (RTLH)</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-8 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <?php if(!empty($item['lokasi_koordinat'])): ?>
                                <button onclick="focusMap(<?= $item['lokasi_koordinat'] ?>)" class="p-2.5 bg-white dark:bg-slate-800 text-blue-600 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 hover:bg-blue-600 hover:text-white transition-all active:scale-95" title="Lihat di Peta"><i data-lucide="map-pin" class="w-4 h-4"></i></button>
                                <?php endif; ?>
                                <a href="<?= base_url('rtlh/detail/'.$item['id_survei']) ?>" class="p-2.5 bg-blue-950 dark:bg-blue-600 text-white rounded-xl shadow-md hover:scale-110 transition-all active:scale-95" title="Detail Data"><i data-lucide="eye" class="w-4 h-4"></i></a>
                                <?php if (has_permission('delete_rtlh')): ?>
                                <button onclick="confirmDelete(<?= $item['id_survei'] ?>, '<?= addslashes($item['pemilik'] ?? '') ?>')" class="p-2.5 bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 rounded-xl hover:bg-rose-600 hover:text-white transition-all active:scale-95" title="Hapus Data"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center justify-center opacity-40">
                                    <i data-lucide="package-search" class="w-16 h-16 mb-4"></i>
                                    <p class="font-black uppercase text-[10px] tracking-[0.3em]">Data Tidak Ditemukan</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if (!empty($pager)): ?>
        <div class="p-8 bg-slate-50/50 dark:bg-slate-800/50 flex justify-center border-t border-slate-100 dark:border-slate-800">
            <?= $pager->links('default', 'tailwind_full') ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<form id="delete-form" action="" method="post" class="hidden"><?= csrf_field() ?></form>

<script>
    let map;
    let rot = 0;

    function initMap() {
        if (typeof L === 'undefined') { setTimeout(initMap, 100); return; }
        try {
            const isDark = document.documentElement.classList.contains('dark');
            const standard = L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { attribution: '&copy; Sibaruki' });
            const satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: '&copy; Esri' });

            map = L.map('map', { zoomControl: false, layers: [standard] }).setView([-5.1245, 120.2536], 13);
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

            const clusterGroup = L.markerClusterGroup({ showCoverageOnHover: false, maxClusterRadius: 50 });
            const rtlhData = <?= json_encode($rumah_all ?? []) ?>;
            rtlhData.forEach(item => {
                if (item.lokasi_koordinat) {
                    const coords = item.lokasi_koordinat.split(',').map(c => parseFloat(c.trim()));
                    if (coords.length === 2 && !isNaN(coords[0]) && !isNaN(coords[1])) {
                        const marker = L.circleMarker(coords, { radius: 8, fillColor: "#1e1b4b", color: "#fff", weight: 2, fillOpacity: 0.8 });
                        marker.bindPopup(`
                            <div class="bg-blue-950 text-white p-4 rounded-t-xl"><p class="text-[8px] font-black uppercase tracking-[0.2em] text-blue-400 mb-1">Data RTLH</p><h5 class="text-xs font-black uppercase leading-tight">${item.pemilik || '-'}</h5></div>
                            <div class="p-4 bg-white dark:bg-slate-900 space-y-3 rounded-b-xl"><p class="text-[10px] font-bold text-slate-700">📍 ${item.desa}</p><a href="<?= base_url('rtlh/detail/') ?>/${item.id_survei}" class="block w-full py-2 bg-blue-950 text-white text-center text-[9px] font-black uppercase tracking-widest rounded-xl transition-all">Detail</a></div>
                        `);
                        clusterGroup.addLayer(marker);
                    }
                }
            });
            map.addLayer(clusterGroup);
            if (typeof lucide !== 'undefined') lucide.createIcons();
        } catch(err) {}
    }

    function focusMap(lat, lng) {
        map.setView([lat, lng], 18);
        const mc = document.getElementById('main-content');
        if (mc) mc.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function confirmDelete(id, name) {
        customConfirm('Hapus RTLH?', `Hapus data milik ${name}?`, 'danger').then(conf => {
            if (conf) { const f = document.getElementById('delete-form'); f.action = `<?= base_url('rtlh/delete') ?>/${id}`; f.submit(); }
        });
    }

    function submitWithScroll(el) {
        const mc = document.getElementById('main-content');
        if (mc) localStorage.setItem('rtlhScrollPos', mc.scrollTop);
        const form = el.tagName === 'FORM' ? el : el.form;
        if (form) form.submit();
    }

    // --- BULK DELETE LOGIC ---
    const selectAll = document.getElementById('select-all');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    const bulkBar = document.getElementById('bulk-action-bar');
    const selectedCount = document.getElementById('selected-count');

    function updateBulkBar() {
        const checked = document.querySelectorAll('.row-checkbox:checked');
        if (checked.length > 0) {
            bulkBar.classList.remove('-translate-y-full');
            selectedCount.innerText = `${checked.length} TERPILIH`;
        } else {
            bulkBar.classList.add('-translate-y-full');
        }
    }

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            rowCheckboxes.forEach(cb => {
                cb.checked = this.checked;
                const row = cb.closest('tr');
                if (this.checked) row.classList.add('bg-blue-50/50', 'dark:bg-blue-900/10');
                else row.classList.remove('bg-blue-50/50', 'dark:bg-blue-900/10');
            });
            updateBulkBar();
        });
    }

    rowCheckboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            const row = this.closest('tr');
            if (this.checked) row.classList.add('bg-blue-50/50', 'dark:bg-blue-900/10');
            else row.classList.remove('bg-blue-50/50', 'dark:bg-blue-900/10');
            
            const allChecked = document.querySelectorAll('.row-checkbox:checked').length === rowCheckboxes.length;
            selectAll.checked = allChecked;
            updateBulkBar();
        });
    });

    function clearSelection() {
        selectAll.checked = false;
        rowCheckboxes.forEach(cb => {
            cb.checked = false;
            cb.closest('tr').classList.remove('bg-blue-50/50', 'dark:bg-blue-900/10');
        });
        updateBulkBar();
    }

    async function handleBulkDelete() {
        try {
            const checked = document.querySelectorAll('.row-checkbox:checked');
            const ids = Array.from(checked).map(cb => cb.value);
            
            if (ids.length === 0) return;

            const ok = await window.customConfirm('Hapus Massal?', `Apakah Anda yakin ingin menghapus ${ids.length} data RTLH yang dipilih? Tindakan ini tidak dapat dibatalkan.`, 'danger');
            
            if (ok) {
                const formData = new FormData();
                ids.forEach(id => formData.append('ids[]', id));
                formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

                const response = await fetch('<?= base_url('rtlh/bulk-delete') ?>', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const result = await response.json();
                
                if (result.status === 'success') {
                    showToast(result.message, 'success');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showToast(result.message, 'error');
                }
            }
        } catch (error) {
            console.error('Bulk Delete Error:', error);
            showToast('Terjadi kesalahan saat memproses hapus massal.', 'error');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const mc = document.getElementById('main-content');
        if (mc) {
            const sp = localStorage.getItem('rtlhScrollPos');
            if (sp) { setTimeout(() => { mc.scrollTop = sp; localStorage.removeItem('rtlhScrollPos'); }, 150); }
            document.querySelectorAll('nav a').forEach(link => link.addEventListener('click', () => localStorage.setItem('rtlhScrollPos', mc.scrollTop)));
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
