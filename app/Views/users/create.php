<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<!-- Tom Select CSS -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<style>
    .ts-control {
        border: none !important;
        padding: 1rem !important;
        background: #f8fafc !important; /* slate-50 */
        border-radius: 1rem !important;
        transition: all 0.3s ease;
    }
    .dark .ts-control {
        background: #020617 !important; /* slate-950 */
        color: #e2e8f0 !important; /* slate-200 */
    }
    .dark .ts-dropdown {
        background: #0f172a !important; /* slate-900 */
        border: 1px solid #1e293b !important; /* slate-800 */
        color: #e2e8f0 !important;
    }
    .dark .ts-dropdown .active {
        background: #1e3a8a !important; /* blue-900 */
    }
    .ts-wrapper.multi .ts-control > div {
        background: #1e3a8a !important; /* blue-900 */
        color: white !important;
        border-radius: 0.5rem !important;
        padding: 2px 8px !important;
    }
</style>

<div class="max-w-3xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-black text-blue-950 dark:text-white uppercase tracking-wider">Tambah User</h1>
        <p class="text-slate-400 dark:text-slate-500 text-sm font-medium italic">Daftarkan akun petugas baru ke dalam sistem.</p>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-300">
        <form action="<?= base_url('users/store') ?>" method="post" class="p-8 space-y-8">
            <?= csrf_field() ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-[10px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-widest mb-2 ml-1">Username</label>
                    <input type="text" name="username" required
                        class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-widest mb-2 ml-1">Password</label>
                    <input type="password" name="password" required
                        class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-widest mb-2 ml-1">Instansi / Desa</label>
                    <input type="text" name="instansi" placeholder="Contoh: Desa Saukang"
                        class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-widest mb-2 ml-1">Role Akses</label>
                    <select name="role_id" id="role_id" required class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-2xl outline-none dark:text-slate-200 appearance-none font-bold">
                        <?php foreach($roles as $role): ?>
                            <option value="<?= $role['id'] ?>"><?= strtoupper($role['role_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div id="desa_select_wrapper">
                <label class="block text-[10px] font-black text-rose-600 dark:text-rose-400 uppercase tracking-widest mb-2 ml-1">Wilayah Penugasan (Petugas)</label>
                <select name="desa_ids[]" id="desa_ids" multiple placeholder="Pilih satu atau lebih desa..." class="w-full">
                    <?php foreach($all_desa as $desa): ?>
                        <option value="<?= $desa['desa_id'] ?>"><?= $desa['desa_nama'] ?> (<?= $desa['desa_id'] ?>)</option>
                    <?php endforeach; ?>
                </select>
                <p class="mt-3 text-[9px] text-slate-400 dark:text-slate-500 italic">Kosongkan jika admin pusat atau memiliki akses seluruh wilayah.</p>
            </div>

            <div class="pt-8 flex justify-end gap-6 border-t border-slate-50 dark:border-slate-800">
                <a href="<?= base_url('users') ?>" class="px-6 py-4 text-xs font-black text-slate-400 dark:text-slate-600 uppercase tracking-widest hover:text-slate-600 dark:hover:text-slate-400 transition-colors">Batal</a>
                <button type="submit" class="bg-blue-950 dark:bg-blue-700 text-white px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-blue-950/20 dark:shadow-none hover:bg-blue-900 dark:hover:bg-blue-600 transition-all">
                    Simpan User Baru
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tom Select JS -->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    new TomSelect("#desa_ids", {
        plugins: ['remove_button'],
        create: false,
        sortField: {
            field: "text",
            direction: "asc"
        }
    });
</script>
<?= $this->endSection() ?>
