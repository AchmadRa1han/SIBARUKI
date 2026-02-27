<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-300">
    <div class="p-8 border-b dark:border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/50 dark:bg-slate-900/50">
        <div>
            <h1 class="text-2xl font-black text-blue-950 dark:text-white tracking-tight">Daftar Wilayah Kumuh</h1>
            <p class="text-sm text-slate-400 dark:text-slate-500 font-medium">Pemantauan lokasi, statistik luas, dan skor kumuh wilayah</p>
        </div>
        <a href="<?= base_url('wilayah-kumuh/create') ?>" class="bg-blue-900 dark:bg-blue-700 hover:bg-blue-950 dark:hover:bg-blue-600 text-white px-6 py-3 rounded-2xl transition-all flex items-center space-x-2 text-sm font-bold shadow-xl shadow-blue-900/20">
            <i data-lucide="plus-circle" class="w-5 h-5"></i>
            <span>Tambah Lokasi Baru</span>
        </a>
    </div>

    <div class="p-8">
        <?php if (session()->getFlashdata('message')) : ?>
            <div class="bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-100 dark:border-emerald-900 text-emerald-700 dark:text-emerald-400 px-6 py-4 rounded-2xl mb-8 flex items-center space-x-3 text-sm font-bold shadow-sm">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                <span><?= session()->getFlashdata('message') ?></span>
            </div>
        <?php endif; ?>

        <div class="overflow-x-auto rounded-[1.5rem] border border-slate-100 dark:border-slate-800 mb-8 shadow-inner bg-slate-50/30 dark:bg-slate-950/50">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white dark:bg-slate-900 text-blue-950 dark:text-blue-400 uppercase text-[10px] font-black tracking-[0.15em] transition-colors duration-300">
                        <th class="p-5 border-b border-slate-100 dark:border-slate-800">Lokasi (Kel/Kec)</th>
                        <th class="p-5 border-b border-slate-100 dark:border-slate-800">RT / RW</th>
                        <th class="p-5 border-b border-slate-100 dark:border-slate-800">Luas (Ha)</th>
                        <th class="p-5 border-b border-slate-100 dark:border-slate-800">Skor Kumuh</th>
                        <th class="p-5 border-b border-slate-100 dark:border-slate-800">Kawasan</th>
                        <th class="p-5 border-b border-slate-100 dark:border-slate-800 text-center uppercase">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800 text-sm text-slate-700 dark:text-slate-400 bg-white/50 dark:bg-slate-900/50 transition-colors duration-300">
                    <?php foreach ($kumuh as $row) : ?>
                        <tr class="hover:bg-blue-50/50 dark:hover:bg-blue-900/10 transition-colors group">
                            <td class="p-5">
                                <p class="font-black text-slate-800 dark:text-slate-200 uppercase tracking-tight group-hover:text-blue-900 dark:group-hover:text-blue-400 transition-colors"><?= $row['Kelurahan'] ?></p>
                                <p class="text-[10px] font-bold text-blue-900/40 dark:text-blue-500/40 uppercase tracking-wider mt-0.5"><?= $row['Kecamatan'] ?></p>
                            </td>
                            <td class="p-5">
                                <span class="font-mono text-xs font-bold text-slate-600 dark:text-slate-400 bg-white dark:bg-slate-800 px-2 py-1 rounded border border-slate-100 dark:border-slate-700 shadow-sm"><?= $row['Kode_RT_RW'] ?></span>
                            </td>
                            <td class="p-5 font-black text-blue-950 dark:text-slate-200">
                                <?= number_format($row['Luas_kumuh'], 2) ?> <span class="text-[10px] text-slate-400 dark:text-slate-500 italic">Ha</span>
                            </td>
                            <td class="p-5">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black bg-rose-50 dark:bg-rose-950/30 text-rose-700 dark:text-rose-400 border border-rose-100 dark:border-rose-900 shadow-sm">
                                    <?= $row['skor_kumuh'] ?>
                                </span>
                            </td>
                            <td class="p-5">
                                <p class="text-xs font-bold text-slate-500 dark:text-slate-500 uppercase italic truncate w-32"><?= $row['Kawasan'] ?></p>
                            </td>
                            <td class="p-5 text-center">
                                <div class="flex justify-center items-center space-x-3">
                                    <a href="<?= base_url('wilayah-kumuh/detail/' . $row['FID']) ?>" 
                                       class="p-2.5 bg-blue-900 dark:bg-blue-700 text-white rounded-xl shadow-lg shadow-blue-900/20 hover:bg-blue-950 dark:hover:bg-blue-600 hover:-translate-y-0.5 transition-all" 
                                       title="Lihat Laporan Lengkap">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <form action="<?= base_url('wilayah-kumuh/delete/' . $row['FID']) ?>" method="post" onsubmit="return confirm('Hapus data wilayah ini?')">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="p-2 text-rose-300 dark:text-rose-900 hover:text-rose-600 dark:hover:text-rose-400 transition-colors">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4 flex justify-center">
            <?= $pager->links('group1', 'tailwind_full') ?>
        </div>
    </div>
</div>

<script>lucide.createIcons();</script>
<?= $this->endSection() ?>
