<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'SIBARUKI - Sistem Informasi Bantuan Rumah & Kawasan Permukiman' ?></title>
    <link rel="shortcut icon" type="image/png" href="<?= base_url('sinjai.png') ?>">
    <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
    <script src="https://unpkg.com/lucide@0.407.0/dist/umd/lucide.js"></script>
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
        .glass-nav { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }
        .dark .glass-nav { background: rgba(15, 23, 42, 0.7); }
        .hero-gradient { background: radial-gradient(circle at top right, rgba(37, 99, 235, 0.1), transparent), radial-gradient(circle at bottom left, rgba(79, 70, 229, 0.1), transparent); }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 font-sans text-slate-900 dark:text-slate-100 transition-colors duration-300">

    <!-- Navbar -->
    <nav class="fixed top-0 inset-x-0 z-[1000] glass-nav border-b border-slate-200/50 dark:border-slate-800/50 h-16 flex items-center">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 w-full flex items-center justify-between">
            <div class="flex items-center gap-3 group cursor-pointer">
                <img src="<?= base_url('sinjai.png') ?>" alt="Logo" class="w-8 h-8 group-hover:scale-110 transition-transform">
                <span class="text-lg font-bold tracking-tighter text-blue-950 dark:text-white uppercase">SIBARUKI</span>
            </div>

            <div class="flex items-center gap-4">
                <button onclick="toggleTheme()" class="p-2 bg-slate-100 dark:bg-slate-800 rounded-xl text-slate-500 hover:text-blue-600 transition-all active:scale-90">
                    <i data-lucide="sun" id="sun-icon" class="w-4.5 h-4.5 hidden dark:block"></i>
                    <i data-lucide="moon" id="moon-icon" class="w-4.5 h-4.5 block dark:hidden"></i>
                </button>
                
                <?php if (session()->get('isLoggedIn')): ?>
                <a href="<?= base_url('dashboard') ?>" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-[10px] font-bold uppercase tracking-widest shadow-lg shadow-indigo-600/20 transition-all active:scale-95 flex items-center gap-2">
                    <i data-lucide="layout-dashboard" class="w-3.5 h-3.5"></i> Dashboard
                </a>
                <?php else: ?>
                <a href="<?= base_url('login') ?>" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-[10px] font-bold uppercase tracking-widest shadow-lg shadow-blue-600/20 transition-all active:scale-95">
                    Login Akses
                </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-16">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-12">
                <div>
                    <h4 class="text-[9px] font-bold uppercase tracking-[0.3em] mb-6 text-blue-600 dark:text-blue-400">Tautan Cepat</h4>
                    <ul class="space-y-4">
                        <li>
                            <a href="https://www.instagram.com/disperkimtan.sinjai/" target="_blank" class="group flex items-center gap-3 text-sm font-bold text-slate-500 hover:text-blue-600 transition-all">
                                <span class="w-8 h-8 rounded-lg bg-slate-50 dark:bg-slate-800 flex items-center justify-center group-hover:bg-blue-50 dark:group-hover:bg-blue-900/30 transition-colors">
                                    <i data-lucide="instagram" class="w-4 h-4"></i>
                                </span>
                                Instagram DPKPP
                            </a>
                        </li>
                        <li>
                            <a href="https://dpkpp.sinjaikab.go.id/" target="_blank" class="group flex items-center gap-3 text-sm font-bold text-slate-500 hover:text-blue-600 transition-all">
                                <span class="w-8 h-8 rounded-lg bg-slate-50 dark:bg-slate-800 flex items-center justify-center group-hover:bg-blue-50 dark:group-hover:bg-blue-900/30 transition-colors">
                                    <i data-lucide="globe" class="w-4 h-4"></i>
                                </span>
                                Website Resmi DPKPP
                            </a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-[9px] font-bold uppercase tracking-[0.3em] mb-6 text-blue-600 dark:text-blue-400">Kantor Pelayanan</h4>
                    <div class="flex gap-4">
                        <span class="flex-shrink-0 w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-950/30 text-blue-600 flex items-center justify-center">
                            <i data-lucide="map-pin" class="w-4.5 h-4.5" stroke-width="2.5"></i>
                        </span>
                        <div>
                            <p class="text-sm font-bold text-blue-950 dark:text-white uppercase mb-1">Alamat Utama</p>
                            <p class="text-xs font-medium text-slate-500 dark:text-slate-400 leading-relaxed">
                                Jl. Persatuan Raya No. 116, Kel. Biringere<br>
                                Kec. Sinjai Utara, Kab. Sinjai, 92611
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-100 dark:border-slate-800 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest text-center md:text-left">
                    &copy; <?= date('Y') ?> SIBARUKI v1.0 <span class="mx-2 opacity-20">|</span> Pemkab Sinjai
                </p>
                <div class="flex gap-4 text-[9px] font-bold text-slate-300 dark:text-slate-600 uppercase tracking-widest">
                    SIBARUKI • Sinjai
                </div>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();
        function toggleTheme() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        }
        // Double check initialization
        window.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
</body>
</html>
