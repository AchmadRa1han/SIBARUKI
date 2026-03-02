<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<!-- Tom Select CSS -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<style>
    .ts-control {
        border: none !important;
        padding: 1rem !important;
        background: #f8fafc !important; /* slate-50 */
        border-radius: 1.25rem !important;
        transition: all 0.3s ease;
        border: 1px solid #f1f5f9 !important;
    }
    .dark .ts-control {
        background: #020617 !important; /* slate-950 */
        color: #e2e8f0 !important; /* slate-200 */
        border-color: #1e293b !important;
    }
    .ts-wrapper.multi .ts-control > div {
        border-radius: 0.75rem !important;
        padding: 4px 12px !important;
        font-weight: 700 !important;
        font-size: 10px !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
    }
    /* RTLH Specific Tag Color */
    .rtlh-select .ts-wrapper.multi .ts-control > div {
        background: #1e3a8a !important; /* blue-900 */
        color: white !important;
    }
    /* Kumuh Specific Tag Color */
    .kumuh-select .ts-wrapper.multi .ts-control > div {
        background: #b45309 !important; /* amber-700 */
        color: white !important;
    }
</style>

<div class="max-w-4xl mx-auto p-6">
    <div class="mb-10 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-blue-950 dark:text-white uppercase tracking-tight">Tambah Pengguna</h1>
            <p class="text-slate-400 dark:text-slate-500 text-sm font-medium italic mt-1">Daftarkan akun petugas baru dan tentukan wilayah tugasnya.</p>
        </div>
        <div class="w-16 h-16 bg-blue-50 dark:bg-blue-900/20 rounded-3xl flex items-center justify-center text-blue-900 dark:text-blue-400">
            <i data-lucide="user-plus" class="w-8 h-8"></i>
        </div>
    </div>

    <form action="<?= base_url('users/store') ?>" method="post" class="space-y-8">
        <?= csrf_field() ?>

        <!-- Baris 1: Informasi Login -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm transition-colors duration-300">
            <h3 class="text-blue-900 dark:text-blue-400 font-black uppercase text-[10px] tracking-[0.2em] mb-8 border-b dark:border-slate-800 pb-4 flex items-center gap-2">
                <i data-lucide="key" class="w-3.5 h-3.5"></i>
                Kredensial Login
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-3 ml-1">Username</label>
                    <input type="text" name="username" required placeholder="Masukkan username..."
                        class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-900 dark:focus:border-blue-700 dark:text-slate-200 outline-none transition-all font-bold">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-3 ml-1">Password</label>
                    <input type="password" name="password" required placeholder="Minimal 6 karakter..."
                        class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-900 dark:focus:border-blue-700 dark:text-slate-200 outline-none transition-all font-bold">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-3 ml-1">Instansi / Unit Kerja</label>
                    <input type="text" name="instansi" placeholder="Contoh: Dinas Perkim / Desa Saukang"
                        class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-900 dark:focus:border-blue-700 dark:text-slate-200 outline-none transition-all font-bold">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-3 ml-1">Role / Hak Akses</label>
                    <div class="relative">
                        <select name="role_id" required class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-2xl outline-none dark:text-slate-200 appearance-none font-black text-blue-900 dark:text-blue-400 cursor-pointer focus:ring-4 focus:ring-blue-500/10">
                            <?php foreach($roles as $role): ?>
                                <option value="<?= $role['id'] ?>"><?= strtoupper($role['role_name']) ?> - <?= strtoupper($role['scope']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <i data-lucide="chevron-down" class="w-4 h-4 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Baris 2: Penugasan Wilayah -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm transition-colors duration-300">
            <h3 class="text-blue-900 dark:text-blue-400 font-black uppercase text-[10px] tracking-[0.2em] mb-8 border-b dark:border-slate-800 pb-4 flex items-center gap-2">
                <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                Penugasan Wilayah Kerja
            </h3>
            
            <div class="space-y-8">
                <!-- RTLH Selection -->
                <div class="rtlh-select">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-900 dark:text-blue-400">
                            <i data-lucide="home" class="w-4 h-4"></i>
                        </div>
                        <label class="block text-[10px] font-black text-slate-700 dark:text-slate-300 uppercase tracking-widest">Wilayah Tugas Data RTLH</label>
                    </div>
                    <select name="desa_ids_rtlh[]" id="desa_rtlh" multiple placeholder="Pilih desa untuk tugas RTLH..." class="w-full">
                        <?php foreach($all_desa as $desa): ?>
                            <option value="<?= $desa['desa_id'] ?>"><?= $desa['desa_nama'] ?> (<?= $desa['desa_id'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Kumuh Selection -->
                <div class="kumuh-select">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 rounded-xl bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center text-amber-600 dark:text-amber-500">
                            <i data-lucide="map" class="w-4 h-4"></i>
                        </div>
                        <label class="block text-[10px] font-black text-slate-700 dark:text-slate-300 uppercase tracking-widest">Wilayah Tugas Wilayah Kumuh</label>
                    </div>
                    <select name="desa_ids_kumuh[]" id="desa_kumuh" multiple placeholder="Pilih desa untuk tugas Kumuh..." class="w-full">
                        <?php foreach($all_desa as $desa): ?>
                            <option value="<?= $desa['desa_id'] ?>"><?= $desa['desa_nama'] ?> (<?= $desa['desa_id'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="mt-8 p-4 bg-slate-50 dark:bg-slate-950/50 rounded-2xl flex items-start gap-3">
                <i data-lucide="info" class="w-4 h-4 text-blue-600 shrink-0 mt-0.5"></i>
                <p class="text-[10px] text-slate-500 dark:text-slate-500 font-medium leading-relaxed italic">Catatan: Jika user memiliki role dengan Scope "Global", wilayah penugasan di atas dapat dikosongkan karena user akan otomatis memiliki akses ke seluruh kabupaten.</p>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="flex items-center justify-end gap-6 pt-4">
            <a href="<?= base_url('users') ?>" class="text-xs font-black text-slate-400 dark:text-slate-600 uppercase tracking-widest hover:text-slate-600 dark:hover:text-slate-400 transition-colors">Batal</a>
            <button type="submit" class="bg-blue-900 dark:bg-blue-700 text-white px-12 py-5 rounded-[1.5rem] font-black text-xs uppercase tracking-[0.2em] shadow-2xl shadow-blue-900/30 dark:shadow-none hover:bg-blue-800 dark:hover:bg-blue-600 transition-all active:scale-95 flex items-center gap-3">
                <i data-lucide="save" class="w-4 h-4"></i>
                Simpan Akun
            </button>
        </div>
    </form>
</div>

<!-- Tom Select JS -->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    const config = {
        plugins: ['remove_button'],
        create: false,
        sortField: { field: "text", direction: "asc" }
    };
    new TomSelect("#desa_rtlh", config);
    new TomSelect("#desa_kumuh", config);
</script>
<?= $this->endSection() ?>
