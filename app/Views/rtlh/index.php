<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<!-- External Assets -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/MarkerCluster.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/MarkerCluster.Default.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/leaflet.markercluster.js"></script>
<script src="https://cdn.jsdelivr.net/npm/wellknown@0.5.0/wellknown.js"></script>

<div class="space-y-6">
    <!-- Header Page -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-blue-950 dark:text-white uppercase tracking-tighter">Master Data Perumahan</h1>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mt-1 flex items-center gap-2">
                <span class="w-8 h-[2px] bg-blue-600"></span> Registri Profil Rumah & Kondisi Hunian
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="<?= base_url('rtlh/export-excel') ?>" class="bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 px-4 py-2 rounded-xl text-[9px] font-bold uppercase tracking-widest border border-emerald-100 dark:border-emerald-800/50 hover:bg-emerald-600 hover:text-white transition-all active:scale-95 flex items-center gap-2">
                <i data-lucide="file-spreadsheet" class="w-3.5 h-3.5"></i> Export Excel
            </a>
            <?php if (has_permission('create_rtlh')): ?>
            <a href="<?= base_url('rtlh/create') ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-[9px] font-bold uppercase tracking-widest shadow-lg shadow-blue-600/20 transition-all active:scale-95 flex items-center gap-2 group">
                <i data-lucide="plus" class="w-3.5 h-3.5 transition-transform group-hover:rotate-90"></i> Tambah Data Rumah
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Map & Stats Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Interactive Map Card -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-[2.5rem] p-3 shadow-xl shadow-slate-200/50 dark:shadow-black/20 border border-slate-100 dark:border-slate-800 relative overflow-hidden">
            <div class="absolute top-6 left-6 z-[1000] flex flex-col gap-2">
                <span class="px-3 py-1 bg-white/90 dark:bg-slate-800/90 backdrop-blur-md rounded-lg text-[8px] font-bold uppercase tracking-widest shadow-sm border border-slate-200 dark:border-slate-700 text-slate-500">
                    Geospasial Perumahan
                </span>
            </div>
            <div id="rtlhMap" class="h-[400px] w-full rounded-[2rem] z-0 bg-slate-50 dark:bg-slate-950"></div>
        </div>

        <!-- Quick Filters & Stats -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-8 shadow-xl shadow-slate-200/50 dark:shadow-black/20 border border-slate-100 dark:border-slate-800">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Filter Status Hunian</h3>
                <div class="flex flex-wrap gap-2">
                    <?php 
                        $filters = [
                            ['semua', 'Semua', 'slate'],
                            ['Unknown', 'Unknown', 'slate'],
                            ['Rtlh', 'RTLH', 'rose'],
                            ['Target', 'Target', 'indigo'],
                            ['Rlh', 'RLH', 'emerald']
                        ];
                        foreach($filters as $f): 
                    ?>
                    <a href="<?= base_url('rtlh?status='.$f[0].'&keyword='.$keyword) ?>" 
                       class="px-4 py-2 rounded-lg text-[9px] font-bold uppercase tracking-widest transition-all 
                       <?= ($status == $f[0]) ? 'bg-'.$f[2].'-600 text-white shadow-lg' : 'bg-slate-50 dark:bg-slate-800 text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700' ?>">
                       <?= $f[1] ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-[2.5rem] p-8 text-white shadow-xl shadow-blue-600/20">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-white/20 rounded-2xl backdrop-blur-md"><i data-lucide="database" class="w-6 h-6"></i></div>
                    <span class="text-[8px] font-black uppercase tracking-widest px-2 py-1 bg-emerald-500 rounded-lg">Validated</span>
                </div>
                <h4 class="text-3xl font-black tracking-tighter mb-1"><?= number_format($total_data) ?></h4>
                <p class="text-[10px] font-bold uppercase tracking-widest opacity-80 leading-relaxed">Total Rumah Terdata <br/> di Kabupaten Sinjai</p>
            </div>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-xl shadow-slate-200/50 dark:shadow-black/20 border border-slate-100 dark:border-slate-800 overflow-hidden min-h-[500px]">
        <div class="p-8 border-b border-slate-50 dark:border-slate-800 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/20 rounded-xl flex items-center justify-center text-blue-600">
                    <i data-lucide="home" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-blue-950 dark:text-white uppercase tracking-tight">Daftar Registri Perumahan</h3>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Manajemen Profil & Verifikasi Kondisi</p>
                </div>
            </div>
            
            <form action="<?= base_url('rtlh') ?>" method="get" class="flex flex-col md:flex-row items-center gap-2 w-full lg:w-auto" id="filter-form">
                <input type="hidden" name="status" value="<?= $status ?>">
                <div class="relative w-full md:w-72 group">
                    <input type="text" name="keyword" value="<?= $keyword ?>" placeholder="Cari Nama, NIK, atau Desa..." 
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-blue-600 transition-all outline-none">
                    <i data-lucide="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-blue-600 transition-colors"></i>
                </div>
                <button type="submit" class="w-full md:w-auto px-6 py-2.5 bg-blue-950 dark:bg-blue-600 text-white rounded-xl text-[10px] font-bold uppercase tracking-widest hover:shadow-lg transition-all active:scale-95">Cari</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/50 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                        <th class="px-8 py-4 w-12"><input type="checkbox" id="check-all" class="w-4.5 h-4.5 rounded-lg border-2 border-slate-200 text-blue-600 focus:ring-blue-600/20 cursor-pointer"></th>
                        <th class="px-6 py-4">Informasi Penghuni</th>
                        <th class="px-6 py-4">Lokasi & Desa</th>
                        <th class="px-6 py-4">Status Verifikasi</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-slate-600 dark:text-slate-400 divide-y divide-slate-50 dark:divide-slate-800">
                    <?php if(!empty($rumah)): foreach($rumah as $item): ?>
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors group">
                        <td class="px-8 py-5">
                            <input type="checkbox" name="rtlh_ids[]" value="<?= $item['id_survei'] ?>" class="row-checkbox w-4.5 h-4.5 rounded-lg border-2 border-slate-200 text-blue-600 focus:ring-blue-600/20 cursor-pointer transition-all">
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 overflow-hidden shrink-0 flex items-center justify-center border border-slate-200 dark:border-slate-700">
                                    <?php if(!empty($item['foto_depan'])): ?>
                                        <img src="<?= base_url('uploads/rtlh/'.$item['foto_depan']) ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <i data-lucide="home" class="w-4 h-4 text-slate-400"></i>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <p class="text-xs font-black text-slate-800 dark:text-slate-200 uppercase tracking-tight line-clamp-1"><?= $item['pemilik'] ?: 'Tidak Terdata' ?></p>
                                    <p class="text-[9px] font-bold text-slate-400 tracking-widest mt-0.5"><?= $item['nik_pemilik'] ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <p class="text-[10px] font-black text-slate-700 dark:text-slate-300 uppercase"><?= $item['desa'] ?></p>
                            <p class="text-[9px] font-medium text-slate-400 line-clamp-1 mt-0.5"><?= $item['alamat_detail'] ?: '-' ?></p>
                        </td>
                        <td class="px-6 py-5">
                            <?php 
                                $statusColors = [
                                    'Unknown' => 'bg-slate-100 text-slate-600 border-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-700',
                                    'Rtlh'    => 'bg-rose-50 text-rose-600 border-rose-100 dark:bg-rose-900/20 dark:text-rose-400 dark:border-rose-800',
                                    'Target'  => 'bg-indigo-50 text-indigo-600 border-indigo-100 dark:bg-indigo-900/20 dark:text-indigo-400 dark:border-indigo-800',
                                    'Rlh'     => 'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-900/20 dark:text-emerald-400 dark:border-emerald-800',
                                ];
                                $c = $statusColors[$item['status_bantuan']] ?? $statusColors['Unknown'];
                            ?>
                            <span class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest border <?= $c ?>">
                                <?= $item['status_bantuan'] ?>
                            </span>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="<?= base_url('rtlh/detail/'.$item['id_survei']) ?>" class="p-2 bg-blue-950 dark:bg-blue-600 text-white rounded-lg shadow-md hover:scale-110 transition-all active:scale-95" title="Detail Master Data"><i data-lucide="eye" class="w-3.5 h-3.5"></i></a>
                                <a href="<?= base_url('rtlh/edit/'.$item['id_survei']) ?>" class="p-2 bg-amber-500 text-white rounded-lg shadow-md hover:scale-110 transition-all active:scale-95" title="Edit Data"><i data-lucide="edit-3" class="w-3.5 h-3.5"></i></a>
                                <?php if (has_permission('delete_rtlh')): ?>
                                <button onclick="deleteConfirm(<?= $item['id_survei'] ?>)" class="p-2 bg-rose-500 text-white rounded-lg shadow-md hover:scale-110 transition-all active:scale-95" title="Hapus"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4 border-4 border-white dark:border-slate-900 shadow-xl">
                                    <i data-lucide="home" class="w-8 h-8 text-slate-300"></i>
                                </div>
                                <h4 class="text-sm font-black text-slate-800 dark:text-slate-200 uppercase tracking-tighter">Data Tidak Ditemukan</h4>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Gunakan kata kunci lain atau filter status yang berbeda</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Multi-select Action Bar -->
        <div id="bulk-action-bar" class="hidden fixed bottom-8 left-1/2 -translate-x-1/2 z-[5000] bg-blue-950 text-white px-8 py-4 rounded-3xl shadow-2xl flex items-center gap-6 border border-white/10 backdrop-blur-xl animate-bounce-subtle">
            <div class="flex items-center gap-3 pr-6 border-r border-white/10">
                <span id="selected-count" class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-[10px] font-black">0</span>
                <span class="text-[9px] font-bold uppercase tracking-widest">Data Terpilih</span>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="bulkDelete()" class="px-6 py-2.5 bg-rose-500 hover:bg-rose-600 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg transition-all active:scale-95 flex items-center gap-2">
                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Hapus Massal
                </button>
                <button onclick="clearSelection()" class="px-6 py-2.5 bg-white/10 hover:bg-white/20 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">Batal</button>
            </div>
        </div>

        <div class="p-8 bg-slate-50/50 dark:bg-slate-800/50 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest italic">Menampilkan <?= count($rumah) ?> dari <?= $total_data ?> total rumah terdaftar</p>
            <?= $pager->links('default', 'tailwind_pager') ?>
        </div>
    </div>
</div>

<!-- Map Scripts -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        let map = null, cluster;
        const mc = document.querySelector('main');
        
        const initMap = () => {
            if (map) return;
            const isDark = document.documentElement.classList.contains('dark');
            map = L.map('rtlhMap', { zoomControl: false }).setView([-5.1245, 120.2536], 11);
            
            L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; CartoDB'
            }).addTo(map);

            L.control.zoom({ position: 'topright' }).addTo(map);

            cluster = L.markerClusterGroup({
                showCoverageOnHover: false,
                maxClusterRadius: 50
            }).addTo(map);

            const statusColors = { Target: '#6366f1', Rtlh: '#ef4444', Rlh: '#10b981', Unknown: '#94a3b8' };
            const rawData = <?= json_encode($rumah_all ?? []) ?>;

            rawData.forEach(item => {
                if (!item.wkt) return;
                try {
                    const geo = wellknown.parse(item.wkt);
                    if (geo && geo.type === 'Point') {
                        const color = statusColors[item.status_bantuan] || '#94a3b8';
                        const icon = L.divIcon({
                            className: 'custom-marker',
                            html: `<div class="w-5 h-5 rounded-full border-4 border-white shadow-lg flex items-center justify-center" style="background-color: ${color};">
                                    <div class="w-1 h-1 bg-white rounded-full"></div>
                                   </div>`,
                            iconSize: [20, 20],
                            iconAnchor: [10, 10]
                        });
                        
                        const popup = `<div class="bg-blue-950 text-white p-3 rounded-t-xl"><p class="text-[7px] font-bold uppercase tracking-[0.2em] opacity-60 mb-1">${item.status_bantuan}</p><h5 class="text-[11px] font-bold uppercase leading-tight">${item.pemilik}</h5></div>
                                       <div class="p-3 bg-white dark:bg-slate-900 space-y-2 rounded-b-xl"><p class="text-[9px] font-bold text-slate-700 dark:text-slate-300 italic">📍 ${item.desa}</p><a href="<?= base_url('rtlh/detail/') ?>/${item.id_survei}" class="block w-full py-2 bg-blue-600 hover:bg-blue-700 text-white text-center text-[9px] font-black uppercase tracking-widest rounded-lg transition-all shadow-md shadow-blue-600/20">Profil Lengkap</a></div>`;
                        
                        L.marker([geo.coordinates[1], geo.coordinates[0]], { icon: icon }).bindPopup(popup).addTo(cluster);
                    }
                } catch(e) {}
            });

            if (cluster.getLayers().length > 0) map.fitBounds(cluster.getBounds(), { padding: [30, 30] });
            setTimeout(() => map.invalidateSize(), 400);
        };

        // Scroll memory
        if (mc) {
            const sp = localStorage.getItem('rtlh_scroll');
            if (sp) { mc.scrollTop = sp; localStorage.removeItem('rtlh_scroll'); }
            document.querySelectorAll('a, button[type="submit"]').forEach(el => el.addEventListener('click', () => localStorage.setItem('rtlh_scroll', mc.scrollTop)));
        }

        // Selection Logic
        const checkAll = document.getElementById('check-all');
        const rows = document.querySelectorAll('.row-checkbox');
        const bar = document.getElementById('bulk-action-bar');
        const countDisplay = document.getElementById('selected-count');

        const updateBar = () => {
            const checked = document.querySelectorAll('.row-checkbox:checked').length;
            countDisplay.innerText = checked;
            bar.classList.toggle('hidden', checked === 0);
            if (checked > 0) lucide.createIcons();
        };

        checkAll?.addEventListener('change', () => { rows.forEach(r => r.checked = checkAll.checked); updateBar(); });
        rows.forEach(r => r.addEventListener('change', updateBar));

        window.clearSelection = () => { rows.forEach(r => r.checked = false); if(checkAll) checkAll.checked = false; updateBar(); };

        window.deleteConfirm = async (id) => {
            const ok = await window.customConfirm('Hapus Data?', 'Data akan dipindahkan ke Recycle Bin.', 'danger');
            if (ok) {
                const f = document.createElement('form');
                f.method = 'POST'; f.action = `<?= base_url('rtlh/delete') ?>/${id}`;
                document.body.appendChild(f); f.submit();
            }
        };

        window.bulkDelete = async () => {
            const ids = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(r => r.value);
            const ok = await window.customConfirm('Hapus Massal?', `Hapus ${ids.length} data ke Recycle Bin?`, 'danger');
            if (ok) {
                try {
                    const res = await fetch('<?= base_url('rtlh/bulk-delete') ?>', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
                        body: `ids[]=${ids.join('&ids[]=')}`
                    });
                    const data = await res.json();
                    if (data.status === 'success') { window.showToast(data.message); setTimeout(() => location.reload(), 1000); }
                    else window.showToast(data.message, 'error');
                } catch(e) { window.showToast('Gagal menghapus data', 'error'); }
            }
        };

        initMap();
    });
</script>
<?= $this->endSection() ?>
