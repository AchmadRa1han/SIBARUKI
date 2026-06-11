<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<!-- Leaflet Assets -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>

<div class="space-y-6 pb-12 text-slate-900 dark:text-slate-200">
    
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-3 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 no-print">
        <a href="<?= base_url('dashboard') ?>" class="hover:text-blue-600 transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">PISEW</span>
    </nav>

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-blue-950 p-7 rounded-[2.5rem] text-white shadow-2xl shadow-blue-950/20 relative overflow-hidden transition-all duration-500">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
        <div class="relative z-10 flex items-center gap-5">
            <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-md border border-white/10 shadow-inner">
                <i data-lucide="building-2" class="w-6 h-6 text-indigo-400"></i>
            </div>
            <div>
                <h1 class="text-2xl md:text-3xl font-black uppercase tracking-tighter leading-none">PISEW</h1>
                <p class="text-white/60 font-medium text-xs mt-2 tracking-wide">Pengembangan Infrastruktur Sosial Ekonomi Wilayah</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2 relative z-10">
            <a href="<?= base_url('pisew/export-excel') ?>" class="bg-emerald-600 text-white px-3.5 py-2 rounded-xl text-[9px] font-bold uppercase tracking-widest shadow-xl shadow-emerald-600/20 hover:bg-emerald-700 transition-all active:scale-95 flex items-center gap-1.5">
                <i data-lucide="file-spreadsheet" class="w-3.5 h-3.5"></i> Export
            </a>
            <?php if (has_permission('create_rtlh')): ?>
            <button onclick="UI.openModal('modal-import')" class="bg-indigo-600 text-white px-3.5 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest shadow-xl shadow-indigo-600/20 hover:scale-105 active:scale-95 transition-all flex items-center gap-1.5 group">
                <i data-lucide="upload-cloud" class="w-3.5 h-3.5 transition-transform group-hover:-translate-y-0.5"></i> Import
            </button>
            <button onclick="pisewModal.openAdd()" class="bg-white text-blue-950 px-3.5 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest shadow-xl hover:scale-105 active:scale-95 transition-all flex items-center gap-1.5 group">
                <i data-lucide="plus" class="w-3.5 h-3.5 transition-transform group-hover:rotate-90"></i> Tambah
            </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Map Section -->
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-3 shadow-xl shadow-slate-200/50 dark:shadow-black/20 border border-slate-100 dark:border-slate-800 relative overflow-hidden">
        <div class="absolute top-6 left-6 z-[1000]">
            <span class="px-3 py-1 bg-blue-950/90 backdrop-blur-md rounded-lg text-[8px] font-bold uppercase tracking-widest shadow-sm border border-white/10 text-white flex items-center gap-2">
                <div class="w-1.5 h-1.5 bg-indigo-400 rounded-full animate-ping"></div>
                Geospasial PISEW
            </span>
        </div>
        <div id="map" class="h-[450px] w-full rounded-[2rem] z-0 bg-slate-50 dark:bg-slate-950"></div>
    </div>

    <!-- Filter & Search Card -->
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-xl overflow-hidden">
        <div class="p-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <form action="<?= base_url('pisew') ?>" method="get" class="flex flex-col md:flex-row items-center gap-3 w-full lg:w-auto" id="filter-form">
                <input type="hidden" name="sort_by" value="<?= $sortBy ?>">
                <input type="hidden" name="sort_order" value="<?= $sortOrder ?>">
                
                <div class="flex items-center gap-2 bg-blue-950/5 dark:bg-slate-800 px-3 py-1.5 rounded-xl border border-blue-950/10 focus-within:border-blue-950 transition-all">
                    <span class="text-[9px] font-black text-blue-950/40 dark:text-slate-500 uppercase tracking-widest">Tampil</span>
                    <select name="per_page" onchange="submitWithScroll(this)" class="bg-transparent border-none text-xs font-black text-blue-950 dark:text-white outline-none cursor-pointer appearance-none">
                        <?php foreach([10, 25, 50, 100] as $p): ?>
                            <option value="<?= $p ?>" <?= ($perPage ?? 10) == $p ? 'selected' : '' ?>><?= $p ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="flex items-center gap-2 bg-blue-950/5 dark:bg-slate-800 px-3 py-1.5 rounded-xl border border-blue-950/10 focus-within:border-blue-950 transition-all">
                    <span class="text-[9px] font-black text-blue-950/40 dark:text-slate-500 uppercase tracking-widest">Wilayah</span>
                    <select name="kecamatan" onchange="submitWithScroll(this)" class="bg-transparent border-none text-xs font-black text-blue-950 dark:text-white outline-none cursor-pointer appearance-none">
                        <option value="">Semua Kecamatan</option>
                        <?php foreach($kecamatans as $k): ?>
                            <option value="<?= $k['kecamatan'] ?>" <?= $selected_kecamatan == $k['kecamatan'] ? 'selected' : '' ?>><?= $k['kecamatan'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="relative w-full md:w-80 group">
                    <input type="text" name="search" value="<?= $search ?>" placeholder="Cari desa/kegiatan..." class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-blue-600 transition-all outline-none">
                    <i data-lucide="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-blue-600 transition-colors"></i>
                </div>
                <button type="submit" class="w-full md:w-auto px-8 py-3 bg-blue-950 dark:bg-blue-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:shadow-lg transition-all active:scale-95">Cari</button>
            </form>
        </div>

        <div class="overflow-x-auto relative">
            <!-- Floating Bulk Action Bar -->
            <div id="bulk-action-bar" class="hidden fixed bottom-8 left-1/2 -translate-x-1/2 z-[5000] bg-blue-950 text-white px-8 py-4 rounded-3xl shadow-2xl flex items-center gap-6 border border-white/10 backdrop-blur-xl animate-bounce-subtle">
                <div class="flex items-center gap-3 pr-6 border-r border-white/10">
                    <span id="selected-count" class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-[10px] font-black">0</span>
                    <span class="text-[9px] font-bold uppercase tracking-widest">Data Terpilih</span>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="handleBulkDelete()" class="px-6 py-2.5 bg-rose-500 hover:bg-rose-600 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg transition-all active:scale-95 flex items-center gap-2">
                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Hapus Massal
                    </button>
                    <button onclick="clearSelection()" class="px-6 py-2.5 bg-white/10 hover:bg-white/20 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">Batal</button>
                </div>
            </div>

            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
                        <th class="px-8 py-5 w-16 text-center">
                            <input type="checkbox" id="select-all" class="w-4.5 h-4.5 rounded-lg border-2 border-slate-200 text-blue-950 focus:ring-blue-900/20 cursor-pointer transition-all">
                        </th>
                        <th class="px-8 py-5 cursor-pointer hover:text-blue-950 transition-colors" onclick="applySort('jenis_pekerjaan')">Informasi Kegiatan</th>
                        <th class="px-8 py-5">Lokasi / Desa</th>
                        <th class="px-8 py-5 cursor-pointer hover:text-blue-950 transition-colors" onclick="applySort('anggaran')">Anggaran</th>
                        <th class="px-8 py-5 text-center">Tahun</th>
                        <th class="px-8 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-slate-800">
                    <?php if (!empty($pisew)): foreach($pisew as $item): ?>
                    <tr class="group hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-all duration-300">
                        <td class="px-8 py-6 text-center">
                            <input type="checkbox" name="ids[]" value="<?= $item['id'] ?>" class="row-checkbox w-4.5 h-4.5 rounded-lg border-2 border-slate-200 text-blue-950 focus:ring-blue-900/20 cursor-pointer transition-all">
                        </td>
                        <td class="px-8 py-6">
                            <p class="text-sm font-black text-blue-950 dark:text-white uppercase tracking-tight"><?= $item['jenis_pekerjaan'] ?></p>
                            <p class="text-[10px] font-bold text-slate-400 tracking-widest mt-1 uppercase">Pelaksana: <?= $item['pelaksana'] ?: '-' ?></p>
                        </td>
                        <td class="px-8 py-6">
                            <p class="text-[11px] font-black text-slate-700 dark:text-slate-300 uppercase"><?= $item['lokasi_desa'] ?></p>
                            <p class="text-[10px] font-bold text-slate-400 mt-0.5 uppercase tracking-tighter">Kec. <?= $item['kecamatan'] ?></p>
                        </td>
                        <td class="px-8 py-6">
                            <span class="font-black text-blue-950 dark:text-white tracking-wider text-sm">Rp <?= number_format($item['anggaran'], 0, ',', '.') ?></span>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <span class="px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-black rounded-full text-[9px] uppercase tracking-widest border border-slate-200 dark:border-slate-700">
                                <?= $item['tahun'] ?>
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex justify-end gap-2">
                                <?php if($item['koordinat']): ?>
                                <button onclick="focusMap(<?= $item['koordinat'] ?>)" class="p-2.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl shadow-lg border border-blue-100 dark:border-blue-800 hover:scale-110 active:scale-95 transition-all" title="Fokus Peta"><i data-lucide="map-pin" class="w-4 h-4"></i></button>
                                <?php endif; ?>
                                <a href="<?= base_url('pisew/detail/'.$item['id']) ?>" class="p-2.5 bg-blue-950 dark:bg-blue-600 text-white rounded-xl shadow-lg shadow-blue-950/20 hover:scale-110 active:scale-95 transition-all" title="Detail"><i data-lucide="eye" class="w-4 h-4"></i></a>
                                <button onclick="confirmDelete(<?= $item['id'] ?>)" class="p-2.5 bg-rose-500 text-white rounded-xl shadow-lg shadow-rose-500/20 hover:scale-110 active:scale-95 transition-all" title="Hapus"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center justify-center opacity-20">
                                    <i data-lucide="database-zap" class="w-16 h-16 mb-4 text-slate-400"></i>
                                    <p class="font-black uppercase text-xs tracking-[0.2em] text-slate-500">Data Tidak Ditemukan</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php if (isset($pager)): ?>
        <div class="p-8 border-t dark:border-slate-800 bg-slate-50/30 dark:bg-slate-900/50">
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
            }).setView([-5.1245, 120.2536], 11);
            
            L.control.zoom({ position: 'topright' }).addTo(map);

            let rot = 0;
            const LayerToggle = L.Control.extend({
                onAdd: function(map) {
                    const btn = L.DomUtil.create('button', 'rounded-lg shadow-xl border transition-all duration-300 active:scale-90 mt-2 flex items-center justify-center');
                    btn.style.width = '38px'; btn.style.height = '38px'; btn.style.cursor = 'pointer';
                    btn.type = 'button';
                    btn.style.backgroundColor = '#2563eb';
                    const isDark = document.documentElement.classList.contains('dark');
                    const standardSvgColor = isDark ? '#60a5fa' : '#2563eb';
                    btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:block; transition: transform 0.8s cubic-bezier(0.65, 0, 0.35, 1);"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>`;
                    L.DomEvent.disableClickPropagation(btn);
                    L.DomEvent.on(btn, 'click', function(e) {
                        L.DomEvent.stopPropagation(e);
                        L.DomEvent.preventDefault(e);
                        rot += 360;                        const svg = btn.querySelector('svg');
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
            const pisewData = <?= json_encode($pisew_all ?? []) ?>;
            pisewData.forEach(item => {
                if (item.koordinat) {
                    const coords = item.koordinat.split(',').map(c => parseFloat(c.trim()));
                    if (coords.length === 2 && !isNaN(coords[0]) && !isNaN(coords[1])) {
                        const marker = L.circleMarker(coords, { radius: 7, fillColor: "#4f46e5", color: "#fff", weight: 2, fillOpacity: 0.8 });
                        marker.bindPopup(`
                            <div class="bg-blue-950 text-white p-3 rounded-t-xl"><p class="text-[7px] font-bold uppercase tracking-widest text-indigo-400 mb-1">PISEW</p><h5 class="text-[11px] font-bold uppercase leading-tight">${item.jenis_pekerjaan}</h5></div>
                            <div class="p-3 bg-white dark:bg-slate-900 space-y-2 rounded-b-xl"><p class="text-[9px] font-bold text-slate-700">📍 ${item.lokasi_desa}</p><a href="<?= base_url('pisew/detail/') ?>/${item.id}" class="block w-full py-2.5 bg-blue-950 hover:bg-blue-800 text-white text-center text-[10px] font-black uppercase tracking-[0.2em] rounded-xl shadow-xl transition-all">Detail</a></div>
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

    function confirmDelete(id) {
        customConfirm('Hapus PISEW?', 'Apakah Anda yakin ingin menghapus data kegiatan ini?', 'danger').then(conf => {
            if (conf) { document.getElementById('delete-form').action = `<?= base_url('pisew/delete') ?>/${id}`; document.getElementById('delete-form').submit(); }
        });
    }

    function submitWithScroll(el) {
        const mc = document.getElementById('main-content');
        if (mc) localStorage.setItem('pisewScrollPos', mc.scrollTop);
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

    const selectAll = document.getElementById('select-all');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    const bulkBar = document.getElementById('bulk-action-bar');
    const selectedCount = document.getElementById('selected-count');

    function updateBulkBar() {
        const checked = document.querySelectorAll('.row-checkbox:checked').length;
        selectedCount.innerText = checked;
        bulkBar.classList.toggle('hidden', checked === 0);
        if (checked > 0 && window.lucide) lucide.createIcons();
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
        const ok = await window.customConfirm('Hapus Massal?', `Apakah Anda yakin ingin menghapus ${ids.length} data PISEW yang dipilih?`, 'danger');
        if (ok) {
            const formData = new FormData();
            ids.forEach(id => formData.append('ids[]', id));
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
            try {
                const response = await fetch('<?= base_url('pisew/bulk-delete') ?>', { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
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

<?= view('pisew/partials/_modal_import') ?>
<?= view('pisew/partials/_modal_edit') ?>

<?= $this->endSection() ?>
