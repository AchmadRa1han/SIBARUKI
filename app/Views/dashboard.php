<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="space-y-8">
    <!-- Header Section -->
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Ringkasan Sistem</h1>
        <p class="text-gray-500 mt-1">Selamat datang kembali, berikut adalah performa data Anda hari ini.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                    <i data-lucide="layers" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Tabel</p>
                    <p class="text-2xl font-bold text-gray-900">12</p>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-green-600 font-bold bg-green-50 w-fit px-2 py-1 rounded-full">
                <i data-lucide="trending-up" class="w-3 h-3 mr-1"></i>
                <span>+2 Baru</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                    <i data-lucide="database" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Data</p>
                    <p class="text-2xl font-bold text-gray-900">2,543</p>
                </div>
            </div>
            <div class="mt-4 text-xs text-gray-400">Total seluruh entri database</div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                    <i data-lucide="users" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Pengguna Aktif</p>
                    <p class="text-2xl font-bold text-gray-900">48</p>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-green-600 font-bold bg-green-50 w-fit px-2 py-1 rounded-full">
                <span>8 Sesi Aktif</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-rose-50 text-rose-600 rounded-xl">
                    <i data-lucide="shield-check" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Status Sistem</p>
                    <p class="text-2xl font-bold text-gray-900">Stabil</p>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-emerald-600 font-bold">
                <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2 animate-pulse"></span>
                Semua sistem berjalan normal
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Aktivitas Terakhir -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-gray-800">Aktivitas Terakhir</h3>
                <button class="text-sm text-blue-600 font-bold hover:underline">Lihat Semua</button>
            </div>
            <div class="divide-y divide-gray-50">
                <div class="p-6 flex items-center space-x-4 hover:bg-gray-50 transition-colors">
                    <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-full flex items-center justify-center shrink-0">
                        <i data-lucide="edit-3" class="w-5 h-5"></i>
                    </div>
                    <div class="flex-grow">
                        <p class="text-sm font-bold text-gray-800">Pembaruan Data ref_master</p>
                        <p class="text-xs text-gray-500">Admin mengubah kategori 'Hobi'</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-medium text-gray-400">2 menit lalu</p>
                    </div>
                </div>
                <div class="p-6 flex items-center space-x-4 hover:bg-gray-50 transition-colors">
                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center shrink-0">
                        <i data-lucide="plus-circle" class="w-5 h-5"></i>
                    </div>
                    <div class="flex-grow">
                        <p class="text-sm font-bold text-gray-800">Data Baru Ditambahkan</p>
                        <p class="text-xs text-gray-500">Menambahkan entri baru pada tabel pengguna</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-medium text-gray-400">1 jam lalu</p>
                    </div>
                </div>
                <div class="p-6 flex items-center space-x-4 hover:bg-gray-50 transition-colors">
                    <div class="w-10 h-10 bg-rose-50 text-rose-600 rounded-full flex items-center justify-center shrink-0">
                        <i data-lucide="trash-2" class="w-5 h-5"></i>
                    </div>
                    <div class="flex-grow">
                        <p class="text-sm font-bold text-gray-800">Penghapusan Log</p>
                        <p class="text-xs text-gray-500">Sistem membersihkan log aktivitas lama</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-medium text-gray-400">5 jam lalu</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Info / Panduan -->
        <div class="bg-indigo-600 rounded-2xl p-8 text-white shadow-xl shadow-indigo-200 relative overflow-hidden">
            <i data-lucide="sparkles" class="w-32 h-32 absolute -right-8 -bottom-8 opacity-20 rotate-12"></i>
            <h3 class="text-xl font-bold mb-4 relative z-10">Pusat Bantuan</h3>
            <p class="text-indigo-100 text-sm leading-relaxed mb-6 relative z-10">
                Butuh bantuan dalam mengelola database SIBARUKI? Tim pengembang siap membantu Anda kapan saja.
            </p>
            <button class="bg-white text-indigo-600 px-6 py-2.5 rounded-xl text-sm font-bold hover:bg-indigo-50 transition-colors relative z-10 shadow-lg">
                Hubungi Support
            </button>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
<?= $this->endSection() ?>
