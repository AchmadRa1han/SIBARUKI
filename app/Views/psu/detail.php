<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<!-- Leaflet Assets -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="max-w-7xl mx-auto space-y-6 pb-24 text-slate-900 dark:text-slate-200">
    
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-3 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 no-print">
        <a href="<?= base_url('dashboard') ?>" class="hover:text-blue-600 transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <a href="<?= base_url('psu') ?>" class="hover:text-blue-600 transition-colors">PSU Jalan</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">Detail Aset</span>
    </nav>

    <!-- Header Action -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white dark:bg-slate-900 p-8 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm transition-all duration-300 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-48 h-48 bg-blue-600/5 rounded-full -mr-24 -mt-24 blur-3xl"></div>
        <div class="relative z-10 flex items-center gap-4">
            <a href="<?= base_url('psu') ?>" class="p-3 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl hover:bg-blue-600 hover:text-white transition-all active:scale-95 no-print" title="Kembali">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-blue-950 dark:text-white uppercase tracking-tighter leading-tight"><?= $jalan['nama_jalan'] ?></h1>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-[0.2em] mt-1">ID Aset: #<?= $jalan['id'] ?> | Terdata pada <?= date('d/m/Y', strtotime($jalan['created_at'])) ?></p>
            </div>
        </div>
        <div class="flex items-center gap-2 relative z-10 no-print">
            <?php if (has_permission('edit_psu')) : ?>
            <a href="<?= base_url('psu/edit/' . $jalan['id']) ?>" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-indigo-700 transition-all active:scale-95 flex items-center gap-2 shadow-lg shadow-indigo-600/20">
                <i data-lucide="edit-3" class="w-4 h-4"></i> Edit
            </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-8 shadow-sm border border-slate-100 dark:border-slate-800 relative overflow-hidden transition-all duration-300">
                <h3 class="text-[10px] font-bold text-blue-950 dark:text-white uppercase tracking-[0.3em] mb-8 flex items-center gap-3">
                    <span class="w-8 h-[2px] bg-blue-600"></span> Informasi Jaringan Jalan
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-10 relative z-10">
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-2">Tahun Pembangunan</p>
                        <p class="text-sm font-bold text-slate-800 dark:text-slate-100 uppercase tracking-wider"><?= $jalan['tahun'] ?: '-' ?></p>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-2">Panjang / Luas Capaian</p>
                        <p class="text-sm font-bold text-blue-600 uppercase tracking-wider"><?= number_format($jalan['panjang_luas'], 2, ',', '.') ?> Meter</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-2">Keterangan Wilayah / Lokasi</p>
                        <p class="text-sm font-bold text-slate-800 dark:text-slate-100 uppercase tracking-wider leading-relaxed"><?= $jalan['jalan'] ?></p>
                    </div>
                </div>
            </div>

            <!-- Dokumentasi Section -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-8 shadow-sm border border-slate-100 dark:border-slate-800 transition-all duration-300">
                <h3 class="text-[10px] font-bold text-blue-950 dark:text-white uppercase tracking-[0.3em] mb-8 flex items-center gap-3">
                    <span class="w-8 h-[2px] bg-emerald-500"></span> Dokumentasi Pekerjaan
                </h3>
                
                <div class="space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Before -->
                        <div class="space-y-4">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] flex items-center gap-2">
                                <i data-lucide="history" class="w-3.5 h-3.5"></i> Kondisi 0% (Before)
                            </p>
                            <div class="aspect-video rounded-2xl overflow-hidden bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 relative group">
                                <?php if($jalan['foto_before']): ?>
                                    <img src="<?= base_url('uploads/psu/' . $jalan['foto_before']) ?>" class="w-full h-full object-cover transition-transform group-hover:scale-110">
                                    <div class="absolute inset-0 bg-blue-950/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <a href="<?= base_url('uploads/psu/' . $jalan['foto_before']) ?>" target="_blank" class="p-3 bg-white/20 backdrop-blur-md rounded-full text-white"><i data-lucide="maximize" class="w-5 h-5"></i></a>
                                    </div>
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center"><i data-lucide="image-off" class="w-6 h-6 text-slate-300"></i></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- After -->
                        <div class="space-y-4">
                            <p class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em] flex items-center gap-2">
                                <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> Kondisi 100% (After)
                            </p>
                            <div class="aspect-video rounded-2xl overflow-hidden bg-slate-50 dark:bg-slate-950 border border-emerald-100 dark:border-emerald-900/30 relative group">
                                <?php if($jalan['foto_after']): ?>
                                    <img src="<?= base_url('uploads/psu/' . $jalan['foto_after']) ?>" class="w-full h-full object-cover transition-transform group-hover:scale-105">
                                    <div class="absolute inset-0 bg-emerald-950/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <a href="<?= base_url('uploads/psu/' . $jalan['foto_after']) ?>" target="_blank" class="p-3 bg-white/20 backdrop-blur-md rounded-full text-white"><i data-lucide="maximize" class="w-5 h-5"></i></a>
                                    </div>
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center"><i data-lucide="image-off" class="w-6 h-6 text-slate-300"></i></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-slate-900 rounded-2xl overflow-hidden shadow-sm border border-slate-100 dark:border-slate-800 aspect-square relative group">
                <div id="map-detail" class="w-full h-full z-10"></div>
                <div class="absolute top-4 left-4 z-[1000] bg-blue-950/80 backdrop-blur-md px-3 py-1.5 rounded-xl border border-white/10 shadow-2xl text-[8px] font-bold uppercase tracking-widest text-white flex items-center gap-2">
                    <div class="w-1 h-1 bg-blue-400 rounded-full animate-pulse"></div>
                    Lokasi Aset
                </div>
            </div>

            <div class="bg-blue-950 rounded-2xl p-8 text-white shadow-xl relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-700">
                    <i data-lucide="route" class="w-40 h-40 text-white"></i>
                </div>
                <h4 class="text-[9px] font-bold uppercase tracking-[0.3em] text-blue-400 mb-6 flex items-center gap-2">
                    Validasi Sistem
                </h4>
                <div class="space-y-4 relative z-10">
                    <div class="flex justify-between items-center text-[9px]">
                        <span class="font-bold text-blue-300/60 uppercase tracking-widest">Waktu Input</span>
                        <span class="font-bold text-blue-50 tracking-wider"><?= date('d/m/Y H:i', strtotime($jalan['created_at'])) ?></span>
                    </div>
                    <div class="flex justify-between items-center text-[9px]">
                        <span class="font-bold text-blue-300/60 uppercase tracking-widest">Status Data</span>
                        <span class="px-2 py-0.5 bg-blue-500 text-white rounded-md font-bold">VERIFIED</span>
                    </div>
                </div>
            </div>
        </div>
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
        const wkt = "<?= $jalan['wkt'] ?>";
        if (!wkt) return;

        let geojson = null;
        try {
            geojson = wellknown.parse(wkt);
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
            }
        } catch(e) {}

        if (!geojson) return;

        // Determine center from first coordinate
        let center;
        if (geojson.type === 'Point') {
            center = [geojson.coordinates[1], geojson.coordinates[0]];
        } else {
            // Simple center for Linestring/Polygon
            const first = Array.isArray(geojson.coordinates[0]) ? geojson.coordinates[0] : geojson.coordinates;
            center = [first[1], first[0]];
        }

        const map = L.map('map-detail', { zoomControl: false }).setView(center, 16);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        L.control.zoom({ position: 'bottomright' }).addTo(map);

        const icon = L.divIcon({
            className: 'custom-marker',
            html: `<div class="w-6 h-6 bg-blue-600 rounded-full border-4 border-white shadow-xl"></div>`,
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        });

        L.geoJSON(geojson, {
            style: { color: '#2563eb', weight: 5, opacity: 0.8 },
            pointToLayer: (feature, latlng) => L.marker(latlng, { icon: icon })
        }).addTo(map);
        
        // Auto fit bounds if it's not a point
        if (geojson.type !== 'Point') {
            map.fitBounds(L.geoJSON(geojson).getBounds(), { padding: [50, 50] });
        }
    }

    window.addEventListener('load', () => {
        lucide.createIcons();
        initMap();
    });
</script>
<?= $this->endSection() ?>
