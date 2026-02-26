<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="space-y-10">
    <!-- Header Section -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-black text-blue-950 tracking-tight">Dashboard Kontrol</h1>
            <p class="text-slate-400 text-sm font-medium italic">Pusat informasi pemantauan data perumahan Kabupaten Sinjai.</p>
        </div>
        <div class="flex items-center gap-4">
            <!-- Signal Indicator -->
            <div id="ping-status" class="bg-white border border-slate-100 shadow-sm px-4 py-2 rounded-2xl flex items-center gap-3">
                <div class="flex gap-0.5 items-end h-3">
                    <div class="w-1 bg-slate-200 rounded-full h-1 bar-1 transition-all duration-500"></div>
                    <div class="w-1 bg-slate-200 rounded-full h-2 bar-2 transition-all duration-500"></div>
                    <div class="w-1 bg-slate-200 rounded-full h-3 bar-3 transition-all duration-500"></div>
                </div>
                <span id="ping-text" class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Checking...</span>
            </div>
            <!-- Waktu & Tanggal Real-time -->
            <div class="bg-white border border-slate-100 shadow-sm px-5 py-2 rounded-2xl flex items-center gap-4">
                <div class="flex items-center gap-2 border-r border-slate-100 pr-4">
                    <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400"></i>
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest"><?= date('d M Y') ?></span>
                </div>
                <div class="flex items-center gap-2">
                    <i data-lucide="clock" class="w-3.5 h-3.5 text-blue-600"></i>
                    <span id="digital-clock" class="text-[10px] font-black text-blue-900 tracking-[0.2em]">00:00:00</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Stats Grid (2 Kolom) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- RTLH -->
        <div class="bg-white p-10 rounded-[3rem] border border-slate-100 shadow-sm hover:shadow-2xl hover:-translate-y-1 transition-all duration-500 relative overflow-hidden group">
            <div class="relative z-10">
                <div class="p-5 bg-amber-50 text-amber-600 rounded-2xl w-fit mb-8 shadow-inner group-hover:scale-110 transition-transform duration-500">
                    <i data-lucide="home" class="w-10 h-10"></i>
                </div>
                <p class="text-[12px] font-black text-amber-600 uppercase tracking-[0.3em] mb-2">Total RTLH Terdata</p>
                <div class="flex items-baseline gap-3">
                    <h2 class="text-5xl font-black text-slate-800"><?= number_format($totalRtlh) ?></h2>
                    <span class="text-sm font-bold text-slate-400 uppercase tracking-widest">Unit Rumah</span>
                </div>
            </div>
            <i data-lucide="home" class="w-48 h-48 absolute -right-10 -bottom-10 opacity-[0.03] text-slate-900 rotate-12 group-hover:rotate-0 transition-all duration-700"></i>
        </div>

        <!-- Wilayah Kumuh -->
        <div class="bg-white p-10 rounded-[3rem] border border-slate-100 shadow-sm hover:shadow-2xl hover:-translate-y-1 transition-all duration-500 relative overflow-hidden group">
            <div class="relative z-10">
                <div class="p-5 bg-rose-50 text-rose-600 rounded-2xl w-fit mb-8 shadow-inner group-hover:scale-110 transition-transform duration-500">
                    <i data-lucide="map-pin" class="w-10 h-10"></i>
                </div>
                <p class="text-[12px] font-black text-rose-600 uppercase tracking-[0.3em] mb-2">Lokasi Wilayah Kumuh</p>
                <div class="flex items-baseline gap-3">
                    <h2 class="text-5xl font-black text-slate-800"><?= number_format($totalKumuh) ?></h2>
                    <span class="text-sm font-bold text-slate-400 uppercase tracking-widest">Titik Kawasan</span>
                </div>
            </div>
            <i data-lucide="map-pin" class="w-48 h-48 absolute -right-10 -bottom-10 opacity-[0.03] text-slate-900 -rotate-12 group-hover:rotate-0 transition-all duration-700"></i>
        </div>
    </div>

    <!-- Health Check & System Info -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 flex items-center justify-between">
            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Database</span>
            <div class="flex items-center gap-2">
                <span class="text-[10px] font-bold text-slate-700"><?= $dbStatus ?></span>
                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
            </div>
        </div>
        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 flex items-center justify-between">
            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Beban Server</span>
            <span class="text-[10px] font-bold text-slate-700"><?= $serverLoad ?></span>
        </div>
        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 flex items-center justify-between">
            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">PHP Env</span>
            <span class="text-[10px] font-bold text-slate-700">v<?= $phpVersion ?></span>
        </div>
        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 flex items-center justify-between">
            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Sistem OS</span>
            <span class="text-[10px] font-bold text-slate-700"><?= PHP_OS ?></span>
        </div>
    </div>

    <!-- Log Aktivitas -->
    <div class="grid grid-cols-1 gap-10">
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b bg-slate-50/50 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 bg-blue-600 rounded-full"></div>
                    <h3 class="font-black text-blue-950 uppercase tracking-widest text-xs">Log Aktivitas Terbaru</h3>
                </div>
                <i data-lucide="activity" class="w-4 h-4 text-slate-300"></i>
            </div>
            <div class="divide-y divide-slate-50">
                <?php if(empty($logs)): ?>
                    <div class="p-12 text-center text-slate-400 italic text-sm">
                        <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-3 opacity-20 text-slate-900"></i>
                        Belum ada aktivitas tercatat hari ini.
                    </div>
                <?php else: foreach($logs as $log): ?>
                    <div class="p-5 flex items-center space-x-5 hover:bg-blue-50/30 transition-all duration-300 group cursor-default">
                        <div class="w-11 h-11 bg-white rounded-2xl border border-slate-100 flex items-center justify-center shrink-0 shadow-sm group-hover:shadow-md transition-all">
                            <?php 
                                $icon = 'edit-3'; $color = 'text-amber-500';
                                if($log['action'] == 'Tambah') { $icon = 'plus-circle'; $color = 'text-blue-500'; }
                                if($log['action'] == 'Hapus') { $icon = 'trash-2'; $color = 'text-rose-500'; }
                            ?>
                            <i data-lucide="<?= $icon ?>" class="w-5 h-5 <?= $color ?>"></i>
                        </div>
                        <div class="flex-grow">
                            <p class="text-sm font-black text-slate-800 uppercase tracking-tight"><?= $log['action'] ?> Data <span class="text-blue-900"><?= $log['table_name'] ?></span></p>
                            <p class="text-xs text-slate-500 font-bold mt-1 flex items-center gap-2">
                                <span class="bg-blue-900 text-white px-2 py-0.5 rounded text-[8px] uppercase tracking-tighter"><?= $log['user'] ?></span>
                                <span class="italic font-medium text-slate-400"><?= $log['description'] ?></span>
                            </p>
                        </div>
                        <div class="text-right shrink-0">
                            <!-- Waktu Sinkron: Hanya Jam & Menit -->
                            <p class="text-[11px] font-black text-blue-900 uppercase leading-none"><?= date('H:i', strtotime($log['created_at'])) ?></p>
                            <p class="text-[8px] font-bold text-slate-400 uppercase tracking-tighter mt-1"><?= date('d M', strtotime($log['created_at'])) ?></p>
                        </div>
                    </div>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();

    // 1. DIGITAL CLOCK SCRIPT
    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        document.getElementById('digital-clock').textContent = `${hours}:${minutes}:${seconds}`;
    }
    setInterval(updateClock, 1000);
    updateClock();

    // 2. PING / SIGNAL DETECTOR
    async function checkPing() {
        const start = Date.now();
        const bars = [document.querySelector('.bar-1'), document.querySelector('.bar-2'), document.querySelector('.bar-3')];
        const pingText = document.getElementById('ping-text');
        
        try {
            await fetch('<?= base_url("favicon.ico") ?>', { mode: 'no-cors', cache: 'no-store' });
            const latency = Date.now() - start;
            
            bars.forEach(b => b.className = b.className.replace(/bg-(emerald|amber|rose)-500/g, 'bg-slate-200'));
            
            if (latency < 150) {
                bars.forEach(b => b.classList.add('bg-emerald-500'));
                pingText.innerText = 'Signal: Excellent';
                pingText.className = pingText.className.replace(/text-(slate|amber|rose)-400/g, 'text-emerald-500');
            } else if (latency < 400) {
                bars[0].classList.add('bg-amber-500'); bars[1].classList.add('bg-amber-500');
                pingText.innerText = 'Signal: Good';
                pingText.className = pingText.className.replace(/text-(slate|emerald|rose)-400/g, 'text-amber-500');
            } else {
                bars[0].classList.add('bg-rose-500');
                pingText.innerText = 'Signal: Poor';
                pingText.className = pingText.className.replace(/text-(slate|emerald|amber)-400/g, 'text-rose-500');
            }
        } catch (e) {
            pingText.innerText = 'Signal: Offline';
        }
    }
    checkPing();
    setInterval(checkPing, 10000);
</script>
<?= $this->endSection() ?>
