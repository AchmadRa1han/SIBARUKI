<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'SIBARUKI - Sistem Informasi Bantuan Rumah & Kawasan Permukiman' ?></title>
    <link rel="shortcut icon" type="image/png" href="<?= base_url('sinjai.png') ?>">
    <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <style>
        .glass-nav {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .dark .glass-nav {
            background: rgba(15, 23, 42, 0.7);
        }
        .hero-gradient {
            background: radial-gradient(circle at top right, rgba(37, 99, 235, 0.1), transparent),
                        radial-gradient(circle at bottom left, rgba(79, 70, 229, 0.1), transparent);
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 font-sans text-slate-900 dark:text-slate-100 transition-colors duration-300">

    <!-- Navbar -->
    <nav class="fixed top-0 inset-x-0 z-[1000] glass-nav border-b border-slate-200/50 dark:border-slate-800/50">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 h-20 flex items-center justify-between">
            <div class="flex items-center gap-4 group cursor-pointer">
                <img src="<?= base_url('sinjai.png') ?>" alt="Logo" class="w-10 h-10 group-hover:scale-110 transition-transform">
                <span class="text-xl font-black tracking-tighter text-blue-950 dark:text-white uppercase">SIBARUKI</span>
            </div>

            <div class="flex items-center gap-6">
                <button onclick="toggleTheme()" class="p-2.5 bg-slate-100 dark:bg-slate-800 rounded-xl text-slate-500 hover:text-blue-600 transition-all">
                    <i data-lucide="sun" id="sun-icon" class="w-5 h-5 hidden dark:block"></i>
                    <i data-lucide="moon" id="moon-icon" class="w-5 h-5 block dark:hidden"></i>
                </button>
                
                <?php if (session()->get('isLoggedIn')): ?>
                <a href="<?= base_url('dashboard') ?>" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl text-sm font-black uppercase tracking-widest shadow-xl shadow-indigo-600/20 transition-all active:scale-95 flex items-center gap-2">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                    Dashboard
                </a>
                <?php else: ?>
                <a href="<?= base_url('login') ?>" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-sm font-black uppercase tracking-widest shadow-xl shadow-blue-600/20 transition-all active:scale-95">
                    Login
                </a>
                <?php endif; ?>
            </div>

            <!-- Mobile Menu Toggle -->
            <button class="md:hidden p-3 bg-slate-100 dark:bg-slate-800 rounded-xl">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-20">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800 pt-20 pb-10">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-16 mb-16">
                <!-- Links -->
                <div>
                    <h4 class="text-[11px] font-black uppercase tracking-[0.3em] mb-8 text-blue-600 dark:text-blue-400">Tautan Cepat</h4>
                    <ul class="space-y-5">
                        <li>
                            <a href="https://www.instagram.com/disperkimtan.sinjai/" target="_blank" class="group flex items-center gap-3 text-sm font-bold text-slate-500 hover:text-blue-600 transition-all">
                                <span class="w-8 h-8 rounded-lg bg-slate-50 dark:bg-slate-800 flex items-center justify-center group-hover:bg-blue-50 dark:group-hover:bg-blue-900/30 transition-colors">
                                    <i data-lucide="instagram" class="w-4 h-4" stroke-width="3"></i>
                                </span>
                                Instagram DPKPP
                            </a>
                        </li>
                        <li>
                            <a href="https://dpkpp.sinjaikab.go.id/" target="_blank" class="group flex items-center gap-3 text-sm font-bold text-slate-500 hover:text-blue-600 transition-all">
                                <span class="w-8 h-8 rounded-lg bg-slate-50 dark:bg-slate-800 flex items-center justify-center group-hover:bg-blue-50 dark:group-hover:bg-blue-900/30 transition-colors">
                                    <i data-lucide="globe" class="w-4 h-4" stroke-width="3"></i>
                                </span>
                                Website Resmi DPKPP
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Location -->
                <div>
                    <h4 class="text-[11px] font-black uppercase tracking-[0.3em] mb-8 text-blue-600 dark:text-blue-400">Kantor Pelayanan</h4>
                    <div class="flex gap-4">
                        <span class="flex-shrink-0 w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/30 text-blue-600 flex items-center justify-center">
                            <i data-lucide="map-pin" class="w-5 h-5" stroke-width="2.5"></i>
                        </span>
                        <div>
                            <p class="text-sm font-black text-blue-950 dark:text-white uppercase mb-1">Alamat Utama</p>
                            <p class="text-sm font-medium text-slate-500 dark:text-slate-400 leading-relaxed">
                                Jalan Persatuan Raya No. 116, Kelurahan Biringere<br>
                                Kecamatan Sinjai Utara, Kab. Sinjai, 92611
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="pt-10 border-t border-slate-100 dark:border-slate-800 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                        &copy; <?= date('Y') ?> SIBARUKI v1.0 <span class="mx-2 text-slate-200 dark:text-slate-700">|</span> Pemerintah Kabupaten Sinjai
                    </p>
                </div>
                <div class="flex gap-4 text-[10px] font-black text-slate-300 dark:text-slate-600 uppercase tracking-widest">
                    SIBARUKI • Sinjai
                </div>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/lucide@0.418.0/dist/umd/lucide.min.js"></script>
    <script>
        // Inisialisasi Ikon
        lucide.createIcons();
        
        function toggleTheme() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        }
    </script>
</body>
</html>
