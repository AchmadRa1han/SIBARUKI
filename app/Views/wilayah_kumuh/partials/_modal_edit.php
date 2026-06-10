<!-- WILAYAH KUMUH MODAL COMPONENT -->
<!-- Load Leaflet Geoman & Wellknown Parser Assets for Modal -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@geoman-io/leaflet-geoman-free@2.17.0/dist/leaflet-geoman.css" />
<script src="https://cdn.jsdelivr.net/npm/wellknown@0.5.0/wellknown.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@geoman-io/leaflet-geoman-free@2.17.0/dist/leaflet-geoman.min.js"></script>

<div id="modal-kumuh" class="fixed inset-0 z-[10002] hidden overflow-y-auto py-10 px-4">
    <div class="fixed inset-0 bg-blue-950/60 backdrop-blur-sm transition-opacity" onclick="UI.closeModal('modal-kumuh')"></div>
    <div class="relative flex items-center justify-center p-4 min-h-full">
        <div class="bg-white dark:bg-slate-900 w-full max-w-4xl rounded-[2.5rem] shadow-2xl overflow-hidden animate-in zoom-in duration-300">
            <!-- Modal Header -->
            <div class="p-8 bg-blue-950 text-white flex justify-between items-center border-b border-white/10 sticky top-0 z-30">
                <div class="flex items-center gap-5">
                    <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-md border border-white/10">
                        <i data-lucide="layers" class="w-6 h-6 text-rose-400"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black uppercase tracking-tighter" id="modal-kumuh-title">Data Wilayah Kumuh</h3>
                        <p class="text-[8px] font-bold uppercase tracking-widest text-white/60 mt-1">Registri Delineasi & SK Kumuh</p>
                    </div>
                </div>
                <button type="button" onclick="UI.closeModal('modal-kumuh')" class="p-2 hover:bg-white/10 rounded-xl transition-colors"><i data-lucide="x" class="w-6 h-6"></i></button>
            </div>

            <form id="form-kumuh" action="<?= base_url('wilayah-kumuh/store') ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6 bg-slate-100 dark:bg-slate-950">
                    <div class="md:col-span-2">
                        <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Nama Kawasan</label>
                        <input type="text" name="Kawasan" id="inp_kum_kawasan" required class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase" placeholder="Masukkan Nama Kawasan...">
                    </div>
                    
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Kecamatan</label>
                        <input type="text" name="Kecamatan" id="inp_kum_kecamatan" required class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                    </div>

                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Kelurahan / Desa</label>
                        <input type="text" name="Kelurahan" id="inp_kum_kelurahan" required class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Luas (Ha)</label>
                            <input type="number" step="0.01" name="Luas_kumuh" id="inp_kum_luas" required class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold">
                        </div>
                        <div>
                            <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Skor Kumuh</label>
                            <input type="number" step="0.01" name="skor_kumuh" id="inp_kum_skor" required class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Nomor SK Kumuh (Opsional)</label>
                        <input type="text" name="Sk_Kumuh" id="inp_kum_sk" class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase" placeholder="SK/01/2024...">
                    </div>

                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Sumber Data</label>
                        <input type="text" name="Sumber_data" id="inp_kum_sumber" class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase" placeholder="P3KE / DAK / DLL">
                    </div>

                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Kode RT/RW</label>
                        <input type="text" name="Kode_RT_RW" id="inp_kum_rt_rw" class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                    </div>

                    <!-- PETA INTERAKTIF DALAM MODAL -->
                    <div class="md:col-span-2 space-y-3">
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-300 uppercase tracking-widest ml-1">Gambar / Edit Poligon Kumuh Pada Peta</label>
                        <div id="modal-map-editor" class="w-full h-[300px] rounded-2xl border border-slate-200 dark:border-slate-800 shadow-inner relative z-10 bg-slate-50 dark:bg-slate-900 overflow-hidden" style="background: #ececec;"></div>
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 text-[9px] font-medium text-slate-400 dark:text-slate-500 leading-normal">
                            <p>💡 Gunakan ikon poligon di kanan atas peta untuk menggambar. Seret titik sudut untuk mengubah bentuk.</p>
                            <button type="button" onclick="loadPolygonFromModalWkt()" class="shrink-0 font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 uppercase tracking-widest flex items-center gap-1.5 transition-all">
                                <i data-lucide="refresh-cw" class="w-3 h-3"></i> Reload Dari Textarea
                            </button>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Polygon Geospasial (WKT)</label>
                        <textarea name="WKT" id="inp_kum_wkt" rows="4" required class="w-full p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-mono text-[10px] leading-relaxed" placeholder="POLYGON((...))"></textarea>
                    </div>

                    <input type="hidden" name="Provinsi" value="Sulawesi Selatan">
                    <input type="hidden" name="Kab_Kota" value="Sinjai">
                    <input type="hidden" name="Kode_Prov" value="73">
                    <input type="hidden" name="Kode_Kab" value="07">
                </div>

                <!-- Footer -->
                <div class="p-6 bg-slate-50 dark:bg-slate-900 border-t dark:border-slate-800 flex justify-end gap-3 rounded-b-[2.5rem]">
                    <button type="button" onclick="UI.closeModal('modal-kumuh')" class="px-6 py-2.5 text-slate-500 font-bold uppercase tracking-widest text-[10px] hover:text-rose-500 transition-colors">Batal</button>
                    <button type="submit" id="btn-kum-submit" class="px-8 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-black uppercase tracking-widest text-[10px] shadow-lg shadow-rose-600/20 active:scale-95 transition-all flex items-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i> Simpan Kawasan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    (function() {
        let modalMap = null;
        let modalEditLayer = null;

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

        function updateModalWktFromMap() {
            if (!modalMap) return;
            let polygons = [];
            modalMap.eachLayer(function(l) {
                if (l instanceof L.Polygon && !(l instanceof L.Rectangle)) {
                    polygons.push(l);
                }
            });

            const textarea = document.getElementById('inp_kum_wkt');
            const inputLuas = document.getElementById('inp_kum_luas');

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

        window.loadPolygonFromModalWkt = function() {
            const textarea = document.getElementById('inp_kum_wkt');
            if (!textarea || !textarea.value || !modalMap) return;

            // Clear existing drawn polygons on the map
            modalMap.eachLayer(function(l) {
                if (l instanceof L.Polygon) {
                    modalMap.removeLayer(l);
                }
            });

            try {
                const geojson = wellknown.parse(textarea.value);
                if (geojson) {
                    modalEditLayer = L.geoJSON(geojson, {
                        style: { color: '#e11d48', fillColor: '#e11d48', fillOpacity: 0.3 }
                    }).addTo(modalMap);

                    const bounds = modalEditLayer.getBounds();
                    if (bounds.isValid()) {
                        modalMap.fitBounds(bounds, { padding: [30, 30] });
                    }

                    modalEditLayer.eachLayer(function(layer) {
                        layer.on('pm:edit', updateModalWktFromMap);
                        layer.on('pm:dragend', updateModalWktFromMap);
                        layer.on('pm:remove', updateModalWktFromMap);
                    });
                }
            } catch (e) {
                console.error("Gagal parsing WKT di modal:", e);
            }
        };

        function initModalMap() {
            if (modalMap) return;

            const isDark = document.documentElement.classList.contains('dark');
            const cartoDB = L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { 
                attribution: '&copy; CartoDB' 
            });
            const googleSat = L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains:['mt0','mt1','mt2','mt3'],
                attribution: '&copy; Google'
            });

            modalMap = L.map('modal-map-editor', { zoomControl: false, layers: [googleSat] }).setView([-5.1245, 120.2536], 12);
            
            // Tambahkan tombol toggle satelit
            let rot = 0;
            const LayerToggle = L.Control.extend({
                onAdd: function(map) {
                    const btn = L.DomUtil.create('button', 'rounded-lg shadow-xl border transition-all duration-300 active:scale-90 flex items-center justify-center bg-blue-600');
                    btn.type = 'button';
                    btn.style.width = '30px'; btn.style.height = '30px'; btn.style.cursor = 'pointer';
                    btn.style.margin = '5px';
                    btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:block; transition: transform 0.8s cubic-bezier(0.65, 0, 0.35, 1);"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>`;
                    
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
            modalMap.addControl(new LayerToggle({ position: 'topleft' }));
            L.control.zoom({ position: 'topleft' }).addTo(modalMap);

            // Konfigurasi Leaflet Geoman
            modalMap.pm.addControls({
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

            modalMap.pm.setGlobalOptions({
                allowSelfIntersection: false,
                templineStyle: { color: '#e11d48' },
                hintlineStyle: { color: '#e11d48', dashArray: [5, 5] },
                pathOptions: { color: '#e11d48', fillColor: '#e11d48', fillOpacity: 0.3 }
            });

            modalMap.on('pm:create', function(e) {
                const layer = e.layer;
                modalMap.eachLayer(function(l) {
                    if (l instanceof L.Polygon && l !== layer) {
                        modalMap.removeLayer(l);
                    }
                });

                layer.on('pm:edit', updateModalWktFromMap);
                layer.on('pm:dragend', updateModalWktFromMap);
                layer.on('pm:remove', updateModalWktFromMap);

                updateModalWktFromMap();
            });

            modalMap.on('pm:remove', function(e) {
                updateModalWktFromMap();
            });
        }

        window.kumuhModal = {
            openAdd: function() {
                const form = document.getElementById('form-kumuh');
                if (!form) return;
                form.reset();
                form.action = <?= json_encode(base_url('wilayah-kumuh/store')) ?>;
                
                document.getElementById('modal-kumuh-title').innerText = "Tambah Kawasan Kumuh";
                
                const btn = document.getElementById('btn-kum-submit');
                if (btn) {
                    btn.className = "px-8 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-black uppercase tracking-widest text-[10px] shadow-lg shadow-emerald-600/20 active:scale-95 transition-all flex items-center gap-2";
                    btn.innerHTML = '<i data-lucide="plus-circle" class="w-4 h-4"></i> Tambah Kawasan';
                }

                if (window.UI) UI.openModal('modal-kumuh');

                // Initialize map and clear it
                setTimeout(() => {
                    initModalMap();
                    if (modalMap) {
                        modalMap.invalidateSize();
                        modalMap.eachLayer(function(l) {
                            if (l instanceof L.Polygon) modalMap.removeLayer(l);
                        });
                        modalMap.setView([-5.1245, 120.2536], 12);
                    }
                }, 200);
            },

            openEdit: function(data) {
                if (!data) return;
                const form = document.getElementById('form-kumuh');
                if (!form) return;
                form.reset();
                form.action = <?= json_encode(base_url('wilayah-kumuh/update')) ?> + '/' + (data.FID || 0);
                
                document.getElementById('modal-kumuh-title').innerText = "Edit Kawasan Kumuh";
                
                const fields = {
                    'inp_kum_kawasan': data.Kawasan,
                    'inp_kum_kecamatan': data.Kecamatan,
                    'inp_kum_kelurahan': data.Kelurahan,
                    'inp_kum_luas': data.Luas_kumuh,
                    'inp_kum_skor': data.skor_kumuh,
                    'inp_kum_sk': data.Sk_Kumuh,
                    'inp_kum_sumber': data.Sumber_data,
                    'inp_kum_rt_rw': data.Kode_RT_RW,
                    'inp_kum_wkt': data.WKT
                };

                for (const id in fields) {
                    const el = document.getElementById(id);
                    if (el) el.value = fields[id] || '';
                }

                const btn = document.getElementById('btn-kum-submit');
                if (btn) {
                    btn.className = "px-8 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-black uppercase tracking-widest text-[10px] shadow-lg shadow-blue-600/20 active:scale-95 transition-all flex items-center gap-2";
                    btn.innerHTML = '<i data-lucide="save" class="w-4 h-4"></i> Simpan Perubahan';
                }

                if (window.UI) UI.openModal('modal-kumuh');

                // Initialize map and load polygon
                setTimeout(() => {
                    initModalMap();
                    if (modalMap) {
                        modalMap.invalidateSize();
                        loadPolygonFromModalWkt();
                    }
                }, 200);
            }
        };

        // Update map when typing manually inside modal WKT textarea
        document.addEventListener('DOMContentLoaded', () => {
            const txt = document.getElementById('inp_kum_wkt');
            if (txt) {
                txt.addEventListener('input', function() {
                    clearTimeout(this.modalTimeout);
                    this.modalTimeout = setTimeout(() => {
                        loadPolygonFromModalWkt();
                    }, 1000);
                });
            }
        });
    })();
</script>
