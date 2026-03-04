<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<!-- Leaflet Assets -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>

<div class="space-y-6 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-blue-950 dark:text-white uppercase tracking-tight">Data RTLH</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium text-sm">Monitoring Rumah Tidak Layak Huni Kabupaten Sinjai.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <div class="bg-blue-50 text-blue-600 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm border border-blue-100">
                <?= count($rumah_all ?? []) ?> Unit Terverifikasi
            </div>
            <?php if (has_permission('create_rtlh')): ?>
            <a href="<?= base_url('rtlh/create') ?>" class="bg-blue-950 hover:bg-black text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-xl transition-all flex items-center gap-2 group">
                <i data-lucide="plus" class="w-3.5 h-3.5 group-hover:rotate-90 transition-transform"></i> Tambah Data
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Map Section -->
    <div class="relative group">
        <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-[2.5rem] blur opacity-10 transition duration-1000"></div>
        <div class="relative bg-white dark:bg-slate-900 rounded-[2.5rem] overflow-hidden shadow-2xl border border-slate-100 dark:border-slate-800">
            <div id="map" class="w-full h-[60vh] z-10" style="min-height: 450px; background: #ececec;"></div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="p-6 border-b border-slate-50 dark:border-slate-800 flex flex-col lg:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-blue-950 rounded-2xl flex items-center justify-center text-white shadow-xl">
                    <i data-lucide="database" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-xs font-black text-blue-950 dark:text-white uppercase tracking-tight">Daftar Penerima RTLH</h3>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Informasi Terpadu Kepemilikan & Kondisi</p>
                </div>
            </div>

            <form action="<?= base_url('rtlh') ?>" method="get" class="flex flex-col md:flex-row items-center gap-2 w-full lg:w-auto">
                <select name="per_page" onchange="this.form.submit()" class="w-full md:w-24 bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-[9px] font-black px-3 py-2.5 focus:ring-2 focus:ring-blue-500 cursor-pointer">
                    <?php foreach([5, 10, 25, 50, 100] as $p): ?>
                        <option value="<?= $p ?>" <?= ($perPage ?? 10) == $p ? 'selected' : '' ?>><?= $p ?> Baris</option>
                    <?php endforeach; ?>
                </select>
                <div class="relative w-full md:w-64">
                    <input type="text" name="keyword" value="<?= $keyword ?? '' ?>" placeholder="Cari NIK / Nama..." class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-[9px] font-black px-3 py-2.5 pl-9 focus:ring-2 focus:ring-blue-500 transition-all uppercase">
                    <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse table-fixed">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/50 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                        <th class="px-4 py-4 w-64">Kepala Keluarga</th>
                        <th class="px-4 py-4 w-40">NIK</th>
                        <th class="px-4 py-4 w-40">Wilayah</th>
                        <th class="px-4 py-4 w-32 text-center">Kawasan</th>
                        <th class="px-4 py-4 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800 text-[10px]">
                    <?php if (!empty($rumah)): foreach($rumah as $item): ?>
                    <tr class="group hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-all duration-200">
                        <td class="px-4 py-3"><span class="font-black text-blue-950 dark:text-white uppercase truncate block"><?= $item['pemilik'] ?? '-' ?></span></td>
                        <td class="px-4 py-3"><span class="font-bold text-slate-500 dark:text-slate-400 tracking-wider"><?= $item['nik_pemilik'] ?></span></td>
                        <td class="px-4 py-3"><span class="font-black text-blue-600 uppercase"><?= $item['desa'] ?></span></td>
                        <td class="px-4 py-3 text-center"><span class="px-2 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-lg font-black uppercase text-[8px] truncate block"><?= $item['jenis_kawasan'] ?? '-' ?></span></td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="<?= base_url('rtlh/detail/'.$item['id_survei']) ?>" class="p-2 bg-blue-950 text-white rounded-lg shadow-md hover:bg-black transition-all active:scale-95"><i data-lucide="eye" class="w-3.5 h-3.5"></i></a>
                                <?php if (has_permission('delete_rtlh')): ?>
                                <button onclick="confirmDelete(<?= $item['id_survei'] ?>, '<?= addslashes($item['pemilik'] ?? '') ?>')" class="p-2 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-600 hover:text-white transition-all active:scale-95"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="5" class="px-8 py-12 text-center text-slate-400 font-bold uppercase text-[10px]">Data tidak ditemukan</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if (!empty($pager)): ?>
        <div class="p-6 bg-slate-50/50 dark:bg-slate-800/50 flex justify-center border-t border-slate-100 dark:border-slate-800">
            <?= $pager ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<form id="delete-form" action="" method="post" class="hidden"><?= csrf_field() ?></form>

<script>
    let map;
    function initMap() {
        if (typeof L === 'undefined') return;
        const isDark = document.documentElement.classList.contains('dark');
        const tile = L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { attribution: '&copy; Sibaruki' });
        map = L.map('map', { zoomControl: false, layers: [tile] }).setView([-5.1245, 120.2536], 13);
        const clusterGroup = L.markerClusterGroup({ showCoverageOnHover: false });
        const rtlhData = <?= json_encode($rumah_all ?? []) ?>;
        rtlhData.forEach(item => {
            if (item.lokasi_koordinat) {
                const coords = item.lokasi_koordinat.split(',').map(c => parseFloat(c.trim()));
                if (coords.length === 2) {
                    const marker = L.circleMarker(coords, { radius: 8, fillColor: "#1e1b4b", color: "#fff", weight: 2, fillOpacity: 0.8 });
                    marker.bindPopup(`<div class="p-2"><h5 class="text-xs font-black uppercase">${item.pemilik || '-'}</h5><p class="text-[10px]">${item.desa}</p></div>`);
                    clusterGroup.addLayer(marker);
                }
            }
        });
        map.addLayer(clusterGroup);
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function confirmDelete(id, name) {
        customConfirm('Hapus RTLH?', `Hapus data milik ${name}?`, 'danger').then(conf => {
            if (conf) { const f = document.getElementById('delete-form'); f.action = `<?= base_url('rtlh/delete') ?>/${id}`; f.submit(); }
        });
    }
    window.addEventListener('load', initMap);
</script>
<?= $this->endSection() ?>
