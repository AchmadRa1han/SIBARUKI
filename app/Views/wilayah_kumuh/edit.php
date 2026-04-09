<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto space-y-6 pb-32 text-slate-900 dark:text-slate-200">
    
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-3 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 no-print">
        <a href="<?= base_url('dashboard') ?>" class="hover:text-blue-600 transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <a href="<?= base_url('wilayah-kumuh') ?>" class="hover:text-blue-600 transition-colors">Wilayah Kumuh</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">Perbarui Delineasi</span>
    </nav>

    <!-- Header Action -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm transition-all duration-300 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-48 h-48 bg-amber-600/5 rounded-full -mr-24 -mt-24 blur-3xl"></div>
        <div class="relative z-10 flex items-center gap-4">
            <a href="<?= base_url('wilayah-kumuh') ?>" class="p-3 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl hover:bg-blue-600 hover:text-white transition-all active:scale-95" title="Kembali">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 bg-amber-500 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-amber-500/20">
                    <i data-lucide="edit-3" class="w-7 h-7"></i>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-blue-950 dark:text-white uppercase tracking-tighter">Edit Wilayah Kumuh</h1>
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">Perbarui Parameter Kawasan <?= $kumuh['Kelurahan'] ?></p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3 relative z-10">
            <a href="<?= base_url('wilayah-kumuh/detail/' . $kumuh['FID']) ?>" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-slate-700 transition-all active:scale-95">Batal</a>
        </div>
    </div>

    <form action="<?= base_url('wilayah-kumuh/update/' . $kumuh['FID']) ?>" method="post">
        <?= csrf_field() ?>
        <div class="space-y-10">
            <!-- SECTION 1: ATRIBUT ADMINISTRASI -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all duration-300">
                <div class="p-6 border-b dark:border-slate-800 bg-amber-50/30 dark:bg-amber-950/30 flex items-center gap-3">
                    <div class="w-9 h-9 bg-amber-500 rounded-lg flex items-center justify-center text-white shadow-lg">
                        <i data-lucide="map-pin" class="w-4.5 h-4.5"></i>
                    </div>
                    <div>
                        <h3 class="text-[11px] font-bold text-amber-900 dark:text-amber-400 uppercase tracking-[0.2em]">Data Administrasi</h3>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Penyesuaian Wilayah & Lokasi</p>
                    </div>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Nama Kelurahan / Desa</label>
                        <input type="text" name="Kelurahan" value="<?= old('Kelurahan', $kumuh['Kelurahan']) ?>" required class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Kecamatan</label>
                        <input type="text" name="Kecamatan" value="<?= old('Kecamatan', $kumuh['Kecamatan']) ?>" required class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Nama Kawasan</label>
                        <input type="text" name="Kawasan" value="<?= old('Kawasan', $kumuh['Kawasan']) ?>" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Kode RT / RW</label>
                        <input type="text" name="Kode_RT_RW" value="<?= old('Kode_RT_RW', $kumuh['Kode_RT_RW']) ?>" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                    </div>
                    <div class="bg-blue-50 dark:bg-blue-950/30 p-5 rounded-2xl border border-blue-100 dark:border-blue-900/50">
                        <label class="block text-[8px] font-bold text-blue-900 dark:text-blue-400 uppercase mb-2 tracking-widest ml-1">Luas Kawasan (Hektar)</label>
                        <input type="number" step="0.01" name="Luas_kumuh" value="<?= old('Luas_kumuh', $kumuh['Luas_kumuh']) ?>" required class="w-full bg-transparent border-none text-xl font-bold text-blue-950 dark:text-white p-0 focus:ring-0 outline-none">
                    </div>
                    <div class="bg-rose-50 dark:bg-rose-950/30 p-5 rounded-2xl border border-rose-100 dark:border-rose-900/50">
                        <label class="block text-[8px] font-bold text-rose-900 dark:text-rose-400 uppercase mb-2 tracking-widest ml-1">Skor Kekumuhan</label>
                        <input type="number" step="0.01" name="skor_kumuh" value="<?= old('skor_kumuh', $kumuh['skor_kumuh']) ?>" required class="w-full bg-transparent border-none text-xl font-bold text-rose-950 dark:text-white p-0 focus:ring-0 outline-none italic">
                    </div>
                </div>
            </div>

            <!-- SECTION 2: PARAMETER TEKNIS -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all duration-300">
                <div class="p-6 border-b dark:border-slate-800 bg-slate-50/30 dark:bg-slate-950/30 flex items-center gap-3">
                    <div class="w-9 h-9 bg-slate-900 rounded-lg flex items-center justify-center text-white shadow-lg">
                        <i data-lucide="database" class="w-4.5 h-4.5"></i>
                    </div>
                    <div>
                        <h3 class="text-[11px] font-bold text-slate-900 dark:text-slate-400 uppercase tracking-[0.2em]">Parameter & Geometri</h3>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Modifikasi Data Teknis Delineasi</p>
                    </div>
                </div>
                <div class="p-8 space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">No. SK Penetapan</label>
                            <input type="text" name="Sk_Kumuh" value="<?= old('Sk_Kumuh', $kumuh['Sk_Kumuh']) ?>" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                        </div>
                        <div>
                            <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Sumber Basis Data</label>
                            <input type="text" name="Sumber_data" value="<?= old('Sumber_data', $kumuh['Sumber_data']) ?>" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Data Poligon (WKT)</label>
                        <div class="relative">
                            <textarea name="WKT" rows="6" required class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 dark:text-slate-200 outline-none transition-all font-mono text-xs leading-relaxed"><?= old('WKT', $kumuh['WKT']) ?></textarea>
                            <div class="absolute right-3 bottom-3 text-[8px] font-bold text-slate-400 uppercase tracking-widest pointer-events-none">Sistem Koordinat: WGS84</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ACTION BAR -->
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 pt-4">
                <div class="flex items-center gap-3 text-slate-400">
                    <i data-lucide="info" class="w-4 h-4"></i>
                    <p class="text-[9px] font-bold uppercase tracking-widest leading-relaxed max-w-md">Metadata perubahan akan dicatat untuk pemantauan histori delineasi wilayah kumuh.</p>
                </div>
                <button type="submit" class="group flex items-center space-x-6 bg-amber-500 hover:bg-amber-600 text-white pl-8 pr-4 py-4 rounded-xl font-bold shadow-xl shadow-amber-500/20 transition-all active:scale-95 w-full md:w-auto">
                    <div class="flex flex-col text-right">
                        <span class="text-[8px] uppercase tracking-[0.3em] opacity-60 mb-0.5">Simpan Perubahan</span>
                        <span class="text-base uppercase tracking-tighter">Perbarui Delineasi</span>
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
