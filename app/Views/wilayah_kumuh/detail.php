<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<!-- Library Peta -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="space-y-10 pb-12">
    <!-- Tombol Navigasi & Judul -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-blue-950 dark:text-white uppercase tracking-tight">Detail Wilayah Kumuh</h1>
            <p class="text-slate-400 dark:text-slate-500 text-sm font-medium italic mt-1">Laporan teknis kondisi kekumuhan tingkat kelurahan/desa.</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="<?= base_url('wilayah-kumuh') ?>" class="px-5 py-3 bg-white dark:bg-slate-800 border-2 border-blue-900/10 dark:border-slate-700 text-blue-900 dark:text-blue-400 rounded-2xl text-sm font-bold hover:bg-slate-50 dark:hover:bg-slate-700 transition-all flex items-center shadow-sm">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Kembali
            </a>
            <?php if (has_permission('export_data')) : ?>
            <button onclick="window.print()" class="px-5 py-3 bg-white dark:bg-slate-800 border-2 border-blue-900/10 dark:border-slate-700 text-slate-600 dark:text-slate-300 rounded-2xl text-sm font-bold hover:bg-slate-50 dark:hover:bg-slate-700 transition-all flex items-center shadow-sm">
                <i data-lucide="printer" class="w-4 h-4 mr-2"></i> Cetak
            </button>
            <?php endif; ?>
            <?php if (has_permission('edit_kumuh')) : ?>
            <a href="<?= base_url('wilayah-kumuh/edit/' . $kumuh['FID']) ?>" class="px-8 py-3 bg-blue-900 dark:bg-blue-700 text-white rounded-2xl text-sm font-bold hover:bg-blue-950 dark:hover:bg-blue-600 shadow-xl shadow-blue-900/30 transition-all flex items-center">
                <i data-lucide="edit-3" class="w-4 h-4 mr-2"></i> Edit Lokasi
            </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Panel Informasi Utama -->
        <div class="lg:col-span-1 space-y-8">
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm transition-colors duration-300">
                <h3 class="text-blue-900 dark:text-blue-400 font-black uppercase text-[10px] tracking-widest mb-8 border-b dark:border-slate-800 pb-4">Identitas Wilayah</h3>
                <div class="space-y-6">
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase mb-1">Kelurahan / Desa</p>
                        <p class="text-lg font-black text-slate-800 dark:text-white"><?= $kumuh['Kelurahan'] ?></p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase mb-1">Kecamatan</p>
                        <p class="text-md font-bold text-slate-700 dark:text-slate-300"><?= $kumuh['Kecamatan'] ?></p>
                    </div>
                    <div class="pt-4 grid grid-cols-2 gap-4">
                        <div class="p-4 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800">
                            <p class="text-[8px] font-black text-slate-400 uppercase mb-1">RT / RW</p>
                            <p class="text-sm font-black text-blue-900 dark:text-blue-400"><?= $kumuh['Kode_RT_RW'] ?></p>
                        </div>
                        <div class="p-4 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800">
                            <p class="text-[8px] font-black text-slate-400 uppercase mb-1">Luas (Ha)</p>
                            <p class="text-sm font-black text-blue-900 dark:text-blue-400"><?= number_format($kumuh['Luas_kumuh'], 2) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel Statistik Skor -->
            <div class="bg-rose-50 dark:bg-rose-950/20 p-8 rounded-[2.5rem] border border-rose-100 dark:border-rose-900 shadow-sm transition-colors duration-300">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-rose-700 dark:text-rose-400 font-black uppercase text-[10px] tracking-widest">Skor Kekumuhan</h3>
                    <i data-lucide="trending-up" class="w-5 h-5 text-rose-400"></i>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-6xl font-black text-rose-600"><?= $kumuh['skor_kumuh'] ?></span>
                    <span class="text-xs font-bold text-rose-400 uppercase">Poin</span>
                </div>
                <p class="mt-4 text-[10px] text-rose-700/60 dark:text-rose-400/60 font-medium leading-relaxed italic">Semakin tinggi skor, semakin mendesak penanganan kawasan.</p>
            </div>
        </div>

        <!-- Panel Visual Peta -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm transition-colors duration-300 relative overflow-hidden group">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-blue-900 dark:text-blue-400 font-black uppercase text-[10px] tracking-widest flex items-center">
                        <i data-lucide="map" class="w-4 h-4 mr-2"></i> Nama Kawasan
                    </h3>
                    <span class="px-3 py-1 bg-blue-50 dark:bg-blue-950 text-blue-700 dark:text-blue-400 rounded-full text-[9px] font-black uppercase tracking-tighter border border-blue-100 dark:border-blue-900"><?= $kumuh['Kawasan'] ?></span>
                </div>
                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 leading-relaxed italic">"Kawasan ini teridentifikasi sebagai titik pantau kekumuhan berdasarkan survei infrastruktur dan sanitasi lingkungan."</p>
            </div>

            <!-- VISUALISASI PETA -->
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-300">
                <div class="p-6 border-b dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 flex items-center justify-between text-blue-950 dark:text-blue-400">
                    <h3 class="font-bold uppercase tracking-widest text-xs flex items-center">
                        <i data-lucide="map-pin" class="w-4 h-4 mr-2"></i> Visualisasi Peta GIS
                    </h3>
                    <div id="map-status" class="px-2 py-0.5 bg-blue-50 dark:bg-blue-950/30 text-blue-700 dark:text-blue-400 rounded text-[8px] font-black uppercase tracking-widest border border-blue-100 dark:border-blue-900 transition-colors duration-300">Ready</div>
                </div>
                <div class="p-2">
                    <div id="map" class="w-full h-[550px] rounded-[2rem] z-0 border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 transition-colors duration-300"></div>
                </div>
                <div class="p-6 bg-slate-50/50 dark:bg-slate-950/50 flex justify-between items-center text-[10px]">
                    <span class="text-slate-400 dark:text-slate-500 italic">Gunakan layer control di kanan atas untuk beralih ke tampilan Satelit.</span>
                    <button onclick="toggleWKT()" class="text-blue-900 dark:text-blue-400 font-black hover:underline uppercase tracking-widest">Lihat Data Teks</button>
                </div>
                <div id="wkt-box" class="hidden p-6 bg-slate-100 dark:bg-slate-950 border-t dark:border-slate-800 text-[9px] font-mono text-slate-500 dark:text-slate-600 break-all leading-relaxed">
                    <?= $kumuh['WKT'] ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
    function toggleWKT() { document.getElementById('wkt-box').classList.toggle('hidden'); }

    document.addEventListener('DOMContentLoaded', function() {
        const rawWkt = <?= json_encode($kumuh['WKT']) ?>;
        const statusEl = document.getElementById('map-status');

        if (!rawWkt || rawWkt.trim() === '') {
            statusEl.innerText = "DATA KOSONG";
            return;
        }

        function extractCoordinates(text) {
            const regex = /(-?\d+\.\d+)\s+(-?\d+\.\d+)/g;
            let match;
            const points = [];
            while ((match = regex.exec(text)) !== null) {
                points.push([parseFloat(match[2]), parseFloat(match[1])]);
            }
            return points;
        }

        try {
            const coords = extractCoordinates(rawWkt);
            
            if (coords.length > 0) {
                // Define Layers
                const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap'
                });

                const satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                    attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EBP, and the GIS User Community'
                });

                const map = L.map('map', { 
                    attributionControl: false,
                    layers: [osm] // Default
                }).setView(coords[0], 17);

                const baseMaps = {
                    "Peta Standar": osm,
                    "Satelit": satellite
                };
                
                L.control.layers(baseMaps, null, { position: 'topright' }).addTo(map);

                if (coords.length > 1) {
                    const polygon = L.polygon(coords, { color: '#1e3a8a', weight: 3, fillOpacity: 0.2 }).addTo(map);
                    map.fitBounds(polygon.getBounds());
                    statusEl.innerText = "POLYGON";
                } else {
                    L.marker(coords[0]).addTo(map);
                    map.setView(coords[0], 17);
                    statusEl.innerText = "HANYA TITIK";
                }
            }
        } catch (e) {
            statusEl.innerText = "ERROR GEOMETRI";
        }
    });
</script>

<style>
    @media print {
        header, aside, .no-print, .shadow-sm, .shadow-xl { display: none !important; }
        .lg\:col-span-2, .lg\:col-span-1 { width: 100% !important; display: block !important; }
        body { background: white !important; }
        #map { height: 400px !important; border: 1px solid #ddd !important; }
    }
</style>
<?= $this->endSection() ?>
