<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<!-- Leaflet Assets -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="max-w-7xl mx-auto space-y-6 pb-12 text-slate-900 dark:text-slate-200">
    
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-3 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 no-print">
        <a href="<?= base_url('dashboard') ?>" class="hover:text-blue-600 transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <a href="<?= base_url('arsinum') ?>" class="hover:text-blue-600 transition-colors">ARSINUM</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">Detail Aset</span>
    </nav>

    <!-- Header Action -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white dark:bg-slate-900 p-8 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm transition-all duration-300 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-48 h-48 bg-blue-600/5 rounded-full -mr-24 -mt-24 blur-3xl"></div>
        <div class="relative z-10 flex items-center gap-4">
            <a href="<?= base_url('arsinum') ?>" class="p-3 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl hover:bg-blue-600 hover:text-white transition-all active:scale-95" title="Kembali">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-blue-950 dark:text-white uppercase tracking-tighter leading-tight">Detail ARSINUM</h1>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-[0.2em] mt-1">Tahun Anggaran <?= $item['tahun'] ?></p>
            </div>
        </div>
        <div class="flex items-center gap-2 relative z-10">
            <a href="<?= base_url('arsinum/edit/'.$item['id']) ?>" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-[10px] font-bold uppercase tracking-widest shadow-lg shadow-amber-500/20 transition-all active:scale-95 flex items-center gap-2">
                <i data-lucide="edit-3" class="w-4 h-4"></i> Edit
            </a>
            <button onclick="confirmDelete(<?= $item['id'] ?>)" class="px-5 py-2.5 bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-rose-600 hover:text-white transition-all active:scale-95 flex items-center gap-2">
                <i data-lucide="trash-2" class="w-4 h-4"></i> Hapus
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informasi Utama -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-8 shadow-sm border border-slate-100 dark:border-slate-800 relative overflow-hidden transition-all duration-300">
                <div class="absolute top-0 right-0 p-10 opacity-5 pointer-events-none">
                    <i data-lucide="clipboard-list" class="w-40 h-40 text-blue-900 dark:text-white"></i>
                </div>
                
                <h3 class="text-[10px] font-bold text-blue-950 dark:text-white uppercase tracking-[0.3em] mb-8 flex items-center gap-3">
                    <span class="w-8 h-[2px] bg-blue-600"></span> Rincian Proyek & Lokasi
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-10 relative z-10">
                    <div class="md:col-span-2">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-2">Jenis Pekerjaan</p>
                        <p class="text-lg font-bold text-slate-800 dark:text-slate-100 uppercase tracking-tight"><?= $item['jenis_pekerjaan'] ?></p>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-2">Wilayah Kecamatan</p>
                        <p class="text-sm font-bold text-blue-600 uppercase tracking-wider">Kec. <?= $item['kecamatan'] ?></p>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-2">Desa / Kelurahan</p>
                        <p class="text-sm font-bold text-slate-800 dark:text-slate-100 uppercase tracking-wider"><?= $item['desa'] ?></p>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-2">Volume Output</p>
                        <span class="inline-block px-4 py-1.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-[10px] font-bold uppercase rounded-lg border border-blue-100 dark:border-blue-800 shadow-sm">
                            <?= $item['volume'] ?>
                        </span>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-2">Periode Pelaksanaan</p>
                        <p class="text-sm font-bold text-slate-800 dark:text-slate-100 uppercase tracking-widest"><?= $item['tahun'] ?></p>
                    </div>
                    <div class="md:col-span-2 pt-4">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-3">Pelaksana / Kontraktor</p>
                        <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-100 dark:border-slate-800">
                            <p class="text-xs font-bold text-slate-600 dark:text-slate-300 leading-relaxed uppercase"><?= $item['pelaksana'] ?: 'Informasi pelaksana tidak tersedia' ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-2xl p-8 shadow-sm border border-slate-100 dark:border-slate-800 transition-all duration-300">
                <h3 class="text-[10px] font-bold text-blue-950 dark:text-white uppercase tracking-[0.3em] mb-8 flex items-center gap-3">
                    <span class="w-8 h-[2px] bg-emerald-500"></span> Informasi Finansial
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-emerald-50 dark:bg-emerald-950/20 p-6 rounded-xl border border-emerald-100 dark:border-emerald-900/30 shadow-sm">
                        <p class="text-[9px] font-bold text-emerald-600/70 uppercase tracking-widest mb-2">Total Pagu Anggaran</p>
                        <p class="text-3xl font-bold text-emerald-600 tracking-tighter italic">Rp <?= number_format($item['anggaran'], 0, ',', '.') ?></p>
                    </div>
                    <div class="bg-blue-50 dark:bg-blue-950/20 p-6 rounded-xl border border-blue-100 dark:border-blue-900/30 shadow-sm">
                        <p class="text-[9px] font-bold text-blue-600/70 uppercase tracking-widest mb-2">Sumber Pendanaan</p>
                        <p class="text-xs font-bold text-blue-900 dark:text-blue-400 uppercase tracking-widest"><?= $item['sumber_dana'] ?: '-' ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Peta & Log -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-800">
                <h3 class="text-[10px] font-bold text-blue-950 dark:text-white uppercase tracking-[0.3em] mb-6 flex items-center gap-3">
                    <span class="w-8 h-[2px] bg-indigo-500"></span> Dokumentasi Realisasi
                </h3>
                
                <div class="space-y-4">
                    <!-- After Photo -->
                    <div class="space-y-2">
                        <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest ml-1">Hasil Pekerjaan (After)</p>
                        <div class="aspect-video bg-slate-50 dark:bg-slate-950 rounded-xl overflow-hidden border border-slate-100 dark:border-slate-800 flex items-center justify-center group relative">
                            <?php if (!empty($item['foto_after'])): ?>
                                <img src="<?= base_url('uploads/arsinum/' . $item['foto_after']) ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                <div class="absolute inset-0 bg-emerald-950/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <a href="<?= base_url('uploads/arsinum/' . $item['foto_after']) ?>" target="_blank" class="p-3 bg-white/20 backdrop-blur-md rounded-full text-white hover:bg-white/40 transition-all">
                                        <i data-lucide="maximize" class="w-5 h-5"></i>
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="text-center p-4">
                                    <i data-lucide="image-off" class="w-8 h-8 text-slate-300 mx-auto mb-2 opacity-50"></i>
                                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Belum Ada Foto</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-2xl overflow-hidden shadow-sm border border-slate-100 dark:border-slate-800 aspect-square relative group">
                <div id="map-detail" class="w-full h-full z-10"></div>
                <div class="absolute top-4 left-4 z-[1000] bg-blue-950/80 backdrop-blur-md px-3 py-1.5 rounded-xl border border-white/10 shadow-2xl text-[8px] font-bold uppercase tracking-widest text-white flex items-center gap-2">
                    <div class="w-1 h-1 bg-blue-400 rounded-full animate-pulse"></div>
                    Titik Geospasial
                </div>
                
                <?php if (!$item['koordinat']): ?>
                <div class="absolute inset-0 z-[1001] bg-slate-900/20 backdrop-blur-md flex items-center justify-center text-center p-8">
                    <div class="bg-white dark:bg-slate-900 p-6 rounded-xl shadow-2xl border border-slate-100 dark:border-slate-800">
                        <div class="w-12 h-12 bg-slate-50 dark:bg-slate-800 rounded-xl flex items-center justify-center mx-auto mb-3">
                            <i data-lucide="map-off" class="w-6 h-6 text-slate-300"></i>
                        </div>
                        <p class="text-[9px] font-bold uppercase tracking-widest text-slate-400">Koordinat Belum Terpetakan</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="bg-blue-950 rounded-2xl p-8 text-white shadow-xl relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-700">
                    <i data-lucide="info" class="w-40 h-40"></i>
                </div>
                <h4 class="text-[9px] font-bold uppercase tracking-[0.3em] text-blue-400 mb-6 flex items-center gap-2">
                    Metadata Sistem
                </h4>
                <div class="space-y-4 relative z-10">
                    <div class="flex justify-between items-center text-[9px]">
                        <span class="font-bold text-blue-300/60 uppercase tracking-widest">Entry Date</span>
                        <span class="font-bold text-blue-50 tracking-wider"><?= date('d/m/y H:i', strtotime($item['created_at'])) ?></span>
                    </div>
                    <div class="flex justify-between items-center text-[9px]">
                        <span class="font-bold text-blue-300/60 uppercase tracking-widest">Last Update</span>
                        <span class="font-bold text-blue-50 tracking-wider"><?= date('d/m/y H:i', strtotime($item['updated_at'])) ?></span>
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
        const cartoDB = L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { 
            attribution: '&copy; CartoDB' 
        });
        const googleSat = L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains:['mt0','mt1','mt2','mt3'],
            attribution: '&copy; Google'
        });

        const map = L.map('map-detail', { zoomControl: false, dragging: true, scrollWheelZoom: false, layers: [cartoDB] }).setView(coords, 17);
        
        let rot = 0;
        const LayerToggle = L.Control.extend({
            onAdd: function(map) {
                const btn = L.DomUtil.create('button', 'bg-white dark:bg-slate-900 rounded-lg shadow-xl border border-slate-100 dark:border-slate-800 transition-all duration-300 active:scale-90 flex items-center justify-center');
                btn.type = 'button';
                btn.style.width = '34px'; btn.style.height = '34px'; btn.style.cursor = 'pointer';
                const isDark = document.documentElement.classList.contains('dark');
                const svgColor = isDark ? '#60a5fa' : '#2563eb';
                btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="${svgColor}" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:block; transition: transform 0.8s cubic-bezier(0.65, 0, 0.35, 1);"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>`;
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
        
        const markerIcon = L.divIcon({
            className: 'custom-marker',
            html: `<div class="w-8 h-8 bg-blue-600 rounded-full border-4 border-white shadow-xl flex items-center justify-center animate-bounce-slow"><div class="w-2 h-2 bg-white rounded-full"></div></div>`,
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
