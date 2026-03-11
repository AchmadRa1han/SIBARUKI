<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="space-y-6 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-blue-950 dark:text-white uppercase tracking-tight">Rekapitulasi RTLH per Desa</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium text-sm">Ringkasan data Rumah Tidak Layak Huni berdasarkan wilayah desa.</p>
        </div>
        <div class="flex items-center gap-2">
            <div class="bg-blue-50 text-blue-600 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm border border-blue-100">
                <?= count($rekap ?? []) ?> Desa Terdata
            </div>
            <a href="<?= base_url('rtlh') ?>" class="bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm border border-slate-200 dark:border-slate-700 hover:bg-slate-50 transition-all flex items-center gap-2">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Kembali ke Data
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
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm transition-all hover:shadow-md group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/20 rounded-2xl flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform">
                    <i data-lucide="home" class="w-6 h-6"></i>
                </div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Target RTLH</span>
            </div>
            <h3 class="text-4xl font-black text-blue-950 dark:text-white"><?= number_format($totalRtlh) ?></h3>
            <p class="text-[10px] text-slate-400 font-bold uppercase mt-1">Unit Belum Tertangani</p>
        </div>

        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm transition-all hover:shadow-md group border-b-4 border-b-emerald-500">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl flex items-center justify-center text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition-transform">
                    <i data-lucide="check-circle" class="w-6 h-6"></i>
                </div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Capaian RLH</span>
            </div>
            <h3 class="text-4xl font-black text-emerald-600"><?= number_format($totalRlh) ?></h3>
            <p class="text-[10px] text-slate-400 font-bold uppercase mt-1">Unit Berhasil Ditransformasi</p>
        </div>

        <div class="bg-blue-950 p-8 rounded-[2.5rem] shadow-xl transition-all hover:shadow-blue-900/20 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center text-white group-hover:scale-110 transition-transform">
                    <i data-lucide="database" class="w-6 h-6"></i>
                </div>
                <span class="text-[10px] font-black text-white/40 uppercase tracking-widest">Total Database</span>
            </div>
            <h3 class="text-4xl font-black text-white"><?= number_format($totalSemua) ?></h3>
            <p class="text-[10px] text-white/60 font-bold uppercase mt-1">Total Entri Terverifikasi</p>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="p-6 border-b border-slate-50 dark:border-slate-800 flex flex-col lg:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-blue-950 rounded-2xl flex items-center justify-center text-white shadow-xl">
                    <i data-lucide="map" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-xs font-black text-blue-950 dark:text-white uppercase tracking-tight">Data Sebaran per Desa</h3>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Akumulasi jumlah unit per wilayah administratif</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/50 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                        <th class="px-8 py-4 w-20 text-center">No</th>
                        <th class="px-8 py-4">Nama Desa / Kelurahan</th>
                        <th class="px-8 py-4 text-center">ID Desa</th>
                        <th class="px-8 py-4 text-center">RTLH (Belum)</th>
                        <th class="px-8 py-4 text-center">RLH (Tuntas)</th>
                        <th class="px-8 py-4 text-center">Total Unit</th>
                        <th class="px-8 py-4 text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800 text-[10px]">
                    <?php if (!empty($rekap)): $no = 1; foreach($rekap as $item): ?>
                    <tr class="group hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-all duration-200">
                        <td class="px-8 py-4 text-center font-bold text-slate-400"><?= $no++ ?></td>
                        <td class="px-8 py-4">
                            <span class="font-black text-blue-950 dark:text-white uppercase"><?= $item['desa'] ?: 'Tidak Terdefinisi' ?></span>
                        </td>
                        <td class="px-8 py-4 text-center font-mono text-slate-500"><?= $item['desa_id'] ?: '-' ?></td>
                        <td class="px-8 py-4 text-center">
                            <span class="inline-block px-4 py-1.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl font-black text-xs border border-blue-100 dark:border-blue-800">
                                <?= $item['total_rtlh'] ?>
                            </span>
                        </td>
                        <td class="px-8 py-4 text-center">
                            <span class="inline-block px-4 py-1.5 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-xl font-black text-xs border border-emerald-100 dark:border-emerald-800">
                                <?= $item['total_rlh'] ?>
                            </span>
                        </td>
                        <td class="px-8 py-4 text-center">
                            <span class="font-black text-blue-950 dark:text-white"><?= $item['total_semua'] ?></span>
                        </td>
                        <td class="px-8 py-4 text-center">
                            <a href="<?= base_url('rtlh?keyword=' . urlencode($item['desa']) . '&status=semua') ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-950 text-white rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-black transition-all active:scale-95 shadow-lg shadow-blue-900/20">
                                <i data-lucide="list" class="w-3.5 h-3.5"></i> Lihat
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="7" class="px-8 py-12 text-center text-slate-400 font-bold uppercase text-[10px]">Data tidak tersedia</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
