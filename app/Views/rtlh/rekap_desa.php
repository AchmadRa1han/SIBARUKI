<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto space-y-8 pb-12">
    
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-3 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 no-print">
        <a href="<?= base_url('dashboard') ?>" class="hover:text-blue-600 transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <a href="<?= base_url('rtlh') ?>" class="hover:text-blue-600 transition-colors">RTLH</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">Rekapitulasi Desa</span>
    </nav>

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white dark:bg-slate-900 p-10 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm transition-all duration-300 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-blue-600/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
        <div class="relative z-10">
            <h1 class="text-3xl md:text-4xl font-black text-blue-950 dark:text-white uppercase tracking-tighter">Rekapitulasi Desa</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium text-sm mt-1">Ringkasan data Rumah Tidak Layak Huni berdasarkan wilayah administratif.</p>
        </div>
        <div class="flex items-center gap-3 relative z-10">
            <div class="bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 px-5 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest border border-blue-100 dark:border-blue-800/50 shadow-sm">
                <?= count($rekap ?? []) ?> Wilayah Terdata
            </div>
            <a href="<?= base_url('rtlh') ?>" class="px-6 py-3 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-slate-700 transition-all active:scale-95 flex items-center gap-3">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <?php 
            $totalSemua = array_sum(array_column($rekap, 'total_semua'));
            $totalRtlh = array_sum(array_column($rekap, 'total_rtlh'));
            $totalRlh = array_sum(array_column($rekap, 'total_rlh'));
        ?>
        <div class="bg-white dark:bg-slate-900 p-10 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm transition-all hover:shadow-xl hover:-translate-y-1 group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-600/5 rounded-full -mr-16 -mt-16 blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="flex items-center justify-between mb-6 relative z-10">
                <div class="w-14 h-14 bg-blue-50 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center text-blue-600 dark:text-blue-400 shadow-inner">
                    <i data-lucide="home" class="w-7 h-7"></i>
                </div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Target RTLH</span>
            </div>
            <h3 class="text-5xl font-black text-blue-950 dark:text-white tracking-tighter mb-2 relative z-10"><?= number_format($totalRtlh) ?></h3>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest relative z-10">Unit Belum Tertangani</p>
        </div>

        <div class="bg-white dark:bg-slate-900 p-10 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm transition-all hover:shadow-xl hover:-translate-y-1 group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-600/5 rounded-full -mr-16 -mt-16 blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="flex items-center justify-between mb-6 relative z-10">
                <div class="w-14 h-14 bg-emerald-50 dark:bg-emerald-900/30 rounded-2xl flex items-center justify-center text-emerald-600 dark:text-emerald-400 shadow-inner">
                    <i data-lucide="check-circle" class="w-7 h-7"></i>
                </div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Capaian RLH</span>
            </div>
            <h3 class="text-5xl font-black text-emerald-600 tracking-tighter mb-2 relative z-10"><?= number_format($totalRlh) ?></h3>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest relative z-10">Unit Berhasil Ditransformasi</p>
        </div>

        <div class="bg-blue-950 p-10 rounded-[2.5rem] shadow-2xl shadow-blue-950/20 transition-all hover:shadow-blue-950/40 hover:-translate-y-1 group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-400/10 rounded-full -mr-16 -mt-16 blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="flex items-center justify-between mb-6 relative z-10">
                <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center text-white shadow-inner">
                    <i data-lucide="database" class="w-7 h-7"></i>
                </div>
                <span class="text-[10px] font-black text-blue-300/40 uppercase tracking-[0.2em]">Total Database</span>
            </div>
            <h3 class="text-5xl font-black text-white tracking-tighter mb-2 relative z-10"><?= number_format($totalSemua) ?></h3>
            <p class="text-[10px] text-blue-300/60 font-bold uppercase tracking-widest relative z-10">Total Entri Terverifikasi</p>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden relative">
        <div class="p-8 border-b border-slate-50 dark:border-slate-800">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-blue-600/20">
                    <i data-lucide="map" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="text-lg font-black text-blue-950 dark:text-white uppercase tracking-tight">Sebaran Data per Desa</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em]">Akumulasi jumlah unit per wilayah administratif</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/50 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                        <th class="px-8 py-5 w-20 text-center">No</th>
                        <th class="px-8 py-5">Nama Desa / Kelurahan</th>
                        <th class="px-8 py-5 text-center">ID Desa</th>
                        <th class="px-8 py-5 text-center">RTLH (Belum)</th>
                        <th class="px-8 py-5 text-center">RLH (Tuntas)</th>
                        <th class="px-8 py-5 text-center">Total Unit</th>
                        <th class="px-8 py-5 text-center w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800 text-[11px]">
                    <?php if (!empty($rekap)): $no = 1; foreach($rekap as $item): ?>
                    <tr class="group hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-all duration-300">
                        <td class="px-8 py-5 text-center font-bold text-slate-400"><?= str_pad($no++, 2, '0', STR_PAD_LEFT) ?></td>
                        <td class="px-8 py-5">
                            <span class="font-black text-blue-950 dark:text-white uppercase tracking-wider"><?= $item['desa'] ?: 'Tidak Terdefinisi' ?></span>
                        </td>
                        <td class="px-8 py-5 text-center font-mono font-bold text-slate-500"><?= $item['desa_id'] ?: '-' ?></td>
                        <td class="px-8 py-5 text-center">
                            <span class="inline-block px-5 py-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-2xl font-black text-sm border border-blue-100 dark:border-blue-800 shadow-sm">
                                <?= $item['total_rtlh'] ?>
                            </span>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <span class="inline-block px-5 py-2 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-2xl font-black text-sm border border-emerald-100 dark:border-emerald-800 shadow-sm">
                                <?= $item['total_rlh'] ?>
                            </span>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <span class="font-black text-blue-950 dark:text-white text-lg"><?= $item['total_semua'] ?></span>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <a href="<?= base_url('rtlh?keyword=' . urlencode($item['desa']) . '&status=semua') ?>" class="inline-flex items-center gap-3 px-6 py-3 bg-blue-950 dark:bg-blue-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:scale-105 transition-all active:scale-95 shadow-xl shadow-blue-900/20">
                                <i data-lucide="list" class="w-4 h-4"></i> Rincian
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                        <tr>
                            <td colspan="7" class="px-8 py-24 text-center">
                                <div class="flex flex-col items-center justify-center opacity-40">
                                    <i data-lucide="package-search" class="w-16 h-16 mb-4"></i>
                                    <p class="font-black uppercase text-[10px] tracking-[0.3em]">Data Tidak Ditemukan</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
