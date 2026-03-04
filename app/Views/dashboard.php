<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<!-- External Libraries -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<?php 
    $isAdmin = (session()->get('role_name') === 'admin');
?>

<div class="space-y-8 pb-12">
    
    <!-- 1. HERO SECTION & HEALTH CHECK -->
    <div class="grid grid-cols-1 <?= $isAdmin ? 'lg:grid-cols-3' : '' ?> gap-8">
        <div class="<?= $isAdmin ? 'lg:col-span-2' : '' ?> bg-blue-900 dark:bg-blue-800 rounded-[3rem] p-8 lg:p-12 text-white relative overflow-hidden shadow-2xl shadow-blue-900/20 transition-colors duration-300">
            <div class="relative z-10">
                <?php
                    $hour = date('H');
                    $greet = ($hour < 12) ? 'Selamat Pagi' : (($hour < 17) ? 'Selamat Siang' : 'Selamat Malam');
                ?>
                <span class="text-[10px] font-black uppercase tracking-[0.4em] text-blue-300 opacity-80 mb-4 block"><?= $greet ?></span>
                <h1 class="text-3xl lg:text-5xl font-black tracking-tight mb-4 capitalize">Halo, <?= session()->get('username') ?>!</h1>
                <p class="text-blue-100/70 text-sm lg:text-base font-medium max-w-md leading-relaxed">
                    <?= $isAdmin ? 'Selamat datang di Command Center SIBARUKI. Pantau dan kelola data perumahan Kabupaten Sinjai dengan presisi.' : 'Selamat bekerja. Pantau data perumahan di wilayah tugas Anda dengan akurat.' ?>
                </p>
                
                <div class="mt-8 flex flex-wrap gap-4">
                    <?php if(has_permission('create_rtlh')): ?>
                    <a href="<?= base_url('rtlh/create') ?>" class="bg-white text-blue-900 px-6 py-3 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl hover:bg-blue-50 transition-all active:scale-95 flex items-center gap-2">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i> Input RTLH Baru
                    </a>
                    <?php endif; ?>
                    <?php if(has_permission('export_data')): ?>
                    <a href="<?= base_url('rtlh') ?>" class="bg-blue-950/40 backdrop-blur-md border border-white/10 text-white px-6 py-3 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-blue-950/60 transition-all active:scale-95 flex items-center gap-2">
                        <i data-lucide="file-text" class="w-4 h-4"></i> Data RTLH
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <i data-lucide="layout-dashboard" class="w-64 h-64 absolute -right-16 -bottom-16 text-white opacity-[0.05] rotate-12"></i>
        </div>

        <?php if($isAdmin): ?>
        <!-- HEALTH CHECK WIDGET (Admin Only) -->
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
                    </div>apa
                </div>
            </div>
            <div class="mt-8 pt-6 border-t dark:border-slate-800">
                <p class="text-[9px] text-slate-400 italic leading-relaxed">Status data se-Kabupaten Sinjai.</p>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- 2. SUMMARY CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- RTLH -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-2xl transition-all duration-500 relative overflow-hidden group">
            <div class="relative z-10">
                <div class="p-4 bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-500 rounded-2xl w-fit mb-6 shadow-inner group-hover:scale-110 transition-transform duration-500">
                    <i data-lucide="home" class="w-8 h-8"></i>
                </div>
                <p class="text-[10px] font-black text-amber-600 dark:text-amber-500 uppercase tracking-widest mb-2"><?= $isAdmin ? 'Total RTLH Kabupaten' : 'RTLH di Wilayah Anda' ?></p>
                <div class="flex items-baseline gap-3">
                    <h2 class="text-4xl font-black text-slate-800 dark:text-white"><?= number_format($totalRtlh) ?></h2>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Unit</span>
                </div>
            </div>
        </div>

        <!-- Wilayah Kumuh -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-2xl transition-all duration-500 relative overflow-hidden group">
            <div class="relative z-10">
                <div class="p-4 bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-500 rounded-2xl w-fit mb-6 shadow-inner group-hover:scale-110 transition-transform duration-500">
                    <i data-lucide="map-pin" class="w-8 h-8"></i>
                </div>
                <p class="text-[10px] font-black text-rose-600 dark:text-rose-500 uppercase tracking-widest mb-2"><?= $isAdmin ? 'Titik Kawasan Kumuh' : 'Kawasan Kumuh Anda' ?></p>
                <div class="flex items-baseline gap-3">
                    <h2 class="text-4xl font-black text-slate-800 dark:text-white"><?= number_format($totalKumuh) ?></h2>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Kawasan</span>
                </div>
            </div>
        </div>

        <!-- Scope Badge -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm transition-all duration-500 relative overflow-hidden flex flex-col justify-center">
            <p class="text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Cakupan Akses</p>
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-2xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 flex items-center justify-center font-black text-xs uppercase shrink-0"><?= substr(session()->get('role_scope'), 0, 1) ?></div>
                <div class="min-w-0">
                    <p class="text-sm font-black text-slate-800 dark:text-white uppercase leading-none mb-1"><?= session()->get('role_scope') ?></p>
                    <div class="flex flex-wrap gap-1 mt-2">
                        <?php if($isAdmin): ?>
                            <span class="text-[9px] font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950 px-2 py-0.5 rounded-md border border-blue-100 dark:border-blue-900">SELURUH KABUPATEN</span>
                        <?php else: ?>
                            <?php if(!empty($assignedDesa)): ?>
                                <?php foreach(array_slice($assignedDesa, 0, 3) as $dn): ?>
                                    <span class="text-[9px] font-bold text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-800 px-2 py-0.5 rounded-md border border-slate-100 dark:border-slate-700 uppercase"><?= $dn ?></span>
                                <?php endforeach; ?>
                                <?php if(count($assignedDesa) > 3): ?>
                                    <span class="text-[9px] font-bold text-slate-400 italic">... +<?= count($assignedDesa) - 3 ?> Desa Lain</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-[9px] font-bold text-rose-500 italic">Wilayah Belum Ditugaskan</span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. ANALYTICS & MAP GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- MINI MAP -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 p-4 rounded-[3rem] border border-slate-100 dark:border-slate-800 shadow-sm transition-colors duration-300">
            <div class="p-4 flex items-center justify-between">
                <h3 class="text-[10px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-widest">Peta Sebaran Wilayah Anda</h3>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                    <span class="text-[9px] font-black text-slate-400 uppercase">Live Preview</span>
                </div>
            </div>
            <div id="miniMap" class="h-[400px] w-full rounded-[2.5rem] overflow-hidden z-0 border dark:border-slate-800 transition-colors duration-300"></div>
        </div>

        <!-- CONDITION CHART (Restructured for better fit) -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[3rem] border border-slate-100 dark:border-slate-800 shadow-sm transition-colors duration-300">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-[10px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-widest">Analisis Kondisi Fisik</h3>
                <i data-lucide="pie-chart" class="w-4 h-4 text-slate-300"></i>
            </div>
            <div id="conditionChart"></div>
        </div>
    </div>

    <!-- 4. LOGS (ADMIN SEE ALL, OTHERS SEE OWN) -->
    <div class="grid grid-cols-1 <?= $isAdmin ? 'lg:grid-cols-2' : '' ?> gap-8">
        <div class="bg-white dark:bg-slate-900 rounded-[3rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-300">
            <div class="p-8 border-b dark:border-slate-800 flex justify-between items-center">
                <h3 class="text-[10px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-widest"><?= $isAdmin ? 'Aktivitas Terbaru (Global)' : 'Riwayat Aktivitas Anda' ?></h3>
                <i data-lucide="activity" class="w-4 h-4 text-slate-300"></i>
            </div>
            <div class="divide-y divide-slate-50 dark:divide-slate-800">
                <?php if(empty($logs)): ?>
                    <p class="p-12 text-center text-xs text-slate-400 italic">Belum ada riwayat aktivitas.</p>
                <?php endif; ?>
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
                        <p class="text-xs font-black text-slate-800 dark:text-slate-200 uppercase tracking-tight"><?= $log['action'] ?> <span class="text-blue-900 dark:text-blue-400 text-[10px]"><?= $log['table_name'] ?></span></p>
                        <p class="text-[10px] text-slate-400 font-medium italic mt-0.5 truncate w-48 lg:w-full"><?= $log['description'] ?></p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-[10px] font-black text-slate-800 dark:text-slate-300"><?= date('H:i', strtotime($log['created_at'])) ?></p>
                        <p class="text-[8px] font-bold text-slate-400 uppercase"><?= date('d M', strtotime($log['created_at'])) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if($isAdmin): ?>
        <!-- CRITICAL AREAS (Admin Only) -->
        <div class="bg-white dark:bg-slate-900 rounded-[3rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-300">
            <div class="p-8 border-b dark:border-slate-800">
                <h3 class="text-[10px] font-black text-rose-600 uppercase tracking-widest">Kawasan Kumuh Terparah</h3>
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
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
    lucide.createIcons();

    // --- 1. DONUT CHART: CONDITION (Scoped Data) ---
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

    // --- 2. MINI MAP PREVIEW (Scoped Data) ---
    document.addEventListener('DOMContentLoaded', function() {
        const map = L.map('miniMap', { attributionControl: false, zoomControl: false }).setView([-5.2, 120.2], 11);
        
        // Gunakan Tile Standard (Light) secara permanen agar lebih jelas
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        const markers = <?= json_encode($mapMarkers) ?>;
        const group = L.featureGroup();

        markers.forEach(m => {
            if (m.lokasi_koordinat) {
                const cleanCoords = m.lokasi_koordinat.replace(/[()a-zA-Z]/g, '').trim().split(' ');
                if (cleanCoords.length >= 2) {
                    const lat = parseFloat(cleanCoords[1]);
                    const lng = parseFloat(cleanCoords[0]);
                    
                    if (!isNaN(lat) && !isNaN(lng)) {
                        const marker = L.circleMarker([lat, lng], {
                            radius: 5, fillColor: '#1e3a8a', color: '#ffffff', weight: 2, opacity: 1, fillOpacity: 0.9
                        }).addTo(group).bindPopup(`<div class="p-2 text-xs font-bold text-blue-900 uppercase">${m.desa}</div>`);
                    }
                }
            }
        });
        
        group.addTo(map);
        if (markers.length > 0) map.fitBounds(group.getBounds(), { padding: [20, 20] });
    });
</script>

<style>
    .apexcharts-canvas { margin: 0 auto; }
    .dark .apexcharts-text { fill: #94a3b8 !important; }
    .dark .apexcharts-legend-text { color: #94a3b8 !important; }
    .dark .apexcharts-gridline { stroke: #1e293b !important; }
</style>
<?= $this->endSection() ?>
