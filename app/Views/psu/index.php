<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<!-- Leaflet Assets -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="space-y-6 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-blue-950 dark:text-white uppercase tracking-tight">Prasarana, Sarana & Utilitas</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium text-sm">Data Jaringan Jalan dan PSU Terpadu Kabupaten Sinjai.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <div class="bg-indigo-50 text-indigo-600 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm border border-indigo-100">
                <?= number_format($total_jalan ?? 0) ?> Ruas Jalan
            </div>
            <a href="<?= base_url('psu/export-excel') ?>" class="bg-emerald-50 text-emerald-600 px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm border border-emerald-100 hover:bg-emerald-600 hover:text-white transition-all flex items-center gap-2">
                <i data-lucide="download" class="w-3.5 h-3.5"></i> Export Excel
            </a>
            <?php if (has_permission('create_psu')): ?>
            <a href="<?= base_url('psu/create') ?>" class="bg-blue-950 hover:bg-black text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-xl transition-all flex items-center gap-2 group">
                <i data-lucide="plus" class="w-3.5 h-3.5 group-hover:rotate-90 transition-transform"></i> Tambah Data
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
                    Visualisasi Jaringan Jalan (PSU)
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
                <p class="text-[10px] font-bold uppercase tracking-widest opacity-70">Aksi massal untuk data jaringan jalan</p>
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
                    <i data-lucide="map-pin" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-xs font-black text-blue-950 dark:text-white uppercase tracking-tight">Daftar Jaringan Jalan</h3>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Informasi Geospasial Infrastruktur</p>
                </div>
            </div>

            <form action="<?= base_url('psu') ?>" method="get" class="flex flex-col md:flex-row items-center gap-2 w-full lg:w-auto" id="filter-form">
                <select name="per_page" onchange="submitWithScroll(this)" class="w-full md:w-24 bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-[9px] font-black px-3 py-2.5 focus:ring-2 focus:ring-blue-500 cursor-pointer">
                    <?php foreach([5, 10, 25, 50, 100] as $p): ?>
                        <option value="<?= $p ?>" <?= ($perPage ?? 10) == $p ? 'selected' : '' ?>><?= $p ?> Baris</option>
                    <?php endforeach; ?>
                </select>
                <div class="relative w-full md:w-64">
                    <input type="text" name="keyword" value="<?= $keyword ?? '' ?>" placeholder="Cari Jalan..." class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-[9px] font-black px-3 py-2.5 pl-9 focus:ring-2 focus:ring-blue-500 transition-all uppercase">
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
                        <th class="px-4 py-4 w-64">Nama Jalan</th>
                        <th class="px-4 py-4 w-24">ID CSV</th>
                        <th class="px-4 py-4 w-40">Nilai Jalan</th>
                        <th class="px-4 py-4 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800 text-[10px]">
                    <?php if (!empty($jalan)): foreach($jalan as $item): ?>
                    <tr class="group hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-all duration-200">
                        <td class="px-6 py-3 text-center">
                            <input type="checkbox" name="ids[]" value="<?= $item['id'] ?>" class="row-checkbox w-5 h-5 rounded-lg border-2 border-slate-200 text-blue-950 focus:ring-blue-900/20 cursor-pointer transition-all">
                        </td>
                        <td class="px-4 py-3">
                            <span class="font-black text-blue-950 dark:text-white uppercase truncate block"><?= $item['nama_jalan'] ?: 'Tanpa Nama' ?></span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 bg-slate-100 dark:bg-slate-800 text-slate-500 rounded-lg font-black uppercase text-[8px]"><?= $item['id_csv'] ?></span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="font-bold text-slate-500 dark:text-slate-400 tracking-wider"><?= number_format($item['jalan'], 2) ?></span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <?php if(!empty($item['wkt'])): ?>
                                <button onclick="focusOnWkt('<?= htmlspecialchars($item['wkt']) ?>')" class="p-2 bg-white dark:bg-slate-800 text-blue-600 rounded-lg shadow-sm border border-slate-100 dark:border-slate-700 hover:bg-blue-600 hover:text-white transition-all active:scale-95">
                                    <i data-lucide="map" class="w-3.5 h-3.5"></i>
                                </button>
                                <?php endif; ?>
                                <a href="<?= base_url('psu/detail/' . $item['id']) ?>" class="p-2 bg-blue-950 text-white rounded-lg shadow-md hover:bg-black transition-all active:scale-95">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                </a>
                                <?php if (has_permission('delete_psu')): ?>
                                <button onclick="confirmDelete(<?= $item['id'] ?>, '<?= addslashes($item['nama_jalan'] ?? '') ?>')" class="p-2 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-600 hover:text-white transition-all active:scale-95">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="4" class="px-8 py-12 text-center text-slate-400 font-bold uppercase text-[10px]">Data tidak ditemukan</td></tr>
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
    let map;
    let rot = 0;
    const psuData = <?= json_encode($jalan_all ?? []) ?>;
    let activeLayers = [];

    // --- UTM TO LAT/LNG CONVERTER (ZONE 50S - SINJAI) ---
    function utmToLatLng(easting, northing) {
        // Konstanta untuk Zone 50S (WGS84)
        const a = 6378137;
        const f = 1 / 298.257223563;
        const b = a * (1 - f);
        const e = Math.sqrt(1 - (b * b) / (a * a));
        const e1sq = (e * e) / (1 - e * e);
        const k0 = 0.9996;
        const falseEasting = 500000;
        const falseNorthing = 10000000;
        const zoneCentralMeridian = 117 * (Math.PI / 180); // Zone 50

        let x = easting - falseEasting;
        let y = northing - falseNorthing;

        let M = y / k0;
        let mu = M / (a * (1 - e * e / 4 - 3 * e * e * e * e / 64 - 5 * e * e * e * e * e * e / 256));

        let phi1Rad = mu + (3 * e1sq / 2 - 27 * e1sq * e1sq * e1sq / 32) * Math.sin(2 * mu) 
                    + (21 * e1sq * e1sq / 16 - 55 * e1sq * e1sq * e1sq * e1sq / 32) * Math.sin(4 * mu)
                    + (151 * e1sq * e1sq * e1sq / 96) * Math.sin(6 * mu);

        let N1 = a / Math.sqrt(1 - e * e * Math.sin(phi1Rad) * Math.sin(phi1Rad));
        let T1 = Math.tan(phi1Rad) * Math.tan(phi1Rad);
        let C1 = e1sq * Math.cos(phi1Rad) * Math.cos(phi1Rad);
        let R1 = a * (1 - e * e) / Math.pow(1 - e * e * Math.sin(phi1Rad) * Math.sin(phi1Rad), 1.5);
        let D = x / (N1 * k0);

        let lat = phi1Rad - (N1 * Math.tan(phi1Rad) / R1) * (D * D / 2 - (5 + 3 * T1 + 10 * C1 - 4 * C1 * C1 - 9 * e1sq) * D * D * D * D / 24
                + (61 + 90 * T1 + 298 * C1 + 45 * T1 * T1 - 252 * e1sq - 3 * C1 * C1) * D * D * D * D * D * D / 720);
        let lon = zoneCentralMeridian + (D - (1 + 2 * T1 + C1) * D * D * D / 6 + (5 - 2 * C1 + 28 * T1 - 3 * C1 * C1 + 8 * e1sq + 24 * T1 * T1) * D * D * D * D * D / 120) / Math.cos(phi1Rad);

        return [lat * (180 / Math.PI), lon * (180 / Math.PI)];
    }

    // --- WKT PARSER (ZERO DEPENDENCY) ---
    function parseWKT(wkt) {
        if (!wkt) return null;
        try {
            if (wkt.startsWith('LINESTRING')) {
                const coordsStr = wkt.match(/\((.*)\)/)[1];
                return coordsStr.split(',').map(pair => {
                    const [e, n] = pair.trim().split(/\s+/).map(Number);
                    // Konversi UTM ke Lat/Lng
                    return utmToLatLng(e, n);
                });
            }
        } catch(e) { console.error('WKT Parse Error:', e); }
        return null;
    }

    // --- UTM TO LAT/LNG CONVERTER (ZONE 51S - SINJAI/SULSEL) ---
    function utmToLatLng(easting, northing) {
        const a = 6378137;
        const f = 1 / 298.257223563;
        const b = a * (1 - f);
        const e = Math.sqrt(1 - (b * b) / (a * a));
        const e1sq = (e * e) / (1 - e * e);
        const k0 = 0.9996;
        const falseEasting = 500000;
        const falseNorthing = 10000000;
        const zoneCentralMeridian = 123 * (Math.PI / 180); // Zone 51 (Sinjai)

        let x = easting - falseEasting;
        let y = northing - falseNorthing;

        let M = y / k0;
        let mu = M / (a * (1 - e * e / 4 - 3 * e * e * e * e / 64 - 5 * e * e * e * e * e * e / 256));

        let phi1Rad = mu + (3 * e1sq / 2 - 27 * e1sq * e1sq * e1sq / 32) * Math.sin(2 * mu) 
                    + (21 * e1sq * e1sq / 16 - 55 * e1sq * e1sq * e1sq * e1sq / 32) * Math.sin(4 * mu)
                    + (151 * e1sq * e1sq * e1sq / 96) * Math.sin(6 * mu);

        let N1 = a / Math.sqrt(1 - e * e * Math.sin(phi1Rad) * Math.sin(phi1Rad));
        let T1 = Math.tan(phi1Rad) * Math.tan(phi1Rad);
        let C1 = e1sq * Math.cos(phi1Rad) * Math.cos(phi1Rad);
        let R1 = a * (1 - e * e) / Math.pow(1 - e * e * Math.sin(phi1Rad) * Math.sin(phi1Rad), 1.5);
        let D = x / (N1 * k0);

        let lat = phi1Rad - (N1 * Math.tan(phi1Rad) / R1) * (D * D / 2 - (5 + 3 * T1 + 10 * C1 - 4 * C1 * C1 - 9 * e1sq) * D * D * D * D / 24
                + (61 + 90 * T1 + 298 * C1 + 45 * T1 * T1 - 252 * e1sq - 3 * C1 * C1) * D * D * D * D * D * D / 720);
        let lon = zoneCentralMeridian + (D - (1 + 2 * T1 + C1) * D * D * D / 6 + (5 - 2 * C1 + 28 * T1 - 3 * C1 * C1 + 8 * e1sq + 24 * T1 * T1) * D * D * D * D * D / 120) / Math.cos(phi1Rad);

        return [lat * (180 / Math.PI), lon * (180 / Math.PI)];
    }

    // --- SAFE WKT PARSER ---
    function parseWKT(wkt) {
        if (!wkt || typeof wkt !== 'string') return null;
        try {
            const cleanWkt = wkt.toUpperCase().trim();
            if (cleanWkt.includes('LINESTRING')) {
                const match = cleanWkt.match(/\(([^()]+)\)/);
                if (!match || !match[1]) return null;
                
                return match[1].split(',').map(pair => {
                    const parts = pair.trim().split(/\s+/);
                    if (parts.length >= 2) {
                        const e = parseFloat(parts[0]);
                        const n = parseFloat(parts[1]);
                        if (!isNaN(e) && !isNaN(n)) {
                            return utmToLatLng(e, n);
                        }
                    }
                    return null;
                }).filter(p => p !== null);
            }
        } catch(e) { console.error('WKT Parse Error:', e); }
        return null;
    }

    function initMap() {
        console.log('Initializing Map...');
        if (typeof L === 'undefined') { 
            console.warn('Leaflet not loaded yet, retrying...');
            setTimeout(initMap, 200); 
            return; 
        }

        const mapContainer = document.getElementById('map');
        if (!mapContainer) return;

        try {
            const isDark = document.documentElement.classList.contains('dark');
            const standard = L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { 
                attribution: '&copy; Sibaruki',
                maxZoom: 20
            });
            const satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { 
                attribution: '&copy; Esri',
                maxZoom: 20
            });

            if (map) map.remove();

            map = L.map('map', { 
                zoomControl: false, 
                layers: [standard],
                scrollWheelZoom: true
            }).setView([-5.1245, 120.2536], 13);
            
            L.control.zoom({ position: 'topright' }).addTo(map);

            // Layer Toggle
            const LayerToggle = L.Control.extend({
                onAdd: function(map) {
                    const btn = L.DomUtil.create('button', 'bg-white dark:bg-slate-900 rounded-xl shadow-xl border border-slate-100 dark:border-slate-800 transition-all duration-300 active:scale-90 mt-2 flex items-center justify-center');
                    btn.style.width = '44px'; btn.style.height = '44px'; btn.style.cursor = 'pointer';
                    btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="${isDark ? '#60a5fa' : '#2563eb'}" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>`;
                    
                    L.DomEvent.disableClickPropagation(btn);
                    L.DomEvent.on(btn, 'click', function() {
                        if (map.hasLayer(standard)) { 
                            map.removeLayer(standard); map.addLayer(satellite);
                            btn.style.backgroundColor = '#2563eb';
                            btn.querySelector('svg').setAttribute('stroke', '#ffffff');
                        } else { 
                            map.removeLayer(satellite); map.addLayer(standard);
                            btn.style.backgroundColor = isDark ? '#0f172a' : '#ffffff';
                            btn.querySelector('svg').setAttribute('stroke', isDark ? '#60a5fa' : '#2563eb');
                        }
                    });
                    return btn;
                }
            });
            map.addControl(new LayerToggle({ position: 'topright' }));

            // Draw Paths
            let allPoints = [];
            console.log('Drawing', psuData.length, 'paths...');
            
            if (Array.isArray(psuData)) {
                psuData.forEach(item => {
                    const coords = parseWKT(item.wkt);
                    if (coords && coords.length > 0) {
                        const line = L.polyline(coords, { 
                            color: '#3b82f6', 
                            weight: 5, 
                            opacity: 0.9,
                            lineJoin: 'round'
                        }).addTo(map);

                        line.bindPopup(`
                            <div class="bg-blue-950 text-white p-4 rounded-t-xl">
                                <p class="text-[8px] font-black uppercase tracking-[0.2em] text-blue-400 mb-1">Jaringan Jalan</p>
                                <h5 class="text-xs font-black uppercase leading-tight">${item.nama_jalan || 'Tanpa Nama'}</h5>
                            </div>
                            <div class="p-4 bg-white dark:bg-slate-900 space-y-1 rounded-b-xl">
                                <p class="text-[10px] font-bold text-slate-700 dark:text-slate-300">ID CSV: ${item.id_csv}</p>
                                <p class="text-[10px] font-bold text-slate-700 dark:text-slate-300">Nilai: ${parseFloat(item.jalan).toFixed(2)}</p>
                            </div>
                        `);
                        
                        allPoints = allPoints.concat(coords);
                    }
                });
            }

            if (allPoints.length > 0) {
                map.fitBounds(L.latLngBounds(allPoints), { padding: [50, 50] });
            }

            if (typeof lucide !== 'undefined') lucide.createIcons();
            
            setTimeout(() => { map.invalidateSize(); }, 500);

        } catch(err) { 
            console.error('Leaflet Init Error:', err); 
        }
    }

    function focusOnWkt(wkt) {
        const coords = parseWKT(wkt);
        if (coords) {
            map.fitBounds(L.polyline(coords).getBounds(), { maxZoom: 18, padding: [100, 100] });
            const mc = document.getElementById('main-content');
            if (mc) mc.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }

    function confirmDelete(id, name) {
        customConfirm('Hapus Data Jalan?', `Hapus ruas jalan ${name}?`, 'danger').then(conf => {
            if (conf) { 
                const f = document.getElementById('delete-form'); 
                f.action = `<?= base_url('psu/delete') ?>/${id}`; 
                f.submit(); 
            }
        });
    }

    function submitWithScroll(el) {
        const mc = document.getElementById('main-content');
        if (mc) localStorage.setItem('psuScrollPos', mc.scrollTop);
        const form = el.tagName === 'FORM' ? el : el.form;
        if (form) form.submit();
    }

    document.addEventListener('DOMContentLoaded', () => {
        const mc = document.getElementById('main-content');
        if (mc) {
            const sp = localStorage.getItem('psuScrollPos');
            if (sp) { setTimeout(() => { mc.scrollTop = sp; localStorage.removeItem('psuScrollPos'); }, 150); }
            document.querySelectorAll('nav a').forEach(link => link.addEventListener('click', () => localStorage.setItem('psuScrollPos', mc.scrollTop)));
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
        
        const ok = await window.customConfirm('Hapus Massal?', `Apakah Anda yakin ingin menghapus ${ids.length} ruas jalan yang dipilih? Tindakan ini tidak dapat dibatalkan.`, 'danger');
        
        if (ok) {
            const formData = new FormData();
            ids.forEach(id => formData.append('ids[]', id));
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            try {
                const response = await fetch('<?= base_url('psu/bulk-delete') ?>', {
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
