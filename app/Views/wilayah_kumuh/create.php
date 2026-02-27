<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-5xl mx-auto">
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-300">
        <div class="p-8 border-b dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-black text-blue-950 dark:text-white uppercase tracking-wider">Tambah Lokasi Wilayah Kumuh</h1>
                <p class="text-sm text-slate-400 dark:text-slate-500 font-medium italic">Input data spasial dan statistik kawasan kumuh baru</p>
            </div>
            <a href="<?= base_url('wilayah-kumuh') ?>" class="text-slate-400 dark:text-slate-600 hover:text-rose-500 transition-colors">
                <i data-lucide="x-circle" class="w-8 h-8"></i>
            </a>
        </div>

        <form action="<?= base_url('wilayah-kumuh/store') ?>" method="post" class="p-10">
            <?= csrf_field() ?>
            
            <div class="space-y-12">
                <!-- Bagian 1: Identitas Wilayah -->
                <div>
                    <h3 class="text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-[0.3em] mb-8 flex items-center">
                        <i data-lucide="map-pin" class="w-4 h-4 mr-3"></i> Identitas Wilayah
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Nama Kawasan</label>
                            <input type="text" name="Kawasan" placeholder="Contoh: Kawasan Pesisir A" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Kecamatan</label>
                            <input type="text" name="Kecamatan" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Kelurahan/Desa</label>
                            <input type="text" name="Kelurahan" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">RT / RW</label>
                            <input type="text" name="Kode_RT_RW" placeholder="001/002" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all">
                        </div>
                    </div>
                </div>

                <hr class="border-slate-100 dark:border-slate-800">

                <!-- Bagian 2: Statistik & Skor -->
                <div>
                    <h3 class="text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-[0.3em] mb-8 flex items-center">
                        <i data-lucide="bar-chart-3" class="w-4 h-4 mr-3"></i> Statistik & Penilaian
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Luas Kumuh (Ha)</label>
                            <input type="number" step="0.01" name="Luas_kumuh" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-mono">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Skor Kumuh</label>
                            <input type="number" step="0.01" name="skor_kumuh" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-mono">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">No. SK Kumuh</label>
                            <input type="text" name="Sk_Kumuh" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Sumber Data</label>
                            <input type="text" name="Sumber_data" placeholder="Contoh: Dinas Perkim 2023" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">ID Desa (Relasi)</label>
                            <input type="number" name="desa_id" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-mono">
                        </div>
                    </div>
                </div>

                <hr class="border-slate-100 dark:border-slate-800">

                <!-- Bagian 3: Data Spasial -->
                <div>
                    <h3 class="text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-[0.3em] mb-8 flex items-center">
                        <i data-lucide="globe" class="w-4 h-4 mr-3"></i> Data Spasial (WKT)
                    </h3>
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Well-Known Text (Geometry)</label>
                        <textarea name="WKT" rows="4" placeholder="POLYGON((...))" class="w-full p-5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-blue-400 outline-none transition-all font-mono text-xs"></textarea>
                    </div>
                </div>
            </div>

            <div class="mt-12 flex items-center justify-end space-x-6 border-t dark:border-slate-800 pt-10">
                <a href="<?= base_url('wilayah-kumuh') ?>" class="text-sm font-bold text-slate-400 dark:text-slate-600 hover:text-slate-600 dark:hover:text-slate-400 transition-colors">Batal</a>
                <button type="submit" class="bg-blue-900 dark:bg-blue-700 hover:bg-blue-950 dark:hover:bg-blue-600 text-white px-12 py-4 rounded-2xl font-black shadow-xl shadow-blue-900/30 transition-all flex items-center space-x-3">
                    <i data-lucide="save" class="w-5 h-5"></i>
                    <span>Simpan Wilayah</span>
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
