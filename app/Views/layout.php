<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'SIBARUKI' ?></title>
    <link rel="shortcut icon" type="image/png" href="<?= base_url('sinjai.png') ?>">
    <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-gray-50 dark:bg-slate-950 font-sans flex h-screen overflow-hidden transition-colors duration-300">
    <script>
        // Immediate Theme Check to prevent flash
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- Elegant Page Loader -->
    <div id="page-loader" class="fixed inset-0 z-[10000] bg-white/60 dark:bg-slate-950/60 backdrop-blur-md flex items-center justify-center transition-opacity duration-500 pointer-events-none opacity-0">
        <div class="flex flex-col items-center">
            <!-- Spinner -->
            <div class="w-12 h-12 border-2 border-blue-900/10 border-t-blue-900 dark:border-blue-400/10 dark:border-t-blue-400 rounded-full animate-spin"></div>
            <!-- Logo/Text -->
            <p class="mt-4 text-[10px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-[0.4em] animate-pulse">Memuat Data</p>
        </div>
    </div>

    <!-- Sidebar Modern -->
    <aside class="w-64 bg-slate-900 dark:bg-slate-950 text-slate-300 flex flex-col shrink-0 shadow-xl border-r border-slate-800">
        <div class="p-6 flex items-center space-x-3 border-b border-slate-800">
            <img src="<?= base_url('sinjai.png') ?>" alt="Logo Sinjai" class="w-10 h-10 object-contain">
            <span class="text-xl font-bold text-white tracking-tight">SIBARUKI</span>
        </div>
        
        <nav class="flex-grow p-4 space-y-1 overflow-y-auto">
            <!-- Dashboard -->
            <a href="<?= base_url('dashboard') ?>" class="group flex items-center space-x-3 p-3 rounded-xl transition-all duration-300 <?= (url_is('/') || url_is('dashboard*')) ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800/50 hover:text-white dark:hover:bg-slate-800/30 hover:translate-x-1' ?>">
                <i data-lucide="layout-dashboard" class="w-5 h-5 transition-transform duration-300 group-hover:scale-110"></i>
                <span class="font-medium">Dashboard</span>
            </a>

            <!-- Manajemen User (Admin Only) -->
            <?php if(session()->get('role_name') === 'admin'): ?>
            <a href="<?= base_url('users') ?>" class="group flex items-center space-x-3 p-3 rounded-xl transition-all duration-300 <?= (url_is('users*')) ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800/50 hover:text-white dark:hover:bg-slate-800/30 hover:translate-x-1' ?>">
                <i data-lucide="users" class="w-5 h-5 transition-transform duration-300 group-hover:scale-110"></i>
                <span class="font-medium">Manajemen User</span>
            </a>
            <?php endif; ?>

            <!-- Dropdown 1: Data Perumahan -->
            <div class="pt-2">
                <button onclick="toggleDropdown('dropdown-perumahan', 'arrow-perumahan')" class="w-full flex justify-between items-center p-3 rounded-xl transition-all duration-300 hover:bg-slate-800/50 hover:text-white dark:hover:bg-slate-800/30 hover:translate-x-1 group">
                    <div class="flex items-center space-x-3">
                        <i data-lucide="home" class="w-5 h-5 transition-transform duration-300 group-hover:scale-110"></i>
                        <span class="font-medium">Data Perumahan</span>
                    </div>
                    <i id="arrow-perumahan" data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-300"></i>
                </button>
                <div id="dropdown-perumahan" class="hidden pl-11 mt-1 space-y-1 text-slate-400">
                    <?php if(session()->get('role_name') === 'admin'): ?>
                    <a href="<?= base_url('ref-master') ?>" class="block p-2 text-sm rounded-md transition-all duration-300 hover:bg-slate-800/50 hover:text-white dark:hover:bg-slate-800/30 hover:translate-x-1 <?= (url_is('ref-master*')) ? 'bg-blue-600 text-white px-3' : '' ?>">
                        Referensi Master
                    </a>
                    <?php endif; ?>
                    <a href="<?= base_url('rtlh') ?>" class="block p-2 text-sm rounded-md transition-all duration-300 hover:bg-slate-800/50 hover:text-white dark:hover:bg-slate-800/30 hover:translate-x-1 <?= (url_is('rtlh*')) ? 'bg-blue-600 text-white px-3' : '' ?>">
                        Data RTLH
                    </a>
                </div>
            </div>

            <!-- Dropdown 2: Data Kawasan Permukiman -->
            <div class="pt-2">
                <button onclick="toggleDropdown('dropdown-permukiman', 'arrow-permukiman')" class="w-full flex justify-between items-center p-3 rounded-xl transition-all duration-300 hover:bg-slate-800/50 hover:text-white dark:hover:bg-slate-800/30 hover:translate-x-1 group">
                    <div class="flex items-center space-x-3">
                        <i data-lucide="map" class="w-5 h-5 transition-transform duration-300 group-hover:scale-110"></i>
                        <span class="font-medium">Data Permukiman</span>
                    </div>
                    <i id="arrow-permukiman" data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-300"></i>
                </button>
                <div id="dropdown-permukiman" class="hidden pl-11 mt-1 space-y-1 text-slate-400">
                    <a href="<?= base_url('wilayah-kumuh') ?>" class="block p-2 text-sm rounded-md transition-all duration-300 hover:bg-slate-800/50 hover:text-white dark:hover:bg-slate-800/30 hover:translate-x-1 <?= (url_is('wilayah-kumuh*')) ? 'bg-blue-600 text-white px-3' : '' ?>">
                        Wilayah Kumuh
                    </a>
                </div>
            </div>

            <div class="pt-4 pb-2 px-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em]">Sistem</div>
            <a href="#" class="group flex items-center space-x-3 p-3 rounded-xl hover:bg-slate-800/50 hover:text-white dark:hover:bg-slate-800/30 transition-all duration-300 hover:translate-x-1">
                <i data-lucide="settings" class="w-5 h-5 transition-transform duration-300 group-hover:rotate-45"></i>
                <span class="font-medium">Pengaturan</span>
            </a>
        </nav>

        <div class="p-4 border-t border-slate-800 bg-slate-900/50 dark:bg-slate-950/50">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white font-bold shadow-inner uppercase">
                    <?= substr(session()->get('username') ?? 'A', 0, 1) ?>
                </div>
                <div class="flex-grow min-w-0">
                    <p class="text-sm font-semibold text-white truncate"><?= session()->get('username') ?? 'User' ?></p>
                    <p class="text-xs text-slate-500 truncate"><?= session()->get('instansi') ?? 'Dinas Perkim' ?></p>
                </div>
                <a href="<?= base_url('logout') ?>" class="text-slate-500 hover:text-rose-500 transition-colors" title="Keluar">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                </a>
            </div>
        </div>
    </aside>

    <!-- Konten Utama -->
    <div class="flex-grow flex flex-col min-w-0 overflow-hidden">
        <!-- Header -->
        <header class="bg-white dark:bg-slate-900 border-b dark:border-slate-800 h-20 flex items-center justify-between px-8 shrink-0 shadow-sm z-10 transition-colors duration-300">
            <div class="flex items-center flex-grow max-w-md">
                <?php if (!url_is('/') && !url_is('/dashboard')): ?>
                <form action="" method="get" class="relative w-full">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="keyword" value="<?= request()->getGet('keyword') ?>" placeholder="Cari data..." class="w-full pl-10 pr-4 py-2 bg-gray-100 dark:bg-slate-800 border-transparent rounded-xl text-sm focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:text-slate-200 transition-all outline-none">
                </form>
                <?php endif; ?>
            </div>            
            <div class="flex items-center space-x-6">
                <!-- Theme Toggle -->
                <button onclick="toggleTheme()" class="p-2.5 text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all group relative overflow-hidden">
                    <div id="sun-icon" class="transition-transform duration-500">
                        <i data-lucide="sun" class="w-5 h-5 group-hover:rotate-90"></i>
                    </div>
                    <div id="moon-icon" class="absolute inset-0 flex items-center justify-center transition-transform duration-500 translate-y-full">
                        <i data-lucide="moon" class="w-5 h-5 group-hover:-rotate-12"></i>
                    </div>
                </button>

                <div class="relative group">
                    <button class="p-2 text-gray-500 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-full transition-colors relative">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        <span class="absolute top-2 right-2.5 w-2 h-2 bg-rose-500 border-2 border-white dark:border-slate-900 rounded-full"></span>
                    </button>
                </div>
                <div class="h-8 w-px bg-gray-200 dark:bg-slate-800"></div>
                <div class="flex items-center space-x-3 cursor-pointer group">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-gray-800 dark:text-slate-200 group-hover:text-blue-600 transition-colors capitalize"><?= session()->get('username') ?></p>
                        <p class="text-[10px] text-gray-500 dark:text-slate-500 font-medium uppercase tracking-wider"><?= session()->get('role_name') ?></p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Area Scroll Konten -->
        <main id="main-content" class="p-8 overflow-y-auto scroll-smooth dark:text-slate-300">
            <?= $this->renderSection('content') ?>
        </main>
    </div>

    <!-- Floating Support Button -->
    <div class="fixed bottom-8 right-8 z-[50] flex items-center group">
        <!-- Tooltip / Label yang muncul saat hover -->
        <div class="bg-blue-950 text-white text-[10px] font-black uppercase tracking-widest px-4 py-3 rounded-xl mr-3 shadow-2xl opacity-0 group-hover:opacity-100 translate-x-4 group-hover:translate-x-0 transition-all duration-300 pointer-events-none whitespace-nowrap">
            Hubungi Bantuan
        </div>
        <!-- Tombol Utama -->
        <button class="w-14 h-14 bg-blue-950 text-white rounded-2xl shadow-2xl flex items-center justify-center hover:bg-blue-900 hover:rotate-12 transition-all duration-300 active:scale-95">
            <i data-lucide="message-square" class="w-6 h-6"></i>
        </button>
    </div>

    <script>
        lucide.createIcons();

        // --- LIVE SEARCH LOGIC ---
        const searchInput = document.querySelector('input[name="keyword"]');
        const mainContent = document.getElementById('main-content');
        let searchTimeout;

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                
                searchTimeout = setTimeout(() => {
                    const keyword = searchInput.value;
                    const url = new URL(window.location.href);
                    url.searchParams.set('keyword', keyword);

                    // Efek loading halus
                    mainContent.style.opacity = '0.5';

                    fetch(url)
                        .then(response => response.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const newContent = doc.getElementById('main-content').innerHTML;
                            
                            mainContent.innerHTML = newContent;
                            mainContent.style.opacity = '1';
                            
                            // Update URL tanpa reload
                            window.history.pushState({}, '', url);
                            
                            // Re-init Lucide Icons
                            lucide.createIcons();
                        })
                        .catch(err => {
                            console.error('Search error:', err);
                            mainContent.style.opacity = '1';
                        });
                }, 400); // 400ms delay
            });

            // Prevent Enter key from reloading page
            const searchForm = searchInput.closest('form');
            if (searchForm) {
                searchForm.addEventListener('submit', (e) => e.preventDefault());
            }
        }
        // --- END LIVE SEARCH ---

        // Elegant Loader Logic
        const loader = document.getElementById('page-loader');

        // Theme Toggle Logic
        function toggleTheme() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateThemeIcons(isDark);
        }

        function updateThemeIcons(isDark) {
            const sun = document.getElementById('sun-icon');
            const moon = document.getElementById('moon-icon');
            if (isDark) {
                sun.style.transform = 'translateY(-150%)';
                moon.style.transform = 'translateY(0)';
            } else {
                sun.style.transform = 'translateY(0)';
                moon.style.transform = 'translateY(100%)';
            }
        }

        // Initialize icons on load
        document.addEventListener('DOMContentLoaded', () => {
            const isDark = document.documentElement.classList.contains('dark');
            updateThemeIcons(isDark);
        });

        // 1. Sembunyikan saat halaman sudah siap (termasuk saat Back/Forward)
        window.addEventListener('pageshow', (event) => {
            loader.classList.add('opacity-0');
            loader.classList.add('pointer-events-none');
        });

        // 2. Tampilkan saat berpindah halaman melalui link
        document.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (
                    href && 
                    !href.startsWith('#') && 
                    !href.startsWith('javascript:') && 
                    !e.metaKey && !e.ctrlKey && // Biarkan jika user buka di tab baru (ctrl+click)
                    this.getAttribute('target') !== '_blank'
                ) {
                    // Tampilkan loader
                    loader.classList.remove('opacity-0');
                    loader.classList.remove('pointer-events-none');
                }
            });
        });

        function toggleDropdown(id, arrowId) {
            const dropdown = document.getElementById(id);
            const arrow = document.getElementById(arrowId);
            const isOpen = !dropdown.classList.contains('hidden');
            
            if (!isOpen) {
                dropdown.classList.remove('hidden');
                if (arrow) arrow.style.transform = 'rotate(180deg)';
                localStorage.setItem(id, 'open');
            } else {
                dropdown.classList.add('hidden');
                if (arrow) arrow.style.transform = 'rotate(0deg)';
                localStorage.setItem(id, 'closed');
            }
        }

        // Auto-open logic with Memory
        document.addEventListener('DOMContentLoaded', () => {
            const path = window.location.pathname;
            const dropdowns = [
                { id: 'dropdown-perumahan', arrow: 'arrow-perumahan', paths: ['ref-master', 'rtlh'] },
                { id: 'dropdown-permukiman', arrow: 'arrow-permukiman', paths: ['wilayah-kumuh'] }
            ];

            dropdowns.forEach(item => {
                const dropdown = document.getElementById(item.id);
                const arrow = document.getElementById(item.arrow);
                const savedState = localStorage.getItem(item.id);
                
                // Cek apakah URL aktif berada di dalam menu ini
                const isPathActive = item.paths.some(p => path.includes(p));

                // Buka jika: Ada di memory 'open' ATAU URL-nya sedang aktif
                if (savedState === 'open' || isPathActive) {
                    dropdown.classList.remove('hidden');
                    if (arrow) arrow.style.transform = 'rotate(180deg)';
                    
                    // Jika tadinya tertutup di memory tapi URL aktif, simpan sebagai open
                    if (isPathActive) localStorage.setItem(item.id, 'open');
                }
            });
        });
    </script>
</body>
</html>
