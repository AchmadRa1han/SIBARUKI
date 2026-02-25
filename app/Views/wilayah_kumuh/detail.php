<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<!-- Library Peta -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-fullscreen/dist/leaflet.fullscreen.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-fullscreen/dist/Leaflet.fullscreen.min.js"></script>

<div class="max-w-6xl mx-auto space-y-8 pb-24 text-slate-900">
    
    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-8 rounded-[2rem] border shadow-sm">
        <div class="flex items-center space-x-5">
            <div class="p-4 bg-blue-900 rounded-2xl text-white shadow-lg shadow-blue-900/20">
                <i data-lucide="map" class="w-10 h-10"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-blue-950 tracking-tight">Detail Kawasan Kumuh</h1>
                <div class="flex items-center space-x-3 mt-1">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Feature ID</span>
                    <span class="px-3 py-1 bg-blue-950 text-white rounded-lg font-mono text-sm font-bold shadow-lg shadow-blue-950/20">FID-<?= $kumuh['FID'] ?></span>
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="<?= base_url('wilayah-kumuh') ?>" class="px-5 py-3 bg-white border-2 border-blue-900/10 text-blue-900 rounded-2xl text-sm font-bold hover:bg-slate-50 transition-all flex items-center shadow-sm">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Kembali
            </a>
            <a href="<?= base_url('wilayah-kumuh/edit/' . $kumuh['FID']) ?>" class="px-8 py-3 bg-blue-900 text-white rounded-2xl text-sm font-bold hover:bg-blue-950 shadow-xl shadow-blue-900/30 transition-all flex items-center">
                <i data-lucide="edit-3" class="w-4 h-4 mr-2"></i> Edit Lokasi
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- KOLOM KIRI: SKOR & LEGALITAS -->
        <div class="lg:col-span-1 space-y-8">
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b bg-slate-50/50 flex items-center space-x-3 text-blue-950">
                    <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                    <h3 class="font-bold uppercase tracking-widest text-xs">Penilaian Kumuh</h3>
                </div>
                <div class="p-8">
                    <div class="text-center p-8 bg-blue-50/30 rounded-[2.5rem] border-2 border-dashed border-blue-900/10">
                        <p class="text-[10px] font-black text-blue-900 uppercase tracking-[0.2em] mb-2 opacity-60">Total Skor Kumuh</p>
                        <p class="text-6xl font-black text-blue-950"><?= number_format($kumuh['skor_kumuh'] ?? 0, 2) ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-blue-950 rounded-[2.5rem] p-8 text-white shadow-2xl relative overflow-hidden">
                <i data-lucide="database" class="w-32 h-32 absolute -right-8 -bottom-8 opacity-10 rotate-12"></i>
                <h4 class="text-[10px] font-black text-blue-300 uppercase tracking-[0.2em] mb-6 relative z-10">Legalitas & Sumber</h4>
                <div class="space-y-6 relative z-10">
                    <div>
                        <label class="text-[9px] font-bold text-blue-400 uppercase block mb-1 tracking-wider">Nomor SK Kumuh</label>
                        <p class="text-sm font-bold leading-tight"><?= $kumuh['Sk_Kumuh'] ?: 'Tidak Tersedia' ?></p>
                    </div>
                    <div>
                        <label class="text-[9px] font-bold text-blue-400 uppercase block mb-1 tracking-wider">Sumber Data</label>
                        <p class="text-sm font-medium text-blue-100 italic"><?= $kumuh['Sumber_data'] ?: '-' ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: IDENTITAS WILAYAH & PETA -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b bg-slate-50/50 flex items-center space-x-3 text-blue-950">
                    <i data-lucide="navigation" class="w-5 h-5"></i>
                    <h3 class="font-bold uppercase tracking-widest text-xs">Identitas Administratif & Wilayah</h3>
                </div>
                <div class="p-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-y-10 gap-x-8">
                    <?php 
                        $fields = [
                            'Provinsi' => $kumuh['Provinsi'],
                            'Kabupaten / Kota' => $kumuh['Kab_Kota'],
                            'Kecamatan' => $kumuh['Kecamatan'],
                            'Kelurahan / Desa' => $kumuh['Kelurahan'],
                            'RT / RW' => $kumuh['Kode_RT_RW'],
                            'Nama Kawasan' => $kumuh['Kawasan'],
                            'Luas Kawasan' => ($kumuh['Luas_kumuh'] ?? '0') . ' Ha'
                        ];
                        foreach($fields as $label => $val):
                    ?>
                    <div>
                        <p class="text-[10px] font-black text-blue-900 uppercase mb-2 tracking-widest opacity-80"><?= $label ?></p>
                        <p class="text-sm font-bold text-slate-800 italic"><?= $val ?? '-' ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- VISUALISASI PETA -->
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b bg-slate-50/50 flex items-center justify-between text-blue-950">
                    <h3 class="font-bold uppercase tracking-widest text-xs flex items-center">
                        <i data-lucide="map-pin" class="w-4 h-4 mr-2"></i> Visualisasi Peta GIS
                    </h3>
                    <div id="map-status" class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded text-[8px] font-black uppercase tracking-widest border border-blue-100">Ready</div>
                </div>
                <div class="p-2">
                    <div id="map" class="w-full h-[550px] rounded-[2rem] z-0 border border-slate-100 bg-slate-50"></div>
                </div>
                <div class="p-6 bg-slate-50/50 flex justify-between items-center text-[10px]">
                    <span class="text-slate-400 italic">Gunakan layer control di kanan atas untuk beralih ke tampilan Satelit.</span>
                    <button onclick="toggleWKT()" class="text-blue-900 font-black hover:underline uppercase tracking-widest">Lihat Data Teks</button>
                </div>
                <div id="wkt-box" class="hidden p-6 bg-slate-100 border-t text-[9px] font-mono text-slate-500 break-all leading-relaxed">
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
            const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' });
            const satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Tiles &copy; Esri'
            });

            const map = L.map('map', { center: [0, 0], zoom: 13, layers: [osm], fullscreenControl: true });
            L.control.layers({ "Peta Jalan": osm, "Satelit": satellite }).addTo(map);

            if (coords.length > 2) {
                const polygon = L.polygon(coords, { color: '#1e3a8a', weight: 4, fillColor: '#3b82f6', fillOpacity: 0.3 }).addTo(map);
                map.fitBounds(polygon.getBounds());
                statusEl.innerText = "WILAYAH TERVERIFIKASI";
            } else if (coords.length > 0) {
                L.marker(coords[0]).addTo(map);
                map.setView(coords[0], 17);
                statusEl.innerText = "HANYA TITIK";
            }
        } catch (e) {
            statusEl.innerText = "ERROR GEOMETRI";
        }
    });
</script>
<?= $this->endSection() ?>
