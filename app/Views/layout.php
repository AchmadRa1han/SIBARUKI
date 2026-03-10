<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= $title ?? 'SIBARUKI' ?></title>
    <link rel="shortcut icon" type="image/png" href="<?= base_url('sinjai.png') ?>">
    <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <style>
        #sidebar-nav::-webkit-scrollbar { width: 4px; }
        #sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        #sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
        
        @media (max-width: 1023px) {
            #main-sidebar {
                background: rgba(15, 23, 42, 0.98) !important;
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
            }
        }
        * { -webkit-tap-highlight-color: transparent; }
        .sidebar-transition { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    </style>
</head>
<body class="bg-gray-50 dark:bg-slate-950 font-sans flex h-screen overflow-hidden transition-colors duration-300">
    
    <!-- Mobile Overlay -->
    <div id="sidebar-overlay" onclick="toggleMobileSidebar()" class="fixed inset-0 bg-slate-900/60 backdrop-blur-[2px] z-[60] hidden transition-opacity duration-300 opacity-0 cursor-pointer"></div>

    <!-- Elegant Page Loader -->
    <div id="page-loader" class="fixed inset-0 z-[10000] bg-white/60 dark:bg-slate-950/60 backdrop-blur-md flex items-center justify-center transition-opacity duration-500 pointer-events-none opacity-0">
        <div class="flex flex-col items-center">
            <div class="w-12 h-12 border-2 border-blue-900/10 border-t-blue-900 dark:border-blue-400/10 dark:border-t-blue-400 rounded-full animate-spin"></div>
            <p class="mt-4 text-[10px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-[0.4em] animate-pulse">Memuat Data</p>
        </div>
    </div>

    <!-- Sidebar Modern -->
    <aside id="main-sidebar" class="sidebar-transition fixed inset-y-0 left-0 w-72 lg:w-64 bg-slate-900 dark:bg-slate-950 text-slate-300 flex flex-col shrink-0 shadow-2xl lg:shadow-xl border-r border-slate-800 z-[70] -translate-x-full lg:translate-x-0 lg:static group/sidebar">
        
        <!-- Floating Toggle Button (Desktop Only) -->
        <button onclick="toggleSidebar()" class="hidden lg:flex absolute top-1/2 -right-4 -translate-y-1/2 w-8 h-52 bg-slate-900 dark:bg-slate-950 border-y border-r border-slate-800 rounded-r-[2.5rem] items-center justify-center z-[80] hover:bg-blue-600 hover:border-blue-600 text-slate-500 hover:text-white transition-all duration-300 shadow-xl group/btn active:scale-95">
            <i id="sidebar-toggle-icon" data-lucide="chevron-left" class="w-5 h-5 transition-transform duration-500"></i>
        </button>

        <!-- Sidebar Header -->
        <div class="p-6 flex items-center justify-between border-b border-slate-800 shrink-0 h-20 transition-all duration-300">
            <div class="flex items-center space-x-3 overflow-hidden sidebar-content transition-all duration-300">
                <img src="<?= base_url('sinjai.png') ?>" alt="Logo Sinjai" class="w-10 h-10 object-contain shrink-0">
                <span class="text-xl font-bold text-white tracking-tight whitespace-nowrap">SIBARUKI</span>
            </div>
            <!-- Close Button Mobile -->
            <button onclick="toggleMobileSidebar()" class="lg:hidden p-3 bg-slate-800/50 hover:bg-rose-600 hover:text-white rounded-2xl transition-all">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        
        <!-- Sidebar Navigation -->
        <nav id="sidebar-nav" class="flex-grow p-4 space-y-1 overflow-y-auto scroll-smooth">
            <!-- Dashboard -->
            <a href="<?= base_url('dashboard') ?>" class="group flex items-center space-x-3 p-4 lg:p-3 rounded-2xl lg:rounded-xl transition-all duration-300 <?= (url_is('/') || url_is('dashboard*')) ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800/50 hover:text-white dark:hover:bg-slate-800/30 hover:translate-x-1' ?>">
                <i data-lucide="layout-dashboard" class="w-5 h-5 shrink-0 transition-transform duration-300 group-hover:scale-110"></i>
                <span class="font-bold lg:font-medium whitespace-nowrap sidebar-content transition-all duration-300">Dashboard</span>
            </a>

            <!-- Dropdown 1: Data Perumahan -->
            <div class="pt-2">
                <button onclick="toggleDropdown('dropdown-perumahan', 'arrow-perumahan')" class="w-full flex justify-between items-center p-4 lg:p-3 rounded-2xl lg:rounded-xl transition-all duration-300 hover:bg-slate-800/50 hover:text-white dark:hover:bg-slate-800/30 hover:translate-x-1 group">
                    <div class="flex items-center space-x-3">
                        <i data-lucide="home" class="w-5 h-5 shrink-0 transition-transform duration-300 group-hover:scale-110"></i>
                        <span class="font-bold lg:font-medium whitespace-nowrap sidebar-content transition-all duration-300">Data Perumahan</span>
                    </div>
                    <i id="arrow-perumahan" data-lucide="chevron-down" class="w-4 h-4 shrink-0 transition-transform duration-300 sidebar-content"></i>
                </button>
                <div id="dropdown-perumahan" class="hidden pl-11 mt-1 space-y-1 text-slate-400">
                    <a href="<?= base_url('rtlh') ?>" class="block p-3 lg:p-2 text-[15px] lg:text-sm rounded-xl lg:rounded-md transition-all duration-300 hover:bg-slate-800/50 hover:text-white dark:hover:bg-slate-800/30 hover:translate-x-1 <?= (url_is('rtlh') || (url_is('rtlh/*') && !url_is('rtlh/rekap-desa'))) ? 'bg-blue-600 text-white px-4 lg:px-3' : '' ?> sidebar-content">RTLH</a>
                    <?php if (session()->get('role_id') == 1): ?>
                    <a href="<?= base_url('rtlh/rekap-desa') ?>" class="block p-3 lg:p-2 text-[15px] lg:text-sm rounded-xl lg:rounded-md transition-all duration-300 hover:bg-slate-800/50 hover:text-white dark:hover:bg-slate-800/30 hover:translate-x-1 <?= (url_is('rtlh/rekap-desa*')) ? 'bg-blue-600 text-white px-4 lg:px-3' : '' ?> sidebar-content">Rekapitulasi Desa</a>
                    <?php endif; ?>
                    <a href="<?= base_url('psu') ?>" class="block p-3 lg:p-2 text-[15px] lg:text-sm rounded-xl lg:rounded-md transition-all duration-300 hover:bg-slate-800/50 hover:text-white dark:hover:bg-slate-800/30 hover:translate-x-1 <?= (url_is('psu*')) ? 'bg-blue-600 text-white px-4 lg:px-3' : '' ?> sidebar-content">PSU Terbangun</a>
                    <a href="<?= base_url('perumahan-formal') ?>" class="block p-3 lg:p-2 text-[15px] lg:text-sm rounded-xl lg:rounded-md transition-all duration-300 hover:bg-slate-800/50 hover:text-white dark:hover:bg-slate-800/30 hover:translate-x-1 <?= (url_is('perumahan-formal*')) ? 'bg-blue-600 text-white px-4 lg:px-3' : '' ?> sidebar-content">Perumahan Formal</a>
                    <a href="<?= base_url('bansos-rtlh') ?>" class="block p-3 lg:p-2 text-[15px] lg:text-sm rounded-xl lg:rounded-md transition-all duration-300 hover:bg-slate-800/50 hover:text-white dark:hover:bg-slate-800/30 hover:translate-x-1 <?= (url_is('bansos-rtlh*')) ? 'bg-blue-600 text-white px-4 lg:px-3' : '' ?> sidebar-content">Bansos RTLH</a>
                </div>
            </div>

            <!-- Dropdown 2: Data Kawasan Permukiman -->
            <div class="pt-2">
                <button onclick="toggleDropdown('dropdown-permukiman', 'arrow-permukiman')" class="w-full flex justify-between items-center p-4 lg:p-3 rounded-2xl lg:rounded-xl transition-all duration-300 hover:bg-slate-800/50 hover:text-white dark:hover:bg-slate-800/30 hover:translate-x-1 group">
                    <div class="flex items-center space-x-3">
                        <i data-lucide="map" class="w-5 h-5 shrink-0 transition-transform duration-300 group-hover:scale-110"></i>
                        <span class="font-bold lg:font-medium whitespace-nowrap sidebar-content transition-all duration-300">Data Permukiman</span>
                    </div>
                    <i id="arrow-permukiman" data-lucide="chevron-down" class="w-4 h-4 shrink-0 transition-transform duration-300 sidebar-content"></i>
                </button>
                <div id="dropdown-permukiman" class="hidden pl-11 mt-1 space-y-1 text-slate-400">
                    <a href="<?= base_url('wilayah-kumuh') ?>" class="block p-3 lg:p-2 text-[15px] lg:text-sm rounded-xl lg:rounded-md transition-all duration-300 hover:bg-slate-800/50 hover:text-white dark:hover:bg-slate-800/30 hover:translate-x-1 <?= (url_is('wilayah-kumuh')) ? 'bg-blue-600 text-white px-4 lg:px-3' : '' ?> sidebar-content">Wilayah Kumuh</a>
                    <a href="<?= base_url('pisew') ?>" class="block p-3 lg:p-2 text-[15px] lg:text-sm rounded-xl lg:rounded-md transition-all duration-300 hover:bg-slate-800/50 hover:text-white dark:hover:bg-slate-800/30 hover:translate-x-1 <?= (url_is('pisew*')) ? 'bg-blue-600 text-white px-4 lg:px-3' : '' ?> sidebar-content">PISEW</a>
                    <a href="<?= base_url('arsinum') ?>" class="block p-3 lg:p-2 text-[15px] lg:text-sm rounded-xl lg:rounded-md transition-all duration-300 hover:bg-slate-800/50 hover:text-white dark:hover:bg-slate-800/30 hover:translate-x-1 <?= (url_is('arsinum*')) ? 'bg-blue-600 text-white px-4 lg:px-3' : '' ?> sidebar-content">Arsinum</a>
                </div>
            </div>

            <!-- Dropdown 3: Data Pertanahan -->
            <div class="pt-2">
                <button onclick="toggleDropdown('dropdown-pertanahan', 'arrow-pertanahan')" class="w-full flex justify-between items-center p-4 lg:p-3 rounded-2xl lg:rounded-xl transition-all duration-300 hover:bg-slate-800/50 hover:text-white dark:hover:bg-slate-800/30 hover:translate-x-1 group">
                    <div class="flex items-center space-x-3">
                        <i data-lucide="layers" class="w-5 h-5 shrink-0 transition-transform duration-300 group-hover:scale-110"></i>
                        <span class="font-bold lg:font-medium whitespace-nowrap sidebar-content transition-all duration-300">Data Pertanahan</span>
                    </div>
                    <i id="arrow-pertanahan" data-lucide="chevron-down" class="w-4 h-4 shrink-0 transition-transform duration-300 sidebar-content"></i>
                </button>
                <div id="dropdown-pertanahan" class="hidden pl-11 mt-1 space-y-1 text-slate-400">
                    <a href="<?= base_url('aset-tanah') ?>" class="block p-3 lg:p-2 text-[15px] lg:text-sm rounded-xl lg:rounded-md transition-all duration-300 hover:bg-slate-800/50 hover:text-white dark:hover:bg-slate-800/30 hover:translate-x-1 <?= (url_is('aset-tanah*')) ? 'bg-blue-600 text-white px-4 lg:px-3' : '' ?> sidebar-content">Aset Tanah Pemda</a>
                </div>
            </div>

            <div class="pt-4 pb-2 px-3 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] sidebar-content">Sistem</div>
            
            <a href="<?= base_url('settings') ?>" class="group flex items-center space-x-3 p-4 lg:p-3 rounded-2xl lg:rounded-xl transition-all duration-300 <?= (url_is('settings*')) ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800/50 hover:text-white dark:hover:bg-slate-800/30 hover:translate-x-1' ?>">
                <i data-lucide="settings" class="w-5 h-5 shrink-0 transition-transform duration-300 group-hover:rotate-45"></i>
                <span class="font-bold lg:font-medium whitespace-nowrap sidebar-content transition-all duration-300">Pengaturan</span>
            </a>
        </nav>

        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-slate-800 bg-slate-900/50 dark:bg-slate-950/50 shrink-0 h-24 lg:h-20 flex items-center transition-all duration-300 overflow-hidden">
            <div class="flex items-center space-x-4 lg:space-x-3 w-full">
                <div class="w-12 h-12 lg:w-10 lg:h-10 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white font-bold shadow-inner uppercase shrink-0">
                    <?= substr(session()->get('username') ?? 'A', 0, 1) ?>
                </div>
                <div class="flex-grow min-w-0 sidebar-content transition-all duration-300">
                    <p class="text-[15px] lg:text-sm font-bold text-white truncate"><?= session()->get('username') ?? 'User' ?></p>
                    <p class="text-xs text-slate-500 truncate"><?= session()->get('instansi') ?? 'Dinas Perkim' ?></p>
                </div>
                <a href="<?= base_url('logout') ?>" class="p-2 text-slate-500 hover:text-rose-500 transition-colors sidebar-content transition-all duration-300" title="Keluar">
                    <i data-lucide="log-out" class="w-5 h-5 lg:w-4 lg:h-4"></i>
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-grow flex flex-col min-w-0 overflow-hidden relative">
        <header class="bg-white dark:bg-slate-900 border-b dark:border-slate-800 h-20 flex items-center justify-between px-4 lg:px-8 shrink-0 shadow-sm z-50 transition-colors duration-300">
            <div class="flex items-center flex-grow max-w-md gap-4">
                <button onclick="toggleMobileSidebar()" class="lg:hidden p-3 text-slate-600 dark:text-slate-400 bg-slate-50 dark:bg-slate-800 rounded-2xl active:scale-95 transition-all">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <?php if (!url_is('/') && !url_is('dashboard*') && !url_is('settings*')): ?>
                <form action="" method="get" class="relative w-full hidden sm:block">
                    <i data-lucide="search" class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="keyword" value="<?= request()->getGet('keyword') ?>" placeholder="Cari data..." class="w-full pl-12 pr-4 py-3 bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl text-sm focus:bg-white dark:focus:bg-slate-700 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 transition-all outline-none">
                </form>
                <?php endif; ?>
            </div>            
            
            <div class="flex items-center space-x-2 lg:space-x-6">
                <button onclick="toggleTheme()" class="p-3 text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-800 lg:bg-transparent rounded-2xl lg:rounded-xl transition-all group relative overflow-hidden active:scale-95">
                    <div id="sun-icon" class="transition-transform duration-500"><i data-lucide="sun" class="w-5 h-5 group-hover:rotate-90"></i></div>
                    <div id="moon-icon" class="absolute inset-0 flex items-center justify-center transition-transform duration-500 translate-y-full"><i data-lucide="moon" class="w-5 h-5 group-hover:-rotate-12"></i></div>
                </button>
                <div class="hidden md:flex items-center space-x-3 cursor-pointer group">
                    <div class="text-right">
                        <p class="text-sm font-black text-slate-800 dark:text-slate-200 group-hover:text-blue-600 transition-colors capitalize"><?= session()->get('username') ?></p>
                        <p class="text-[9px] text-slate-500 dark:text-slate-500 font-bold uppercase tracking-[0.2em]"><?= session()->get('role_name') ?></p>
                    </div>
                    <div class="w-10 h-10 rounded-2xl bg-blue-900 dark:bg-blue-600 flex items-center justify-center text-white font-black shadow-lg shadow-blue-900/20"><?= substr(session()->get('username') ?? 'A', 0, 1) ?></div>
                </div>
                <div class="md:hidden w-11 h-11 rounded-2xl bg-blue-900 dark:bg-blue-600 flex items-center justify-center text-white font-black shadow-lg shadow-blue-900/20 active:scale-95 transition-all"><?= substr(session()->get('username') ?? 'A', 0, 1) ?></div>
            </div>
        </header>

        <main id="main-content" class="p-4 lg:p-8 overflow-y-auto scroll-smooth dark:text-slate-300 flex-grow">
            <?= $this->renderSection('content') ?>
        </main>
    </div>

    <div id="toast-container" class="fixed top-24 lg:top-8 right-4 lg:right-8 z-[10000] space-y-3 pointer-events-none w-[calc(100%-2rem)] lg:w-96"></div>

    <script>
        lucide.createIcons();

        function toggleSidebar() {
            const sidebar = document.getElementById('main-sidebar');
            const isCollapsed = sidebar.classList.contains('lg:w-20');
            
            if (isCollapsed) {
                sidebar.classList.remove('lg:w-20');
                sidebar.classList.add('lg:w-64');
                document.querySelectorAll('.sidebar-content').forEach(el => el.classList.remove('hidden'));
                localStorage.setItem('sidebarState', 'expanded');
                updateToggleIcon(false);
            } else {
                sidebar.classList.remove('lg:w-64');
                sidebar.classList.add('lg:w-20');
                document.querySelectorAll('.sidebar-content').forEach(el => el.classList.add('hidden'));
                localStorage.setItem('sidebarState', 'collapsed');
                updateToggleIcon(true);
            }
            setTimeout(() => window.dispatchEvent(new Event('resize')), 300);
        }

        function updateToggleIcon(isCollapsed) {
            const icon = document.getElementById('sidebar-toggle-icon');
            if (icon) {
                icon.setAttribute('data-lucide', isCollapsed ? 'chevrons-right' : 'chevron-left');
                lucide.createIcons();
            }
        }

        function toggleMobileSidebar() {
            const sidebar = document.getElementById('main-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const isHidden = sidebar.classList.contains('-translate-x-full');

            if (isHidden) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                setTimeout(() => overlay.classList.add('opacity-100'), 10);
                document.body.style.overflow = 'hidden';
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.remove('opacity-100');
                setTimeout(() => overlay.classList.add('hidden'), 300);
                document.body.style.overflow = '';
            }
        }

        function toggleTheme() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateThemeIcons(isDark);
        }

        function updateThemeIcons(isDark) {
            const sun = document.getElementById('sun-icon');
            const moon = document.getElementById('moon-icon');
            if (sun && moon) {
                if (isDark) { sun.style.transform = 'translateY(-150%)'; moon.style.transform = 'translateY(0)'; }
                else { sun.style.transform = 'translateY(0)'; moon.style.transform = 'translateY(100%)'; }
            }
        }

        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-white dark:bg-slate-900 border-emerald-500' : 'bg-white dark:bg-slate-900 border-rose-500';
            const icon = type === 'success' ? 'check-circle' : 'alert-circle';
            const iconColor = type === 'success' ? 'text-emerald-500' : 'text-rose-500';

            toast.className = `pointer-events-auto flex items-center gap-4 p-5 rounded-2xl border-l-4 shadow-2xl transition-all duration-500 translate-x-full opacity-0 ${bgColor}`;
            toast.innerHTML = `<div class="${iconColor}"><i data-lucide="${icon}" class="w-6 h-6"></i></div><div class="flex-grow"><p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">${type === 'success' ? 'Berhasil' : 'Peringatan'}</p><p class="text-sm font-bold text-slate-700 dark:text-slate-200">${message}</p></div>`;
            container.appendChild(toast);
            lucide.createIcons();
            setTimeout(() => toast.classList.remove('translate-x-full', 'opacity-0'), 10);
            setTimeout(() => { toast.classList.add('translate-x-full', 'opacity-0'); setTimeout(() => toast.remove(), 500); }, 4000);
        }

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

        document.addEventListener('DOMContentLoaded', () => {
            const isDark = document.documentElement.classList.contains('dark');
            updateThemeIcons(isDark);
            
            const sidebar = document.getElementById('main-sidebar');
            const sidebarState = localStorage.getItem('sidebarState');
            if (sidebarState === 'collapsed' && window.innerWidth >= 1024) {
                sidebar.classList.remove('lg:w-64');
                sidebar.classList.add('lg:w-20');
                document.querySelectorAll('.sidebar-content').forEach(el => el.classList.add('hidden'));
                updateToggleIcon(true);
            }

            const path = window.location.pathname;
            const dropdowns = [
                { id: 'dropdown-perumahan', arrow: 'arrow-perumahan', paths: ['rtlh', 'psu', 'perumahan-formal', 'bansos-rtlh'] },
                { id: 'dropdown-permukiman', arrow: 'arrow-permukiman', paths: ['wilayah-kumuh', 'pisew', 'arsinum'] },
                { id: 'dropdown-pertanahan', arrow: 'arrow-pertanahan', paths: ['aset-tanah'] }
            ];
            dropdowns.forEach(item => {
                const isPathActive = item.paths.some(p => path.includes(p));
                if (localStorage.getItem(item.id) === 'open' || isPathActive) {
                    const dropdown = document.getElementById(item.id);
                    const arrow = document.getElementById(item.arrow);
                    if (dropdown) dropdown.classList.remove('hidden');
                    if (arrow) arrow.style.transform = 'rotate(180deg)';
                }
            });

            <?php if (session()->getFlashdata('success')): ?> showToast("<?= session()->getFlashdata('success') ?>", 'success'); <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?> showToast("<?= session()->getFlashdata('error') ?>", 'error'); <?php endif; ?>
        });

        // Close sidebar on mobile when clicking a link
        document.querySelectorAll('#main-sidebar a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 1024) toggleMobileSidebar();
            });
        });

        let touchStartX = 0;
        document.getElementById('main-sidebar').addEventListener('touchstart', e => { touchStartX = e.changedTouches[0].screenX; }, false);
        document.getElementById('main-sidebar').addEventListener('touchend', e => {
            if (touchStartX - e.changedTouches[0].screenX > 50) toggleMobileSidebar();
        }, false);
    </script>
</body>
</html>
