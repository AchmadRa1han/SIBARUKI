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
        <a href="<?= base_url('aset-tanah') ?>" class="hover:text-blue-600 transition-colors">Aset Tanah</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">Detail Sertifikat</span>
    </nav>

    <!-- Header Action -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white dark:bg-slate-900 p-8 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm transition-all duration-300 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-48 h-48 bg-blue-600/5 rounded-full -mr-24 -mt-24 blur-3xl"></div>
        <div class="relative z-10 flex items-center gap-5">
            <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-blue-600/20">
                <i data-lucide="landmark" class="w-7 h-7"></i>
            </div>
            <div>
                <h1 class="text-xl md:text-2xl font-black text-blue-950 dark:text-white uppercase tracking-tighter leading-tight"><?= $aset['nama_pemilik'] ?></h1>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-[0.2em] mt-1">Sertifikat No. <?= $aset['no_sertifikat'] ?></p>
            </div>
        </div>
        <div class="flex items-center gap-2 relative z-10">
            <?php if (has_permission('edit_psu')) : ?>
            <a href="<?= base_url('aset-tanah/edit/' . $aset['id']) ?>" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-amber-500/20 transition-all active:scale-95 flex items-center gap-2">
                <i data-lucide="edit-3" class="w-4 h-4"></i> Edit
            </a>
            <?php endif; ?>
            <a href="<?= base_url('aset-tanah') ?>" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-slate-700 transition-all active:scale-95">Kembali</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sidebar Info -->
        <div class="space-y-6">
            <!-- Luas & Nilai -->
            <div class="bg-blue-950 p-8 rounded-[2rem] text-white shadow-xl relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-700">
                    <i data-lucide="maximize" class="w-40 h-40"></i>
                </div>
                <div class="relative z-10 space-y-8">
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-[0.3em] text-blue-300 mb-2">Luas Bidang Tanah</p>
                        <p class="text-4xl font-black italic"><?= number_format($aset['luas_m2'], 0, ',', '.') ?><span class="text-lg ml-1 opacity-40">M²</span></p>
                    </div>
                    <div class="pt-6 border-t border-white/10">
                        <p class="text-[9px] font-black uppercase tracking-[0.3em] text-blue-300 mb-2">Estimasi Nilai Aset</p>
                        <p class="text-xl font-black text-emerald-400 italic">Rp <?= number_format($aset['nilai_aset'], 0, ',', '.') ?></p>
                    </div>
                </div>
            </div>

            <!-- Atribut Utama -->
            <div class="bg-white dark:bg-slate-900 rounded-[2rem] p-8 shadow-sm border border-slate-100 dark:border-slate-800 transition-all duration-300">
                <h3 class="text-[10px] font-black text-blue-950 dark:text-white uppercase tracking-[0.3em] mb-8 flex items-center gap-3">
                    <span class="w-8 h-[2px] bg-blue-600"></span> Informasi Legalitas
                </h3>
                <div class="space-y-6">
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Nomor Hak</p>
                        <p class="text-sm font-black text-slate-700 dark:text-white uppercase"><?= $aset['nomor_hak'] ?: '-' ?></p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Status Kepemilikan</p>
                        <span class="px-3 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full text-[9px] font-black uppercase border border-blue-100 dark:border-blue-800"><?= $aset['status_tanah'] ?: 'AKTIF' ?></span>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Tanggal Terbit</p>
                        <p class="text-sm font-black text-slate-700 dark:text-white uppercase"><?= $aset['tgl_terbit'] ? date('d F Y', strtotime($aset['tgl_terbit'])) : '-' ?></p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Peruntukan Aset</p>
                        <p class="text-xs font-bold text-slate-500 dark:text-slate-400 leading-relaxed uppercase"><?= $aset['peruntukan'] ?: '-' ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Map Content -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] overflow-hidden shadow-sm border border-slate-100 dark:border-slate-800 relative group lg:h-[600px]">
                <div id="map" class="w-full h-full z-10" style="background: #f8fafc;"></div>
                
                <div class="absolute top-6 left-6 z-[1000] flex items-center gap-3">
                    <div class="bg-blue-950/90 backdrop-blur-xl text-white px-4 py-2 rounded-xl shadow-2xl border border-white/10 flex items-center gap-3">
                        <div class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-ping"></div>
                        <span class="text-[9px] font-black uppercase tracking-[0.2em]">Visualisasi Titik Lokasi</span>
                    </div>
                </div>

                <?php if(!$aset['koordinat']): ?>
                <div class="absolute inset-0 z-[1001] bg-slate-900/20 backdrop-blur-md flex items-center justify-center text-center p-10">
                    <div class="bg-white dark:bg-slate-900 p-8 rounded-[2rem] shadow-2xl border border-slate-100 dark:border-slate-800 max-w-sm">
                        <div class="w-16 h-16 bg-slate-50 dark:bg-slate-800 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="map-off" class="w-8 h-8 text-slate-300"></i>
                        </div>
                        <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Koordinat Kosong</h4>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Titik geospasial belum tercatat.</p>
                    </div>
                </div>
                <?php endif; ?>

                <div class="absolute bottom-6 right-6 z-[1000]">
                    <button onclick="focusAset()" class="p-3 bg-white dark:bg-slate-900 rounded-xl shadow-2xl text-blue-600 hover:scale-110 active:scale-95 transition-all border border-slate-100 dark:border-slate-800">
                        <i data-lucide="maximize" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>

            <!-- Location Details Card -->
            <div class="bg-white dark:bg-slate-900 rounded-[2rem] p-8 shadow-sm border border-slate-100 dark:border-slate-800 transition-all duration-300">
                <h3 class="text-[10px] font-black text-blue-950 dark:text-white uppercase tracking-[0.3em] mb-8 flex items-center gap-3">
                    <span class="w-8 h-[2px] bg-blue-600"></span> Penempatan Wilayah
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-6">
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Wilayah Kecamatan</p>
                            <p class="text-sm font-black text-blue-600 uppercase tracking-wider">Kec. <?= $aset['kecamatan'] ?></p>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Desa / Kelurahan</p>
                            <p class="text-sm font-black text-slate-700 dark:text-white uppercase tracking-wider"><?= $aset['desa_kelurahan'] ?></p>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Lokasi / Alamat</p>
                            <p class="text-xs font-bold text-slate-600 dark:text-slate-300 leading-relaxed uppercase"><?= $aset['lokasi'] ?></p>
                        </div>
                        <div class="pt-4 border-t dark:border-slate-800">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Catatan Inventaris</p>
                            <p class="text-[11px] font-medium text-slate-500 dark:text-slate-400 leading-relaxed italic">"<?= $aset['keterangan'] ?: 'Tidak ada catatan tambahan untuk aset ini.' ?>"</p>
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
            const standard = L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { attribution: '&copy; Sibaruki', maxZoom: 20 });
            
            map = L.map('map', { zoomControl: false, layers: [standard] }).setView([lat, lng], 17);
            L.control.zoom({ position: 'bottomright' }).addTo(map);

            const markerIcon = L.divIcon({
                className: 'custom-marker',
                html: `<div class="w-8 h-8 bg-blue-600 rounded-full border-4 border-white shadow-xl flex items-center justify-center animate-bounce-slow"><div class="w-2 h-2 bg-white rounded-full"></div></div>`,
                iconSize: [32, 32],
                iconAnchor: [16, 32]
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
        50% { transform: translateY(-8px); }
    }
    .animate-bounce-slow { animation: bounce-slow 2s infinite ease-in-out; }
</style>
<?= $this->endSection() ?>
