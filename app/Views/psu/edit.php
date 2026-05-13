<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto space-y-6 pb-32 text-slate-900 dark:text-slate-200">
    
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-3 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 no-print">
        <a href="<?= base_url('dashboard') ?>" class="hover:text-blue-600 transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <a href="<?= base_url('psu') ?>" class="hover:text-blue-600 transition-colors">PSU Jalan</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">Perbarui Data</span>
    </nav>

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm relative overflow-hidden transition-all duration-300">
        <div class="absolute top-0 right-0 w-48 h-48 bg-blue-600/5 rounded-full -mr-24 -mt-24 blur-3xl"></div>
        <div class="relative z-10 flex items-center gap-4">
            <a href="<?= base_url('psu') ?>" class="p-3 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl hover:bg-blue-600 hover:text-white transition-all active:scale-95 no-print" title="Kembali">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-blue-950 dark:text-white uppercase tracking-tighter">Edit Jaringan Jalan</h1>
                <p class="text-slate-500 dark:text-slate-400 font-medium text-xs mt-1">ID Aset: #<?= $jalan['id'] ?></p>
            </div>
        </div>
        <div class="flex items-center gap-2 relative z-10 no-print">
            <a href="<?= base_url('psu') ?>" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-slate-200 transition-all active:scale-95">Batal</a>
        </div>
    </div>

    <form action="<?= base_url('psu/update/' . $jalan['id']) ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form Section -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all duration-300">
                    <div class="p-6 border-b dark:border-slate-800 bg-blue-50/30 dark:bg-blue-950/30 flex items-center gap-3">
                        <div class="w-9 h-9 bg-blue-600 rounded-lg flex items-center justify-center text-white shadow-lg">
                            <i data-lucide="edit-3" class="w-4.5 h-4.5"></i>
                        </div>
                        <div>
                            <h3 class="text-[11px] font-bold text-blue-900 dark:text-blue-400 uppercase tracking-[0.2em]">Ubah Informasi</h3>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Detail Teknis Jaringan Jalan</p>
                        </div>
                    </div>
                    
                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Nama Jaringan Jalan / PSU</label>
                            <input type="text" name="nama_jalan" value="<?= old('nama_jalan', $jalan['nama_jalan']) ?>" required class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold">
                        </div>
                        
                        <div>
                            <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Tahun Pembangunan</label>
                            <input type="number" name="tahun" value="<?= old('tahun', $jalan['tahun']) ?>" min="2000" max="2100" required class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold">
                        </div>

                        <div>
                            <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Panjang / Luas Capaian</label>
                            <div class="relative">
                                <input type="number" step="0.01" name="panjang_luas" value="<?= old('panjang_luas', $jalan['panjang_luas']) ?>" required class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[9px] font-black text-slate-400 uppercase">Meter</span>
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Keterangan Wilayah / Lokasi</label>
                            <input type="text" name="jalan" value="<?= old('jalan', $jalan['jalan']) ?>" required class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold">
                        </div>
                    </div>
                </div>

                <!-- Foto Documentation -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all duration-300">
                    <div class="p-6 border-b dark:border-slate-800 bg-emerald-50/30 dark:bg-emerald-950/30 flex items-center gap-3">
                        <div class="w-9 h-9 bg-emerald-600 rounded-lg flex items-center justify-center text-white shadow-lg">
                            <i data-lucide="camera" class="w-4.5 h-4.5"></i>
                        </div>
                        <div>
                            <h3 class="text-[11px] font-bold text-emerald-900 dark:text-emerald-400 uppercase tracking-[0.2em]">Dokumentasi Visual</h3>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Kondisi 0% dan 100% Pekerjaan</p>
                        </div>
                    </div>
                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest ml-1">Kondisi Awal (Before)</label>
                            <div id="preview_before" class="relative group aspect-video bg-slate-50 dark:bg-slate-950 border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-2xl flex flex-col items-center justify-center overflow-hidden transition-all hover:border-blue-500/50">
                                <input type="file" name="foto_before" accept="image/*" class="absolute inset-0 opacity-0 z-10 cursor-pointer" onchange="previewImage(this, 'preview_before')">
                                <?php if($jalan['foto_before']): ?>
                                    <img src="<?= base_url('uploads/psu/' . $jalan['foto_before']) ?>" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <i data-lucide="refresh-cw" class="w-6 h-6 text-white animate-spin-slow"></i>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center p-6">
                                        <i data-lucide="image-plus" class="w-8 h-8 text-slate-300 mx-auto mb-2 group-hover:scale-110 transition-transform"></i>
                                        <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Pilih Foto 0%</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest ml-1">Hasil Akhir (After)</label>
                            <div id="preview_after" class="relative group aspect-video bg-slate-50 dark:bg-slate-950 border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-2xl flex flex-col items-center justify-center overflow-hidden transition-all hover:border-emerald-500/50">
                                <input type="file" name="foto_after" accept="image/*" class="absolute inset-0 opacity-0 z-10 cursor-pointer" onchange="previewImage(this, 'preview_after')">
                                <?php if($jalan['foto_after']): ?>
                                    <img src="<?= base_url('uploads/psu/' . $jalan['foto_after']) ?>" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <i data-lucide="refresh-cw" class="w-6 h-6 text-white animate-spin-slow"></i>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center p-6">
                                        <i data-lucide="image-plus" class="w-8 h-8 text-slate-300 mx-auto mb-2 group-hover:scale-110 transition-transform"></i>
                                        <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Pilih Foto 100%</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Map Side -->
            <div class="space-y-6">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden h-full flex flex-col min-h-[500px]">
                    <div class="p-5 border-b dark:border-slate-800 flex items-center justify-between bg-blue-950 text-white">
                        <div class="flex items-center gap-3">
                            <i data-lucide="map-pin" class="w-4 h-4 text-blue-400"></i>
                            <span class="text-[9px] font-bold uppercase tracking-widest">Lokasi Geospasial</span>
                        </div>
                    </div>
                    <div id="map-input" class="flex-1 z-10"></div>
                    <div class="p-6 bg-slate-50 dark:bg-slate-950">
                        <label class="block text-[8px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Koordinat WKT (POINT)</label>
                        <textarea id="wkt-input" name="wkt" rows="2" readonly required class="w-full p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-[10px] font-mono text-blue-600 outline-none transition-all placeholder:opacity-30" placeholder="Klik pada peta..."><?= old('wkt', $jalan['wkt']) ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="fixed bottom-8 right-8 z-[100] no-print">
            <button type="submit" class="px-8 py-3.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-[10px] font-black uppercase tracking-[0.2em] shadow-2xl shadow-blue-600/40 active:scale-95 transition-all flex items-center gap-3 group">
                Simpan Perubahan
                <i data-lucide="check" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
            </button>
        </div>
    </form>
</div>

<!-- Leaflet Assets -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    let map, marker;

    function initMap() {
        const wkt = document.getElementById('wkt-input').value;
        let center = [-5.1245, 120.2536];
        
        if (wkt) {
            const match = wkt.match(/POINT\s*\(\s*([-\d.]+)\s+([-\d.]+)\s*\)/i);
            if (match) center = [parseFloat(match[2]), parseFloat(match[1])];
        }

        map = L.map('map-input', { zoomControl: false }).setView(center, 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        L.control.zoom({ position: 'bottomright' }).addTo(map);

        if (wkt) marker = L.marker(center).addTo(map);

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
                        <i data-lucide="refresh-cw" class="w-6 h-6 text-white animate-spin-slow"></i>
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
