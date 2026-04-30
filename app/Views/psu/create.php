<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto space-y-6 pb-32 text-slate-900 dark:text-slate-200">
    
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-3 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 no-print">
        <a href="<?= base_url('dashboard') ?>" class="hover:text-blue-600 transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <a href="<?= base_url('psu') ?>" class="hover:text-blue-600 transition-colors">PSU JALAN</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">Tambah Data</span>
    </nav>

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm transition-all duration-300 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-48 h-48 bg-blue-600/5 rounded-full -mr-24 -mt-24 blur-3xl"></div>
        <div class="relative z-10 flex items-center gap-4">
            <a href="<?= base_url('psu') ?>" class="p-3 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl hover:bg-blue-600 hover:text-white transition-all active:scale-95" title="Kembali">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-blue-600/20">
                    <i data-lucide="plus" class="w-7 h-7"></i>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-blue-950 dark:text-white uppercase tracking-tighter">Tambah PSU Jalan</h1>
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">Input Inventaris Jaringan Jalan Baru</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3 relative z-10">
            <a href="<?= base_url('psu') ?>" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-slate-700 transition-all active:scale-95">Batal</a>
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
                    <p class="text-[9px] text-emerald-600/70 dark:text-emerald-500/50 font-bold uppercase tracking-[0.2em]">Unggah file koordinat jalan (WKT)</p>
                </div>
            </div>
            <form action="<?= base_url('psu/import-csv') ?>" method="post" enctype="multipart/form-data" class="flex flex-col md:flex-row items-center gap-3 w-full lg:w-auto">
                <?= csrf_field() ?>
                <input type="file" name="csv_file" accept=".csv" required class="block w-full text-[9px] text-emerald-900 dark:text-emerald-400 file:mr-4 file:py-2 file:px-6 file:rounded-lg file:border-0 file:text-[9px] file:font-bold file:uppercase file:tracking-widest file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 cursor-pointer transition-all">
                <button type="submit" class="w-full md:w-auto bg-emerald-900 dark:bg-emerald-600 text-white px-6 py-2.5 rounded-lg text-[9px] font-bold uppercase tracking-widest shadow-lg shadow-emerald-900/20 hover:bg-black dark:hover:bg-emerald-700 transition-all active:scale-95 flex items-center justify-center gap-2">
                    <i data-lucide="upload-cloud" class="w-3.5 h-3.5"></i> Impor
                </button>
            </form>
        </div>
    </div>

    <!-- 2. FORM MANUAL -->
    <form action="<?= base_url('psu/store') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all duration-300">
            <div class="p-6 border-b dark:border-slate-800 bg-blue-50/30 dark:bg-blue-950/30 flex items-center gap-3">
                <div class="w-9 h-9 bg-blue-600 rounded-lg flex items-center justify-center text-white shadow-lg">
                    <i data-lucide="clipboard-list" class="w-4.5 h-4.5"></i>
                </div>
                <div>
                    <h3 class="text-[11px] font-bold text-blue-900 dark:text-blue-400 uppercase tracking-[0.2em]">Atribut Jaringan Jalan</h3>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Identitas & Koordinat Spasial</p>
                </div>
            </div>
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="md:col-span-2">
                    <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Nama Ruas Jalan</label>
                    <input type="text" name="nama_jalan" required class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase placeholder:opacity-30" placeholder="CONTOH: POROS SINJAI...">
                </div>
                <div>
                    <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">ID CSV / Referensi</label>
                    <input type="text" name="id_csv" required class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-mono font-bold" placeholder="ID-001">
                </div>
                <div>
                    <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Nilai / Skor Kondisi</label>
                    <input type="number" step="0.01" name="jalan" required class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold" placeholder="0.00">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Koordinat Lokasi (Map Picker)</label>
                    <div class="rounded-[2rem] overflow-hidden border border-slate-200 dark:border-slate-800 shadow-inner mb-4">
                        <div id="map" class="w-full h-72 z-10"></div>
                    </div>
                    <input type="text" name="wkt" id="wkt" required readonly class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-mono text-xs" placeholder="POINT(lng lat)">
                    <p class="text-[8px] text-slate-400 italic mt-2 ml-1">*Geser marker pada peta untuk menentukan titik koordinat PSU.</p>
                </div>

                <script>
                    let map, marker;
                    function initMap() {
                        const defaultLat = -5.1245; 
                        const defaultLng = 120.2536;

                        const isDark = document.documentElement.classList.contains('dark');
                        const cartoDB = L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { 
                            attribution: '&copy; CartoDB' 
                        });
                        const googleSat = L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
                            maxZoom: 20,
                            subdomains:['mt0','mt1','mt2','mt3'],
                            attribution: '&copy; Google'
                        });

                        map = L.map('map', {
                            center: [defaultLat, defaultLng],
                            zoom: 11,
                            layers: [cartoDB],
                            zoomControl: false
                        });

                        let rot = 0;
                        const LayerToggle = L.Control.extend({
                            onAdd: function(map) {
                                const btn = L.DomUtil.create('button', 'bg-white dark:bg-slate-900 rounded-lg shadow-xl border border-slate-100 dark:border-slate-800 transition-all duration-300 active:scale-90 mt-2 flex items-center justify-center');
                                btn.type = 'button';
                                btn.style.width = '38px'; btn.style.height = '38px'; btn.style.cursor = 'pointer';
                                const isDark = document.documentElement.classList.contains('dark');
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

                        L.control.zoom({ position: 'bottomright' }).addTo(map);

                        marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

                        marker.on('dragend', function (e) {
                            const latlng = marker.getLatLng();
                            updateCoords(latlng.lat, latlng.lng);
                        });

                        map.on('click', function (e) {
                            marker.setLatLng(e.latlng);
                            updateCoords(e.latlng.lat, e.latlng.lng);
                        });

                        if (navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition(pos => {
                                const lat = pos.coords.latitude;
                                const lng = pos.coords.longitude;
                                map.setView([lat, lng], 16);
                                marker.setLatLng([lat, lng]);
                                updateCoords(lat, lng);
                            });
                        }
                    }

                    function updateCoords(lat, lng) {
                        document.getElementById('wkt').value = `POINT(${lng} ${lat})`;
                    }

                    window.addEventListener('load', initMap);
                </script>
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
                <script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>

                <!-- Dokumentasi Before After -->
                <div>
                    <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Foto Sebelum (Before)</label>
                    <div class="relative group">
                        <input type="file" name="foto_before" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 z-10 cursor-pointer" onchange="previewImage(this, 'before_preview')">
                        <div id="before_preview" class="w-full h-32 bg-slate-50 dark:bg-slate-950 border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-2xl flex flex-col items-center justify-center overflow-hidden transition-all group-hover:border-rose-500 group-hover:bg-rose-50/5">
                            <i data-lucide="image-plus" class="w-6 h-6 text-slate-300 mb-1.5"></i>
                            <span class="text-[7px] font-bold text-slate-400 uppercase tracking-widest">Unggah Foto Kondisi Lama</span>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Foto Sesudah (After)</label>
                    <div class="relative group">
                        <input type="file" name="foto_after" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 z-10 cursor-pointer" onchange="previewImage(this, 'after_preview')">
                        <div id="after_preview" class="w-full h-32 bg-slate-50 dark:bg-slate-950 border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-2xl flex flex-col items-center justify-center overflow-hidden transition-all group-hover:border-emerald-500 group-hover:bg-emerald-50/5">
                            <i data-lucide="image-plus" class="w-6 h-6 text-slate-300 mb-1.5"></i>
                            <span class="text-[7px] font-bold text-slate-400 uppercase tracking-widest">Unggah Foto Kondisi Baru</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="flex flex-col md:flex-row justify-between items-center gap-6 pt-4">
            <div class="flex items-center gap-3 text-slate-400">
                <i data-lucide="info" class="w-4 h-4"></i>
                <p class="text-[9px] font-bold uppercase tracking-widest leading-relaxed max-w-md">Pastikan seluruh data teknis dan dokumentasi visual telah divalidasi sebelum melakukan penyimpanan.</p>
            </div>
            <button type="submit" class="group flex items-center space-x-6 bg-blue-600 hover:bg-blue-700 text-white pl-8 pr-4 py-4 rounded-xl font-bold shadow-xl shadow-blue-600/20 transition-all active:scale-95 w-full md:w-auto">
                <div class="flex flex-col text-right">
                    <span class="text-[8px] uppercase tracking-[0.3em] opacity-60 mb-0.5">Konfirmasi Final</span>
                    <span class="text-base uppercase tracking-tighter">Simpan Ruas</span>
                </div>
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center group-hover:translate-x-1 transition-transform">
                    <i data-lucide="save" class="w-5 h-5"></i>
                </div>
            </button>
        </div>
    </form>
</div>

<script>
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                preview.classList.remove('border-dashed');
                preview.classList.add('border-solid', 'border-blue-500');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>
<?= $this->endSection() ?>
