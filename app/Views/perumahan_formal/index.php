<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<!-- Leaflet Assets -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>

<div class="max-w-7xl mx-auto space-y-6 pb-24 text-slate-900 dark:text-slate-200">
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-3 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 no-print">
        <a href="<?= base_url('dashboard') ?>" class="hover:text-blue-600 transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">Perumahan Formal</span>
    </nav>

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-blue-950 p-7 rounded-[2.5rem] text-white shadow-2xl shadow-blue-950/20 relative overflow-hidden transition-all duration-500">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
        <div class="relative z-10 flex items-center gap-5">
            <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-md border border-white/10 shadow-inner">
                <i data-lucide="home" class="w-6 h-6 text-blue-400"></i>
            </div>
            <div>
                <h1 class="text-2xl md:text-3xl font-black uppercase tracking-tighter leading-none">Perumahan Formal</h1>
                <p class="text-white/60 font-medium text-xs mt-2 tracking-wide">Pemetaan & Database Sebaran Perumahan Kabupaten Sinjai</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-3 relative z-10">
            <div class="bg-white/10 text-white px-3 py-1.5 rounded-xl text-[9px] font-bold uppercase tracking-widest border border-white/10 backdrop-blur-md shadow-sm">
                <?= number_format($total_perumahan ?? 0) ?> Lokasi
            </div>
            <a href="<?= base_url('perumahan-formal/export-excel') ?>" class="bg-emerald-600 text-white px-4 py-2 rounded-xl text-[9px] font-bold uppercase tracking-widest shadow-xl shadow-emerald-600/20 hover:bg-emerald-700 transition-all active:scale-95 flex items-center gap-2">
                <i data-lucide="file-spreadsheet" class="w-4 h-4"></i> Export Excel
            </a>
            <?php if (has_permission('create_rtlh')): ?>
            <button onclick="UI.openModal('modal-import')" class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest shadow-xl hover:scale-105 active:scale-95 transition-all flex items-center gap-2 group">
                <i data-lucide="upload-cloud" class="w-4 h-4 transition-transform group-hover:-translate-y-0.5"></i> Import
            </button>
            <button type="button" onclick="perumahanModal.openAdd()" class="bg-white text-blue-950 px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest shadow-xl hover:scale-105 active:scale-95 transition-all flex items-center gap-2 group">
                <i data-lucide="plus" class="w-4 h-4 transition-transform group-hover:rotate-90"></i> Tambah
            </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Map Section -->
    <div class="relative">
        <div class="bg-white dark:bg-slate-900 rounded-2xl overflow-hidden shadow-md border border-slate-100 dark:border-slate-800">
            <div id="map" class="w-full h-[450px] z-10" style="background: #ececec;"></div>
            <div class="absolute top-6 left-6 z-[1000] hidden md:block">
                <div class="bg-blue-950/80 backdrop-blur-md text-white px-4 py-2 rounded-xl text-[9px] font-bold uppercase tracking-[0.2em] shadow-2xl border border-white/10 flex items-center gap-3">
                    <div class="w-1.5 h-1.5 bg-blue-400 rounded-full animate-ping"></div>
                    Database Geospasial Perumahan
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Card -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-100 dark:border-slate-800 p-3">
        <div class="flex flex-col lg:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-3 w-full lg:w-auto">
                <div class="flex bg-slate-100 dark:bg-slate-800 p-1 rounded-xl w-full md:w-auto">
                    <button class="px-4 py-2 bg-white dark:bg-slate-700 text-blue-600 rounded-lg text-[9px] font-bold uppercase tracking-widest shadow-sm">Daftar Perumahan</button>
                </div>
            </div>

            <form action="<?= base_url('perumahan-formal') ?>" method="get" class="flex flex-col md:flex-row items-center gap-2 w-full lg:w-auto" id="filter-form">
                <div class="relative w-full md:w-28">
                    <select name="per_page" onchange="submitWithScroll(this)" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-[9px] font-bold uppercase px-3 py-2 focus:ring-2 focus:ring-blue-500 cursor-pointer appearance-none">
                        <?php foreach([5, 10, 25, 50, 100] as $p): ?>
                            <option value="<?= $p ?>" <?= ($perPage ?? 10) == $p ? 'selected' : '' ?>><?= $p ?> Baris</option>
                        <?php endforeach; ?>
                    </select>
                    <i data-lucide="chevron-down" class="w-3 h-3 text-slate-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                </div>

                <div class="relative w-full md:w-64">
                    <input type="text" name="keyword" value="<?= $keyword ?? '' ?>" placeholder="Cari nama/pengembang..." class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-[9px] font-bold uppercase px-3 py-2 pl-10 focus:ring-2 focus:ring-blue-500 transition-all">
                    <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden relative">
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
                    <h3 class="text-sm font-bold text-blue-950 dark:text-white uppercase tracking-tight">Data Induk Perumahan</h3>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-[0.2em]">Monitoring Sektor Perumahan</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse table-fixed">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/50 text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                        <th class="px-6 py-4 w-16 text-center">
                            <input type="checkbox" id="select-all" class="w-4.5 h-4.5 rounded-lg border-2 border-slate-200 text-blue-600 focus:ring-blue-600/20 cursor-pointer transition-all">
                        </th>
                        <th class="px-4 py-4 w-64">Nama Perumahan</th>
                        <th class="px-4 py-4 w-48">Pengembang</th>
                        <th class="px-4 py-4 w-32 text-center">Tahun</th>
                        <th class="px-4 py-4 w-32 text-center">Luas (Ha)</th>
                        <th class="px-6 py-4 text-center w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800 text-[10px]">
                    <?php if (!empty($perumahan)): foreach($perumahan as $item): ?>
                    <tr class="group hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-all duration-200">
                        <td class="px-6 py-3 text-center">
                            <input type="checkbox" name="ids[]" value="<?= $item['id'] ?>" class="row-checkbox w-4.5 h-4.5 rounded-lg border-2 border-slate-200 text-blue-600 focus:ring-blue-600/20 cursor-pointer transition-all">
                        </td>
                        <td class="px-4 py-3">
                            <span class="font-bold text-blue-950 dark:text-white uppercase truncate block text-xs mb-0.5"><?= $item['nama_perumahan'] ?></span>
                            <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Kawasan Permukiman</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="font-bold text-slate-700 dark:text-slate-200 uppercase tracking-tight"><?= $item['pengembang'] ?></span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2.5 py-0.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg font-bold text-[9px]">
                                <?= $item['tahun_pembangunan'] ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="font-bold text-slate-700 dark:text-slate-300"><?= number_format($item['luas_kawasan_ha'], 2) ?> Ha</span>
                        </td>
                        <td class="px-6 py-3 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <?php if(!empty($item['latitude'])): ?>
                                <button onclick="focusMap(<?= $item['latitude'] ?>, <?= $item['longitude'] ?>)" class="p-2 bg-white dark:bg-slate-800 text-blue-600 rounded-lg shadow-sm border border-slate-100 dark:border-slate-700 hover:bg-blue-600 hover:text-white transition-all active:scale-95" title="Peta"><i data-lucide="map-pin" class="w-3.5 h-3.5"></i></button>
                                <?php endif; ?>
                                <a href="<?= base_url('perumahan-formal/detail/'.$item['id']) ?>" class="p-2 bg-blue-950 dark:bg-blue-600 text-white rounded-lg shadow-md hover:scale-110 transition-all active:scale-95" title="Detail"><i data-lucide="eye" class="w-3.5 h-3.5"></i></a>
                                <?php if (has_permission('edit_rtlh')): ?>
                                <button onclick="perumahanModal.openEdit(<?= htmlspecialchars(json_encode($item), ENT_QUOTES, 'UTF-8') ?>)" class="p-2 bg-white dark:bg-slate-800 text-slate-500 rounded-lg shadow-sm border border-slate-100 dark:border-slate-700 hover:text-blue-600 transition-all active:scale-95" title="Edit"><i data-lucide="edit-3" class="w-3.5 h-3.5"></i></button>
                                <?php endif; ?>
                                <?php if (has_permission('delete_rtlh')): ?>
                                <button onclick="confirmDelete(<?= $item['id'] ?>, '<?= addslashes($item['nama_perumahan']) ?>')" class="p-2 bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 rounded-lg hover:bg-rose-600 hover:text-white transition-all active:scale-95" title="Hapus"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
                                <?php endif; ?>
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
        <?php if (!empty($pager)): ?>
        <div class="p-6 bg-slate-50/50 dark:bg-slate-800/50 flex justify-center border-t border-slate-100 dark:border-slate-800">
            <?= $pager->links('default', 'tailwind_full') ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<?= view('perumahan_formal/partials/_modal_edit') ?>
<?= view('perumahan_formal/partials/_modal_import') ?>

<form id="delete-form" action="" method="post" class="hidden"><?= csrf_field() ?></form>

<script>
    let map;
    let rot = 0;

    function initMap() {
        if (typeof L === 'undefined') { setTimeout(initMap, 100); return; }
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

            map = L.map('map', { 
                zoomControl: false, 
                layers: [googleSat] 
            }).setView([-5.1245, 120.2536], 13);

            L.control.zoom({ position: 'topright' }).addTo(map);

            let rot = 0;
            const LayerToggle = L.Control.extend({
                onAdd: function(map) {
                    const btn = L.DomUtil.create('button', 'rounded-lg shadow-xl border transition-all duration-300 active:scale-90 mt-2 flex items-center justify-center');
                    btn.type = 'button';
                    btn.style.width = '38px'; btn.style.height = '38px'; btn.style.cursor = 'pointer';
                    btn.style.backgroundColor = '#2563eb';
                    const isDark = document.documentElement.classList.contains('dark');
                    const standardSvgColor = isDark ? '#60a5fa' : '#2563eb';
                    btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:block; transition: transform 0.8s cubic-bezier(0.65, 0, 0.35, 1);"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>`;
                    L.DomEvent.disableClickPropagation(btn);
                    L.DomEvent.on(btn, 'click', function(e) {
                        L.DomEvent.stopPropagation(e);
                        L.DomEvent.preventDefault(e);
                        rot += 360;
                        const svg = btn.querySelector('svg');
                        svg.style.transform = `rotate(${rot}deg)`;
                        setTimeout(() => {
                            if (map.hasLayer(googleSat)) { 
                                map.removeLayer(googleSat); 
                                map.addLayer(cartoDB); 
                                btn.style.backgroundColor = isDark ? '#0f172a' : '#ffffff'; 
                                svg.setAttribute('stroke', standardSvgColor); 
                            }
                            else { 
                                map.removeLayer(cartoDB); 
                                map.addLayer(googleSat); 
                                btn.style.backgroundColor = '#2563eb'; 
                                svg.setAttribute('stroke', '#ffffff'); 
                            }
                        }, 200);
                    });
                    return btn;
                }
            });
            map.addControl(new LayerToggle({ position: 'topright' }));
            const clusterGroup = L.markerClusterGroup({ showCoverageOnHover: false, maxClusterRadius: 50 });
            const perumahanData = <?= json_encode($perumahan_all ?? []) ?>;
            perumahanData.forEach(item => {
                if (item.latitude && item.longitude) {
                    const coords = [parseFloat(item.latitude), parseFloat(item.longitude)];
                    const marker = L.circleMarker(coords, { radius: 8, fillColor: "#1e1b4b", color: "#fff", weight: 2, fillOpacity: 0.8 });
                    marker.bindPopup(`
                        <div class="bg-blue-950 text-white p-3 rounded-t-xl"><p class="text-[7px] font-bold uppercase tracking-widest text-blue-400 mb-1">Perumahan</p><h5 class="text-[11px] font-bold uppercase leading-tight">${item.nama_perumahan}</h5></div>
                        <div class="p-3 bg-white dark:bg-slate-900 space-y-2 rounded-b-xl"><p class="text-[9px] font-bold text-slate-700 dark:text-slate-300">🏢 ${item.pengembang}</p><a href="<?= base_url('perumahan-formal/detail/') ?>/${item.id}" class="block w-full py-2.5 bg-blue-950 hover:bg-blue-800 text-white text-center text-[10px] font-black uppercase tracking-[0.2em] rounded-xl shadow-xl transition-all">Detail</a></div>
                    `);
                    clusterGroup.addLayer(marker);
                }
            });
            map.addLayer(clusterGroup);
            if (perumahanData.length > 0) map.fitBounds(clusterGroup.getBounds().pad(0.1));
            if (typeof lucide !== 'undefined') lucide.createIcons();
            setTimeout(() => { map.invalidateSize(); }, 500);
        } catch(err) { console.error(err); }
    }

    function focusMap(lat, lng) {
        map.setView([lat, lng], 18);
        const mc = document.getElementById('main-content');
        if (mc) mc.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function confirmDelete(id, name) {
        customConfirm('Hapus Perumahan?', `Hapus data perumahan ${name}?`, 'danger').then(conf => {
            if (conf) { const f = document.getElementById('delete-form'); f.action = `<?= base_url('perumahan-formal/delete') ?>/${id}`; f.submit(); }
        });
    }

    function submitWithScroll(el) {
        const mc = document.getElementById('main-content');
        if (mc) localStorage.setItem('perumahanScrollPos', mc.scrollTop);
        const form = el.tagName === 'FORM' ? el : el.form;
        if (form) form.submit();
    }

    const selectAll = document.getElementById('select-all');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    const bulkBar = document.getElementById('bulk-action-bar');
    const selectedCount = document.getElementById('selected-count');

    function updateBulkBar() {
        const checked = document.querySelectorAll('.row-checkbox:checked');
        if (checked.length > 0) { bulkBar.classList.remove('-translate-y-full'); selectedCount.innerText = `${checked.length} TERPILIH`; }
        else { bulkBar.classList.add('-translate-y-full'); }
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
            if(selectAll) selectAll.checked = allChecked;
            updateBulkBar();
        });
    });

    function clearSelection() {
        if(selectAll) selectAll.checked = false;
        rowCheckboxes.forEach(cb => { cb.checked = false; cb.closest('tr').classList.remove('bg-blue-50/50', 'dark:bg-blue-900/10'); });
        updateBulkBar();
    }

    async function handleBulkDelete() {
        const checked = document.querySelectorAll('.row-checkbox:checked');
        const ids = Array.from(checked).map(cb => cb.value);
        const ok = await window.customConfirm('Hapus Massal?', `Apakah Anda yakin ingin menghapus ${ids.length} data perumahan yang dipilih?`, 'danger');
        if (ok) {
            const formData = new FormData();
            ids.forEach(id => formData.append('ids[]', id));
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
            try {
                const response = await fetch('<?= base_url('perumahan-formal/bulk-delete') ?>', { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const result = await response.json();
                if (result.status === 'success') { showToast(result.message, 'success'); setTimeout(() => window.location.reload(), 1000); }
                else { showToast(result.message, 'error'); }
            } catch (error) { showToast('Terjadi kesalahan sistem.', 'error'); }
        }
    }

    window.addEventListener('load', initMap);
</script>

<style>
    .leaflet-popup-content-wrapper { border-radius: 1rem; padding: 0; overflow: hidden; box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.2); border: none; }
    .leaflet-popup-content { margin: 0; width: 200px !important; }
    .leaflet-container { font-family: inherit; }
    .marker-cluster-small div, .marker-cluster-medium div, .marker-cluster-large div { background-color: rgba(30, 27, 75, 0.9); color: white; font-weight: 900; font-size: 10px; }
</style>
<?= $this->endSection() ?>
