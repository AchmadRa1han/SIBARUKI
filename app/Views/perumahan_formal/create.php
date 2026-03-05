<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto pb-20 space-y-8">
    <div class="flex items-center gap-4">
        <a href="<?= base_url('perumahan-formal') ?>" class="p-3 bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 hover:bg-slate-50 transition-all">
            <i data-lucide="arrow-left" class="w-5 h-5 text-slate-500"></i>
        </a>
        <div>
            <h1 class="text-3xl font-black text-blue-950 dark:text-white uppercase tracking-tight">Tambah Perumahan</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium text-sm">Input data perumahan formal baru.</p>
        </div>
    </div>

    <!-- Import Card -->
    <div class="bg-emerald-50 dark:bg-emerald-950/20 rounded-[2.5rem] p-8 border border-emerald-100 dark:border-emerald-900/30">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                    <i data-lucide="file-up" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black text-emerald-900 dark:text-emerald-400 uppercase tracking-tight">Import via CSV</h3>
                    <p class="text-[10px] text-emerald-600/70 font-bold uppercase tracking-widest">Unggah file untuk impor massal</p>
                </div>
            </div>
            <form action="<?= base_url('perumahan-formal/import-csv') ?>" method="post" enctype="multipart/form-data" class="flex items-center gap-2">
                <?= csrf_field() ?>
                <input type="file" name="csv_file" accept=".csv" required class="block w-full text-[10px] text-emerald-900 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 cursor-pointer">
                <button type="submit" class="bg-emerald-900 text-white px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm hover:bg-black transition-all">Upload</button>
            </form>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-10 shadow-sm border border-slate-100 dark:border-slate-800 flex flex-col items-center justify-center text-center py-20">
        <i data-lucide="constructor" class="w-16 h-16 text-slate-200 mb-4"></i>
        <h3 class="text-lg font-black text-blue-950 dark:text-white uppercase tracking-tight">Form Manual Segera Hadir</h3>
        <p class="text-slate-400 text-sm max-w-sm">Untuk saat ini, silakan gunakan fitur **Import via CSV** di atas untuk menambahkan data perumahan.</p>
    </div>
</div>
<?= $this->endSection() ?>
