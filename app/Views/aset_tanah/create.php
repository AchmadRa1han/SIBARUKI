<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto space-y-6 pb-32 text-slate-900 dark:text-slate-200">
    
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-3 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 no-print">
        <a href="<?= base_url('dashboard') ?>" class="hover:text-blue-600 transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <a href="<?= base_url('aset-tanah') ?>" class="hover:text-blue-600 transition-colors">Aset Tanah</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">Tambah Bidang</span>
    </nav>

    <!-- Header Action -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm transition-all duration-300 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-48 h-48 bg-blue-600/5 rounded-full -mr-24 -mt-24 blur-3xl"></div>
        <div class="relative z-10 flex items-center gap-4">
            <a href="<?= base_url('aset-tanah') ?>" class="p-3 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl hover:bg-blue-600 hover:text-white transition-all active:scale-95" title="Kembali">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-blue-600/20">
                    <i data-lucide="plus" class="w-7 h-7"></i>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-blue-950 dark:text-white uppercase tracking-tighter">Tambah Aset Tanah</h1>
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">Registrasi Inventaris Tanah Pemerintah Daerah</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3 relative z-10">
            <a href="<?= base_url('aset-tanah') ?>" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-slate-700 transition-all active:scale-95">Batal</a>
        </div>
    </div>

    <!-- 2. FORM MANUAL -->
    <form action="<?= base_url('aset-tanah/store') ?>" method="post" id="aset-form">
        <?= csrf_field() ?>
        <div class="space-y-10">
            <!-- SECTION 1: LEGALITAS & PEMILIK -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all duration-300">
                <div class="p-6 border-b dark:border-slate-800 bg-blue-50/30 dark:bg-blue-950/30 flex items-center gap-3">
                    <div class="w-9 h-9 bg-blue-600 rounded-lg flex items-center justify-center text-white shadow-lg">
                        <i data-lucide="clipboard-list" class="w-4.5 h-4.5"></i>
                    </div>
                    <div>
                        <h3 class="text-[11px] font-bold text-blue-900 dark:text-blue-400 uppercase tracking-[0.2em]">Legalitas & Kepemilikan</h3>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Informasi Berdasarkan Sertifikat Resmi</p>
                    </div>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2">
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Nama Pemilik / Nama Aset</label>
                        <input type="text" name="nama_pemilik" required class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase placeholder:opacity-30" placeholder="PEMDA KAB. SINJAI...">
                    </div>
                    <div class="lg:col-span-1">
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Status Sertifikat</label>
                        <div class="relative">
                            <select id="status_sertifikat_select" name="status_sertifikat_dummy" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all appearance-none font-bold uppercase" onchange="toggleSertifikat()">
                                <option value="Bersertifikat">Bersertifikat</option>
                                <option value="Belum Bersertifikat">Belum Bersertifikat</option>
                            </select>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                        </div>
                    </div>
                    <div id="no_sertifikat_wrapper">
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Nomor Sertifikat</label>
                        <input type="text" id="no_sertifikat_input" name="no_sertifikat" required class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Nomor Hak</label>
                        <input type="text" name="nomor_hak" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                    </div>
                    <div id="tgl_terbit_wrapper">
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Tanggal Terbit Sertifikat</label>
                        <input type="date" id="tgl_terbit_input" name="tgl_terbit" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Status Tanah</label>
                        <select name="status_tanah" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all appearance-none font-bold uppercase">
                            <option value="Hak Pakai">Hak Pakai</option>
                            <option value="Hak Milik">Hak Milik</option>
                            <option value="Hak Guna Bangunan">Hak Guna Bangunan</option>
                            <option value="Tanah Negara">Tanah Garapan / Negara</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: DIMENSI & LOKASI -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all duration-300">
                <div class="p-6 border-b dark:border-slate-800 bg-indigo-50/30 dark:bg-indigo-950/30 flex items-center gap-3">
                    <div class="w-9 h-9 bg-indigo-600 rounded-lg flex items-center justify-center text-white shadow-lg">
                        <i data-lucide="map" class="w-4.5 h-4.5"></i>
                    </div>
                    <div>
                        <h3 class="text-[11px] font-bold text-indigo-900 dark:text-indigo-400 uppercase tracking-[0.2em]">Dimensi & Lokasi Bidang</h3>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Parameter Fisik & Penempatan Wilayah</p>
                    </div>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-indigo-50 dark:bg-indigo-950/30 p-5 rounded-2xl border border-indigo-100 dark:border-indigo-900/50">
                        <label class="block text-[8px] font-bold text-indigo-900 dark:text-indigo-400 uppercase mb-2 tracking-widest ml-1">Luas Tanah (M²)</label>
                        <input type="number" step="0.01" name="luas_m2" required class="w-full bg-transparent border-none text-xl font-bold text-indigo-950 dark:text-white p-0 focus:ring-0 outline-none placeholder:opacity-20" placeholder="0.00">
                    </div>
                    <div class="bg-emerald-50 dark:bg-emerald-950/30 p-5 rounded-2xl border border-emerald-100 dark:border-emerald-900/50">
                        <label class="block text-[8px] font-bold text-emerald-900 dark:text-emerald-400 uppercase mb-2 tracking-widest ml-1">Nilai Aset (Rp)</label>
                        <input type="number" name="nilai_aset" required class="w-full bg-transparent border-none text-xl font-bold text-emerald-950 dark:text-white p-0 focus:ring-0 outline-none placeholder:opacity-20" placeholder="0">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Peruntukan</label>
                        <input type="text" name="peruntukan" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 dark:text-slate-200 outline-none transition-all font-bold uppercase" placeholder="KANTOR / FASUM...">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Kecamatan</label>
                        <div class="relative">
                            <select name="kecamatan" id="kecamatan_select" required class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all appearance-none font-bold uppercase" onchange="loadDesa()">
                                <option value="">Pilih Kecamatan</option>
                                <?php foreach($kecamatans as $k): ?>
                                    <option value="<?= $k['Kecamatan'] ?>"><?= $k['Kecamatan'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Desa / Kelurahan</label>
                        <div class="relative">
                            <select name="desa_kelurahan" id="desa_select" required class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all appearance-none font-bold uppercase">
                                <option value="">Pilih Desa</option>
                            </select>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                        </div>
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Pilih Lokasi di Peta</label>
                        <div id="map-picker" class="w-full h-80 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-inner overflow-hidden mb-4"></div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Koordinat (Lat, Long)</label>
                        <input type="text" name="koordinat" id="koordinat_input" readonly class="w-full p-3.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-0 dark:text-slate-200 outline-none transition-all font-mono text-xs cursor-not-allowed" placeholder="Klik pada peta untuk mengambil koordinat...">
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Alamat Lengkap / Lokasi Detail</label>
                        <textarea name="lokasi" rows="2" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 dark:text-slate-200 outline-none transition-all font-bold placeholder:opacity-30" placeholder="MASUKKAN ALAMAT LENGKAP..."></textarea>
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Keterangan Tambahan</label>
                        <textarea name="keterangan" rows="2" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 dark:text-slate-200 outline-none transition-all font-bold placeholder:opacity-30" placeholder="CATATAN TAMBAHAN..."></textarea>
                    </div>
                </div>
            </div>

            <!-- ACTION BAR -->
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 pt-4">
                <div class="flex items-center gap-3 text-slate-400">
                    <i data-lucide="info" class="w-4 h-4"></i>
                    <p class="text-[9px] font-bold uppercase tracking-widest leading-relaxed max-w-md">Metadata aset tanah akan diintegrasikan dengan modul geospasial untuk pemantauan aset daerah.</p>
                </div>
                <button type="submit" class="group flex items-center space-x-6 bg-blue-600 hover:bg-blue-700 text-white pl-8 pr-4 py-4 rounded-xl font-bold shadow-xl shadow-blue-600/20 transition-all active:scale-95 w-full md:w-auto">
                    <div class="flex flex-col text-right">
                        <span class="text-[8px] uppercase tracking-[0.3em] opacity-60 mb-0.5">Konfirmasi Final</span>
                        <span class="text-base uppercase tracking-tighter">Simpan Aset</span>
                    </div>
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center group-hover:translate-x-1 transition-transform">
                        <i data-lucide="save" class="w-5 h-5"></i>
                    </div>
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Leaflet Setup -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    let map, marker;

    function initMap() {
        // Default Sinjai Coordinates
        const defaultLat = -5.123;
        const defaultLng = 120.211;

        const isDark = document.documentElement.classList.contains('dark');
        
        // Base Layers
        const street = L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>'
        });

        const satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EBP, and the GIS User Community'
        });

        map = L.map('map-picker', {
            center: [defaultLat, defaultLng],
            zoom: 12,
            layers: [street],
            zoomControl: false
        });

        // Add Zoom Control at top-right
        L.control.zoom({ position: 'topright' }).addTo(map);

        // Better Layer Toggle Integration
        let rot = 0;
        const LayerToggle = L.Control.extend({
            onAdd: function(map) {
                const btn = L.DomUtil.create('button', 'bg-white dark:bg-slate-900 rounded-lg shadow-xl border border-slate-100 dark:border-slate-800 transition-all duration-300 active:scale-90 mt-2 flex items-center justify-center');
                btn.style.width = '38px'; btn.style.height = '38px'; btn.style.cursor = 'pointer';
                btn.type = 'button';
                const svgColor = isDark ? '#60a5fa' : '#2563eb';
                btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="${svgColor}" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:block; transition: transform 0.8s cubic-bezier(0.65, 0, 0.35, 1);"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>`;
                
                L.DomEvent.disableClickPropagation(btn);
                L.DomEvent.on(btn, 'click', function(e) {
                    L.DomEvent.stopPropagation(e);
                    L.DomEvent.preventDefault(e);
                    rot += 360;
                    const svg = btn.querySelector('svg');
                    svg.style.transform = `rotate(${rot}deg)`;
                    setTimeout(() => {
                        if (map.hasLayer(street)) { 
                            map.removeLayer(street); 
                            map.addLayer(satellite); 
                            btn.style.backgroundColor = '#2563eb'; 
                            svg.setAttribute('stroke', '#ffffff'); 
                        } else { 
                            map.removeLayer(satellite); 
                            map.addLayer(street); 
                            btn.style.backgroundColor = isDark ? '#0f172a' : '#ffffff'; 
                            svg.setAttribute('stroke', svgColor); 
                        }
                    }, 200);
                });
                return btn;
            }
        });
        map.addControl(new LayerToggle({ position: 'topright' }));

        map.on('click', function(e) {
            const lat = e.latlng.lat.toFixed(7);
            const lng = e.latlng.lng.toFixed(7);
            
            if (marker) {
                marker.setLatLng(e.latlng);
            } else {
                marker = L.marker(e.latlng, { draggable: true }).addTo(map);
                marker.on('dragend', function(event) {
                    const pos = event.target.getLatLng();
                    document.getElementById('koordinat_input').value = pos.lat.toFixed(7) + ', ' + pos.lng.toFixed(7);
                });
            }
            
            document.getElementById('koordinat_input').value = lat + ', ' + lng;
        });
    }

    async function loadDesa() {
        const kecamatan = document.getElementById('kecamatan_select').value;
        const desaSelect = document.getElementById('desa_select');
        
        desaSelect.innerHTML = '<option value="">Memuat...</option>';
        
        if (!kecamatan) {
            desaSelect.innerHTML = '<option value="">Pilih Desa</option>';
            return;
        }

        try {
            const response = await fetch(`<?= base_url('aset-tanah/get-desa') ?>?kecamatan=${kecamatan}`);
            const data = await response.json();
            
            desaSelect.innerHTML = '<option value="">Pilih Desa</option>';
            data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.desa;
                option.textContent = item.desa;
                desaSelect.appendChild(option);
            });
        } catch (error) {
            console.error('Failed to load Desa:', error);
            desaSelect.innerHTML = '<option value="">Gagal memuat data</option>';
        }
    }

    function toggleSertifikat() {
        const status = document.getElementById('status_sertifikat_select').value;
        const noWrapper = document.getElementById('no_sertifikat_wrapper');
        const tglWrapper = document.getElementById('tgl_terbit_wrapper');
        const noInput = document.getElementById('no_sertifikat_input');
        const tglInput = document.getElementById('tgl_terbit_input');
        
        if (status === 'Belum Bersertifikat') {
            noWrapper.style.display = 'none';
            tglWrapper.style.display = 'none';
            noInput.value = 'Belum Bersertifikat';
            tglInput.value = '';
        } else {
            noWrapper.style.display = 'block';
            tglWrapper.style.display = 'block';
            if (noInput.value === 'Belum Bersertifikat') {
                noInput.value = '';
            }
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
        toggleSertifikat();
        initMap();
    });
</script>
<?= $this->endSection() ?>
