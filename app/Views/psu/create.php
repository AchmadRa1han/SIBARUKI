<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto space-y-6 pb-24 text-slate-900 dark:text-slate-200">
    
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-3 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 no-print">
        <a href="<?= base_url('dashboard') ?>" class="hover:text-blue-600 transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <a href="<?= base_url('psu') ?>" class="hover:text-blue-600 transition-colors">PSU Jalan</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">Tambah Data</span>
    </nav>

    <!-- Header Action -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white dark:bg-slate-900 p-8 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm transition-all duration-300 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-48 h-48 bg-blue-600/5 rounded-full -mr-24 -mt-24 blur-3xl"></div>
        <div class="relative z-10">
            <h1 class="text-3xl font-bold text-blue-950 dark:text-white uppercase tracking-tighter leading-tight">Tambah Jaringan Jalan</h1>
            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-[0.2em] mt-1">Pencatatan Aset PSU Jaringan Jalan Baru</p>
        </div>
        <a href="<?= base_url('psu') ?>" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-slate-200 transition-all active:scale-95 flex items-center gap-2 relative z-10">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
        </a>
    </div>

    <form action="<?= base_url('psu/store') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form Section -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 shadow-sm border border-slate-100 dark:border-slate-800 transition-all duration-300">
                    <h3 class="text-[10px] font-bold text-blue-950 dark:text-white uppercase tracking-[0.3em] mb-8 flex items-center gap-3">
                        <span class="w-8 h-[2px] bg-blue-600"></span> Informasi Umum PSU
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="md:col-span-2">
                            <label class="block text-[9px] font-bold text-slate-400 uppercase mb-3 tracking-widest ml-1">Nama Jaringan Jalan / PSU</label>
                            <input type="text" name="nama_jalan" value="<?= old('nama_jalan') ?>" placeholder="Contoh: Pembangunan Jalan Beton Ruas A..." class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-white outline-none transition-all font-bold" required>
                        </div>
                        
                        <div>
                            <label class="block text-[9px] font-bold text-slate-400 uppercase mb-3 tracking-widest ml-1">Tahun Pembangunan</label>
                            <input type="number" name="tahun" value="<?= old('tahun', date('Y')) ?>" min="2000" max="2100" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-white outline-none transition-all font-bold" required>
                        </div>

                        <div>
                            <label class="block text-[9px] font-bold text-slate-400 uppercase mb-3 tracking-widest ml-1">Panjang / Luas Capaian</label>
                            <div class="relative">
                                <input type="number" step="0.01" name="panjang_luas" value="<?= old('panjang_luas') ?>" placeholder="0.00" class="w-full p-4 pr-16 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-white outline-none transition-all font-bold" required>
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[10px] font-black text-slate-400 uppercase">Meter</span>
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-[9px] font-bold text-slate-400 uppercase mb-3 tracking-widest ml-1">Keterangan Wilayah / Lokasi</label>
                            <input type="text" name="jalan" value="<?= old('jalan') ?>" placeholder="Contoh: Kelurahan Lappa, Kec. Sinjai Utara" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-white outline-none transition-all font-bold" required>
                        </div>
                    </div>
                </div>

                <!-- Foto Documentation -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 shadow-sm border border-slate-100 dark:border-slate-800 transition-all duration-300">
                    <h3 class="text-[10px] font-bold text-blue-950 dark:text-white uppercase tracking-[0.3em] mb-8 flex items-center gap-3">
                        <span class="w-8 h-[2px] bg-emerald-500"></span> Dokumentasi Visual
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest ml-1">Kondisi 0% (Before)</label>
                            <div id="preview_before" class="relative group aspect-video bg-slate-50 dark:bg-slate-950 border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-3xl flex flex-col items-center justify-center overflow-hidden transition-all hover:border-blue-500/50">
                                <input type="file" name="foto_before" accept="image/*" class="absolute inset-0 opacity-0 z-10 cursor-pointer" onchange="previewImage(this, 'preview_before')">
                                <div class="flex flex-col items-center justify-center text-center p-6">
                                    <div class="w-12 h-12 bg-white dark:bg-slate-900 rounded-2xl shadow-sm flex items-center justify-center text-slate-400 mb-4 group-hover:scale-110 transition-transform">
                                        <i data-lucide="camera" class="w-6 h-6"></i>
                                    </div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pilih Foto Before</p>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest ml-1">Kondisi 100% (After)</label>
                            <div id="preview_after" class="relative group aspect-video bg-slate-50 dark:bg-slate-950 border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-3xl flex flex-col items-center justify-center overflow-hidden transition-all hover:border-emerald-500/50">
                                <input type="file" name="foto_after" accept="image/*" class="absolute inset-0 opacity-0 z-10 cursor-pointer" onchange="previewImage(this, 'preview_after')">
                                <div class="flex flex-col items-center justify-center text-center p-6">
                                    <div class="w-12 h-12 bg-white dark:bg-slate-900 rounded-2xl shadow-sm flex items-center justify-center text-slate-400 mb-4 group-hover:scale-110 transition-transform">
                                        <i data-lucide="camera" class="w-6 h-6"></i>
                                    </div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pilih Foto After</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Map Side -->
            <div class="space-y-6">
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden h-full flex flex-col min-h-[500px]">
                    <div class="p-6 border-b dark:border-slate-800 flex items-center justify-between bg-blue-950 text-white">
                        <div class="flex items-center gap-3">
                            <i data-lucide="map-pin" class="w-4 h-4 text-blue-400"></i>
                            <span class="text-[9px] font-bold uppercase tracking-widest">Titik Koordinat</span>
                        </div>
                        <span id="coords-badge" class="px-2 py-0.5 bg-blue-500/20 text-blue-400 rounded text-[8px] font-mono font-bold">WKT READY</span>
                    </div>
                    <div id="map-input" class="flex-1 z-10"></div>
                    <div class="p-6 bg-slate-50 dark:bg-slate-950">
                        <label class="block text-[8px] font-bold text-slate-400 uppercase mb-2 tracking-widest">Well-Known Text (WKT)</label>
                        <textarea id="wkt-input" name="wkt" rows="3" class="w-full p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-[10px] font-mono text-blue-600 outline-none readonly" readonly required><?= old('wkt') ?></textarea>
                        <p class="text-[8px] text-slate-400 mt-2 font-medium italic">*Klik pada peta untuk menentukan lokasi pembangunan</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="fixed bottom-8 right-8 z-[100] flex items-center gap-3 no-print">
            <button type="submit" class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] shadow-2xl shadow-blue-600/40 active:scale-95 transition-all flex items-center gap-3 group">
                Simpan Data PSU
                <div class="w-6 h-6 bg-white/20 rounded-lg flex items-center justify-center group-hover:translate-x-1 transition-transform">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </div>
            </button>
        </div>
    </form>
</div>

<!-- Leaflet & GIS Script -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    let map, marker;

    function initMap() {
        map = L.map('map-input', { zoomControl: false }).setView([-5.215, 120.208], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        L.control.zoom({ position: 'bottomright' }).addTo(map);

        map.on('click', function(e) {
            const lat = e.latlng.lat.toFixed(7);
            const lng = e.latlng.lng.toFixed(7);
            if (marker) map.removeLayer(marker);
            marker = L.marker(e.latlng).addTo(map);
            document.getElementById('wkt-input').value = `POINT(${lng} ${lat})`;
        });
    }

    function previewImage(input, previewId) {
        const container = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                container.innerHTML = `
                    <input type="file" name="${input.name}" accept="image/*" class="absolute inset-0 opacity-0 z-10 cursor-pointer" onchange="previewImage(this, '${previewId}')">
                    <img src="${e.target.result}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <i data-lucide="refresh-cw" class="w-6 h-6 text-white"></i>
                    </div>
                `;
                lucide.createIcons();
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    window.addEventListener('load', () => {
        lucide.createIcons();
        initMap();
    });
</script>
<?= $this->endSection() ?>
