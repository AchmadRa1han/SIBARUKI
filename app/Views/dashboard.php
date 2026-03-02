<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<!-- External Libraries -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="space-y-8 pb-12">
    
    <!-- 1. HERO SECTION: GREETING & QUICK ACTIONS -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 bg-blue-900 dark:bg-blue-800 rounded-[3rem] p-8 lg:p-12 text-white relative overflow-hidden shadow-2xl shadow-blue-900/20 transition-colors duration-300">
            <div class="relative z-10">
                <?php
                    $hour = date('H');
                    $greet = ($hour < 12) ? 'Selamat Pagi' : (($hour < 17) ? 'Selamat Siang' : 'Selamat Malam');
                ?>
                <span class="text-[10px] font-black uppercase tracking-[0.4em] text-blue-300 opacity-80 mb-4 block"><?= $greet ?></span>
                <h1 class="text-3xl lg:text-5xl font-black tracking-tight mb-4 capitalize">Halo, <?= session()->get('username') ?>!</h1>
                <p class="text-blue-100/70 text-sm lg:text-base font-medium max-w-md leading-relaxed">Selamat datang di Command Center SIBARUKI. Pantau dan kelola data perumahan Kabupaten Sinjai dengan presisi.</p>
                
                <div class="mt-8 flex flex-wrap gap-4">
                    <?php if(has_permission('create_rtlh')): ?>
                    <a href="<?= base_url('rtlh/create') ?>" class="bg-white text-blue-900 px-6 py-3 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl hover:bg-blue-50 transition-all active:scale-95 flex items-center gap-2">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i> Input RTLH
                    </a>
                    <?php endif; ?>
                    <?php if(has_permission('export_data')): ?>
                    <a href="<?= base_url('rtlh') ?>" class="bg-blue-950/40 backdrop-blur-md border border-white/10 text-white px-6 py-3 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-blue-950/60 transition-all active:scale-95 flex items-center gap-2">
                        <i data-lucide="file-text" class="w-4 h-4"></i> Export Laporan
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <!-- Background Decorative Icon -->
            <i data-lucide="layout-dashboard" class="w-64 h-64 absolute -right-16 -bottom-16 text-white opacity-[0.05] rotate-12"></i>
        </div>

        <!-- HEALTH CHECK WIDGET -->
        <div class="bg-white dark:bg-slate-900 rounded-[3rem] p-8 border border-slate-100 dark:border-slate-800 shadow-sm flex flex-col justify-between transition-colors duration-300">
            <div>
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Kesehatan Data (Health Check)</h3>
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-rose-50 dark:bg-rose-950/30 text-rose-600 flex items-center justify-center"><i data-lucide="map-pin-off" class="w-5 h-5"></i></div>
                            <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Tanpa Koordinat</span>
                        </div>
                        <span class="text-lg font-black text-rose-600"><?= $health['coords'] ?></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-amber-50 dark:bg-amber-950/30 text-amber-600 flex items-center justify-center"><i data-lucide="file-warning" class="w-5 h-5"></i></div>
                            <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Nomor KK Kosong</span>
                        </div>
                        <span class="text-lg font-black text-amber-600"><?= $health['kk'] ?></span>
                    </div>
                </div>
            </div>
            <div class="mt-8 pt-6 border-t dark:border-slate-800">
                <p class="text-[9px] text-slate-400 italic">Segera lengkapi data untuk akurasi pemetaan.</p>
            </div>
        </div>
    </div>

    <!-- 2. ANALYTICS GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- BAR CHART: TOP VILLAGES -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 p-8 rounded-[3rem] border border-slate-100 dark:border-slate-800 shadow-sm transition-colors duration-300">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-[10px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-widest">Desa Dengan RTLH Terbanyak</h3>
                <i data-lucide="bar-chart-2" class="w-4 h-4 text-slate-300"></i>
            </div>
            <div id="villageChart" class="min-h-[300px]"></div>
        </div>

        <!-- DONUT CHART: CONDITION -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[3rem] border border-slate-100 dark:border-slate-800 shadow-sm transition-colors duration-300">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-[10px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-widest">Kondisi Bangunan</h3>
                <i data-lucide="pie-chart" class="w-4 h-4 text-slate-300"></i>
            </div>
            <div id="conditionChart"></div>
        </div>
    </div>

    <!-- 3. MAP PREVIEW & CRITICAL AREAS -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- MINI MAP -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 p-4 rounded-[3rem] border border-slate-100 dark:border-slate-800 shadow-sm transition-colors duration-300">
            <div class="p-4 flex items-center justify-between">
                <h3 class="text-[10px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-widest">Peta Sebaran Titik RTLH</h3>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                    <span class="text-[9px] font-black text-slate-400 uppercase">Live Feed</span>
                </div>
            </div>
            <div id="miniMap" class="h-[400px] w-full rounded-[2.5rem] overflow-hidden z-0 border dark:border-slate-800 transition-colors duration-300"></div>
        </div>

        <!-- TOP CRITICAL AREAS -->
        <div class="bg-white dark:bg-slate-900 rounded-[3rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-300">
            <div class="p-8 border-b dark:border-slate-800">
                <h3 class="text-[10px] font-black text-rose-600 uppercase tracking-widest">Area Kumuh Prioritas</h3>
            </div>
            <div class="p-4 space-y-2">
                <?php foreach($topKumuh as $k): ?>
                <div class="p-4 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-800/50 rounded-2xl transition-all group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-950/30 text-rose-600 flex items-center justify-center font-black text-xs"><?= substr($k['Kelurahan'], 0, 1) ?></div>
                        <div>
                            <p class="text-xs font-black text-slate-800 dark:text-slate-200 uppercase"><?= $k['Kelurahan'] ?></p>
                            <p class="text-[9px] font-bold text-slate-400"><?= $k['Kawasan'] ?></p>
                        </div>
                    </div>
                    <span class="text-sm font-black text-rose-600"><?= number_format($k['skor_kumuh'], 1) ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            <a href="<?= base_url('wilayah-kumuh') ?>" class="block text-center p-6 text-[10px] font-black text-slate-400 hover:text-blue-900 dark:hover:text-blue-400 uppercase tracking-widest transition-colors border-t dark:border-slate-800">Lihat Semua Kawasan</a>
        </div>
    </div>

    <!-- 4. LOGS & SYSTEM STATUS -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- LOG AKTIVITAS -->
        <div class="bg-white dark:bg-slate-900 rounded-[3rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-300">
            <div class="p-8 border-b dark:border-slate-800 flex justify-between items-center">
                <h3 class="text-[10px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-widest">Aktivitas Terbaru</h3>
                <i data-lucide="activity" class="w-4 h-4 text-slate-300"></i>
            </div>
            <div class="divide-y divide-slate-50 dark:divide-slate-800">
                <?php foreach($logs as $log): ?>
                <div class="p-6 flex items-center gap-4 hover:bg-blue-50/30 dark:hover:bg-blue-900/10 transition-all">
                    <div class="w-10 h-10 bg-slate-50 dark:bg-slate-800 rounded-xl flex items-center justify-center shrink-0">
                        <?php 
                            $icon = 'edit-3'; $color = 'text-amber-500';
                            if($log['action'] == 'Login') { $icon = 'log-in'; $color = 'text-indigo-500'; }
                            if($log['action'] == 'Tambah') { $icon = 'plus-circle'; $color = 'text-blue-500'; }
                            if($log['action'] == 'Hapus') { $icon = 'trash-2'; $color = 'text-rose-500'; }
                        ?>
                        <i data-lucide="<?= $icon ?>" class="w-4 h-4 <?= $color ?>"></i>
                    </div>
                    <div class="flex-grow">
                        <p class="text-xs font-black text-slate-800 dark:text-slate-200 uppercase tracking-tight"><?= $log['action'] ?> Data <span class="text-blue-900 dark:text-blue-400"><?= $log['table_name'] ?></span></p>
                        <p class="text-[10px] text-slate-400 font-medium italic mt-0.5"><?= $log['description'] ?></p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-black text-slate-800 dark:text-slate-300"><?= date('H:i', strtotime($log['created_at'])) ?></p>
                        <p class="text-[8px] font-bold text-slate-400 uppercase"><?= date('d M', strtotime($log['created_at'])) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();

    // --- 1. BAR CHART: TOP VILLAGES ---
    const villageOptions = {
        series: [{
            name: 'Total RTLH',
            data: <?= json_encode(array_column($chartDesa, 'total')) ?>
        }],
        chart: { type: 'bar', height: 350, toolbar: { show: false }, fontFamily: 'Plus Jakarta Sans, sans-serif' },
        colors: ['#1e3a8a'],
        plotOptions: { bar: { borderRadius: 12, columnWidth: '40%', distributed: false } },
        dataLabels: { enabled: false },
        xaxis: {
            categories: <?= json_encode(array_column($chartDesa, 'desa')) ?>,
            labels: { style: { colors: '#94a3b8', fontWeight: 700, fontSize: '10px' } }
        },
        yaxis: { labels: { style: { colors: '#94a3b8' } } },
        grid: { borderColor: '#f1f5f9', strokeDashArray: 4 }
    };
    new ApexCharts(document.querySelector("#villageChart"), villageOptions).render();

    // --- 2. DONUT CHART: CONDITION ---
    const conditionOptions = {
        series: [<?= (int)($statusLayak['layak'] ?? 0) ?>, <?= (int)($statusLayak['menuju_layak'] ?? 0) ?>, <?= (int)($statusLayak['tidak_layak'] ?? 0) ?>],
        chart: { type: 'donut', height: 350, fontFamily: 'Plus Jakarta Sans, sans-serif' },
        labels: ['LAYAK', 'MENUJU LAYAK', 'TIDAK LAYAK'],
        colors: ['#10b981', '#f59e0b', '#ef4444'],
        plotOptions: { pie: { donut: { size: '75%', labels: { show: true, total: { show: true, label: 'TOTAL DATA', color: '#94a3b8' } } } } },
        dataLabels: { enabled: false },
        legend: { position: 'bottom', labels: { colors: '#94a3b8' }, markers: { radius: 12 } }
    };
    new ApexCharts(document.querySelector("#conditionChart"), conditionOptions).render();

    // --- 3. MINI MAP PREVIEW ---
    document.addEventListener('DOMContentLoaded', function() {
        const map = L.map('miniMap', { attributionControl: false, zoomControl: false }).setView([-5.2, 120.2], 11);
        
        // Add Dark/Light Base Tile based on theme
        const isDark = document.documentElement.classList.contains('dark');
        const tileUrl = isDark 
            ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' 
            : 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
        L.tileLayer(tileUrl).addTo(map);

        // Add Circle Markers for RTLH
        const markers = <?= json_encode($mapMarkers) ?>;
        markers.forEach(m => {
            if (m.lokasi_koordinat) {
                const cleanCoords = m.lokasi_koordinat.replace(/[()a-zA-Z]/g, '').trim().split(' ');
                if (cleanCoords.length >= 2) {
                    const lat = parseFloat(cleanCoords[1]);
                    const lng = parseFloat(cleanCoords[0]);
                    
                    if (!isNaN(lat) && !isNaN(lng)) {
                        L.circleMarker([lat, lng], {
                            radius: 5,
                            fillColor: '#1e3a8a',
                            color: '#ffffff',
                            weight: 2,
                            opacity: 1,
                            fillOpacity: 0.9
                        }).addTo(map).bindPopup(`
                            <div class="p-2">
                                <p class="text-[10px] font-black text-blue-900 uppercase mb-1">${m.desa}</p>
                                <p class="text-[9px] font-bold text-slate-500 uppercase tracking-tighter">NIK: ${m.nik_pemilik}</p>
                            </div>
                        `);
                    }
                }
            }
        });
    });
</script>

<style>
    .apexcharts-canvas { margin: 0 auto; }
    .dark .apexcharts-text { fill: #94a3b8 !important; }
    .dark .apexcharts-legend-text { color: #94a3b8 !important; }
    .dark .apexcharts-gridline { stroke: #1e293b !important; }
</style>
<?= $this->endSection() ?>
