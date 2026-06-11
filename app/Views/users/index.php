<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto space-y-6 pb-24 text-slate-900 dark:text-slate-200">
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-3 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 no-print">
        <a href="<?= base_url('dashboard') ?>" class="hover:text-blue-600 transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">Manajemen Pengguna</span>
    </nav>

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-blue-950 p-7 rounded-[2.5rem] text-white shadow-2xl shadow-blue-950/20 relative overflow-hidden transition-all duration-500">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
        <div class="relative z-10 flex items-center gap-5">
            <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-md border border-white/10 shadow-inner">
                <i data-lucide="users" class="w-6 h-6 text-blue-400"></i>
            </div>
            <div>
                <h1 class="text-2xl md:text-3xl font-black uppercase tracking-tighter leading-none">Otoritas Pengguna</h1>
                <p class="text-white/60 font-medium text-xs mt-2 tracking-wide">Kelola hak akses dan penugasan wilayah administratif petugas</p>
            </div>
        </div>
        <div class="flex items-center gap-3 relative z-10">
            <?php if (has_permission('manage_users')): ?>
            <a href="<?= base_url('sys_users/create') ?>" class="bg-white text-blue-950 px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest shadow-xl hover:scale-105 active:scale-95 transition-all flex items-center gap-2 group">
                <i data-lucide="user-plus" class="w-4 h-4 transition-transform group-hover:scale-110"></i> Tambah Akun
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden relative">
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
                <i data-lucide="shield-check" class="w-6 h-6"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-blue-950 dark:text-white uppercase tracking-tight">Daftar Akun Terdaftar</h3>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em]">Manajemen Role & Kredensial Sistem</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse table-fixed">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/50 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                        <th class="px-8 py-5 w-20 text-center">
                            <input type="checkbox" id="select-all" class="w-5 h-5 rounded-lg border-2 border-slate-200 text-blue-600 focus:ring-blue-600/20 cursor-pointer transition-all">
                        </th>
                        <th class="px-4 py-5 w-64">Identitas Pengguna</th>
                        <th class="px-4 py-5 w-48">Unit Kerja / Instansi</th>
                        <th class="px-4 py-5 w-36 text-center">Hak Akses</th>
                        <th class="px-4 py-5 w-48">Audit Entry</th>
                        <th class="px-8 py-5 text-center w-40">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800 text-[11px]">
                    <?php foreach($users as $user): ?>
                    <tr class="group hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-all duration-300">
                        <td class="px-8 py-4 text-center">
                            <?php if($user['username'] !== 'admin'): ?>
                            <input type="checkbox" name="ids[]" value="<?= $user['id'] ?>" class="row-checkbox w-5 h-5 rounded-lg border-2 border-slate-200 text-blue-600 focus:ring-blue-600/20 cursor-pointer transition-all">
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center font-bold text-sm uppercase shadow-inner">
                                    <?= substr($user['username'], 0, 1) ?>
                                </div>
                                <div>
                                    <span class="font-bold text-blue-950 dark:text-white uppercase truncate block text-sm mb-0.5"><?= $user['username'] ?></span>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Verified System User</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="font-bold text-slate-700 dark:text-slate-200 uppercase tracking-tight"><?= $user['instansi'] ?: '-' ?></span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-widest border <?= $user['role_name'] == 'admin' ? 'bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400 border-indigo-100 dark:border-indigo-900' : 'bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 border-amber-100 dark:border-amber-900' ?>">
                                <?= $user['role_name'] ?>
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase"><?= date('d M Y, H:i', strtotime($user['created_at'])) ?></span>
                        </td>
                        <td class="px-8 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <?php if(has_permission('manage_users')): ?>
                                <a href="<?= base_url('sys_users/edit/'.$user['id']) ?>" class="p-2.5 bg-white dark:bg-slate-800 text-blue-600 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 hover:bg-blue-600 hover:text-white transition-all active:scale-95" title="Edit Akun"><i data-lucide="edit-3" class="w-4 h-4"></i></a>
                                <?php if($user['username'] !== 'admin'): ?>
                                <button type="button" onclick="confirmDeleteUser(this)" data-url="<?= base_url('sys_users/delete/'.$user['id']) ?>" class="p-2.5 bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 rounded-xl hover:bg-rose-600 hover:text-white transition-all active:scale-95" title="Hapus Akun"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest italic">Protected</span>
                                <?php endif; ?>
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
    async function confirmDeleteUser(btn) {
        const url = btn.getAttribute('data-url');
        const ok = await customConfirm('Hapus User?', 'Akun ini akan dipindahkan ke Recycle Bin.', 'danger');
        if (ok) {
            const form = document.createElement('form');
            form.method = 'POST'; form.action = url;
            const csrf = document.createElement('input');
            const csrfToken = document.cookie.split('; ').find(row => row.startsWith('csrf_cookie_name='))?.split('=')[1] || '<?= csrf_hash() ?>';
            csrf.type = 'hidden'; csrf.name = '<?= csrf_token() ?>'; csrf.value = csrfToken;
            form.appendChild(csrf); document.body.appendChild(form); form.submit();
        }
    }

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
            if(selectAll) selectAll.checked = allChecked;
            updateBulkBar();
        });
    });

    function clearSelection() {
        if(selectAll) selectAll.checked = false;
        rowCheckboxes.forEach(cb => { cb.checked = false; cb.closest('tr').classList.remove('bg-blue-50/50', 'dark:bg-blue-900/10'); });
        updateBulkBar();
    }

    async function handleBulkDelete() {
        const checked = document.querySelectorAll('.row-checkbox:checked');
        const ids = Array.from(checked).map(cb => cb.value);
        const ok = await window.customConfirm('Hapus Massal?', `Apakah Anda yakin ingin menghapus ${ids.length} user yang dipilih?`, 'danger');
        if (ok) {
            const csrfToken = document.cookie.split('; ').find(row => row.startsWith('csrf_cookie_name='))?.split('=')[1] || '<?= csrf_hash() ?>';
            const formData = new FormData();
            ids.forEach(id => formData.append('ids[]', id));
            formData.append('<?= csrf_token() ?>', csrfToken);
            try {
                const response = await fetch('<?= base_url('sys_users/bulk-delete') ?>', { 
                    method: 'POST', 
                    body: formData, 
                    headers: { 
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    } 
                });
                const result = await response.json();
                if (result.status === 'success') { showToast(result.message, 'success'); setTimeout(() => window.location.reload(), 1000); }
                else { showToast(result.message, 'error'); }
            } catch (error) { showToast('Terjadi kesalahan sistem.', 'error'); }
        }
    }
</script>
<?= $this->endSection() ?>
