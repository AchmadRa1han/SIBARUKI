<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-6xl mx-auto">
    <form action="<?= base_url('rtlh/store') ?>" method="post">
        <?= csrf_field() ?>

        <div class="space-y-8">
            <!-- HEADER -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Input Data RTLH Terpadu</h1>
                    <p class="text-sm text-gray-500">Isi seluruh informasi pemilik, rumah, dan kondisi fisik secara sekaligus.</p>
                </div>
                <div class="flex space-x-3">
                    <a href="<?= base_url('rtlh') ?>" class="text-sm font-bold text-gray-400 hover:text-gray-600 px-4 py-2">Batal</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-blue-200 transition-all flex items-center space-x-2">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        <span>Simpan Semua Data</span>
                    </button>
                </div>
            </div>

            <?php if (session()->getFlashdata('message')) : ?>
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm italic">
                    <?= session()->getFlashdata('message') ?>
                </div>
            <?php endif; ?>

            <!-- SECTION 1: DATA PENERIMA -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b bg-blue-50/30">
                    <h3 class="font-bold text-blue-700 flex items-center">
                        <span class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center mr-3 text-xs">1</span>
                        Data Penerima (Pemilik)
                    </h3>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">NIK (16 Digit)</label>
                        <input type="text" name="nik" maxlength="16" required class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">No. KK</label>
                        <input type="text" name="no_kk" maxlength="16" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Kepala Keluarga</label>
                        <input type="text" name="nama_kepala_keluarga" required class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 outline-none">
                    </div>
                    
                    <!-- Dropdown Pendidikan -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Pendidikan</label>
                        <select name="pendidikan_id" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none">
                            <option value="">Pilih Pendidikan</option>
                            <?php if(isset($master['PENDIDIKAN'])): foreach($master['PENDIDIKAN'] as $p): ?>
                                <option value="<?= $p['id'] ?>"><?= $p['nama_pilihan'] ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>

                    <!-- Dropdown Pekerjaan -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Pekerjaan</label>
                        <select name="pekerjaan_id" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none">
                            <option value="">Pilih Pekerjaan</option>
                            <?php if(isset($master['PEKERJAAN'])): foreach($master['PEKERJAAN'] as $p): ?>
                                <option value="<?= $p['id'] ?>"><?= $p['nama_pilihan'] ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Penghasilan / Bulan</label>
                        <input type="text" name="penghasilan_per_bulan" placeholder="Rp" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 outline-none">
                    </div>
                </div>
            </div>

            <!-- SECTION 3: KONDISI FISIK -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b bg-rose-50/30">
                    <h3 class="font-bold text-rose-700 flex items-center">
                        <span class="w-8 h-8 bg-rose-600 text-white rounded-full flex items-center justify-center mr-3 text-xs">2</span>
                        Kondisi Fisik Bangunan
                    </h3>
                </div>
                <div class="p-8 space-y-8">
                    <!-- Helper untuk mengambil data kondisi -->
                    <?php $optKondisi = $master['KONDISI'] ?? []; ?>

                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">I. Struktur Utama</h4>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <?php 
                                $struktur = ['st_pondasi' => 'Pondasi', 'st_kolom' => 'Kolom', 'st_balok' => 'Balok', 'st_sloof' => 'Sloof'];
                                foreach($struktur as $key => $label) : 
                            ?>
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 mb-2"><?= $label ?></label>
                                    <select name="<?= $key ?>" class="w-full p-2 bg-gray-50 border border-gray-200 rounded-lg outline-none text-sm">
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
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">II. Material Penutup</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <!-- Atap -->
                            <div class="p-4 bg-gray-50 rounded-xl space-y-4">
                                <p class="text-sm font-bold text-gray-700 border-b pb-2">Atap</p>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Material</label>
                                    <select name="mat_atap" class="w-full p-2 border border-gray-200 rounded-lg text-sm">
                                        <option value="">Pilih Material</option>
                                        <?php $m_atap = $master['MATERIAL_ATAP'] ?? []; 
                                              foreach($m_atap as $rm) : ?>
                                            <option value="<?= $rm['id'] ?>"><?= $rm['nama_pilihan'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Kondisi Atap</label>
                                    <select name="st_atap" class="w-full p-2 border border-gray-200 rounded-lg text-sm">
                                        <option value="">Pilih Kondisi</option>
                                        <?php foreach($optKondisi as $rk) : ?>
                                            <option value="<?= $rk['id'] ?>"><?= $rk['nama_pilihan'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Dinding -->
                            <div class="p-4 bg-gray-50 rounded-xl space-y-4">
                                <p class="text-sm font-bold text-gray-700 border-b pb-2">Dinding</p>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Material</label>
                                    <select name="mat_dinding" class="w-full p-2 border border-gray-200 rounded-lg text-sm">
                                        <option value="">Pilih Material</option>
                                        <?php $m_dinding = $master['MATERIAL_DINDING'] ?? []; 
                                              foreach($m_dinding as $rm) : ?>
                                            <option value="<?= $rm['id'] ?>"><?= $rm['nama_pilihan'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Kondisi Dinding</label>
                                    <select name="st_dinding" class="w-full p-2 border border-gray-200 rounded-lg text-sm">
                                        <option value="">Pilih Kondisi</option>
                                        <?php foreach($optKondisi as $rk) : ?>
                                            <option value="<?= $rk['id'] ?>"><?= $rk['nama_pilihan'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Lantai -->
                            <div class="p-4 bg-gray-50 rounded-xl space-y-4">
                                <p class="text-sm font-bold text-gray-700 border-b pb-2">Lantai</p>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Material</label>
                                    <select name="mat_lantai" class="w-full p-2 border border-gray-200 rounded-lg text-sm">
                                        <option value="">Pilih Material</option>
                                        <?php $m_lantai = $master['MATERIAL_LANTAI'] ?? []; 
                                              foreach($m_lantai as $rm) : ?>
                                            <option value="<?= $rm['id'] ?>"><?= $rm['nama_pilihan'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Kondisi Lantai</label>
                                    <select name="st_lantai" class="w-full p-2 border border-gray-200 rounded-lg text-sm">
                                        <option value="">Pilih Kondisi</option>
                                        <?php foreach($optKondisi as $rk) : ?>
                                            <option value="<?= $rk['id'] ?>"><?= $rk['nama_pilihan'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: PROFIL RUMAH -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b bg-emerald-50/30">
                    <h3 class="font-bold text-emerald-700 flex items-center">
                        <span class="w-8 h-8 bg-emerald-600 text-white rounded-full flex items-center justify-center mr-3 text-xs">3</span>
                        Profil Rumah & Lingkungan
                    </h3>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Desa</label>
                        <input type="text" name="desa" required class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 outline-none">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Alamat Detail</label>
                        <input type="text" name="alamat_detail" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Luas Rumah (m2)</label>
                        <input type="number" name="luas_rumah_m2" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Jumlah Penghuni (Jiwa)</label>
                        <input type="number" name="jumlah_penghuni_jiwa" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Kepemilikan Tanah</label>
                        <select name="kepemilikan_tanah" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none">
                            <option value="Milik Sendiri">Milik Sendiri</option>
                            <option value="Bukan Milik Sendiri">Bukan Milik Sendiri</option>
                            <option value="Tanah Negara">Tanah Negara</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- BUTTON SIMPAN DI BAWAH -->
            <div class="flex justify-end pb-12">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-12 py-4 rounded-2xl font-bold shadow-xl shadow-blue-200 transition-all flex items-center space-x-3 text-lg">
                    <i data-lucide="check-circle" class="w-6 h-6"></i>
                    <span>Simpan Seluruh Data RTLH</span>
                </button>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
