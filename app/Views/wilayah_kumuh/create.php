<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-5xl mx-auto">
    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">
        <div class="p-6 border-b bg-gray-50/50 flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold text-gray-800">Tambah Lokasi Wilayah Kumuh</h1>
                <p class="text-sm text-gray-500">Input data spasial dan statistik kawasan kumuh baru</p>
            </div>
            <a href="<?= base_url('wilayah-kumuh') ?>" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i data-lucide="x" class="w-6 h-6"></i>
            </a>
        </div>

        <form action="<?= base_url('wilayah-kumuh/store') ?>" method="post" class="p-8">
            <?= csrf_field() ?>
            
            <div class="space-y-8">
                <!-- Bagian 1: Identitas Wilayah -->
                <div>
                    <h3 class="text-sm font-bold text-blue-600 uppercase tracking-widest mb-4 flex items-center">
                        <i data-lucide="map-pin" class="w-4 h-4 mr-2"></i> Identitas Wilayah
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">FID</label>
                            <input type="number" name="FID" required class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 outline-none">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Kawasan</label>
                            <input type="text" name="Kawasan" placeholder="Contoh: Kawasan Pesisir A" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Kecamatan</label>
                            <input type="text" name="Kecamatan" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Kelurahan/Desa</label>
                            <input type="text" name="Kelurahan" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">RT / RW</label>
                            <input type="text" name="Kode_RT_RW" placeholder="001/002" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 outline-none">
                        </div>
                    </div>
                </div>

                <hr class="border-gray-100">

                <!-- Bagian 2: Statistik & Skor -->
                <div>
                    <h3 class="text-sm font-bold text-blue-600 uppercase tracking-widest mb-4 flex items-center">
                        <i data-lucide="bar-chart-3" class="w-4 h-4 mr-2"></i> Statistik & Penilaian
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Luas Kumuh (Ha)</label>
                            <input type="number" step="0.01" name="Luas_kumuh" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 outline-none font-mono">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Skor Kumuh</label>
                            <input type="number" step="0.01" name="skor_kumuh" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 outline-none font-mono">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">No. SK Kumuh</label>
                            <input type="text" name="Sk_Kumuh" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 outline-none">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Sumber Data</label>
                            <input type="text" name="Sumber_data" placeholder="Contoh: Dinas Perkim 2023" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 outline-none">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">ID Desa (Relasi)</label>
                            <input type="number" name="desa_id" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 outline-none font-mono">
                        </div>
                    </div>
                </div>

                <hr class="border-gray-100">

                <!-- Bagian 3: Data Spasial -->
                <div>
                    <h3 class="text-sm font-bold text-blue-600 uppercase tracking-widest mb-4 flex items-center">
                        <i data-lucide="globe" class="w-4 h-4 mr-2"></i> Data Spasial (WKT)
                    </h3>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Well-Known Text (Geometry)</label>
                        <textarea name="WKT" rows="4" placeholder="POLYGON((...))" class="w-full p-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 outline-none font-mono text-xs"></textarea>
                    </div>
                </div>
            </div>

            <div class="mt-10 flex items-center justify-end space-x-4 border-t pt-8">
                <a href="<?= base_url('wilayah-kumuh') ?>" class="text-sm font-bold text-gray-400 hover:text-gray-600 transition-colors">Batal</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-10 py-3 rounded-xl font-bold shadow-lg shadow-blue-200 transition-all flex items-center space-x-2">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>Simpan Wilayah</span>
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
