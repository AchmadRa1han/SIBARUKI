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
                <span class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 dark:text-slate-500">System Audit & Forensic Log</span>
            </div>
            <h1 class="text-3xl lg:text-4xl font-black tracking-tight text-blue-950 dark:text-white uppercase">Monitoring Aktivitas</h1>
        </div>
        
        <div class="flex flex-wrap items-center gap-3 lg:justify-end">
            <button id="btn-refresh" onclick="toggleAutoRefresh()" class="px-5 py-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl flex items-center gap-2 transition-all hover:shadow-lg active:scale-95 group">
                <span id="refresh-status" class="w-2 h-2 bg-slate-300 rounded-full"></span>
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-500">Live Stream</span>
            </button>
            <button onclick="confirmClearLogs()" class="px-5 py-3 bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 rounded-2xl text-[10px] font-black uppercase hover:bg-rose-100 transition-all border border-rose-100 dark:border-rose-900 flex items-center gap-2 shadow-sm active:scale-95">
                <i data-lucide="trash-2" class="w-4 h-4 text-rose-500"></i> Housekeeping
            </button>
        </div>
    </div>

    <!-- 2. AUDIT SNAPSHOT WIDGETS (24H) -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <!-- Deleted -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-rose-50 dark:bg-rose-950/30 text-rose-600 rounded-xl group-hover:scale-110 transition-transform"><i data-lucide="trash-2" class="w-5 h-5"></i></div>
                <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">24H Dihapus</span>
            </div>
            <h3 class="text-3xl font-black text-slate-800 dark:text-white"><?= number_format($snapshot['deleted']) ?></h3>
            <p class="text-[9px] font-bold text-rose-500 uppercase mt-1 tracking-tighter italic">Data Removal</p>
        </div>
        <!-- Exported -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 rounded-xl group-hover:scale-110 transition-transform"><i data-lucide="file-down" class="w-5 h-5"></i></div>
                <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">24H Ekspor</span>
            </div>
            <h3 class="text-3xl font-black text-slate-800 dark:text-white"><?= number_format($snapshot['exported']) ?></h3>
            <p class="text-[9px] font-bold text-indigo-500 uppercase mt-1 tracking-tighter italic">Info Retrieval</p>
        </div>
        <!-- Created -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 rounded-xl group-hover:scale-110 transition-transform"><i data-lucide="plus-circle" class="w-5 h-5"></i></div>
                <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">24H Tambah</span>
            </div>
            <h3 class="text-3xl font-black text-slate-800 dark:text-white"><?= number_format($snapshot['created']) ?></h3>
            <p class="text-[9px] font-bold text-emerald-500 uppercase mt-1 tracking-tighter italic">New Entities</p>
        </div>
        <!-- Critical -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-rose-100 dark:bg-rose-900 text-rose-600 dark:text-rose-200 rounded-xl group-hover:scale-110 transition-transform"><i data-lucide="shield-alert" class="w-5 h-5"></i></div>
                <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">24H Critical</span>
            </div>
            <h3 class="text-3xl font-black text-rose-600"><?= number_format($snapshot['critical']) ?></h3>
            <p class="text-[9px] font-bold text-rose-500 uppercase mt-1 tracking-tighter italic">Security Events</p>
        </div>
    </div>

    <!-- 3. MAIN CHARTS GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Activity Pulse -->
        <div class="lg:col-span-2 bg-blue-950 rounded-[3rem] p-10 text-white relative overflow-hidden shadow-2xl group">
            <div class="absolute top-0 right-0 w-64 h-64 bg-blue-400/10 rounded-full blur-[80px] -mr-32 -mt-32"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-10">
                    <div>
                        <h3 class="text-[10px] font-black text-blue-300 uppercase tracking-[0.3em] mb-1">Aktivitas Sistem (Live Pulse)</h3>
                        <p class="text-sm font-medium text-blue-100/60 italic">Distribusi load per rentang waktu.</p>
                    </div>
                    <div class="flex p-1 bg-white/5 backdrop-blur-md rounded-2xl border border-white/10">
                        <button onclick="updateTrend('hourly')" id="tab-hourly" class="px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all bg-white text-blue-950 shadow-lg">Hourly</button>
                        <button onclick="updateTrend('daily')" id="tab-daily" class="px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all text-blue-300 hover:text-white">Daily</button>
                        <button onclick="updateTrend('monthly')" id="tab-monthly" class="px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all text-blue-300 hover:text-white">Monthly</button>
                    </div>
                </div>
                <div id="trendChart" class="w-full h-[280px]"></div>
            </div>
        </div>

        <div class="flex flex-col gap-6">
            <!-- Resource Monitor -->
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm flex-1">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-8 flex items-center justify-between">
                    System Resources <i data-lucide="cpu" class="w-4 h-4 text-blue-500"></i>
                </h3>
                <div class="space-y-6">
                    <div>
                        <div class="flex justify-between text-[10px] font-black uppercase mb-2">
                            <span class="text-slate-500">Server Load</span>
                            <span class="text-blue-600"><?= $system['serverLoad'] ?></span>
                        </div>
                        <div class="h-1.5 w-full bg-slate-50 dark:bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full bg-blue-500 rounded-full" style="width: <?= min(100, (int)$system['serverLoad'] * 5) ?>%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-[10px] font-black uppercase mb-2">
                            <span class="text-slate-500">Storage Usage</span>
                            <span class="text-amber-600"><?= $system['disk']['percent'] ?>%</span>
                        </div>
                        <div class="h-1.5 w-full bg-slate-50 dark:bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full bg-amber-500 rounded-full transition-all duration-1000" style="width: <?= $system['disk']['percent'] ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Presence & Security Alarm -->
            <div class="bg-rose-600 rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-xl shadow-rose-600/20 group">
                <i data-lucide="shield-alert" class="w-32 h-32 absolute -right-8 -bottom-8 opacity-10 rotate-12"></i>
                <h3 class="text-[10px] font-black text-rose-200 uppercase tracking-widest mb-6 relative z-10">Security Alarm</h3>
                <div class="space-y-4 relative z-10">
                    <div class="text-4xl font-black mb-2"><?= $analytics['failedLogins'] ?></div>
                    <p class="text-[9px] font-bold text-rose-200 uppercase tracking-widest">Login Gagal Terdeteksi (24j)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. INTELLIGENCE FILTER HUB -->
    <div class="bg-white dark:bg-slate-900 rounded-[3rem] border border-slate-100 dark:border-slate-800 shadow-2xl overflow-hidden transition-all duration-500">
        <div class="p-8 border-b border-slate-100 dark:border-slate-800 bg-gradient-to-r from-slate-50/50 to-white dark:from-slate-900/50 dark:to-slate-900">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-1.5 h-1.5 rounded-full bg-blue-600"></div>
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Parameter Pencarian Taktis</h3>
            </div>
            
            <form action="<?= base_url('logs') ?>" method="get" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                <input type="hidden" name="per_page" value="<?= $perPage ?>">
                
                <!-- Filter User -->
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-widest ml-1">Operator</label>
                    <div class="relative group">
                        <i data-lucide="user" class="w-3.5 h-3.5 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                        <select name="user" onchange="this.form.submit()" class="w-full pl-11 pr-4 py-3 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-[11px] font-bold outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all appearance-none dark:text-slate-200 cursor-pointer shadow-sm">
                            <option value="">Semua Pengguna</option>
                            <?php foreach($options['users'] as $u): ?>
                                <option value="<?= $u ?>" <?= $filters['user'] == $u ? 'selected' : '' ?>><?= $u ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Filter Action -->
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-rose-600 dark:text-rose-400 uppercase tracking-widest ml-1">Jenis Aksi</label>
                    <div class="relative group">
                        <i data-lucide="activity" class="w-3.5 h-3.5 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-rose-500 transition-colors"></i>
                        <select name="action" onchange="this.form.submit()" class="w-full pl-11 pr-4 py-3 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-[11px] font-bold outline-none focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 transition-all appearance-none dark:text-slate-200 cursor-pointer shadow-sm">
                            <option value="">Seluruh Aktivitas</option>
                            <option value="Login" <?= $filters['action'] == 'Login' ? 'selected' : '' ?>>Login Sukses</option>
                            <option value="Login Gagal" <?= $filters['action'] == 'Login Gagal' ? 'selected' : '' ?>>Security Alert</option>
                            <option value="Tambah" <?= $filters['action'] == 'Tambah' ? 'selected' : '' ?>>Tambah Data</option>
                            <option value="Ubah" <?= $filters['action'] == 'Ubah' ? 'selected' : '' ?>>Update Data</option>
                            <option value="Hapus" <?= $filters['action'] == 'Hapus' ? 'selected' : '' ?>>Hapus Data</option>
                            <option value="Ekspor PDF" <?= $filters['action'] == 'Ekspor PDF' ? 'selected' : '' ?>>Export Log</option>
                        </select>
                    </div>
                </div>

                <!-- Filter Severity -->
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-amber-600 dark:text-amber-400 uppercase tracking-widest ml-1">Severity</label>
                    <div class="relative group">
                        <i data-lucide="alert-triangle" class="w-3.5 h-3.5 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-amber-500 transition-colors"></i>
                        <select name="severity" onchange="this.form.submit()" class="w-full pl-11 pr-4 py-3 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-[11px] font-bold outline-none focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 transition-all appearance-none dark:text-slate-200 cursor-pointer shadow-sm">
                            <option value="">Semua Tingkat</option>
                            <option value="info" <?= $filters['severity'] == 'info' ? 'selected' : '' ?>>Normal (Info)</option>
                            <option value="warning" <?= $filters['severity'] == 'warning' ? 'selected' : '' ?>>Waspada (Warning)</option>
                            <option value="critical" <?= $filters['severity'] == 'critical' ? 'selected' : '' ?>>Bahaya (Critical)</option>
                        </select>
                    </div>
                </div>

                <!-- Filter Date -->
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest ml-1">Rentang Waktu</label>
                    <div class="relative group">
                        <i data-lucide="calendar" class="w-3.5 h-3.5 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                        <input type="date" name="date" value="<?= $filters['date'] ?>" onchange="this.form.submit()" class="w-full pl-11 pr-4 py-2.5 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-[11px] font-bold outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all dark:text-slate-200 shadow-sm cursor-pointer">
                    </div>
                </div>

                <!-- Reset Button -->
                <div class="flex items-end pb-0.5">
                    <a href="<?= base_url('logs') ?>" class="w-full flex items-center justify-center gap-2 py-3 bg-slate-100 dark:bg-slate-800 text-[10px] font-black uppercase tracking-widest text-slate-500 hover:bg-rose-600 hover:text-white rounded-2xl transition-all shadow-sm active:scale-95 group">
                        <i data-lucide="refresh-ccw" class="w-3.5 h-3.5 group-hover:rotate-180 transition-transform duration-500"></i>
                        Reset Filter
                    </a>
                </div>
            </form>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[1100px]">
                <thead>
                    <tr class="bg-white dark:bg-slate-900 border-b dark:border-slate-800">
                        <th class="p-8 text-[10px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-[0.2em]">Log ID & Time</th>
                        <th class="p-8 text-[10px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-[0.2em]">Forensic Subject</th>
                        <th class="p-8 text-[10px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-[0.2em] text-center">Severity</th>
                        <th class="p-8 text-[10px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-[0.2em]">Target Entity</th>
                        <th class="p-8 text-[10px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-[0.2em] text-center">Audit Evidence</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50">
                    <?php foreach($logs as $log): ?>
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-all group">
                        <td class="p-8">
                            <div class="flex flex-col">
                                <span class="text-[9px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest mb-1">#<?= str_pad($log['id'], 6, '0', STR_PAD_LEFT) ?></span>
                                <p class="text-sm font-black text-slate-700 dark:text-slate-200"><?= date('H:i:s', strtotime($log['created_at'])) ?></p>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1"><?= date('d F Y', strtotime($log['created_at'])) ?></p>
                            </div>
                        </td>
                        <td class="p-8">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 flex items-center justify-center text-sm font-black uppercase shadow-inner shrink-0"><?= substr($log['user'] ?? 'U', 0, 1) ?></div>
                                <div class="min-w-0">
                                    <p class="text-sm font-black text-slate-800 dark:text-slate-200 truncate"><?= $log['user'] ?></p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-[9px] font-black text-blue-600 dark:text-blue-400 uppercase px-2 py-0.5 bg-blue-50 dark:bg-blue-900/30 rounded-md"><?= $log['role_name'] ?? 'System' ?></span>
                                        <span class="text-[9px] font-bold text-slate-400 uppercase"><?= $log['ip_address'] ?? 'No IP' ?></span>
                                    </div>
                                    <div class="flex items-center gap-2 mt-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                        <?php 
                                            $ua = strtolower($log['user_agent'] ?? '');
                                            $icon = 'monitor'; if(str_contains($ua, 'android') || str_contains($ua, 'iphone')) $icon = 'smartphone';
                                        ?>
                                        <i data-lucide="<?= $icon ?>" class="w-3 h-3 text-slate-400"></i>
                                        <p class="text-[8px] font-mono text-slate-400 truncate max-w-[180px]"><?= $log['user_agent'] ?? 'No Trace' ?></p>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="p-8 text-center">
                            <?php 
                                $sevClass = 'bg-slate-100 text-slate-600';
                                if($log['severity'] == 'warning') $sevClass = 'bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 border-amber-200';
                                if($log['severity'] == 'critical') $sevClass = 'bg-rose-100 dark:bg-rose-900 text-rose-700 dark:text-white border-rose-200 animate-pulse';
                            ?>
                            <div class="flex flex-col items-center gap-2">
                                <span class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest border <?= $sevClass ?>">
                                    <?= $log['severity'] ?>
                                </span>
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
                                <button onclick="toggleLogDetail('detail-<?= $log['id'] ?>')" class="px-4 py-2 bg-blue-950 dark:bg-white text-white dark:text-blue-950 rounded-xl text-[9px] font-black uppercase tracking-[0.2em] hover:scale-105 transition-all shadow-xl active:scale-95 whitespace-nowrap">Investigate</button>
                            <?php else: ?>
                                <span class="text-[9px] text-slate-300 font-bold uppercase tracking-widest">No Artifact</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    
                    <!-- ENHANCED DIFF VIEW -->
                    <tr id="detail-<?= $log['id'] ?>" class="hidden bg-slate-50/50 dark:bg-slate-950/50">
                        <td colspan="5" class="p-10">
                            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-blue-100 dark:border-blue-900 overflow-hidden shadow-2xl transition-all">
                                <div class="p-6 bg-blue-950 text-white flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="p-2 bg-white/10 rounded-xl"><i data-lucide="database" class="w-5 h-5 text-blue-400"></i></div>
                                        <div>
                                            <p class="text-[9px] font-black text-blue-400 uppercase tracking-widest mb-1">Deep Data Analysis</p>
                                            <h4 class="text-sm font-black uppercase"><?= $log['action'] ?> Artifact - Module <?= $log['table_name'] ?></h4>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[9px] font-black text-blue-400 uppercase">Status</p>
                                        <span class="text-xs font-black text-emerald-400 uppercase">Verified Evidence</span>
                                    </div>
                                </div>
                                <div class="p-8 space-y-8">
                                    <div class="p-5 bg-blue-50/30 dark:bg-blue-900/10 rounded-2xl border border-blue-100/50 dark:border-blue-800/50">
                                        <div class="flex items-center gap-2 mb-2">
                                            <i data-lucide="info" class="w-3.5 h-3.5 text-blue-500"></i>
                                            <span class="text-[9px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-widest">Executive Summary</span>
                                        </div>
                                        <p class="text-xs text-slate-600 dark:text-slate-300 font-medium leading-relaxed">"<?= $log['description'] ?>"</p>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 gap-4">
                                        <?php 
                                            $diffs = explode(' | ', $log['details'] ?? '');
                                            foreach($diffs as $d):
                                                if(empty($d)) continue;
                                                $isUpdate = str_contains($d, ' diubah dari ');
                                        ?>
                                            <div class="p-5 bg-slate-50/50 dark:bg-slate-950/50 rounded-2xl border border-transparent hover:border-slate-200 dark:hover:border-slate-800 transition-all group/item">
                                                <?php if($isUpdate): 
                                                    // Parse: Field diubah dari 'Old' menjadi 'New'
                                                    preg_match("/^(.*?) diubah dari '(.*?)' menjadi '(.*?)'$/", $d, $matches);
                                                    $fieldName = $matches[1] ?? 'Field';
                                                    $oldVal = $matches[2] ?? '-';
                                                    $newVal = $matches[3] ?? '-';
                                                ?>
                                                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                                                        <div class="w-40 shrink-0">
                                                            <span class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest"><?= $fieldName ?></span>
                                                        </div>
                                                        <div class="flex flex-grow items-center gap-4">
                                                            <div class="flex-1 bg-white dark:bg-slate-900 p-3 rounded-xl border border-rose-100 dark:border-rose-900/30 shadow-sm text-center relative overflow-hidden">
                                                                <span class="text-[8px] absolute top-1 left-2 font-black text-rose-400 uppercase opacity-50">Original</span>
                                                                <span class="text-xs font-bold text-rose-600 line-through opacity-70"><?= $oldVal ?></span>
                                                            </div>
                                                            <i data-lucide="chevrons-right" class="w-5 h-5 text-blue-500 shrink-0 animate-pulse"></i>
                                                            <div class="flex-1 bg-white dark:bg-slate-900 p-3 rounded-xl border border-emerald-100 dark:border-emerald-900/30 shadow-md text-center relative overflow-hidden">
                                                                <span class="text-[8px] absolute top-1 left-2 font-black text-emerald-400 uppercase">Modified</span>
                                                                <span class="text-xs font-black text-emerald-600 dark:text-emerald-400"><?= $newVal ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="flex items-center gap-4">
                                                        <div class="w-2 h-2 rounded-full <?= str_contains($log['action'], 'Hapus') ? 'bg-rose-500' : 'bg-emerald-500' ?> shadow-lg"></div>
                                                        <p class="text-xs font-bold text-slate-700 dark:text-slate-300"><?= $d ?></p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="p-6 bg-slate-50 dark:bg-slate-950 border-t dark:border-slate-800 flex justify-between items-center">
                                    <p class="text-[9px] font-bold text-slate-400 italic">Audit Evidence ID: <?= md5($log['id'] . $log['created_at']) ?></p>
                                    <button onclick="toggleLogDetail('detail-<?= $log['id'] ?>')" class="text-[9px] font-black text-blue-600 uppercase tracking-widest hover:text-blue-800 transition-all">Close Forensic View</button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- ENHANCED PAGINATION -->
        <div class="p-10 bg-slate-50/50 dark:bg-slate-950/50 border-t dark:border-slate-800 flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-3 bg-white dark:bg-slate-900 p-1 rounded-2xl shadow-inner border border-slate-100 dark:border-slate-800">
                    <?php foreach([10, 25, 50] as $count): ?>
                        <button onclick="changePerPage(<?= $count ?>)" class="px-4 py-2 rounded-xl text-[10px] font-black uppercase transition-all <?= $perPage == $count ? 'bg-blue-950 text-white shadow-lg' : 'text-slate-400 hover:text-slate-600' ?>">
                            <?= $count ?>
                        </button>
                    <?php endforeach; ?>
                </div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Record: <span class="text-blue-600"><?= number_format($total) ?></span></span>
            </div>
            <div class="flex-grow flex justify-center">
                <?= $pager->links('group1', 'tailwind_full') ?>
            </div>
            <div class="hidden md:block min-w-[200px] text-right">
                <span class="text-[10px] font-bold text-slate-400 italic italic">Audit Cycle Finalized</span>
            </div>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();

    // --- 1. PULSE TREND CHART ---
    const trends = <?= json_encode($analytics['trend']) ?>;
    const trendChart = new ApexCharts(document.querySelector("#trendChart"), {
        series: [{ name: 'Aktivitas', data: trends.hourly.data }],
        chart: { type: 'area', height: 280, toolbar: { show: false }, fontFamily: 'Plus Jakarta Sans', sparkline: { enabled: false } },
        colors: ['#60a5fa'],
        stroke: { curve: 'smooth', width: 4, lineCap: 'round' },
        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 90, 100] } },
        xaxis: {
            categories: trends.hourly.labels,
            labels: { style: { colors: '#94a3b8', fontSize: '9px', fontWeight: 900 }, rotate: -45 },
            axisBorder: { show: false }, axisTicks: { show: false }
        },
        yaxis: { labels: { show: false } },
        grid: { borderColor: 'rgba(255,255,255,0.05)', strokeDashArray: 4, xaxis: { lines: { show: true } }, yaxis: { lines: { show: false } } },
        dataLabels: { enabled: false },
        tooltip: { theme: 'dark', x: { show: true }, y: { formatter: (v) => v + " Aksi" } }
    });
    trendChart.render();

    function updateTrend(range) {
        ['hourly', 'daily', 'monthly'].forEach(r => {
            const btn = document.getElementById(`tab-${r}`);
            if(r === range) btn.className = "px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all bg-white text-blue-950 shadow-lg";
            else btn.className = "px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all text-blue-300 hover:text-white";
        });
        trendChart.updateOptions({ series: [{ data: trends[range].data }], xaxis: { categories: trends[range].labels } });
    }

    // --- 2. AUTO REFRESH ---
    let refreshInterval;
    function toggleAutoRefresh() {
        const btn = document.getElementById('btn-refresh');
        const status = document.getElementById('refresh-status');
        const isRunning = localStorage.getItem('auto_refresh_logs') === 'true';
        if (!isRunning) {
            localStorage.setItem('auto_refresh_logs', 'true');
            btn.classList.add('bg-blue-50', 'dark:bg-blue-900/20', 'border-blue-200');
            status.classList.replace('bg-slate-300', 'bg-emerald-500'); status.classList.add('animate-pulse');
            refreshInterval = setInterval(() => window.location.reload(), 30000);
        } else {
            localStorage.setItem('auto_refresh_logs', 'false');
            btn.classList.remove('bg-blue-50', 'dark:bg-blue-900/20', 'border-blue-200');
            status.classList.replace('bg-emerald-500', 'bg-slate-300'); status.classList.remove('animate-pulse');
            clearInterval(refreshInterval);
        }
    }
    if (localStorage.getItem('auto_refresh_logs') === 'true') { localStorage.setItem('auto_refresh_logs', 'false'); toggleAutoRefresh(); }

    function confirmClearLogs() {
        if (confirm('Bersihkan Log? Seluruh riwayat aktivitas yang lebih dari 6 bulan akan dihapus permanen.')) {
            const loader = document.getElementById('page-loader');
            if (loader) { loader.classList.remove('opacity-0'); loader.classList.remove('pointer-events-none'); }
            window.location.href = '<?= base_url('logs/clear') ?>';
        }
    }

    function toggleLogDetail(id) { document.getElementById(id).classList.toggle('hidden'); lucide.createIcons(); }
    function changePerPage(count) {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', count);
        url.searchParams.set('page_group1', 1);
        window.location.href = url.href;
    }
</script>

<style>
    .custom-tooltip { background: #1e1b4b !important; color: white !important; border: none !important; border-radius: 12px !important; font-weight: 900 !important; font-size: 8px !important; text-transform: uppercase !important; }
    .apexcharts-canvas { margin: 0 auto; }
</style>
<?= $this->endSection() ?>
