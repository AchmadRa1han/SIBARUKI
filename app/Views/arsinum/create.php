<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto space-y-8 pb-12">
    <div class="flex items-center gap-4">
        <a href="<?= base_url('arsinum') ?>" class="w-10 h-10 bg-white dark:bg-slate-900 rounded-xl flex items-center justify-center text-slate-600 dark:text-slate-400 shadow-sm border border-slate-100 dark:border-slate-800 hover:bg-blue-600 hover:text-white transition-all">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-blue-950 dark:text-white uppercase tracking-tight">Tambah ARSINUM</h1>
            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest leading-none">Input Proyek Air Siap Minum Baru</p>
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
            <form action="<?= base_url('arsinum/import-csv') ?>" method="post" enctype="multipart/form-data" class="flex items-center gap-2">
                <?= csrf_field() ?>
                <input type="file" name="csv_file" accept=".csv" required class="block w-full text-[10px] text-emerald-900 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 cursor-pointer">
                <button type="submit" class="bg-emerald-900 text-white px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm hover:bg-black transition-all">Upload</button>
            </form>
        </div>
    </div>

    <form action="<?= base_url('arsinum/store') ?>" method="post" class="space-y-6">
        <?= csrf_field() ?>
        
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-10 shadow-sm border border-slate-100 dark:border-slate-800 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="md:col-span-2 space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Jenis Pekerjaan / Nama Objek</label>
                    <input type="text" name="jenis_pekerjaan" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-4 text-sm font-bold text-blue-950 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all uppercase" placeholder="CONTOH: PEMBANGUNAN ARSINUM DESA X">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Volume / Kapasitas</label>
                    <input type="text" name="volume" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-4 text-sm font-bold text-blue-950 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all" placeholder="CONTOH: 1 UNIT">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Anggaran (Rp)</label>
                    <input type="number" name="anggaran" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-4 text-sm font-bold text-blue-950 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all" placeholder="0">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Desa / Lokasi</label>
                    <input type="text" name="desa" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-4 text-sm font-bold text-blue-950 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all uppercase" placeholder="CONTOH: DESA LAPPA">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Kecamatan</label>
                    <input type="text" name="kecamatan" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-4 text-sm font-bold text-blue-950 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all uppercase" placeholder="CONTOH: SINJAI UTARA">
                </div>
                <div class="md:col-span-2 space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Koordinat (Lat, Long)</label>
                    <input type="text" name="koordinat" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-4 text-sm font-bold text-blue-950 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all" placeholder="-5.12345, 120.12345">
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <button type="submit" class="bg-blue-950 text-white px-10 py-5 rounded-3xl text-[10px] font-black uppercase tracking-[0.2em] shadow-2xl hover:bg-black transition-all active:scale-95 flex items-center gap-3 group">
                Simpan Data <i data-lucide="save" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
