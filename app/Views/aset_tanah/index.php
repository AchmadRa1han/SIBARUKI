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
    <nav class="flex items-center gap-3 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 no-print">
        <a href="<?= base_url('dashboard') ?>" class="hover:text-blue-600 transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">Aset Tanah</span>
    </nav>

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm relative overflow-hidden transition-all duration-300">
        <div class="absolute top-0 right-0 w-48 h-48 bg-blue-600/5 rounded-full -mr-24 -mt-24 blur-3xl"></div>
        <div class="relative z-10 flex items-center gap-4">
            <a href="<?= base_url('dashboard') ?>" class="p-3 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl hover:bg-blue-600 hover:text-white transition-all active:scale-95" title="Kembali">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-blue-950 dark:text-white uppercase tracking-tighter">Aset Tanah Pemda</h1>
                <p class="text-slate-500 dark:text-slate-400 font-medium text-xs mt-1">Manajemen & monitoring aset tanah Pemerintah Daerah Kabupaten Sinjai.</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2 relative z-10">
            <div class="bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest border border-blue-100 dark:border-blue-800/50 shadow-sm">
                <?= number_format($total_aset ?? 0) ?> Bidang
            </div>
            <a href="<?= base_url('aset-tanah/export-excel') ?>" class="bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest border border-emerald-100 dark:border-emerald-800/50 hover:bg-emerald-600 hover:text-white transition-all active:scale-95 flex items-center gap-2 shadow-sm">
                <i data-lucide="download" class="w-3.5 h-3.5"></i> Export
            </a>
            <?php if (has_permission('create_rtlh')): ?>
            <a href="<?= base_url('aset-tanah/create') ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest shadow-lg shadow-blue-600/20 transition-all active:scale-95 flex items-center gap-2 group">
                <i data-lucide="plus" class="w-4 h-4 group-hover:rotate-90 transition-transform"></i> Tambah Data
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Map Section -->
    <div class="relative">
        <div class="bg-white dark:bg-slate-900 rounded-[2rem] overflow-hidden shadow-md border border-slate-100 dark:border-slate-800">
            <div id="map" class="w-full h-[450px] z-10" style="background: #ececec;"></div>
            <div class="absolute top-6 left-6 z-[1000] hidden md:block">
                <div class="bg-blue-950/80 backdrop-blur-md text-white px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-[0.2em] shadow-2xl border border-white/10 flex items-center gap-3">
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
                <div class="flex bg-slate-100 dark:bg-slate-800 p-1 rounded-xl w-full md:w-auto">
                    <button class="px-4 py-2 bg-white dark:bg-slate-700 text-blue-600 rounded-lg text-[9px] font-black uppercase tracking-widest shadow-sm">Daftar Sertifikat</button>
                </div>
            </div>

            <form action="<?= base_url('aset-tanah') ?>" method="get" class="flex flex-col md:flex-row items-center gap-2 w-full lg:w-auto" id="filter-form">
                <input type="hidden" name="sort_by" value="<?= $sortBy ?>">
                <input type="hidden" name="sort_order" value="<?= $sortOrder ?>">
                
                <div class="relative w-full md:w-28">
                    <select name="per_page" onchange="submitWithScroll(this)" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-[9px] font-black uppercase px-3 py-2 focus:ring-2 focus:ring-blue-500 cursor-pointer appearance-none">
                        <?php foreach([5, 10, 25, 50, 100] as $p): ?>
                            <option value="<?= $p ?>" <?= ($perPage ?? 10) == $p ? 'selected' : '' ?>><?= $p ?> Baris</option>
                        <?php endforeach; ?>
                    </select>
                    <i data-lucide="chevron-down" class="w-3 h-3 text-slate-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                </div>

                <div class="relative w-full md:w-40">
                    <select name="kecamatan" onchange="submitWithScroll(this)" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-[9px] font-black uppercase px-3 py-2 focus:ring-2 focus:ring-blue-500 cursor-pointer appearance-none">
                        <option value="">Semua Wilayah</option>
                        <?php foreach($kecamatans as $k): ?>
                            <option value="<?= $k['kecamatan'] ?>" <?= $selected_kecamatan == $k['kecamatan'] ? 'selected' : '' ?>><?= $k['kecamatan'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                </div>

                <div class="relative w-full md:w-64">
                    <input type="text" name="search" value="<?= $search ?>" placeholder="Cari aset..." class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-[9px] font-black uppercase px-3 py-2 pl-10 focus:ring-2 focus:ring-blue-500 transition-all">
                    <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white dark:bg-slate-900 rounded-[2rem] shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden relative">
        <!-- Floating Bulk Action Bar -->
        <div id="bulk-action-bar" class="absolute top-0 left-0 right-0 z-50 bg-blue-950 text-white p-4 transform -translate-y-full transition-transform duration-500 flex items-center justify-between px-8">
            <div class="flex items-center gap-4">
                <span id="selected-count" class="bg-blue-600 px-3 py-1 rounded-lg text-[9px] font-black tracking-widest shadow-lg shadow-blue-600/20">0 TERPILIH</span>
                <p class="text-[9px] font-bold uppercase tracking-widest opacity-70 hidden md:block">Aksi massal tersedia</p>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="handleBulkDelete()" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-[9px] font-black uppercase tracking-widest transition-all active:scale-95 flex items-center gap-2 shadow-lg shadow-rose-600/20">
                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Hapus
                </button>
                <button onclick="clearSelection()" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg text-[9px] font-black uppercase tracking-widest transition-all active:scale-95">Batal</button>
            </div>
        </div>

        <div class="p-6 border-b border-slate-50 dark:border-slate-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-600/20">
                    <i data-lucide="database" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black text-blue-950 dark:text-white uppercase tracking-tight">Inventaris Aset Tanah</h3>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-[0.2em]">Data Legalitas & Luas Bidang</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse table-fixed">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/50 text-[9px] font-black text-slate-400 uppercase tracking-widest">
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
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800 text-[10px]">
                    <?php if (!empty($aset)): foreach($aset as $item): ?>
                    <tr class="group hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-all duration-200">
                        <td class="px-6 py-3 text-center">
                            <input type="checkbox" name="ids[]" value="<?= $item['id'] ?>" class="row-checkbox w-4.5 h-4.5 rounded-lg border-2 border-slate-200 text-blue-600 focus:ring-blue-600/20 cursor-pointer transition-all">
                        </td>
                        <td class="px-4 py-3">
                            <span class="font-black text-blue-600 dark:text-blue-400 uppercase tracking-wider"><?= $item['no_sertifikat'] ?></span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-col gap-0.5">
                                <span class="font-black text-blue-950 dark:text-white uppercase truncate block text-xs mb-0.5" title="<?= $item['nama_pemilik'] ?>"><?= $item['nama_pemilik'] ?></span>
                                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest truncate"><?= $item['lokasi'] ?></span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="font-black text-slate-700 dark:text-slate-300"><?= number_format($item['luas_m2'], 0, ',', '.') ?></span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="font-black text-slate-700 dark:text-slate-200 uppercase tracking-tight"><?= $item['kecamatan'] ?></span>
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
                                    <p class="font-black uppercase text-[9px] tracking-[0.3em]">Data Tidak Ditemukan</p>
                                </div>
                            </td>
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

            map = L.map('map', { zoomControl: false, layers: [standard] }).setView([-5.1245, 120.2536], 13);
            L.control.zoom({ position: 'topright' }).addTo(map);

            const LayerToggle = L.Control.extend({
                onAdd: function(map) {
                    const btn = L.DomUtil.create('button', 'bg-white dark:bg-slate-900 rounded-lg shadow-xl border border-slate-100 dark:border-slate-800 transition-all duration-300 active:scale-90 mt-2 flex items-center justify-center');
                    btn.style.width = '38px'; btn.style.height = '38px'; btn.style.cursor = 'pointer';
                    const svgColor = isDark ? '#60a5fa' : '#2563eb';
                    btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="${svgColor}" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:block; transition: transform 0.8s cubic-bezier(0.65, 0, 0.35, 1);"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>`;
                    L.DomEvent.disableClickPropagation(btn);
                    L.DomEvent.on(btn, 'click', function(e) {
                        rot += 360;
                        const svg = btn.querySelector('svg');
                        svg.style.transform = `rotate(${rot}deg)`;
                        setTimeout(() => {
                            if (map.hasLayer(standard)) { map.removeLayer(standard); map.addLayer(satellite); btn.style.backgroundColor = '#2563eb'; svg.setAttribute('stroke', '#ffffff'); }
                            else { map.removeLayer(satellite); map.addLayer(standard); btn.style.backgroundColor = isDark ? '#0f172a' : '#ffffff'; svg.setAttribute('stroke', svgColor); }
                        }, 200);
                    });
                    return btn;
                }
            });
            map.addControl(new LayerToggle({ position: 'topright' }));

            clusterGroup = L.markerClusterGroup({ showCoverageOnHover: false, maxClusterRadius: 50 });
            const asetData = <?= json_encode($aset_all ?? []) ?>;
            asetData.forEach(item => {
                if (item.koordinat) {
                    const coords = item.koordinat.split(',').map(c => parseFloat(c.trim()));
                    const marker = L.circleMarker(coords, { radius: 7, fillColor: "#1e1b4b", color: "#fff", weight: 2, fillOpacity: 0.8 });
                    marker.bindPopup(`
                        <div class="bg-blue-950 text-white p-3 rounded-t-xl border-b border-white/10"><p class="text-[7px] font-black uppercase tracking-[0.2em] text-blue-400 mb-1">Aset Tanah</p><h5 class="text-[11px] font-black uppercase leading-tight">${item.nama_pemilik}</h5></div>
                        <div class="p-3 bg-white dark:bg-slate-900 space-y-2 rounded-b-xl"><p class="text-[9px] font-black text-blue-600 uppercase">${item.no_sertifikat}</p><a href="<?= base_url('aset-tanah/detail/') ?>/${item.id}" class="block w-full py-2 bg-blue-950 text-white text-center text-[8px] font-black uppercase tracking-widest rounded-lg transition-all">Detail</a></div>
                    `);
                    clusterGroup.addLayer(marker);
                }
            });
            map.addLayer(clusterGroup);
            if (typeof lucide !== 'undefined') lucide.createIcons();
        } catch(err) { console.error(err); }
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
                updateBulkBar();
            });
        });
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
</script>

<style>
    .leaflet-popup-content-wrapper { border-radius: 1rem; padding: 0; overflow: hidden; box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.2); border: none; }
    .leaflet-popup-content { margin: 0; width: 200px !important; }
    .leaflet-container { font-family: inherit; }
    .marker-cluster-small div, .marker-cluster-medium div, .marker-cluster-large div { background-color: rgba(30, 27, 75, 0.9); color: white; font-weight: 900; font-size: 10px; }
</style>
<?= $this->endSection() ?>
