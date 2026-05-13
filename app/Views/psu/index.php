<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<!-- Leaflet Assets -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
<script src="https://cdn.jsdelivr.net/npm/wellknown@0.5.0/wellknown.js"></script>

<div class="space-y-6 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm relative overflow-hidden transition-all duration-300">
        <div class="absolute top-0 right-0 w-48 h-48 bg-blue-600/5 rounded-full -mr-24 -mt-24 blur-3xl"></div>
        <div class="relative z-10 flex items-center gap-4">
            <a href="<?= base_url('dashboard') ?>" class="p-3 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl hover:bg-blue-600 hover:text-white transition-all active:scale-95" title="Kembali">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-blue-950 dark:text-white uppercase tracking-tighter">PSU Jaringan Jalan</h1>
                <p class="text-slate-500 dark:text-slate-400 font-medium text-xs mt-1">Monitoring Prasarana, Sarana, dan Utilitas (Jalan) Kabupaten Sinjai.</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2 relative z-10">
            <div class="bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 px-4 py-2 rounded-xl text-[9px] font-bold uppercase tracking-widest border border-blue-100 dark:border-blue-800/50 shadow-sm">
                <?= number_format($total_panjang, 2, ',', '.') ?> m Terdata
            </div>
            <a href="<?= base_url('psu/export-excel') ?>" class="bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 px-4 py-2 rounded-xl text-[9px] font-bold uppercase tracking-widest border border-emerald-100 dark:border-emerald-800/50 hover:bg-emerald-600 hover:text-white transition-all active:scale-95 flex items-center gap-2">
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
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] overflow-hidden shadow-md border border-slate-100 dark:border-slate-800">
            <div id="map" class="w-full h-[450px] z-10" style="background: #ececec;"></div>
            <div class="absolute top-6 left-6 z-[1000] hidden md:block">
                <div class="bg-blue-950/80 backdrop-blur-md text-white px-4 py-2 rounded-xl text-[9px] font-bold uppercase tracking-[0.2em] shadow-2xl border border-white/10 flex items-center gap-3">
                    <div class="w-1.5 h-1.5 bg-blue-400 rounded-full animate-ping"></div>
                    Pemetaan Jaringan Jalan
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Bar (Identical to RTLH) -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 p-3">
        <div class="flex flex-col lg:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-3 w-full lg:w-auto">
                <div class="flex items-center gap-2 px-3 py-2 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-100 dark:border-slate-700">
                    <i data-lucide="database" class="w-3.5 h-3.5 text-slate-400"></i>
                    <span class="text-[9px] font-bold uppercase tracking-widest text-slate-500"><?= $total_jalan ?> Titik Data</span>
                </div>
                <?php if (has_permission('create_psu')): ?>
                <button onclick="document.getElementById('csv_file').click()" class="bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-600 px-4 py-2 rounded-xl text-[9px] font-bold uppercase tracking-widest transition-all active:scale-95 flex items-center gap-2 border border-emerald-500/20">
                    <i data-lucide="file-up" class="w-3.5 h-3.5"></i> Import CSV
                </button>
                <form id="import-form" action="<?= base_url('psu/import-csv') ?>" method="POST" enctype="multipart/form-data" class="hidden">
                    <?= csrf_field() ?>
                    <input type="file" id="csv_file" name="csv_file" accept=".csv" onchange="document.getElementById('import-form').submit()">
                </form>
                <?php endif; ?>
            </div>

            <form action="<?= base_url('psu') ?>" method="get" class="flex flex-col md:flex-row items-center gap-2 w-full lg:w-auto">
                <div class="relative w-full md:w-28">
                    <select name="per_page" onchange="this.form.submit()" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-[9px] font-bold uppercase px-3 py-2 focus:ring-2 focus:ring-blue-500 cursor-pointer appearance-none">
                        <?php foreach([5, 10, 25, 50, 100] as $p): ?>
                            <option value="<?= $p ?>" <?= ($perPage ?? 10) == $p ? 'selected' : '' ?>><?= $p ?> Baris</option>
                        <?php endforeach; ?>
                    </select>
                    <i data-lucide="chevron-down" class="w-3 h-3 text-slate-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                </div>
                <div class="relative w-full md:w-64">
                    <input type="text" name="keyword" value="<?= $keyword ?? '' ?>" placeholder="Cari Nama Jalan / Tahun..." class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-[9px] font-bold uppercase px-3 py-2 pl-10 focus:ring-2 focus:ring-blue-500 transition-all">
                    <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Section (Identical to RTLH) -->
    <div class="bg-white dark:bg-slate-900 rounded-[2rem] shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden relative transition-all duration-300">
        <div class="p-6 border-b border-slate-50 dark:border-slate-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-600/20">
                    <i data-lucide="route" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-blue-950 dark:text-white uppercase tracking-tight">Daftar PSU Jaringan Jalan</h3>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-[0.2em]">Data Prasarana Terintegrasi Spasial</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse table-fixed">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/50 text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                        <th class="px-6 py-4 w-16 text-center">No</th>
                        <th class="px-4 py-4 w-64">Nama Jaringan Jalan</th>
                        <th class="px-4 py-4 w-28 text-center">Tahun</th>
                        <th class="px-4 py-4 w-40">Panjang / Luas</th>
                        <th class="px-6 py-4 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800 text-[10px]">
                    <?php if(!empty($jalan)): $no = 1 + (($pager->getCurrentPage() - 1) * $perPage); foreach($jalan as $item): ?>
                    <tr class="group hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-all duration-200">
                        <td class="px-6 py-3 text-center font-bold text-slate-400"><?= $no++ ?></td>
                        <td class="px-4 py-3">
                            <span class="font-bold text-blue-950 dark:text-white uppercase truncate block text-xs mb-0.5"><?= $item['nama_jalan'] ?></span>
                            <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest"><?= $item['jalan'] ?></span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2.5 py-0.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg font-bold uppercase text-[8px] border border-blue-100 dark:border-blue-900 tracking-wider"><?= $item['tahun'] ?: '-' ?></span>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-sm font-black text-slate-700 dark:text-slate-200 italic"><?= number_format($item['panjang_luas'], 2, ',', '.') ?> <span class="text-[8px] ml-0.5 opacity-50 not-italic uppercase font-bold">Meter</span></p>
                        </td>
                        <td class="px-6 py-3 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="<?= base_url('psu/detail/' . $item['id']) ?>" class="p-2 bg-blue-950 dark:bg-blue-600 text-white rounded-lg shadow-md hover:scale-110 transition-all active:scale-95" title="Detail">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                </a>
                                <?php if (has_permission('edit_psu')): ?>
                                <a href="<?= base_url('psu/edit/' . $item['id']) ?>" class="p-2 bg-white dark:bg-slate-800 text-blue-600 rounded-lg shadow-sm border border-slate-100 dark:border-slate-700 hover:bg-amber-500 hover:text-white transition-all active:scale-95" title="Edit">
                                    <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                </a>
                                <button onclick="confirmDelete(<?= $item['id'] ?>)" class="p-2 bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 rounded-lg hover:bg-rose-600 hover:text-white transition-all active:scale-95" title="Hapus">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                </button>
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
        
        <?php if ($pager) : ?>
        <div class="p-6 bg-slate-50/50 dark:bg-slate-800/50 flex justify-center border-t border-slate-100 dark:border-slate-800">
            <?= $pager->links('default', 'tailwind_full') ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
    function utmToLatLng(easting, northing) {
        const a = 6378137, f = 1 / 298.257223563;
        const b = a * (1 - f), e = Math.sqrt(1 - (b * b) / (a * a)), e1sq = (e * e) / (1 - e * e);
        const k0 = 0.9996, falseEasting = 500000, falseNorthing = 10000000;
        const zoneCentralMeridian = 123 * (Math.PI / 180); 
        let x = easting - falseEasting, y = northing - falseNorthing;
        let M = y / k0, mu = M / (a * (1 - e * e / 4 - 3 * e * e * e * e / 64 - 5 * e * e * e * e * e * e / 256));
        let phi1Rad = mu + (3 * e1sq / 2 - 27 * e1sq * e1sq * e1sq / 32) * Math.sin(2 * mu) + (21 * e1sq * e1sq / 16 - 55 * e1sq * e1sq * e1sq / 32) * Math.sin(4 * mu) + (151 * e1sq * e1sq / 96) * Math.sin(6 * mu);
        let N1 = a / Math.sqrt(1 - e * e * Math.sin(phi1Rad) * Math.sin(phi1Rad)), T1 = Math.tan(phi1Rad) * Math.tan(phi1Rad), C1 = e1sq * Math.cos(phi1Rad) * Math.cos(phi1Rad), R1 = a * (1 - e * e) / Math.pow(1 - e * e * Math.sin(phi1Rad) * Math.sin(phi1Rad), 1.5);
        let D = x / (N1 * k0);
        let lat = phi1Rad - (N1 * Math.tan(phi1Rad) / R1) * (D * D / 2 - (5 + 3 * T1 + 10 * C1 - 4 * C1 * C1 - 9 * e1sq) * D * D * D * D / 24 + (61 + 90 * T1 + 298 * C1 + 45 * T1 * T1 - 252 * e1sq - 3 * C1 * C1) * D * D * D * D * D * D / 720);
        let lon = zoneCentralMeridian + (D - (1 + 2 * T1 + C1) * D * D * D / 6 + (5 - 2 * C1 + 28 * T1 - 3 * C1 * C1 + 8 * e1sq + 24 * T1 * T1) * D * D * D * D * D / 120) / Math.cos(phi1Rad);
        return [lat * (180 / Math.PI), lon * (180 / Math.PI)];
    }

    function initMap() {
        if (typeof L === 'undefined' || typeof L.markerClusterGroup === 'undefined' || typeof wellknown === 'undefined') { 
            setTimeout(initMap, 200); 
            return; 
        }

        const jalanData = <?= json_encode($jalan_all ?? []) ?>;
        const isDark = document.documentElement.classList.contains('dark');
        const cartoDB = L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png');
        
        const map = L.map('map', { zoomControl: false, layers: [cartoDB] }).setView([-5.1245, 120.2536], 11);
        L.control.zoom({ position: 'topright' }).addTo(map);

        clusterGroup = L.markerClusterGroup({ showCoverageOnHover: false, maxClusterRadius: 50 }).addTo(map);
        const activeDataGroup = L.featureGroup().addTo(map);

        const dotIcon = (color) => L.divIcon({
            className: 'custom-div-icon',
            html: `<div class="w-6 h-6 rounded-full border-4 border-white shadow-xl flex items-center justify-center" style="background-color: ${color};"><div class="w-1 h-1 bg-white rounded-full"></div></div>`,
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        });

        jalanData.forEach(item => {
            try {
                if (item.wkt) {
                    let geojson = wellknown.parse(item.wkt);
                    if (geojson) {
                        const convert = (c) => {
                            if (typeof c[0] === 'number') {
                                if (Math.abs(c[0]) > 500) {
                                    const [la, lo] = utmToLatLng(c[0], c[1]);
                                    return [lo, la];
                                }
                                return c;
                            }
                            return c.map(convert);
                        };
                        geojson.coordinates = convert(geojson.coordinates);

                        L.geoJSON(geojson, {
                            style: { color: '#2563eb', weight: 4, opacity: 0.8 },
                            pointToLayer: (feature, latlng) => L.marker(latlng, { icon: dotIcon('#2563eb') })
                        }).bindPopup(`<div class="p-3">
                            <h5 class="text-[10px] font-bold uppercase mb-1">${item.nama_jalan}</h5>
                            <p class="text-[8px] text-slate-500 uppercase font-bold mb-2">Tahun: ${item.tahun || '-'}</p>
                            <a href="<?= base_url('psu/detail') ?>/${item.id}" class="block w-full py-2.5 bg-blue-950 hover:bg-blue-800 text-white text-center text-[10px] font-black uppercase tracking-[0.2em] rounded-xl shadow-xl transition-all">Detail</a>
                        </div>`).addTo(activeDataGroup);
                    }
                }
            } catch(e) {}
        });

        if (activeDataGroup.getLayers().length > 0) {
            map.fitBounds(activeDataGroup.getBounds(), { padding: [30, 30] });
        }
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function confirmDelete(id) {
        if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `<?= base_url('psu/delete') ?>/${id}`;
            const csrf = document.createElement('input');
            csrf.type = 'hidden'; csrf.name = '<?= csrf_token() ?>'; csrf.value = '<?= csrf_hash() ?>';
            form.appendChild(csrf);
            document.body.appendChild(form);
            form.submit();
        }
    }

    window.addEventListener('load', () => {
        lucide.createIcons();
        initMap();
    });
</script>

<style>
    .leaflet-popup-content-wrapper { border-radius: 1rem; padding: 0; overflow: hidden; box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.2); border: none; }
    .leaflet-popup-content { margin: 0; width: 200px !important; }
    .leaflet-container { font-family: inherit; }
    .marker-cluster-small div, .marker-cluster-medium div, .marker-cluster-large div { background-color: rgba(30, 27, 75, 0.9); color: white; font-weight: 900; font-size: 10px; }
</style>
<?= $this->endSection() ?>
