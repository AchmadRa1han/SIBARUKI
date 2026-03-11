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
            <h1 class="text-3xl font-black text-blue-950 dark:text-white uppercase tracking-tight">PISEW</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium text-sm">Program Pengembangan Infrastruktur Sosial Ekonomi Wilayah Kab. Sinjai.</p>
        </div>
        <div class="flex flex-wrap md:flex-nowrap items-center justify-end gap-2 flex-shrink-0">
            <div class="bg-blue-50 text-blue-600 px-3 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest border border-blue-100 whitespace-nowrap">
                <?= number_format($total_kegiatan ?? 0) ?> Kegiatan
            </div>
            <a href="<?= base_url('pisew/export-excel') ?>" class="bg-emerald-50 text-emerald-600 px-3 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest border border-emerald-100 hover:bg-emerald-600 hover:text-white transition-all flex items-center gap-2 whitespace-nowrap">
                <i data-lucide="download" class="w-3 h-3"></i> Export Excel
            </a>
            <?php if (has_permission('create_rtlh')): ?>
            <a href="<?= base_url('pisew/create') ?>" class="bg-blue-950 hover:bg-black text-white px-3 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest shadow-lg transition-all flex items-center gap-2 group whitespace-nowrap">
                <i data-lucide="plus" class="w-3 h-3 group-hover:rotate-90 transition-transform"></i> Tambah Data
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Map Section -->
    <div class="relative group">
        <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-[2.5rem] blur opacity-10 transition duration-1000"></div>
        <div class="relative bg-white dark:bg-slate-900 rounded-[2.5rem] overflow-hidden shadow-2xl border border-slate-100 dark:border-slate-800">
            <div id="map" class="w-full h-[60vh] z-10" style="min-height: 450px; background: #ececec;"></div>
            <div class="absolute top-6 left-6 z-[1000] hidden md:block">
                <div class="bg-blue-950/80 backdrop-blur-md text-white px-4 py-2.5 rounded-2xl text-[9px] font-black uppercase tracking-widest shadow-2xl border border-white/10 flex items-center gap-3">
                    <div class="w-1.5 h-1.5 bg-blue-400 rounded-full animate-ping"></div>
                    Database Spasial PISEW
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden relative">
        <!-- Floating Bulk Action Bar -->
        <div id="bulk-action-bar" class="absolute top-0 left-0 right-0 z-50 bg-blue-950 text-white p-4 transform -translate-y-full transition-transform duration-300 flex items-center justify-between px-10">
            <div class="flex items-center gap-4">
                <span id="selected-count" class="bg-blue-600 px-3 py-1 rounded-full text-[10px] font-black tracking-widest">0 TERPILIH</span>
                <p class="text-[10px] font-bold uppercase tracking-widest opacity-70">Aksi massal untuk data PISEW</p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="handleBulkDelete()" class="px-6 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Hapus Terpilih
                </button>
                <button onclick="clearSelection()" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">Batal</button>
            </div>
        </div>

        <div class="p-6 border-b border-slate-50 dark:border-slate-800 flex flex-col lg:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-blue-950 rounded-2xl flex items-center justify-center text-white shadow-xl">
                    <i data-lucide="database" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-xs font-black text-blue-950 dark:text-white uppercase tracking-tight">Daftar Kegiatan PISEW</h3>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Infrastruktur Sosial Ekonomi Wilayah</p>
                </div>
            </div>

            <form action="<?= base_url('pisew') ?>" method="get" class="flex flex-col md:flex-row items-center gap-2 w-full lg:w-auto" id="filter-form">
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
                    <input type="text" name="search" value="<?= $search ?>" placeholder="Cari desa/pekerjaan..." class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-[9px] font-black px-3 py-2.5 pl-9 focus:ring-2 focus:ring-blue-500 transition-all uppercase">
                    <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse table-fixed">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/50 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                        <th class="px-6 py-4 w-16 text-center">
                            <input type="checkbox" id="select-all" class="w-5 h-5 rounded-lg border-2 border-slate-200 text-blue-950 focus:ring-blue-900/20 cursor-pointer transition-all">
                        </th>
                        <th class="px-4 py-4 w-64 cursor-pointer hover:text-blue-600" onclick="applySort('jenis_pekerjaan')">Jenis Pekerjaan</th>
                        <th class="px-4 py-4 w-48">Lokasi (Desa)</th>
                        <th class="px-4 py-4 w-32">Kecamatan</th>
                        <th class="px-4 py-4 w-32 cursor-pointer hover:text-blue-600" onclick="applySort('anggaran')">Anggaran</th>
                        <th class="px-4 py-4 w-20 text-center cursor-pointer hover:text-blue-600" onclick="applySort('tahun')">Tahun</th>
                        <th class="px-4 py-4 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800 text-[10px]">
                    <?php foreach($pisew as $item): ?>
                    <tr class="group hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-all duration-200">
                        <td class="px-6 py-3 text-center">
                            <input type="checkbox" name="ids[]" value="<?= $item['id'] ?>" class="row-checkbox w-5 h-5 rounded-lg border-2 border-slate-200 text-blue-950 focus:ring-blue-900/20 cursor-pointer transition-all">
                        </td>
                        <td class="px-4 py-3 font-black text-blue-950 dark:text-white uppercase truncate block" title="<?= $item['jenis_pekerjaan'] ?>"><?= $item['jenis_pekerjaan'] ?></td>
                        <td class="px-4 py-3 font-bold text-slate-700 dark:text-slate-200 uppercase truncate" title="<?= $item['lokasi_desa'] ?>"><?= $item['lokasi_desa'] ?></td>
                        <td class="px-4 py-3 font-black text-blue-600 uppercase"><?= $item['kecamatan'] ?></td>
                        <td class="px-4 py-3 font-black text-slate-700 dark:text-slate-300">Rp <?= number_format($item['anggaran'], 0, ',', '.') ?></td>
                        <td class="px-4 py-3 text-center"><span class="px-2 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-black rounded-lg"><?= $item['tahun'] ?></span></td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <?php if($item['koordinat']): ?>
                                <button onclick="focusMap(<?= $item['koordinat'] ?>)" class="p-2 bg-white dark:bg-slate-800 text-blue-600 rounded-lg shadow-sm border border-slate-100 dark:border-slate-700 hover:bg-blue-600 hover:text-white transition-all active:scale-95"><i data-lucide="map-pin" class="w-3.5 h-3.5"></i></button>
                                <?php endif; ?>
                                <a href="<?= base_url('pisew/detail/'.$item['id']) ?>" class="p-2 bg-blue-950 text-white rounded-lg shadow-md hover:bg-black transition-all active:scale-95" title="Detail"><i data-lucide="eye" class="w-3.5 h-3.5"></i></a>
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

            const clusterGroup = L.markerClusterGroup({ showCoverageOnHover: false, maxClusterRadius: 50 });
            const pisewData = <?= json_encode($pisew_all ?? []) ?>;
            pisewData.forEach(item => {
                if (item.koordinat) {
                    const coords = item.koordinat.split(',').map(c => parseFloat(c.trim()));
                    if (coords.length === 2 && !isNaN(coords[0]) && !isNaN(coords[1])) {
                        const marker = L.circleMarker(coords, { radius: 8, fillColor: "#1e1b4b", color: "#fff", weight: 2, fillOpacity: 0.8 });
                        marker.bindPopup(`
                            <div class="bg-blue-950 text-white p-4 rounded-t-xl"><p class="text-[8px] font-black uppercase tracking-[0.2em] text-blue-400 mb-1">Kegiatan PISEW</p><h5 class="text-xs font-black uppercase leading-tight">${item.jenis_pekerjaan}</h5></div>
                            <div class="p-4 bg-white dark:bg-slate-900 space-y-3 rounded-b-xl"><p class="text-[10px] font-bold text-slate-700">📍 ${item.lokasi_desa}</p><a href="<?= base_url('pisew/detail/') ?>/${item.id}" class="block w-full py-2 bg-blue-950 text-white text-center text-[9px] font-black uppercase tracking-widest rounded-xl transition-all">Detail</a></div>
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

    document.addEventListener('DOMContentLoaded', () => {
        const mc = document.getElementById('main-content');
        if (mc) {
            const sp = localStorage.getItem('pisewScrollPos');
            if (sp) { setTimeout(() => { mc.scrollTop = sp; localStorage.removeItem('pisewScrollPos'); }, 100); }
            document.querySelectorAll('nav a').forEach(link => link.addEventListener('click', () => localStorage.setItem('pisewScrollPos', mc.scrollTop)));
        }
    });

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
        const checked = document.querySelectorAll('.row-checkbox:checked');
        const ids = Array.from(checked).map(cb => cb.value);
        
        const ok = await window.customConfirm('Hapus Massal?', `Apakah Anda yakin ingin menghapus ${ids.length} data PISEW yang dipilih? Tindakan ini tidak dapat dibatalkan.`, 'danger');
        
        if (ok) {
            const formData = new FormData();
            ids.forEach(id => formData.append('ids[]', id));
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            try {
                const response = await fetch('<?= base_url('pisew/bulk-delete') ?>', {
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
            } catch (error) {
                showToast('Terjadi kesalahan sistem.', 'error');
            }
        }
    }

    window.addEventListener('load', initMap);
</script>

<style>
    .leaflet-popup-content-wrapper { border-radius: 1.5rem; padding: 0; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3); border: none; }
    .leaflet-popup-content { margin: 0; width: 240px !important; }
    .leaflet-container { font-family: inherit; }
</style>
<?= $this->endSection() ?>
