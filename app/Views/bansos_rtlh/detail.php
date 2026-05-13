<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<!-- Leaflet Assets -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="max-w-7xl mx-auto space-y-6 pb-12 text-slate-900 dark:text-slate-200">
    
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-3 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 no-print">
        <a href="<?= base_url('dashboard') ?>" class="hover:text-emerald-600 transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <a href="<?= base_url('bansos-rtlh') ?>" class="hover:text-emerald-600 transition-colors">Bansos RTLH</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-emerald-600">Detail Realisasi</span>
    </nav>

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white dark:bg-slate-900 p-8 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm relative overflow-hidden transition-all duration-300">
        <div class="absolute top-0 right-0 w-48 h-48 bg-emerald-600/5 rounded-full -mr-24 -mt-24 blur-3xl"></div>
        <div class="relative z-10 flex items-center gap-4">
            <a href="<?= base_url('bansos-rtlh') ?>" class="p-3 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl hover:bg-emerald-600 hover:text-white transition-all active:scale-95" title="Kembali">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-blue-950 dark:text-white uppercase tracking-tighter leading-tight"><?= $bansos['nama_penerima'] ?></h1>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-[0.2em] mt-1">Realisasi Tahun <?= $bansos['tahun_anggaran'] ?> | <?= $bansos['sumber_dana'] ?></p>
            </div>
        </div>
        <div class="flex items-center gap-2 relative z-10 no-print">
            <a href="<?= base_url('bansos-rtlh/edit/' . $bansos['id']) ?>" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-indigo-700 transition-all active:scale-95 flex items-center gap-2 shadow-lg shadow-indigo-600/20">
                <i data-lucide="edit-3" class="w-4 h-4"></i> Edit
            </a>
            <a href="<?= base_url('bansos-rtlh/print/' . $bansos['id']) ?>" target="_blank" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-blue-600 hover:text-white transition-all active:scale-95 flex items-center gap-2">
                <i data-lucide="printer" class="w-4 h-4"></i> Cetak Laporan
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-8 shadow-sm border border-slate-100 dark:border-slate-800 relative overflow-hidden transition-all duration-300">
                <h3 class="text-[10px] font-bold text-blue-950 dark:text-white uppercase tracking-[0.3em] mb-8 flex items-center gap-3">
                    <span class="w-8 h-[2px] bg-emerald-500"></span> Informasi Realisasi Bantuan
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-10 relative z-10">
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-2">NIK Penerima</p>
                        <p class="text-sm font-bold text-slate-800 dark:text-slate-100 uppercase tracking-wider"><?= $bansos['nik'] ?></p>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-2">Wilayah Desa</p>
                        <p class="text-sm font-bold text-slate-800 dark:text-slate-100 uppercase tracking-wider"><?= $bansos['desa'] ?></p>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-2">Sumber Bantuan</p>
                        <p class="text-sm font-bold text-emerald-600 uppercase tracking-wider"><?= $bansos['sumber_dana'] ?></p>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-2">Tahun Anggaran</p>
                        <p class="text-sm font-bold text-slate-800 dark:text-slate-100 uppercase tracking-widest"><?= $bansos['tahun_anggaran'] ?></p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-3">Keterangan Tambahan</p>
                        <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-100 dark:border-slate-800">
                            <p class="text-xs font-bold text-slate-600 dark:text-slate-300 leading-relaxed uppercase"><?= $bansos['keterangan'] ?: 'Tidak ada keterangan tambahan.' ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dokumentasi Section -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-8 shadow-sm border border-slate-100 dark:border-slate-800 transition-all duration-300">
                <h3 class="text-[10px] font-bold text-blue-950 dark:text-white uppercase tracking-[0.3em] mb-8 flex items-center gap-3">
                    <span class="w-8 h-[2px] bg-indigo-500"></span> Dokumentasi Visual (Before & After)
                </h3>
                
                <div class="space-y-8">
                    <!-- Before Documentation -->
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                            <i data-lucide="history" class="w-3.5 h-3.5"></i> Kondisi Awal (Before)
                        </p>
                        
                        <?php if ($rumah): ?>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <?php foreach(['foto_depan', 'foto_samping', 'foto_belakang', 'foto_dalam'] as $f): ?>
                                    <div class="aspect-square rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 border border-slate-100 dark:border-slate-800 group relative">
                                        <?php if (!empty($rumah[$f])): ?>
                                            <img src="<?= base_url('uploads/rtlh/'.$rumah[$f]) ?>" class="w-full h-full object-cover transition-transform group-hover:scale-110">
                                            <div class="absolute inset-0 bg-blue-950/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                <a href="<?= base_url('uploads/rtlh/'.$rumah[$f]) ?>" target="_blank" class="p-2 bg-white/20 backdrop-blur-md rounded-full text-white"><i data-lucide="maximize" class="w-4 h-4"></i></a>
                                            </div>
                                        <?php else: ?>
                                            <div class="w-full h-full flex items-center justify-center"><i data-lucide="image-off" class="w-6 h-6 text-slate-300"></i></div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <p class="mt-3 text-[9px] text-slate-400 italic font-bold uppercase tracking-widest text-center">*Menampilkan 4 foto dari database survei RTLH</p>
                        <?php elseif (!empty($bansos['foto_before'])): ?>
                            <div class="aspect-video rounded-2xl overflow-hidden bg-slate-100 dark:bg-slate-800 border border-slate-100 dark:border-slate-800 group relative max-w-md mx-auto">
                                <img src="<?= base_url('uploads/rtlh/'.$bansos['foto_before']) ?>" class="w-full h-full object-cover transition-transform group-hover:scale-110">
                                <div class="absolute inset-0 bg-blue-950/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <a href="<?= base_url('uploads/rtlh/'.$bansos['foto_before']) ?>" target="_blank" class="p-3 bg-white/20 backdrop-blur-md rounded-full text-white"><i data-lucide="maximize" class="w-5 h-5"></i></a>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="p-12 text-center bg-slate-50 dark:bg-slate-800/30 rounded-2xl border border-dashed border-slate-200 dark:border-slate-800">
                                <i data-lucide="image-off" class="w-10 h-10 text-slate-300 mx-auto mb-3"></i>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Foto before tidak tersedia</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Divider -->
                    <div class="flex items-center gap-4 py-2">
                        <div class="flex-1 h-[1px] bg-slate-100 dark:bg-slate-800"></div>
                        <div class="w-8 h-8 rounded-full bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600">
                            <i data-lucide="arrow-down" class="w-4 h-4"></i>
                        </div>
                        <div class="flex-1 h-[1px] bg-slate-100 dark:bg-slate-800"></div>
                    </div>

                    <!-- After Documentation -->
                    <div>
                        <p class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                            <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> Hasil Pekerjaan (After)
                        </p>
                        
                        <?php if (!empty($bansos['foto_after'])): ?>
                            <div class="aspect-video rounded-2xl overflow-hidden bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-100 dark:border-emerald-900/30 group relative max-w-2xl mx-auto shadow-xl shadow-emerald-900/10">
                                <img src="<?= base_url('uploads/rtlh/'.$bansos['foto_after']) ?>" class="w-full h-full object-cover transition-transform group-hover:scale-105">
                                <div class="absolute inset-0 bg-emerald-950/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <a href="<?= base_url('uploads/rtlh/'.$bansos['foto_after']) ?>" target="_blank" class="p-4 bg-white/20 backdrop-blur-md rounded-full text-white hover:bg-white/40 transition-all">
                                        <i data-lucide="maximize" class="w-6 h-6"></i>
                                    </a>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="p-12 text-center bg-slate-50 dark:bg-slate-800/30 rounded-2xl border border-dashed border-slate-200 dark:border-slate-800">
                                <i data-lucide="image-off" class="w-10 h-10 text-slate-300 mx-auto mb-3"></i>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Foto after belum diunggah</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Map -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-slate-900 rounded-2xl overflow-hidden shadow-sm border border-slate-100 dark:border-slate-800 aspect-square relative group">
                <div id="map-detail" class="w-full h-full z-10"></div>
                <div class="absolute top-4 left-4 z-[1000] bg-blue-950/80 backdrop-blur-md px-3 py-1.5 rounded-xl border border-white/10 shadow-2xl text-[8px] font-bold uppercase tracking-widest text-white flex items-center gap-2">
                    <div class="w-1 h-1 bg-emerald-400 rounded-full animate-pulse"></div>
                    Lokasi Realisasi
                </div>
                
                <?php if (!$bansos['wkt_realisasi']): ?>
                <div class="absolute inset-0 z-[1001] bg-slate-900/20 backdrop-blur-md flex items-center justify-center text-center p-8">
                    <div class="bg-white dark:bg-slate-900 p-6 rounded-xl shadow-2xl border border-slate-100 dark:border-slate-800">
                        <i data-lucide="map-off" class="w-8 h-8 text-slate-300 mx-auto mb-3"></i>
                        <p class="text-[9px] font-bold uppercase tracking-widest text-slate-400">Koordinat Belum Diinput</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="bg-blue-950 rounded-2xl p-8 text-white shadow-xl relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-700">
                    <i data-lucide="award" class="w-40 h-40 text-white"></i>
                </div>
                <h4 class="text-[9px] font-bold uppercase tracking-[0.3em] text-emerald-400 mb-6 flex items-center gap-2">
                    Status RLH Tuntas
                </h4>
                <div class="space-y-4 relative z-10">
                    <div class="flex justify-between items-center text-[9px]">
                        <span class="font-bold text-emerald-300/60 uppercase tracking-widest">Waktu Input</span>
                        <span class="font-bold text-emerald-50 tracking-wider"><?= date('d/m/Y H:i', strtotime($bansos['created_at'])) ?></span>
                    </div>
                    <div class="flex justify-between items-center text-[9px]">
                        <span class="font-bold text-emerald-300/60 uppercase tracking-widest">Status Data</span>
                        <span class="px-2 py-0.5 bg-emerald-500 text-white rounded-md font-bold">VERIFIED</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function initDetailMap() {
        const coordsStr = "<?= $bansos['wkt_realisasi'] ?? '' ?>";
        if (!coordsStr) return;

        const match = coordsStr.match(/POINT\s*\(\s*([-\d.]+)\s+([-\d.]+)\s*\)/i);
        if (!match) return;
        const lng = parseFloat(match[1]);
        const lat = parseFloat(match[2]);

        const isDark = document.documentElement.classList.contains('dark');
        const cartoDB = L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { 
            attribution: '&copy; CartoDB' 
        });
        const googleSat = L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains:['mt0','mt1','mt2','mt3'],
            attribution: '&copy; Google'
        });

        const map = L.map('map-detail', { zoomControl: false, layers: [cartoDB] }).setView([lat, lng], 17);
        
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

        const markerIcon = L.divIcon({
            className: 'custom-marker',
            html: `<div class="w-6 h-6 bg-emerald-600 rounded-full border-4 border-white shadow-xl flex items-center justify-center animate-bounce-slow"><div class="w-1 h-1 bg-white rounded-full"></div></div>`,
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        });

        L.marker([lat, lng], { icon: markerIcon }).addTo(map);
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
    @media print {
        .no-print { display: none !important; }
        body { background: white !important; }
        .bg-white, .bg-slate-50, .bg-blue-50, .bg-emerald-50 { background: white !important; border: 1px solid #eee !important; }
        .shadow-sm, .shadow-xl { shadow: none !important; }
    }
</style>
<?= $this->endSection() ?>
