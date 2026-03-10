<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-5xl mx-auto">
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-300">
        <div class="p-8 border-b dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-black text-blue-950 dark:text-white uppercase tracking-wider">Edit Wilayah Kumuh</h1>
                <p class="text-sm text-slate-400 dark:text-slate-500 font-medium italic">Perbarui informasi data spasial dan statistik kawasan</p>
            </div>
            <a href="<?= base_url('wilayah-kumuh') ?>" class="text-slate-400 dark:text-slate-600 hover:text-rose-500 transition-colors">
                <i data-lucide="x-circle" class="w-8 h-8"></i>
            </a>
        </div>

        <form action="<?= base_url('wilayah-kumuh/update/' . $kumuh['FID']) ?>" method="post" class="p-10">
            <?= csrf_field() ?>
            
            <div class="space-y-12">
                <!-- Bagian 1: Identitas Wilayah -->
                <div>
                    <h3 class="text-[10px] font-black text-amber-600 dark:text-amber-500 uppercase tracking-[0.3em] mb-8 flex items-center">
                        <i data-lucide="map-pin" class="w-4 h-4 mr-3"></i> Identitas Wilayah
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Nama Kawasan</label>
                            <input type="text" name="Kawasan" value="<?= old('Kawasan', $kumuh['Kawasan']) ?>" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Kecamatan</label>
                            <input type="text" name="Kecamatan" value="<?= old('Kecamatan', $kumuh['Kecamatan']) ?>" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Kelurahan/Desa</label>
                            <input type="text" name="Kelurahan" value="<?= old('Kelurahan', $kumuh['Kelurahan']) ?>" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">RT / RW</label>
                            <input type="text" name="Kode_RT_RW" value="<?= old('Kode_RT_RW', $kumuh['Kode_RT_RW']) ?>" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all">
                        </div>
                    </div>
                </div>

                <hr class="border-slate-100 dark:border-slate-800">

                <!-- Bagian 2: Statistik & Skor -->
                <div>
                    <h3 class="text-[10px] font-black text-amber-600 dark:text-amber-500 uppercase tracking-[0.3em] mb-8 flex items-center">
                        <i data-lucide="bar-chart-3" class="w-4 h-4 mr-3"></i> Statistik & Penilaian
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Luas Kumuh (Ha)</label>
                            <input type="number" step="0.01" name="Luas_kumuh" value="<?= old('Luas_kumuh', $kumuh['Luas_kumuh']) ?>" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-mono text-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Skor Kumuh</label>
                            <input type="number" step="0.01" name="skor_kumuh" value="<?= old('skor_kumuh', $kumuh['skor_kumuh']) ?>" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-mono text-sm">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">No. SK Kumuh</label>
                            <input type="text" name="Sk_Kumuh" value="<?= old('Sk_Kumuh', $kumuh['Sk_Kumuh']) ?>" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Sumber Data</label>
                            <input type="text" name="Sumber_data" value="<?= old('Sumber_data', $kumuh['Sumber_data']) ?>" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">ID Desa (Relasi)</label>
                            <input type="number" name="desa_id" value="<?= old('desa_id', $kumuh['desa_id']) ?>" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-mono text-sm">
                        </div>
                    </div>
                </div>

                <hr class="border-slate-100 dark:border-slate-800">

                <!-- Bagian 3: Data Spasial -->
                <div>
                    <h3 class="text-[10px] font-black text-amber-600 dark:text-amber-500 uppercase tracking-[0.3em] mb-8 flex items-center">
                        <i data-lucide="globe" class="w-4 h-4 mr-3"></i> Data Spasial (WKT)
                    </h3>
                    <div class="space-y-6">
                        <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest flex items-center gap-2">
                            <i data-lucide="pen-tool" class="w-3.5 h-3.5"></i> Perbarui Wilayah pada Peta (Polygon)
                        </label>
                        <div class="relative group">
                            <div class="absolute -inset-1 bg-gradient-to-r from-amber-600 to-orange-600 rounded-[2.5rem] blur opacity-10 transition duration-1000"></div>
                            <div class="relative bg-white dark:bg-slate-900 rounded-[2.5rem] overflow-hidden shadow-2xl border border-slate-100 dark:border-slate-800">
                                <div id="map-draw" class="w-full h-96 z-10" style="min-height: 400px; background: #ececec;"></div>
                                
                                <!-- GPS Button -->
                                <button type="button" onclick="getLocation()" class="absolute top-4 right-4 z-[1000] p-3 bg-white dark:bg-slate-800 text-blue-600 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-700 hover:bg-blue-600 hover:text-white transition-all active:scale-90 flex items-center gap-2 font-black text-[9px] uppercase tracking-widest">
                                    <i data-lucide="crosshair" class="w-4 h-4"></i> Fokus Lokasi
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Well-Known Text (Geometry Output)</label>
                            <textarea name="WKT" id="wkt_output" rows="4" class="w-full p-5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-blue-400 outline-none transition-all font-mono text-xs leading-relaxed italic" readonly><?= old('WKT', $kumuh['WKT']) ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TOMBOL AKSI -->
            <div class="mt-12 flex items-center justify-end space-x-6 border-t dark:border-slate-800 pt-10">
                <a href="<?= base_url('wilayah-kumuh') ?>" class="text-sm font-bold text-slate-400 dark:text-slate-600 hover:text-slate-600 dark:hover:text-slate-400 transition-colors">Batal</a>
                <button type="submit" class="bg-amber-500 dark:bg-amber-600 hover:bg-amber-600 dark:hover:bg-amber-500 text-white px-12 py-4 rounded-2xl font-black shadow-xl shadow-amber-200 transition-all flex items-center space-x-3 text-lg">
                    <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                    <span>Perbarui Data Wilayah</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Leaflet & Draw Tools -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

<script>
    let map, drawnItems;

    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(initMapDraw, 500);
    });

    function parsePolygonWKT(wkt) {
        if (!wkt || typeof wkt !== 'string') return null;
        const match = wkt.match(/POLYGON\s*\(\s*\(\s*(.*)\s*\)\s*\)/i);
        if (match) {
            const pointsStr = match[1].split(',');
            return pointsStr.map(p => {
                const [lng, lat] = p.trim().split(/\s+/).map(Number);
                return [lat, lng];
            });
        }
        return null;
    }

    function initMapDraw() {
        const isDark = document.documentElement.classList.contains('dark');
        const standard = L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { attribution: '&copy; Sibaruki' });
        const satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: '&copy; Esri' });

        const initialWkt = document.getElementById('wkt_output').value;
        const polygonPoints = parsePolygonWKT(initialWkt);

        map = L.map('map-draw', { 
            zoomControl: false, 
            layers: [satellite] 
        }).setView(polygonPoints ? polygonPoints[0] : [-5.1245, 120.2536], polygonPoints ? 16 : 14);

        L.control.zoom({ position: 'topright' }).addTo(map);

        drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        if (polygonPoints) {
            const leafletPoints = [...polygonPoints];
            if (leafletPoints.length > 1 && 
                leafletPoints[0][0] === leafletPoints[leafletPoints.length-1][0] && 
                leafletPoints[0][1] === leafletPoints[leafletPoints.length-1][1]) {
                leafletPoints.pop();
            }
            const polygon = L.polygon(leafletPoints, { color: '#2563eb', fillOpacity: 0.3 });
            drawnItems.addLayer(polygon);
            map.fitBounds(polygon.getBounds());
        }

        const drawControl = new L.Control.Draw({
            position: 'topright',
            draw: {
                polygon: {
                    allowIntersection: false,
                    showArea: true,
                    shapeOptions: { color: '#2563eb', fillOpacity: 0.3 }
                },
                polyline: false, circle: false, circlemarker: false, marker: false, rectangle: true
            },
            edit: { featureGroup: drawnItems, remove: true }
        });
        map.addControl(drawControl);

        const LayerToggle = L.Control.extend({
            onAdd: function(map) {
                const btn = L.DomUtil.create('button', 'bg-white dark:bg-slate-900 rounded-xl shadow-xl border border-slate-100 dark:border-slate-800 transition-all duration-300 active:scale-90 mt-2 flex items-center justify-center');
                btn.type = 'button'; btn.style.width = '44px'; btn.style.height = '44px'; btn.style.cursor = 'pointer';
                btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="${isDark?'#60a5fa':'#2563eb'}" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>`;
                L.DomEvent.disableClickPropagation(btn);
                L.DomEvent.on(btn, 'click', function(e) {
                    if (map.hasLayer(standard)) {
                        map.removeLayer(standard); map.addLayer(satellite);
                        btn.style.backgroundColor = '#2563eb';
                    } else {
                        map.removeLayer(satellite); map.addLayer(standard);
                        btn.style.backgroundColor = isDark ? '#0f172a' : '#ffffff';
                    }
                });
                return btn;
            }
        });
        new LayerToggle({ position: 'topright' }).addTo(map);

        map.on(L.Draw.Event.CREATED, function (e) {
            drawnItems.clearLayers();
            const layer = e.layer;
            drawnItems.addLayer(layer);
            updateWKT(layer);
        });

        map.on(L.Draw.Event.EDITED, function (e) {
            e.layers.eachLayer(function (layer) {
                updateWKT(layer);
            });
        });

        map.on(L.Draw.Event.DELETED, function (e) {
            document.getElementById('wkt_output').value = '';
        });
    }

    function updateWKT(layer) {
        let coords = [];
        const latlngs = (layer instanceof L.Polygon) ? layer.getLatLngs()[0] : layer.getLatLngs()[0];
        latlngs.forEach(ll => {
            coords.push(`${ll.lng.toFixed(8)} ${ll.lat.toFixed(8)}`);
        });
        coords.push(`${latlngs[0].lng.toFixed(8)} ${latlngs[0].lat.toFixed(8)}`);
        document.getElementById('wkt_output').value = `POLYGON((${coords.join(', ')}))`;

        const areaM2 = L.GeometryUtil.geodesicArea(latlngs);
        const areaHa = (areaM2 / 10000).toFixed(4);
        const luasInput = document.getElementsByName('Luas_kumuh')[0];
        if (luasInput) luasInput.value = areaHa;
    }

    function getLocation() {
        if (navigator.geolocation) {
            showToast('Mengakses GPS...', 'success');
            navigator.geolocation.getCurrentPosition((position) => {
                map.setView([position.coords.latitude, position.coords.longitude], 18);
            }, (err) => {
                showToast('Gagal mengakses lokasi. Pastikan GPS aktif.', 'error');
            });
        }
    }
    lucide.createIcons();
</script>
<?= $this->endSection() ?>
