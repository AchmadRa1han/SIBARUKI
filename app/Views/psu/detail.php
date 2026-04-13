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
            <h3 class="text-xl font-bold text-blue-950 dark:text-white tracking-tight mb-1 relative z-10 uppercase">LineString</h3>
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
                            <a href="<?= base_url('uploads/psu/' . $jalan['foto_before']) ?>" target="_blank" class="absolute inset-0 bg-blue-950/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <i data-lucide="maximize" class="w-6 h-6 text-white"></i>
                            </a>
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
                            <a href="<?= base_url('uploads/psu/' . $jalan['foto_after']) ?>" target="_blank" class="absolute inset-0 bg-blue-950/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <i data-lucide="maximize" class="w-6 h-6 text-white"></i>
                            </a>
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

            <!-- Kartu Sistem Log -->
            <div class="bg-slate-900 rounded-2xl p-8 text-white shadow-xl relative overflow-hidden group transition-all hover:bg-slate-950">
                <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:scale-110 transition-transform duration-700">
                    <i data-lucide="info" class="w-40 h-40"></i>
                </div>
                <h4 class="text-[9px] font-bold uppercase tracking-[0.3em] text-blue-400 mb-6 flex items-center gap-2">
                    Audit Log Geospasial
                </h4>
                <div class="space-y-4 relative z-10">
                    <div class="flex justify-between items-center text-[9px]">
                        <span class="font-bold text-slate-500 uppercase tracking-widest">Entry Date</span>
                        <span class="font-bold text-slate-300 tracking-wider"><?= date('d/m/y H:i', strtotime($jalan['created_at'])) ?></span>
                    </div>
                    <div class="flex justify-between items-center text-[9px]">
                        <span class="font-bold text-slate-500 uppercase tracking-widest">Last Update</span>
                        <span class="font-bold text-blue-400 tracking-wider"><?= date('d/m/y H:i', strtotime($jalan['updated_at'])) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // --- UTM ZONE 51S CONVERTER (SINJAI) ---
    function utmToLatLng(easting, northing) {
        const a = 6378137, f = 1 / 298.257223563;
        const b = a * (1 - f), e = Math.sqrt(1 - (b * b) / (a * a)), e1sq = (e * e) / (1 - e * e);
        const k0 = 0.9996, falseEasting = 500000, falseNorthing = 10000000;
        const zoneCentralMeridian = 123 * (Math.PI / 180); 
        let x = easting - falseEasting, y = northing - falseNorthing;
        let M = y / k0, mu = M / (a * (1 - e * e / 4 - 3 * e * e * e * e / 64 - 5 * e * e * e * e * e * e / 256));
        let phi1Rad = mu + (3 * e1sq / 2 - 27 * e1sq * e1sq * e1sq / 32) * Math.sin(2 * mu) + (21 * e1sq * e1sq / 16 - 55 * e1sq * e1sq * e1sq / 32) * Math.sin(4 * mu) + (151 * e1sq * e1sq * e1sq / 96) * Math.sin(6 * mu);
        let N1 = a / Math.sqrt(1 - e * e * Math.sin(phi1Rad) * Math.sin(phi1Rad)), T1 = Math.tan(phi1Rad) * Math.tan(phi1Rad), C1 = e1sq * Math.cos(phi1Rad) * Math.cos(phi1Rad), R1 = a * (1 - e * e) / Math.pow(1 - e * e * Math.sin(phi1Rad) * Math.sin(phi1Rad), 1.5);
        let D = x / (N1 * k0);
        let lat = phi1Rad - (N1 * Math.tan(phi1Rad) / R1) * (D * D / 2 - (5 + 3 * T1 + 10 * C1 - 4 * C1 * C1 - 9 * e1sq) * D * D * D * D / 24 + (61 + 90 * T1 + 298 * C1 + 45 * T1 * T1 - 252 * e1sq - 3 * C1 * C1) * D * D * D * D * D * D / 720);
        let lon = zoneCentralMeridian + (D - (1 + 2 * T1 + C1) * D * D * D / 6 + (5 - 2 * C1 + 28 * T1 - 3 * C1 * C1 + 8 * e1sq + 24 * T1 * T1) * D * D * D * D * D / 120) / Math.cos(phi1Rad);
        return [lat * (180 / Math.PI), lon * (180 / Math.PI)];
    }

    function parseWKT(wkt) {
        if (!wkt || typeof wkt !== 'string') return null;
        try {
            const cleanWkt = wkt.toUpperCase().trim();
            if (cleanWkt.includes('LINESTRING')) {
                const match = cleanWkt.match(/\(([^()]+)\)/);
                if (!match || !match[1]) return null;
                return match[1].split(',').map(pair => {
                    const parts = pair.trim().split(/\s+/);
                    if (parts.length >= 2) return utmToLatLng(parseFloat(parts[0]), parseFloat(parts[1]));
                    return null;
                }).filter(p => p !== null);
            }
        } catch(e) { console.error('WKT Parse Error:', e); }
        return null;
    }

    let map, pathLayer;
    function initMap() {
        if (typeof L === 'undefined') { setTimeout(initMap, 200); return; }
        const mapContainer = document.getElementById('map');
        if (!mapContainer) return;

        try {
            const wkt = `<?= addslashes($jalan['wkt']) ?>`;
            const coords = parseWKT(wkt);
            const isDark = document.documentElement.classList.contains('dark');
            const standard = L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { attribution: '&copy; Sibaruki', maxZoom: 20 });

            map = L.map('map', { zoomControl: false, layers: [standard] }).setView([-5.1245, 120.2536], 13);
            L.control.zoom({ position: 'bottomright' }).addTo(map);

            if (coords && coords.length > 0) {
                pathLayer = L.polyline(coords, { color: '#2563eb', weight: 6, opacity: 1, lineCap: 'round', lineJoin: 'round' }).addTo(map);
                L.polyline(coords, { color: '#60a5fa', weight: 10, opacity: 0.2, lineCap: 'round' }).addTo(map);
                map.fitBounds(pathLayer.getBounds(), { padding: [80, 80], animate: true, duration: 1.5 });
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

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.1); border-radius: 10px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); }
</style>
<?= $this->endSection() ?>
