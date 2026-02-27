<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-300">
    <div class="p-8 border-b dark:border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/50 dark:bg-slate-900/50">
        <div>
            <h1 class="text-2xl font-black text-blue-950 dark:text-white tracking-tight uppercase tracking-wider">Referensi Master</h1>
            <p class="text-sm text-slate-400 dark:text-slate-500 font-medium">Kelola daftar kategori dan pilihan referensi sistem</p>
        </div>
        <a href="<?= base_url('ref-master/create') ?>" class="bg-blue-900 dark:bg-blue-700 hover:bg-blue-950 dark:hover:bg-blue-600 text-white px-6 py-3 rounded-2xl transition-all flex items-center space-x-2 text-sm font-bold shadow-xl shadow-blue-900/20">
            <i data-lucide="plus-circle" class="w-5 h-5"></i>
            <span>Tambah Referensi</span>
        </a>
    </div>

    <div class="p-8">
        <?php if (session()->getFlashdata('message')) : ?>
            <div class="bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-100 dark:border-emerald-900 text-emerald-700 dark:text-emerald-400 px-6 py-4 rounded-2xl mb-8 flex items-center space-x-3 text-sm font-bold shadow-sm transition-colors duration-300">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                <span><?= session()->getFlashdata('message') ?></span>
            </div>
        <?php endif; ?>

        <div class="overflow-x-auto rounded-[1.5rem] border border-slate-100 dark:border-slate-800 mb-8 shadow-inner bg-slate-50/30 dark:bg-slate-950/50">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white dark:bg-slate-900 text-blue-950 dark:text-blue-400 uppercase text-[10px] font-black tracking-[0.15em] transition-colors duration-300">
                        <th class="p-5 border-b border-slate-100 dark:border-slate-800">ID</th>
                        <th class="p-5 border-b border-slate-100 dark:border-slate-800">Kategori</th>
                        <th class="p-5 border-b border-slate-100 dark:border-slate-800">Nama Pilihan</th>
                        <th class="p-5 border-b border-slate-100 dark:border-slate-800 text-center uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800 text-sm text-slate-700 dark:text-slate-400 bg-white/50 dark:bg-slate-900/50 transition-colors duration-300">
                    <?php foreach ($ref_master as $row) : ?>
                        <tr class="hover:bg-blue-50/50 dark:hover:bg-blue-900/10 transition-colors group">
                            <td class="p-5 font-mono text-[10px] font-bold text-slate-400 dark:text-slate-600">#<?= str_pad($row['id'], 3, '0', STR_PAD_LEFT) ?></td>
                            <td class="p-5">
                                <span class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border border-slate-200 dark:border-slate-700 transition-colors duration-300">
                                    <?= $row['kategori'] ?>
                                </span>
                            </td>
                            <td class="p-5 font-black text-slate-800 dark:text-slate-200 group-hover:text-blue-900 dark:group-hover:text-blue-400 transition-colors"><?= $row['nama_pilihan'] ?></td>
                            <td class="p-5">
                                <div class="flex justify-center items-center space-x-3">
                                    <a href="<?= base_url('ref-master/edit/' . $row['id']) ?>" class="p-2 text-slate-400 dark:text-slate-600 hover:text-blue-600 dark:hover:text-blue-400 transition-colors" title="Edit Data">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>
                                    <form action="<?= base_url('ref-master/delete/' . $row['id']) ?>" method="post" class="inline" onsubmit="return confirm('Hapus data referensi ini?')">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="p-2 text-slate-400 dark:text-slate-600 hover:text-rose-600 dark:hover:text-rose-400 transition-colors" title="Hapus Data">
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
        <div class="mt-6 flex justify-center">
            <?= $pager->links('group1', 'tailwind_full') ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
