<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto space-y-8 pb-32">
    
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-3 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 no-print">
        <a href="<?= base_url('dashboard') ?>" class="hover:text-blue-600 transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <a href="<?= base_url('users') ?>" class="hover:text-blue-600 transition-colors">Otoritas Pengguna</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">Registrasi Akun</span>
    </nav>

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 bg-white dark:bg-slate-900 p-10 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm transition-all duration-300 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-blue-600/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
        <div class="relative z-10 flex items-center gap-6">
            <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-blue-600/20">
                <i data-lucide="user-plus" class="w-8 h-8"></i>
            </div>
            <div>
                <h1 class="text-3xl md:text-4xl font-black text-blue-950 dark:text-white uppercase tracking-tighter">Tambah Pengguna</h1>
                <p class="text-sm text-slate-500 font-bold uppercase tracking-widest mt-1">Buat Kredensial Akses Petugas Baru</p>
            </div>
        </div>
        <div class="flex items-center gap-4 relative z-10">
            <a href="<?= base_url('users') ?>" class="px-8 py-4 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-slate-700 transition-all active:scale-95">Batal</a>
        </div>
    </div>

    <form action="<?= base_url('users/store') ?>" method="post">
        <?= csrf_field() ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Data Kredensial -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
                    <div class="p-8 border-b dark:border-slate-800 bg-blue-50/30 dark:bg-blue-950/30 flex items-center gap-4">
                        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                            <i data-lucide="key" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="text-xs font-black text-blue-900 dark:text-blue-400 uppercase tracking-[0.2em]">Informasi Login</h3>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Kredensial Keamanan Akun</p>
                        </div>
                    </div>
                    <div class="p-10 space-y-8">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Username Pengguna</label>
                            <input type="text" name="username" required class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-black" placeholder="EX: PETUGAS_SINJAI">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Kata Sandi</label>
                                <input type="password" name="password" required class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold" placeholder="••••••••">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Konfirmasi Kata Sandi</label>
                                <input type="password" name="password_confirm" required class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold" placeholder="••••••••">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
                    <div class="p-8 border-b dark:border-slate-800 bg-indigo-50/30 dark:bg-indigo-950/30 flex items-center gap-4">
                        <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                            <i data-lucide="building" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="text-xs font-black text-indigo-900 dark:text-indigo-400 uppercase tracking-[0.2em]">Profil Instansi</h3>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Afiliasi Kerja Petugas</p>
                        </div>
                    </div>
                    <div class="p-10">
                        <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Nama Instansi / Dinas / Desa</label>
                        <input type="text" name="instansi" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 dark:text-slate-200 outline-none transition-all font-bold uppercase" placeholder="EX: DINAS PERUMAHAN KAB. SINJAI">
                    </div>
                </div>
            </div>

            <!-- Role & Scope -->
            <div class="space-y-8">
                <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
                    <div class="p-8 border-b dark:border-slate-800 bg-amber-50/30 dark:bg-amber-950/30 flex items-center gap-4">
                        <div class="w-10 h-10 bg-amber-500 rounded-xl flex items-center justify-center text-white shadow-lg">
                            <i data-lucide="shield" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="text-xs font-black text-amber-900 dark:text-amber-400 uppercase tracking-[0.2em]">Otoritas</h3>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Hak Akses Sistem</p>
                        </div>
                    </div>
                    <div class="p-10 space-y-10">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-4 tracking-widest ml-1">Pilih Role Utama</label>
                            <div class="space-y-3">
                                <?php foreach($roles as $role): ?>
                                <label class="flex items-center p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl cursor-pointer hover:border-blue-500 transition-all group">
                                    <input type="radio" name="role_id" value="<?= $role['id'] ?>" required class="w-5 h-5 text-blue-600 focus:ring-blue-500/20 border-slate-300">
                                    <div class="ml-4">
                                        <p class="text-xs font-black text-slate-700 dark:text-slate-200 uppercase tracking-tight"><?= $role['role_name'] ?></p>
                                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Scope: <?= $role['scope'] ?></p>
                                    </div>
                                </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div id="desa-assignment" class="pt-8 border-t dark:border-slate-800">
                            <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-4 tracking-widest ml-1">Penugasan Wilayah (Desa)</label>
                            <p class="text-[9px] text-slate-400 font-medium mb-4 italic">Opsional: Biarkan kosong jika user memiliki akses global.</p>
                            <div class="max-h-64 overflow-y-auto space-y-2 pr-2 custom-scrollbar">
                                <?php foreach($desa_list as $desa): ?>
                                <label class="flex items-center p-3 hover:bg-slate-50 dark:hover:bg-slate-800/50 rounded-xl transition-all cursor-pointer group">
                                    <input type="checkbox" name="desa_ids[]" value="<?= $desa['desa_id'] ?>" class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500/20 border-slate-300">
                                    <span class="ml-3 text-[11px] font-bold text-slate-600 dark:text-slate-400 group-hover:text-blue-600 uppercase tracking-tight transition-colors"><?= $desa['desa'] ?></span>
                                </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="flex flex-col md:flex-row justify-between items-center gap-6 pt-8">
            <div class="flex items-center gap-4 text-slate-400">
                <i data-lucide="info" class="w-5 h-5"></i>
                <p class="text-[10px] font-bold uppercase tracking-widest leading-relaxed max-w-md">Kredensial pengguna baru akan langsung aktif setelah proses penyimpanan selesai.</p>
            </div>
            <button type="submit" class="group flex items-center space-x-8 bg-blue-600 hover:bg-blue-700 text-white pl-12 pr-6 py-6 rounded-[2.5rem] font-black shadow-2xl shadow-blue-600/20 transition-all active:scale-95 w-full md:w-auto">
                <div class="flex flex-col text-right">
                    <span class="text-[10px] uppercase tracking-[0.3em] opacity-60 mb-1">Konfirmasi Registrasi</span>
                    <span class="text-xl uppercase tracking-tighter">Simpan Akun</span>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center group-hover:translate-x-2 transition-transform">
                    <i data-lucide="save" class="w-6 h-6"></i>
                </div>
            </button>
        </div>
    </form>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.1); border-radius: 10px; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>
<?= $this->endSection() ?>
