<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<!-- Leaflet & WKT Parser Assets -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/wellknown@0.5.0/wellknown.js"></script>

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-blue-950 dark:text-white uppercase tracking-tight">Peta Wilayah Kumuh</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium">Visualisasi geospasial sebaran kawasan kumuh Sinjai.</p>
        </div>
        <div id="debug-status" class="bg-blue-50 text-blue-600 px-4 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest shadow-sm">
            Inisialisasi...
        </div>
    </div>

    <!-- Map Container -->
    <div class="relative group">
        <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl blur opacity-10 transition duration-1000"></div>
        <div class="relative bg-white dark:bg-slate-900 rounded-2xl overflow-hidden shadow-2xl border border-slate-100 dark:border-slate-800">
            <div id="map" class="w-full h-[75vh] z-10" style="min-height: 500px; background: #f8fafc;"></div>

            <!-- Floating Legend -->
            <div class="absolute bottom-6 left-6 z-[1000] bg-white/90 dark:bg-slate-950/90 backdrop-blur-md p-4 rounded-3xl shadow-2xl border border-white/20 dark:border-slate-800 w-52">
                <h4 class="text-[9px] font-bold text-blue-950 dark:text-white uppercase tracking-[0.2em] mb-3 flex items-center gap-2">
                    <i data-lucide="layers" class="w-3 h-3 text-blue-600"></i> Legenda
                </h4>
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-[#f43f5e] shadow-lg"></div>
                        <span class="text-[10px] font-bold text-slate-700 dark:text-slate-300 uppercase">Tinggi (> 60)</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-[#f97316] shadow-lg"></div>
                        <span class="text-[10px] font-bold text-slate-700 dark:text-slate-300 uppercase">Sedang (40-60)</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-[#f59e0b] shadow-lg"></div>
                        <span class="text-[10px] font-bold text-slate-700 dark:text-slate-300 uppercase">Ringan (< 40)</span>
                    </div>
                </div>
            </div>

            <!-- Info Card -->
            <div class="absolute top-6 left-6 z-[1000] hidden md:block">
                <div class="bg-blue-950/80 backdrop-blur-md text-white px-4 py-2.5 rounded-2xl text-[9px] font-bold uppercase tracking-widest shadow-2xl border border-white/10 flex items-center gap-3">
                    <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-ping"></div>
                    Visualisasi Data Terkini
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function startMap() {
        const debug = document.getElementById('debug-status');
        
        // Cek library 'wellknown' (WKT Parser profesional)
        if (typeof wellknown === 'undefined' || typeof L === 'undefined') {
            setTimeout(startMap, 100);
            return;
        }

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

            const map = L.map('map', { 
                zoomControl: false, 
                layers: [cartoDB] 
            }).setView([-5.1245, 120.2536], 14); // Set Fokus Kota Sinjai

            let rot = 0;
            const LayerToggle = L.Control.extend({
                onAdd: function(map) {
                    const btn = L.DomUtil.create('button', 'bg-white dark:bg-slate-900 rounded-lg shadow-xl border border-slate-100 dark:border-slate-800 transition-all duration-300 active:scale-90 mt-2 flex items-center justify-center');
                    btn.style.width = '38px'; btn.style.height = '38px'; btn.style.cursor = 'pointer';
                    const isDark = document.documentElement.classList.contains('dark');
                    const svgColor = isDark ? '#60a5fa' : '#2563eb';
                    btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="${svgColor}" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:block; transition: transform 0.8s cubic-bezier(0.65, 0, 0.35, 1);"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>`;
                    L.DomEvent.disableClickPropagation(btn);
                    L.DomEvent.on(btn, 'click', function(e) {
                        rot += 360;
                        const svg = btn.querySelector('svg');
                        svg.style.transform = `rotate(${rot}deg)`;
                        setTimeout(() => {
                            if (map.hasLayer(cartoDB)) { 
                                map.removeLayer(cartoDB); 
                                map.addLayer(googleSat); 
                                btn.style.backgroundColor = '#2563eb'; 
                                svg.setAttribute('stroke', '#ffffff'); 
                            }
                            else { 
                                map.removeLayer(googleSat); 
                                map.addLayer(cartoDB); 
                                btn.style.backgroundColor = isDark ? '#0f172a' : '#ffffff'; 
                                svg.setAttribute('stroke', svgColor); 
                            }
                        }, 200);
                    });
                    return btn;
                }
            });
            map.addControl(new LayerToggle({ position: 'topright' }));

            if (typeof lucide !== 'undefined') lucide.createIcons();

            L.control.zoom({ position: 'topright' }).addTo(map);

            const kumuhData = <?= json_encode($kumuh) ?>;
            const geoJsonGroup = L.featureGroup().addTo(map);
            let count = 0;

            kumuhData.forEach(item => {
                if (item.WKT) {
                    try {
                        // Gunakan library 'wellknown' untuk parse WKT ke GeoJSON secara presisi
                        const geojson = wellknown.parse(item.WKT);
                        
                        if (geojson) {
                            const color = item.skor_kumuh >= 60 ? '#f43f5e' : (item.skor_kumuh >= 40 ? '#f97316' : '#f59e0b');
                            
                            const layer = L.geoJSON(geojson, {
                                style: {
                                    color: color,
                                    weight: 2,
                                    fillOpacity: 0.5,
                                    fillColor: color
                                }
                            }).addTo(geoJsonGroup);

                            layer.bindPopup(`
                                <div class="bg-slate-900 text-white p-4 rounded-t-xl">
                                    <p class="text-[9px] font-bold uppercase tracking-widest text-blue-400 mb-1">Wilayah Kumuh</p>
                                    <h5 class="text-xs font-bold truncate">${item.Kelurahan}</h5>
                                </div>
                                <div class="p-4 bg-white dark:bg-slate-900 space-y-3 rounded-b-xl border-t border-slate-100 dark:border-slate-800">
                                    <div class="flex justify-between items-center pb-2 border-b border-slate-50 dark:border-slate-800">
                                        <span class="text-[9px] font-bold text-slate-400 uppercase">Kawasan</span>
                                        <span class="text-[10px] font-bold text-slate-700 dark:text-slate-300">${item.Kawasan || '-'}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-[9px] font-bold text-slate-400 uppercase">Skor Kumuh</span>
                                        <span class="px-2 py-0.5 rounded-md bg-blue-50 dark:bg-blue-900/30 text-blue-600 text-[10px] font-bold">${item.skor_kumuh}</span>
                                    </div>
                                    <a href="<?= base_url('wilayah-kumuh/detail/') ?>/${item.FID}" class="block w-full py-2 bg-blue-900 hover:bg-blue-950 text-white text-center text-[9px] font-bold uppercase tracking-widest rounded-xl transition-all mt-2">Lihat Detail</a>
                                </div>
                            `);

                            layer.on('mouseover', function() { this.setStyle({ fillOpacity: 0.8, weight: 3 }); });
                            layer.on('mouseout', function() { this.setStyle({ fillOpacity: 0.5, weight: 2 }); });

                            count++;
                        }
                    } catch (e) {
                        console.warn("Gagal parse WKT FID: " + item.FID);
                    }
                }
            });

            // if (count > 0) {
            //     map.fitBounds(geoJsonGroup.getBounds());
            //     debug.innerHTML = "✅ " + count + " Kawasan Terpetakan";
            //     debug.className = "bg-emerald-50 text-emerald-600 px-4 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest shadow-sm";
            // } else {
            //     debug.innerHTML = "⚠️ Data WKT Tidak Valid";
            // }

            // Tampilan tetap fokus pada Sinjai Utara sesuai setView awal (Zoom 14)
            if (count > 0) {
                debug.innerHTML = "✅ " + count + " Kawasan Terpetakan";
                debug.className = "bg-emerald-50 text-emerald-600 px-4 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest shadow-sm";
            } else {
                debug.innerHTML = "⚠️ Data WKT Tidak Valid";
            }
        } catch (err) {
            console.error(err);
            debug.innerHTML = "❌ Error Script Peta";
        }
    }

    window.addEventListener('load', startMap);
    if (document.readyState === 'complete') startMap();
</script>

<style>
    /* Force custom button shape */
    .custom-layer-control {
        border: none !important;
        background: transparent !important;
        box-shadow: none !important;
    }
    
    .leaflet-popup-content-wrapper { border-radius: 1.5rem; padding: 0; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3); border: none; }
    .leaflet-popup-content { margin: 0; width: 260px !important; }
    .leaflet-container a.leaflet-popup-close-button { color: white; padding: 12px; z-index: 100; }
    .leaflet-container { font-family: inherit; }
</style>
<?= $this->endSection() ?>
