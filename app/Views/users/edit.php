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
        <h1 class="text-2xl font-black text-blue-950 uppercase tracking-wider text-center">Edit Pengguna</h1>
        <p class="text-slate-400 text-sm font-medium italic text-center">Modifikasi profil atau ubah penugasan wilayah.</p>
    </div>

    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
        <form action="<?= base_url('users/update/'.$user['id']) ?>" method="post" class="p-8 space-y-6">
            <?= csrf_field() ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-black text-blue-900 uppercase tracking-widest mb-2 ml-1">Username</label>
                    <input type="text" name="username" value="<?= $user['username'] ?>" required
                        class="w-full p-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-blue-900 uppercase tracking-widest mb-2 ml-1">Password Baru (Kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" placeholder="••••••••"
                        class="w-full p-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-blue-900 uppercase tracking-widest mb-2 ml-1">Instansi / Desa</label>
                    <input type="text" name="instansi" value="<?= $user['instansi'] ?>"
                        class="w-full p-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-blue-900 uppercase tracking-widest mb-2 ml-1">Role Akses</label>
                    <select name="role_id" required class="w-full p-4 bg-slate-50 border border-slate-100 rounded-2xl outline-none">
                        <?php foreach($roles as $role): ?>
                            <option value="<?= $role['id'] ?>" <?= $user['role_id'] == $role['id'] ? 'selected' : '' ?>><?= strtoupper($role['role_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black text-blue-900 uppercase tracking-widest mb-2 ml-1 text-rose-600">Wilayah Penugasan (Petugas)</label>
                <select name="desa_ids[]" id="desa_ids" multiple placeholder="Pilih satu atau lebih desa..." class="w-full">
                    <?php foreach($all_desa as $desa): ?>
                        <option value="<?= $desa['desa_id'] ?>" <?= in_array($desa['desa_id'], $assigned_desa_ids) ? 'selected' : '' ?>>
                            <?= $desa['desa_nama'] ?> (<?= $desa['desa_id'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="pt-4 flex justify-end gap-4 border-t border-slate-50">
                <a href="<?= base_url('users') ?>" class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest hover:text-slate-600 transition-colors">Batal</a>
                <button type="submit" class="bg-blue-950 text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-blue-950/20 hover:bg-blue-900 transition-all">
                    Update User
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
