<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="p-6">
    <div class="flex justify-between items-center mb-8">
        <div class="relative z-10 flex items-center gap-4">
            <a href="<?= base_url('dashboard') ?>" class="p-3 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl hover:bg-blue-600 hover:text-white transition-all active:scale-95" title="Kembali">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-3xl font-black text-blue-950 dark:text-white uppercase tracking-tight">Manajemen Role</h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Kelola hak akses dan cakupan data pengguna sistem.</p>
            </div>
        </div>
        <a href="<?= base_url('roles/create') ?>" class="bg-blue-900 hover:bg-blue-800 text-white px-6 py-3 rounded-2xl font-bold flex items-center gap-2 transition-all shadow-lg shadow-blue-900/20 active:scale-95">
            <i data-lucide="plus-circle" class="w-5 h-5"></i>
            TAMBAH ROLE
        </a>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="mb-6 bg-emerald-50 text-emerald-700 px-6 py-4 rounded-3xl border border-emerald-100 flex items-center gap-3 animate-fade-in">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            <span class="font-medium"><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($roles as $role) : ?>
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-xl hover:shadow-blue-900/5 transition-all group relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i data-lucide="shield-check" class="w-24 h-24 text-blue-900 dark:text-blue-400"></i>
                </div>
                
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <span class="text-[10px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-widest bg-blue-50 dark:bg-blue-950/30 px-3 py-1 rounded-full border border-blue-100 dark:border-blue-900 mb-2 inline-block transition-colors">Role Name</span>
                            <h3 class="text-2xl font-bold text-slate-800 dark:text-white"><?= strtoupper($role['role_name']) ?></h3>
                        </div>
                    </div>

                    <div class="space-y-4 mb-8">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-slate-50 dark:bg-slate-950 flex items-center justify-center text-slate-600 dark:text-slate-400 border border-transparent dark:border-slate-800">
                                <i data-lucide="map-pin" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-widest transition-colors">Scope</p>
                                <p class="text-sm font-semibold <?= $role['scope'] == 'global' ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' ?>">
                                    <?= strtoupper($role['scope']) ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-auto">
                        <a href="<?= base_url('roles/edit/' . $role['id']) ?>" class="flex-1 bg-slate-50 dark:bg-slate-800 hover:bg-blue-900 dark:hover:bg-blue-700 hover:text-white text-slate-600 dark:text-slate-300 px-4 py-3 rounded-2xl font-bold text-center transition-all flex items-center justify-center gap-2 text-sm border border-slate-100 dark:border-slate-700">
                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                            EDIT
                        </a>
                        <?php if ($role['id'] != 1) : ?>
                            <button type="button" onclick="confirmDeleteRole('<?= base_url('roles/delete/' . $role['id']) ?>')" class="bg-rose-50 dark:bg-rose-950/30 hover:bg-rose-500 hover:text-white text-rose-600 dark:text-rose-400 p-3 rounded-2xl transition-all border border-rose-100 dark:border-rose-900 active:scale-95">
                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    async function confirmDeleteRole(url) {
        const ok = await customConfirm('Hapus Role?', 'Pastikan tidak ada user yang sedang menggunakan role ini.', 'danger');
        if (ok) window.location.href = url;
    }
</script>
<?= $this->endSection() ?>
