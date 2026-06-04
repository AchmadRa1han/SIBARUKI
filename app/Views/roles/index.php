<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto space-y-6 pb-24 text-slate-900 dark:text-slate-200">
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-3 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 no-print">
        <a href="<?= base_url('dashboard') ?>" class="hover:text-blue-600 transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <a href="<?= base_url('sys_settings') ?>" class="hover:text-blue-600 transition-colors">Pengaturan</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">Hak Akses</span>
    </nav>

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-blue-950 p-7 rounded-[2.5rem] text-white shadow-2xl shadow-blue-950/20 relative overflow-hidden transition-all duration-500">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
        <div class="relative z-10 flex items-center gap-5">
            <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-md border border-white/10 shadow-inner">
                <i data-lucide="shield-check" class="w-6 h-6 text-blue-400"></i>
            </div>
            <div>
                <h1 class="text-2xl md:text-3xl font-black uppercase tracking-tighter leading-none">Otoritas Hak Akses</h1>
                <p class="text-white/60 font-medium text-xs mt-2 tracking-wide">Kelola matriks perizinan dan tingkatan otorisasi sistem</p>
            </div>
        </div>
        <div class="flex items-center gap-3 relative z-10">
            <a href="<?= base_url('roles/create') ?>" class="bg-white text-blue-950 px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest shadow-xl hover:scale-105 active:scale-95 transition-all flex items-center gap-2 group">
                <i data-lucide="plus" class="w-4 h-4 transition-transform group-hover:rotate-90"></i> Tambah Role
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($roles as $role) : ?>
            <div class="bg-white dark:bg-slate-900 p-10 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-xl hover:shadow-2xl hover:shadow-blue-950/10 transition-all group relative overflow-hidden flex flex-col justify-between">
                <div class="absolute top-0 right-0 p-6 opacity-[0.03] group-hover:opacity-10 transition-opacity duration-700">
                    <i data-lucide="shield-check" class="w-32 h-32 text-blue-950 dark:text-white"></i>
                </div>
                
                <div class="relative z-10">
                    <div class="mb-8">
                        <span class="text-[8px] font-black text-blue-950 dark:text-blue-400 uppercase tracking-[0.2em] bg-blue-950/5 dark:bg-blue-950/30 px-3 py-1.5 rounded-lg border border-blue-950/10 dark:border-blue-900 mb-4 inline-block">Role Identifier</span>
                        <h3 class="text-2xl font-black text-blue-950 dark:text-white uppercase tracking-tighter"><?= $role['role_name'] ?></h3>
                    </div>

                    <div class="flex items-center gap-4 mb-10 p-4 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800">
                        <div class="w-10 h-10 rounded-xl bg-white dark:bg-slate-900 flex items-center justify-center text-blue-950 dark:text-blue-400 shadow-sm">
                            <i data-lucide="map-pin" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Coverage Scope</p>
                            <p class="text-xs font-black uppercase tracking-tight <?= $role['scope'] == 'global' ? 'text-emerald-600' : 'text-amber-600' ?>">
                                <?= $role['scope'] ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 relative z-10">
                    <a href="<?= base_url('roles/edit/' . $role['id']) ?>" class="flex-1 bg-blue-950 dark:bg-blue-600 text-white py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest text-center shadow-lg shadow-blue-950/20 hover:scale-[1.02] active:scale-95 transition-all">
                        Edit Matrix
                    </a>
                    <?php if ($role['id'] != 1) : ?>
                        <button type="button" onclick="confirmDeleteRole('<?= base_url('roles/delete/' . $role['id']) ?>')" class="bg-rose-500 text-white p-4 rounded-2xl hover:bg-rose-600 active:scale-95 transition-all shadow-lg shadow-rose-500/20">
                            <i data-lucide="trash-2" class="w-5 h-5"></i>
                        </button>
                    <?php endif; ?>
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
