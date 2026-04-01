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
        <a href="<?= base_url('aset-tanah') ?>" class="hover:text-blue-600 transition-colors">Aset Tanah</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">Detail Sertifikat</span>
    </nav>

    <!-- Header Action -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white dark:bg-slate-900 p-10 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm transition-all duration-300 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-blue-600/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
        <div class="relative z-10 flex items-center gap-6">
            <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-blue-600/20">
                <i data-lucide="landmark" class="w-8 h-8"></i>
            </div>
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-blue-950 dark:text-white uppercase tracking-tighter leading-tight"><?= $aset['nama_pemilik'] ?></h1>
                <p class="text-sm text-slate-500 font-bold uppercase tracking-widest mt-1">Sertifikat No. <?= $aset['no_sertifikat'] ?></p>
            </div>
        </div>
        <div class="flex items-center gap-3 relative z-10">
            <?php if (has_permission('edit_psu')) : ?>
            <a href="<?= base_url('aset-tanah/edit/' . $aset['id']) ?>" class="px-8 py-4 bg-amber-500 hover:bg-amber-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-amber-500/20 transition-all active:scale-95 flex items-center gap-3">
                <i data-lucide="edit-3" class="w-5 h-5"></i> Edit Data
            </a>
            <?php endif; ?>
            <a href="<?= base_url('aset-tanah') ?>" class="px-8 py-4 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-slate-700 transition-all active:scale-95">Kembali</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar Info -->
        <div class="space-y-8">
            <!-- Luas & Nilai -->
            <div class="bg-blue-950 p-10 rounded-[2.5rem] text-white shadow-2xl relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-700">
                    <i data-lucide="maximize" class="w-48 h-48"></i>
                </div>
                <div class="relative z-10 space-y-10">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-blue-300 mb-2">Luas Bidang Tanah</p>
                        <p class="text-5xl font-black italic"><?= number_format($aset['luas_m2'], 0, ',', '.') ?><span class="text-xl ml-1 opacity-40">M²</span></p>
                    </div>
                    <div class="pt-8 border-t border-white/10">
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-blue-300 mb-2">Estimasi Nilai Aset</p>
                        <p class="text-2xl font-black text-emerald-400 italic">Rp <?= number_format($aset['nilai_aset'], 0, ',', '.') ?></p>
                    </div>
                </div>
            </div>

            <!-- Atribut Utama -->
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-10 shadow-sm border border-slate-100 dark:border-slate-800 transition-all duration-300">
                <h3 class="text-xs font-black text-blue-950 dark:text-white uppercase tracking-[0.3em] mb-10 flex items-center gap-4">
                    <span class="w-10 h-[2px] bg-blue-600"></span> Informasi Legalitas
                </h3>
                <div class="space-y-8">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Nomor Hak</p>
                        <p class="text-sm font-black text-slate-700 dark:text-white uppercase"><?= $aset['nomor_hak'] ?: '-' ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Status Kepemilikan</p>
                        <span class="px-4 py-1.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full text-[10px] font-black uppercase border border-blue-100 dark:border-blue-800"><?= $aset['status_tanah'] ?: 'AKTIF' ?></span>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Tanggal Terbit</p>
                        <p class="text-sm font-black text-slate-700 dark:text-white uppercase"><?= $aset['tgl_terbit'] ? date('d F Y', strtotime($aset['tgl_terbit'])) : '-' ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Peruntukan Aset</p>
                        <p class="text-xs font-bold text-slate-500 dark:text-slate-400 leading-relaxed uppercase"><?= $aset['peruntukan'] ?: '-' ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Map Content -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white dark:bg-slate-900 rounded-[3rem] overflow-hidden shadow-2xl border border-slate-100 dark:border-slate-800 relative group lg:h-[650px]">
                <div id="map" class="w-full h-full z-10" style="background: #f8fafc;"></div>
                
                <div class="absolute top-8 left-8 z-[1000] flex flex-col gap-2">
                    <div class="bg-blue-950/90 backdrop-blur-xl text-white px-5 py-3 rounded-2xl shadow-2xl border border-white/10 flex items-center gap-4">
                        <div class="w-2 h-2 bg-emerald-400 rounded-full animate-ping"></div>
                        <span class="text-[10px] font-black uppercase tracking-[0.2em]">Visualisasi Titik Lokasi</span>
                    </div>
                </div>

                <?php if(!$aset['koordinat']): ?>
                <div class="absolute inset-0 z-[1001] bg-slate-900/20 backdrop-blur-md flex items-center justify-center text-center p-10">
                    <div class="bg-white dark:bg-slate-900 p-10 rounded-[3rem] shadow-2xl border border-slate-100 dark:border-slate-800 max-w-sm">
                        <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800 rounded-3xl flex items-center justify-center mx-auto mb-6">
                            <i data-lucide="map-off" class="w-10 h-10 text-slate-300"></i>
                        </div>
                        <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-2">Koordinat Kosong</h4>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Titik lokasi geospasial belum tercatat di database.</p>
                    </div>
                </div>
                <?php endif; ?>

                <div class="absolute bottom-8 right-8 z-[1000]">
                    <button onclick="focusAset()" class="p-4 bg-white dark:bg-slate-900 rounded-2xl shadow-2xl text-blue-600 hover:scale-110 active:scale-95 transition-all border border-slate-100 dark:border-slate-800">
                        <i data-lucide="maximize" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>

            <!-- Location Details Card -->
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-10 shadow-sm border border-slate-100 dark:border-slate-800 transition-all duration-300">
                <h3 class="text-xs font-black text-blue-950 dark:text-white uppercase tracking-[0.3em] mb-10 flex items-center gap-4">
                    <span class="w-10 h-[2px] bg-blue-600"></span> Penempatan Wilayah
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    <div class="space-y-8">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Wilayah Kecamatan</p>
                            <p class="text-sm font-black text-blue-600 uppercase tracking-wider">Kec. <?= $aset['kecamatan'] ?></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Desa / Kelurahan</p>
                            <p class="text-sm font-black text-slate-700 dark:text-white uppercase tracking-wider"><?= $aset['desa_kelurahan'] ?></p>
                        </div>
                    </div>
                    <div class="space-y-8">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Lokasi / Alamat</p>
                            <p class="text-sm font-bold text-slate-600 dark:text-slate-300 leading-relaxed uppercase"><?= $aset['lokasi'] ?></p>
                        </div>
                        <div class="pt-6 border-t dark:border-slate-800">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Catatan Inventaris</p>
                            <p class="text-xs font-medium text-slate-500 dark:text-slate-400 leading-relaxed italic">"<?= $aset['keterangan'] ?: 'Tidak ada catatan tambahan untuk aset ini.' ?>"</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
        initMap();
    });

    let map;
    const coordsStr = "<?= $aset['koordinat'] ?>";

    function initMap() {
        if (!coordsStr) return;
        if (typeof L === 'undefined') { setTimeout(initMap, 100); return; }

        try {
            const [lat, lng] = coordsStr.split(',').map(c => parseFloat(c.trim()));
            if (isNaN(lat) || isNaN(lng)) return;

            const isDark = document.documentElement.classList.contains('dark');
            const tileUrl = isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png';
            const satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: 'Esri' });

            map = L.map('map', { zoomControl: false, layers: [satellite] }).setView([lat, lng], 17);
            L.control.zoom({ position: 'bottomright' }).addTo(map);

            const markerIcon = L.divIcon({
                className: 'custom-marker',
                html: `<div class="w-10 h-10 bg-blue-600 rounded-full border-4 border-white shadow-2xl flex items-center justify-center animate-bounce-slow"><div class="w-3 h-3 bg-white rounded-full"></div></div>`,
                iconSize: [40, 40],
                iconAnchor: [20, 40]
            });

            L.marker([lat, lng], { icon: markerIcon }).addTo(map);
            setTimeout(() => map.invalidateSize(), 500);
        } catch (e) { console.error('Map Error:', e); }
    }

    function focusAset() {
        if (!map || !coordsStr) return;
        const [lat, lng] = coordsStr.split(',').map(c => parseFloat(c.trim()));
        map.setView([lat, lng], 18, { animate: true, duration: 1.5 });
    }
</script>

<style>
    @keyframes bounce-slow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    .animate-bounce-slow { animation: bounce-slow 2s infinite ease-in-out; }
</style>
<?= $this->endSection() ?>
