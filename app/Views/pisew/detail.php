<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<!-- Leaflet Assets -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="max-w-7xl mx-auto space-y-6 pb-12 text-slate-900 dark:text-slate-200">
    
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-3 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 no-print">
        <a href="<?= base_url('dashboard') ?>" class="hover:text-blue-600 transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <a href="<?= base_url('pisew') ?>" class="hover:text-blue-600 transition-colors">PISEW</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">Detail Kegiatan</span>
    </nav>

    <!-- Header Action -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white dark:bg-slate-900 p-8 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm transition-all duration-300 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-48 h-48 bg-indigo-600/5 rounded-full -mr-24 -mt-24 blur-3xl"></div>
        <div class="relative z-10 flex items-center gap-5">
            <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-indigo-600/20">
                <i data-lucide="building-2" class="w-7 h-7"></i>
            </div>
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-blue-950 dark:text-white uppercase tracking-tighter leading-tight">Detail PISEW</h1>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-[0.2em] mt-1">Tahun Anggaran <?= $item['tahun'] ?></p>
            </div>
        </div>
        <div class="flex items-center gap-2 relative z-10">
            <a href="<?= base_url('pisew/edit/'.$item['id']) ?>" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-amber-500/20 transition-all active:scale-95 flex items-center gap-2">
                <i data-lucide="edit-3" class="w-4 h-4"></i> Edit
            </a>
            <button onclick="confirmDelete(<?= $item['id'] ?>)" class="px-5 py-2.5 bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-rose-600 hover:text-white transition-all active:scale-95 flex items-center gap-2">
                <i data-lucide="trash-2" class="w-4 h-4"></i> Hapus
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informasi Utama -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-slate-900 rounded-[2rem] p-8 shadow-sm border border-slate-100 dark:border-slate-800 relative overflow-hidden transition-all duration-300">
                <div class="absolute top-0 right-0 p-8 opacity-5 pointer-events-none">
                    <i data-lucide="map" class="w-40 h-40 text-indigo-900 dark:text-white"></i>
                </div>
                
                <h3 class="text-[10px] font-black text-blue-950 dark:text-white uppercase tracking-[0.3em] mb-8 flex items-center gap-3">
                    <span class="w-8 h-[2px] bg-indigo-600"></span> Identitas & Lokasi Pekerjaan
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-10 relative z-10">
                    <div class="md:col-span-2">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Nama / Jenis Pekerjaan</p>
                        <p class="text-xl font-black text-slate-800 dark:text-slate-100 uppercase tracking-tight"><?= $item['jenis_pekerjaan'] ?></p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Wilayah Kecamatan</p>
                        <p class="text-sm font-black text-indigo-600 uppercase tracking-wider">Kec. <?= $item['kecamatan'] ?></p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Lokasi Desa</p>
                        <p class="text-sm font-black text-slate-800 dark:text-slate-100 uppercase tracking-wider"><?= $item['lokasi_desa'] ?></p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Periode Anggaran</p>
                        <span class="inline-block px-4 py-1.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-[10px] font-black uppercase rounded-lg border border-slate-200 dark:border-slate-700 shadow-sm">
                            <?= $item['tahun'] ?>
                        </span>
                    </div>
                    <div class="md:col-span-2 pt-4">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">Pihak Pelaksana</p>
                        <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-100 dark:border-slate-800">
                            <p class="text-xs font-bold text-slate-600 dark:text-slate-300 leading-relaxed uppercase"><?= $item['pelaksana'] ?: 'Informasi pelaksana belum tercatat' ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-[2rem] p-8 shadow-sm border border-slate-100 dark:border-slate-800 transition-all duration-300">
                <h3 class="text-[10px] font-black text-blue-950 dark:text-white uppercase tracking-[0.3em] mb-8 flex items-center gap-3">
                    <span class="w-8 h-[2px] bg-emerald-500"></span> Analisis Pembiayaan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-emerald-50 dark:bg-emerald-950/20 p-6 rounded-[1.5rem] border border-emerald-100 dark:border-emerald-900/30 shadow-sm">
                        <p class="text-[9px] font-black text-emerald-600/70 uppercase tracking-widest mb-2">Nilai Kontrak / Pagu</p>
                        <p class="text-3xl font-black text-emerald-600 tracking-tighter italic">Rp <?= number_format($item['anggaran'], 0, ',', '.') ?></p>
                    </div>
                    <div class="bg-indigo-50 dark:bg-indigo-950/20 p-6 rounded-[1.5rem] border border-indigo-100 dark:border-indigo-900/30 shadow-sm">
                        <p class="text-[9px] font-black text-indigo-600/70 uppercase tracking-widest mb-2">Sumber Pendanaan</p>
                        <p class="text-xs font-black text-indigo-900 dark:text-indigo-400 uppercase tracking-widest"><?= $item['sumber_dana'] ?: '-' ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Peta & Log -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-slate-900 rounded-[2rem] overflow-hidden shadow-sm border border-slate-100 dark:border-slate-800 aspect-square relative group">
                <div id="map-detail" class="w-full h-full z-10"></div>
                <div class="absolute top-4 left-4 z-[1000] bg-blue-950/80 backdrop-blur-md px-3 py-1.5 rounded-xl border border-white/10 shadow-2xl text-[8px] font-black uppercase tracking-widest text-white flex items-center gap-2">
                    <div class="w-1 h-1 bg-indigo-400 rounded-full animate-pulse"></div>
                    Titik Geospasial
                </div>
                
                <?php if (!$item['koordinat']): ?>
                <div class="absolute inset-0 z-[1001] bg-slate-900/20 backdrop-blur-md flex items-center justify-center text-center p-8">
                    <div class="bg-white dark:bg-slate-900 p-6 rounded-[1.5rem] shadow-2xl border border-slate-100 dark:border-slate-800">
                        <div class="w-12 h-12 bg-slate-50 dark:bg-slate-800 rounded-xl flex items-center justify-center mx-auto mb-3">
                            <i data-lucide="map-off" class="w-6 h-6 text-slate-300"></i>
                        </div>
                        <p class="text-[9px] font-black uppercase tracking-widest text-slate-400">Koordinat Belum Terpetakan</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="bg-blue-950 rounded-[2rem] p-8 text-white shadow-xl relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-700">
                    <i data-lucide="database" class="w-40 h-40"></i>
                </div>
                <h4 class="text-[9px] font-black uppercase tracking-[0.3em] text-blue-400 mb-6 flex items-center gap-2">
                    Metadata Inventaris
                </h4>
                <div class="space-y-4 relative z-10">
                    <div class="flex justify-between items-center text-[9px]">
                        <span class="font-bold text-blue-300/60 uppercase tracking-widest">Entry Date</span>
                        <span class="font-black text-blue-50 tracking-wider"><?= date('d/m/y H:i', strtotime($item['created_at'])) ?></span>
                    </div>
                    <div class="flex justify-between items-center text-[9px]">
                        <span class="font-bold text-blue-300/60 uppercase tracking-widest">Last Update</span>
                        <span class="font-black text-blue-400 tracking-wider"><?= date('d/m/y H:i', strtotime($item['updated_at'])) ?></span>
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
            html: `<div class="w-8 h-8 bg-indigo-600 rounded-full border-4 border-white shadow-xl flex items-center justify-center animate-bounce-slow"><div class="w-2 h-2 bg-white rounded-full"></div></div>`,
            iconSize: [32, 32],
            iconAnchor: [16, 32]
        });

        L.marker(coords, { icon: markerIcon }).addTo(map);
    }

    function confirmDelete(id) {
        customConfirm('Hapus Data?', 'Apakah Anda yakin ingin menghapus data PISEW ini secara permanen?', 'danger').then(conf => {
            if (conf) { const f = document.getElementById('delete-form'); f.action = `<?= base_url('pisew/delete') ?>/${id}`; f.submit(); }
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
