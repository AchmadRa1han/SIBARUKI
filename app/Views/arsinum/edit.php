<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto space-y-6 pb-32 text-slate-900 dark:text-slate-200">
    
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-3 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 no-print">
        <a href="<?= base_url('dashboard') ?>" class="hover:text-blue-600 transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <a href="<?= base_url('arsinum') ?>" class="hover:text-blue-600 transition-colors">ARSINUM</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">Perbarui Data</span>
    </nav>

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm transition-all duration-300 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-48 h-48 bg-amber-600/5 rounded-full -mr-24 -mt-24 blur-3xl"></div>
        <div class="relative z-10 flex items-center gap-4">
            <a href="<?= base_url('arsinum') ?>" class="p-3 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl hover:bg-blue-600 hover:text-white transition-all active:scale-95" title="Kembali">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-blue-950 dark:text-white uppercase tracking-tighter">Edit ARSINUM</h1>
                <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">Perbarui Data Proyek Air Siap Minum</p>
            </div>
        </div>
        <div class="flex items-center gap-3 relative z-10">
            <a href="<?= base_url('arsinum/detail/' . $item['id']) ?>" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-slate-700 transition-all active:scale-95">Batal</a>
        </div>
    </div>

    <form action="<?= base_url('arsinum/update/' . $item['id']) ?>" method="post">
        <?= csrf_field() ?>
        <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all duration-300">
            <div class="p-6 border-b dark:border-slate-800 bg-blue-50/30 dark:bg-blue-950/30 flex items-center gap-3">
                <div class="w-9 h-9 bg-blue-600 rounded-lg flex items-center justify-center text-white shadow-lg">
                    <i data-lucide="clipboard-list" class="w-4.5 h-4.5"></i>
                </div>
                <div>
                    <h3 class="text-[11px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-[0.2em]">Data Teknis Aset</h3>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Sinkronisasi Informasi & Wilayah</p>
                </div>
            </div>
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="md:col-span-2">
                    <label class="block text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Jenis Pekerjaan / Nama Objek</label>
                    <input type="text" name="jenis_pekerjaan" value="<?= old('jenis_pekerjaan', $item['jenis_pekerjaan']) ?>" required class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-black uppercase" placeholder="PEMBANGUNAN ARSINUM...">
                </div>
                <div>
                    <label class="block text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Volume / Kapasitas</label>
                    <input type="text" name="volume" value="<?= old('volume', $item['volume']) ?>" required class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                </div>
                <div>
                    <label class="block text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Tahun Anggaran</label>
                    <input type="number" name="tahun" value="<?= old('tahun', $item['tahun']) ?>" required class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold">
                </div>
                <div class="bg-blue-50 dark:bg-blue-950/30 p-6 rounded-2xl border border-blue-100 dark:border-blue-900/50">
                    <label class="block text-[8px] font-black text-blue-900 dark:text-blue-400 uppercase mb-2 tracking-widest ml-1">Total Pagu Anggaran (Rp)</label>
                    <input type="number" name="anggaran" value="<?= old('anggaran', $item['anggaran']) ?>" required class="w-full bg-transparent border-none text-xl font-black text-blue-950 dark:text-white p-0 focus:ring-0 outline-none">
                </div>
                <div>
                    <label class="block text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Sumber Dana</label>
                    <input type="text" name="sumber_dana" value="<?= old('sumber_dana', $item['sumber_dana']) ?>" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                </div>
                <div>
                    <label class="block text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Kecamatan</label>
                    <input type="text" name="kecamatan" value="<?= old('kecamatan', $item['kecamatan']) ?>" required class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                </div>
                <div>
                    <label class="block text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Desa / Kelurahan</label>
                    <input type="text" name="desa" value="<?= old('desa', $item['desa']) ?>" required class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Pelaksana / Kontraktor</label>
                    <input type="text" name="pelaksana" value="<?= old('pelaksana', $item['pelaksana']) ?>" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Koordinat (Lat, Long)</label>
                    <input type="text" name="koordinat" value="<?= old('koordinat', $item['koordinat']) ?>" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-mono text-xs">
                </div>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="flex flex-col md:flex-row justify-between items-center gap-6 pt-4">
            <div class="flex items-center gap-3 text-slate-400">
                <i data-lucide="info" class="w-4 h-4"></i>
                <p class="text-[9px] font-bold uppercase tracking-widest leading-relaxed max-w-md">Metadata perubahan akan dicatat dalam sistem log audit untuk keperluan inventaris daerah.</p>
            </div>
            <button type="submit" class="group flex items-center space-x-6 bg-amber-500 hover:bg-amber-600 text-white pl-8 pr-4 py-4 rounded-[1.5rem] font-black shadow-xl shadow-amber-500/20 transition-all active:scale-95 w-full md:w-auto">
                <div class="flex flex-col text-right">
                    <span class="text-[8px] uppercase tracking-[0.3em] opacity-60 mb-0.5">Simpan Perubahan</span>
                    <span class="text-base uppercase tracking-tighter">Perbarui Aset</span>
                </div>
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center group-hover:translate-x-1 transition-transform">
                    <i data-lucide="save" class="w-5 h-5"></i>
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
