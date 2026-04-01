<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto space-y-8 pb-32">
    
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-3 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 no-print">
        <a href="<?= base_url('dashboard') ?>" class="hover:text-blue-600 transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <a href="<?= base_url('arsinum') ?>" class="hover:text-blue-600 transition-colors">ARSINUM</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">Tambah Aset</span>
    </nav>

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 bg-white dark:bg-slate-900 p-10 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm transition-all duration-300 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-blue-600/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
        <div class="relative z-10 flex items-center gap-6">
            <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-blue-600/20">
                <i data-lucide="plus" class="w-8 h-8"></i>
            </div>
            <div>
                <h1 class="text-3xl md:text-4xl font-black text-blue-950 dark:text-white uppercase tracking-tighter">Tambah ARSINUM</h1>
                <p class="text-sm text-slate-500 font-bold uppercase tracking-widest mt-1">Input Proyek Air Siap Minum Baru</p>
            </div>
        </div>
        <div class="flex items-center gap-4 relative z-10">
            <a href="<?= base_url('arsinum') ?>" class="px-8 py-4 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-slate-700 transition-all active:scale-95">Batal</a>
        </div>
    </div>

    <!-- 1. FORM IMPORT -->
    <div class="bg-emerald-50 dark:bg-emerald-950/20 rounded-[2.5rem] p-10 border border-emerald-100 dark:border-emerald-900/30 relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-600/5 rounded-full -mr-16 -mt-16 blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 relative z-10">
            <div class="flex items-center gap-6">
                <div class="w-14 h-14 bg-emerald-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-emerald-600/20">
                    <i data-lucide="file-up" class="w-7 h-7"></i>
                </div>
                <div>
                    <h3 class="text-lg font-black text-emerald-900 dark:text-emerald-400 uppercase tracking-tight">Import via CSV</h3>
                    <p class="text-[10px] text-emerald-600/70 dark:text-emerald-500/50 font-bold uppercase tracking-[0.2em]">Unggah file untuk pengisian data massal</p>
                </div>
            </div>
            <form action="<?= base_url('arsinum/import-csv') ?>" method="post" enctype="multipart/form-data" class="flex flex-col md:flex-row items-center gap-4 w-full lg:w-auto">
                <?= csrf_field() ?>
                <input type="file" name="csv_file" accept=".csv" required class="block w-full text-[10px] text-emerald-900 dark:text-emerald-400 file:mr-6 file:py-3 file:px-8 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-widest file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 cursor-pointer transition-all">
                <button type="submit" class="w-full md:w-auto bg-emerald-900 dark:bg-emerald-600 text-white px-10 py-4 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-emerald-900/20 hover:bg-black dark:hover:bg-emerald-700 transition-all active:scale-95 flex items-center justify-center gap-2">
                    <i data-lucide="upload-cloud" class="w-4 h-4"></i> Mulai Impor
                </button>
            </form>
        </div>
    </div>

    <!-- 2. FORM MANUAL -->
    <form action="<?= base_url('arsinum/store') ?>" method="post">
        <?= csrf_field() ?>
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all duration-300">
            <div class="p-8 border-b dark:border-slate-800 bg-blue-50/30 dark:bg-blue-950/30 flex items-center gap-4">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                    <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-xs font-black text-blue-900 dark:text-blue-400 uppercase tracking-[0.2em]">Rincian Aset Arsinum</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Informasi Teknis & Wilayah</p>
                </div>
            </div>
            <div class="p-10 grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Jenis Pekerjaan / Nama Objek</label>
                    <input type="text" name="jenis_pekerjaan" required class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-black uppercase placeholder:opacity-30" placeholder="PEMBANGUNAN ARSINUM...">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Volume / Kapasitas</label>
                    <input type="text" name="volume" required class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase" placeholder="1 UNIT">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Tahun Anggaran</label>
                    <input type="number" name="tahun" value="<?= date('Y') ?>" required class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold">
                </div>
                <div class="bg-blue-50 dark:bg-blue-950/30 p-8 rounded-[2rem] border border-blue-100 dark:border-blue-900/50">
                    <label class="block text-[10px] font-black text-blue-900 dark:text-blue-400 uppercase mb-3 tracking-widest ml-1">Total Pagu Anggaran (Rp)</label>
                    <input type="number" name="anggaran" required class="w-full bg-transparent border-none text-2xl font-black text-blue-950 dark:text-white p-0 focus:ring-0 outline-none" placeholder="0">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Sumber Dana</label>
                    <input type="text" name="sumber_dana" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase" placeholder="APBD / DAK / DLL">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Kecamatan</label>
                    <input type="text" name="kecamatan" required class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Desa / Kelurahan</label>
                    <input type="text" name="desa" required class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Pelaksana / Kontraktor</label>
                    <input type="text" name="pelaksana" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Koordinat (Latitude, Longitude)</label>
                    <div class="relative">
                        <input type="text" name="koordinat" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-mono text-sm" placeholder="-5.12345, 120.12345">
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 text-[9px] font-black text-slate-400 uppercase tracking-widest pointer-events-none">Format: Lat, Long</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="flex flex-col md:flex-row justify-between items-center gap-6 pt-8">
            <div class="flex items-center gap-4 text-slate-400">
                <i data-lucide="info" class="w-5 h-5"></i>
                <p class="text-[10px] font-bold uppercase tracking-widest leading-relaxed max-w-md">Pastikan seluruh informasi teknis dan wilayah telah sesuai dengan dokumen kontrak sebelum menyimpan.</p>
            </div>
            <button type="submit" class="group flex items-center space-x-8 bg-blue-600 hover:bg-blue-700 text-white pl-12 pr-6 py-6 rounded-[2.5rem] font-black shadow-2xl shadow-blue-600/20 transition-all active:scale-95 w-full md:w-auto">
                <div class="flex flex-col text-right">
                    <span class="text-[10px] uppercase tracking-[0.3em] opacity-60 mb-1">Konfirmasi Final</span>
                    <span class="text-xl uppercase tracking-tighter">Simpan Aset</span>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center group-hover:translate-x-2 transition-transform">
                    <i data-lucide="save" class="w-6 h-6"></i>
                </div>
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>
<?= $this->endSection() ?>
