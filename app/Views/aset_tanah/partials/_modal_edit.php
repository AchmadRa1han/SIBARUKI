<!-- ASET TANAH MODAL COMPONENT -->
<div id="modal-aset" class="fixed inset-0 z-[10002] hidden overflow-y-auto py-10 px-4">
    <div class="fixed inset-0 bg-blue-950/60 backdrop-blur-sm transition-opacity" onclick="UI.closeModal('modal-aset')"></div>
    <div class="relative flex items-center justify-center p-4 min-h-full">
        <div class="bg-white dark:bg-slate-900 w-full max-w-5xl rounded-[2.5rem] shadow-2xl overflow-hidden animate-in zoom-in duration-300">
            <!-- Modal Header -->
            <div class="p-8 bg-blue-950 text-white flex justify-between items-center border-b border-white/10 sticky top-0 z-30">
                <div class="flex items-center gap-5">
                    <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-md border border-white/10">
                        <i data-lucide="map" class="w-6 h-6 text-emerald-400"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black uppercase tracking-tighter" id="modal-aset-title">Data Aset Tanah</h3>
                        <p class="text-[8px] font-bold uppercase tracking-widest text-white/60 mt-1">Registri Inventaris Tanah Pemerintah Daerah</p>
                    </div>
                </div>
                <button type="button" onclick="UI.closeModal('modal-aset')" class="p-2 hover:bg-white/10 rounded-xl transition-colors"><i data-lucide="x" class="w-6 h-6"></i></button>
            </div>

            <form id="form-aset" action="<?= base_url('aset-tanah/store') ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="p-8 grid grid-cols-1 lg:grid-cols-2 gap-10 bg-slate-100 dark:bg-slate-950">
                    <!-- Column 1: Legalitas -->
                    <div class="space-y-6">
                        <h4 class="text-[10px] font-black text-blue-600 uppercase border-b pb-3 tracking-widest flex items-center gap-2">
                            <i data-lucide="shield-check" class="w-3.5 h-3.5"></i> Legalitas & Kepemilikan
                        </h4>

                        <div>
                            <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Nama Pemilik / Aset</label>
                            <input type="text" name="nama_pemilik" id="inp_aset_pemilik" required class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 outline-none transition-all font-bold uppercase" placeholder="PEMDA KAB. SINJAI...">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Status Sertifikat</label>
                                <select id="inp_aset_is_sertifikat" onchange="asetModal.toggleSertifikat()" class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl outline-none font-bold uppercase">
                                    <option value="1">Bersertifikat</option>
                                    <option value="0">Belum Bersertifikat</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Status Tanah</label>
                                <select name="status_tanah" id="inp_aset_status_tanah" class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl outline-none font-bold uppercase">
                                    <option value="Hak Pakai">Hak Pakai</option>
                                    <option value="Hak Milik">Hak Milik</option>
                                    <option value="Hak Guna Bangunan">Hak Guna Bangunan</option>
                                    <option value="Tanah Negara">Tanah Garapan / Negara</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 serts-only">
                            <div>
                                <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Nomor Sertifikat</label>
                                <input type="text" name="no_sertifikat" id="inp_aset_no_sertifikat" class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl outline-none font-bold uppercase">
                            </div>
                            <div>
                                <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Nomor Hak</label>
                                <input type="text" name="nomor_hak" id="inp_aset_nomor_hak" class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl outline-none font-bold uppercase">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="serts-only">
                                <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Tanggal Terbit</label>
                                <input type="date" name="tgl_terbit" id="inp_aset_tgl_terbit" class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl outline-none font-bold">
                            </div>
                            <div>
                                <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Peruntukan</label>
                                <input type="text" name="peruntukan" id="inp_aset_peruntukan" class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl outline-none font-bold uppercase" placeholder="KANTOR / FASUM...">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-indigo-50 dark:bg-indigo-900/20 p-4 rounded-2xl border border-indigo-100 dark:border-indigo-900/50">
                                <label class="block text-[8px] font-bold text-indigo-900 dark:text-indigo-400 uppercase mb-1 tracking-widest ml-1">Luas (M²)</label>
                                <input type="number" step="0.01" name="luas_m2" id="inp_aset_luas" required class="w-full bg-transparent border-none text-lg font-bold text-indigo-950 dark:text-white p-0 focus:ring-0 outline-none" placeholder="0.00">
                            </div>
                            <div class="bg-emerald-50 dark:bg-emerald-950/30 p-4 rounded-2xl border border-emerald-100 dark:border-emerald-900/50">
                                <label class="block text-[8px] font-bold text-emerald-900 dark:text-emerald-400 uppercase mb-1 tracking-widest ml-1">Nilai Aset (Rp)</label>
                                <input type="number" name="nilai_aset" id="inp_aset_nilai" required class="w-full bg-transparent border-none text-lg font-bold text-emerald-950 dark:text-white p-0 focus:ring-0 outline-none" placeholder="0">
                            </div>
                        </div>
                    </div>

                    <!-- Column 2: Lokasi & Map -->
                    <div class="space-y-6">
                        <h4 class="text-[10px] font-black text-indigo-600 uppercase border-b pb-3 tracking-widest flex items-center gap-2">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5"></i> Penempatan Spasial
                        </h4>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Kecamatan</label>
                                <select name="kecamatan" id="inp_aset_kecamatan" onchange="asetModal.loadDesa(this.value)" required class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl outline-none font-bold uppercase appearance-none">
                                    <option value="">Pilih</option>
                                    <?php foreach(($kecamatans ?? []) as $k): ?>
                                        <option value="<?= $k['kecamatan'] ?>"><?= $k['kecamatan'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Desa / Kelurahan</label>
                                <select name="desa_kelurahan" id="inp_aset_desa" required class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl outline-none font-bold uppercase appearance-none">
                                    <option value="">Pilih Desa</option>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="h-60 rounded-2xl overflow-hidden border-2 border-slate-200 dark:border-slate-800 shadow-inner relative">
                                <div id="modalMapAset" class="w-full h-full z-10"></div>
                            </div>
                            <input type="text" name="koordinat" id="inp_aset_koordinat" readonly class="w-full p-3.5 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-900/50 rounded-xl outline-none font-mono text-[10px] text-blue-600 font-bold" placeholder="Klik pada peta untuk mengambil titik...">
                        </div>

                        <div>
                            <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Alamat / Lokasi Detail</label>
                            <textarea name="lokasi" id="inp_aset_lokasi" rows="2" class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl outline-none font-bold uppercase text-xs" placeholder="MASUKKAN ALAMAT LENGKAP..."></textarea>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Keterangan Tambahan</label>
                        <textarea name="keterangan" id="inp_aset_keterangan" rows="2" class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl outline-none font-bold text-xs" placeholder="CATATAN TAMBAHAN..."></textarea>
                    </div>
                </div>

                <!-- Footer -->
                <div class="p-6 bg-slate-50 dark:bg-slate-900 border-t dark:border-slate-800 flex justify-end gap-3 rounded-b-[2.5rem]">
                    <button type="button" onclick="UI.closeModal('modal-aset')" class="px-6 py-2.5 text-slate-500 font-bold uppercase tracking-widest text-[10px] hover:text-rose-500 transition-colors">Batal</button>
                    <button type="submit" id="btn-aset-submit" class="px-8 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-black uppercase tracking-widest text-[10px] shadow-lg shadow-blue-600/20 active:scale-95 transition-all flex items-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i> Simpan Aset
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    (function() {
        window.asetModal = {
            map: null,
            marker: null,

            init: function() {
                window.addEventListener('modalOpened', (e) => {
                    if (e && e.detail && e.detail.id === 'modal-aset') {
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
                    const standard = L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png');
                    const googleSat = L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', { maxZoom: 20, subdomains:['mt0','mt1','mt2','mt3'] });

                    this.map = L.map('modalMapAset', { zoomControl: false, layers: [googleSat] }).setView([-5.1245, 120.2536], 12);
                    L.control.zoom({ position: 'topright' }).addTo(this.map);

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
                        document.getElementById('inp_aset_koordinat').value = `${pos.lat.toFixed(7)}, ${pos.lng.toFixed(7)}`;
                    });
                }
                document.getElementById('inp_aset_koordinat').value = `${lat.toFixed(7)}, ${lng.toFixed(7)}`;
                this.map.setView([lat, lng], 18);
            },

            loadDesa: function(kec, selectedDesa = '') {
                const ds = document.getElementById('inp_aset_desa');
                if (!kec) { ds.innerHTML = '<option value="">Pilih Desa</option>'; return; }
                
                ds.innerHTML = '<option value="">Memuat...</option>';
                fetch(`<?= base_url('aset-tanah/get-desa') ?>?kecamatan=${kec}`)
                    .then(r => r.json())
                    .then(data => {
                        ds.innerHTML = '<option value="">Pilih Desa</option>';
                        data.forEach(d => {
                            const opt = document.createElement('option');
                            opt.value = d.desa_nama;
                            opt.innerText = d.desa_nama;
                            if (d.desa_nama === selectedDesa) opt.selected = true;
                            ds.appendChild(opt);
                        });
                    });
            },

            toggleSertifikat: function() {
                const isSert = document.getElementById('inp_aset_is_sertifikat').value === "1";
                document.querySelectorAll('.serts-only').forEach(el => {
                    el.classList.toggle('hidden', !isSert);
                    const input = el.querySelector('input');
                    if (input && !isSert) input.required = false;
                    if (input && isSert && input.id === 'inp_aset_no_sertifikat') input.required = true;
                });
            },

            openAdd: function() {
                const form = document.getElementById('form-aset');
                if (!form) return;
                form.reset();
                form.action = <?= json_encode(base_url('aset-tanah/store')) ?>;
                
                document.getElementById('modal-aset-title').innerText = "Tambah Bidang Tanah";
                document.getElementById('inp_aset_is_sertifikat').value = "1";
                this.toggleSertifikat();
                
                if (this.marker && this.map) {
                    this.map.removeLayer(this.marker);
                    this.marker = null;
                }

                if (window.UI) UI.openModal('modal-aset');
            },

            openEdit: function(data) {
                if (!data) return;
                const form = document.getElementById('form-aset');
                if (!form) return;
                form.reset();
                form.action = <?= json_encode(base_url('aset-tanah/update')) ?> + '/' + (data.id || 0);
                
                document.getElementById('modal-aset-title').innerText = "Edit Data Aset";
                
                const isSert = (data.no_sertifikat && data.no_sertifikat !== '-' && data.no_sertifikat !== 'Belum Bersertifikat');
                document.getElementById('inp_aset_is_sertifikat').value = isSert ? "1" : "0";
                this.toggleSertifikat();

                const fields = {
                    'inp_aset_pemilik': data.nama_pemilik,
                    'inp_aset_status_tanah': data.status_tanah,
                    'inp_aset_no_sertifikat': data.no_sertifikat,
                    'inp_aset_nomor_hak': data.nomor_hak,
                    'inp_aset_tgl_terbit': data.tgl_terbit,
                    'inp_aset_peruntukan': data.peruntukan,
                    'inp_aset_luas': data.luas_m2,
                    'inp_aset_nilai': data.nilai_aset,
                    'inp_aset_kecamatan': data.kecamatan,
                    'inp_aset_koordinat': data.koordinat,
                    'inp_aset_lokasi': data.lokasi,
                    'inp_aset_keterangan': data.keterangan
                };

                for (const id in fields) {
                    const el = document.getElementById(id);
                    if (el) el.value = fields[id] || '';
                }

                this.loadDesa(data.kecamatan, data.desa_kelurahan);

                const btn = document.getElementById('btn-aset-submit');
                if (btn) {
                    btn.className = "px-8 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-black uppercase tracking-widest text-[10px] shadow-lg shadow-blue-600/20 active:scale-95 transition-all flex items-center gap-2";
                    btn.innerHTML = '<i data-lucide="save" class="w-4 h-4"></i> Simpan Perubahan';
                }

                if (window.UI) UI.openModal('modal-aset');
                
                if (data.koordinat) {
                    const coords = data.koordinat.split(',').map(c => parseFloat(c.trim()));
                    if (coords.length === 2 && !isNaN(coords[0])) {
                        setTimeout(() => this.setMarker(coords[0], coords[1]), 500);
                    }
                }
            }
        };

        document.addEventListener('DOMContentLoaded', () => {
            asetModal.init();
        });
    })();
</script>
