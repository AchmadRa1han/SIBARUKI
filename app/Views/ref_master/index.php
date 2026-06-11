<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto space-y-6 pb-24 text-slate-900 dark:text-slate-200">
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-3 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 no-print">
        <a href="<?= base_url('dashboard') ?>" class="hover:text-blue-600 transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <a href="<?= base_url('sys_settings') ?>" class="hover:text-blue-600 transition-colors">Pengaturan</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">Referensi Master</span>
    </nav>

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-blue-950 p-7 rounded-[2.5rem] text-white shadow-2xl shadow-blue-950/20 relative overflow-hidden transition-all duration-500">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
        <div class="relative z-10 flex items-center gap-5">
            <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-md border border-white/10 shadow-inner">
                <i data-lucide="library" class="w-6 h-6 text-blue-400"></i>
            </div>
            <div>
                <h1 class="text-2xl md:text-3xl font-black uppercase tracking-tighter leading-none">Referensi Master</h1>
                <p class="text-white/60 font-medium text-xs mt-2 tracking-wide">Kelola daftar kategori dan pilihan referensi data sistem</p>
            </div>
        </div>
        <div class="flex items-center gap-3 relative z-10">
            <a href="<?= base_url('ref-master/create') ?>" class="bg-white text-blue-950 px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest shadow-xl hover:scale-105 active:scale-95 transition-all flex items-center gap-2 group">
                <i data-lucide="plus" class="w-4 h-4 transition-transform group-hover:rotate-90"></i> Tambah Referensi
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-xl overflow-hidden relative">
        <!-- Floating Bulk Action Bar -->
        <!-- Floating Bulk Action Bar -->
        <div id="bulk-action-bar" class="hidden fixed bottom-8 left-1/2 -translate-x-1/2 z-[5000] bg-blue-950 text-white px-8 py-4 rounded-3xl shadow-2xl flex items-center gap-6 border border-white/10 backdrop-blur-xl animate-bounce-subtle">
            <div class="flex items-center gap-3 pr-6 border-r border-white/10">
                <span id="selected-count" class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-[10px] font-black">0</span>
                <span class="text-[9px] font-bold uppercase tracking-widest">Data Terpilih</span>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="handleBulkDelete()" class="px-6 py-2.5 bg-rose-500 hover:bg-rose-600 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg transition-all active:scale-95 flex items-center gap-2">
                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Hapus Massal
                </button>
                <button onclick="clearSelection()" class="px-6 py-2.5 bg-white/10 hover:bg-white/20 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">Batal</button>
            </div>
        </div>

        <div class="p-8 border-b border-slate-50 dark:border-slate-800 flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-blue-600/20">
                <i data-lucide="database" class="w-6 h-6"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-blue-950 dark:text-white uppercase tracking-tight">Kamus Data Referensi</h3>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em]">Pusat ID & Pilihan Dropdown Sistem</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/50 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                        <th class="px-8 py-5 w-20 text-center">
                            <input type="checkbox" id="select-all" class="w-5 h-5 rounded-lg border-2 border-slate-200 text-blue-950 focus:ring-blue-900/20 cursor-pointer transition-all">
                        </th>
                        <th class="px-4 py-5 w-24">ID</th>
                        <th class="px-4 py-5 w-64">Kategori</th>
                        <th class="px-4 py-5">Nama Pilihan</th>
                        <th class="px-8 py-5 text-center w-40">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800 text-[11px]">
                    <?php foreach ($ref_master as $row) : ?>
                    <tr class="group hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-all duration-300">
                        <td class="px-8 py-4 text-center">
                            <input type="checkbox" name="ids[]" value="<?= $row['id'] ?>" class="row-checkbox w-5 h-5 rounded-lg border-2 border-slate-200 text-blue-950 focus:ring-blue-900/20 cursor-pointer transition-all">
                        </td>
                        <td class="px-4 py-4 font-mono font-bold text-slate-400">#<?= str_pad($row['id'], 3, '0', STR_PAD_LEFT) ?></td>
                        <td class="px-4 py-4">
                            <span class="bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border border-blue-100 dark:border-blue-800">
                                <?= $row['kategori'] ?>
                            </span>
                        </td>
                        <td class="px-4 py-4 font-black text-blue-950 dark:text-white uppercase tracking-tight"><?= $row['nama_pilihan'] ?></td>
                        <td class="px-8 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="<?= base_url('ref-master/edit/' . $row['id']) ?>" class="p-2.5 bg-white dark:bg-slate-800 text-blue-600 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 hover:bg-blue-600 hover:text-white transition-all active:scale-95" title="Edit Referensi"><i data-lucide="edit-3" class="w-4 h-4"></i></a>
                                <form action="<?= base_url('ref-master/delete/' . $row['id']) ?>" method="post" class="inline" onsubmit="return confirm('Hapus data referensi ini?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="p-2.5 bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 rounded-xl hover:bg-rose-600 hover:text-white transition-all active:scale-95" title="Hapus Referensi"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="p-8 border-t dark:border-slate-800 bg-slate-50/30 dark:bg-slate-900/50">
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
        const checked = document.querySelectorAll('.row-checkbox:checked').length;
        selectedCount.innerText = checked;
        bulkBar.classList.toggle('hidden', checked === 0);
        if (checked > 0 && window.lucide) lucide.createIcons();
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
            const csrfToken = document.cookie.split('; ').find(row => row.startsWith('csrf_cookie_name='))?.split('=')[1] || '<?= csrf_hash() ?>';
            const formData = new FormData();
            ids.forEach(id => formData.append('ids[]', id));
            formData.append('<?= csrf_token() ?>', csrfToken);

            try {
                const response = await fetch('<?= base_url('ref-master/bulk-delete') ?>', {
                    method: 'POST',
                    body: formData,
                    headers: { 
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    }
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
