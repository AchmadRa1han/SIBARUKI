<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="space-y-10 pb-24">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-4xl font-black text-blue-950 dark:text-white uppercase tracking-tight">Pengaturan Sistem</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium mt-1">Pusat kendali konfigurasi, manajemen akses, dan pemeliharaan data.</p>
        </div>
        <div class="flex items-center space-x-3 bg-blue-50 dark:bg-blue-900/20 px-6 py-3 rounded-2xl border border-blue-100 dark:border-blue-800">
            <i data-lucide="shield-check" class="w-5 h-5 text-blue-600"></i>
            <span class="text-xs font-black text-blue-900 dark:text-blue-400 uppercase tracking-widest">Administrator Mode</span>
        </div>
    </div>

    <!-- GRID MENU UTAMA -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        
        <!-- Manajemen User -->
        <?php if(has_permission('manage_users') || has_permission('view_users')): ?>
        <a href="<?= base_url('users') ?>" class="group relative bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 overflow-hidden">
            <div class="absolute -right-4 -top-4 w-32 h-32 bg-blue-50 dark:bg-blue-900/10 rounded-full blur-3xl group-hover:bg-blue-100 dark:group-hover:bg-blue-900/20 transition-colors"></div>
            <div class="relative z-10 space-y-6">
                <div class="w-16 h-16 bg-blue-900 dark:bg-blue-700 text-white rounded-3xl flex items-center justify-center shadow-lg shadow-blue-900/30 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                    <i data-lucide="users" class="w-8 h-8 text-white"></i>
                </div>
                <div>
                    <h3 class="text-xl font-black text-blue-950 dark:text-white uppercase tracking-tight mb-2">Manajemen User</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed font-medium">Kelola akun pengguna, reset password, dan pantau status aktif petugas lapangan.</p>
                </div>
                <div class="flex items-center text-blue-600 dark:text-blue-400 text-xs font-black uppercase tracking-widest group-hover:gap-2 transition-all">
                    Buka Menu <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </div>
            </div>
        </a>
        <?php endif; ?>

        <!-- Manajemen Role -->
        <?php if(has_permission('manage_roles')): ?>
        <a href="<?= base_url('roles') ?>" class="group relative bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 overflow-hidden">
            <div class="absolute -right-4 -top-4 w-32 h-32 bg-indigo-50 dark:bg-indigo-900/10 rounded-full blur-3xl group-hover:bg-indigo-100 dark:group-hover:bg-indigo-900/20 transition-colors"></div>
            <div class="relative z-10 space-y-6">
                <div class="w-16 h-16 bg-indigo-900 dark:bg-indigo-700 text-white rounded-3xl flex items-center justify-center shadow-lg shadow-indigo-900/30 group-hover:scale-110 group-hover:-rotate-6 transition-all duration-500">
                    <i data-lucide="shield-check" class="w-8 h-8 text-white"></i>
                </div>
                <div>
                    <h3 class="text-xl font-black text-blue-950 dark:text-white uppercase tracking-tight mb-2">Manajemen Role</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed font-medium">Atur hak akses secara dinamis untuk Admin, Pimpinan, Petugas, hingga Kepala Desa.</p>
                </div>
                <div class="flex items-center text-indigo-600 dark:text-indigo-400 text-xs font-black uppercase tracking-widest group-hover:gap-2 transition-all">
                    Konfigurasi Izin <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </div>
            </div>
        </a>
        <?php endif; ?>

        <!-- Monitoring Aktivitas -->
        <?php if(has_permission('manage_roles')): ?>
        <a href="<?= base_url('logs') ?>" class="group relative bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 overflow-hidden">
            <div class="absolute -right-4 -top-4 w-32 h-32 bg-amber-50 dark:bg-amber-900/10 rounded-full blur-3xl group-hover:bg-amber-100 dark:group-hover:bg-amber-900/20 transition-colors"></div>
            <div class="relative z-10 space-y-6">
                <div class="w-16 h-16 bg-amber-600 dark:bg-amber-700 text-white rounded-3xl flex items-center justify-center shadow-lg shadow-amber-900/30 group-hover:scale-110 group-hover:rotate-12 transition-all duration-500">
                    <i data-lucide="activity" class="w-8 h-8 text-white"></i>
                </div>
                <div>
                    <h3 class="text-xl font-black text-blue-950 dark:text-white uppercase tracking-tight mb-2">Audit Trail</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed font-medium">Lihat rekaman aktivitas sistem, perubahan data teknis, hingga deteksi login yang mencurigakan.</p>
                </div>
                <div class="flex items-center text-amber-600 dark:text-amber-400 text-xs font-black uppercase tracking-widest group-hover:gap-2 transition-all">
                    Lihat Log <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </div>
            </div>
        </a>
        <?php endif; ?>

        <!-- Recycle Bin -->
        <?php if(has_permission('manage_roles')): ?>
        <a href="<?= base_url('trash') ?>" class="group relative bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 overflow-hidden">
            <div class="absolute -right-4 -top-4 w-32 h-32 bg-rose-50 dark:bg-rose-900/10 rounded-full blur-3xl group-hover:bg-rose-100 dark:group-hover:bg-rose-900/20 transition-colors"></div>
            <div class="relative z-10 space-y-6">
                <div class="w-16 h-16 bg-rose-600 dark:bg-rose-700 text-white rounded-3xl flex items-center justify-center shadow-lg shadow-rose-900/30 group-hover:scale-110 group-hover:-rotate-12 transition-all duration-500">
                    <i data-lucide="trash-2" class="w-8 h-8 text-white"></i>
                </div>
                <div>
                    <h3 class="text-xl font-black text-blue-950 dark:text-white uppercase tracking-tight mb-2">Recycle Bin</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed font-medium">Pulihkan data RTLH atau Kumuh yang tidak sengaja terhapus dengan satu kali klik.</p>
                </div>
                <div class="flex items-center text-rose-600 dark:text-rose-400 text-xs font-black uppercase tracking-widest group-hover:gap-2 transition-all">
                    Buka Tempat Sampah <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </div>
            </div>
        </a>
        <?php endif; ?>

        <!-- Referensi Master -->
        <?php if(session()->get('role_name') === 'admin'): ?>
        <a href="<?= base_url('ref-master') ?>" class="group relative bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 overflow-hidden">
            <div class="absolute -right-4 -top-4 w-32 h-32 bg-emerald-50 dark:bg-emerald-900/10 rounded-full blur-3xl group-hover:bg-emerald-100 dark:group-hover:bg-emerald-900/20 transition-colors"></div>
            <div class="relative z-10 space-y-6">
                <div class="w-16 h-16 bg-emerald-600 dark:bg-emerald-700 text-white rounded-3xl flex items-center justify-center shadow-lg shadow-emerald-900/30 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                    <i data-lucide="database" class="w-8 h-8 text-white"></i>
                </div>
                <div>
                    <h3 class="text-xl font-black text-blue-950 dark:text-white uppercase tracking-tight mb-2">Referensi Dropdown</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed font-medium">Kelola pilihan dropdown instrumen teknis (Material, Kondisi, dll).</p>
                </div>
                <div class="flex items-center text-emerald-600 dark:text-emerald-400 text-xs font-black uppercase tracking-widest group-hover:gap-2 transition-all">
                    Kelola Database <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </div>
            </div>
        </a>
        <?php endif; ?>

        <!-- Pengaturan Carousel -->
        <?php if(has_permission('manage_roles')): ?>
        <a href="<?= base_url('settings/carousel') ?>" class="group relative bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 overflow-hidden">
            <div class="absolute -right-4 -top-4 w-32 h-32 bg-blue-50 dark:bg-blue-900/10 rounded-full blur-3xl group-hover:bg-blue-100 dark:group-hover:bg-blue-900/20 transition-colors"></div>
            <div class="relative z-10 space-y-6">
                <div class="w-16 h-16 bg-blue-600 dark:bg-blue-700 text-white rounded-3xl flex items-center justify-center shadow-lg shadow-blue-900/30 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                    <i data-lucide="layout-template" class="w-8 h-8 text-white"></i>
                </div>
                <div>
                    <h3 class="text-xl font-black text-blue-950 dark:text-white uppercase tracking-tight mb-2">Carousel Hero</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed font-medium">Ganti gambar dan teks promosi pada halaman depan (Landing Page) secara mandiri.</p>
                </div>
                <div class="flex items-center text-blue-600 dark:text-blue-400 text-xs font-black uppercase tracking-widest group-hover:gap-2 transition-all">
                    Atur Konten <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </div>
            </div>
        </a>
        <?php endif; ?>

        <!-- Histori Perubahan (RLH) -->
        <?php if(session()->get('role_id') == 1): ?>
        <a href="<?= base_url('rtlh/history-transformasi') ?>" class="group relative bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 overflow-hidden">
            <div class="absolute -right-4 -top-4 w-32 h-32 bg-indigo-50 dark:bg-indigo-900/10 rounded-full blur-3xl group-hover:bg-indigo-100 dark:group-hover:bg-indigo-900/20 transition-colors"></div>
            <div class="relative z-10 space-y-6">
                <div class="w-16 h-16 bg-indigo-600 dark:bg-indigo-700 text-white rounded-3xl flex items-center justify-center shadow-lg shadow-indigo-900/30 group-hover:scale-110 group-hover:-rotate-6 transition-all duration-500">
                    <i data-lucide="award" class="w-8 h-8 text-white"></i>
                </div>
                <div>
                    <h3 class="text-xl font-black text-blue-950 dark:text-white uppercase tracking-tight mb-2">Histori Perubahan (RLH)</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed font-medium">Rekam jejak transformasi rumah dari tidak layak (RTLH) menjadi layak huni (RLH).</p>
                </div>
                <div class="flex items-center text-indigo-600 dark:text-indigo-400 text-xs font-black uppercase tracking-widest group-hover:gap-2 transition-all">
                    Lihat Histori <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </div>
            </div>
        </a>
        <?php endif; ?>

        <!-- Profil Instansi (Coming Soon) -->
        <div class="group relative bg-slate-50/50 dark:bg-slate-950 p-8 rounded-[2.5rem] border border-dashed border-slate-200 dark:border-slate-800 transition-all duration-500">
            <div class="relative z-10 space-y-6 opacity-60 group-hover:opacity-100 transition-opacity">
                <div class="w-16 h-16 bg-slate-200 dark:bg-slate-800 text-slate-400 rounded-3xl flex items-center justify-center">
                    <i data-lucide="building-2" class="w-8 h-8"></i>
                </div>
                <div>
                    <h3 class="text-xl font-black text-slate-400 dark:text-slate-500 uppercase tracking-tight mb-2">Profil Instansi</h3>
                    <p class="text-sm text-slate-400 dark:text-slate-600 leading-relaxed font-medium italic">Segera: Konfigurasi nama dinas, logo, dan pimpinan untuk laporan PDF.</p>
                </div>
                <div class="bg-slate-100 dark:bg-slate-800/50 text-[9px] font-black uppercase tracking-widest px-3 py-1 rounded-full w-fit text-slate-400">
                    Coming Soon
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    lucide.createIcons();
</script>
<?= $this->endSection() ?>
