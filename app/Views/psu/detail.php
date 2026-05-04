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
        <a href="<?= base_url('psu') ?>" class="hover:text-blue-600 transition-colors">PSU JALAN</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">Detail Ruas</span>
    </nav>

    <!-- Header Action -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white dark:bg-slate-900 p-8 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm transition-all duration-300 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-48 h-48 bg-indigo-600/5 rounded-full -mr-24 -mt-24 blur-3xl"></div>
        <div class="relative z-10 flex items-center gap-4">
            <a href="<?= base_url('psu') ?>" class="p-3 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl hover:bg-blue-600 hover:text-white transition-all active:scale-95" title="Kembali">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-indigo-600/20">
                    <i data-lucide="navigation" class="w-7 h-7"></i>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-blue-950 dark:text-white uppercase tracking-tighter"><?= $jalan['nama_jalan'] ?: 'RUAS TANPA NAMA' ?></h1>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">ID Referensi:</span>
                        <span class="px-2 py-0.5 bg-blue-950 dark:bg-blue-800 text-white rounded-lg font-mono text-[10px] font-bold shadow-lg">#<?= str_pad($jalan['id_csv'] ?? $jalan['id'], 4, '0', STR_PAD_LEFT) ?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2 relative z-10">
            <?php if (has_permission('edit_psu')): ?>
            <a href="<?= base_url('psu/edit/' . $jalan['id']) ?>" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-[10px] font-bold uppercase tracking-widest shadow-lg shadow-amber-500/20 transition-all active:scale-95 flex items-center gap-2">
                <i data-lucide="edit-3" class="w-4 h-4"></i> Edit
            </a>
            <?php endif; ?>
            <a href="<?= base_url('psu') ?>" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-slate-700 transition-all active:scale-95">Kembali</a>
        </div>
    </div>

    <!-- Ringkasan Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-slate-900 p-8 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm group relative overflow-hidden transition-all hover:shadow-xl hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-600/5 rounded-full -mr-16 -mt-16 blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="flex items-center justify-between mb-6 relative z-10">
                <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-900/30 rounded-xl flex items-center justify-center text-indigo-600 dark:text-indigo-400 shadow-inner">
                    <i data-lucide="activity" class="w-6 h-6"></i>
                </div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em]">Nilai Aset</span>
            </div>
            <h3 class="text-4xl font-bold text-indigo-600 tracking-tighter mb-1 relative z-10"><?= number_format($jalan['jalan'], 2) ?></h3>
            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest relative z-10">Metrik Penilaian PSU</p>
        </div>

        <div class="bg-white dark:bg-slate-900 p-8 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm group relative overflow-hidden transition-all hover:shadow-xl hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-600/5 rounded-full -mr-16 -mt-16 blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="flex items-center justify-between mb-6 relative z-10">
                <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/30 rounded-xl flex items-center justify-center text-blue-600 dark:text-blue-400 shadow-inner">
                    <i data-lucide="layers" class="w-6 h-6"></i>
                </div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em]">Tipe Struktur</span>
            </div>
            <h3 class="text-xl font-bold text-blue-950 dark:text-white tracking-tight mb-1 relative z-10 uppercase">Point</h3>
            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest relative z-10">Format Geospasial WKT</p>
        </div>

        <div class="bg-blue-950 p-8 rounded-2xl shadow-xl shadow-blue-950/20 relative overflow-hidden transition-all hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-400/10 rounded-full -mr-16 -mt-16 blur-2xl"></div>
            <div class="flex items-center justify-between mb-6 relative z-10">
                <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center text-white">
                    <i data-lucide="calendar" class="w-6 h-6"></i>
                </div>
                <span class="text-[9px] font-bold text-blue-300/40 uppercase tracking-[0.2em]">Sync Date</span>
            </div>
            <h3 class="text-xl font-bold text-white tracking-tight mb-1 relative z-10"><?= date('d M Y', strtotime($jalan['updated_at'])) ?></h3>
            <p class="text-[9px] text-blue-300/60 font-bold uppercase tracking-widest relative z-10">Terakhir Diperbarui</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Peta (Visualisasi) -->
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-white dark:bg-slate-900 rounded-2xl overflow-hidden shadow-sm border border-slate-100 dark:border-slate-800 relative group aspect-video lg:aspect-auto lg:h-[500px]">
                <div id="map" class="w-full h-full z-10" style="background: #f8fafc;"></div>
                <div class="absolute top-6 left-6 z-[1000] bg-blue-950/90 backdrop-blur-xl text-white px-4 py-2 rounded-xl shadow-2xl border border-white/10 flex items-center gap-3">
                    <div class="w-1.5 h-1.5 bg-blue-400 rounded-full animate-ping"></div>
                    <span class="text-[9px] font-bold uppercase tracking-[0.2em]">Visualisasi Geospasial</span>
                </div>
            </div>

            <!-- Dokumentasi Before After -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-800">
                    <h3 class="text-[10px] font-bold text-blue-950 dark:text-white uppercase tracking-[0.3em] mb-4 flex items-center gap-3">
                        <span class="w-8 h-[2px] bg-rose-500"></span> Kondisi Sebelum
                    </h3>
                    <div class="aspect-video bg-slate-50 dark:bg-slate-950 rounded-xl overflow-hidden border border-slate-100 dark:border-slate-800 flex items-center justify-center group relative">
                        <?php if (!empty($jalan['foto_before'])): ?>
                            <img src="<?= base_url('uploads/psu/' . $jalan['foto_before']) ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <?php else: ?>
                            <div class="text-center p-8">
                                <i data-lucide="image-off" class="w-10 h-10 text-slate-300 mx-auto mb-3"></i>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Foto tidak tersedia</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-800">
                    <h3 class="text-[10px] font-bold text-blue-950 dark:text-white uppercase tracking-[0.3em] mb-4 flex items-center gap-3">
                        <span class="w-8 h-[2px] bg-emerald-500"></span> Kondisi Sesudah
                    </h3>
                    <div class="aspect-video bg-slate-50 dark:bg-slate-950 rounded-xl overflow-hidden border border-slate-100 dark:border-slate-800 flex items-center justify-center group relative">
                        <?php if (!empty($jalan['foto_after'])): ?>
                            <img src="<?= base_url('uploads/psu/' . $jalan['foto_after']) ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <?php else: ?>
                            <div class="text-center p-8">
                                <i data-lucide="image-off" class="w-10 h-10 text-slate-300 mx-auto mb-3"></i>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Foto tidak tersedia</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Atribut -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-8 shadow-sm border border-slate-100 dark:border-slate-800 relative overflow-hidden transition-all duration-300">
                <div class="absolute top-0 right-0 p-8 opacity-[0.03] pointer-events-none text-blue-950 dark:text-white">
                    <i data-lucide="database" class="w-32 h-32"></i>
                </div>

                <h3 class="text-[10px] font-bold text-blue-950 dark:text-white uppercase tracking-[0.3em] mb-8 flex items-center gap-3">
                    <span class="w-8 h-[2px] bg-blue-600"></span> Metadata Atribut
                </h3>

                <div class="space-y-8 relative z-10">
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-2">Nama Ruas Jalan</p>
                        <p class="text-sm font-bold text-blue-950 dark:text-white uppercase leading-relaxed"><?= $jalan['nama_jalan'] ?: 'RUAS TANPA NAMA' ?></p>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">ID Inventaris</p>
                            <p class="text-xs font-bold text-slate-700 dark:text-slate-300"><?= $jalan['id_csv'] ?? '-' ?></p>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Skor Kondisi</p>
                            <p class="text-xs font-bold text-indigo-600"><?= number_format($jalan['jalan'], 2) ?></p>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-50 dark:border-slate-800">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Format WKT</p>
                            <button onclick="copyWKT()" class="p-1.5 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-all text-blue-600" title="Salin Koordinat">
                                <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                            </button>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-2xl border border-slate-100 dark:border-slate-800">
                            <code id="wkt-code" class="text-[9px] text-slate-500 dark:text-slate-400 break-all font-mono leading-relaxed block max-h-40 overflow-y-auto custom-scrollbar">
                                <?= $jalan['wkt'] ?>
                            </code>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function parseWKT(wkt) {
        if (!wkt || typeof wkt !== 'string') return null;
        try {
            const cleanWkt = wkt.toUpperCase().trim();
            if (cleanWkt.includes('POINT')) {
                const match = cleanWkt.match(/\(([^()]+)\)/);
                if (!match || !match[1]) return null;
                const parts = match[1].trim().split(/\s+/);
                return [parseFloat(parts[1]), parseFloat(parts[0])]; // lat, lng
            }
        } catch(e) { console.error('WKT Parse Error:', e); }
        return null;
    }

    let map, pointLayer;
    function initMap() {
        if (typeof L === 'undefined') { setTimeout(initMap, 200); return; }
        const mapContainer = document.getElementById('map');
        if (!mapContainer) return;

        try {
            const wkt = `<?= addslashes($jalan['wkt']) ?>`;
            const coords = parseWKT(wkt);
            const isDark = document.documentElement.classList.contains('dark');
            const standard = L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { attribution: '&copy; Sibaruki', maxZoom: 20 });
            const googleSat = L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains:['mt0','mt1','mt2','mt3'],
                attribution: '&copy; Google'
            });

            map = L.map('map', { zoomControl: false, layers: [standard] }).setView([-5.1245, 120.2536], 13);
            
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
                            if (map.hasLayer(standard)) { 
                                map.removeLayer(standard); 
                                map.addLayer(googleSat); 
                                btn.style.backgroundColor = '#2563eb'; 
                                svg.setAttribute('stroke', '#ffffff'); 
                            }
                            else { 
                                map.removeLayer(googleSat); 
                                map.addLayer(standard); 
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

            if (coords) {
                pointLayer = L.circleMarker(coords, { radius: 10, fillColor: '#2563eb', color: '#fff', weight: 3, fillOpacity: 0.8 }).addTo(map);
                map.setView(coords, 16);
            }
            if (typeof lucide !== 'undefined') lucide.createIcons();
            setTimeout(() => { map.invalidateSize(); }, 500);
        } catch(err) { console.error('Leaflet Init Error:', err); }
    }

    function copyWKT() {
        const text = document.getElementById('wkt-code').innerText;
        navigator.clipboard.writeText(text).then(() => { if (typeof showToast === 'function') showToast('WKT berhasil disalin!'); });
    }

    window.addEventListener('load', initMap);
</script>
<?= $this->endSection() ?>
