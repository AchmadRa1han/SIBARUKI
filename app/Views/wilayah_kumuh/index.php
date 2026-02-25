<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
    <div class="p-8 border-b flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/50">
        <div>
            <h1 class="text-2xl font-black text-blue-950 tracking-tight">Daftar Wilayah Kumuh</h1>
            <p class="text-sm text-slate-400 font-medium">Pemantauan lokasi, statistik luas, dan skor kumuh wilayah</p>
        </div>
        <a href="<?= base_url('wilayah-kumuh/create') ?>" class="bg-blue-900 hover:bg-blue-950 text-white px-6 py-3 rounded-2xl transition-all flex items-center space-x-2 text-sm font-bold shadow-xl shadow-blue-900/20">
            <i data-lucide="plus-circle" class="w-5 h-5"></i>
            <span>Tambah Lokasi Baru</span>
        </a>
    </div>

    <div class="p-8">
        <?php if (session()->getFlashdata('message')) : ?>
            <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-6 py-4 rounded-2xl mb-8 flex items-center space-x-3 text-sm font-bold">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                <span><?= session()->getFlashdata('message') ?></span>
            </div>
        <?php endif; ?>

        <div class="overflow-x-auto rounded-[1.5rem] border border-slate-100 mb-8 shadow-inner bg-slate-50/30">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white text-blue-950 uppercase text-[10px] font-black tracking-[0.15em]">
                        <th class="p-5 border-b border-slate-100">Lokasi (Kel/Kec)</th>
                        <th class="p-5 border-b border-slate-100">RT / RW</th>
                        <th class="p-5 border-b border-slate-100">Luas (Ha)</th>
                        <th class="p-5 border-b border-slate-100">Skor Kumuh</th>
                        <th class="p-5 border-b border-slate-100">Kawasan</th>
                        <th class="p-5 border-b border-slate-100 text-center uppercase">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm text-slate-700 bg-white/50">
                    <?php foreach ($kumuh as $row) : ?>
                        <tr class="hover:bg-blue-50/50 transition-colors group">
                            <td class="p-5">
                                <p class="font-black text-slate-800 uppercase tracking-tight"><?= $row['Kelurahan'] ?></p>
                                <p class="text-[10px] font-bold text-blue-900/40 uppercase tracking-wider mt-0.5"><?= $row['Kecamatan'] ?></p>
                            </td>
                            <td class="p-5">
                                <span class="font-mono text-xs font-bold text-slate-600 bg-white px-2 py-1 rounded border border-slate-100 shadow-sm"><?= $row['Kode_RT_RW'] ?></span>
                            </td>
                            <td class="p-5 font-black text-blue-950">
                                <?= number_format($row['Luas_kumuh'], 2) ?> <span class="text-[10px] text-slate-400 italic">Ha</span>
                            </td>
                            <td class="p-5">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black bg-rose-50 text-rose-700 border border-rose-100 shadow-sm">
                                    <?= $row['skor_kumuh'] ?>
                                </span>
                            </td>
                            <td class="p-5">
                                <p class="text-xs font-bold text-slate-500 uppercase italic truncate w-32"><?= $row['Kawasan'] ?></p>
                            </td>
                            <td class="p-5 text-center">
                                <div class="flex justify-center items-center space-x-3">
                                    <a href="<?= base_url('wilayah-kumuh/detail/' . $row['FID']) ?>" 
                                       class="p-2.5 bg-blue-900 text-white rounded-xl shadow-lg shadow-blue-900/20 hover:bg-blue-950 hover:-translate-y-0.5 transition-all" 
                                       title="Lihat Laporan Lengkap">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <form action="<?= base_url('wilayah-kumuh/delete/' . $row['FID']) ?>" method="post" onsubmit="return confirm('Hapus data wilayah ini?')">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="p-2 text-rose-300 hover:text-rose-600 transition-colors">
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
