<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto space-y-6 pb-24 text-slate-900 dark:text-slate-200">
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-3 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 no-print">
        <a href="<?= base_url('dashboard') ?>" class="hover:text-blue-600 transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">Recycle Bin</span>
    </nav>

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-blue-950 p-7 rounded-[2.5rem] text-white shadow-2xl shadow-blue-950/20 relative overflow-hidden transition-all duration-500">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
        <div class="relative z-10 flex items-center gap-5">
            <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-md border border-white/10 shadow-inner">
                <i data-lucide="trash-2" class="w-6 h-6 text-rose-400"></i>
            </div>
            <div>
                <h1 class="text-2xl md:text-3xl font-black uppercase tracking-tighter leading-none">Recycle Bin</h1>
                <p class="text-white/60 font-medium text-xs mt-2 tracking-wide">Data yang dihapus tersimpan di sini sebelum dibuang permanen</p>
            </div>
        </div>
        <div class="flex items-center gap-3 relative z-10">
            <?php if(!empty($trash)): ?>
            <button onclick="handleEmptyTrash()" class="bg-rose-500 text-white px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest shadow-xl hover:bg-rose-600 active:scale-95 transition-all flex items-center gap-2 group border border-white/10">
                <i data-lucide="zap" class="w-4 h-4"></i> Kosongkan Sampah
            </button>
            <?php endif; ?>
        </div>
    </div>

    <form id="form-bulk-trash" method="POST" class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-xl overflow-hidden relative">
        <?= csrf_field() ?>
        <div class="p-8 border-b border-slate-50 dark:border-slate-800 flex items-center gap-4 bg-slate-50/50 dark:bg-slate-900/50">
            <div class="w-12 h-12 bg-rose-50 dark:bg-rose-950/30 rounded-2xl flex items-center justify-center text-rose-600 shadow-sm">
                <i data-lucide="database-zap" class="w-6 h-6"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-blue-950 dark:text-white uppercase tracking-tight">Karantina Data</h3>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em]"><?= count($trash) ?> Item Terdeteksi</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
                        <th class="px-8 py-5 w-12 text-center">
                            <input type="checkbox" id="check-all" class="w-4.5 h-4.5 rounded-lg border-2 border-slate-200 text-blue-600 focus:ring-blue-600/20 cursor-pointer">
                        </th>
                        <th class="px-8 py-5">Tipe Data & Identifier</th>
                        <th class="px-8 py-5">Waktu Penghapusan</th>
                        <th class="px-8 py-5">Otoritas Pengolah</th>
                        <th class="px-8 py-5 text-center">Aksi Pemulihan</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-slate-800">
                    <?php if(empty($trash)): ?>
                        <tr><td colspan="5" class="px-8 py-20 text-center opacity-20 font-black uppercase text-xs tracking-[0.3em]">Recycle Bin Kosong</td></tr>
                    <?php endif; ?>
                    <?php foreach($trash as $item): ?>
                    <tr class="group hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-all duration-300">
                        <td class="px-8 py-6 text-center">
                            <input type="checkbox" name="ids[]" value="<?= $item['id'] ?>" class="row-checkbox w-4.5 h-4.5 rounded-lg border-2 border-slate-200 text-blue-600 focus:ring-blue-600/20 cursor-pointer transition-all">
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center font-black text-xs border border-blue-100 dark:border-blue-900"><?= substr($item['entity_type'], 0, 1) ?></div>
                                <div>
                                    <p class="text-sm font-black text-blue-950 dark:text-white uppercase tracking-tight"><?= $item['entity_type'] ?></p>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Original ID: #<?= $item['entity_id'] ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <p class="text-sm font-black text-slate-700 dark:text-slate-300 uppercase"><?= date('d M Y', strtotime($item['created_at'])) ?></p>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest"><?= date('H:i:s', strtotime($item['created_at'])) ?></p>
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-lg font-black text-[9px] uppercase tracking-widest border border-slate-200 dark:border-slate-700">
                                <?= $item['deleted_by'] ?>
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex justify-center gap-3">
                                <button type="button" onclick="handleRestore('<?= base_url('trash/restore/'.$item['id']) ?>')" class="px-5 py-2 bg-emerald-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-emerald-600/20 hover:scale-105 active:scale-95 transition-all">
                                    Restore
                                </button>
                                <button type="button" onclick="handleDeletePerm('<?= base_url('trash/delete-perm/'.$item['id']) ?>')" class="px-5 py-2 bg-rose-500 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-rose-500/20 hover:scale-105 active:scale-95 transition-all">
                                    Permanent
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Multi-select Action Bar -->
        <div id="bulk-action-bar" class="hidden fixed bottom-8 left-1/2 -translate-x-1/2 z-[5000] bg-blue-950 text-white px-8 py-4 rounded-3xl shadow-2xl flex items-center gap-6 border border-white/10 backdrop-blur-xl">
            <div class="flex items-center gap-3 pr-6 border-r border-white/10">
                <span id="selected-count" class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-[10px] font-black">0</span>
                <span class="text-[9px] font-bold uppercase tracking-widest">Data Terpilih</span>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="bulkRestore()" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg transition-all active:scale-95 flex items-center gap-2">
                    <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> Restore Terpilih
                </button>
                <button type="button" onclick="bulkDeletePerm()" class="px-6 py-2.5 bg-rose-500 hover:bg-rose-600 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg transition-all active:scale-95 flex items-center gap-2">
                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Hapus Permanen
                </button>
                <button type="button" onclick="clearSelection()" class="px-6 py-2.5 bg-white/10 hover:bg-white/20 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">Batal</button>
            </div>
        </div>
    </form>
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

    async function handleEmptyTrash() {
        const ok = await customConfirm('Kosongkan Recycle Bin?', 'Semua data di dalam Recycle Bin akan dihapus PERMANEN dan tidak bisa dipulihkan!', 'danger');
        if (ok) window.location.href = '<?= base_url('trash/empty') ?>';
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Selection Logic
        const checkAll = document.getElementById('check-all');
        const rows = document.querySelectorAll('.row-checkbox');
        const bar = document.getElementById('bulk-action-bar');
        const countDisplay = document.getElementById('selected-count');

        const updateBar = () => {
            const checked = document.querySelectorAll('.row-checkbox:checked').length;
            countDisplay.innerText = checked;
            bar.classList.toggle('hidden', checked === 0);
            if (checked > 0) {
                if (window.lucide) lucide.createIcons();
            }
        };

        checkAll?.addEventListener('change', () => { 
            rows.forEach(r => r.checked = checkAll.checked); 
            updateBar(); 
        });

        rows.forEach(r => r.addEventListener('change', updateBar));

        window.clearSelection = () => { 
            rows.forEach(r => r.checked = false); 
            if(checkAll) checkAll.checked = false; 
            updateBar(); 
        };
    });

    function bulkRestore() {
        customConfirm('Restore Data Terpilih?', 'Semua data yang dipilih akan dikembalikan ke daftar utama.', 'info').then(ok => {
            if (ok) {
                const form = document.getElementById('form-bulk-trash');
                form.action = '<?= base_url('trash/bulk-restore') ?>';
                form.submit();
            }
        });
    }

    function bulkDeletePerm() {
        customConfirm('Hapus Permanen Data Terpilih?', 'Semua data yang dipilih akan dihapus secara PERMANEN dan tidak dapat dipulihkan!', 'danger').then(ok => {
            if (ok) {
                const form = document.getElementById('form-bulk-trash');
                form.action = '<?= base_url('trash/bulk-delete-perm') ?>';
                form.submit();
            }
        });
    }
</script>
<?= $this->endSection() ?>
