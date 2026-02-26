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
<body class="bg-gray-50 font-sans flex h-screen overflow-hidden">

    <!-- Sidebar Modern -->
    <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col shrink-0 shadow-xl">
        <div class="p-6 flex items-center space-x-3 border-b border-slate-800">
            <img src="<?= base_url('sinjai.png') ?>" alt="Logo Sinjai" class="w-10 h-10 object-contain">
            <span class="text-xl font-bold text-white tracking-tight">SIBARUKI</span>
        </div>
        
        <nav class="flex-grow p-4 space-y-1 overflow-y-auto">
            <!-- Dashboard -->
            <a href="<?= base_url('/') ?>" class="flex items-center space-x-3 p-3 rounded-lg transition-colors <?= (url_is('/')) ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' ?>">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                <span class="font-medium">Dashboard</span>
            </a>

            <!-- Dropdown 1: Data Perumahan -->
            <div class="pt-2">
                <button onclick="toggleDropdown('dropdown-perumahan', 'arrow-perumahan')" class="w-full flex justify-between items-center p-3 rounded-lg transition-colors hover:bg-slate-800 hover:text-white group">
                    <div class="flex items-center space-x-3">
                        <i data-lucide="home" class="w-5 h-5"></i>
                        <span class="font-medium">Data Perumahan</span>
                    </div>
                    <i id="arrow-perumahan" data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200"></i>
                </button>
                <div id="dropdown-perumahan" class="hidden pl-11 mt-1 space-y-1 text-slate-400">
                    <a href="<?= base_url('ref-master') ?>" class="block p-2 text-sm rounded-md transition-colors <?= (url_is('ref-master*')) ? 'text-white font-medium' : 'hover:text-white' ?>">
                        Referensi Master
                    </a>
                    <a href="<?= base_url('rtlh') ?>" class="block p-2 text-sm rounded-md transition-colors <?= (url_is('rtlh*')) ? 'text-white font-medium' : 'hover:text-white' ?>">
                        Data RTLH
                    </a>
                </div>
            </div>

            <!-- Dropdown 2: Data Kawasan Permukiman -->
            <div class="pt-2">
                <button onclick="toggleDropdown('dropdown-permukiman', 'arrow-permukiman')" class="w-full flex justify-between items-center p-3 rounded-lg transition-colors hover:bg-slate-800 hover:text-white group">
                    <div class="flex items-center space-x-3">
                        <i data-lucide="map" class="w-5 h-5"></i>
                        <span class="font-medium">Data Permukiman</span>
                    </div>
                    <i id="arrow-permukiman" data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200"></i>
                </button>
                <div id="dropdown-permukiman" class="hidden pl-11 mt-1 space-y-1 text-slate-400">
                    <a href="<?= base_url('wilayah-kumuh') ?>" class="block p-2 text-sm rounded-md transition-colors <?= (url_is('wilayah-kumuh*')) ? 'text-white font-medium' : 'hover:text-white' ?>">
                        Wilayah Kumuh
                    </a>
                </div>
            </div>

            <div class="pt-4 pb-2 px-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em]">Sistem</div>
            <a href="#" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-slate-800 hover:text-white transition-colors">
                <i data-lucide="settings" class="w-5 h-5"></i>
                <span class="font-medium">Pengaturan</span>
            </a>
        </nav>

        <div class="p-4 border-t border-slate-800 bg-slate-900/50">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white font-bold shadow-inner">
                    A
                </div>
                <div class="flex-grow min-w-0">
                    <p class="text-sm font-semibold text-white truncate">Administrator</p>
                    <p class="text-xs text-slate-500 truncate">admin@sibaruki.id</p>
                </div>
                <button class="text-slate-500 hover:text-rose-500 transition-colors">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
    </aside>

    <!-- Konten Utama -->
    <div class="flex-grow flex flex-col min-w-0 overflow-hidden">
        <!-- Header -->
        <header class="bg-white border-b h-20 flex items-center justify-between px-8 shrink-0 shadow-sm z-10">
            <div class="flex items-center flex-grow max-w-md">
                <div class="relative w-full">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" placeholder="Cari data..." class="w-full pl-10 pr-4 py-2 bg-gray-100 border-transparent rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none">
                </div>
            </div>
            
            <div class="flex items-center space-x-6">
                <div class="relative group">
                    <button class="p-2 text-gray-500 hover:bg-gray-100 rounded-full transition-colors relative">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        <span class="absolute top-2 right-2.5 w-2 h-2 bg-rose-500 border-2 border-white rounded-full"></span>
                    </button>
                </div>
                <div class="h-8 w-px bg-gray-200"></div>
                <div class="flex items-center space-x-3 cursor-pointer group">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-gray-800 group-hover:text-blue-600 transition-colors">Budi Santoso</p>
                        <p class="text-[10px] text-gray-500 font-medium">Super Admin</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Area Scroll Konten -->
        <main class="p-8 overflow-y-auto scroll-smooth">
            <?= $this->renderSection('content') ?>
        </main>
    </div>

    <script>
        lucide.createIcons();

        function toggleDropdown(id, arrowId) {
            const dropdown = document.getElementById(id);
            const arrow = document.getElementById(arrowId);
            
            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
                if (arrow) arrow.style.transform = 'rotate(180deg)';
            } else {
                dropdown.classList.add('hidden');
                if (arrow) arrow.style.transform = 'rotate(0deg)';
            }
        }

        // Auto-open logic
        document.addEventListener('DOMContentLoaded', () => {
            const path = window.location.pathname;
            
            // Perumahan
            if (path.includes('ref-master') || path.includes('rtlh')) {
                toggleDropdown('dropdown-perumahan', 'arrow-perumahan');
            }
            
            // Permukiman
            if (path.includes('wilayah-kumuh')) {
                toggleDropdown('dropdown-permukiman', 'arrow-permukiman');
            }
        });
    </script>
</body>
</html>
