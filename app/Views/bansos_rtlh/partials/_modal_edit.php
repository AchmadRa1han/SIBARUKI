<!-- BANSOS RTLH MODAL COMPONENT -->
<div id="modal-bansos" class="fixed inset-0 z-[10002] hidden overflow-y-auto py-10 px-4">
    <div class="fixed inset-0 bg-blue-950/60 backdrop-blur-sm transition-opacity" onclick="UI.closeModal('modal-bansos')"></div>
    <div class="relative flex items-center justify-center p-4 min-h-full">
        <div class="bg-white dark:bg-slate-900 w-full max-w-4xl rounded-[2.5rem] shadow-2xl overflow-hidden animate-in zoom-in duration-300">
            <!-- Modal Header -->
            <div class="p-8 bg-blue-950 text-white flex justify-between items-center border-b border-white/10 sticky top-0 z-30">
                <div class="flex items-center gap-5">
                    <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-md border border-white/10">
                        <i data-lucide="award" class="w-6 h-6 text-blue-400"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black uppercase tracking-tighter" id="modal-bansos-title">Input Realisasi Bansos</h3>
                        <p class="text-[8px] font-bold uppercase tracking-widest text-white/60 mt-1">Catat Keberhasilan Perbaikan Rumah</p>
                    </div>
                </div>
                <button type="button" onclick="UI.closeModal('modal-bansos')" class="p-2 hover:bg-white/10 rounded-xl transition-colors"><i data-lucide="x" class="w-6 h-6"></i></button>
            </div>

            <form id="form-bansos" action="<?= base_url('bansos-rtlh/store') ?>" method="POST" enctype="multipart/form-data" class="p-10 space-y-10">
                <?= csrf_field() ?>
                
                <!-- Pilihan Data RTLH (Optional linking) -->
                <div class="bg-blue-50/50 dark:bg-blue-950/20 p-6 rounded-3xl border border-blue-100/50 dark:border-blue-900/30">
                    <label class="block text-[10px] font-bold text-blue-900 dark:text-blue-400 uppercase mb-3 tracking-widest flex items-center gap-2">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i> Hubungkan dengan Data Survei RTLH (Opsional)
                    </label>
                    <select name="id_survei" id="inp_bansos_id_survei" onchange="bansosModal.fillFromRtlh(this)" class="w-full p-4 bg-white dark:bg-slate-900 border border-blue-200 dark:border-blue-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 outline-none transition-all font-bold text-sm shadow-sm">
                        <option value="">-- Input Manual (Bukan dari Data Survei) --</option>
                        <?php foreach(($rtlh ?? []) as $r): ?>
                            <option value="<?= $r['id_survei'] ?>" data-nik="<?= $r['nik'] ?>" data-nama="<?= $r['nama_kepala_keluarga'] ?>" data-desa="<?= $r['desa'] ?>">
                                [ID: <?= $r['id_survei'] ?>] <?= $r['nama_kepala_keluarga'] ?> - <?= $r['desa'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p class="mt-2 text-[9px] text-blue-600/60 font-medium italic">*Jika dipilih, status rumah tersebut akan otomatis menjadi RLH (Tuntas).</p>
                </div>

                <!-- Identitas Penerima -->
                <div class="space-y-6">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] flex items-center gap-3">
                        <span class="w-8 h-[2px] bg-slate-200 dark:bg-slate-800"></span> Identitas Penerima
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">NIK Penerima</label>
                            <input type="text" name="nik" id="inp_bansos_nik" placeholder="Masukkan 16 digit NIK" class="w-full p-4 bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-white outline-none transition-all font-bold" required>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                            <input type="text" name="nama_penerima" id="inp_bansos_nama" placeholder="Nama sesuai KTP" class="w-full p-4 bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-white outline-none transition-all font-bold" required>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Wilayah Desa</label>
                            <input type="text" name="desa" id="inp_bansos_desa" placeholder="Nama Desa / Kelurahan" class="w-full p-4 bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-white outline-none transition-all font-bold" required>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Tahun Anggaran</label>
                            <input type="number" name="tahun_anggaran" id="inp_bansos_tahun" value="<?= date('Y') ?>" min="2000" max="2099" class="w-full p-4 bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-white outline-none transition-all font-bold" required>
                        </div>
                    </div>
                </div>

                <!-- Detail Program & Lokasi -->
                <div class="space-y-6">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] flex items-center gap-3">
                        <span class="w-8 h-[2px] bg-slate-200 dark:bg-slate-800"></span> Detail Program & Lokasi Realisasi
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Sumber Dana / Nama Program</label>
                            <input type="text" name="sumber_dana" id="inp_bansos_sumber" placeholder="Contoh: BSPS, APBD Sinjai, DAK Bidang Perumahan" class="w-full p-4 bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-white outline-none transition-all font-bold" required>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Keterangan Tambahan</label>
                            <input type="text" name="keterangan" id="inp_bansos_ket" placeholder="Informasi tambahan..." class="w-full p-4 bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-white outline-none transition-all font-bold">
                        </div>
                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Koordinat Realisasi (Map Picker)</label>
                            <div class="rounded-[2rem] overflow-hidden border border-slate-200 dark:border-slate-800 shadow-inner">
                                <div id="modalMapBansos" class="w-full h-72 z-10"></div>
                            </div>
                            <input type="text" name="lokasi_realisasi" id="inp_bansos_lokasi" placeholder="POINT(lng lat)" class="w-full p-4 bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl font-mono text-xs font-bold text-emerald-600 outline-none" readonly>
                        </div>
                    </div>
                </div>

                <!-- Dokumentasi Realisasi -->
                <div class="space-y-6">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] flex items-center gap-3">
                        <span class="w-8 h-[2px] bg-slate-200 dark:bg-slate-800"></span> Dokumentasi Realisasi (Before & After)
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="space-y-3">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Kondisi Awal (Before)</label>
                            <div class="relative group aspect-square bg-slate-100 dark:bg-slate-950 border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-2xl flex flex-col items-center justify-center overflow-hidden transition-all hover:border-emerald-500/50">
                                <input type="file" name="foto_before" accept="image/*" class="absolute inset-0 opacity-0 z-10 cursor-pointer" onchange="bansosModal.previewImg(this, 'before')">
                                <div id="placeholder_bansos_before" class="flex flex-col items-center justify-center">
                                    <i data-lucide="camera" class="w-6 h-6 text-slate-300 mb-2"></i>
                                    <span class="text-[7px] font-bold text-slate-400 uppercase">Pilih Foto</span>
                                </div>
                                <img id="img_bansos_before" class="absolute inset-0 w-full h-full object-cover hidden">
                            </div>
                        </div>

                        <?php 
                            $afterFotos = [['foto_setelah_depan', 'Tampak Depan (After)'], ['foto_setelah_samping', 'Samping (After)'], ['foto_setelah_dalam', 'Interior (After)']];
                            foreach($afterFotos as $f):
                        ?>
                        <div class="space-y-3">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1"><?= $f[1] ?></label>
                            <div class="relative group aspect-square bg-slate-100 dark:bg-slate-950 border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-2xl flex flex-col items-center justify-center overflow-hidden transition-all hover:border-emerald-500/50">
                                <input type="file" name="<?= $f[0] ?>" accept="image/*" class="absolute inset-0 opacity-0 z-10 cursor-pointer" onchange="bansosModal.previewImg(this, '<?= $f[0] ?>')">
                                <div id="placeholder_bansos_<?= $f[0] ?>" class="flex flex-col items-center justify-center">
                                    <i data-lucide="camera" class="w-6 h-6 text-slate-300 mb-2"></i>
                                    <span class="text-[7px] font-bold text-slate-400 uppercase">Pilih Foto</span>
                                </div>
                                <img id="img_bansos_<?= $f[0] ?>" class="absolute inset-0 w-full h-full object-cover hidden">
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" id="btn-submit-bansos" class="w-full py-5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-[2rem] text-sm font-bold uppercase tracking-[0.2em] shadow-xl shadow-emerald-900/20 transition-all active:scale-[0.98] flex items-center justify-center gap-3">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    (function() {
        const BANSOS_UPLOAD_URL = <?= json_encode(base_url('uploads/rtlh/')) ?>;
        
        window.bansosModal = {
            map: null,
            marker: null,
            uploadUrl: BANSOS_UPLOAD_URL.endsWith('/') ? BANSOS_UPLOAD_URL : BANSOS_UPLOAD_URL + '/',

            init: function() {
                window.addEventListener('modalOpened', (e) => {
                    if (e && e.detail && e.detail.id === 'modal-bansos') {
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

                    this.map = L.map('modalMapBansos', { zoomControl: false, layers: [googleSat] }).setView([-5.1245, 120.2536], 12);
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

                    this.map.on('click', (e) => this.setMarker(e.latlng.lat, e.latlng.lng));
                    setTimeout(() => this.map.invalidateSize(), 300);
                } catch (err) { console.error('Modal map init error:', err); }
            },

            setMarker: function(lat, lng) {
                if (this.marker) {
                    this.marker.setLatLng([lat, lng]);
                } else {
                    this.marker = L.marker([lat, lng], { draggable: true }).addTo(this.map);
                    this.marker.on('dragend', (e) => {
                        const pos = e.target.getLatLng();
                        const elCoords = document.getElementById('inp_bansos_lokasi');
                        if (elCoords) elCoords.value = `POINT(${pos.lng.toFixed(7)} ${pos.lat.toFixed(7)})`;
                    });
                }
                const elCoords = document.getElementById('inp_bansos_lokasi');
                if (elCoords) elCoords.value = `POINT(${lng.toFixed(7)} ${lat.toFixed(7)})`;
                this.map.setView([lat, lng], 18);
            },

            fillFromRtlh: function(select) {
                const option = select.options[select.selectedIndex];
                const elNik = document.getElementById('inp_bansos_nik');
                const elNama = document.getElementById('inp_bansos_nama');
                const elDesa = document.getElementById('inp_bansos_desa');
                
                if (option && option.value) {
                    elNik.value = option.getAttribute('data-nik');
                    elNama.value = option.getAttribute('data-nama');
                    elDesa.value = option.getAttribute('data-desa');
                    
                    elNik.readOnly = true; elNik.classList.add('opacity-60');
                    elNama.readOnly = true; elNama.classList.add('opacity-60');
                    elDesa.readOnly = true; elDesa.classList.add('opacity-60');
                } else {
                    elNik.value = ''; elNik.readOnly = false; elNik.classList.remove('opacity-60');
                    elNama.value = ''; elNama.readOnly = false; elNama.classList.remove('opacity-60');
                    elDesa.value = ''; elDesa.readOnly = false; elDesa.classList.remove('opacity-60');
                }
            },

            openAdd: function() {
                const form = document.getElementById('form-bansos');
                if (!form) return;
                
                form.reset();
                form.action = <?= json_encode(base_url('bansos-rtlh/store')) ?>;
                
                const title = document.getElementById('modal-bansos-title');
                if (title) title.innerText = "Input Realisasi Bansos";

                const btn = document.getElementById('btn-submit-bansos');
                if (btn) btn.innerHTML = '<i data-lucide="check-circle" class="w-5 h-5"></i> Simpan & Update Status RTLH';
                
                // Unset readonly for manuals
                ['nik', 'nama', 'desa'].forEach(id => {
                    const el = document.getElementById('inp_bansos_' + id);
                    if (el) { el.readOnly = false; el.classList.remove('opacity-60'); }
                });
                
                ['before', 'foto_setelah_depan', 'foto_setelah_samping', 'foto_setelah_dalam'].forEach(p => {
                    const img = document.getElementById('img_bansos_' + p);
                    const ph = document.getElementById('placeholder_bansos_' + p);
                    if (img) img.classList.add('hidden');
                    if (ph) ph.classList.remove('hidden');
                });

                if (this.marker && this.map) {
                    this.map.removeLayer(this.marker);
                    this.marker = null;
                }

                if (window.UI) UI.openModal('modal-bansos');
            },

            openEdit: function(data) {
                if (!data) return;
                
                const form = document.getElementById('form-bansos');
                if (!form) return;
                
                form.reset();
                form.action = <?= json_encode(base_url('bansos-rtlh/update')) ?> + '/' + (data.id || 0);
                
                const title = document.getElementById('modal-bansos-title');
                if (title) title.innerText = "Edit Data Bansos";

                const btn = document.getElementById('btn-submit-bansos');
                if (btn) btn.innerHTML = '<i data-lucide="save" class="w-5 h-5"></i> Simpan Perubahan';
                
                // Populate fields
                const fields = ['id_survei', 'nik', 'nama_penerima', 'desa', 'tahun_anggaran', 'sumber_dana', 'lokasi_realisasi', 'keterangan'];
                fields.forEach(f => {
                    const el = document.getElementById('inp_bansos_' + (f === 'nama_penerima' ? 'nama' : (f === 'tahun_anggaran' ? 'tahun' : (f === 'sumber_dana' ? 'sumber' : (f === 'lokasi_realisasi' ? 'lokasi' : (f === 'keterangan' ? 'ket' : f))))));
                    if (el) el.value = data[f] || '';
                });

                // If id_survei exists, lock manual fields
                if (data.id_survei) {
                    ['nik', 'nama', 'desa'].forEach(id => {
                        const el = document.getElementById('inp_bansos_' + id);
                        if (el) { el.readOnly = true; el.classList.add('opacity-60'); }
                    });
                }

                ['before', 'foto_setelah_depan', 'foto_setelah_samping', 'foto_setelah_dalam'].forEach(p => {
                    const img = document.getElementById('img_bansos_' + p);
                    const ph = document.getElementById('placeholder_bansos_' + p);
                    const file = (p === 'before') ? data.foto_before : data[p];
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

                if (window.UI) UI.openModal('modal-bansos');
                
                if (data.lokasi_realisasi && typeof wellknown !== 'undefined') {
                    try {
                        const geo = wellknown.parse(data.lokasi_realisasi);
                        if (geo && geo.coordinates) {
                            setTimeout(() => this.setMarker(geo.coordinates[1], geo.coordinates[0]), 500);
                        }
                    } catch(e) { console.error('Map parse error:', e); }
                }
            },

            previewImg: function(input, id) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.getElementById('img_bansos_' + id);
                        const ph = document.getElementById('placeholder_bansos_' + id);
                        if (img) { img.src = e.target.result; img.classList.remove('hidden'); }
                        if (ph) ph.classList.add('hidden');
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }
        };

        document.addEventListener('DOMContentLoaded', () => {
            bansosModal.init();
        });
    })();
</script>
