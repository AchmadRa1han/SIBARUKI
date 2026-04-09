<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="p-8 max-w-5xl mx-auto">
    <div class="mb-10">
        <a href="<?= base_url('roles') ?>" class="inline-flex items-center gap-2 text-blue-900 font-bold hover:gap-3 transition-all mb-4 text-sm uppercase tracking-widest">
            <i data-lucide="chevron-left" class="w-4 h-4 text-blue-900"></i>
            Kembali ke Daftar
        </a>
        <h1 class="text-3xl font-bold text-blue-950 uppercase tracking-tight">Tambah Role Baru</h1>
        <p class="text-slate-500 text-sm mt-1">Tentukan hak akses dan cakupan wilayah untuk role baru ini.</p>
    </div>

    <form action="<?= base_url('roles/store') ?>" method="POST">
        <div class="space-y-8">
            <!-- Informasi Dasar -->
            <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm">
                <h3 class="text-blue-900 font-bold uppercase text-[10px] tracking-widest mb-6 border-b border-slate-50 pb-4 flex items-center gap-2">
                    <i data-lucide="info" class="w-3.5 h-3.5"></i>
                    Informasi Dasar
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Nama Role</label>
                        <input type="text" name="role_name" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900 outline-none transition-all font-bold text-slate-700 placeholder:font-medium" placeholder="Contoh: Verifikator Lapangan" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Cakupan Data (Scope)</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="cursor-pointer group">
                                <input type="radio" name="scope" value="global" class="hidden peer" checked>
                                <div class="p-4 rounded-2xl border-2 border-slate-50 group-hover:border-blue-900/20 peer-checked:border-blue-900 peer-checked:bg-blue-50 transition-all text-center">
                                    <span class="block text-xs font-bold uppercase tracking-widest text-slate-400 peer-checked:text-blue-900">Global</span>
                                    <span class="block text-[9px] text-slate-400 mt-1 peer-checked:text-blue-700 font-bold uppercase">Seluruh Kabupaten</span>
                                </div>
                            </label>
                            <label class="cursor-pointer group">
                                <input type="radio" name="scope" value="local" class="hidden peer">
                                <div class="p-4 rounded-2xl border-2 border-slate-50 group-hover:border-blue-900/20 peer-checked:border-blue-900 peer-checked:bg-blue-50 transition-all text-center">
                                    <span class="block text-xs font-bold uppercase tracking-widest text-slate-400 peer-checked:text-blue-900">Local</span>
                                    <span class="block text-[9px] text-slate-400 mt-1 peer-checked:text-blue-700 font-bold uppercase">Wilayah Tugas</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permission Matrix -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-8 border-b border-slate-50">
                    <h3 class="text-blue-900 font-bold uppercase text-[10px] tracking-widest flex items-center gap-2">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                        Matriks Hak Akses (Permissions)
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-blue-900 uppercase text-[10px] font-bold tracking-widest">
                                <th class="p-6 border-b border-slate-100">Modul / Fitur</th>
                                <th class="p-6 border-b border-slate-100 text-center">Lihat (View)</th>
                                <th class="p-6 border-b border-slate-100 text-center">Tambah (Create)</th>
                                <th class="p-6 border-b border-slate-100 text-center">Edit (Update)</th>
                                <th class="p-6 border-b border-slate-100 text-center">Hapus (Delete)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <!-- Baris RTLH -->
                            <tr class="hover:bg-blue-50/30 transition-colors">
                                <td class="p-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl bg-blue-50 flex items-center justify-center text-blue-900">
                                            <i data-lucide="home" class="w-4 h-4"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-slate-800 uppercase tracking-tight">Data RTLH</p>
                                            <p class="text-[9px] text-slate-400 font-medium italic">Data rumah tidak layak huni</p>
                                        </div>
                                    </div>
                                </td>
                                <?php $actions = ['view_rtlh', 'create_rtlh', 'edit_rtlh', 'delete_rtlh']; ?>
                                <?php foreach ($actions as $action) : ?>
                                    <td class="p-6 text-center">
                                        <?php 
                                            $perm = array_filter($permissions, fn($p) => $p['permission_name'] === $action);
                                            $perm = reset($perm);
                                        ?>
                                        <?php if ($perm) : ?>
                                            <input type="checkbox" name="permissions[]" value="<?= $perm['id'] ?>" class="w-6 h-6 rounded-lg border-2 border-slate-200 text-blue-900 focus:ring-blue-900/20 cursor-pointer transition-all">
                                        <?php else : ?>
                                            <span class="text-slate-200">-</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>

                            <!-- Baris Kumuh -->
                            <tr class="hover:bg-amber-50/30 transition-colors">
                                <td class="p-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl bg-amber-50 flex items-center justify-center text-amber-700">
                                            <i data-lucide="map" class="w-4 h-4"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-slate-800 uppercase tracking-tight">Wilayah Kumuh</p>
                                            <p class="text-[9px] text-slate-400 font-medium italic">Data kawasan kumuh kabupaten</p>
                                        </div>
                                    </div>
                                </td>
                                <?php $actions = ['view_kumuh', 'create_kumuh', 'edit_kumuh', 'delete_kumuh']; ?>
                                <?php foreach ($actions as $action) : ?>
                                    <td class="p-6 text-center">
                                        <?php 
                                            $perm = array_filter($permissions, fn($p) => $p['permission_name'] === $action);
                                            $perm = reset($perm);
                                        ?>
                                        <?php if ($perm) : ?>
                                            <input type="checkbox" name="permissions[]" value="<?= $perm['id'] ?>" class="w-6 h-6 rounded-lg border-2 border-slate-200 text-amber-600 focus:ring-amber-500/20 cursor-pointer transition-all">
                                        <?php else : ?>
                                            <span class="text-slate-200">-</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>

                            <!-- Baris User Management -->
                            <tr class="hover:bg-indigo-50/30 transition-colors">
                                <td class="p-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-700">
                                            <i data-lucide="users" class="w-4 h-4"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-slate-800 uppercase tracking-tight">Manajemen User</p>
                                            <p class="text-[9px] text-slate-400 font-medium italic">Kelola akun dan petugas</p>
                                        </div>
                                    </div>
                                </td>
                                <?php $actions = ['view_users', 'create_users', 'edit_users', 'delete_users']; ?>
                                <?php foreach ($actions as $action) : ?>
                                    <td class="p-6 text-center">
                                        <?php 
                                            $perm = array_filter($permissions, fn($p) => $p['permission_name'] === $action);
                                            $perm = reset($perm);
                                        ?>
                                        <?php if ($perm) : ?>
                                            <input type="checkbox" name="permissions[]" value="<?= $perm['id'] ?>" class="w-6 h-6 rounded-lg border-2 border-slate-200 text-indigo-600 focus:ring-indigo-500/20 cursor-pointer transition-all">
                                        <?php else : ?>
                                            <span class="text-slate-200">-</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Bagian Izin Lainnya -->
                <div class="p-8 bg-slate-50/50">
                    <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                        <i data-lucide="settings" class="w-3.5 h-3.5"></i>
                        Izin Sistem & Manajemen Lainnya
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <?php 
                            $systemPerms = ['manage_users', 'manage_roles', 'export_data'];
                            foreach ($permissions as $p) : 
                                if (in_array($p['permission_name'], $systemPerms)) :
                        ?>
                            <label class="flex items-center gap-3 p-4 bg-white rounded-2xl border border-slate-100 hover:border-blue-900/20 cursor-pointer transition-all group">
                                <input type="checkbox" name="permissions[]" value="<?= $p['id'] ?>" class="w-5 h-5 rounded-lg border-2 border-slate-200 text-blue-900 focus:ring-blue-900/20 cursor-pointer">
                                <span class="text-[10px] font-bold text-slate-600 group-hover:text-blue-900 uppercase tracking-widest">
                                    <?= strtoupper(str_replace('_', ' ', $p['permission_name'])) ?>
                                </span>
                            </label>
                        <?php 
                                endif;
                            endforeach; 
                        ?>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-blue-900 hover:bg-blue-800 text-white font-bold py-5 rounded-2xl shadow-xl shadow-blue-900/20 transition-all active:scale-95 uppercase tracking-widest flex items-center justify-center gap-3">
                    <i data-lucide="save" class="w-5 h-5"></i>
                    SIMPAN ROLE
                </button>
                <a href="<?= base_url('roles') ?>" class="bg-slate-50 hover:bg-slate-100 text-slate-400 font-bold px-10 py-5 rounded-2xl transition-all uppercase tracking-widest text-xs border border-slate-100">
                    Batal
                </a>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
