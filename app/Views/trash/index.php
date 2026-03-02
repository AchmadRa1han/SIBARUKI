<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-blue-950 dark:text-white uppercase tracking-tight">Recycle Bin</h1>
            <p class="text-slate-400 dark:text-slate-500 text-sm font-medium italic">Data yang dihapus tersimpan di sini sebelum dibuang permanen.</p>
        </div>
        <div class="px-6 py-3 bg-rose-50 dark:bg-rose-950/30 rounded-2xl border border-rose-100 dark:border-rose-900">
            <p class="text-[8px] font-black text-rose-600 dark:text-rose-400 uppercase tracking-widest">Total Sampah</p>
            <p class="text-lg font-black text-rose-700 dark:text-rose-200"><?= count($trash) ?> Item Terdeteksi</p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-[3rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-950/50 border-b dark:border-slate-800">
                        <th class="p-6 text-[10px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-widest">Tipe Data</th>
                        <th class="p-6 text-[10px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-widest">Waktu Hapus</th>
                        <th class="p-6 text-[10px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-widest">Dihapus Oleh</th>
                        <th class="p-6 text-[10px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-widest text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                    <?php if(empty($trash)): ?>
                        <tr><td colspan="4" class="p-20 text-center text-slate-400 italic">Recycle Bin kosong.</td></tr>
                    <?php endif; ?>
                    <?php foreach($trash as $item): ?>
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="p-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 flex items-center justify-center font-black text-xs"><?= substr($item['entity_type'], 0, 1) ?></div>
                                <div>
                                    <p class="text-xs font-black text-slate-800 dark:text-slate-200 uppercase"><?= $item['entity_type'] ?></p>
                                    <p class="text-[9px] font-bold text-slate-400">Orig ID: <?= $item['entity_id'] ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="p-6">
                            <p class="text-xs font-black text-slate-700 dark:text-slate-300"><?= date('d M Y', strtotime($item['created_at'])) ?></p>
                            <p class="text-[9px] font-bold text-slate-400 uppercase"><?= date('H:i:s', strtotime($item['created_at'])) ?></p>
                        </td>
                        <td class="p-6">
                            <span class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase"><?= $item['deleted_by'] ?></span>
                        </td>
                        <td class="p-6">
                            <div class="flex justify-center gap-3">
                                <button onclick="handleRestore('<?= base_url('trash/restore/'.$item['id']) ?>')" class="px-4 py-2 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 rounded-xl text-[10px] font-black uppercase tracking-widest border border-emerald-100 dark:border-emerald-900 hover:bg-emerald-600 hover:text-white transition-all">
                                    Restore
                                </button>
                                <button onclick="handleDeletePerm('<?= base_url('trash/delete-perm/'.$item['id']) ?>')" class="px-4 py-2 bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 rounded-xl text-[10px] font-black uppercase tracking-widest border border-rose-100 dark:border-rose-900 hover:bg-rose-600 hover:text-white transition-all">
                                    Permanent
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    async function handleRestore(url) {
        const ok = await customConfirm('Pulihkan Data?', 'Data akan dikembalikan ke daftar utama.', 'info');
        if (ok) window.location.href = url;
    }

    async function handleDeletePerm(url) {
        const ok = await customConfirm('Hapus Permanen?', 'Data yang sudah dihapus tidak dapat dikembalikan lagi.', 'danger');
        if (ok) window.location.href = url;
    }
</script>
<?= $this->endSection() ?>
