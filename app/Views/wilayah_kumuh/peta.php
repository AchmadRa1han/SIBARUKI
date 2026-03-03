<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<!-- Leaflet Assets -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-blue-950 dark:text-white uppercase tracking-tight">Peta Wilayah Kumuh</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium">Visualisasi geospasial sebaran kawasan kumuh Sinjai.</p>
        </div>
        <div id="debug-status" class="bg-blue-50 text-blue-600 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm">
            Memuat Peta...
        </div>
    </div>

    <!-- Map Container -->
    <div class="relative group">
        <!-- Decoration Blur -->
        <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-[2.5rem] blur opacity-10 transition duration-1000"></div>
        
        <div class="relative bg-white dark:bg-slate-900 rounded-[2.5rem] overflow-hidden shadow-2xl border border-slate-100 dark:border-slate-800">
            <div id="map" class="w-full h-[75vh] z-10" style="min-height: 500px; background: #f8fafc;"></div>

            <!-- Floating Legend -->
            <div class="absolute bottom-6 left-6 z-[1000] bg-white/90 dark:bg-slate-950/90 backdrop-blur-md p-6 rounded-[2rem] shadow-2xl border border-white/20 dark:border-slate-800 w-64 transition-all hover:scale-105">
                <h4 class="text-[10px] font-black text-blue-950 dark:text-white uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                    <i data-lucide="layers" class="w-3 h-3 text-blue-600"></i>
                    Legenda Kekumuhan
                </h4>
                <div class="space-y-4">
                    <div class="flex items-center gap-4 group">
                        <div class="w-4 h-4 rounded-full bg-[#f43f5e] shadow-lg shadow-rose-500/40 ring-4 ring-rose-500/10"></div>
                        <div class="flex flex-col">
                            <span class="text-[11px] font-bold text-slate-700 dark:text-slate-200 uppercase">Tinggi / Berat</span>
                            <span class="text-[9px] text-slate-400 font-medium">Skor di atas 60</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 group">
                        <div class="w-4 h-4 rounded-full bg-[#f97316] shadow-lg shadow-orange-500/40 ring-4 ring-orange-500/10"></div>
                        <div class="flex flex-col">
                            <span class="text-[11px] font-bold text-slate-700 dark:text-slate-200 uppercase">Sedang</span>
                            <span class="text-[9px] text-slate-400 font-medium">Skor 40 s/d 60</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 group">
                        <div class="w-4 h-4 rounded-full bg-[#f59e0b] shadow-lg shadow-amber-500/40 ring-4 ring-amber-500/10"></div>
                        <div class="flex flex-col">
                            <span class="text-[11px] font-bold text-slate-700 dark:text-slate-200 uppercase">Ringan</span>
                            <span class="text-[9px] text-slate-400 font-medium">Skor di bawah 40</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Floating Info Card -->
            <div class="absolute top-6 right-6 z-[1000] hidden md:block">
                <div class="bg-blue-950 text-white px-5 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-2xl border border-blue-900/50 flex items-center gap-3">
                    <div class="w-2 h-2 bg-emerald-500 rounded-full animate-ping"></div>
                    Data Real-time Terpetakan
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // FUNGSI PARSER WKT MANDIRI
    function parseWktToCoords(wkt) {
        if (!wkt) return null;
        let clean = wkt.replace(/[A-Z]+\s*/g, '');
        const rings = clean.split('),');
        const polygonCoords = [];
        rings.forEach(ring => {
            const coordsString = ring.replace(/[\(\)]/g, '');
            const points = coordsString.split(',');
            const ringCoords = [];
            points.forEach(point => {
                const parts = point.trim().split(/\s+/);
                if (parts.length >= 2) {
                    const lng = parseFloat(parts[0]);
                    const lat = parseFloat(parts[1]);
                    if (!isNaN(lat) && !isNaN(lng)) ringCoords.push([lat, lng]);
                }
            });
            if (ringCoords.length > 0) polygonCoords.push(ringCoords);
        });
        return polygonCoords.length > 0 ? polygonCoords : null;
    }

    function startMap() {
        const debug = document.getElementById('debug-status');
        if (typeof L === 'undefined') {
            debug.innerHTML = "❌ Gagal Memuat Leaflet";
            return;
        }

        try {
            const map = L.map('map', { zoomControl: false }).setView([-5.1245, 120.2536], 13);
            const isDark = document.documentElement.classList.contains('dark');
            const tileUrl = isDark 
                ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png'
                : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png';

            L.tileLayer(tileUrl, { attribution: '&copy; CartoDB' }).addTo(map);
            L.control.zoom({ position: 'topright' }).addTo(map);

            const kumuhData = <?= json_encode($kumuh) ?>;
            const bounds = [];
            let count = 0;

            kumuhData.forEach(item => {
                const coords = parseWktToCoords(item.WKT);
                if (coords) {
                    const color = item.skor_kumuh >= 60 ? '#f43f5e' : (item.skor_kumuh >= 40 ? '#f97316' : '#f59e0b');
                    const polygon = L.polygon(coords, {
                        color: color,
                        weight: 2,
                        fillOpacity: 0.5,
                        fillColor: color
                    }).addTo(map);

                    polygon.bindPopup(`
                        <div class="bg-slate-900 text-white p-4 rounded-t-xl">
                            <p class="text-[9px] font-black uppercase tracking-widest text-blue-400 mb-1">Wilayah Kumuh</p>
                            <h5 class="text-xs font-bold truncate">${item.Kelurahan}</h5>
                        </div>
                        <div class="p-4 bg-white dark:bg-slate-900 space-y-3 rounded-b-xl border-t border-slate-100 dark:border-slate-800">
                            <div class="flex justify-between items-center pb-2 border-b border-slate-50 dark:border-slate-800">
                                <span class="text-[9px] font-bold text-slate-400 uppercase">Kawasan</span>
                                <span class="text-[10px] font-bold text-slate-700 dark:text-slate-300">${item.Kawasan || '-'}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[9px] font-bold text-slate-400 uppercase">Skor Kumuh</span>
                                <span class="px-2 py-0.5 rounded-md bg-blue-50 dark:bg-blue-900/30 text-blue-600 text-[10px] font-black">${item.skor_kumuh}</span>
                            </div>
                            <a href="<?= base_url('wilayah-kumuh/detail/') ?>/${item.FID}" class="block w-full py-2 bg-blue-900 hover:bg-blue-950 text-white text-center text-[9px] font-black uppercase tracking-widest rounded-xl transition-all mt-2">Lihat Detail</a>
                        </div>
                    `);

                    polygon.on('mouseover', function() { this.setStyle({ fillOpacity: 0.8, weight: 3 }); });
                    polygon.on('mouseout', function() { this.setStyle({ fillOpacity: 0.5, weight: 2 }); });

                    bounds.push(polygon.getBounds());
                    count++;
                }
            });

            if (bounds.length > 0) {
                map.fitBounds(L.latLngBounds(bounds));
                debug.innerHTML = "✅ " + count + " Kawasan Terpetakan";
                debug.className = "bg-emerald-50 text-emerald-600 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm";
            } else {
                debug.innerHTML = "⚠️ Data WKT Kosong";
            }
        } catch (err) {
            console.error(err);
            debug.innerHTML = "❌ Error Script Peta";
        }
    }

    window.addEventListener('load', startMap);
    if (document.readyState === 'complete') startMap();
    
    // Re-init Lucide Icons for Legend
    if (typeof lucide !== 'undefined') lucide.createIcons();
</script>

<style>
    /* Styling Popup Modern */
    .leaflet-popup-content-wrapper { border-radius: 1.5rem; padding: 0; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3); border: none; }
    .leaflet-popup-content { margin: 0; width: 260px !important; }
    .leaflet-container a.leaflet-popup-close-button { color: white; padding: 12px; z-index: 100; }
    .leaflet-container { font-family: inherit; }
</style>
<?= $this->endSection() ?>
