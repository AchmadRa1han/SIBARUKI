<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-6xl mx-auto">
    <form action="<?= base_url('rtlh/store') ?>" method="post">
        <?= csrf_field() ?>

        <div class="space-y-8">
            <!-- HEADER -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-black text-blue-950 dark:text-white uppercase tracking-wider">Input Data RTLH Terpadu</h1>
                    <p class="text-sm text-slate-400 dark:text-slate-500 font-medium italic">Isi seluruh informasi pemilik, rumah, dan kondisi fisik secara sekaligus.</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="<?= base_url('rtlh') ?>" class="text-sm font-bold text-slate-400 dark:text-slate-600 hover:text-slate-600 dark:hover:text-slate-400 px-4 py-2 transition-colors">Batal</a>
                    <button type="submit" class="bg-blue-900 dark:bg-blue-700 hover:bg-blue-950 dark:hover:bg-blue-600 text-white px-8 py-3 rounded-2xl font-bold shadow-lg shadow-blue-900/20 transition-all flex items-center space-x-2">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        <span>Simpan Semua Data</span>
                    </button>
                </div>
            </div>

            <?php if (session()->getFlashdata('message')) : ?>
                <div class="bg-rose-50 dark:bg-rose-950/30 border border-rose-100 dark:border-rose-900 text-rose-700 dark:text-rose-400 px-6 py-4 rounded-2xl text-sm font-bold shadow-sm transition-colors duration-300">
                    <i data-lucide="alert-circle" class="w-4 h-4 inline mr-2"></i>
                    <?= session()->getFlashdata('message') ?>
                </div>
            <?php endif; ?>

            <!-- SECTION 1: DATA PENERIMA -->
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-300">
                <div class="p-6 border-b dark:border-slate-800 bg-blue-50/30 dark:bg-blue-950/30">
                    <h3 class="font-black text-blue-900 dark:text-blue-400 uppercase tracking-widest text-xs flex items-center">
                        <span class="w-8 h-8 bg-blue-900 dark:bg-blue-700 text-white rounded-xl flex items-center justify-center mr-3 text-[10px] shadow-lg shadow-blue-900/20">1</span>
                        Data Penerima (Pemilik)
                    </h3>
                </div>
                <div class="p-10 grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <label class="block text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest">NIK (16 Digit)</label>
                        <input type="text" name="nik" maxlength="16" required class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-mono">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest">No. KK</label>
                        <input type="text" name="no_kk" maxlength="16" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-mono">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest">Nama Kepala Keluarga</label>
                        <input type="text" name="nama_kepala_keluarga" required class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all">
                    </div>
                    
                    <div>
                        <label class="block text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest">Pendidikan</label>
                        <select name="pendidikan_id" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all appearance-none">
                            <option value="">Pilih Pendidikan</option>
                            <?php if(isset($master['PENDIDIKAN'])): foreach($master['PENDIDIKAN'] as $p): ?>
                                <option value="<?= $p['id'] ?>"><?= $p['nama_pilihan'] ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest">Pekerjaan</label>
                        <select name="pekerjaan_id" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all appearance-none">
                            <option value="">Pilih Pekerjaan</option>
                            <?php if(isset($master['PEKERJAAN'])): foreach($master['PEKERJAAN'] as $p): ?>
                                <option value="<?= $p['id'] ?>"><?= $p['nama_pilihan'] ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest">Penghasilan / Bulan</label>
                        <select name="penghasilan_per_bulan" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all appearance-none">
                            <option value="">Pilih Penghasilan</option>
                            <option value="0 - 1,2 juta">0 - 1,2 juta</option>
                            <option value="1,3 - 2,5 juta">1,3 - 2,5 juta</option>
                            <option value="2,6 - 4,5 juta">2,6 - 4,5 juta</option>
                            <option value="> 4,5 juta">> 4,5 juta</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: KONDISI FISIK -->
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-300">
                <div class="p-6 border-b dark:border-slate-800 bg-rose-50/30 dark:bg-rose-950/30">
                    <h3 class="font-black text-rose-700 dark:text-rose-400 uppercase tracking-widest text-xs flex items-center">
                        <span class="w-8 h-8 bg-rose-600 dark:bg-rose-700 text-white rounded-xl flex items-center justify-center mr-3 text-[10px] shadow-lg shadow-rose-900/20">2</span>
                        Kondisi Fisik Bangunan
                    </h3>
                </div>
                <div class="p-8 space-y-12">
                    <?php $optKondisi = $master['KONDISI'] ?? []; ?>

                    <div>
                        <h4 class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.3em] mb-6 flex items-center"><span class="w-8 h-1 bg-rose-600 dark:bg-rose-700 mr-3"></span> I. Struktur Utama</h4>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <?php 
                                $struktur = ['st_pondasi' => 'Pondasi', 'st_kolom' => 'Kolom', 'st_balok' => 'Balok', 'st_sloof' => 'Sloof'];
                                foreach($struktur as $key => $label) : 
                            ?>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase mb-2 tracking-widest"><?= $label ?></label>
                                    <select name="<?= $key ?>" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 dark:text-slate-200 outline-none transition-all appearance-none text-sm font-bold">
                                        <option value="">Pilih Kondisi</option>
                                        <?php foreach($optKondisi as $rk) : ?>
                                            <option value="<?= $rk['id'] ?>"><?= $rk['nama_pilihan'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.3em] mb-6 flex items-center"><span class="w-8 h-1 bg-rose-600 dark:bg-rose-700 mr-3"></span> II. Material Penutup</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <?php 
                                $penutup = [
                                    'Atap' => ['mat' => 'mat_atap', 'st' => 'st_atap', 'm_key' => 'MATERIAL_ATAP'],
                                    'Dinding' => ['mat' => 'mat_dinding', 'st' => 'st_dinding', 'm_key' => 'MATERIAL_DINDING'],
                                    'Lantai' => ['mat' => 'mat_lantai', 'st' => 'st_lantai', 'm_key' => 'MATERIAL_LANTAI']
                                ];
                                foreach($penutup as $title => $conf):
                            ?>
                            <div class="p-6 bg-slate-50 dark:bg-slate-950/50 border border-slate-100 dark:border-slate-800 rounded-3xl space-y-5 transition-colors duration-300">
                                <p class="text-xs font-black text-slate-800 dark:text-slate-200 border-b dark:border-slate-800 pb-3 uppercase tracking-wider"><?= $title ?></p>
                                <div>
                                    <label class="block text-[9px] font-black text-slate-400 uppercase mb-2 tracking-widest">Material</label>
                                    <select name="<?= $conf['mat'] ?>" class="w-full p-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-bold dark:text-slate-200 outline-none focus:border-rose-500 transition-all appearance-none">
                                        <option value="">Pilih Material</option>
                                        <?php $materials = $master[$conf['m_key']] ?? []; 
                                              foreach($materials as $rm) : ?>
                                            <option value="<?= $rm['id'] ?>"><?= $rm['nama_pilihan'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[9px] font-black text-slate-400 uppercase mb-2 tracking-widest">Kondisi</label>
                                    <select name="<?= $conf['st'] ?>" class="w-full p-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-bold dark:text-slate-200 outline-none focus:border-rose-500 transition-all appearance-none">
                                        <option value="">Pilih Kondisi</option>
                                        <?php foreach($optKondisi as $rk) : ?>
                                            <option value="<?= $rk['id'] ?>"><?= $rk['nama_pilihan'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: PROFIL RUMAH -->
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-300">
                <div class="p-6 border-b dark:border-slate-800 bg-emerald-50/30 dark:bg-emerald-950/30">
                    <h3 class="font-black text-emerald-700 dark:text-emerald-400 uppercase tracking-widest text-xs flex items-center">
                        <span class="w-8 h-8 bg-emerald-600 dark:bg-emerald-700 text-white rounded-xl flex items-center justify-center mr-3 text-[10px] shadow-lg shadow-emerald-900/20">3</span>
                        Profil Rumah & Lingkungan
                    </h3>
                </div>
                <div class="p-10 grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <label class="block text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest">Desa</label>
                        <input type="text" name="desa" required class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-slate-200 outline-none transition-all">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest">Alamat Detail</label>
                        <input type="text" name="alamat_detail" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-slate-200 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest">Luas Rumah (m2)</label>
                        <input type="number" name="luas_rumah_m2" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-slate-200 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest">Jumlah Penghuni (Jiwa)</label>
                        <input type="number" name="jumlah_penghuni_jiwa" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-slate-200 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest">Kepemilikan Tanah</label>
                        <select name="kepemilikan_tanah" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-slate-200 outline-none transition-all appearance-none font-bold text-sm">
                            <option value="Milik Sendiri">Milik Sendiri</option>
                            <option value="Bukan Milik Sendiri">Bukan Milik Sendiri</option>
                            <option value="Tanah Negara">Tanah Negara</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- BUTTON SIMPAN DI BAWAH -->
            <div class="flex justify-end pb-24">
                <button type="submit" class="bg-blue-950 dark:bg-blue-700 hover:bg-black dark:hover:bg-blue-600 text-white px-12 py-5 rounded-[2rem] font-black shadow-2xl shadow-blue-900/40 transition-all flex items-center space-x-4 text-xl group">
                    <i data-lucide="check-circle" class="w-7 h-7 group-hover:scale-110 transition-transform"></i>
                    <span>Simpan Seluruh Data RTLH</span>
                </button>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
