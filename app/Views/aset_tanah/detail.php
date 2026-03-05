<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<!-- Leaflet Assets -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="space-y-6 pb-12">
    <!-- Header Action -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="<?= base_url('aset-tanah') ?>" class="w-10 h-10 bg-white dark:bg-slate-900 rounded-xl flex items-center justify-center text-slate-600 dark:text-slate-400 shadow-sm border border-slate-100 dark:border-slate-800 hover:bg-blue-600 hover:text-white transition-all">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-2xl font-black text-blue-950 dark:text-white uppercase tracking-tight">Detail Aset Tanah</h1>
                <p class="text-xs text-slate-500 font-bold uppercase tracking-widest"><?= $aset['no_sertifikat'] ?></p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="<?= base_url('aset-tanah/edit/'.$aset['id']) ?>" class="px-5 py-2.5 bg-amber-500 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-amber-200 dark:shadow-none hover:bg-amber-600 transition-all flex items-center gap-2">
                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> Edit Data
            </a>
            <button onclick="confirmDelete(<?= $aset['id'] ?>)" class="px-5 py-2.5 bg-rose-500 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-rose-200 dark:shadow-none hover:bg-rose-600 transition-all flex items-center gap-2">
                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Hapus
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informasi Utama -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-8 shadow-sm border border-slate-100 dark:border-slate-800 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-8 opacity-5">
                    <i data-lucide="file-text" class="w-32 h-32 text-blue-900 dark:text-white"></i>
                </div>
                
                <h3 class="text-sm font-black text-blue-950 dark:text-white uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                    <span class="w-8 h-1 bg-blue-600 rounded-full"></span> Informasi Sertifikat
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12">
                    <div class="space-y-1">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Nama Pemilik / Instansi</p>
                        <p class="text-sm font-bold text-slate-800 dark:text-slate-100"><?= $aset['nama_pemilik'] ?></p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Nomor Sertifikat</p>
                        <p class="text-sm font-bold text-blue-600"><?= $aset['no_sertifikat'] ?></p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Luas Tanah</p>
                        <p class="text-sm font-bold text-slate-800 dark:text-slate-100"><?= number_format($aset['luas_m2'], 2, ',', '.') ?> m²</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Tanggal Terbit</p>
                        <p class="text-sm font-bold text-slate-800 dark:text-slate-100">
                            <?php 
                                if (!empty($aset['tgl_terbit']) && $aset['tgl_terbit'] !== '0000-00-00') {
                                    $months = [
                                        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                                        '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                                        '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                                    ];
                                    $d = explode('-', $aset['tgl_terbit']);
                                    if (count($d) === 3) {
                                        echo $d[2] . ' ' . ($months[$d[1]] ?? $d[1]) . ' ' . $d[0];
                                    } else {
                                        echo $aset['tgl_terbit'];
                                    }
                                } else {
                                    echo '<span class="text-slate-300">Data Tidak Tersedia</span>';
                                }
                            ?>
                        </p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Nomor Hak</p>
                        <p class="text-sm font-bold text-slate-800 dark:text-slate-100"><?= $aset['nomor_hak'] ?: '-' ?></p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Status Tanah</p>
                        <span class="inline-block px-3 py-1 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 text-[10px] font-black uppercase rounded-lg">
                            <?= $aset['status_tanah'] ?: 'Aktif' ?>
                        </span>
                    </div>
                </div>

                <div class="mt-12 pt-8 border-t border-slate-50 dark:border-slate-800 space-y-4">
                    <div class="space-y-1">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Lokasi / Alamat</p>
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-300 leading-relaxed"><?= $aset['lokasi'] ?></p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Peruntukan</p>
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-300 leading-relaxed"><?= $aset['peruntukan'] ?: '-' ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-8 shadow-sm border border-slate-100 dark:border-slate-800">
                <h3 class="text-sm font-black text-blue-950 dark:text-white uppercase tracking-[0.2em] mb-6 flex items-center gap-3">
                    <span class="w-8 h-1 bg-emerald-500 rounded-full"></span> Nilai & Keterangan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-slate-50 dark:bg-slate-800/50 p-6 rounded-3xl">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Estimasi Nilai Aset</p>
                        <p class="text-2xl font-black text-emerald-600">Rp <?= number_format($aset['nilai_aset'], 0, ',', '.') ?></p>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/50 p-6 rounded-3xl">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Catatan Tambahan</p>
                        <p class="text-xs font-medium text-slate-600 dark:text-slate-400 italic"><?= $aset['keterangan'] ?: 'Tidak ada catatan tambahan.' ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Peta Lokasi -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] overflow-hidden shadow-sm border border-slate-100 dark:border-slate-800 h-[400px] relative">
                <div id="map-detail" class="w-full h-full z-10"></div>
                <div class="absolute top-4 left-4 z-[1000] bg-white/90 dark:bg-slate-900/90 backdrop-blur-md px-3 py-1.5 rounded-xl border border-white/20 shadow-xl text-[9px] font-black uppercase tracking-widest text-blue-600">
                    Titik Lokasi Aset
                </div>
            </div>

            <div class="bg-blue-950 rounded-[2.5rem] p-8 text-white shadow-2xl relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-700">
                    <i data-lucide="info" class="w-32 h-32"></i>
                </div>
                <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-blue-400 mb-4">Informasi Sistem</h4>
                <div class="space-y-4 relative z-10">
                    <div class="flex justify-between items-center text-[10px]">
                        <span class="font-bold text-blue-300/60 uppercase">Dibuat</span>
                        <span class="font-black"><?= date('d/m/Y H:i', strtotime($aset['created_at'])) ?></span>
                    </div>
                    <div class="flex justify-between items-center text-[10px]">
                        <span class="font-bold text-blue-300/60 uppercase">Pembaruan</span>
                        <span class="font-black"><?= date('d/m/Y H:i', strtotime($aset['updated_at'])) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="delete-form" action="" method="post" class="hidden"></form>

<script>
    function initDetailMap() {
        const coordsStr = "<?= $aset['koordinat'] ?>";
        if (!coordsStr) return;

        const coords = coordsStr.split(',').map(c => parseFloat(c.trim()));
        if (coords.length !== 2 || isNaN(coords[0]) || isNaN(coords[1])) return;

        const isDark = document.documentElement.classList.contains('dark');
        const tileUrl = isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png';

        const map = L.map('map-detail', { 
            zoomControl: false,
            dragging: false,
            scrollWheelZoom: false,
            doubleClickZoom: false
        }).setView(coords, 17);

        L.tileLayer(tileUrl).addTo(map);

        L.circleMarker(coords, {
            radius: 12,
            fillColor: "#2563eb",
            color: "#fff",
            weight: 4,
            opacity: 1,
            fillOpacity: 0.8
        }).addTo(map);
    }

    function confirmDelete(id) {
        customConfirm({
            title: 'Hapus Aset?',
            message: 'Tindakan ini tidak dapat dibatalkan. Apakah Anda yakin ingin menghapus data aset ini?',
            confirmText: 'Ya, Hapus',
            type: 'danger',
            onConfirm: () => {
                const form = document.getElementById('delete-form');
                form.action = `<?= base_url('aset-tanah/delete') ?>/${id}`;
                form.submit();
            }
        });
    }

    window.addEventListener('load', initDetailMap);
</script>
<?= $this->endSection() ?>
