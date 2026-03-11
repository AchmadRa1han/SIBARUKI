<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto pb-20 space-y-8">
    <div class="flex items-center gap-4">
        <a href="<?= base_url('perumahan-formal') ?>" class="p-3 bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 hover:bg-slate-50 transition-all">
            <i data-lucide="arrow-left" class="w-5 h-5 text-slate-500"></i>
        </a>
        <div>
            <h1 class="text-3xl font-black text-blue-950 dark:text-white uppercase tracking-tight">Tambah Perumahan</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium text-sm">Input data perumahan formal baru secara manual atau via CSV.</p>
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

    <form action="<?= base_url('perumahan-formal/store') ?>" method="post" class="space-y-6">
        <?= csrf_field() ?>
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-8 shadow-sm border border-slate-100 dark:border-slate-800">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2 space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Perumahan</label>
                    <input type="text" name="nama_perumahan" value="<?= old('nama_perumahan') ?>" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all uppercase" placeholder="CONTOH: PERUMAHAN BUMI SINJAI">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Pengembang</label>
                    <input type="text" name="pengembang" value="<?= old('pengembang') ?>" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all uppercase" placeholder="NAMA PT / DEVELOPER">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tahun Pembangunan</label>
                    <input type="number" name="tahun_pembangunan" value="<?= old('tahun_pembangunan') ?>" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all" placeholder="2024">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Luas Kawasan (Ha)</label>
                    <input type="number" step="0.01" name="luas_kawasan_ha" value="<?= old('luas_kawasan_ha') ?>" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all" placeholder="0.00">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Longitude</label>
                    <input type="text" name="longitude" value="<?= old('longitude') ?>" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all" placeholder="120.xxxx">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Latitude</label>
                    <input type="text" name="latitude" value="<?= old('latitude') ?>" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all" placeholder="-5.xxxx">
                </div>
                <div class="md:col-span-2 space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Data Spasial (WKT)</label>
                    <textarea name="wkt" rows="3" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all font-mono text-xs" placeholder="POLYGON((...)) atau POINT(...)"><?= old('wkt') ?></textarea>
                </div>
            </div>
        </div>
        <div class="flex justify-end gap-4">
            <button type="submit" class="px-12 py-4 bg-blue-950 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl hover:bg-black transition-all flex items-center gap-3">
                Simpan Data Perumahan <i data-lucide="save" class="w-4 h-4"></i>
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
