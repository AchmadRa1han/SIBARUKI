<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'SIBARUKI' ?></title>
    <link rel="shortcut icon" type="image/png" href="<?= base_url('sinjai.png') ?>">
    <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        // Immediate Theme Check to prevent flash and maintain state
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-gray-50 dark:bg-slate-950 font-sans flex h-screen overflow-hidden transition-colors duration-300">
    <!-- Overlay for Mobile Sidebar -->
    <div id="sidebar-overlay" onclick="toggleMobileSidebar()" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-[60] hidden lg:hidden transition-opacity duration-300"></div>

    <!-- Elegant Page Loader -->
    <div id="page-loader" class="fixed inset-0 z-[10000] bg-white/60 dark:bg-slate-950/60 backdrop-blur-md flex items-center justify-center transition-opacity duration-500 pointer-events-none opacity-0">
        <div class="flex flex-col items-center">
            <div class="w-12 h-12 border-2 border-blue-900/10 border-t-blue-900 dark:border-blue-400/10 dark:border-t-blue-400 rounded-full animate-spin"></div>
            <p class="mt-4 text-[10px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-[0.4em] animate-pulse">Memuat Data</p>
        </div>
    </div>

    <!-- Sidebar Modern (Responsive) -->
    <aside id="main-sidebar" class="fixed inset-y-0 left-0 w-64 bg-slate-900 dark:bg-slate-950 text-slate-300 flex flex-col shrink-0 shadow-xl border-r border-slate-800 z-[70] transition-transform duration-300 -translate-x-full lg:translate-x-0 lg:static">
        <?php
            // Simple Helper for View
            $has_permission = function($name) {
                $perms = session()->get('permissions') ?? [];
                return in_array($name, $perms);
            };
        ?>
        <div class="p-6 flex items-center justify-between border-b border-slate-800">
            <div class="flex items-center space-x-3">
                <img src="<?= base_url('sinjai.png') ?>" alt="Logo Sinjai" class="w-10 h-10 object-contain">
                <span class="text-xl font-bold text-white tracking-tight">SIBARUKI</span>
            </div>
            <!-- Close Button Mobile -->
            <button onclick="toggleMobileSidebar()" class="lg:hidden p-2 hover:bg-slate-800 rounded-lg">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        
        <nav id="sidebar-nav" class="flex-grow p-4 space-y-1 overflow-y-auto">
            <!-- Dashboard -->
            <a href="<?= base_url('dashboard') ?>" class="group flex items-center space-x-3 p-3 rounded-xl transition-all duration-300 <?= (url_is('/') || url_is('dashboard*')) ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800/50 hover:text-white dark:hover:bg-slate-800/30 hover:translate-x-1' ?>">
                <i data-lucide="layout-dashboard" class="w-5 h-5 transition-transform duration-300 group-hover:scale-110"></i>
                <span class="font-medium">Dashboard</span>
            </a>

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
                    <a href="<?= base_url('rtlh') ?>" class="block p-2 text-sm rounded-md transition-all duration-300 hover:bg-slate-800/50 hover:text-white dark:hover:bg-slate-800/30 hover:translate-x-1 <?= (url_is('rtlh*')) ? 'bg-blue-600 text-white px-3' : '' ?>">
                        RTLH
                    </a>
                    <a href="<?= base_url('psu') ?>" class="block p-2 text-sm rounded-md transition-all duration-300 hover:bg-slate-800/50 hover:text-white dark:hover:bg-slate-800/30 hover:translate-x-1 <?= (url_is('psu*')) ? 'bg-blue-600 text-white px-3' : '' ?>">
                        PSU Terbangun
                    </a>
                    <a href="<?= base_url('perumahan-formal') ?>" class="block p-2 text-sm rounded-md transition-all duration-300 hover:bg-slate-800/50 hover:text-white dark:hover:bg-slate-800/30 hover:translate-x-1 <?= (url_is('perumahan-formal*')) ? 'bg-blue-600 text-white px-3' : '' ?>">
                        Perumahan Formal
                    </a>
                    <a href="<?= base_url('bansos-rtlh') ?>" class="block p-2 text-sm rounded-md transition-all duration-300 hover:bg-slate-800/50 hover:text-white dark:hover:bg-slate-800/30 hover:translate-x-1 <?= (url_is('bansos-rtlh*')) ? 'bg-blue-600 text-white px-3' : '' ?>">
                        Bansos Perbaikan RTLH
                    </a>
                </div>
            </div>

            <!-- Dropdown 2: Data Kawasan Permukiman -->
            <div class="pt-2">
                <button onclick="toggleDropdown('dropdown-permukiman', 'arrow-permukiman')" class="w-full flex justify-between items-center p-3 rounded-xl transition-all duration-300 hover:bg-slate-800/50 hover:text-white dark:hover:bg-slate-800/30 hover:translate-x-1 group">
                    <div class="flex items-center space-x-3">
                        <i data-lucide="map" class="w-5 h-5 transition-transform duration-300 group-hover:scale-110"></i>
                        <span class="font-medium">Kawasan Permukiman</span>
                    </div>
                    <i id="arrow-permukiman" data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-300"></i>
                </button>
                <div id="dropdown-permukiman" class="hidden pl-11 mt-1 space-y-1 text-slate-400">
                    <a href="<?= base_url('wilayah-kumuh') ?>" class="block p-2 text-sm rounded-md transition-all duration-300 hover:bg-slate-800/50 hover:text-white dark:hover:bg-slate-800/30 hover:translate-x-1 <?= (url_is('wilayah-kumuh')) ? 'bg-blue-600 text-white px-3' : '' ?>">
                        Kawasan Kumuh
                    </a>
                    <a href="<?= base_url('wilayah-kumuh/peta') ?>" class="block p-2 text-sm rounded-md transition-all duration-300 hover:bg-slate-800/50 hover:text-white dark:hover:bg-slate-800/30 hover:translate-x-1 <?= (url_is('wilayah-kumuh/peta')) ? 'bg-blue-600 text-white px-3' : '' ?>">
                        Peta Wilayah Kumuh
                    </a>
                    <a href="<?= base_url('pisew') ?>" class="block p-2 text-sm rounded-md transition-all duration-300 hover:bg-slate-800/50 hover:text-white dark:hover:bg-slate-800/30 hover:translate-x-1 <?= (url_is('pisew*')) ? 'bg-blue-600 text-white px-3' : '' ?>">
                        PISEW
                    </a>
                    <a href="<?= base_url('arsinum') ?>" class="block p-2 text-sm rounded-md transition-all duration-300 hover:bg-slate-800/50 hover:text-white dark:hover:bg-slate-800/30 hover:translate-x-1 <?= (url_is('arsinum*')) ? 'bg-blue-600 text-white px-3' : '' ?>">
                        Arsinum
                    </a>
                </div>
            </div>

            <!-- Dropdown 3: Data Pertanahan -->
            <div class="pt-2">
                <button onclick="toggleDropdown('dropdown-pertanahan', 'arrow-pertanahan')" class="w-full flex justify-between items-center p-3 rounded-xl transition-all duration-300 hover:bg-slate-800/50 hover:text-white dark:hover:bg-slate-800/30 hover:translate-x-1 group">
                    <div class="flex items-center space-x-3">
                        <i data-lucide="layers" class="w-5 h-5 transition-transform duration-300 group-hover:scale-110"></i>
                        <span class="font-medium">Data Pertanahan</span>
                    </div>
                    <i id="arrow-pertanahan" data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-300"></i>
                </button>
                <div id="dropdown-pertanahan" class="hidden pl-11 mt-1 space-y-1 text-slate-400">
                    <a href="<?= base_url('aset-tanah') ?>" class="block p-2 text-sm rounded-md transition-all duration-300 hover:bg-slate-800/50 hover:text-white dark:hover:bg-slate-800/30 hover:translate-x-1 <?= (url_is('aset-tanah*')) ? 'bg-blue-600 text-white px-3' : '' ?>">
                        Aset Tanah Pemda
                    </a>
                </div>
            </div>

            <div class="pt-4 pb-2 px-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em]">Sistem</div>
            
            <a href="<?= base_url('settings') ?>" class="group flex items-center space-x-3 p-3 rounded-xl transition-all duration-300 <?= (url_is('settings*')) ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800/50 hover:text-white dark:hover:bg-slate-800/30 hover:translate-x-1' ?>">
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
    <div class="flex-grow flex flex-col min-w-0 overflow-hidden relative">
        <!-- Header -->
        <header class="bg-white dark:bg-slate-900 border-b dark:border-slate-800 h-20 flex items-center justify-between px-4 lg:px-8 shrink-0 shadow-sm z-50 transition-colors duration-300">
            <div class="flex items-center flex-grow max-w-md gap-4">
                <!-- Mobile Sidebar Toggle -->
                <button onclick="toggleMobileSidebar()" class="lg:hidden p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all active:scale-95">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                
                <?php if (!url_is('/') && !url_is('dashboard*') && !url_is('settings*')): ?>
                <form action="" method="get" class="relative w-full hidden sm:block">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="keyword" value="<?= request()->getGet('keyword') ?>" placeholder="Cari data..." class="w-full pl-10 pr-4 py-2 bg-gray-100 dark:bg-slate-800 border-transparent rounded-xl text-sm focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:text-slate-200 transition-all outline-none">
                </form>
                <?php endif; ?>
            </div>            
            <div class="flex items-center space-x-2 lg:space-x-6">
                <!-- Search Button Mobile Only -->
                <?php if (!url_is('/') && !url_is('dashboard*') && !url_is('settings*')): ?>
                <button class="sm:hidden p-2.5 text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all">
                    <i data-lucide="search" class="w-5 h-5"></i>
                </button>
                <?php endif; ?>

                <!-- Theme Toggle -->
                <button onclick="toggleTheme()" class="p-2.5 text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all group relative overflow-hidden">
                    <div id="sun-icon" class="transition-transform duration-500">
                        <i data-lucide="sun" class="w-5 h-5 group-hover:rotate-90"></i>
                    </div>
                    <div id="moon-icon" class="absolute inset-0 flex items-center justify-center transition-transform duration-500 translate-y-full">
                        <i data-lucide="moon" class="w-5 h-5 group-hover:-rotate-12"></i>
                    </div>
                </button>

                <div class="relative group hidden sm:block">
                    <button class="p-2 text-gray-500 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-full transition-colors relative">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        <span class="absolute top-2 right-2.5 w-2 h-2 bg-rose-500 border-2 border-white dark:border-slate-900 rounded-full"></span>
                    </button>
                </div>
                <div class="h-8 w-px bg-gray-200 dark:bg-slate-800 hidden sm:block"></div>
                <div class="flex items-center space-x-3 cursor-pointer group">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-gray-800 dark:text-slate-200 group-hover:text-blue-600 transition-colors capitalize"><?= session()->get('username') ?></p>
                        <p class="text-[10px] text-gray-500 dark:text-slate-500 font-medium uppercase tracking-wider"><?= session()->get('role_name') ?></p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-blue-900 dark:bg-blue-600 flex items-center justify-center text-white font-bold sm:hidden">
                        <?= substr(session()->get('username') ?? 'A', 0, 1) ?>
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
    <div class="fixed bottom-8 right-8 z-[50]">
        <button class="w-14 h-14 bg-blue-950 text-white rounded-2xl shadow-2xl flex items-center justify-center hover:bg-blue-900 transition-all duration-300 active:scale-95 group relative">
            <!-- Tooltip / Label Pop-up Bouncy (Tetap Tegak) -->
            <div class="absolute bottom-full mb-4 left-1/2 -translate-x-1/2 bg-blue-950 text-white text-[10px] font-black uppercase tracking-widest px-4 py-3 rounded-xl shadow-2xl opacity-0 scale-50 translate-y-4 group-hover:opacity-100 group-hover:scale-100 group-hover:translate-y-0 pointer-events-none whitespace-nowrap origin-bottom transition-all duration-500 ease-[custom-bezier] z-10" style="transition-timing-function: cubic-bezier(0.34, 1.56, 0.64, 1);">
                Hubungi Bantuan
                <!-- Small Triangle / Arrow -->
                <div class="absolute top-full left-1/2 -translate-x-1/2 w-0 h-0 border-l-[6px] border-l-transparent border-r-[6px] border-r-transparent border-t-[6px] border-t-blue-950"></div>
            </div>
            <!-- Hanya Ikon yang Miring -->
            <i data-lucide="message-square" class="w-6 h-6 group-hover:rotate-12 transition-transform duration-300"></i>
        </button>
    </div>

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed top-8 right-8 z-[10000] space-y-4 pointer-events-none"></div>

    <!-- Custom Confirmation Modal -->
    <div id="confirm-modal" class="fixed inset-0 z-[10001] flex items-center justify-center p-4 hidden">
        <div id="confirm-backdrop" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm opacity-0 transition-opacity duration-300"></div>
        <div id="confirm-card" class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-8 max-w-sm w-full relative z-10 shadow-2xl border border-slate-100 dark:border-slate-800 scale-90 opacity-0 transition-all duration-300">
            <div class="flex flex-col items-center text-center">
                <div id="confirm-icon-box" class="w-20 h-20 rounded-3xl mb-6 flex items-center justify-center shadow-inner transition-colors">
                    <i id="confirm-icon" data-lucide="alert-triangle" class="w-10 h-10"></i>
                </div>
                <h3 id="confirm-title" class="text-xl font-black text-blue-950 dark:text-white uppercase tracking-tight mb-2">Konfirmasi</h3>
                <p id="confirm-message" class="text-sm text-slate-500 dark:text-slate-400 font-medium leading-relaxed mb-8">Apakah Anda yakin ingin melanjutkan aksi ini?</p>
                
                <div class="flex gap-3 w-full">
                    <button id="confirm-cancel" class="flex-1 px-6 py-4 bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-100 dark:hover:bg-slate-700 transition-all">Batal</button>
                    <button id="confirm-ok" class="flex-1 px-6 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest text-white transition-all shadow-lg active:scale-95">Ya, Lanjutkan</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();

        // --- CUSTOM CONFIRM SYSTEM ---
        function customConfirm(title, message, type = 'danger') {
            return new Promise((resolve) => {
                const modal = document.getElementById('confirm-modal');
                const card = document.getElementById('confirm-card');
                const backdrop = document.getElementById('confirm-backdrop');
                const iconBox = document.getElementById('confirm-icon-box');
                const icon = document.getElementById('confirm-icon');
                const btnOk = document.getElementById('confirm-ok');
                const btnCancel = document.getElementById('confirm-cancel');

                document.getElementById('confirm-title').innerText = title;
                document.getElementById('confirm-message').innerText = message;

                // Style based on type
                if (type === 'danger') {
                    iconBox.className = 'w-20 h-20 rounded-3xl mb-6 flex items-center justify-center shadow-inner bg-rose-50 dark:bg-rose-950/30 text-rose-600';
                    btnOk.className = 'flex-1 px-6 py-4 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg shadow-rose-600/20 active:scale-95';
                    icon.setAttribute('data-lucide', 'trash-2');
                } else {
                    iconBox.className = 'w-20 h-20 rounded-3xl mb-6 flex items-center justify-center shadow-inner bg-blue-50 dark:bg-blue-950/30 text-blue-600';
                    btnOk.className = 'flex-1 px-6 py-4 bg-blue-900 hover:bg-blue-950 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg shadow-blue-900/20 active:scale-95';
                    icon.setAttribute('data-lucide', 'help-circle');
                }
                lucide.createIcons();

                // Show Modal
                modal.classList.remove('hidden');
                setTimeout(() => {
                    backdrop.classList.add('opacity-100');
                    card.classList.remove('scale-90', 'opacity-0');
                    card.classList.add('scale-100', 'opacity-100');
                }, 10);

                const close = (result) => {
                    backdrop.classList.remove('opacity-100');
                    card.classList.add('scale-90', 'opacity-0');
                    setTimeout(() => {
                        modal.classList.add('hidden');
                        resolve(result);
                    }, 300);
                };

                btnOk.onclick = () => close(true);
                btnCancel.onclick = () => close(false);
            });
        }

        // --- TOAST SYSTEM ---
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            const bgColor = type === 'success' ? 'bg-white dark:bg-slate-900 border-emerald-500' : 'bg-white dark:bg-slate-900 border-rose-500';
            const icon = type === 'success' ? 'check-circle' : 'alert-circle';
            const iconColor = type === 'success' ? 'text-emerald-500' : 'text-rose-500';

            toast.className = `pointer-events-auto flex items-center gap-4 p-5 rounded-2xl border-l-4 shadow-2xl transition-all duration-500 translate-x-full opacity-0 ${bgColor}`;
            toast.innerHTML = `
                <div class="${iconColor}"><i data-lucide="${icon}" class="w-6 h-6"></i></div>
                <div class="flex-grow">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">${type === 'success' ? 'Berhasil' : 'Peringatan'}</p>
                    <p class="text-sm font-bold text-slate-700 dark:text-slate-200">${message}</p>
                </div>
                <button class="text-slate-300 hover:text-slate-500 transition-colors"><i data-lucide="x" class="w-4 h-4"></i></button>
            `;

            container.appendChild(toast);
            lucide.createIcons();

            // Animate In
            setTimeout(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
            }, 10);

            // Auto Remove
            const remove = () => {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => toast.remove(), 500);
            };

            const autoTimeout = setTimeout(remove, 5000);
            toast.querySelector('button').onclick = () => {
                clearTimeout(autoTimeout);
                remove();
            };
        }

        // Check for Flashdata on Load
        document.addEventListener('DOMContentLoaded', () => {
            <?php if (session()->getFlashdata('success') || session()->getFlashdata('message')): ?>
                showToast("<?= session()->getFlashdata('success') ?: session()->getFlashdata('message') ?>", 'success');
            <?php endif; ?>
            <?php if (session()->getFlashdata('error') || session()->getFlashdata('errors')): ?>
                showToast("<?= session()->getFlashdata('error') ?: 'Terjadi kesalahan input.' ?>", 'error');
            <?php endif; ?>
        });

        // Responsive Sidebar Toggle
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('main-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const isHidden = sidebar.classList.contains('-translate-x-full');

            if (isHidden) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden'; // Prevent scroll
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }

        // Close sidebar when clicking links on mobile
        document.querySelectorAll('#main-sidebar a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 1024) {
                    toggleMobileSidebar();
                }
            });
        });

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
            if (sun && moon) {
                if (isDark) {
                    sun.style.transform = 'translateY(-150%)';
                    moon.style.transform = 'translateY(0)';
                } else {
                    sun.style.transform = 'translateY(0)';
                    moon.style.transform = 'translateY(100%)';
                }
            }
        }

        // Initialize icons on load
        document.addEventListener('DOMContentLoaded', () => {
            const isDark = document.documentElement.classList.contains('dark');
            updateThemeIcons(isDark);
        });

        // 1. Sembunyikan saat halaman sudah siap (termasuk saat Back/Forward)
        window.addEventListener('pageshow', (event) => {
            if (loader) {
                loader.classList.add('opacity-0');
                loader.classList.add('pointer-events-none');
            }
        });

        // 2. Tampilkan saat berpindah halaman melalui link
        document.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (
                    loader &&
                    href && 
                    !href.startsWith('#') && 
                    !href.startsWith('javascript:') && 
                    !e.metaKey && !e.ctrlKey && 
                    this.getAttribute('target') !== '_blank'
                ) {
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
            const sidebarNav = document.getElementById('sidebar-nav');
            
            const dropdowns = [
                { id: 'dropdown-akses', arrow: 'arrow-akses', paths: ['roles', 'users', 'logs', 'trash'] },
                { id: 'dropdown-perumahan', arrow: 'arrow-perumahan', paths: ['rtlh', 'psu', 'perumahan-formal', 'bansos-rtlh'] },
                { id: 'dropdown-permukiman', arrow: 'arrow-permukiman', paths: ['wilayah-kumuh', 'pisew', 'arsinum'] },
                { id: 'dropdown-pertanahan', arrow: 'arrow-pertanahan', paths: ['aset-tanah'] }
            ];

            dropdowns.forEach(item => {
                const dropdown = document.getElementById(item.id);
                const arrow = document.getElementById(item.arrow);
                const savedState = localStorage.getItem(item.id);
                
                // Cek apakah URL aktif berada di dalam menu ini
                const isPathActive = item.paths.some(p => path.includes(p));

                // Buka jika: Ada di memory 'open' ATAU URL-nya sedang aktif
                if (savedState === 'open' || isPathActive) {
                    if (dropdown) dropdown.classList.remove('hidden');
                    if (arrow) arrow.style.transform = 'rotate(180deg)';
                    if (isPathActive) localStorage.setItem(item.id, 'open');
                }
            });

            // --- SIDEBAR SCROLL MEMORY (Restored AFTER dropdowns open) ---
            setTimeout(() => {
                const savedScroll = localStorage.getItem('sidebarScrollPos');
                if (savedScroll && sidebarNav) {
                    sidebarNav.scrollTop = savedScroll;
                }
            }, 50);

            if (sidebarNav) {
                sidebarNav.addEventListener('scroll', () => {
                    localStorage.setItem('sidebarScrollPos', sidebarNav.scrollTop);
                });
            }
        });
    </script>
</body>
</html>
