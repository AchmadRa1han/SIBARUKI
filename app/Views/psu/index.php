<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto space-y-6 pb-24 text-slate-900 dark:text-slate-200">
    
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-3 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 no-print">
        <a href="<?= base_url('dashboard') ?>" class="hover:text-blue-600 transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <a href="<?= base_url('psu') ?>" class="hover:text-blue-600 transition-colors">PSU Jalan</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">Manajemen Data</span>
    </nav>

    <!-- Header Action -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white dark:bg-slate-900 p-8 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm transition-all duration-300 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-48 h-48 bg-blue-600/5 rounded-full -mr-24 -mt-24 blur-3xl"></div>
        <div class="relative z-10 flex items-center gap-6">
            <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-blue-600/20">
                <i data-lucide="route" class="w-8 h-8"></i>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-blue-950 dark:text-white uppercase tracking-tighter leading-tight">PSU Jaringan Jalan</h1>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-[0.2em] mt-1">Total Pembangunan: <span class="text-blue-600"><?= number_format($total_panjang, 2, ',', '.') ?> m</span> dari <?= $total_jalan ?> Titik</p>
            </div>
        </div>
        <div class="flex items-center gap-3 relative z-10">
            <?php if (has_permission('create_psu')) : ?>
            <a href="<?= base_url('psu/create') ?>" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-blue-600/20 transition-all active:scale-95 flex items-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i> Tambah Aset
            </a>
            <button onclick="document.getElementById('csv_file').click()" class="px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-emerald-500/20 transition-all active:scale-95 flex items-center gap-2">
                <i data-lucide="file-up" class="w-4 h-4"></i> Import CSV
            </button>
            <form id="import-form" action="<?= base_url('psu/import-csv') ?>" method="POST" enctype="multipart/form-data" class="hidden">
                <?= csrf_field() ?>
                <input type="file" id="csv_file" name="csv_file" accept=".csv" onchange="document.getElementById('import-form').submit()">
            </form>
            <?php endif; ?>
            <a href="<?= base_url('psu/export-excel') ?>" class="p-3 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl hover:bg-indigo-600 hover:text-white transition-all active:scale-95 shadow-sm" title="Export Excel">
                <i data-lucide="download" class="w-5 h-5"></i>
            </a>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-xl shadow-slate-200/50 dark:shadow-black/20 border border-slate-100 dark:border-slate-800 overflow-hidden transition-all duration-300">
        <div class="p-8 border-b dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <h3 class="text-[10px] font-black text-blue-950 dark:text-white uppercase tracking-[0.3em]">Daftar Aset Terverifikasi</h3>
                <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 text-[10px] font-bold rounded-full"><?= $total_jalan ?> Data</span>
            </div>
            <form action="" method="GET" class="relative group w-full md:w-80">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                <input type="text" name="keyword" value="<?= $keyword ?>" placeholder="Cari Nama Jalan atau Tahun..." class="w-full pl-11 pr-4 py-3 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none dark:text-white">
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-900/50 border-b dark:border-slate-800">
                        <th class="p-6 text-[9px] font-black text-slate-400 uppercase tracking-widest">Aset Jaringan Jalan</th>
                        <th class="p-6 text-[9px] font-black text-slate-400 uppercase tracking-widest text-center">Tahun</th>
                        <th class="p-6 text-[9px] font-black text-slate-400 uppercase tracking-widest">Panjang / Luas</th>
                        <th class="p-6 text-[9px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-slate-800">
                    <?php if(!empty($jalan)): foreach($jalan as $item): ?>
                    <tr class="hover:bg-blue-50/30 dark:hover:bg-blue-900/5 transition-colors group">
                        <td class="p-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center text-slate-400 group-hover:bg-blue-600 group-hover:text-white transition-all">
                                    <i data-lucide="route" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-700 dark:text-slate-200 uppercase tracking-tight"><?= $item['nama_jalan'] ?></p>
                                    <p class="text-[10px] text-slate-400 font-medium mt-0.5"><?= $item['jalan'] ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="p-6 text-center">
                            <span class="px-3 py-1.5 bg-blue-50 dark:bg-blue-900/20 text-blue-600 text-[11px] font-bold rounded-lg border border-blue-100 dark:border-blue-800/50 italic"><?= $item['tahun'] ?: '-' ?></span>
                        </td>
                        <td class="p-6">
                            <p class="text-sm font-black text-slate-700 dark:text-slate-200 italic"><?= number_format($item['panjang_luas'], 2, ',', '.') ?><span class="text-[10px] ml-1 opacity-50 font-bold uppercase not-italic">Meter</span></p>
                        </td>
                        <td class="p-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="<?= base_url('psu/detail/' . $item['id']) ?>" class="p-2.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                <?php if (has_permission('edit_psu')) : ?>
                                <a href="<?= base_url('psu/edit/' . $item['id']) ?>" class="p-2.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-xl hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="4" class="p-20 text-center">
                            <div class="flex flex-col items-center">
                                <i data-lucide="database-zap" class="w-16 h-16 text-slate-200 mb-4"></i>
                                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Belum ada data PSU terverifikasi</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php if ($pager) : ?>
        <div class="p-8 bg-slate-50/50 dark:bg-slate-900/50 border-t dark:border-slate-800">
            <?= $pager->links('default', 'tailwind_pager') ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
    window.addEventListener('load', () => {
        lucide.createIcons();
    });
</script>
<?= $this->endSection() ?>
