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
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-blue-950 p-7 rounded-[2.5rem] text-white shadow-2xl shadow-blue-950/20 relative overflow-hidden transition-all duration-500">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
        <div class="relative z-10 flex items-center gap-5">
            <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-md border border-white/10 shadow-inner">
                <i data-lucide="home" class="w-6 h-6 text-blue-400"></i>
            </div>
            <div>
                <h1 class="text-2xl md:text-3xl font-black uppercase tracking-tighter leading-none">Master Data Perumahan</h1>
                <p class="text-white/60 font-medium text-xs mt-2 tracking-wide">Registri Profil Rumah & Kondisi Hunian Kabupaten Sinjai</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-3 relative z-10">
            <a href="<?= base_url('rtlh/export-excel') ?>" class="bg-emerald-600 text-white px-4 py-2 rounded-xl text-[9px] font-bold uppercase tracking-widest shadow-xl shadow-emerald-600/20 hover:bg-emerald-700 transition-all active:scale-95 flex items-center gap-2">
                <i data-lucide="file-spreadsheet" class="w-4 h-4"></i> Export Excel
            </a>
            <?php if (has_permission('create_rtlh')): ?>
            <button onclick="UI.openModal('modal-import')" class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest shadow-xl shadow-indigo-600/20 hover:scale-105 active:scale-95 transition-all flex items-center gap-2 group">
                <i data-lucide="upload-cloud" class="w-4 h-4 transition-transform group-hover:-translate-y-0.5"></i> Import Data
            </button>
            <button onclick="openModalAdd()" class="bg-white text-blue-950 px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest shadow-xl hover:scale-105 active:scale-95 transition-all flex items-center gap-2 group">
                <i data-lucide="plus" class="w-4 h-4 transition-transform group-hover:rotate-90"></i> Tambah Data
            </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Map & Stats Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Interactive Map Card -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-[2.5rem] p-3 shadow-xl shadow-slate-200/50 dark:shadow-black/20 border border-slate-100 dark:border-slate-800 relative overflow-hidden">
            <div class="absolute top-6 left-6 z-[1000] flex flex-col gap-2">
                <span class="px-3 py-1 bg-blue-950/90 backdrop-blur-md rounded-lg text-[8px] font-bold uppercase tracking-widest shadow-sm border border-white/10 text-white">
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
                            ['semua', 'Semua', 'bg-blue-950 shadow-blue-950/20 hover:bg-black'],
                            ['Unknown', 'Unknown', 'bg-blue-800 shadow-blue-800/20 hover:bg-blue-700'],
                            ['Rtlh', 'RTLH', 'bg-rose-600 shadow-rose-600/20 hover:bg-rose-700'],
                            ['Target', 'Target', 'bg-indigo-600 shadow-indigo-600/20 hover:bg-indigo-700'],
                            ['Rlh', 'RLH', 'bg-emerald-600 shadow-emerald-600/20 hover:bg-emerald-700']
                        ];
                        foreach($filters as $f): 
                            $isActive = ($status == $f[0]);
                    ?>
                    <a href="<?= base_url('rtlh?status='.$f[0].'&keyword='.$keyword) ?>" 
                       class="px-4 py-2 rounded-lg text-[9px] font-black uppercase tracking-widest transition-all 
                       <?= $isActive ? $f[2].' text-white shadow-lg scale-105' : 'bg-white dark:bg-slate-900 border border-blue-950/10 dark:border-white/10 text-blue-950/40 dark:text-white/40 hover:border-blue-950/30 dark:hover:border-white/30 hover:text-blue-950 dark:hover:text-white' ?>">
                       <?= $f[1] ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="bg-blue-950 rounded-[2.5rem] p-8 text-white shadow-2xl shadow-blue-950/40 border border-white/5 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16 blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                <div class="flex justify-between items-start mb-6 relative z-10">
                    <div class="p-3 bg-white/10 rounded-2xl backdrop-blur-md border border-white/10"><i data-lucide="database" class="w-6 h-6 text-white"></i></div>
                    <span class="text-[8px] font-black uppercase tracking-widest px-2 py-1 bg-emerald-500 rounded-lg shadow-lg">Verified</span>
                </div>
                <h4 class="text-4xl font-black tracking-tighter mb-1 relative z-10"><?= number_format($total_data) ?></h4>
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-white/60 leading-relaxed relative z-10">Database Perumahan <br/> Kabupaten Sinjai</p>
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
                
                <div class="flex items-center gap-2 w-full md:w-auto bg-blue-950/10 dark:bg-slate-800 px-3 py-1.5 rounded-xl border border-blue-950/20 dark:border-white/10 focus-within:border-blue-950 transition-all">
                    <span class="text-[9px] font-black text-blue-950/40 dark:text-slate-500 uppercase tracking-widest whitespace-nowrap">Tampil</span>
                    <select name="per_page" onchange="this.form.submit()" class="bg-transparent border-none text-xs font-black text-blue-950 dark:text-white outline-none cursor-pointer appearance-none">
                        <option value="10" <?= $perPage == 10 ? 'selected' : '' ?>>10</option>
                        <option value="25" <?= $perPage == 25 ? 'selected' : '' ?>>25</option>
                        <option value="50" <?= $perPage == 50 ? 'selected' : '' ?>>50</option>
                        <option value="100" <?= $perPage == 100 ? 'selected' : '' ?>>100</option>
                    </select>
                </div>

                <div class="relative w-full md:w-64 group">
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
                            <div>
                                <p class="text-xs font-black text-slate-800 dark:text-slate-200 uppercase tracking-tight line-clamp-1"><?= $item['pemilik'] ?: 'Tidak Terdata' ?></p>
                                <p class="text-[9px] font-bold text-slate-400 tracking-widest mt-0.5"><?= $item['nik_pemilik'] ?></p>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <p class="text-[10px] font-black text-slate-700 dark:text-slate-300 uppercase"><?= $item['desa'] ?></p>
                            <p class="text-[9px] font-medium text-slate-400 line-clamp-1 mt-0.5"><?= $item['alamat_detail'] ?: '-' ?></p>
                        </td>
                        <td class="px-6 py-5">
                            <?php 
                                $statusColors = [
                                    'Unknown'         => 'bg-slate-100 text-slate-600 border-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-700',
                                    'Belum Menerima'  => 'bg-rose-50 text-rose-600 border-rose-100 dark:bg-rose-900/20 dark:text-rose-400 dark:border-rose-800',
                                    'Sudah Menerima'  => 'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-900/20 dark:text-emerald-400 dark:border-emerald-800',
                                    'Rtlh'            => 'bg-rose-50 text-rose-600 border-rose-100 dark:bg-rose-900/20 dark:text-rose-400 dark:border-rose-800',
                                    'Rlh'             => 'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-900/20 dark:text-emerald-400 dark:border-emerald-800',
                                    'Target'          => 'bg-indigo-50 text-indigo-600 border-indigo-100 dark:bg-indigo-900/20 dark:text-indigo-400 dark:border-indigo-800',
                                ];
                                $c = $statusColors[$item['status_bantuan']] ?? $statusColors['Unknown'];
                            ?>
                            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border <?= $c ?>">
                                <?= $item['status_bantuan'] ?: 'Belum Terdata' ?>
                            </span>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex justify-end gap-2 transition-opacity">
                                <a href="<?= base_url('rtlh/detail/'.$item['id_survei']) ?>" class="p-2 bg-blue-950 dark:bg-blue-600 text-white rounded-lg shadow-md hover:scale-110 transition-all active:scale-95" title="Detail Master Data"><i data-lucide="eye" class="w-3.5 h-3.5"></i></a>
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

<!-- SHARED RTLH MODAL COMPONENT -->
<?= view('rtlh/partials/_modal_edit') ?>

<!-- Map Scripts -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        let map = null, cluster;
        const mc = document.querySelector('main');
        
        const initMap = () => {
            if (map) return;
            const isDark = document.documentElement.classList.contains('dark');
            const cartoDB = L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; CartoDB'
            });
            const googleSat = L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains:['mt0','mt1','mt2','mt3'],
                attribution: '&copy; Google'
            });

            map = L.map('rtlhMap', { zoomControl: false, layers: [googleSat] }).setView([-5.1245, 120.2536], 11);
            L.control.zoom({ position: 'topright' }).addTo(map);

            let rot = 0;
            const LayerToggle = L.Control.extend({
                onAdd: function(map) {
                    const btn = L.DomUtil.create('button', 'rounded-lg shadow-xl border transition-all duration-300 active:scale-90 mt-2 flex items-center justify-center');
                    btn.style.width = '38px'; btn.style.height = '38px'; btn.style.cursor = 'pointer';
                    btn.type = 'button';
                    
                    // Initial State: Satellite Active
                    btn.style.backgroundColor = '#2563eb';
                    const standardSvgColor = isDark ? '#60a5fa' : '#2563eb';
                    
                    btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:block; transition: transform 0.8s cubic-bezier(0.65, 0, 0.35, 1);"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>`;
                    L.DomEvent.disableClickPropagation(btn);
                    L.DomEvent.on(btn, 'click', function(e) {
                        L.DomEvent.stopPropagation(e);
                        L.DomEvent.preventDefault(e);
                        rot += 360;
                        const svg = btn.querySelector('svg');
                        svg.style.transform = `rotate(${rot}deg)`;
                        setTimeout(() => {
                            if (map.hasLayer(googleSat)) { 
                                map.removeLayer(googleSat); 
                                map.addLayer(cartoDB); 
                                btn.style.backgroundColor = isDark ? '#0f172a' : '#ffffff'; 
                                svg.setAttribute('stroke', standardSvgColor); 
                            }
                            else { 
                                map.removeLayer(cartoDB); 
                                map.addLayer(googleSat); 
                                btn.style.backgroundColor = '#2563eb'; 
                                svg.setAttribute('stroke', '#ffffff'); 
                            }
                        }, 200);
                    });
                    return btn;
                }
            });
            new LayerToggle({ position: 'topright' }).addTo(map);

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

        // Open Modal Trigger
        window.openModalAdd = () => {
            if (window.rtlhModal) rtlhModal.openAdd();
            else console.error('rtlhModal not loaded');
        };

        initMap();
    });
</script>
<?= $this->endSection() ?>
