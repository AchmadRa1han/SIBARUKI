<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto space-y-8 pb-12">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="<?= base_url('aset-tanah') ?>" class="w-10 h-10 bg-white dark:bg-slate-900 rounded-xl flex items-center justify-center text-slate-600 dark:text-slate-400 shadow-sm border border-slate-100 dark:border-slate-800 hover:bg-blue-600 hover:text-white transition-all">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-blue-950 dark:text-white uppercase tracking-tight">Tambah Aset Baru</h1>
            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest leading-none">Input Inventaris Tanah Pemda</p>
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
            <form action="<?= base_url('aset-tanah/import-csv') ?>" method="post" enctype="multipart/form-data" class="flex items-center gap-2">
                <?= csrf_field() ?>
                <input type="file" name="csv_file" accept=".csv" required class="block w-full text-[10px] text-emerald-900 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 cursor-pointer">
                <button type="submit" class="bg-emerald-900 text-white px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm hover:bg-black transition-all">Upload</button>
            </form>
        </div>
    </div>

    <form action="<?= base_url('aset-tanah/store') ?>" method="post" class="space-y-6">
        <?= csrf_field() ?>
        
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-8 shadow-sm border border-slate-100 dark:border-slate-800">
            <h3 class="text-sm font-black text-blue-950 dark:text-white uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                <span class="w-8 h-1 bg-blue-600 rounded-full"></span> Data Legalitas
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nomor Sertifikat</label>
                    <input type="text" name="no_sertifikat" value="<?= old('no_sertifikat') ?>" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all" placeholder="Contoh: 00031">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Pemilik / Instansi</label>
                    <input type="text" name="nama_pemilik" value="<?= old('nama_pemilik') ?>" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all" placeholder="Contoh: UPTD SMPN 23 SINJAI">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Luas Tanah (m²)</label>
                    <input type="number" step="0.01" name="luas_m2" value="<?= old('luas_m2') ?>" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all" placeholder="0.00">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tanggal Terbit</label>
                    <input type="date" name="tgl_terbit" value="<?= old('tgl_terbit') ?>" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-8 shadow-sm border border-slate-100 dark:border-slate-800">
            <h3 class="text-sm font-black text-blue-950 dark:text-white uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                <span class="w-8 h-1 bg-emerald-500 rounded-full"></span> Lokasi & Teknis
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Kecamatan</label>
                    <input type="text" name="kecamatan" value="<?= old('kecamatan') ?>" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all" placeholder="Contoh: SINJAI UTARA">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Desa / Kelurahan</label>
                    <input type="text" name="desa_kelurahan" value="<?= old('desa_kelurahan') ?>" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all" placeholder="Contoh: Bongki">
                </div>
                <div class="md:col-span-2 space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Koordinat (Lat, Long)</label>
                    <input type="text" name="koordinat" value="<?= old('koordinat') ?>" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all" placeholder="-5.12345, 120.12345">
                </div>
                <div class="md:col-span-2 space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Alamat Lengkap</label>
                    <textarea name="lokasi" rows="3" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all" placeholder="Masukkan alamat lengkap lokasi aset..."><?= old('lokasi') ?></textarea>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4">
            <button type="reset" class="px-8 py-3.5 text-sm font-black text-slate-400 uppercase tracking-widest hover:text-rose-500 transition-all">Reset</button>
            <button type="submit" class="px-10 py-3.5 bg-blue-600 text-white rounded-2xl text-xs font-black uppercase tracking-[0.2em] shadow-xl shadow-blue-200 dark:shadow-none hover:bg-blue-700 transition-all">Simpan Aset</button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
