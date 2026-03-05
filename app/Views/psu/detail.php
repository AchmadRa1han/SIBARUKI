<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="space-y-8 pb-20">
    <!-- Header Terpadu -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <div class="flex items-center gap-3">
                <a href="<?= base_url('psu') ?>" class="p-3 bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 hover:bg-blue-50 dark:hover:bg-slate-800 transition-all group">
                    <i data-lucide="arrow-left" class="w-5 h-5 text-slate-400 group-hover:text-blue-600"></i>
                </a>
                <div>
                    <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.3em]">Manajemen Infrastruktur</p>
                    <h1 class="text-3xl font-black text-blue-950 dark:text-white uppercase tracking-tight"><?= $jalan['nama_jalan'] ?: 'RUAS TANPA NAMA' ?></h1>
                </div>
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            <?php if (has_permission('edit_psu')): ?>
            <a href="<?= base_url('psu/edit/' . $jalan['id']) ?>" class="bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-sm border border-slate-100 dark:border-slate-800 hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all flex items-center gap-2">
                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> Edit
            </a>
            <?php endif; ?>
            <div class="h-10 w-px bg-slate-200 dark:bg-slate-800 hidden md:block mx-2"></div>
            <div class="flex flex-col items-end">
                <p class="text-[9px] text-slate-400 font-black uppercase tracking-widest">ID Referensi</p>
                <p class="text-sm font-black text-blue-950 dark:text-white">#<?= str_pad($jalan['id_csv'] ?? $jalan['id'], 4, '0', STR_PAD_LEFT) ?></p>
            </div>
        </div>
    </div>

    <!-- Ringkasan Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-blue-600 rounded-[2rem] p-6 text-white shadow-xl shadow-blue-900/20 relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-[9px] font-black uppercase tracking-[0.2em] opacity-80 mb-1">Nilai Jalan</p>
                <h4 class="text-2xl font-black italic"><?= number_format($jalan['jalan'], 2) ?> <span class="text-xs font-medium not-italic opacity-70">Unit</span></h4>
            </div>
            <i data-lucide="activity" class="absolute top-1/2 right-6 -translate-y-1/2 w-16 h-16 opacity-10 group-hover:scale-110 transition-transform duration-500"></i>
        </div>
        <div class="bg-white dark:bg-slate-900 rounded-[2rem] p-6 border border-slate-100 dark:border-slate-800 shadow-sm flex items-center gap-5">
            <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-950/30 rounded-2xl flex items-center justify-center text-indigo-600">
                <i data-lucide="navigation" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[9px] text-slate-400 font-black uppercase tracking-[0.2em]">Tipe Data</p>
                <p class="text-sm font-black text-blue-950 dark:text-white uppercase">LineString (WKT)</p>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 rounded-[2rem] p-6 border border-slate-100 dark:border-slate-800 shadow-sm flex items-center gap-5">
            <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-950/30 rounded-2xl flex items-center justify-center text-emerald-600">
                <i data-lucide="calendar" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[9px] text-slate-400 font-black uppercase tracking-[0.2em]">Terakhir Diperbarui</p>
                <p class="text-sm font-black text-blue-950 dark:text-white"><?= date('d M Y', strtotime($jalan['updated_at'])) ?></p>
            </div>
        </div>
    </div>

    <!-- Konten Utama -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Peta (Visualisasi) -->
        <div class="lg:col-span-8 space-y-6">
            <div class="relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-[3rem] blur opacity-10 group-hover:opacity-20 transition duration-1000"></div>
                <div class="relative bg-white dark:bg-slate-900 rounded-[3rem] overflow-hidden shadow-2xl border border-slate-100 dark:border-slate-800">
                    <div id="map" class="w-full h-[65vh] z-10" style="min-height: 550px; background: #f8fafc;"></div>
                    
                    <!-- Overlay Kontrol Kustom -->
                    <div class="absolute top-8 left-8 z-[1000] flex flex-col gap-2">
                        <div class="bg-blue-950/90 backdrop-blur-xl text-white px-5 py-3 rounded-2xl shadow-2xl border border-white/10 flex items-center gap-4">
                            <div class="relative">
                                <div class="w-2 h-2 bg-blue-400 rounded-full animate-ping"></div>
                                <div class="absolute inset-0 w-2 h-2 bg-blue-400 rounded-full"></div>
                            </div>
                            <span class="text-[10px] font-black uppercase tracking-[0.2em]">Visualisasi Geospasial</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Atribut -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-10 shadow-sm border border-slate-100 dark:border-slate-800 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-10 opacity-[0.03] pointer-events-none">
                    <i data-lucide="database" class="w-40 h-40 text-blue-950"></i>
                </div>

                <div class="flex items-center gap-3 mb-10">
                    <div class="w-10 h-10 bg-blue-950 rounded-xl flex items-center justify-center text-white">
                        <i data-lucide="layers" class="w-5 h-5"></i>
                    </div>
                    <h3 class="text-xs font-black text-blue-950 dark:text-white uppercase tracking-widest">Metadata Teknis</h3>
                </div>

                <div class="space-y-8">
                    <div class="group">
                        <p class="text-[9px] text-slate-400 font-black uppercase tracking-[0.2em] mb-2 group-hover:text-blue-600 transition-colors">Nama Ruas Jalan</p>
                        <p class="text-sm font-bold text-blue-950 dark:text-white uppercase leading-relaxed"><?= $jalan['nama_jalan'] ?: 'TIDAK TERDEFINISI' ?></p>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-[9px] text-slate-400 font-black uppercase tracking-[0.2em] mb-2">ID CSV</p>
                            <p class="text-sm font-bold text-slate-700 dark:text-slate-300"><?= $jalan['id_csv'] ?? '-' ?></p>
                        </div>
                        <div>
                            <p class="text-[9px] text-slate-400 font-black uppercase tracking-[0.2em] mb-2">Nilai Jalan</p>
                            <p class="text-sm font-bold text-slate-700 dark:text-slate-300"><?= number_format($jalan['jalan'], 2) ?></p>
                        </div>
                    </div>

                    <div class="pt-8 border-t border-slate-50 dark:border-slate-800">
                        <div class="flex items-center justify-between mb-4">
                            <p class="text-[9px] text-slate-400 font-black uppercase tracking-[0.2em]">Koordinat WKT</p>
                            <button onclick="copyWKT()" class="p-2 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg transition-colors" title="Salin WKT">
                                <i data-lucide="copy" class="w-3.5 h-3.5 text-slate-400"></i>
                            </button>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/50 p-5 rounded-[1.5rem] border border-slate-100 dark:border-slate-800">
                            <code id="wkt-code" class="text-[10px] text-slate-500 dark:text-slate-400 break-all font-mono leading-relaxed block max-h-32 overflow-y-auto custom-scrollbar">
                                <?= $jalan['wkt'] ?>
                            </code>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kartu Aksi Cepat -->
            <div class="bg-slate-950 rounded-[2.5rem] p-10 text-white shadow-2xl relative overflow-hidden">
                <div class="relative z-10 space-y-6">
                    <div class="space-y-2">
                        <h4 class="text-xs font-black uppercase tracking-widest text-blue-400">Navigasi Cepat</h4>
                        <p class="text-[11px] text-slate-400 leading-relaxed">Gunakan fungsi di bawah untuk berinteraksi dengan data spasial ini.</p>
                    </div>
                    <div class="grid grid-cols-1 gap-3">
                        <button onclick="map.fitBounds(pathLayer.getBounds(), {padding:[50,50]})" class="w-full py-4 bg-white/5 hover:bg-white/10 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center justify-center gap-3 border border-white/5">
                            <i data-lucide="maximize" class="w-4 h-4"></i> Reset Fokus Peta
                        </button>
                        <a href="<?= base_url('psu') ?>" class="w-full py-4 bg-blue-600 hover:bg-blue-500 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center justify-center gap-3 shadow-lg shadow-blue-600/20">
                            <i data-lucide="list" class="w-4 h-4"></i> Kembali ke Daftar
                        </a>
                    </div>
                </div>
                <div class="absolute -bottom-10 -right-10 opacity-10 pointer-events-none">
                    <i data-lucide="navigation-2" class="w-48 h-40"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // --- UTM ZONE 51S CONVERTER (SINJAI) ---
    function utmToLatLng(easting, northing) {
        const a = 6378137;
        const f = 1 / 298.257223563;
        const b = a * (1 - f);
        const e = Math.sqrt(1 - (b * b) / (a * a));
        const e1sq = (e * e) / (1 - e * e);
        const k0 = 0.9996;
        const falseEasting = 500000;
        const falseNorthing = 10000000;
        const zoneCentralMeridian = 123 * (Math.PI / 180); // CM Zone 51 (Sinjai)

        let x = easting - falseEasting;
        let y = northing - falseNorthing;

        let M = y / k0;
        let mu = M / (a * (1 - e * e / 4 - 3 * e * e * e * e / 64 - 5 * e * e * e * e * e * e / 256));

        let phi1Rad = mu + (3 * e1sq / 2 - 27 * e1sq * e1sq * e1sq / 32) * Math.sin(2 * mu) 
                    + (21 * e1sq * e1sq / 16 - 55 * e1sq * e1sq * e1sq * e1sq / 32) * Math.sin(4 * mu)
                    + (151 * e1sq * e1sq * e1sq / 96) * Math.sin(6 * mu);

        let N1 = a / Math.sqrt(1 - e * e * Math.sin(phi1Rad) * Math.sin(phi1Rad));
        let T1 = Math.tan(phi1Rad) * Math.tan(phi1Rad);
        let C1 = e1sq * Math.cos(phi1Rad) * Math.cos(phi1Rad);
        let R1 = a * (1 - e * e) / Math.pow(1 - e * e * Math.sin(phi1Rad) * Math.sin(phi1Rad), 1.5);
        let D = x / (N1 * k0);

        let lat = phi1Rad - (N1 * Math.tan(phi1Rad) / R1) * (D * D / 2 - (5 + 3 * T1 + 10 * C1 - 4 * C1 * C1 - 9 * e1sq) * D * D * D * D / 24
                + (61 + 90 * T1 + 298 * C1 + 45 * T1 * T1 - 252 * e1sq - 3 * C1 * C1) * D * D * D * D * D * D / 720);
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
            const standard = L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { 
                attribution: '&copy; Sibaruki',
                maxZoom: 20
            });

            map = L.map('map', { zoomControl: false, layers: [standard] }).setView([-5.1245, 120.2536], 13);
            L.control.zoom({ position: 'bottomright' }).addTo(map);

            if (coords && coords.length > 0) {
                pathLayer = L.polyline(coords, { 
                    color: '#2563eb', 
                    weight: 8, 
                    opacity: 1,
                    lineCap: 'round',
                    lineJoin: 'round'
                }).addTo(map);

                // Efek Glow / Shadow pada garis
                L.polyline(coords, { color: '#60a5fa', weight: 12, opacity: 0.2, lineCap: 'round' }).addTo(map);
                
                map.fitBounds(pathLayer.getBounds(), { padding: [100, 100], animate: true, duration: 1.5 });
            } else {
                mapContainer.innerHTML = '<div class="flex flex-col items-center justify-center h-full bg-slate-50 dark:bg-slate-800/50 text-slate-400 gap-4"><i data-lucide="map-pin-off" class="w-12 h-12 opacity-20"></i><p class="font-black uppercase text-[10px] tracking-widest px-8 text-center">Data Geospasial Tidak Valid</p></div>';
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }

            setTimeout(() => { map.invalidateSize(); }, 500);
        } catch(err) { console.error('Leaflet Init Error:', err); }
    }

    function copyWKT() {
        const text = document.getElementById('wkt-code').innerText;
        navigator.clipboard.writeText(text).then(() => {
            if (typeof showToast === 'function') showToast('WKT berhasil disalin ke clipboard!');
        });
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
