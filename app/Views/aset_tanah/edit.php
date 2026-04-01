<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto space-y-8 pb-32">
    
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-3 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 no-print">
        <a href="<?= base_url('dashboard') ?>" class="hover:text-blue-600 transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <a href="<?= base_url('aset-tanah') ?>" class="hover:text-blue-600 transition-colors">Aset Tanah</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">Perbarui Bidang</span>
    </nav>

    <!-- Header Action -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 bg-white dark:bg-slate-900 p-10 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm transition-all duration-300 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-amber-600/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
        <div class="relative z-10 flex items-center gap-6">
            <div class="w-16 h-16 bg-amber-500 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-amber-500/20">
                <i data-lucide="edit-3" class="w-8 h-8"></i>
            </div>
            <div>
                <h1 class="text-3xl md:text-4xl font-black text-blue-950 dark:text-white uppercase tracking-tighter">Edit Aset Tanah</h1>
                <p class="text-sm text-slate-500 font-bold uppercase tracking-widest mt-1">Perbarui Parameter Inventaris Sertifikat</p>
            </div>
        </div>
        <div class="flex items-center gap-4 relative z-10">
            <a href="<?= base_url('aset-tanah/detail/' . $aset['id']) ?>" class="px-8 py-4 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-slate-700 transition-all active:scale-95">Batal</a>
        </div>
    </div>

    <form action="<?= base_url('aset-tanah/update/' . $aset['id']) ?>" method="post">
        <?= csrf_field() ?>
        <div class="space-y-12">
            <!-- SECTION 1: LEGALITAS & PEMILIK -->
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all duration-300">
                <div class="p-8 border-b dark:border-slate-800 bg-blue-50/30 dark:bg-blue-950/30 flex items-center gap-4">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                        <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-xs font-black text-blue-900 dark:text-blue-400 uppercase tracking-[0.2em]">Legalitas & Kepemilikan</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Sinkronisasi Data Sertifikat</p>
                    </div>
                </div>
                <div class="p-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2">
                        <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Nama Pemilik / Nama Aset</label>
                        <input type="text" name="nama_pemilik" value="<?= old('nama_pemilik', $aset['nama_pemilik']) ?>" required class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-black uppercase">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Nomor Sertifikat</label>
                        <input type="text" name="no_sertifikat" value="<?= old('no_sertifikat', $aset['no_sertifikat']) ?>" required class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Nomor Hak</label>
                        <input type="text" name="nomor_hak" value="<?= old('nomor_hak', $aset['nomor_hak']) ?>" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Tanggal Terbit</label>
                        <input type="date" name="tgl_terbit" value="<?= old('tgl_terbit', $aset['tgl_terbit']) ?>" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Status Tanah</label>
                        <select name="status_tanah" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all appearance-none font-bold uppercase">
                            <option value="Hak Pakai" <?= old('status_tanah', $aset['status_tanah']) == 'Hak Pakai' ? 'selected' : '' ?>>Hak Pakai</option>
                            <option value="Hak Milik" <?= old('status_tanah', $aset['status_tanah']) == 'Hak Milik' ? 'selected' : '' ?>>Hak Milik</option>
                            <option value="Hak Guna Bangunan" <?= old('status_tanah', $aset['status_tanah']) == 'Hak Guna Bangunan' ? 'selected' : '' ?>>Hak Guna Bangunan</option>
                            <option value="Tanah Negara" <?= old('status_tanah', $aset['status_tanah']) == 'Tanah Negara' ? 'selected' : '' ?>>Tanah Garapan / Negara</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: DIMENSI & LOKASI -->
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all duration-300">
                <div class="p-8 border-b dark:border-slate-800 bg-indigo-50/30 dark:bg-indigo-950/30 flex items-center gap-4">
                    <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                        <i data-lucide="map" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-xs font-black text-indigo-900 dark:text-indigo-400 uppercase tracking-[0.2em]">Dimensi & Lokasi Bidang</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Parameter Fisik & Penempatan Wilayah</p>
                    </div>
                </div>
                <div class="p-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="bg-indigo-50 dark:bg-indigo-950/30 p-6 rounded-[2rem] border border-indigo-100 dark:border-indigo-900/50">
                        <label class="block text-[10px] font-black text-indigo-900 dark:text-indigo-400 uppercase mb-3 tracking-widest ml-1">Luas Tanah (M²)</label>
                        <input type="number" step="0.01" name="luas_m2" value="<?= old('luas_m2', $aset['luas_m2']) ?>" required class="w-full bg-transparent border-none text-2xl font-black text-indigo-950 dark:text-white p-0 focus:ring-0 outline-none">
                    </div>
                    <div class="bg-emerald-50 dark:bg-emerald-950/30 p-6 rounded-[2rem] border border-emerald-100 dark:border-emerald-900/50">
                        <label class="block text-[10px] font-black text-emerald-900 dark:text-emerald-400 uppercase mb-3 tracking-widest ml-1">Nilai Aset (Rp)</label>
                        <input type="number" name="nilai_aset" value="<?= old('nilai_aset', $aset['nilai_aset']) ?>" required class="w-full bg-transparent border-none text-2xl font-black text-emerald-950 dark:text-white p-0 focus:ring-0 outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Peruntukan</label>
                        <input type="text" name="peruntukan" value="<?= old('peruntukan', $aset['peruntukan']) ?>" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Kecamatan</label>
                        <input type="text" name="kecamatan" value="<?= old('kecamatan', $aset['kecamatan']) ?>" required class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Desa / Kelurahan</label>
                        <input type="text" name="desa_kelurahan" value="<?= old('desa_kelurahan', $aset['desa_kelurahan']) ?>" required class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Titik Koordinat (Lat, Long)</label>
                        <input type="text" name="koordinat" value="<?= old('koordinat', $aset['koordinat']) ?>" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 dark:text-slate-200 outline-none transition-all font-mono text-sm">
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Alamat Lengkap / Lokasi Detail</label>
                        <textarea name="lokasi" rows="2" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 dark:text-slate-200 outline-none transition-all font-bold"><?= old('lokasi', $aset['lokasi']) ?></textarea>
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Keterangan Tambahan</label>
                        <textarea name="keterangan" rows="2" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 dark:text-slate-200 outline-none transition-all font-bold"><?= old('keterangan', $aset['keterangan']) ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Action Bar -->
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 pt-8">
                <div class="flex items-center gap-4 text-slate-400">
                    <i data-lucide="info" class="w-5 h-5"></i>
                    <p class="text-[10px] font-bold uppercase tracking-widest leading-relaxed max-w-md"> Metadata perubahan akan dicatat dalam sistem log audit pertanahan pemerintah daerah.</p>
                </div>
                <button type="submit" class="group flex items-center space-x-8 bg-amber-500 hover:bg-amber-600 text-white pl-12 pr-6 py-6 rounded-[2.5rem] font-black shadow-2xl shadow-amber-500/20 transition-all active:scale-95 w-full md:w-auto">
                    <div class="flex flex-col text-right">
                        <span class="text-[10px] uppercase tracking-[0.3em] opacity-60 mb-1">Simpan Perubahan</span>
                        <span class="text-xl uppercase tracking-tighter">Perbarui Aset</span>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center group-hover:translate-x-2 transition-transform">
                        <i data-lucide="save" class="w-6 h-6"></i>
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
