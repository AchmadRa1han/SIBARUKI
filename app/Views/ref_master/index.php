<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-300 relative">
    <!-- Floating Bulk Action Bar -->
    <div id="bulk-action-bar" class="absolute top-0 left-0 right-0 z-50 bg-blue-950 text-white p-4 transform -translate-y-full transition-transform duration-300 flex items-center justify-between px-10">
        <div class="flex items-center gap-4">
            <span id="selected-count" class="bg-blue-600 px-3 py-1 rounded-full text-[10px] font-bold tracking-widest">0 TERPILIH</span>
            <p class="text-[10px] font-bold uppercase tracking-widest opacity-70">Aksi massal untuk referensi master</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="handleBulkDelete()" class="px-6 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-[10px] font-bold uppercase tracking-widest transition-all flex items-center gap-2">
                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Hapus Terpilih
            </button>
            <button onclick="clearSelection()" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl text-[10px] font-bold uppercase tracking-widest transition-all">Batal</button>
        </div>
    </div>

    <div class="p-8 border-b dark:border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/50 dark:bg-slate-900/50">
        <div class="flex items-center gap-4">
            <a href="<?= base_url('settings') ?>" class="p-3 bg-white dark:bg-slate-900 text-slate-500 dark:text-slate-400 rounded-xl hover:bg-blue-600 hover:text-white transition-all active:scale-95 shadow-sm border border-slate-100 dark:border-slate-800" title="Kembali ke Pengaturan">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-blue-950 dark:text-white tracking-tight uppercase tracking-wider">Referensi Master</h1>
                <p class="text-sm text-slate-400 dark:text-slate-500 font-medium">Kelola daftar kategori dan pilihan referensi sistem</p>
            </div>
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

        <div class="overflow-x-auto rounded-xl border border-slate-100 dark:border-slate-800 mb-8 shadow-inner bg-slate-50/30 dark:bg-slate-950/50">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white dark:bg-slate-900 text-blue-950 dark:text-blue-400 uppercase text-[10px] font-bold tracking-[0.15em] transition-colors duration-300">
                        <th class="p-5 border-b border-slate-100 dark:border-slate-800 w-16 text-center">
                            <input type="checkbox" id="select-all" class="w-5 h-5 rounded-lg border-2 border-slate-200 text-blue-950 focus:ring-blue-900/20 cursor-pointer transition-all">
                        </th>
                        <th class="p-5 border-b border-slate-100 dark:border-slate-800">ID</th>
                        <th class="p-5 border-b border-slate-100 dark:border-slate-800">Kategori</th>
                        <th class="p-5 border-b border-slate-100 dark:border-slate-800">Nama Pilihan</th>
                        <th class="p-5 border-b border-slate-100 dark:border-slate-800 text-center uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800 text-sm text-slate-700 dark:text-slate-400 bg-white/50 dark:bg-slate-900/50 transition-colors duration-300">
                    <?php foreach ($ref_master as $row) : ?>
                        <tr class="hover:bg-blue-50/50 dark:hover:bg-blue-900/10 transition-colors group">
                            <td class="p-5 text-center">
                                <input type="checkbox" name="ids[]" value="<?= $row['id'] ?>" class="row-checkbox w-5 h-5 rounded-lg border-2 border-slate-200 text-blue-950 focus:ring-blue-900/20 cursor-pointer transition-all">
                            </td>
                            <td class="p-5 font-mono text-[10px] font-bold text-slate-400 dark:text-slate-600">#<?= str_pad($row['id'], 3, '0', STR_PAD_LEFT) ?></td>
                            <td class="p-5">
                                <span class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 px-2.5 py-1 rounded-lg text-[9px] font-bold uppercase tracking-widest border border-slate-200 dark:border-slate-700 transition-colors duration-300">
                                    <?= $row['kategori'] ?>
                                </span>
                            </td>
                            <td class="p-5 font-bold text-slate-800 dark:text-slate-200 group-hover:text-blue-900 dark:group-hover:text-blue-400 transition-colors"><?= $row['nama_pilihan'] ?></td>
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
<script>
    // --- BULK DELETE LOGIC ---
    const selectAll = document.getElementById('select-all');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    const bulkBar = document.getElementById('bulk-action-bar');
    const selectedCount = document.getElementById('selected-count');

    function updateBulkBar() {
        const checked = document.querySelectorAll('.row-checkbox:checked');
        if (checked.length > 0) {
            bulkBar.classList.remove('-translate-y-full');
            selectedCount.innerText = `${checked.length} TERPILIH`;
        } else {
            bulkBar.classList.add('-translate-y-full');
        }
    }

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            rowCheckboxes.forEach(cb => {
                cb.checked = this.checked;
                const row = cb.closest('tr');
                if (this.checked) row.classList.add('bg-blue-50/50', 'dark:bg-blue-900/10');
                else row.classList.remove('bg-blue-50/50', 'dark:bg-blue-900/10');
            });
            updateBulkBar();
        });
    }

    rowCheckboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            const row = this.closest('tr');
            if (this.checked) row.classList.add('bg-blue-50/50', 'dark:bg-blue-900/10');
            else row.classList.remove('bg-blue-50/50', 'dark:bg-blue-900/10');
            
            const allChecked = document.querySelectorAll('.row-checkbox:checked').length === rowCheckboxes.length;
            selectAll.checked = allChecked;
            updateBulkBar();
        });
    });

    function clearSelection() {
        selectAll.checked = false;
        rowCheckboxes.forEach(cb => {
            cb.checked = false;
            cb.closest('tr').classList.remove('bg-blue-50/50', 'dark:bg-blue-900/10');
        });
        updateBulkBar();
    }

    async function handleBulkDelete() {
        const checked = document.querySelectorAll('.row-checkbox:checked');
        const ids = Array.from(checked).map(cb => cb.value);
        
        const ok = await customConfirm('Hapus Massal?', `Apakah Anda yakin ingin menghapus ${ids.length} referensi yang dipilih?`, 'danger');
        
        if (ok) {
            const formData = new FormData();
            ids.forEach(id => formData.append('ids[]', id));
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            try {
                const response = await fetch('<?= base_url('ref-master/bulk-delete') ?>', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const result = await response.json();
                
                if (result.status === 'success') {
                    showToast(result.message, 'success');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showToast(result.message, 'error');
                }
            } catch (error) {
                showToast('Terjadi kesalahan sistem.', 'error');
            }
        }
    }
</script>
<?= $this->endSection() ?>
