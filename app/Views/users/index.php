<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="space-y-8">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-black text-blue-950 dark:text-white uppercase tracking-wider">Manajemen Pengguna</h1>
            <p class="text-slate-400 dark:text-slate-500 text-sm font-medium italic">Kelola hak akses dan penugasan wilayah petugas.</p>
        </div>
        <?php if (has_permission('manage_users')): ?>
        <a href="<?= base_url('users/create') ?>" class="bg-blue-900 dark:bg-blue-700 hover:bg-blue-800 dark:hover:bg-blue-600 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-blue-900/20 transition-all flex items-center gap-2 group">
            <i data-lucide="user-plus" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
            <span>Tambah User</span>
        </a>
        <?php endif; ?>
    </div>

    <?php if(session()->getFlashdata('message')): ?>
        <div class="bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400 p-4 rounded-2xl text-sm font-bold flex items-center gap-3 border border-emerald-100 dark:border-emerald-900 shadow-sm">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            <?= session()->getFlashdata('message') ?>
        </div>
    <?php endif; ?>

    <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-300 relative">
        <!-- Floating Bulk Action Bar -->
        <div id="bulk-action-bar" class="absolute top-0 left-0 right-0 z-50 bg-blue-950 text-white p-4 transform -translate-y-full transition-transform duration-300 flex items-center justify-between px-10">
            <div class="flex items-center gap-4">
                <span id="selected-count" class="bg-blue-600 px-3 py-1 rounded-full text-[10px] font-black tracking-widest">0 TERPILIH</span>
                <p class="text-[10px] font-bold uppercase tracking-widest opacity-70">Aksi massal untuk manajemen user</p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="handleBulkDelete()" class="px-6 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Hapus Terpilih
                </button>
                <button onclick="clearSelection()" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">Batal</button>
            </div>
        </div>

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 dark:bg-slate-950/50 border-b border-slate-100 dark:border-slate-800 transition-colors duration-300">
                    <th class="p-6 w-16 text-center">
                        <input type="checkbox" id="select-all" class="w-5 h-5 rounded-lg border-2 border-slate-200 text-blue-950 focus:ring-blue-900/20 cursor-pointer transition-all">
                    </th>
                    <th class="p-6 text-[10px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-widest">Username</th>
                    <th class="p-6 text-[10px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-widest">Instansi</th>
                    <th class="p-6 text-[10px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-widest">Role</th>
                    <th class="p-6 text-[10px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-widest">Dibuat Pada</th>
                    <th class="p-6 text-[10px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-widest text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 dark:divide-slate-800 transition-colors duration-300">
                <?php foreach($users as $user): ?>
                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors group">
                    <td class="p-6 text-center">
                        <?php if($user['username'] !== 'admin'): ?>
                        <input type="checkbox" name="ids[]" value="<?= $user['id'] ?>" class="row-checkbox w-5 h-5 rounded-lg border-2 border-slate-200 text-blue-950 focus:ring-blue-900/20 cursor-pointer transition-all">
                        <?php endif; ?>
                    </td>
                    <td class="p-6">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg flex items-center justify-center font-black text-xs uppercase">
                                <?= substr($user['username'], 0, 1) ?>
                            </div>
                            <span class="font-bold text-slate-700 dark:text-slate-300 group-hover:text-blue-900 dark:group-hover:text-blue-400 transition-colors"><?= $user['username'] ?></span>
                        </div>
                    </td>
                    <td class="p-6 text-sm text-slate-500 dark:text-slate-500 font-medium"><?= $user['instansi'] ?: '-' ?></td>
                    <td class="p-6">
                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest <?= $user['role_name'] == 'admin' ? 'bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400' : 'bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400' ?>">
                            <?= $user['role_name'] ?>
                        </span>
                    </td>
                    <td class="p-6 text-xs text-slate-400 dark:text-slate-600 font-bold uppercase"><?= date('d M Y', strtotime($user['created_at'])) ?></td>
                    <td class="p-6">
                        <div class="flex justify-center gap-2">
                            <?php if(has_permission('manage_users')): ?>
                            <a href="<?= base_url('users/edit/'.$user['id']) ?>" class="p-2 text-slate-400 dark:text-slate-600 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </a>
                            <?php if($user['username'] !== 'admin'): ?>
                            <button type="button" onclick="confirmDeleteUser(this)" data-url="<?= base_url('users/delete/'.$user['id']) ?>" class="p-2 text-slate-400 dark:text-slate-600 hover:text-rose-600 dark:hover:text-rose-400 transition-colors">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                            <?php endif; ?>
                            <?php else: ?>
                                <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest italic">Read Only</span>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    async function confirmDeleteUser(btn) {
        const url = btn.getAttribute('data-url');
        const ok = await customConfirm('Hapus User?', 'Akun ini akan dipindahkan ke Recycle Bin.', 'danger');
        if (ok) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '<?= csrf_token() ?>';
            csrf.value = '<?= csrf_hash() ?>';
            form.appendChild(csrf);
            document.body.appendChild(form);
            form.submit();
        }
    }

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
        
        const ok = await window.customConfirm('Hapus Massal?', `Apakah Anda yakin ingin menghapus ${ids.length} user yang dipilih?`, 'danger');
        
        if (ok) {
            const formData = new FormData();
            ids.forEach(id => formData.append('ids[]', id));
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            try {
                const response = await fetch('<?= base_url('users/bulk-delete') ?>', {
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
