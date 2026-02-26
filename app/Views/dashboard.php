<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="space-y-8">
    <!-- Header Section -->
    <div>
        <h1 class="text-3xl font-extrabold text-blue-950 tracking-tight">Ringkasan Sistem</h1>
        <p class="text-slate-400 mt-1 font-medium italic">Data diperbarui secara real-time dari database SIBARUKI.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-blue-50 text-blue-900 rounded-2xl shadow-inner">
                    <i data-lucide="layers" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-blue-900 uppercase tracking-widest opacity-60">Total Tabel</p>
                    <p class="text-2xl font-black text-slate-800"><?= $totalTables ?></p>
                </div>
            </div>
            <div class="mt-4 text-[10px] font-bold text-slate-400 uppercase tracking-tight">Tabel Utama Terkelola</div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-indigo-50 text-indigo-900 rounded-2xl shadow-inner">
                    <i data-lucide="database" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-blue-900 uppercase tracking-widest opacity-60">Total Data</p>
                    <p class="text-2xl font-black text-slate-800"><?= number_format($totalData) ?></p>
                </div>
            </div>
            <div class="mt-4 text-[10px] font-bold text-slate-400 uppercase tracking-tight">Seluruh Entri Database</div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm opacity-50 cursor-not-allowed">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-emerald-50 text-emerald-900 rounded-2xl shadow-inner">
                    <i data-lucide="users" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-blue-900 uppercase tracking-widest">Pengguna</p>
                    <p class="text-2xl font-black text-slate-800">1</p>
                </div>
            </div>
            <div class="mt-4 text-[10px] font-bold text-slate-400 uppercase">Sesi Administrator</div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-rose-50 text-rose-900 rounded-2xl shadow-inner">
                    <i data-lucide="shield-check" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-blue-900 uppercase tracking-widest opacity-60">Status</p>
                    <p class="text-2xl font-black text-slate-800 tracking-tighter uppercase">STABIL</p>
                </div>
            </div>
            <div class="mt-4 flex items-center text-[9px] font-black text-emerald-600 uppercase tracking-widest">
                <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2 animate-pulse"></span>
                Sistem Online
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Aktivitas Terakhir -->
        <div class="lg:col-span-2 bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-8 border-b bg-slate-50/50 flex justify-between items-center">
                <h3 class="font-black text-blue-950 uppercase tracking-widest text-xs">Aktivitas Terakhir</h3>
                <i data-lucide="activity" class="w-4 h-4 text-slate-300"></i>
            </div>
            <div class="divide-y divide-slate-50">
                <?php if(empty($logs)): ?>
                    <div class="p-12 text-center text-slate-400 italic text-sm">
                        <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-3 opacity-20 text-slate-900"></i>
                        Belum ada aktivitas yang tercatat.
                    </div>
                <?php else: foreach($logs as $log): ?>
                    <div class="p-6 flex items-center space-x-5 hover:bg-slate-50 transition-colors group">
                        <div class="w-12 h-12 bg-white rounded-2xl border border-slate-100 flex items-center justify-center shrink-0 shadow-sm group-hover:shadow-md transition-all">
                            <?php 
                                $icon = 'edit-3'; $color = 'text-amber-500';
                                if($log['action'] == 'Tambah') { $icon = 'plus-circle'; $color = 'text-blue-500'; }
                                if($log['action'] == 'Hapus') { $icon = 'trash-2'; $color = 'text-rose-500'; }
                            ?>
                            <i data-lucide="<?= $icon ?>" class="w-5 h-5 <?= $color ?>"></i>
                        </div>
                        <div class="flex-grow">
                            <p class="text-sm font-black text-slate-800 uppercase tracking-tight"><?= $log['action'] ?> Data di <span class="text-blue-900 underline decoration-blue-100"><?= $log['table_name'] ?></span></p>
                            <p class="text-xs text-slate-400 font-medium mt-0.5 italic"><?= $log['description'] ?></p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-black text-slate-300 uppercase"><?= date('H:i', strtotime($log['created_at'])) ?></p>
                            <p class="text-[9px] font-bold text-slate-400 uppercase"><?= date('d M', strtotime($log['created_at'])) ?></p>
                        </div>
                    </div>
                <?php endforeach; endif; ?>
            </div>
        </div>

        <!-- Bantuan -->
        <div class="bg-blue-950 rounded-[2.5rem] p-10 text-white shadow-2xl relative overflow-hidden flex flex-col justify-between">
            <i data-lucide="help-circle" class="w-48 h-48 absolute -right-12 -bottom-12 opacity-5 rotate-12"></i>
            <div>
                <h3 class="text-2xl font-black mb-4 leading-tight tracking-tight">Butuh Bantuan Navigasi?</h3>
                <p class="text-blue-200 text-sm leading-relaxed font-medium italic">
                    Gunakan menu di samping kiri untuk mengelola Data Perumahan dan Kawasan Permukiman.
                </p>
            </div>
            <div class="mt-10 pt-10 border-t border-white/10">
                <button class="w-full bg-white text-blue-950 py-4 rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-blue-50 transition-all shadow-xl shadow-blue-950/50">
                    Hubungi Developer
                </button>
            </div>
        </div>
    </div>
</div>

<script>lucide.createIcons();</script>
<?= $this->endSection() ?>
