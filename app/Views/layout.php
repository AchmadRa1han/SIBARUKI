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
        
        /* --- SIDEBAR BASE --- */
        .sidebar-transition { 
            transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-clip-wrapper {
            width: 100%; height: 100%; overflow: hidden; display: flex; flex-direction: column;
        }

        .sidebar-content-fixed {
            width: 256px; flex-shrink: 0; display: flex; flex-direction: column; height: 100%;
        }

        .sidebar-text { transition: opacity 0.3s ease; white-space: nowrap; }
        .sidebar-collapsed .sidebar-text { opacity: 0; pointer-events: none; }

        .sidebar-icon-box {
            width: 80px; min-width: 80px; flex-shrink: 0; display: flex; align-items: center; justify-content: center;
        }

        .dropdown-container {
            display: grid; grid-template-rows: 0fr; transition: grid-template-rows 0.3s ease-out, opacity 0.2s ease-out; opacity: 0; overflow: hidden;
        }
        .dropdown-container.open { grid-template-rows: 1fr; opacity: 1; }
        .dropdown-content { min-height: 0; }
    </style>
</head>
<body class="bg-gray-50 dark:bg-slate-950 font-sans flex h-screen overflow-hidden transition-colors duration-300">
    
    <!-- Mobile Overlay -->
    <div id="sidebar-overlay" onclick="toggleMobileSidebar()" class="fixed inset-0 bg-slate-900/60 backdrop-blur-[2px] z-[60] hidden transition-opacity duration-300 opacity-0 cursor-pointer"></div>

    <!-- Sidebar Modern -->
    <aside id="main-sidebar" class="sidebar-transition fixed inset-y-0 left-0 w-72 z-[100] -translate-x-full lg:relative lg:inset-auto lg:w-64 lg:translate-x-0 h-screen bg-slate-900 dark:bg-slate-950 text-slate-300 flex flex-col shrink-0 shadow-2xl lg:shadow-xl border-r border-slate-800 group/sidebar overflow-visible">
        
        <!-- Floating Toggle Button -->
        <button onclick="toggleSidebar()" class="hidden lg:flex absolute top-1/2 -right-4 -translate-y-1/2 w-8 h-40 bg-slate-900 dark:bg-slate-950 border-y border-r border-slate-800 rounded-r-3xl items-center justify-center z-[100] hover:bg-blue-600 hover:border-blue-600 text-slate-500 hover:text-white transition-all duration-300 shadow-xl group/btn active:scale-95">
            <i id="sidebar-toggle-icon" data-lucide="chevron-left" class="w-5 h-5 transition-transform duration-500"></i>
        </button>

        <!-- Clip Wrapper -->
        <div class="sidebar-clip-wrapper">
            <div class="sidebar-content-fixed">
                
                <!-- Header -->
                <div class="h-16 flex items-center border-b border-slate-800 shrink-0 overflow-hidden">
                    <a href="<?= base_url('/') ?>" class="sidebar-icon-box group/logo transition-all duration-300 hover:scale-105">
                        <img src="<?= base_url('sinjai.png') ?>" alt="Logo Sinjai" class="w-8 h-8 object-contain group-hover:rotate-6 transition-transform">
                    </a>
                    <a href="<?= base_url('/') ?>" class="text-lg font-bold text-white tracking-tight sidebar-text hover:text-blue-400 transition-colors uppercase">SIBARUKI</a>
                    <!-- Mobile Close -->
                    <button onclick="toggleMobileSidebar()" class="lg:hidden ml-auto mr-4 p-2.5 bg-slate-800/50 rounded-xl">
                        <i data-lucide="x" class="w-4.5 h-4.5"></i>
                    </button>
                </div>
                
                <!-- Navigation -->
                <nav id="sidebar-nav" class="flex-grow py-4 space-y-1 overflow-y-auto overflow-x-hidden scroll-smooth">
                    <a href="<?= base_url('dashboard') ?>" class="flex items-center h-11 w-full transition-all duration-300 <?= (url_is('/') || url_is('dashboard*')) ? 'bg-blue-600 text-white shadow-lg' : 'hover:bg-slate-800/50 hover:text-white' ?>">
                        <div class="sidebar-icon-box"><i data-lucide="layout-dashboard" class="w-4.5 h-4.5"></i></div>
                        <span class="text-sm font-bold sidebar-text">Dashboard</span>
                    </a>

                    <div class="pt-1">
                        <button onclick="toggleDropdown('dropdown-perumahan', 'arrow-perumahan')" class="w-full flex items-center h-11 transition-all duration-300 hover:bg-slate-800/50 hover:text-white group">
                            <div class="sidebar-icon-box"><i data-lucide="home" class="w-4.5 h-4.5"></i></div>
                            <span class="text-sm font-bold flex-grow text-left sidebar-text">Perumahan</span>
                            <i id="arrow-perumahan" data-lucide="chevron-down" class="w-3.5 h-3.5 mr-6 transition-transform duration-300 sidebar-text"></i>
                        </button>
                        <div id="dropdown-perumahan" class="dropdown-container">
                            <div class="dropdown-content pl-20 mt-1 space-y-1 text-slate-400">
                                <a href="<?= base_url('rtlh') ?>" class="block py-1.5 text-xs hover:text-white <?= (url_is('rtlh') || (url_is('rtlh/*') && !url_is('rtlh/rekap-desa'))) ? 'text-blue-400 font-bold' : '' ?>">RTLH</a>
                                <?php if (session()->get('role_id') == 1): ?>
                                <a href="<?= base_url('data-perumahan') ?>" class="block py-1.5 text-xs hover:text-white <?= (url_is('data-perumahan') || url_is('data-perumahan/sync')) ? 'text-blue-400 font-bold' : '' ?>">Rekapitulasi Desa</a>
                                <?php if (session()->get('role_id') == 1): ?>
                                <a href="<?= base_url('data-perumahan/backlog') ?>" class="block py-1.5 text-xs hover:text-white <?= (url_is('data-perumahan/backlog*')) ? 'text-blue-400 font-bold' : '' ?>">Manajemen Backlog</a>
                                <?php endif; ?>
                                <?php endif; ?>
                                <a href="<?= base_url('psu') ?>" class="block py-1.5 text-xs hover:text-white <?= (url_is('psu*')) ? 'text-blue-400 font-bold' : '' ?>">PSU Terbangun</a>
                                <a href="<?= base_url('perumahan-formal') ?>" class="block py-1.5 text-xs hover:text-white <?= (url_is('perumahan-formal*')) ? 'text-blue-400 font-bold' : '' ?>">Perumahan</a>
                                <a href="<?= base_url('bansos-rtlh') ?>" class="block py-1.5 text-xs hover:text-white <?= (url_is('bansos-rtlh*')) ? 'text-blue-400 font-bold' : '' ?>">Bansos RTLH</a>
                            </div>
                        </div>
                    </div>

                    <div class="pt-1">
                        <button onclick="toggleDropdown('dropdown-permukiman', 'arrow-permukiman')" class="w-full flex items-center h-11 transition-all duration-300 hover:bg-slate-800/50 hover:text-white group">
                            <div class="sidebar-icon-box"><i data-lucide="map" class="w-4.5 h-4.5"></i></div>
                            <span class="text-sm font-bold flex-grow text-left sidebar-text">Permukiman</span>
                            <i id="arrow-permukiman" data-lucide="chevron-down" class="w-3.5 h-3.5 mr-6 transition-transform duration-300 sidebar-text"></i>
                        </button>
                        <div id="dropdown-permukiman" class="dropdown-container">
                            <div class="dropdown-content pl-20 mt-1 space-y-1 text-slate-400">
                                <a href="<?= base_url('wilayah-kumuh') ?>" class="block py-1.5 text-xs hover:text-white <?= (url_is('wilayah-kumuh')) ? 'text-blue-400 font-bold' : '' ?>">Wilayah Kumuh</a>
                                <a href="<?= base_url('pisew') ?>" class="block py-1.5 text-xs hover:text-white <?= (url_is('pisew*')) ? 'text-blue-400 font-bold' : '' ?>">PISEW</a>
                                <a href="<?= base_url('arsinum') ?>" class="block py-1.5 text-xs hover:text-white <?= (url_is('arsinum*')) ? 'text-blue-400 font-bold' : '' ?>">Arsinum</a>
                            </div>
                        </div>
                    </div>

                    <div class="pt-1">
                        <button onclick="toggleDropdown('dropdown-pertanahan', 'arrow-pertanahan')" class="w-full flex items-center h-11 transition-all duration-300 hover:bg-slate-800/50 hover:text-white group">
                            <div class="sidebar-icon-box"><i data-lucide="layers" class="w-4.5 h-4.5"></i></div>
                            <span class="text-sm font-bold flex-grow text-left sidebar-text">Pertanahan</span>
                            <i id="arrow-pertanahan" data-lucide="chevron-down" class="w-3.5 h-3.5 mr-6 transition-transform duration-300 sidebar-text"></i>
                        </button>
                        <div id="dropdown-pertanahan" class="dropdown-container">
                            <div class="dropdown-content pl-20 mt-1 space-y-1 text-slate-400">
                                <a href="<?= base_url('aset-tanah') ?>" class="block py-1.5 text-xs hover:text-white <?= (url_is('aset-tanah*')) ? 'text-blue-400 font-bold' : '' ?>">Aset Tanah Pemda</a>
                            </div>
                        </div>
                    </div>

                    <a href="<?= base_url('settings') ?>" class="flex items-center h-11 w-full transition-all duration-300 <?= (url_is('settings*')) ? 'bg-blue-600 text-white shadow-lg' : 'hover:bg-slate-800/50 hover:text-white' ?>">
                        <div class="sidebar-icon-box"><i data-lucide="settings" class="w-4.5 h-4.5"></i></div>
                        <span class="text-sm font-bold sidebar-text">Pengaturan</span>
                    </a>
                </nav>

                <!-- Footer -->
                <div class="h-16 border-t border-slate-800 bg-slate-900/50 flex items-center shrink-0">
                    <div class="sidebar-icon-box">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white font-bold shadow-inner uppercase text-sm">
                            <?= substr(session()->get('username') ?? 'A', 0, 1) ?>
                        </div>
                    </div>
                    <div class="flex-grow min-w-0 sidebar-text">
                        <p class="text-xs font-bold text-white truncate"><?= session()->get('username') ?? 'User' ?></p>
                        <p class="text-[8px] text-slate-500 truncate uppercase font-bold"><?= session()->get('instansi') ?? 'Dinas Perkim' ?></p>
                    </div>
                    <a href="<?= base_url('logout') ?>" class="mr-6 text-slate-500 hover:text-rose-500 sidebar-text transition-colors">
                        <i data-lucide="log-out" class="w-4.5 h-4.5"></i>
                    </a>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-grow flex flex-col min-w-0 overflow-hidden relative">
        <header class="bg-white dark:bg-slate-900 border-b dark:border-slate-800 h-16 flex items-center justify-between px-4 lg:px-6 shrink-0 shadow-sm z-50 transition-colors duration-300">
            <button onclick="toggleMobileSidebar()" class="lg:hidden p-2.5 text-slate-600 dark:text-slate-400 bg-slate-50 dark:bg-slate-800 rounded-xl active:scale-95"><i data-lucide="menu" class="w-5 h-5"></i></button>
            <div class="flex items-center space-x-3 ml-auto">
                <button onclick="toggleTheme()" class="p-2.5 text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-800 rounded-xl relative overflow-hidden transition-all active:scale-90">
                    <div id="sun-icon" class="transition-transform duration-500"><i data-lucide="sun" class="w-4.5 h-4.5"></i></div>
                    <div id="moon-icon" class="absolute inset-0 flex items-center justify-center transition-transform duration-500 translate-y-full"><i data-lucide="moon" class="w-4.5 h-4.5"></i></div>
                </button>
                <div class="hidden md:flex items-center space-x-2.5 group">
                    <div class="text-right"><p class="text-xs font-bold text-slate-800 dark:text-slate-200"><?= session()->get('username') ?></p><p class="text-[8px] text-slate-500 font-bold uppercase tracking-widest"><?= session()->get('role_name') ?></p></div>
                    <div class="w-9 h-9 rounded-xl bg-blue-900 flex items-center justify-center text-white font-bold shadow-lg shadow-blue-900/20 uppercase text-xs"><?= substr(session()->get('username') ?? 'A', 0, 1) ?></div>
                </div>
            </div>
        </header>
        <main id="main-content" class="p-4 lg:p-6 overflow-y-auto dark:text-slate-300 flex-grow"><?= $this->renderSection('content') ?></main>
    </div>

    <div id="toast-container" class="fixed top-20 right-4 lg:right-6 z-[10000] space-y-2 pointer-events-none w-[calc(100%-2rem)] lg:w-80"></div>

    <script>
        lucide.createIcons();

        function toggleSidebar() {
            const sidebar = document.getElementById('main-sidebar');
            const isCollapsed = sidebar.classList.contains('lg:w-20');
            if (isCollapsed) { sidebar.classList.remove('sidebar-collapsed', 'lg:w-20'); sidebar.classList.add('lg:w-64'); localStorage.setItem('sidebarState', 'expanded'); updateToggleIcon(false); } 
            else { sidebar.classList.add('sidebar-collapsed', 'lg:w-20'); sidebar.classList.remove('lg:w-64'); localStorage.setItem('sidebarState', 'collapsed'); updateToggleIcon(true); ['dropdown-perumahan', 'dropdown-permukiman', 'dropdown-pertanahan'].forEach(id => closeDropdown(id, 'arrow-' + id.split('-')[1])); }
            setTimeout(() => window.dispatchEvent(new Event('resize')), 300);
        }

        function updateToggleIcon(isCollapsed) {
            const icon = document.getElementById('sidebar-toggle-icon');
            if (icon) { icon.setAttribute('data-lucide', isCollapsed ? 'chevrons-right' : 'chevron-left'); lucide.createIcons(); }
        }

        function toggleMobileSidebar() {
            const sidebar = document.getElementById('main-sidebar'), overlay = document.getElementById('sidebar-overlay');
            const isHidden = sidebar.classList.contains('-translate-x-full');
            if (isHidden) { sidebar.classList.remove('-translate-x-full'); overlay.classList.remove('hidden'); setTimeout(() => overlay.classList.add('opacity-100'), 10); document.body.style.overflow = 'hidden'; } 
            else { sidebar.classList.add('-translate-x-full'); overlay.classList.remove('opacity-100'); setTimeout(() => overlay.classList.add('hidden'), 300); document.body.style.overflow = ''; }
        }

        function toggleTheme() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateThemeIcons(isDark);
        }

        function updateThemeIcons(isDark) {
            const sun = document.getElementById('sun-icon'), moon = document.getElementById('moon-icon');
            if (sun && moon) { if (isDark) { sun.style.transform = 'translateY(-150%)'; moon.style.transform = 'translateY(0)'; } else { sun.style.transform = 'translateY(0)'; moon.style.transform = 'translateY(100%)'; } }
        }

        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            const color = type === 'success' ? 'emerald' : 'rose';
            toast.className = `pointer-events-auto flex items-center gap-3 p-4 rounded-xl border-l-4 border-${color}-500 bg-white dark:bg-slate-900 shadow-xl transition-all duration-500 translate-x-full opacity-0`;
            toast.innerHTML = `<div class="text-${color}-500"><i data-lucide="${type==='success'?'check-circle':'alert-circle'}" class="w-5 h-5"></i></div><div class="flex-grow"><p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">${type==='success'?'Berhasil':'Peringatan'}</p><p class="text-xs font-bold text-slate-700 dark:text-slate-200">${message}</p></div>`;
            container.appendChild(toast); lucide.createIcons();
            setTimeout(() => toast.classList.remove('translate-x-full', 'opacity-0'), 10);
            setTimeout(() => { toast.classList.add('translate-x-full', 'opacity-0'); setTimeout(() => toast.remove(), 500); }, 4000);
        }

        function toggleDropdown(id, arrowId) {
            const isCollapsed = document.getElementById('main-sidebar').classList.contains('lg:w-20');
            if (isCollapsed) { toggleSidebar(); setTimeout(() => openDropdown(id, arrowId), 400); } 
            else { const el = document.getElementById(id); if (el.classList.contains('open')) closeDropdown(id, arrowId); else openDropdown(id, arrowId); }
        }

        function openDropdown(id, arrowId) {
            const el = document.getElementById(id), arrow = document.getElementById(arrowId);
            if (el) { el.classList.add('open'); if (arrow) arrow.style.transform = 'rotate(180deg)'; localStorage.setItem(id, 'open'); }
        }

        function closeDropdown(id, arrowId) {
            const el = document.getElementById(id), arrow = document.getElementById(arrowId);
            if (el) { el.classList.remove('open'); if (arrow) arrow.style.transform = 'rotate(0deg)'; localStorage.setItem(id, 'closed'); }
        }

        function customConfirm(title, message, type = 'info') {
            return new Promise((resolve) => {
                let options = {};
                if (typeof title === 'object') { options = title; title = options.title || 'Konfirmasi'; message = options.message || ''; type = options.type || 'info'; }
                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 z-[2000] flex items-center justify-center p-6 transition-opacity duration-300 opacity-0';
                const typeColors = { danger: { bg: 'rose-500', text: 'rose-500', light: 'rose-50', dark: 'rose-950/30', icon: 'alert-triangle' }, info: { bg: 'blue-500', text: 'blue-500', light: 'blue-50', dark: 'blue-950/30', icon: 'info' }, warning: { bg: 'amber-500', text: 'amber-500', light: 'amber-50', dark: 'amber-950/30', icon: 'alert-circle' } };
                const colors = typeColors[type] || typeColors.info;
                modal.innerHTML = `<div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md"></div><div class="relative bg-white dark:bg-slate-900 w-full max-w-sm rounded-2xl border border-slate-100 dark:border-slate-800 shadow-2xl overflow-hidden scale-95 transition-transform duration-300"><div class="p-8 text-center"><div class="w-16 h-16 bg-${colors.light} dark:bg-${colors.dark} text-${colors.text} rounded-xl flex items-center justify-center mx-auto mb-6"><i data-lucide="${colors.icon}" class="w-8 h-8"></i></div><h3 class="text-lg font-bold text-blue-950 dark:text-white uppercase tracking-tight mb-3">${title}</h3><p class="text-xs text-slate-500 dark:text-slate-400 font-medium leading-relaxed">${message}</p></div><div class="p-6 bg-slate-50/50 dark:bg-slate-950/50 border-t dark:border-slate-800 flex gap-3"><button id="confirm-cancel" class="flex-1 py-3 text-[10px] font-bold text-slate-400 hover:text-slate-600 transition-colors uppercase tracking-widest">Batal</button><button id="confirm-ok" class="flex-[2] py-3 bg-${colors.bg} text-white rounded-lg text-[10px] font-bold uppercase tracking-widest shadow-lg active:scale-95 transition-all">${options.confirmText || 'Lanjutkan'}</button></div></div>`;
                document.body.appendChild(modal); lucide.createIcons({ attrs: { 'stroke-width': 2.5 } });
                setTimeout(() => { modal.classList.add('opacity-100'); modal.querySelector('.relative').classList.remove('scale-95'); }, 10);
                const cleanup = (result) => { modal.classList.remove('opacity-100'); modal.querySelector('.relative').classList.add('scale-95'); setTimeout(() => { modal.remove(); if (result && typeof options.onConfirm === 'function') options.onConfirm(); resolve(result); }, 300); };
                modal.querySelector('#confirm-ok').addEventListener('click', () => cleanup(true));
                modal.querySelector('#confirm-cancel').addEventListener('click', () => cleanup(false));
                modal.querySelector('.fixed.inset-0').addEventListener('click', () => cleanup(false));
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            const isDark = document.documentElement.classList.contains('dark');
            updateThemeIcons(isDark);
            const sidebar = document.getElementById('main-sidebar');
            if (localStorage.getItem('sidebarState') === 'collapsed' && window.innerWidth >= 1024) { sidebar.classList.add('sidebar-collapsed', 'lg:w-20'); sidebar.classList.remove('lg:w-64'); updateToggleIcon(true); }
            const path = window.location.pathname;
            const dropdowns = [ { id: 'dropdown-perumahan', arrow: 'arrow-perumahan', paths: ['rtlh', 'psu', 'perumahan-formal', 'bansos-rtlh'] }, { id: 'dropdown-permukiman', arrow: 'arrow-permukiman', paths: ['wilayah-kumuh', 'pisew', 'arsinum'] }, { id: 'dropdown-pertanahan', arrow: 'arrow-pertanahan', paths: ['aset-tanah'] } ];
            dropdowns.forEach(item => { if (item.paths.some(p => path.includes(p)) || localStorage.getItem(item.id) === 'open') openDropdown(item.id, item.arrow); });
            <?php if (session()->getFlashdata('success')): ?> showToast("<?= session()->getFlashdata('success') ?>", 'success'); <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?> showToast("<?= session()->getFlashdata('error') ?>", 'error'); <?php endif; ?>
        });
        document.querySelectorAll('#main-sidebar a').forEach(link => { link.addEventListener('click', () => { if (window.innerWidth < 1024) toggleMobileSidebar(); }); });
    </script>
</body>
</html>
