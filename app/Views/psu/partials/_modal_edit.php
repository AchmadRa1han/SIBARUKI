<!-- PSU JALAN MODAL COMPONENT -->
<div id="modal-psu" class="fixed inset-0 z-[10002] hidden overflow-y-auto py-10 px-4">
    <div class="fixed inset-0 bg-blue-950/60 backdrop-blur-sm transition-opacity" onclick="UI.closeModal('modal-psu')"></div>
    <div class="relative flex items-center justify-center p-4 min-h-full">
        <div class="bg-white dark:bg-slate-900 w-full max-w-4xl rounded-[2.5rem] shadow-2xl overflow-hidden animate-in zoom-in duration-300">
            <!-- Modal Header -->
            <div class="p-8 bg-blue-950 text-white flex justify-between items-center border-b border-white/10 sticky top-0 z-30">
                <div class="flex items-center gap-5">
                    <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-md border border-white/10">
                        <i data-lucide="route" class="w-6 h-6 text-blue-400"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black uppercase tracking-tighter" id="modal-psu-title">Data PSU Jalan</h3>
                        <p class="text-[8px] font-bold uppercase tracking-widest text-white/60 mt-1">Registri Aset Infrastruktur Jalan</p>
                    </div>
                </div>
                <button type="button" onclick="UI.closeModal('modal-psu')" class="p-2 hover:bg-white/10 rounded-xl transition-colors"><i data-lucide="x" class="w-6 h-6"></i></button>
            </div>

            <form id="form-psu" action="<?= base_url('psu/store') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                
                <div class="p-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <h4 class="text-[10px] font-black text-blue-600 uppercase border-b pb-3 tracking-widest">Informasi Umum</h4>
                        
                        <div>
                            <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Nama Jaringan Jalan / PSU</label>
                            <input type="text" name="nama_jalan" id="inp_psu_nama" required class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold" placeholder="Contoh: Pembangunan Jalan Beton...">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Tahun Pembangunan</label>
                                <input type="number" name="tahun" id="inp_psu_tahun" value="<?= date('Y') ?>" min="2000" max="2100" required class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold">
                            </div>
                            <div>
                                <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Panjang/Luas (Meter)</label>
                                <input type="number" step="0.01" name="panjang_luas" id="inp_psu_panjang" required class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold" placeholder="0.00">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Keterangan Wilayah / Lokasi</label>
                            <input type="text" name="jalan" id="inp_psu_jalan" required class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold" placeholder="Contoh: Kelurahan Lappa, Kec. Sinjai Utara">
                        </div>

                        <div>
                            <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Batas Koordinat / Garis (WKT LINESTRING)</label>
                            <textarea name="wkt" id="inp_psu_wkt" rows="3" required readonly class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-mono text-xs leading-relaxed" placeholder="LINESTRING(...)"></textarea>
                            <p class="text-[8px] text-slate-400 mt-1 italic">Klik pada peta di samping untuk mulai menggambar titik.</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="h-64 rounded-2xl overflow-hidden border-2 border-slate-200 dark:border-slate-800 shadow-sm relative">
                            <div id="modalMapPsu" class="w-full h-full z-10"></div>
                            <div class="absolute bottom-2 left-2 z-20">
                                <button type="button" onclick="psuModal.clearMap()" class="px-3 py-1.5 bg-white dark:bg-slate-900 text-rose-500 rounded-lg shadow-md text-[9px] font-bold uppercase tracking-widest hover:bg-rose-50 dark:hover:bg-rose-950 transition-all border border-slate-100 dark:border-slate-800">Clear Map</button>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest ml-1">Kondisi 0% (Before)</label>
                                <div id="preview_psu_before" class="relative group aspect-video bg-slate-50 dark:bg-slate-950 border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-xl flex flex-col items-center justify-center overflow-hidden transition-all hover:border-blue-500/50">
                                    <img id="img_psu_before" class="absolute inset-0 w-full h-full object-cover hidden">
                                    <div id="placeholder_psu_before" class="text-center p-4">
                                        <i data-lucide="image-plus" class="w-6 h-6 text-slate-300 mx-auto mb-1 group-hover:scale-110 transition-transform"></i>
                                        <p class="text-[7px] font-bold text-slate-400 uppercase tracking-widest">Pilih Foto</p>
                                    </div>
                                    <input type="file" name="foto_before" accept="image/*" class="absolute inset-0 opacity-0 z-10 cursor-pointer" onchange="psuModal.previewImg(this, 'before')">
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest ml-1">Kondisi 100% (After)</label>
                                <div id="preview_psu_after" class="relative group aspect-video bg-slate-50 dark:bg-slate-950 border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-xl flex flex-col items-center justify-center overflow-hidden transition-all hover:border-emerald-500/50">
                                    <img id="img_psu_after" class="absolute inset-0 w-full h-full object-cover hidden">
                                    <div id="placeholder_psu_after" class="text-center p-4">
                                        <i data-lucide="image-plus" class="w-6 h-6 text-slate-300 mx-auto mb-1 group-hover:scale-110 transition-transform"></i>
                                        <p class="text-[7px] font-bold text-slate-400 uppercase tracking-widest">Pilih Foto</p>
                                    </div>
                                    <input type="file" name="foto_after" accept="image/*" class="absolute inset-0 opacity-0 z-10 cursor-pointer" onchange="psuModal.previewImg(this, 'after')">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="p-6 bg-slate-50 dark:bg-slate-900 border-t dark:border-slate-800 flex justify-end gap-3 rounded-b-[2.5rem]">
                    <button type="button" onclick="UI.closeModal('modal-psu')" class="px-6 py-2.5 text-slate-500 font-bold uppercase tracking-widest text-[10px] hover:text-rose-500 transition-colors">Batal</button>
                    <button type="submit" id="btn-submit-psu" class="px-8 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-black uppercase tracking-widest text-[10px] shadow-lg shadow-blue-600/20 active:scale-95 transition-all flex items-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    (function() {
        const PSU_UPLOAD_URL = <?= json_encode(base_url('uploads/psu/')) ?>;
        
        window.psuModal = {
            map: null,
            polyLine: null,
            points: [],
            uploadUrl: PSU_UPLOAD_URL.endsWith('/') ? PSU_UPLOAD_URL : PSU_UPLOAD_URL + '/',

            init: function() {
                window.addEventListener('modalOpened', (e) => {
                    if (e && e.detail && e.detail.id === 'modal-psu') {
                        this.initMap();
                    }
                });
            },

            initMap: function() {
                if (this.map) {
                    setTimeout(() => this.map.invalidateSize(), 300);
                    return;
                }
                
                try {
                    const isDark = document.documentElement.classList.contains('dark');
                    const cartoDB = L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { attribution: '&copy; CartoDB' });
                    const googleSat = L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', { maxZoom: 20, subdomains:['mt0','mt1','mt2','mt3'], attribution: '&copy; Google' });

                    this.map = L.map('modalMapPsu', { zoomControl: false, layers: [googleSat] }).setView([-5.1245, 120.2536], 13);
                    L.control.zoom({ position: 'topright' }).addTo(this.map);

                    let rot = 0;
                    const LayerToggle = L.Control.extend({
                        onAdd: (map) => {
                            const btn = L.DomUtil.create('button', 'rounded-lg shadow-xl border transition-all duration-300 active:scale-90 mt-2 flex items-center justify-center');
                            btn.style.width = '38px'; btn.style.height = '38px'; btn.style.cursor = 'pointer';
                            btn.type = 'button';
                            btn.style.backgroundColor = '#2563eb';
                            const standardSvgColor = isDark ? '#60a5fa' : '#2563eb';
                            btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:block; transition: transform 0.8s cubic-bezier(0.65, 0, 0.35, 1);"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>`;
                            L.DomEvent.disableClickPropagation(btn);
                            L.DomEvent.on(btn, 'click', (e) => {
                                L.DomEvent.stopPropagation(e);
                                L.DomEvent.preventDefault(e);
                                rot += 360;
                                const svg = btn.querySelector('svg');
                                svg.style.transform = `rotate(${rot}deg)`;
                                setTimeout(() => {
                                    if (map.hasLayer(googleSat)) { 
                                        map.removeLayer(googleSat); 
                                        map.addLayer(cartoDB); 
                                        btn.style.backgroundColor = isDark ? '#0f172a' : '#ffffff'; 
                                        svg.setAttribute('stroke', standardSvgColor); 
                                    }
                                    else { 
                                        map.removeLayer(cartoDB); 
                                        map.addLayer(googleSat); 
                                        btn.style.backgroundColor = '#2563eb'; 
                                        svg.setAttribute('stroke', '#ffffff'); 
                                    }
                                }, 200);
                            });
                            return btn;
                        }
                    });
                    this.map.addControl(new LayerToggle({ position: 'topright' }));

                    this.map.on('click', (e) => this.addPoint(e.latlng.lat, e.latlng.lng));
                    setTimeout(() => this.map.invalidateSize(), 300);
                } catch (err) { console.error('Modal map init error:', err); }
            },

            addPoint: function(lat, lng) {
                this.points.push([lat, lng]);
                this.drawPolyline();
                this.updateWKT();
            },

            drawPolyline: function() {
                if (this.polyLine) this.map.removeLayer(this.polyLine);
                if (this.points.length > 0) {
                    this.polyLine = L.polyline(this.points, { color: '#2563eb', weight: 4 }).addTo(this.map);
                    if (this.points.length > 1) {
                        this.map.fitBounds(this.polyLine.getBounds(), { padding: [20, 20], maxZoom: 18 });
                    }
                }
            },

            updateWKT: function() {
                const el = document.getElementById('inp_psu_wkt');
                if (this.points.length === 0) {
                    el.value = '';
                } else if (this.points.length === 1) {
                    el.value = `POINT(${this.points[0][1]} ${this.points[0][0]})`;
                } else {
                    const coords = this.points.map(p => `${p[1]} ${p[0]}`).join(', ');
                    el.value = `LINESTRING(${coords})`;
                }
            },

            clearMap: function() {
                this.points = [];
                if (this.polyLine) this.map.removeLayer(this.polyLine);
                this.polyLine = null;
                document.getElementById('inp_psu_wkt').value = '';
            },

            loadWKT: function(wktString) {
                this.clearMap();
                if (!wktString || typeof wellknown === 'undefined') return;
                try {
                    const geo = wellknown.parse(wktString);
                    if (geo && geo.type === 'LineString') {
                        this.points = geo.coordinates.map(c => [c[1], c[0]]);
                    } else if (geo && geo.type === 'Point') {
                        this.points = [[geo.coordinates[1], geo.coordinates[0]]];
                    }
                    this.drawPolyline();
                    this.updateWKT();
                } catch(e) { console.error('Error parsing WKT:', e); }
            },

            openAdd: function() {
                const form = document.getElementById('form-psu');
                if (!form) return;
                form.reset();
                form.action = <?= json_encode(base_url('psu/store')) ?>;
                
                const title = document.getElementById('modal-psu-title');
                if (title) title.innerText = "Tambah Data PSU Jalan";
                
                ['before', 'after'].forEach(p => {
                    const img = document.getElementById('img_psu_' + p);
                    const ph = document.getElementById('placeholder_psu_' + p);
                    if (img) img.classList.add('hidden');
                    if (ph) ph.classList.remove('hidden');
                });

                this.clearMap();
                if (window.UI) UI.openModal('modal-psu');
            },

            openEdit: function(data) {
                if (!data) return;
                
                const form = document.getElementById('form-psu');
                if (!form) return;
                form.reset();
                form.action = <?= json_encode(base_url('psu/update')) ?> + '/' + (data.id || 0);
                
                const title = document.getElementById('modal-psu-title');
                if (title) title.innerText = "Edit Data PSU Jalan";
                
                const fields = ['nama_jalan', 'tahun', 'panjang_luas', 'jalan'];
                fields.forEach(f => {
                    const el = document.getElementById('inp_psu_' + (f === 'panjang_luas' ? 'panjang' : f));
                    if (el) el.value = data[f] || '';
                });

                ['before', 'after'].forEach(p => {
                    const img = document.getElementById('img_psu_' + p);
                    const ph = document.getElementById('placeholder_psu_' + p);
                    const file = data['foto_' + p];
                    if (img) {
                        if (file) {
                            img.src = this.uploadUrl + file;
                            img.classList.remove('hidden');
                            if (ph) ph.classList.add('hidden');
                        } else {
                            img.classList.add('hidden');
                            if (ph) ph.classList.remove('hidden');
                        }
                    }
                });

                if (window.UI) UI.openModal('modal-psu');
                setTimeout(() => {
                    this.loadWKT(data.wkt);
                }, 500);
            },

            previewImg: function(input, id) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.getElementById('img_psu_' + id);
                        const ph = document.getElementById('placeholder_psu_' + id);
                        if (img) { img.src = e.target.result; img.classList.remove('hidden'); }
                        if (ph) ph.classList.add('hidden');
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }
        };

        document.addEventListener('DOMContentLoaded', () => {
            psuModal.init();
        });
    })();
</script>
