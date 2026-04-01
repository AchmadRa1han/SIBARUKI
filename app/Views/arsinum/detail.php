<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<!-- Leaflet Assets -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="max-w-7xl mx-auto space-y-8 pb-12">
    
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-3 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 no-print">
        <a href="<?= base_url('dashboard') ?>" class="hover:text-blue-600 transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <a href="<?= base_url('arsinum') ?>" class="hover:text-blue-600 transition-colors">ARSINUM</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">Detail Aset</span>
    </nav>

    <!-- Header Action -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white dark:bg-slate-900 p-10 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm transition-all duration-300 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-blue-600/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
        <div class="relative z-10 flex items-center gap-6">
            <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-blue-600/20">
                <i data-lucide="droplets" class="w-8 h-8"></i>
            </div>
            <div>
                <h1 class="text-3xl md:text-4xl font-black text-blue-950 dark:text-white uppercase tracking-tighter">Detail ARSINUM</h1>
                <p class="text-sm text-slate-500 font-bold uppercase tracking-widest mt-1">Tahun Anggaran <?= $item['tahun'] ?></p>
            </div>
        </div>
        <div class="flex items-center gap-3 relative z-10">
            <a href="<?= base_url('arsinum/edit/'.$item['id']) ?>" class="px-8 py-4 bg-amber-500 hover:bg-amber-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-amber-500/20 transition-all active:scale-95 flex items-center gap-3">
                <i data-lucide="edit-3" class="w-5 h-5"></i> Edit Data
            </a>
            <button onclick="confirmDelete(<?= $item['id'] ?>)" class="px-8 py-4 bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-rose-600 hover:text-white transition-all active:scale-95 flex items-center gap-3">
                <i data-lucide="trash-2" class="w-5 h-5"></i> Hapus
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Informasi Utama -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-10 shadow-sm border border-slate-100 dark:border-slate-800 relative overflow-hidden transition-all duration-300">
                <div class="absolute top-0 right-0 p-12 opacity-5 pointer-events-none">
                    <i data-lucide="clipboard-list" class="w-48 h-48 text-blue-900 dark:text-white"></i>
                </div>
                
                <h3 class="text-xs font-black text-blue-950 dark:text-white uppercase tracking-[0.3em] mb-10 flex items-center gap-4">
                    <span class="w-10 h-[2px] bg-blue-600"></span> Rincian Proyek & Lokasi
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-10 gap-x-12 relative z-10">
                    <div class="md:col-span-2">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Jenis Pekerjaan</p>
                        <p class="text-xl font-black text-slate-800 dark:text-slate-100 uppercase tracking-tight"><?= $item['jenis_pekerjaan'] ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Wilayah Kecamatan</p>
                        <p class="text-sm font-black text-blue-600 uppercase tracking-wider">Kec. <?= $item['kecamatan'] ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Desa / Kelurahan</p>
                        <p class="text-sm font-black text-slate-800 dark:text-slate-100 uppercase tracking-wider"><?= $item['desa'] ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Volume Output</p>
                        <span class="inline-block px-5 py-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs font-black uppercase rounded-2xl border border-blue-100 dark:border-blue-800 shadow-sm">
                            <?= $item['volume'] ?>
                        </span>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Periode Pelaksanaan</p>
                        <p class="text-sm font-black text-slate-800 dark:text-slate-100 uppercase tracking-widest"><?= $item['tahun'] ?></p>
                    </div>
                    <div class="md:col-span-2 pt-6">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Pelaksana / Kontraktor</p>
                        <div class="p-5 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-800">
                            <p class="text-sm font-bold text-slate-600 dark:text-slate-300 leading-relaxed uppercase"><?= $item['pelaksana'] ?: 'Informasi pelaksana tidak tersedia' ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-10 shadow-sm border border-slate-100 dark:border-slate-800 transition-all duration-300">
                <h3 class="text-xs font-black text-blue-950 dark:text-white uppercase tracking-[0.3em] mb-8 flex items-center gap-4">
                    <span class="w-10 h-[2px] bg-emerald-500"></span> Informasi Finansial
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-emerald-50 dark:bg-emerald-950/20 p-8 rounded-[2rem] border border-emerald-100 dark:border-emerald-900/30 shadow-sm">
                        <p class="text-[10px] font-black text-emerald-600/70 uppercase tracking-widest mb-3">Total Pagu Anggaran</p>
                        <p class="text-3xl font-black text-emerald-600 tracking-tighter italic">Rp <?= number_format($item['anggaran'], 0, ',', '.') ?></p>
                    </div>
                    <div class="bg-blue-50 dark:bg-blue-950/20 p-8 rounded-[2rem] border border-blue-100 dark:border-blue-900/30 shadow-sm">
                        <p class="text-[10px] font-black text-blue-600/70 uppercase tracking-widest mb-3">Sumber Pendanaan</p>
                        <p class="text-sm font-black text-blue-900 dark:text-blue-400 uppercase tracking-[0.15em]"><?= $item['sumber_dana'] ?: '-' ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Peta & Log -->
        <div class="space-y-8">
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] overflow-hidden shadow-sm border border-slate-100 dark:border-slate-800 aspect-square relative group">
                <div id="map-detail" class="w-full h-full z-10"></div>
                <div class="absolute top-6 left-6 z-[1000] bg-blue-950/80 backdrop-blur-md px-4 py-2 rounded-2xl border border-white/10 shadow-2xl text-[9px] font-black uppercase tracking-widest text-white flex items-center gap-2">
                    <div class="w-1.5 h-1.5 bg-blue-400 rounded-full animate-pulse"></div>
                    Titik Geospasial
                </div>
                
                <?php if (!$item['koordinat']): ?>
                <div class="absolute inset-0 z-[1001] bg-slate-900/20 backdrop-blur-md flex items-center justify-center text-center p-10">
                    <div class="bg-white dark:bg-slate-900 p-8 rounded-[2rem] shadow-2xl border border-slate-100 dark:border-slate-800">
                        <div class="w-16 h-16 bg-slate-50 dark:bg-slate-800 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="map-off" class="w-8 h-8 text-slate-300"></i>
                        </div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Koordinat Belum Terpetakan</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="bg-blue-950 rounded-[2.5rem] p-10 text-white shadow-2xl relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-700">
                    <i data-lucide="info" class="w-32 h-32"></i>
                </div>
                <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-blue-400 mb-6 flex items-center gap-2">
                    Metadata Sistem
                </h4>
                <div class="space-y-6 relative z-10">
                    <div class="flex justify-between items-center text-[10px]">
                        <span class="font-bold text-blue-300/60 uppercase tracking-widest">Entry Date</span>
                        <span class="font-black text-blue-50 tracking-wider"><?= date('d/m/Y H:i', strtotime($item['created_at'])) ?></span>
                    </div>
                    <div class="flex justify-between items-center text-[10px]">
                        <span class="font-bold text-blue-300/60 uppercase tracking-widest">Last Update</span>
                        <span class="font-black text-blue-50 tracking-wider"><?= date('d/m/Y H:i', strtotime($item['updated_at'])) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="delete-form" action="" method="post" class="hidden"><?= csrf_field() ?></form>

<script>
    function initDetailMap() {
        const coordsStr = "<?= $item['koordinat'] ?>";
        if (!coordsStr) return;

        const coords = coordsStr.split(',').map(c => parseFloat(c.trim()));
        if (coords.length !== 2 || isNaN(coords[0]) || isNaN(coords[1])) return;

        const isDark = document.documentElement.classList.contains('dark');
        const tileUrl = isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png';

        const map = L.map('map-detail', { zoomControl: false, dragging: true, scrollWheelZoom: false }).setView(coords, 17);
        L.tileLayer(tileUrl, { attribution: '&copy; Sibaruki' }).addTo(map);
        
        const markerIcon = L.divIcon({
            className: 'custom-marker',
            html: `<div class="w-8 h-8 bg-blue-600 rounded-full border-4 border-white shadow-2xl flex items-center justify-center animate-bounce-slow"><div class="w-2 h-2 bg-white rounded-full"></div></div>`,
            iconSize: [32, 32],
            iconAnchor: [16, 32]
        });

        L.marker(coords, { icon: markerIcon }).addTo(map);
    }

    function confirmDelete(id) {
        customConfirm('Hapus Data?', 'Apakah Anda yakin ingin menghapus data ARSINUM ini secara permanen?', 'danger').then(conf => {
            if (conf) { const f = document.getElementById('delete-form'); f.action = `<?= base_url('arsinum/delete') ?>/${id}`; f.submit(); }
        });
    }

    window.addEventListener('load', () => {
        lucide.createIcons();
        initDetailMap();
    });
</script>

<style>
    @keyframes bounce-slow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    .animate-bounce-slow { animation: bounce-slow 2s infinite ease-in-out; }
</style>
<?= $this->endSection() ?>
