<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<!-- Leaflet Assets -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="space-y-6 pb-12 text-slate-900 dark:text-slate-200">
    
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-3 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 no-print">
        <a href="<?= base_url('dashboard') ?>" class="hover:text-blue-600 transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">PSU JALAN</span>
    </nav>

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm relative overflow-hidden transition-all duration-300">
        <div class="absolute top-0 right-0 w-48 h-48 bg-indigo-600/5 rounded-full -mr-24 -mt-24 blur-3xl"></div>
        <div class="relative z-10 flex items-center gap-4">
            <a href="<?= base_url('dashboard') ?>" class="p-3 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl hover:bg-blue-600 hover:text-white transition-all active:scale-95" title="Kembali">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-blue-950 dark:text-white uppercase tracking-tighter">Infrastruktur PSU</h1>
                <p class="text-slate-500 dark:text-slate-400 font-medium text-xs mt-1">Data Jaringan Jalan dan Prasarana Sarana Utilitas Terpadu.</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2 relative z-10">
            <div class="bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 px-4 py-2 rounded-xl text-[9px] font-bold uppercase tracking-widest border border-indigo-100 dark:border-indigo-800/50 shadow-sm">
                <?= number_format($total_jalan ?? 0) ?> Ruas
            </div>
            <a href="<?= base_url('psu/export-excel') ?>" class="bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 px-4 py-2 rounded-xl text-[9px] font-bold uppercase tracking-widest border border-emerald-100 dark:border-emerald-800/50 hover:bg-emerald-600 hover:text-white transition-all active:scale-95 flex items-center gap-2 shadow-sm">
                <i data-lucide="download" class="w-3.5 h-3.5"></i> Export
            </a>
            <?php if (has_permission('create_psu')): ?>
            <a href="<?= base_url('psu/create') ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-[9px] font-bold uppercase tracking-widest shadow-lg shadow-blue-600/20 transition-all active:scale-95 flex items-center gap-2 group">
                <i data-lucide="plus" class="w-4 h-4 group-hover:rotate-90 transition-transform"></i> Tambah Data
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Map Section -->
    <div class="relative">
        <div class="bg-white dark:bg-slate-900 rounded-2xl overflow-hidden shadow-md border border-slate-100 dark:border-slate-800">
            <div id="map" class="w-full h-[450px] z-10" style="background: #ececec;"></div>
            <div class="absolute top-6 left-6 z-[1000] hidden md:block">
                <div class="bg-blue-950/80 backdrop-blur-md text-white px-4 py-2 rounded-xl text-[9px] font-bold uppercase tracking-[0.2em] shadow-2xl border border-white/10 flex items-center gap-3">
                    <div class="w-1.5 h-1.5 bg-indigo-400 rounded-full animate-ping"></div>
                    Jaringan Jalan (PSU)
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Card -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-100 dark:border-slate-800 p-3">
        <div class="flex flex-col lg:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-3 w-full lg:w-auto">
                <div class="flex bg-slate-100 dark:bg-slate-800 p-1 rounded-xl w-full md:w-auto">
                    <button class="px-4 py-2 bg-white dark:bg-slate-700 text-blue-600 rounded-lg text-[9px] font-bold uppercase tracking-widest shadow-sm">Daftar Jaringan Jalan</button>
                </div>
            </div>

            <form action="<?= base_url('psu') ?>" method="get" class="flex flex-col md:flex-row items-center gap-2 w-full lg:w-auto" id="filter-form">
                <div class="relative w-full md:w-28">
                    <select name="per_page" onchange="submitWithScroll(this)" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-[9px] font-bold uppercase px-3 py-2 focus:ring-2 focus:ring-blue-500 cursor-pointer appearance-none">
                        <?php foreach([5, 10, 25, 50, 100] as $p): ?>
                            <option value="<?= $p ?>" <?= ($perPage ?? 10) == $p ? 'selected' : '' ?>><?= $p ?> Baris</option>
                        <?php endforeach; ?>
                    </select>
                    <i data-lucide="chevron-down" class="w-3 h-3 text-slate-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                </div>

                <div class="relative w-full md:w-64">
                    <input type="text" name="keyword" value="<?= $keyword ?? '' ?>" placeholder="Cari ruas jalan..." class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-[9px] font-bold uppercase px-3 py-2 pl-10 focus:ring-2 focus:ring-blue-500 transition-all">
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
                    <h3 class="text-sm font-bold text-blue-950 dark:text-white uppercase tracking-tight">Inventaris Jaringan Jalan</h3>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-[0.2em]">Manajemen Geospasial Infrastruktur</p>
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
                        <th class="px-4 py-4 w-64">Nama Ruas Jalan</th>
                        <th class="px-4 py-4 w-32 text-center">ID Inventaris</th>
                        <th class="px-4 py-4 w-40">Nilai Aset</th>
                        <th class="px-6 py-4 text-center w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800 text-[10px]">
                    <?php if (!empty($jalan)): foreach($jalan as $item): ?>
                    <tr class="group hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-all duration-200">
                        <td class="px-6 py-3 text-center">
                            <input type="checkbox" name="ids[]" value="<?= $item['id'] ?>" class="row-checkbox w-4.5 h-4.5 rounded-lg border-2 border-slate-200 text-blue-600 focus:ring-blue-600/20 cursor-pointer transition-all">
                        </td>
                        <td class="px-4 py-3">
                            <span class="font-bold text-blue-950 dark:text-white uppercase truncate block text-xs mb-0.5"><?= $item['nama_jalan'] ?: 'Tanpa Nama Ruas' ?></span>
                            <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Infrastruktur PSU Kabupaten Sinjai</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2.5 py-0.5 bg-slate-100 dark:bg-slate-800 text-slate-500 rounded-lg font-mono font-bold text-[9px] border border-slate-200 dark:border-slate-700">
                                <?= $item['id_csv'] ?>
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="font-bold text-indigo-600 dark:text-indigo-400 tracking-wider">Rp <?= number_format($item['jalan'], 2) ?></span>
                        </td>
                        <td class="px-6 py-3 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <?php if(!empty($item['wkt'])): ?>
                                <button onclick="focusOnWkt('<?= htmlspecialchars($item['wkt']) ?>')" class="p-2 bg-white dark:bg-slate-800 text-blue-600 rounded-lg shadow-sm border border-slate-100 dark:border-slate-700 hover:bg-blue-600 hover:text-white transition-all active:scale-95" title="Peta"><i data-lucide="map" class="w-3.5 h-3.5"></i></button>
                                <?php endif; ?>
                                <a href="<?= base_url('psu/detail/' . $item['id']) ?>" class="p-2 bg-blue-950 dark:bg-blue-600 text-white rounded-lg shadow-md hover:scale-110 transition-all active:scale-95" title="Detail"><i data-lucide="eye" class="w-3.5 h-3.5"></i></a>
                                <?php if (has_permission('delete_psu')): ?>
                                <button onclick="confirmDelete(<?= $item['id'] ?>, '<?= addslashes($item['nama_jalan'] ?? '') ?>')" class="p-2 bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 rounded-lg hover:bg-rose-600 hover:text-white transition-all active:scale-95" title="Hapus"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                        <tr>
                            <td colspan="5" class="px-8 py-16 text-center">
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

<form id="delete-form" action="" method="post" class="hidden"><?= csrf_field() ?></form>

<script>
    // UTM ZONE 51S CONVERTER (SINJAI)
    function utmToLatLng(easting, northing) {
        const a = 6378137, f = 1 / 298.257223563;
        const b = a * (1 - f), e = Math.sqrt(1 - (b * b) / (a * a)), e1sq = (e * e) / (1 - e * e);
        const k0 = 0.9996, falseEasting = 500000, falseNorthing = 10000000;
        const zoneCentralMeridian = 123 * (Math.PI / 180); 
        let x = easting - falseEasting, y = northing - falseNorthing;
        let M = y / k0, mu = M / (a * (1 - e * e / 4 - 3 * e * e * e * e / 64 - 5 * e * e * e * e * e * e / 256));
        let phi1Rad = mu + (3 * e1sq / 2 - 27 * e1sq * e1sq * e1sq / 32) * Math.sin(2 * mu) + (21 * e1sq * e1sq / 16 - 55 * e1sq * e1sq * e1sq / 32) * Math.sin(4 * mu) + (151 * e1sq * e1sq * e1sq / 96) * Math.sin(6 * mu);
        let N1 = a / Math.sqrt(1 - e * e * Math.sin(phi1Rad) * Math.sin(phi1Rad)), T1 = Math.tan(phi1Rad) * Math.tan(phi1Rad), C1 = e1sq * Math.cos(phi1Rad) * Math.cos(phi1Rad), R1 = a * (1 - e * e) / Math.pow(1 - e * e * Math.sin(phi1Rad) * Math.sin(phi1Rad), 1.5);
        let D = x / (N1 * k0);
        let lat = phi1Rad - (N1 * Math.tan(phi1Rad) / R1) * (D * D / 2 - (5 + 3 * T1 + 10 * C1 - 4 * C1 * C1 - 9 * e1sq) * D * D * D * D / 24 + (61 + 90 * T1 + 298 * C1 + 45 * T1 * T1 - 252 * e1sq - 3 * C1 * C1) * D * D * D * D * D * D / 720);
        let lon = zoneCentralMeridian + (D - (1 + 2 * T1 + C1) * D * D * D / 6 + (5 - 2 * C1 + 28 * T1 - 3 * C1 * C1 + 8 * e1sq + 24 * T1 * T1) * D * D * D * D * D / 120) / Math.cos(phi1Rad);
        return [lat * (180 / Math.PI), lon * (180 / Math.PI)];
    }

    function parseWKT(wkt) {
        if (!wkt || typeof wkt !== 'string') return null;
        try {
            const cleanWkt = wkt.toUpperCase().trim();
            if (cleanWkt.includes('LINESTRING')) {
                const match = cleanWkt.match(/\(([^()]+)\)/);
                if (!match || !match[1]) return null;
                return match[1].split(',').map(pair => {
                    const parts = pair.trim().split(/\s+/);
                    if (parts.length >= 2) return utmToLatLng(parseFloat(parts[0]), parseFloat(parts[1]));
                    return null;
                }).filter(p => p !== null);
            }
        } catch(e) { console.error('WKT Parse Error:', e); }
        return null;
    }

    let map;
    let rot = 0;
    const psuData = <?= json_encode($jalan_all ?? []) ?>;

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
                layers: [cartoDB] 
            }).setView([-5.1245, 120.2536], 13);
            
            L.control.zoom({ position: 'topright' }).addTo(map);
            L.control.layers({
                "Default View": cartoDB,
                "Satellite View": googleSat
            }, null, { position: 'topright' }).addTo(map);

            let allPoints = [];
            if (Array.isArray(psuData)) {
                psuData.forEach(item => {
                    const coords = parseWKT(item.wkt);
                    if (coords && coords.length > 0) {
                        const line = L.polyline(coords, { color: '#4f46e5', weight: 4, opacity: 0.8 }).addTo(map);
                        line.bindPopup(`
                            <div class="bg-blue-950 text-white p-3 rounded-t-xl"><p class="text-[7px] font-bold uppercase tracking-widest text-blue-400 mb-1">PSU JALAN</p><h5 class="text-[11px] font-bold uppercase leading-tight">${item.nama_jalan || 'Tanpa Nama'}</h5></div>
                            <div class="p-3 bg-white dark:bg-slate-900 space-y-2 rounded-b-xl"><p class="text-[9px] font-bold text-slate-700 dark:text-slate-300">📍 ID: ${item.id_csv}</p><a href="<?= base_url('psu/detail/') ?>/${item.id}" class="block w-full py-2 bg-blue-950 text-white text-center text-[8px] font-bold uppercase tracking-widest rounded-lg transition-all">Detail</a></div>
                        `);
                        allPoints = allPoints.concat(coords);
                    }
                });
            }
            if (allPoints.length > 0) map.fitBounds(L.polyline(allPoints).getBounds(), { padding: [50, 50] });
            if (typeof lucide !== 'undefined') lucide.createIcons();
        } catch(err) { console.error('Map Init Error:', err); }
    }

    function focusOnWkt(wkt) {
        const coords = parseWKT(wkt);
        if (coords && coords.length > 0) {
            map.fitBounds(L.polyline(coords).getBounds(), { padding: [100, 100], maxZoom: 18 });
            const mc = document.getElementById('main-content');
            if (mc) mc.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }

    function confirmDelete(id, name) {
        customConfirm('Hapus Data?', `Apakah Anda yakin ingin menghapus ruas jalan "${name}"?`, 'danger').then(conf => {
            if (conf) { document.getElementById('delete-form').action = `<?= base_url('psu/delete') ?>/${id}`; document.getElementById('delete-form').submit(); }
        });
    }

    function submitWithScroll(el) {
        const mc = document.getElementById('main-content');
        if (mc) localStorage.setItem('psuScrollPos', mc.scrollTop);
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
        const ok = await window.customConfirm('Hapus Massal?', `Apakah Anda yakin ingin menghapus ${ids.length} data jalan yang dipilih?`, 'danger');
        if (ok) {
            const formData = new FormData();
            ids.forEach(id => formData.append('ids[]', id));
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
            try {
                const response = await fetch('<?= base_url('psu/bulk-delete') ?>', { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
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
</style>
<?= $this->endSection() ?>
