<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<!-- External Libraries -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<div class="space-y-8 pb-12">
    <!-- HEADER & ACTION BAR -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-blue-950 dark:text-white uppercase tracking-tight">Monitoring Aktivitas</h1>
            <p class="text-slate-400 dark:text-slate-500 text-sm font-medium italic">Pusat kendali audit: Performa, Keamanan, dan Perilaku Pengguna.</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-3 lg:justify-end">
            <a href="<?= base_url('logs/clear') ?>" onclick="return confirm('Hapus log lama (> 6 bulan)?')" class="px-5 py-2.5 bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 rounded-2xl text-[10px] font-black uppercase hover:bg-rose-100 transition-all border border-rose-100 dark:border-rose-900 flex items-center gap-2">
                <i data-lucide="trash-2" class="w-4 h-4"></i> Bersihkan Log
            </a>
            <button id="btn-refresh" onclick="toggleAutoRefresh()" class="px-5 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl flex items-center gap-2 transition-all group shadow-sm">
                <span id="refresh-status" class="w-2 h-2 bg-slate-300 rounded-full"></span>
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-500">Live Auto-Refresh</span>
            </button>
            <div class="px-5 py-2.5 bg-emerald-50 dark:bg-emerald-950/30 rounded-2xl border border-emerald-100 dark:border-emerald-800 flex flex-col justify-center">
                <p class="text-[7px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest leading-none mb-1">Latency</p>
                <p class="text-sm font-black text-slate-700 dark:text-slate-200 leading-none"><?= $system['responseTime'] ?>s</p>
            </div>
        </div>
    </div>

    <!-- 1. TOP ANALYTICS GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Trend Aktivitas (Multi-Range Chart) -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 p-8 rounded-[3rem] border border-slate-100 dark:border-slate-800 shadow-sm transition-all overflow-hidden">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-[10px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-widest flex items-center gap-2">
                    <i data-lucide="trending-up" class="w-4 h-4"></i> Tren Aktivitas Sistem
                </h3>
                <!-- Range Switcher -->
                <div class="flex p-1 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-100 dark:border-slate-800">
                    <button onclick="updateTrend('hourly')" id="tab-hourly" class="px-4 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-tighter transition-all bg-white dark:bg-blue-900 text-blue-900 dark:text-white shadow-sm">24 Jam</button>
                    <button onclick="updateTrend('daily')" id="tab-daily" class="px-4 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-tighter transition-all text-slate-400 hover:text-slate-600">7 Hari</button>
                    <button onclick="updateTrend('monthly')" id="tab-monthly" class="px-4 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-tighter transition-all text-slate-400 hover:text-slate-600">6 Bulan</button>
                </div>
            </div>
            <div id="trendChart" class="w-full"></div>
        </div>

        <!-- Distribusi Aksi (Donut) -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[3rem] border border-slate-100 dark:border-slate-800 shadow-sm transition-all">
            <h3 class="text-[10px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                <i data-lucide="pie-chart" class="w-4 h-4"></i> Komposisi Aksi
            </h3>
            <div id="actionDonut"></div>
        </div>
    </div>

    <!-- 2. STATUS WIDGETS -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Online Users -->
        <div class="bg-emerald-600 rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-xl shadow-emerald-600/20 group transition-all">
            <i data-lucide="zap" class="w-32 h-32 absolute -right-8 -bottom-8 opacity-10 rotate-12"></i>
            <h3 class="text-[10px] font-black text-emerald-200 uppercase tracking-widest mb-6 relative z-10">User Online (5 Menit)</h3>
            <div class="space-y-4 relative z-10">
                <div class="text-4xl font-black mb-2"><?= count($analytics['onlineUsers']) ?></div>
                <div class="flex -space-x-3 overflow-hidden">
                    <?php foreach($analytics['onlineUsers'] as $ou): ?>
                        <div class="inline-block h-8 w-8 rounded-full ring-2 ring-emerald-600 bg-emerald-400 flex items-center justify-center text-[10px] font-black uppercase shadow-lg" title="<?= $ou['username'] ?> - <?= $ou['instansi'] ?>">
                            <?= substr($ou['username'], 0, 1) ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Anomaly Alert -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border <?= !empty($analytics['anomalies']) ? 'border-rose-500 bg-rose-50/10' : 'border-slate-100 dark:border-slate-800' ?> shadow-sm transition-all overflow-hidden relative">
            <h3 class="text-[10px] font-black <?= !empty($analytics['anomalies']) ? 'text-rose-600' : 'text-slate-400' ?> uppercase tracking-widest mb-4 flex items-center gap-2">
                <i data-lucide="alert-triangle" class="w-4 h-4"></i> Deteksi Anomali
            </h3>
            <div class="space-y-3">
                <?php foreach(array_slice($analytics['anomalies'], 0, 2) as $a): ?>
                    <div class="p-3 bg-white dark:bg-slate-950 rounded-xl border border-rose-100 dark:border-rose-900/50">
                        <p class="text-[8px] font-black text-rose-600 uppercase mb-1"><?= $a['type'] ?></p>
                        <p class="text-[10px] font-bold text-slate-700 dark:text-slate-300 truncate"><?= $a['user'] ?>: <?= $a['desc'] ?></p>
                    </div>
                <?php endforeach; ?>
                <?php if(empty($analytics['anomalies'])): ?>
                    <div class="py-6 text-center">
                        <i data-lucide="check-shield" class="w-8 h-8 text-emerald-500/20 mx-auto mb-2"></i>
                        <p class="text-[9px] font-bold text-emerald-500 uppercase tracking-widest">Aktivitas Wajar</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Storage Monitor -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm flex flex-col justify-between transition-all">
            <div>
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                    <i data-lucide="database" class="w-4 h-4"></i> Storage Monitor
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-end">
                        <div class="space-y-1">
                            <p class="text-lg font-black text-slate-800 dark:text-white leading-none"><?= $system['disk']['percent'] ?>%</p>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">
                                <?= round($system['disk']['used'] / (1024*1024*1024), 2) ?> GB / <?= round($system['disk']['total'] / (1024*1024*1024), 2) ?> GB
                            </p>
                        </div>
                    </div>
                    <div class="w-full h-2.5 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-600 transition-all duration-1000" style="width: <?= $system['disk']['percent'] ?>%"></div>
                    </div>
                </div>
            </div>
            <p class="mt-4 text-[8px] text-slate-400 font-mono truncate uppercase"><?= basename($system['disk']['path']) ?></p>
        </div>

        <!-- Security Alarm -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border <?= $analytics['failedLogins'] > 0 ? 'border-rose-200 bg-rose-50/20' : 'border-slate-100 dark:border-slate-800' ?> shadow-sm flex flex-col justify-between transition-all">
            <div>
                <h3 class="text-[10px] font-black <?= $analytics['failedLogins'] > 0 ? 'text-rose-600' : 'text-slate-400' ?> uppercase tracking-widest mb-6 flex items-center gap-2">
                    <i data-lucide="shield-alert" class="w-4 h-4"></i> Security Alarm
                </h3>
                <div class="text-center py-2">
                    <p class="text-4xl font-black <?= $analytics['failedLogins'] > 0 ? 'text-rose-600' : 'text-slate-800 dark:text-white' ?>"><?= $analytics['failedLogins'] ?></p>
                    <p class="text-[9px] font-bold text-slate-400 uppercase mt-1">Login Gagal (24j)</p>
                </div>
            </div>
            <?php if($analytics['failedLogins'] > 0): ?>
                <div class="mt-4 p-2 bg-rose-50 dark:bg-rose-950/30 rounded-xl text-center border border-rose-100 dark:border-rose-900 animate-pulse">
                    <span class="text-[8px] font-black text-rose-600 uppercase italic">Waspada Ancaman</span>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- 3. LOG TABLE & FILTER -->
    <div class="bg-white dark:bg-slate-900 rounded-[3rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-300">
        <!-- Filter Bar -->
        <div class="p-6 border-b dark:border-slate-800">
            <form action="<?= base_url('logs') ?>" method="get" class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-5 gap-4">
                <input type="hidden" name="per_page" value="<?= $perPage ?>">
                <select name="user" onchange="this.form.submit()" class="p-3 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-[10px] font-bold outline-none dark:text-slate-300">
                    <option value="">Semua User</option>
                    <?php foreach($options['users'] as $u): ?>
                        <option value="<?= $u ?>" <?= $filters['user'] == $u ? 'selected' : '' ?>><?= $u ?></option>
                    <?php endforeach; ?>
                </select>
                <select name="action" onchange="this.form.submit()" class="p-3 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-[10px] font-bold outline-none dark:text-slate-300">
                    <option value="">Semua Aksi</option>
                    <option value="Login" <?= $filters['action'] == 'Login' ? 'selected' : '' ?>>Login</option>
                    <option value="Login Gagal" <?= $filters['action'] == 'Login Gagal' ? 'selected' : '' ?>>Security Alarm</option>
                    <option value="Tambah" <?= $filters['action'] == 'Tambah' ? 'selected' : '' ?>>Tambah</option>
                    <option value="Ubah" <?= $filters['action'] == 'Ubah' ? 'selected' : '' ?>>Ubah</option>
                    <option value="Hapus" <?= $filters['action'] == 'Hapus' ? 'selected' : '' ?>>Hapus</option>
                    <option value="Ekspor PDF" <?= $filters['action'] == 'Ekspor PDF' ? 'selected' : '' ?>>Ekspor Data</option>
                </select>
                <select name="table" onchange="this.form.submit()" class="p-3 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-[10px] font-bold outline-none dark:text-slate-300">
                    <option value="">Semua Modul</option>
                    <?php foreach($options['tables'] as $t): ?>
                        <option value="<?= $t ?>" <?= $filters['table'] == $t ? 'selected' : '' ?>><?= $t ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="date" name="date" value="<?= $filters['date'] ?>" onchange="this.form.submit()" class="p-3 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-[10px] font-bold outline-none dark:text-slate-300">
                <a href="<?= base_url('logs') ?>" class="p-3 text-[10px] font-black text-slate-400 hover:text-rose-600 uppercase text-center border border-dashed border-slate-200 dark:border-slate-800 rounded-xl transition-colors">Reset</a>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-950/50 border-b dark:border-slate-800 transition-colors">
                        <th class="p-6 text-[9px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-widest">Waktu</th>
                        <th class="p-6 text-[9px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-widest">Pengguna & Perangkat</th>
                        <th class="p-6 text-[9px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-widest text-center">Aksi</th>
                        <th class="p-6 text-[9px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-widest">Modul</th>
                        <th class="p-6 text-[9px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-widest text-center">Rincian</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                    <?php foreach($logs as $log): ?>
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors group">
                        <td class="p-6">
                            <p class="text-xs font-black text-slate-700 dark:text-slate-200"><?= date('H:i', strtotime($log['created_at'])) ?></p>
                            <p class="text-[8px] font-bold text-slate-400 uppercase"><?= date('d M Y', strtotime($log['created_at'])) ?></p>
                        </td>
                        <td class="p-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 flex items-center justify-center text-[10px] font-black uppercase shrink-0"><?= substr($log['user'] ?? 'U', 0, 1) ?></div>
                                <div class="min-w-0">
                                    <p class="text-xs font-black text-slate-700 dark:text-slate-300"><?= $log['user'] ?></p>
                                    <p class="text-[8px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-tighter">
                                        <?= $log['role_name'] ?? 'System' ?> 
                                        <span class="text-slate-300 dark:text-slate-700 mx-1">|</span> 
                                        <?= $log['instansi'] ?? '-' ?>
                                    </p>
                                    <div class="flex items-center gap-1.5 mt-1 border-t dark:border-slate-800 pt-1">
                                        <?php 
                                            $ua = strtolower($log['user_agent'] ?? '');
                                            $deviceIcon = 'monitor';
                                            if(str_contains($ua, 'android') || str_contains($ua, 'iphone')) $deviceIcon = 'smartphone';
                                        ?>
                                        <i data-lucide="<?= $deviceIcon ?>" class="w-2.5 h-3 text-slate-400"></i>
                                        <p class="text-[7px] font-bold text-slate-400 uppercase truncate max-w-[120px]"><?= $log['user_agent'] ?? 'Unknown' ?></p>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="p-6 text-center">
                            <?php 
                                $color = 'bg-slate-100 text-slate-600';
                                if($log['action'] == 'Login') $color = 'bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400';
                                if($log['action'] == 'Login Gagal') $color = 'bg-rose-100 dark:bg-rose-900 text-rose-700 dark:text-white border-rose-200';
                                if($log['action'] == 'Tambah') $color = 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400';
                                if($log['action'] == 'Ubah') $color = 'bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400';
                                if($log['action'] == 'Hapus') $color = 'bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400';
                                if($log['action'] == 'Ekspor PDF') $color = 'bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400';
                            ?>
                            <span class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest border <?= $color ?>">
                                <?= $log['action'] ?>
                            </span>
                        </td>
                        <td class="p-6"><span class="text-[10px] font-black text-slate-400 uppercase tracking-widest"><?= $log['table_name'] ?></span></td>
                        <td class="p-6 text-center">
                            <?php if(!empty($log['details'])): ?>
                                <button onclick="toggleLogDetail('detail-<?= $log['id'] ?>')" class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-lg text-[8px] font-black uppercase tracking-widest hover:bg-blue-900 hover:text-white transition-all whitespace-nowrap">Rincian</button>
                            <?php else: ?>
                                <span class="text-[8px] text-slate-300 italic uppercase">Log Ringkas</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr id="detail-<?= $log['id'] ?>" class="hidden bg-blue-50/20 dark:bg-blue-950/10">
                        <td colspan="5" class="p-8">
                            <div class="bg-white dark:bg-slate-950 p-6 rounded-2xl border border-blue-100 dark:border-blue-900/50 shadow-inner">
                                <p class="text-[9px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-[0.2em] mb-4">
                                    <?= in_array($log['action'], ['Tambah', 'Hapus']) ? 'Snapshot Data Record:' : 'Daftar Field yang Diperbarui:' ?>
                                </p>
                                <p class="text-xs text-slate-500 mb-4 italic"><?= $log['description'] ?></p>
                                <div class="space-y-2 border-t dark:border-slate-800 pt-4">
                                    <?php 
                                        $diffs = explode(' | ', $log['details'] ?? '');
                                        foreach($diffs as $d):
                                            if(empty($d)) continue;
                                    ?>
                                        <div class="flex items-start gap-3">
                                            <i data-lucide="corner-down-right" class="w-3 h-3 text-blue-400 mt-1"></i>
                                            <p class="text-[11px] font-medium text-slate-600 dark:text-slate-400 leading-relaxed"><?= $d ?></p>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        <div class="p-6 bg-slate-50/50 dark:bg-slate-950/50 border-t dark:border-slate-800 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-4 min-w-[300px]">
                <div class="flex items-center gap-3">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tampilkan:</span>
                    <select onchange="changePerPage(this.value)" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-1.5 text-xs font-bold text-slate-600 dark:text-slate-300 outline-none focus:ring-4 focus:ring-blue-500/10 transition-all cursor-pointer">
                        <?php foreach([5, 10, 25, 50] as $count): ?>
                            <option value="<?= $count ?>" <?= $perPage == $count ? 'selected' : '' ?>><?= $count ?> Data</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <span class="text-[10px] font-bold text-slate-400 italic">Total: <?= $total ?> record</span>
            </div>
            <div class="flex-grow flex justify-center">
                <?= $pager->links('group1', 'tailwind_full') ?>
            </div>
            <div class="hidden md:block min-w-[300px]"></div>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();

    // --- 1. TREND CHART (MULTI-RANGE) ---
    const trends = <?= json_encode($analytics['trend']) ?>;
    let currentRange = 'hourly';

    const trendChart = new ApexCharts(document.querySelector("#trendChart"), {
        series: [{ name: 'Aktivitas', data: trends.hourly.data }],
        chart: { type: 'area', height: 250, toolbar: { show: false }, fontFamily: 'Plus Jakarta Sans, sans-serif' },
        colors: ['#1e3a8a'],
        stroke: { curve: 'smooth', width: 3 },
        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.45, opacityTo: 0.05 } },
        xaxis: {
            categories: trends.hourly.labels,
            labels: { style: { colors: '#94a3b8', fontSize: '10px', fontWeight: 700 } }
        },
        yaxis: { labels: { show: false } },
        grid: { show: false },
        dataLabels: { enabled: false }
    });
    trendChart.render();

    function updateTrend(range) {
        // Update Buttons UI
        ['hourly', 'daily', 'monthly'].forEach(r => {
            const btn = document.getElementById(`tab-${r}`);
            btn.className = "px-4 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-tighter transition-all " + 
                            (r === range ? "bg-white dark:bg-blue-900 text-blue-900 dark:text-white shadow-sm" : "text-slate-400 hover:text-slate-600");
        });

        // Update Data
        trendChart.updateOptions({
            series: [{ data: trends[range].data }],
            xaxis: { categories: trends[range].labels }
        });
    }

    // --- 2. ACTION DONUT CHART ---
    const actionData = <?= json_encode($analytics['actionDist']) ?>;
    if (actionData.length > 0) {
        const donutOptions = {
            series: actionData.map(item => item.total),
            labels: actionData.map(item => item.label),
            chart: { type: 'donut', height: 300, fontFamily: 'Plus Jakarta Sans, sans-serif' },
            colors: ['#1e3a8a', '#10b981', '#f59e0b', '#ef4444', '#6366f1', '#8b5cf6', '#ec4899'],
            plotOptions: { pie: { donut: { size: '75%', labels: { show: true, total: { show: true, label: 'TOTAL AKSI', color: '#94a3b8' } } } } },
            legend: { position: 'bottom', labels: { colors: '#94a3b8' } },
            dataLabels: { enabled: false }
        };
        new ApexCharts(document.querySelector("#actionDonut"), donutOptions).render();
    }

    // --- 3. AUTO REFRESH LOGIC ---
    let refreshInterval;
    function toggleAutoRefresh() {
        const btn = document.getElementById('btn-refresh');
        const status = document.getElementById('refresh-status');
        const isRunning = localStorage.getItem('auto_refresh_logs') === 'true';

        if (!isRunning) {
            localStorage.setItem('auto_refresh_logs', 'true');
            btn.classList.add('bg-blue-50', 'dark:bg-blue-900/20', 'border-blue-200');
            status.classList.replace('bg-slate-300', 'bg-emerald-500');
            status.classList.add('animate-pulse');
            refreshInterval = setInterval(() => window.location.reload(), 30000);
        } else {
            localStorage.setItem('auto_refresh_logs', 'false');
            btn.classList.remove('bg-blue-50', 'dark:bg-blue-900/20', 'border-blue-200');
            status.classList.replace('bg-emerald-500', 'bg-slate-300');
            status.classList.remove('animate-pulse');
            clearInterval(refreshInterval);
        }
    }

    if (localStorage.getItem('auto_refresh_logs') === 'true') {
        localStorage.setItem('auto_refresh_logs', 'false');
        toggleAutoRefresh();
    }

    async function confirmClearLogs() {
        const ok = await customConfirm('Bersihkan Log?', 'Seluruh riwayat aktivitas yang lebih dari 6 bulan akan dihapus permanen.', 'danger');
        if (ok) window.location.href = '<?= base_url('logs/clear') ?>';
    }

    function toggleLogDetail(id) { document.getElementById(id).classList.toggle('hidden'); }
    function changePerPage(count) {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', count);
        url.searchParams.set('page_group1', 1);
        window.location.href = url.href;
    }
</script>
<?= $this->endSection() ?>
