<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto space-y-6 pb-32 text-slate-900 dark:text-slate-200">
    
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-3 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 no-print">
        <a href="<?= base_url('dashboard') ?>" class="hover:text-blue-600 transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <a href="<?= base_url('users') ?>" class="hover:text-blue-600 transition-colors">Otoritas Pengguna</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">Edit Profil Pengguna</span>
    </nav>

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm transition-all duration-300 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-48 h-48 bg-amber-600/5 rounded-full -mr-24 -mt-24 blur-3xl"></div>
        <div class="relative z-10 flex items-center gap-4">
            <a href="<?= base_url('users') ?>" class="p-3 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl hover:bg-blue-600 hover:text-white transition-all active:scale-95" title="Kembali">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-blue-950 dark:text-white uppercase tracking-tighter">Edit Akun</h1>
                <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">Konfigurasi Hak Akses: <span class="text-blue-600"><?= $user['username'] ?></span></p>
            </div>
        </div>
        <div class="flex items-center gap-3 relative z-10">
            <a href="<?= base_url('users') ?>" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-slate-700 transition-all active:scale-95">Batal</a>
        </div>
    </div>

    <form action="<?= base_url('users/update/'.$user['id']) ?>" method="post">
        <?= csrf_field() ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Data Profil -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
                    <div class="p-6 border-b dark:border-slate-800 bg-blue-50/30 dark:bg-blue-950/30 flex items-center gap-3">
                        <div class="w-9 h-9 bg-blue-600 rounded-lg flex items-center justify-center text-white shadow-lg">
                            <i data-lucide="user" class="w-4.5 h-4.5"></i>
                        </div>
                        <div>
                            <h3 class="text-[11px] font-bold text-blue-900 dark:text-blue-400 uppercase tracking-[0.2em]">Identitas Pengguna</h3>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Informasi Dasar Petugas</p>
                        </div>
                    </div>
                    <div class="p-8 space-y-6">
                        <div>
                            <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Username (Read Only)</label>
                            <input type="text" value="<?= $user['username'] ?>" disabled class="w-full p-3.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-bold text-slate-400 cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Nama Instansi / Unit Kerja</label>
                            <input type="text" name="instansi" value="<?= old('instansi', $user['instansi']) ?>" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
                    <div class="p-6 border-b dark:border-slate-800 bg-rose-50/30 dark:bg-rose-950/30 flex items-center gap-3">
                        <div class="w-9 h-9 bg-rose-600 rounded-lg flex items-center justify-center text-white shadow-lg">
                            <i data-lucide="lock" class="w-4.5 h-4.5"></i>
                        </div>
                        <div>
                            <h3 class="text-[11px] font-bold text-rose-900 dark:text-rose-400 uppercase tracking-[0.2em]">Keamanan</h3>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Ubah Kata Sandi Akses</p>
                        </div>
                    </div>
                    <div class="p-8">
                        <div class="bg-rose-50/50 dark:bg-rose-950/10 border border-rose-100 dark:border-rose-900/50 rounded-xl p-4 mb-6">
                            <p class="text-[9px] font-bold text-rose-600 uppercase tracking-widest flex items-center gap-2">
                                <i data-lucide="info" class="w-3 h-3"></i> Perhatian Keamanan
                            </p>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1.5 font-medium leading-relaxed">Kosongkan kolom kata sandi di bawah ini jika Anda tidak ingin melakukan perubahan akses.</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Kata Sandi Baru</label>
                                <input type="password" name="password" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 dark:text-slate-200 outline-none transition-all font-bold" placeholder="••••••••">
                            </div>
                            <div>
                                <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Konfirmasi Sandi</label>
                                <input type="password" name="password_confirm" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 dark:text-slate-200 outline-none transition-all font-bold" placeholder="••••••••">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Role & Scope -->
            <div class="space-y-6">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
                    <div class="p-6 border-b dark:border-slate-800 bg-amber-50/30 dark:bg-amber-950/30 flex items-center gap-3">
                        <div class="w-9 h-9 bg-amber-500 rounded-lg flex items-center justify-center text-white shadow-lg">
                            <i data-lucide="shield" class="w-4.5 h-4.5"></i>
                        </div>
                        <div>
                            <h3 class="text-[11px] font-bold text-amber-900 dark:text-amber-400 uppercase tracking-[0.2em]">Otoritas</h3>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Konfigurasi Peran</p>
                        </div>
                    </div>
                    <div class="p-8 space-y-8">
                        <div>
                            <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Pilih Role Utama</label>
                            <div class="space-y-2">
                                <?php foreach($roles as $role): ?>
                                <label class="flex items-center p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl cursor-pointer hover:border-blue-500 transition-all group">
                                    <input type="radio" name="role_id" value="<?= $role['id'] ?>" <?= $user['role_id'] == $role['id'] ? 'checked' : '' ?> required class="w-4.5 h-4.5 text-blue-600 focus:ring-blue-500/20 border-slate-300">
                                    <div class="ml-3">
                                        <p class="text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-tight group-hover:text-blue-600 transition-colors"><?= $role['role_name'] ?></p>
                                        <p class="text-[8px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Scope: <?= $role['scope'] ?></p>
                                    </div>
                                </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div id="desa-assignment" class="pt-6 border-t dark:border-slate-800">
                            <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Penugasan Wilayah</label>
                            <div class="max-h-56 overflow-y-auto space-y-1.5 pr-2 custom-scrollbar">
                                <?php foreach($desa_list as $desa): ?>
                                <label class="flex items-center p-2.5 hover:bg-slate-50 dark:hover:bg-slate-800/50 rounded-lg transition-all cursor-pointer group">
                                    <input type="checkbox" name="desa_ids[]" value="<?= $desa['desa_id'] ?>" <?= in_array($desa['desa_id'], $user_desa) ? 'checked' : '' ?> class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500/20 border-slate-300">
                                    <span class="ml-2.5 text-[10px] font-bold text-slate-600 dark:text-slate-400 group-hover:text-blue-600 uppercase tracking-tight transition-colors"><?= $desa['desa'] ?></span>
                                </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="flex flex-col md:flex-row justify-between items-center gap-6 pt-4">
            <div class="flex items-center gap-3 text-slate-400">
                <i data-lucide="info" class="w-4 h-4"></i>
                <p class="text-[9px] font-bold uppercase tracking-widest leading-relaxed max-w-md">Metadata perubahan akun akan dicatat dalam sistem log audit untuk monitoring keamanan.</p>
            </div>
            <button type="submit" class="group flex items-center space-x-6 bg-amber-500 hover:bg-amber-600 text-white pl-8 pr-4 py-4 rounded-xl font-bold shadow-xl shadow-amber-500/20 transition-all active:scale-95 w-full md:w-auto">
                <div class="flex flex-col text-right">
                    <span class="text-[8px] uppercase tracking-[0.3em] opacity-60 mb-0.5">Simpan Perubahan</span>
                    <span class="text-base uppercase tracking-tighter">Perbarui Akun</span>
                </div>
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center group-hover:translate-x-1 transition-transform">
                    <i data-lucide="save" class="w-5 h-5"></i>
                </div>
            </button>
        </div>
    </form>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.1); border-radius: 10px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>
<?= $this->endSection() ?>
