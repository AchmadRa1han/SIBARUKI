<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-5xl mx-auto pb-20 space-y-10">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <a href="<?= base_url('aset-tanah') ?>" class="p-3 bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 hover:bg-slate-50 transition-all">
                <i data-lucide="arrow-left" class="w-5 h-5 text-slate-500"></i>
            </a>
            <div>
                <h1 class="text-3xl font-black text-blue-950 dark:text-white uppercase tracking-tight">Detail Aset Tanah</h1>
                <p class="text-slate-500 dark:text-slate-400 font-medium text-sm">Informasi lengkap aset tanah pemerintah daerah.</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <?php if (has_permission('edit_psu')) : ?>
            <a href="<?= base_url('aset-tanah/edit/' . $aset['id']) ?>" class="px-8 py-3 bg-blue-950 text-white rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl hover:bg-black transition-all flex items-center gap-2">
                <i data-lucide="edit-3" class="w-4 h-4"></i> Edit Data
            </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Sidebar Info -->
        <div class="space-y-8">
            <!-- Main Info Card -->
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-8 shadow-sm border border-slate-100 dark:border-slate-800 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-6 opacity-10">
                    <i data-lucide="landmark" class="w-24 h-24 text-blue-900"></i>
                </div>
                <h3 class="text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-[0.3em] mb-8">Informasi Utama</h3>
                <div class="space-y-6 relative z-10">
                    <div>
                        <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest block mb-1">Nama Pemilik / Instansi</label>
                        <p class="text-lg font-black text-blue-950 dark:text-white leading-tight"><?= $aset['nama_pemilik'] ?></p>
                    </div>
                    <div>
                        <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest block mb-1">Nomor Sertifikat</label>
                        <p class="text-md font-bold text-slate-700 dark:text-slate-200"><?= $aset['no_sertifikat'] ?></p>
                    </div>
                    <div>
                        <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest block mb-1">Nomor Hak</label>
                        <p class="text-sm font-bold text-slate-600 dark:text-slate-400"><?= $aset['nomor_hak'] ?: '-' ?></p>
                    </div>
                    <div class="pt-4 grid grid-cols-2 gap-4">
                        <div class="p-4 bg-blue-50 dark:bg-blue-950/30 rounded-2xl border border-blue-100 dark:border-blue-900/50">
                            <label class="text-[7px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest block mb-1">Luas Tanah</label>
                            <p class="text-sm font-black text-blue-950 dark:text-blue-200"><?= number_format($aset['luas_m2'], 2, ',', '.') ?> m²</p>
                        </div>
                        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/30 rounded-2xl border border-emerald-100 dark:border-emerald-900/50">
                            <label class="text-[7px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest block mb-1">Nilai Aset</label>
                            <p class="text-sm font-black text-emerald-950 dark:text-emerald-200">Rp <?= number_format($aset['nilai_aset'], 0, ',', '.') ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Card -->
            <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white shadow-2xl relative overflow-hidden group">
                <div class="absolute -bottom-4 -right-4 opacity-10 group-hover:scale-110 transition-transform duration-700">
                    <i data-lucide="shield-check" class="w-32 h-32 text-white"></i>
                </div>
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-6">Status & Legalitas</h3>
                <div class="space-y-6 relative z-10">
                    <div>
                        <label class="text-[8px] font-black text-slate-500 uppercase tracking-widest block mb-1">Status Tanah</label>
                        <span class="px-3 py-1 bg-white/10 rounded-full text-[10px] font-black uppercase tracking-widest"><?= $aset['status_tanah'] ?: 'AKTIF' ?></span>
                    </div>
                    <div>
                        <label class="text-[8px] font-black text-slate-500 uppercase tracking-widest block mb-1">Tanggal Terbit</label>
                        <p class="text-sm font-bold"><?= $aset['tgl_terbit'] ? date('d F Y', strtotime($aset['tgl_terbit'])) : '-' ?></p>
                    </div>
                    <div>
                        <label class="text-[8px] font-black text-slate-500 uppercase tracking-widest block mb-1">Peruntukan</label>
                        <p class="text-xs font-medium leading-relaxed text-slate-300"><?= $aset['peruntukan'] ?: '-' ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Location Details -->
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-8 shadow-sm border border-slate-100 dark:border-slate-800">
                <h3 class="text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-[0.3em] mb-8">Detail Lokasi</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <div>
                            <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest block mb-1">Kecamatan</label>
                            <p class="text-sm font-bold text-slate-700 dark:text-slate-200"><?= $aset['kecamatan'] ?></p>
                        </div>
                        <div>
                            <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest block mb-1">Desa / Kelurahan</label>
                            <p class="text-sm font-bold text-slate-700 dark:text-slate-200"><?= $aset['desa_kelurahan'] ?></p>
                        </div>
                        <div>
                            <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest block mb-1">Alamat Lengkap</label>
                            <p class="text-xs font-medium text-slate-600 dark:text-slate-400 leading-relaxed"><?= $aset['lokasi'] ?></p>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div class="p-6 bg-slate-50 dark:bg-slate-950 rounded-3xl border border-slate-100 dark:border-slate-800">
                            <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest block mb-2">Koordinat Geospasial</label>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-white dark:bg-slate-900 rounded-xl flex items-center justify-center shadow-sm">
                                    <i data-lucide="map-pin" class="w-5 h-5 text-rose-500"></i>
                                </div>
                                <code class="text-[10px] font-mono font-bold text-blue-600 dark:text-blue-400"><?= $aset['koordinat'] ?: 'Tidak ada data koordinat' ?></code>
                            </div>
                        </div>
                        <div>
                            <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest block mb-1">Keterangan Tambahan</label>
                            <p class="text-xs font-medium text-slate-600 dark:text-slate-400 leading-relaxed italic"><?= $aset['keterangan'] ?: 'Tidak ada keterangan.' ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Visualization / Map Placeholder -->
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden h-96 relative group">
                <div id="map" class="w-full h-full z-0 bg-slate-50 dark:bg-slate-950"></div>
                <div class="absolute top-6 right-6 z-10 pointer-events-none">
                    <span class="px-4 py-2 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md rounded-xl text-[10px] font-black uppercase tracking-widest shadow-xl border border-white/20">GIS Visualization</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet Library -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
        
        const coords = "<?= $aset['koordinat'] ?>";
        if (coords) {
            const [lat, lng] = coords.split(',').map(c => parseFloat(c.trim()));
            
            if (!isNaN(lat) && !lng !== undefined) {
                const map = L.map('map', { zoomControl: false }).setView([lat, lng], 15);
                
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                L.marker([lat, lng]).addTo(map);
                
                L.control.zoom({ position: 'bottomright' }).addTo(map);
            }
        }
    });
</script>
<?= $this->endSection() ?>
