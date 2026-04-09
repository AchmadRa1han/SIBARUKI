<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto space-y-6 pb-32 text-slate-900 dark:text-slate-200">
    
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-3 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 no-print">
        <a href="<?= base_url('dashboard') ?>" class="hover:text-blue-600 transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <a href="<?= base_url('aset-tanah') ?>" class="hover:text-blue-600 transition-colors">Aset Tanah</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">Tambah Bidang</span>
    </nav>

    <!-- Header Action -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm transition-all duration-300 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-48 h-48 bg-blue-600/5 rounded-full -mr-24 -mt-24 blur-3xl"></div>
        <div class="relative z-10 flex items-center gap-4">
            <a href="<?= base_url('aset-tanah') ?>" class="p-3 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl hover:bg-blue-600 hover:text-white transition-all active:scale-95" title="Kembali">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-blue-600/20">
                    <i data-lucide="plus" class="w-7 h-7"></i>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-blue-950 dark:text-white uppercase tracking-tighter">Tambah Aset Tanah</h1>
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">Registrasi Inventaris Tanah Pemerintah Daerah</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3 relative z-10">
            <a href="<?= base_url('aset-tanah') ?>" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-slate-700 transition-all active:scale-95">Batal</a>
        </div>
    </div>

    <!-- 1. FORM IMPORT -->
    <div class="bg-emerald-50 dark:bg-emerald-950/20 rounded-2xl p-6 border border-emerald-100 dark:border-emerald-900/30 relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-600/5 rounded-full -mr-16 -mt-16 blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 relative z-10">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-emerald-600/20">
                    <i data-lucide="file-up" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-emerald-900 dark:text-emerald-400 uppercase tracking-tight">Import via CSV</h3>
                    <p class="text-[9px] text-emerald-600/70 dark:text-emerald-500/50 font-bold uppercase tracking-[0.2em]">Unggah database sertifikat tanah secara massal</p>
                </div>
            </div>
            <form action="<?= base_url('aset-tanah/import-csv') ?>" method="post" enctype="multipart/form-data" class="flex flex-col md:flex-row items-center gap-3 w-full lg:w-auto">
                <?= csrf_field() ?>
                <input type="file" name="csv_file" accept=".csv" required class="block w-full text-[9px] text-emerald-900 dark:text-emerald-400 file:mr-4 file:py-2 file:px-6 file:rounded-lg file:border-0 file:text-[9px] file:font-bold file:uppercase file:tracking-widest file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 cursor-pointer transition-all">
                <button type="submit" class="w-full md:w-auto bg-emerald-900 dark:bg-emerald-600 text-white px-6 py-2.5 rounded-lg text-[9px] font-bold uppercase tracking-widest shadow-lg shadow-emerald-900/20 hover:bg-black dark:hover:bg-emerald-700 transition-all active:scale-95 flex items-center justify-center gap-2">
                    <i data-lucide="upload-cloud" class="w-3.5 h-3.5"></i> Impor
                </button>
            </form>
        </div>
    </div>

    <!-- 2. FORM MANUAL -->
    <form action="<?= base_url('aset-tanah/store') ?>" method="post">
        <?= csrf_field() ?>
        <div class="space-y-10">
            <!-- SECTION 1: LEGALITAS & PEMILIK -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all duration-300">
                <div class="p-6 border-b dark:border-slate-800 bg-blue-50/30 dark:bg-blue-950/30 flex items-center gap-3">
                    <div class="w-9 h-9 bg-blue-600 rounded-lg flex items-center justify-center text-white shadow-lg">
                        <i data-lucide="clipboard-list" class="w-4.5 h-4.5"></i>
                    </div>
                    <div>
                        <h3 class="text-[11px] font-bold text-blue-900 dark:text-blue-400 uppercase tracking-[0.2em]">Legalitas & Kepemilikan</h3>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Informasi Berdasarkan Sertifikat Resmi</p>
                    </div>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2">
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Nama Pemilik / Nama Aset</label>
                        <input type="text" name="nama_pemilik" required class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase placeholder:opacity-30" placeholder="PEMDA KAB. SINJAI...">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Nomor Sertifikat</label>
                        <input type="text" name="no_sertifikat" required class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Nomor Hak</label>
                        <input type="text" name="nomor_hak" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Tanggal Terbit Sertifikat</label>
                        <input type="date" name="tgl_terbit" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Status Tanah</label>
                        <select name="status_tanah" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all appearance-none font-bold uppercase">
                            <option value="Hak Pakai">Hak Pakai</option>
                            <option value="Hak Milik">Hak Milik</option>
                            <option value="Hak Guna Bangunan">Hak Guna Bangunan</option>
                            <option value="Tanah Negara">Tanah Garapan / Negara</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: DIMENSI & LOKASI -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all duration-300">
                <div class="p-6 border-b dark:border-slate-800 bg-indigo-50/30 dark:bg-indigo-950/30 flex items-center gap-3">
                    <div class="w-9 h-9 bg-indigo-600 rounded-lg flex items-center justify-center text-white shadow-lg">
                        <i data-lucide="map" class="w-4.5 h-4.5"></i>
                    </div>
                    <div>
                        <h3 class="text-[11px] font-bold text-indigo-900 dark:text-indigo-400 uppercase tracking-[0.2em]">Dimensi & Lokasi Bidang</h3>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Parameter Fisik & Penempatan Wilayah</p>
                    </div>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-indigo-50 dark:bg-indigo-950/30 p-5 rounded-2xl border border-indigo-100 dark:border-indigo-900/50">
                        <label class="block text-[8px] font-bold text-indigo-900 dark:text-indigo-400 uppercase mb-2 tracking-widest ml-1">Luas Tanah (M²)</label>
                        <input type="number" step="0.01" name="luas_m2" required class="w-full bg-transparent border-none text-xl font-bold text-indigo-950 dark:text-white p-0 focus:ring-0 outline-none placeholder:opacity-20" placeholder="0.00">
                    </div>
                    <div class="bg-emerald-50 dark:bg-emerald-950/30 p-5 rounded-2xl border border-emerald-100 dark:border-emerald-900/50">
                        <label class="block text-[8px] font-bold text-emerald-900 dark:text-emerald-400 uppercase mb-2 tracking-widest ml-1">Nilai Aset (Rp)</label>
                        <input type="number" name="nilai_aset" required class="w-full bg-transparent border-none text-xl font-bold text-emerald-950 dark:text-white p-0 focus:ring-0 outline-none placeholder:opacity-20" placeholder="0">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Peruntukan</label>
                        <input type="text" name="peruntukan" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 dark:text-slate-200 outline-none transition-all font-bold uppercase" placeholder="KANTOR / FASUM...">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Kecamatan</label>
                        <input type="text" name="kecamatan" required class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Desa / Kelurahan</label>
                        <input type="text" name="desa_kelurahan" required class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Koordinat (Lat, Long)</label>
                        <input type="text" name="koordinat" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 dark:text-slate-200 outline-none transition-all font-mono text-xs" placeholder="-5.123, 120.123">
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Alamat Lengkap / Lokasi Detail</label>
                        <textarea name="lokasi" rows="2" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 dark:text-slate-200 outline-none transition-all font-bold placeholder:opacity-30" placeholder="MASUKKAN ALAMAT LENGKAP..."></textarea>
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Keterangan Tambahan</label>
                        <textarea name="keterangan" rows="2" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 dark:text-slate-200 outline-none transition-all font-bold placeholder:opacity-30" placeholder="CATATAN TAMBAHAN..."></textarea>
                    </div>
                </div>
            </div>

            <!-- ACTION BAR -->
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 pt-4">
                <div class="flex items-center gap-3 text-slate-400">
                    <i data-lucide="info" class="w-4 h-4"></i>
                    <p class="text-[9px] font-bold uppercase tracking-widest leading-relaxed max-w-md">Metadata aset tanah akan diintegrasikan dengan modul geospasial untuk pemantauan aset daerah.</p>
                </div>
                <button type="submit" class="group flex items-center space-x-6 bg-blue-600 hover:bg-blue-700 text-white pl-8 pr-4 py-4 rounded-xl font-bold shadow-xl shadow-blue-600/20 transition-all active:scale-95 w-full md:w-auto">
                    <div class="flex flex-col text-right">
                        <span class="text-[8px] uppercase tracking-[0.3em] opacity-60 mb-0.5">Konfirmasi Final</span>
                        <span class="text-base uppercase tracking-tighter">Simpan Aset</span>
                    </div>
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center group-hover:translate-x-1 transition-transform">
                        <i data-lucide="save" class="w-5 h-5"></i>
                    </div>
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>
<?= $this->endSection() ?>
