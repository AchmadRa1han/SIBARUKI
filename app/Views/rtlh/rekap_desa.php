<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto space-y-6 pb-12 text-slate-900 dark:text-slate-200">
    
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-3 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 no-print">
        <a href="<?= base_url('dashboard') ?>" class="hover:text-blue-600 transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <a href="<?= base_url('rtlh') ?>" class="hover:text-blue-600 transition-colors">RTLH</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">Rekapitulasi Desa</span>
    </nav>

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm transition-all duration-300 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-48 h-48 bg-blue-600/5 rounded-full -mr-24 -mt-24 blur-3xl"></div>
        <div class="relative z-10">
            <h1 class="text-2xl md:text-3xl font-black text-blue-950 dark:text-white uppercase tracking-tighter">Rekapitulasi Desa</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium text-xs mt-1">Ringkasan data Rumah Tidak Layak Huni berdasarkan wilayah administratif.</p>
        </div>
        <div class="flex items-center gap-3 relative z-10">
            <div class="bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest border border-blue-100 dark:border-blue-800/50 shadow-sm">
                <?= count($rekap ?? []) ?> Wilayah
            </div>
            <a href="<?= base_url('rtlh') ?>" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-slate-700 transition-all active:scale-95 flex items-center gap-2">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php 
            $totalSemua = array_sum(array_column($rekap, 'total_semua'));
            $totalRtlh = array_sum(array_column($rekap, 'total_rtlh'));
            $totalRlh = array_sum(array_column($rekap, 'total_rlh'));
        ?>
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm transition-all hover:shadow-md group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-blue-600/5 rounded-full -mr-12 -mt-12 blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="flex items-center justify-between mb-6 relative z-10">
                <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/30 rounded-xl flex items-center justify-center text-blue-600 dark:text-blue-400 shadow-inner">
                    <i data-lucide="home" class="w-6 h-6"></i>
                </div>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Target RTLH</span>
            </div>
            <h3 class="text-4xl font-black text-blue-950 dark:text-white tracking-tighter mb-1 relative z-10"><?= number_format($totalRtlh) ?></h3>
            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest relative z-10">Unit Belum Tertangani</p>
        </div>

        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm transition-all hover:shadow-md group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-600/5 rounded-full -mr-12 -mt-12 blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="flex items-center justify-between mb-6 relative z-10">
                <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center text-emerald-600 dark:text-emerald-400 shadow-inner">
                    <i data-lucide="check-circle" class="w-6 h-6"></i>
                </div>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Capaian RLH</span>
            </div>
            <h3 class="text-4xl font-black text-emerald-600 tracking-tighter mb-1 relative z-10"><?= number_format($totalRlh) ?></h3>
            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest relative z-10">Unit Berhasil Ditransformasi</p>
        </div>

        <div class="bg-blue-950 p-8 rounded-[2rem] shadow-xl shadow-blue-950/20 transition-all hover:-translate-y-1 group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-blue-400/10 rounded-full -mr-12 -mt-12 blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="flex items-center justify-between mb-6 relative z-10">
                <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center text-white">
                    <i data-lucide="database" class="w-6 h-6"></i>
                </div>
                <span class="text-[9px] font-black text-blue-300/40 uppercase tracking-[0.2em]">Total Data</span>
            </div>
            <h3 class="text-4xl font-black text-white tracking-tighter mb-1 relative z-10"><?= number_format($totalSemua) ?></h3>
            <p class="text-[9px] text-blue-300/60 font-bold uppercase tracking-widest relative z-10">Total Database Terverifikasi</p>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white dark:bg-slate-900 rounded-[2rem] shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden relative">
        <div class="p-6 border-b border-slate-50 dark:border-slate-800 flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-600/20">
                <i data-lucide="map" class="w-5 h-5"></i>
            </div>
            <div>
                <h3 class="text-sm font-black text-blue-950 dark:text-white uppercase tracking-tight">Sebaran Data per Desa</h3>
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-[0.2em]">Akumulasi jumlah unit per wilayah administratif</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/50 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                        <th class="px-6 py-4 w-16 text-center">No</th>
                        <th class="px-6 py-4">Nama Desa / Kelurahan</th>
                        <th class="px-6 py-4 text-center">ID Desa</th>
                        <th class="px-6 py-4 text-center">RTLH</th>
                        <th class="px-6 py-4 text-center">RLH</th>
                        <th class="px-6 py-4 text-center">Total</th>
                        <th class="px-6 py-4 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800 text-[10px]">
                    <?php if (!empty($rekap)): $no = 1; foreach($rekap as $item): ?>
                    <tr class="group hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-all duration-200">
                        <td class="px-6 py-4 text-center font-bold text-slate-400"><?= str_pad($no++, 2, '0', STR_PAD_LEFT) ?></td>
                        <td class="px-6 py-4">
                            <span class="font-black text-blue-950 dark:text-white uppercase tracking-wider text-xs"><?= $item['desa'] ?: 'N/A' ?></span>
                        </td>
                        <td class="px-6 py-4 text-center font-mono font-bold text-slate-500"><?= $item['desa_id'] ?: '-' ?></td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-block px-3 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg font-black text-xs border border-blue-100 dark:border-blue-800 shadow-sm">
                                <?= $item['total_rtlh'] ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-block px-3 py-1 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-lg font-black text-xs border border-emerald-100 dark:border-emerald-800 shadow-sm">
                                <?= $item['total_rlh'] ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="font-black text-blue-950 dark:text-white text-base"><?= $item['total_semua'] ?></span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="<?= base_url('rtlh?keyword=' . urlencode($item['desa']) . '&status=semua') ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-950 dark:bg-blue-600 text-white rounded-xl text-[9px] font-black uppercase tracking-widest hover:scale-105 transition-all active:scale-95 shadow-lg shadow-blue-900/20">
                                <i data-lucide="list" class="w-3.5 h-3.5"></i> Rincian
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="7" class="px-8 py-16 text-center text-slate-400 font-bold uppercase text-[9px]">Data tidak ditemukan</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
