<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto space-y-6 pb-24 text-slate-900 dark:text-slate-200">
    
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-3 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 no-print">
        <a href="<?= base_url('dashboard') ?>" class="hover:text-blue-600 transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <a href="<?= base_url('psu') ?>" class="hover:text-blue-600 transition-colors">PSU Jalan</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">Detail Aset</span>
    </nav>

    <!-- Header Action -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white dark:bg-slate-900 p-8 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm transition-all duration-300 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-48 h-48 bg-blue-600/5 rounded-full -mr-24 -mt-24 blur-3xl"></div>
        <div class="relative z-10 flex items-center gap-6">
            <a href="<?= base_url('psu') ?>" class="p-3 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl hover:bg-blue-600 hover:text-white transition-all active:scale-95 no-print" title="Kembali">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-blue-950 dark:text-white uppercase tracking-tighter leading-tight"><?= $jalan['nama_jalan'] ?></h1>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-[0.2em] mt-1">ID Aset: #<?= $jalan['id'] ?> | Terdata pada <?= date('d/m/Y', strtotime($jalan['created_at'])) ?></p>
            </div>
        </div>
        <div class="flex items-center gap-2 relative z-10 no-print">
            <?php if (has_permission('edit_psu')) : ?>
            <a href="<?= base_url('psu/edit/' . $jalan['id']) ?>" class="px-5 py-2.5 bg-blue-600 text-white rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-blue-700 transition-all active:scale-95 flex items-center gap-2 shadow-lg shadow-blue-600/20">
                <i data-lucide="edit-3" class="w-4 h-4"></i> Perbarui Data
            </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Summary Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-blue-600 p-6 rounded-3xl text-white shadow-xl shadow-blue-900/20 relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-700">
                        <i data-lucide="calendar" class="w-24 h-24"></i>
                    </div>
                    <p class="text-[8px] font-bold uppercase tracking-[0.3em] text-blue-100 mb-2">Tahun Pembangunan</p>
                    <p class="text-3xl font-black italic"><?= $jalan['tahun'] ?: '-' ?></p>
                </div>
                <div class="bg-emerald-600 p-6 rounded-3xl text-white shadow-xl shadow-emerald-900/20 relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-700">
                        <i data-lucide="maximize" class="w-24 h-24"></i>
                    </div>
                    <p class="text-[8px] font-bold uppercase tracking-[0.3em] text-emerald-100 mb-2">Panjang / Luas</p>
                    <p class="text-3xl font-black italic"><?= number_format($jalan['panjang_luas'], 2, ',', '.') ?><span class="text-xs ml-1 opacity-60">m</span></p>
                </div>
                <div class="bg-slate-900 p-6 rounded-3xl text-white shadow-xl relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-700">
                        <i data-lucide="map-pin" class="w-24 h-24"></i>
                    </div>
                    <p class="text-[8px] font-bold uppercase tracking-[0.3em] text-slate-400 mb-2">Lokasi Wilayah</p>
                    <p class="text-xs font-bold uppercase leading-tight line-clamp-2"><?= $jalan['jalan'] ?></p>
                </div>
            </div>

            <!-- Dokumentasi Visual -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 shadow-sm border border-slate-100 dark:border-slate-800 transition-all duration-300">
                <h3 class="text-[10px] font-bold text-blue-950 dark:text-white uppercase tracking-[0.3em] mb-8 flex items-center gap-3">
                    <span class="w-8 h-[2px] bg-emerald-500"></span> Dokumentasi Pekerjaan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Before -->
                    <div class="space-y-4">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                            <i data-lucide="history" class="w-3.5 h-3.5"></i> Kondisi 0% (Before)
                        </p>
                        <div class="aspect-video rounded-2xl overflow-hidden bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 relative group">
                            <?php if($jalan['foto_before']): ?>
                                <img src="<?= base_url('uploads/psu/' . $jalan['foto_before']) ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                <div class="absolute inset-0 bg-blue-950/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <a href="<?= base_url('uploads/psu/' . $jalan['foto_before']) ?>" target="_blank" class="p-3 bg-white/20 backdrop-blur-md rounded-full text-white"><i data-lucide="maximize" class="w-5 h-5"></i></a>
                                </div>
                            <?php else: ?>
                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-300 opacity-50">
                                    <i data-lucide="image-off" class="w-12 h-12 mb-2"></i>
                                    <span class="text-[8px] font-bold uppercase">Foto tidak tersedia</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- After -->
                    <div class="space-y-4">
                        <p class="text-[9px] font-black text-emerald-600 uppercase tracking-widest flex items-center gap-2">
                            <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> Kondisi 100% (After)
                        </p>
                        <div class="aspect-video rounded-2xl overflow-hidden bg-slate-50 dark:bg-slate-950 border border-emerald-100 dark:border-emerald-900/30 relative group shadow-lg shadow-emerald-500/5">
                            <?php if($jalan['foto_after']): ?>
                                <img src="<?= base_url('uploads/psu/' . $jalan['foto_after']) ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                <div class="absolute inset-0 bg-emerald-950/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <a href="<?= base_url('uploads/psu/' . $jalan['foto_after']) ?>" target="_blank" class="p-3 bg-white/20 backdrop-blur-md rounded-full text-white"><i data-lucide="maximize" class="w-5 h-5"></i></a>
                                </div>
                            <?php else: ?>
                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-300 opacity-50">
                                    <i data-lucide="image-off" class="w-12 h-12 mb-2"></i>
                                    <span class="text-[8px] font-bold uppercase">Foto tidak tersedia</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Map -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden h-full flex flex-col min-h-[500px]">
                <div class="p-6 border-b dark:border-slate-800 flex items-center justify-between bg-blue-950 text-white">
                    <div class="flex items-center gap-3">
                        <i data-lucide="map" class="w-4 h-4 text-blue-400"></i>
                        <span class="text-[9px] font-bold uppercase tracking-widest">Peta Lokasi</span>
                    </div>
                </div>
                <div id="map-detail" class="flex-1 z-10"></div>
                <div class="p-6 bg-slate-50 dark:bg-slate-950">
                    <p class="text-[8px] font-bold text-slate-400 uppercase mb-2 tracking-widest">Koordinat Spasial</p>
                    <p id="wkt-text" class="text-[10px] font-mono font-bold text-blue-600 break-all bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-100 dark:border-slate-800 italic"><?= $jalan['wkt'] ?: 'KOORDINAT TIDAK TERSEDIA' ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet & GIS Script -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    function initMap() {
        const wkt = "<?= $jalan['wkt'] ?>";
        if (!wkt) return;

        const match = wkt.match(/POINT\s*\(\s*([-\d.]+)\s+([-\d.]+)\s*\)/i);
        if (!match) return;
        const lng = parseFloat(match[1]);
        const lat = parseFloat(match[2]);

        const map = L.map('map-detail', { zoomControl: false }).setView([lat, lng], 16);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        L.control.zoom({ position: 'bottomright' }).addTo(map);

        const icon = L.divIcon({
            className: 'custom-div-icon',
            html: `<div class="w-6 h-6 bg-blue-600 rounded-full border-4 border-white shadow-xl"></div>`,
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        });

        L.marker([lat, lng], { icon: icon }).addTo(map);
    }

    window.addEventListener('load', () => {
        lucide.createIcons();
        initMap();
    });
</script>
<?= $this->endSection() ?>
