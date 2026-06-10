<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<!-- GIS & Map Drawing Assets -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@geoman-io/leaflet-geoman-free@2.17.0/dist/leaflet-geoman.css" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/wellknown@0.5.0/wellknown.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@geoman-io/leaflet-geoman-free@2.17.0/dist/leaflet-geoman.min.js"></script>

<div class="max-w-7xl mx-auto space-y-6 pb-32 text-slate-900 dark:text-slate-200">
    
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-3 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 no-print">
        <a href="<?= base_url('dashboard') ?>" class="hover:text-blue-600 transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <a href="<?= base_url('wilayah-kumuh') ?>" class="hover:text-blue-600 transition-colors">Wilayah Kumuh</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">Tambah Kawasan</span>
    </nav>

    <!-- Header Action -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm transition-all duration-300 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-48 h-48 bg-rose-600/5 rounded-full -mr-24 -mt-24 blur-3xl"></div>
        <div class="relative z-10 flex items-center gap-4">
            <a href="<?= base_url('wilayah-kumuh') ?>" class="p-3 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl hover:bg-blue-600 hover:text-white transition-all active:scale-95" title="Kembali">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 bg-rose-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-rose-600/20">
                    <i data-lucide="plus" class="w-7 h-7"></i>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-blue-950 dark:text-white uppercase tracking-tighter">Tambah Wilayah Kumuh</h1>
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">Registrasi Delineasi Kawasan Kumuh Baru</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3 relative z-10">
            <a href="<?= base_url('wilayah-kumuh') ?>" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-slate-700 transition-all active:scale-95">Batal</a>
        </div>
    </div>

    <!-- 1. FORM IMPORT -->
    <div class="bg-emerald-50 dark:bg-emerald-950/20 rounded-2xl p-6 border border-emerald-100 dark:border-emerald-900/30 relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-600/5 rounded-full -mr-16 -mt-16 blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 relative z-10">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-emerald-600/20">
                    <i data-lucide="file-up" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-emerald-900 dark:text-emerald-400 uppercase tracking-tight">Import via CSV</h3>
                    <p class="text-[9px] text-emerald-600/70 dark:text-emerald-500/50 font-bold uppercase tracking-[0.2em]">Unggah file database delineasi massal</p>
                </div>
            </div>
            <form action="<?= base_url('wilayah-kumuh/import-csv') ?>" method="post" enctype="multipart/form-data" class="flex flex-col md:flex-row items-center gap-3 w-full lg:w-auto">
                <?= csrf_field() ?>
                <input type="file" name="csv_file" accept=".csv" required class="block w-full text-[9px] text-emerald-900 dark:text-emerald-400 file:mr-4 file:py-2 file:px-6 file:rounded-lg file:border-0 file:text-[9px] file:font-bold file:uppercase file:tracking-widest file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 cursor-pointer transition-all">
                <button type="submit" class="w-full md:w-auto bg-emerald-900 dark:bg-emerald-600 text-white px-6 py-2.5 rounded-lg text-[9px] font-bold uppercase tracking-widest shadow-lg shadow-emerald-900/20 hover:bg-black dark:hover:bg-emerald-700 transition-all active:scale-95 flex items-center justify-center gap-2">
                    <i data-lucide="upload-cloud" class="w-3.5 h-3.5"></i> Impor
                </button>
            </form>
        </div>
    </div>

    <!-- 2. FORM MANUAL -->
    <form action="<?= base_url('wilayah-kumuh/store') ?>" method="post">
        <?= csrf_field() ?>
        <div class="space-y-10">
            <!-- SECTION 1: ATRIBUT ADMINISTRASI -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all duration-300">
                <div class="p-6 border-b dark:border-slate-800 bg-rose-50/30 dark:bg-rose-950/30 flex items-center gap-3">
                    <div class="w-9 h-9 bg-rose-600 rounded-lg flex items-center justify-center text-white shadow-lg">
                        <i data-lucide="map-pin" class="w-4.5 h-4.5"></i>
                    </div>
                    <div>
                        <h3 class="text-[11px] font-bold text-rose-900 dark:text-rose-400 uppercase tracking-[0.2em]">Data Administrasi</h3>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Identitas Wilayah & Lokasi</p>
                    </div>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Nama Kelurahan / Desa</label>
                        <input type="text" name="Kelurahan" required class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Kecamatan</label>
                        <input type="text" name="Kecamatan" required class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Nama Kawasan</label>
                        <input type="text" name="Kawasan" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 dark:text-slate-200 outline-none transition-all font-bold uppercase" placeholder="CONTOH: PESISIR...">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Kode RT / RW</label>
                        <input type="text" name="Kode_RT_RW" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                    </div>
                    <div class="bg-rose-50 dark:bg-rose-950/30 p-5 rounded-2xl border border-rose-100 dark:border-rose-900/50">
                        <label class="block text-[8px] font-bold text-rose-900 dark:text-rose-400 uppercase mb-2 tracking-widest ml-1">Luas Kawasan (Ha)</label>
                        <input type="number" step="0.01" name="Luas_kumuh" required class="w-full bg-transparent border-none text-xl font-bold text-rose-950 dark:text-white p-0 focus:ring-0 outline-none placeholder:opacity-20" placeholder="0.00">
                    </div>
                    <div class="bg-blue-950 p-5 rounded-2xl shadow-lg">
                        <label class="block text-[8px] font-bold text-blue-300 uppercase mb-2 tracking-widest ml-1">Skor Kekumuhan</label>
                        <input type="number" step="0.01" name="skor_kumuh" required class="w-full bg-transparent border-none text-xl font-bold text-white p-0 focus:ring-0 outline-none italic" placeholder="0.00">
                    </div>
                </div>
            </div>

            <!-- SECTION 2: PARAMETER TEKNIS -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all duration-300">
                <div class="p-6 border-b dark:border-slate-800 bg-slate-50/30 dark:bg-slate-950/30 flex items-center gap-3">
                    <div class="w-9 h-9 bg-slate-900 rounded-lg flex items-center justify-center text-white shadow-lg">
                        <i data-lucide="database" class="w-4.5 h-4.5"></i>
                    </div>
                    <div>
                        <h3 class="text-[11px] font-bold text-slate-900 dark:text-slate-400 uppercase tracking-[0.2em]">Parameter & Geometri</h3>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Data Teknis Delineasi</p>
                    </div>
                </div>
                <div class="p-8 space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">No. SK Penetapan (Opsional)</label>
                            <input type="text" name="Sk_Kumuh" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                        </div>
                        <div>
                            <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Sumber Basis Data</label>
                            <input type="text" name="Sumber_data" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                        </div>
                    </div>

                    <!-- PETA INTERAKTIF GAMBAR POLYGON -->
                    <div class="space-y-3">
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1">Gambar Poligon Kumuh Pada Peta</label>
                        <div id="map-editor" class="w-full h-[450px] rounded-2xl border border-slate-200 dark:border-slate-800 shadow-inner relative overflow-hidden z-10 bg-slate-100 dark:bg-slate-950"></div>
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 text-[10px] font-medium text-slate-400 leading-normal">
                            <p>💡 <span class="font-bold">Panduan:</span> Gunakan ikon poligon di kanan atas peta untuk menggambar batas wilayah kumuh. Hasil gambar akan terkonversi otomatis menjadi format WKT.</p>
                            <button type="button" onclick="loadPolygonFromWkt()" class="shrink-0 text-[10px] font-bold text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 uppercase tracking-widest flex items-center gap-1.5 transition-all">
                                <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i> Plot Dari Textarea
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Data Poligon (WKT)</label>
                        <div class="relative">
                            <textarea name="WKT" rows="5" required class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 dark:text-slate-200 outline-none transition-all font-mono text-xs leading-relaxed" placeholder="POLYGON((Long Lat, ...))"></textarea>
                            <div class="absolute right-3 bottom-3 text-[8px] font-bold text-slate-400 uppercase tracking-widest pointer-events-none">Format Spasial: WGS84</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ACTION BAR -->
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 pt-4">
                <div class="flex items-center gap-3 text-slate-400">
                    <i data-lucide="info" class="w-4 h-4"></i>
                    <p class="text-[9px] font-bold uppercase tracking-widest leading-relaxed max-w-md">Pastikan data WKT telah terverifikasi melalui aplikasi GIS sebelum melakukan penyimpanan database.</p>
                </div>
                <button type="submit" class="group flex items-center space-x-6 bg-rose-600 hover:bg-rose-700 text-white pl-8 pr-4 py-4 rounded-xl font-bold shadow-xl shadow-rose-600/20 transition-all active:scale-95 w-full md:w-auto">
                    <div class="flex flex-col text-right">
                        <span class="text-[8px] uppercase tracking-[0.3em] opacity-60 mb-0.5">Konfirmasi Final</span>
                        <span class="text-base uppercase tracking-tighter">Simpan Delineasi</span>
                    </div>
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center group-hover:translate-x-1 transition-transform">
                        <i data-lucide="save" class="w-5 h-5"></i>
                    </div>
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    let map = null;
    let editLayer = null;

    function calculateAreaInHectares(polygon) {
        const latlngs = polygon.getLatLngs()[0]; // Outer ring
        const pts = Array.isArray(latlngs[0]) ? latlngs[0] : latlngs;
        if (pts.length < 3) return 0;
        
        // Sinjai local conversion factors
        const latToMeters = 110820; // 1 degree lat ≈ 110.82 km
        const lngToMeters = 110900; // 1 degree lng at -5 degrees lat ≈ 110.9 km
        
        let area = 0;
        for (let i = 0; i < pts.length; i++) {
            const p1 = pts[i];
            const p2 = pts[(i + 1) % pts.length];
            
            const x1 = p1.lng * lngToMeters;
            const y1 = p1.lat * latToMeters;
            const x2 = p2.lng * lngToMeters;
            const y2 = p2.lat * latToMeters;
            
            area += (x1 * y2) - (x2 * y1);
        }
        
        const areaSqMeters = Math.abs(area / 2);
        return areaSqMeters / 10000;
    }

    function updateWktFromMap() {
        let polygons = [];
        map.eachLayer(function(l) {
            if (l instanceof L.Polygon && !(l instanceof L.Rectangle)) {
                polygons.push(l);
            }
        });

        const textarea = document.querySelector('textarea[name="WKT"]');
        const inputLuas = document.querySelector('input[name="Luas_kumuh"]');

        if (polygons.length > 0) {
            const poly = polygons[0];
            const geojson = poly.toGeoJSON();
            const wkt = wellknown.stringify(geojson);
            textarea.value = wkt;

            // Auto calculate area
            const areaHa = calculateAreaInHectares(poly);
            if (inputLuas) {
                inputLuas.value = areaHa.toFixed(2);
            }
        } else {
            textarea.value = '';
        }
    }

    function loadPolygonFromWkt() {
        const textarea = document.querySelector('textarea[name="WKT"]');
        if (!textarea || !textarea.value) return;

        // Clear existing drawn polygons on the map
        map.eachLayer(function(l) {
            if (l instanceof L.Polygon) {
                map.removeLayer(l);
            }
        });

        try {
            const geojson = wellknown.parse(textarea.value);
            if (geojson) {
                // Add geojson layer to map
                editLayer = L.geoJSON(geojson, {
                    style: { color: '#e11d48', fillColor: '#e11d48', fillOpacity: 0.3 }
                }).addTo(map);

                // Zoom map to layer bounds
                const bounds = editLayer.getBounds();
                if (bounds.isValid()) {
                    map.fitBounds(bounds, { padding: [50, 50] });
                }

                // Bind pm events to the new sub-layers
                editLayer.eachLayer(function(layer) {
                    layer.on('pm:edit', updateWktFromMap);
                    layer.on('pm:dragend', updateWktFromMap);
                    layer.on('pm:remove', updateWktFromMap);
                });
            }
        } catch (e) {
            console.error("Gagal parsing WKT:", e);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();

        // 1. Inisialisasi Peta
        const isDark = document.documentElement.classList.contains('dark');
        const cartoDB = L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { 
            attribution: '&copy; CartoDB' 
        });
        const googleSat = L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains:['mt0','mt1','mt2','mt3'],
            attribution: '&copy; Google'
        });

        map = L.map('map-editor', { zoomControl: false, layers: [googleSat] }).setView([-5.1245, 120.2536], 12);
        
        // Tambahkan tombol toggle satelit
        let rot = 0;
        const LayerToggle = L.Control.extend({
            onAdd: function(map) {
                const btn = L.DomUtil.create('button', 'rounded-lg shadow-xl border transition-all duration-300 active:scale-90 flex items-center justify-center bg-blue-600');
                btn.type = 'button';
                btn.style.width = '34px'; btn.style.height = '34px'; btn.style.cursor = 'pointer';
                btn.style.margin = '10px';
                btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:block; transition: transform 0.8s cubic-bezier(0.65, 0, 0.35, 1);"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>`;
                
                L.DomEvent.disableClickPropagation(btn);
                L.DomEvent.on(btn, 'click', function(e) {
                    L.DomEvent.stopPropagation(e);
                    L.DomEvent.preventDefault(e);
                    rot += 360;
                    const svg = btn.querySelector('svg');
                    svg.style.transform = `rotate(${rot}deg)`;
                    setTimeout(() => {
                        if (map.hasLayer(googleSat)) { 
                            map.removeLayer(googleSat); 
                            map.addLayer(cartoDB); 
                            btn.className = 'rounded-lg shadow-xl border transition-all duration-300 active:scale-90 flex items-center justify-center ' + (isDark ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-200');
                            svg.setAttribute('stroke', isDark ? '#60a5fa' : '#2563eb'); 
                        }
                        else { 
                            map.removeLayer(cartoDB); 
                            map.addLayer(googleSat); 
                            btn.className = 'rounded-lg shadow-xl border transition-all duration-300 active:scale-90 flex items-center justify-center bg-blue-600 border-transparent';
                            svg.setAttribute('stroke', '#ffffff'); 
                        }
                    }, 200);
                });
                return btn;
            }
        });
        map.addControl(new LayerToggle({ position: 'topleft' }));
        L.control.zoom({ position: 'topleft' }).addTo(map);

        // 2. Konfigurasi Leaflet Geoman
        map.pm.addControls({
            position: 'topright',
            drawMarker: false,
            drawCircleMarker: false,
            drawPolyline: false,
            drawRectangle: false,
            drawCircle: false,
            drawText: false,
            cutPolygon: false,
            dragMode: true,
            editMode: true,
            removalMode: true,
            drawPolygon: true
        });

        map.pm.setGlobalOptions({
            allowSelfIntersection: false,
            templineStyle: { color: '#e11d48' },
            hintlineStyle: { color: '#e11d48', dashArray: [5, 5] },
            pathOptions: { color: '#e11d48', fillColor: '#e11d48', fillOpacity: 0.3 }
        });

        // 3. Listener Event Geoman
        map.on('pm:create', function(e) {
            const layer = e.layer;
            
            // Hapus poligon lain agar selalu hanya ada satu
            map.eachLayer(function(l) {
                if (l instanceof L.Polygon && l !== layer) {
                    map.removeLayer(l);
                }
            });

            // Tambahkan event handler untuk modifikasi layer baru
            layer.on('pm:edit', updateWktFromMap);
            layer.on('pm:dragend', updateWktFromMap);
            layer.on('pm:remove', updateWktFromMap);

            updateWktFromMap();
        });

        map.on('pm:remove', function(e) {
            updateWktFromMap();
        });

        // 4. Load WKT Awal (jika ada data lama)
        loadPolygonFromWkt();

        // 5. Update jika user mengetik WKT secara manual
        document.querySelector('textarea[name="WKT"]').addEventListener('input', function() {
            // Debounce load dari textarea
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                loadPolygonFromWkt();
            }, 1000);
        });
    });
</script>
<?= $this->endSection() ?>
