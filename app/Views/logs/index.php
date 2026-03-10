<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<!-- External Libraries -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<div class="space-y-8 pb-12 animate-in fade-in duration-700">
    
    <!-- 1. COMMAND HEADER & GLOBAL CONTROLS -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                <span class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 dark:text-slate-500">System Audit & Forensic Log v3.0</span>
            </div>
            <h1 class="text-3xl lg:text-4xl font-black tracking-tight text-blue-950 dark:text-white uppercase">Forensik Aktivitas</h1>
        </div>
        
        <div class="flex flex-wrap items-center gap-3 lg:justify-end">
            <button id="btn-refresh" onclick="toggleAutoRefresh()" class="px-5 py-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl flex items-center gap-2 transition-all hover:shadow-lg active:scale-95 group">
                <span id="refresh-status" class="w-2 h-2 bg-slate-300 rounded-full"></span>
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-500">Real-Time Stream</span>
            </button>
            <button onclick="confirmClearLogs()" class="px-5 py-3 bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 rounded-2xl text-[10px] font-black uppercase hover:bg-rose-100 transition-all border border-rose-100 dark:border-rose-900 flex items-center gap-2 shadow-sm active:scale-95">
                <i data-lucide="trash-2" class="w-4 h-4 text-rose-500"></i> Purge History
            </button>
        </div>
    </div>

    <!-- 2. AUDIT SNAPSHOT WIDGETS -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-rose-50 dark:bg-rose-950/30 text-rose-600 rounded-xl group-hover:scale-110 transition-transform"><i data-lucide="trash-2" class="w-5 h-5"></i></div>
                <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">24H Deleted</span>
            </div>
            <h3 class="text-3xl font-black text-slate-800 dark:text-white"><?= number_format($snapshot['deleted']) ?></h3>
            <p class="text-[9px] font-bold text-rose-500 uppercase mt-1 tracking-tighter italic">Entity Redactions</p>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 rounded-xl group-hover:scale-110 transition-transform"><i data-lucide="file-down" class="w-5 h-5"></i></div>
                <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">24H Exported</span>
            </div>
            <h3 class="text-3xl font-black text-slate-800 dark:text-white"><?= number_format($snapshot['exported']) ?></h3>
            <p class="text-[9px] font-bold text-indigo-500 uppercase mt-1 tracking-tighter italic">Data Extractions</p>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 rounded-xl group-hover:scale-110 transition-transform"><i data-lucide="plus-circle" class="w-5 h-5"></i></div>
                <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">24H Created</span>
            </div>
            <h3 class="text-3xl font-black text-slate-800 dark:text-white"><?= number_format($snapshot['created']) ?></h3>
            <p class="text-[9px] font-bold text-emerald-500 uppercase mt-1 tracking-tighter italic">System Ingestion</p>
        </div>
        <div class="bg-rose-600 p-6 rounded-[2rem] shadow-xl shadow-rose-600/20 group text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-white/20 rounded-xl group-hover:scale-110 transition-transform"><i data-lucide="shield-alert" class="w-5 h-5 text-white"></i></div>
                <span class="text-[8px] font-black text-rose-200 uppercase tracking-widest">24H Critical</span>
            </div>
            <h3 class="text-3xl font-black"><?= number_format($snapshot['critical']) ?></h3>
            <p class="text-[9px] font-bold text-rose-200 uppercase mt-1 tracking-tighter italic">Security Warnings</p>
        </div>
    </div>

    <!-- 3. MAIN ANALYTICS -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 bg-blue-950 rounded-[3rem] p-10 text-white relative overflow-hidden shadow-2xl group">
            <div class="absolute top-0 right-0 w-64 h-64 bg-blue-400/10 rounded-full blur-[80px] -mr-32 -mt-32"></div>
            <div class="relative z-10 flex flex-col h-full justify-between">
                <div class="flex items-center justify-between mb-10">
                    <div>
                        <h3 class="text-[10px] font-black text-blue-300 uppercase tracking-[0.3em] mb-1">Aktivitas Sistem (Pulse)</h3>
                        <p class="text-sm font-medium text-blue-100/60 italic">Metrik load per rentang waktu.</p>
                    </div>
                    <div class="flex p-1 bg-white/5 backdrop-blur-md rounded-2xl border border-white/10">
                        <?php foreach(['hourly', 'daily', 'monthly'] as $r): ?>
                            <button onclick="updateTrend('<?= $r ?>')" id="tab-<?= $r ?>" class="px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all <?= $r=='hourly'?'bg-white text-blue-950 shadow-lg':'text-blue-300 hover:text-white' ?>"><?= ucfirst($r) ?></button>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div id="trendChart" class="w-full h-[280px]"></div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-8 flex items-center justify-between">
                    Live Diagnostics <i data-lucide="activity" class="w-4 h-4 text-emerald-500"></i>
                </h3>
                <div class="space-y-8">
                    <div>
                        <div class="flex justify-between text-[10px] font-black uppercase mb-2">
                            <span class="text-slate-500">DB Connection</span>
                            <span class="text-emerald-600"><?= $system['dbStatus'] ?></span>
                        </div>
                        <div class="h-1.5 w-full bg-slate-50 dark:bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-500 rounded-full w-full"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-[10px] font-black uppercase mb-2">
                            <span class="text-slate-500">Disk Integrity</span>
                            <span class="text-blue-600"><?= $system['disk']['percent'] ?>% Checked</span>
                        </div>
                        <div class="h-1.5 w-full bg-slate-50 dark:bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full bg-blue-500 rounded-full" style="width: <?= $system['disk']['percent'] ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-8 pt-6 border-t border-slate-50 dark:border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-blue-600"><i data-lucide="zap" class="w-5 h-5"></i></div>
                    <div>
                        <p class="text-[10px] font-black text-slate-800 dark:text-slate-200 uppercase">Mean Latency</p>
                        <p class="text-xs font-bold text-emerald-600"><?= $system['responseTime'] ?> ms per request</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. INTELLIGENCE FILTER HUB -->
    <div class="bg-white dark:bg-slate-900 rounded-[3rem] border border-slate-100 dark:border-slate-800 shadow-2xl overflow-hidden">
        <div class="p-8 border-b border-slate-100 dark:border-slate-800 bg-gradient-to-r from-slate-50/50 to-white dark:from-slate-900/50 dark:to-slate-900">
            <form action="<?= base_url('logs') ?>" method="get" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                <input type="hidden" name="per_page" value="<?= $perPage ?>">
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-widest ml-1">Subject</label>
                    <select name="user" onchange="this.form.submit()" class="w-full px-4 py-3 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-[11px] font-bold outline-none focus:ring-4 focus:ring-blue-500/10 transition-all appearance-none dark:text-slate-200 shadow-sm">
                        <option value="">Semua User</option>
                        <?php foreach($options['users'] as $u): ?>
                            <option value="<?= $u ?>" <?= $filters['user'] == $u ? 'selected' : '' ?>><?= $u ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-rose-600 dark:text-rose-400 uppercase tracking-widest ml-1">Action</label>
                    <select name="action" onchange="this.form.submit()" class="w-full px-4 py-3 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-[11px] font-bold outline-none focus:ring-4 focus:ring-rose-500/10 transition-all appearance-none dark:text-slate-200 shadow-sm">
                        <option value="">Semua Aksi</option>
                        <?php foreach(['Login', 'Login Gagal', 'Tambah', 'Ubah', 'Hapus', 'Ekspor PDF'] as $a): ?>
                            <option value="<?= $a ?>" <?= $filters['action'] == $a ? 'selected' : '' ?>><?= $a ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest ml-1">Severity</label>
                    <select name="severity" onchange="this.form.submit()" class="w-full px-4 py-3 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-[11px] font-bold outline-none focus:ring-4 focus:ring-emerald-500/10 transition-all appearance-none dark:text-slate-200 shadow-sm">
                        <option value="">Tingkat Bahaya</option>
                        <option value="info" <?= $filters['severity'] == 'info' ? 'selected' : '' ?>>Info (Normal)</option>
                        <option value="warning" <?= $filters['severity'] == 'warning' ? 'selected' : '' ?>>Warning (Berisiko)</option>
                        <option value="critical" <?= $filters['severity'] == 'critical' ? 'selected' : '' ?>>Critical (Bahaya)</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest ml-1">Timestamp</label>
                    <input type="date" name="date" value="<?= $filters['date'] ?>" onchange="this.form.submit()" class="w-full px-4 py-2.5 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-[11px] font-bold outline-none dark:text-slate-200 shadow-sm">
                </div>
                <div class="flex items-end pb-0.5">
                    <a href="<?= base_url('logs') ?>" class="w-full flex items-center justify-center gap-2 py-3 bg-slate-100 dark:bg-slate-800 text-[10px] font-black uppercase text-slate-500 hover:bg-rose-600 hover:text-white rounded-2xl transition-all shadow-sm group">
                        <i data-lucide="refresh-ccw" class="w-3.5 h-3.5 group-hover:rotate-180 transition-transform duration-500"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Table Forensic Content -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[1100px]">
                <thead>
                    <tr class="bg-white dark:bg-slate-900 border-b dark:border-slate-800">
                        <th class="p-8 text-[10px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-[0.2em]">Log Context</th>
                        <th class="p-8 text-[10px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-[0.2em]">Actor Forensic</th>
                        <th class="p-8 text-[10px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-[0.2em] text-center">Threat Level</th>
                        <th class="p-8 text-[10px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-[0.2em]">Target System</th>
                        <th class="p-8 text-[10px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-[0.2em] text-center">Audit Trails</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50">
                    <?php foreach($logs as $log): 
                        $meta = json_decode($log['user_agent'] ?? '{}', true);
                        $isLegacy = !is_array($meta);
                    ?>
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-all group">
                        <td class="p-8">
                            <div class="flex flex-col">
                                <span class="text-[9px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest mb-1">Entry #<?= $log['id'] ?></span>
                                <p class="text-sm font-black text-slate-700 dark:text-slate-200"><?= date('H:i:s', strtotime($log['created_at'])) ?></p>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1"><?= date('d F Y', strtotime($log['created_at'])) ?></p>
                            </div>
                        </td>
                        <td class="p-8">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 flex items-center justify-center text-sm font-black uppercase shadow-inner shrink-0"><?= substr($log['user'], 0, 1) ?></div>
                                <div class="min-w-0">
                                    <p class="text-sm font-black text-slate-800 dark:text-slate-200 truncate"><?= $log['user'] ?></p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-[9px] font-black text-blue-600 dark:text-blue-400 uppercase px-2 py-0.5 bg-blue-50 dark:bg-blue-900/30 rounded-md"><?= $log['ip_address'] ?></span>
                                        <?php if(!$isLegacy): ?>
                                            <span class="text-[9px] font-bold text-slate-400 uppercase"><?= $meta['platform'] ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if(!$isLegacy): ?>
                                    <div class="flex items-center gap-3 mt-3 opacity-60 group-hover:opacity-100 transition-opacity">
                                        <div class="flex items-center gap-1.5">
                                            <i data-lucide="<?= $meta['device']=='Mobile'?'smartphone':'monitor' ?>" class="w-3 h-3 text-slate-400"></i>
                                            <span class="text-[8px] font-black text-slate-500 uppercase"><?= $meta['browser'] ?> <?= $meta['version'] ?></span>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <i data-lucide="clock" class="w-3 h-3 text-slate-400"></i>
                                            <span class="text-[8px] font-black text-emerald-600 uppercase"><?= $meta['latency_ms'] ?>ms</span>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td class="p-8 text-center">
                            <?php 
                                $sevClass = 'border-slate-200 text-slate-500';
                                if($log['severity'] == 'warning') $sevClass = 'bg-amber-50 dark:bg-amber-900/20 border-amber-200 text-amber-600';
                                if($log['severity'] == 'critical') $sevClass = 'bg-rose-50 dark:bg-rose-900/20 border-rose-200 text-rose-600 animate-pulse';
                            ?>
                            <div class="flex flex-col items-center gap-2">
                                <span class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest border <?= $sevClass ?>"><?= $log['severity'] ?></span>
                                <span class="text-[10px] font-bold text-slate-500 uppercase"><?= $log['action'] ?></span>
                            </div>
                        </td>
                        <td class="p-8">
                            <div class="space-y-1">
                                <span class="text-[11px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest"><?= $log['table_name'] ?></span>
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 font-medium italic line-clamp-2"><?= $log['description'] ?></p>
                            </div>
                        </td>
                        <td class="p-8 text-center">
                            <?php if(!empty($log['details'])): ?>
                                <button onclick="toggleLogDetail('detail-<?= $log['id'] ?>')" class="px-5 py-2.5 bg-blue-950 dark:bg-white text-white dark:text-blue-950 rounded-xl text-[9px] font-black uppercase tracking-[0.2em] hover:scale-105 transition-all shadow-xl active:scale-95 whitespace-nowrap">Investigate</button>
                            <?php else: ?>
                                <span class="text-[9px] text-slate-300 font-bold uppercase tracking-widest">No Evidence</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    
                    <!-- DIFF DETAIL VIEW -->
                    <tr id="detail-<?= $log['id'] ?>" class="hidden bg-slate-50/50 dark:bg-slate-950/50">
                        <td colspan="5" class="p-10">
                            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-blue-100 dark:border-blue-900 overflow-hidden shadow-2xl">
                                <div class="p-6 bg-blue-950 text-white flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="p-2 bg-white/10 rounded-xl"><i data-lucide="shield-check" class="w-5 h-5 text-blue-400"></i></div>
                                        <div>
                                            <p class="text-[9px] font-black text-blue-400 uppercase tracking-widest mb-1">Audit Evidence Analysis</p>
                                            <h4 class="text-sm font-black uppercase"><?= $log['action'] ?> Data - <?= $log['table_name'] ?></h4>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[9px] font-black text-blue-400 uppercase">Process Latency</p>
                                        <span class="text-xs font-black text-emerald-400"><?= $meta['latency_ms'] ?? '?' ?> ms</span>
                                    </div>
                                </div>
                                <div class="p-8 space-y-8">
                                    <div class="p-5 bg-blue-50/30 dark:bg-blue-900/10 rounded-2xl border border-blue-100/50 dark:border-blue-800/50">
                                        <p class="text-xs text-slate-600 dark:text-slate-300 font-medium italic leading-relaxed">"<?= $log['description'] ?>"</p>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 gap-4">
                                        <?php 
                                            $diffs = explode(' | ', $log['details'] ?? '');
                                            foreach($diffs as $d):
                                                if(empty($d)) continue;
                                                $isUpdate = str_contains($d, ' diubah dari ');
                                        ?>
                                            <div class="p-5 bg-slate-50/50 dark:bg-slate-950/50 rounded-2xl border border-transparent hover:border-slate-200 transition-all">
                                                <?php if($isUpdate): 
                                                    preg_match("/^(.*?) diubah dari '(.*?)' menjadi '(.*?)'$/", $d, $matches);
                                                    $fieldName = $matches[1] ?? 'Field';
                                                    $oldVal = $matches[2] ?? '-';
                                                    $newVal = $matches[3] ?? '-';
                                                ?>
                                                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                                                        <div class="w-40 shrink-0"><span class="text-[10px] font-black text-slate-400 uppercase tracking-widest"><?= $fieldName ?></span></div>
                                                        <div class="flex flex-grow items-center gap-4">
                                                            <div class="flex-1 bg-white dark:bg-slate-900 p-3 rounded-xl border border-rose-100 text-center relative overflow-hidden">
                                                                <span class="text-[8px] absolute top-1 left-2 font-black text-rose-400 uppercase opacity-50">BEFORE</span>
                                                                <span class="text-xs font-bold text-rose-600 line-through opacity-70"><?= $oldVal ?></span>
                                                            </div>
                                                            <i data-lucide="chevrons-right" class="w-5 h-5 text-blue-500 shrink-0 animate-pulse"></i>
                                                            <div class="flex-1 bg-white dark:bg-slate-900 p-3 rounded-xl border border-emerald-100 shadow-md text-center relative overflow-hidden">
                                                                <span class="text-[8px] absolute top-1 left-2 font-black text-emerald-400 uppercase">AFTER</span>
                                                                <span class="text-xs font-black text-emerald-600 dark:text-emerald-400"><?= $newVal ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="flex items-center gap-4">
                                                        <div class="w-2 h-2 rounded-full <?= str_contains($log['action'], 'Hapus') ? 'bg-rose-500' : 'bg-emerald-500' ?> shadow-lg"></div>
                                                        <p class="text-xs font-black text-slate-700 dark:text-slate-300 uppercase tracking-tight"><?= $d ?></p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="p-6 bg-slate-50 dark:bg-slate-950 border-t dark:border-slate-800 flex justify-between items-center text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">
                                    <span>Session: <?= $meta['scope'] ?? '?' ?> Mode</span>
                                    <span>Auth Hash: <?= md5($log['id'] . $log['created_at']) ?></span>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="p-10 bg-slate-50/50 dark:bg-slate-950/50 border-t dark:border-slate-800 flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-3 bg-white dark:bg-slate-900 p-1 rounded-2xl shadow-inner border border-slate-100 dark:border-slate-800">
                    <?php foreach([10, 25, 50] as $count): ?>
                        <button onclick="changePerPage(<?= $count ?>)" class="px-4 py-2 rounded-xl text-[10px] font-black uppercase transition-all <?= $perPage == $count ? 'bg-blue-950 text-white shadow-lg' : 'text-slate-400 hover:text-slate-600' ?>"><?= $count ?></button>
                    <?php endforeach; ?>
                </div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Record: <span class="text-blue-600"><?= number_format($total) ?></span></span>
            </div>
            <?= $pager->links('group1', 'tailwind_full') ?>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
    const trends = <?= json_encode($analytics['trend']) ?>;
    const trendChart = new ApexCharts(document.querySelector("#trendChart"), {
        series: [{ name: 'Activity', data: trends.hourly.data }],
        chart: { type: 'area', height: 280, toolbar: { show: false }, fontFamily: 'Plus Jakarta Sans' },
        colors: ['#60a5fa'], stroke: { curve: 'smooth', width: 4 },
        fill: { type: 'gradient', gradient: { opacityFrom: 0.4, opacityTo: 0.05 } },
        xaxis: { categories: trends.hourly.labels, labels: { style: { colors: '#94a3b8', fontSize: '9px', fontWeight: 900 }, rotate: -45 }, axisBorder: { show: false } },
        yaxis: { labels: { show: false } }, grid: { borderColor: 'rgba(255,255,255,0.05)', strokeDashArray: 4 },
        dataLabels: { enabled: false }, tooltip: { theme: 'dark' }
    });
    trendChart.render();

    function updateTrend(range) {
        ['hourly', 'daily', 'monthly'].forEach(r => {
            document.getElementById(`tab-${r}`).className = `px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all ${r===range?'bg-white text-blue-950 shadow-lg':'text-blue-300 hover:text-white'}`;
        });
        trendChart.updateOptions({ series: [{ data: trends[range].data }], xaxis: { categories: trends[range].labels } });
    }

    function toggleAutoRefresh() {
        const btn = document.getElementById('btn-refresh');
        const status = document.getElementById('refresh-status');
        const isRunning = localStorage.getItem('auto_refresh_logs') === 'true';
        if (!isRunning) {
            localStorage.setItem('auto_refresh_logs', 'true');
            btn.classList.add('bg-blue-50', 'dark:bg-blue-900/20', 'border-blue-200');
            status.classList.replace('bg-slate-300', 'bg-emerald-500'); status.classList.add('animate-pulse');
            setTimeout(() => window.location.reload(), 30000);
        } else {
            localStorage.setItem('auto_refresh_logs', 'false');
            btn.classList.remove('bg-blue-50', 'dark:bg-blue-900/20', 'border-blue-200');
            status.classList.replace('bg-emerald-500', 'bg-slate-300'); status.classList.remove('animate-pulse');
        }
    }
    if (localStorage.getItem('auto_refresh_logs') === 'true') { localStorage.setItem('auto_refresh_logs', 'false'); toggleAutoRefresh(); }

    function confirmClearLogs() {
        if (confirm('Permanently purge audit history older than 6 months?')) {
            const loader = document.getElementById('page-loader');
            if (loader) { loader.classList.remove('opacity-0'); loader.classList.remove('pointer-events-none'); }
            window.location.href = '<?= base_url('logs/clear') ?>';
        }
    }

    function toggleLogDetail(id) { document.getElementById(id).classList.toggle('hidden'); lucide.createIcons(); }
    function changePerPage(count) {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', count);
        window.location.href = url.href;
    }
</script>
<?= $this->endSection() ?>
