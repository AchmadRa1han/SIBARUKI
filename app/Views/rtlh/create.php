<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-6xl mx-auto space-y-8">
    
    <!-- HEADER -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border dark:border-slate-800 shadow-sm transition-all duration-300">
        <div>
            <h1 class="text-3xl font-black text-blue-950 dark:text-white uppercase tracking-tight">Input RTLH Terpadu</h1>
            <p class="text-sm text-slate-400 dark:text-slate-500 font-medium mt-1">Lengkapi seluruh instrumen survei teknis atau impor data massal.</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="<?= base_url('rtlh') ?>" class="px-6 py-3 text-sm font-bold text-slate-400 hover:text-slate-600 transition-colors uppercase tracking-widest">Batal</a>
        </div>
    </div>

    <!-- 1. FORM IMPORT (TERPISAH) -->
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
            <form action="<?= base_url('rtlh/import-csv') ?>" method="post" enctype="multipart/form-data" class="flex items-center gap-2">
                <?= csrf_field() ?>
                <input type="file" name="csv_file" accept=".csv" required class="block w-full text-[10px] text-emerald-900 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 cursor-pointer">
                <button type="submit" class="bg-emerald-900 text-white px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm hover:bg-black transition-all">Upload</button>
            </form>
        </div>
    </div>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="bg-rose-50 dark:bg-rose-950/30 border border-rose-100 dark:border-rose-900 text-rose-700 dark:text-rose-400 px-6 py-4 rounded-2xl text-sm font-bold shadow-sm transition-colors duration-300">
            <i data-lucide="alert-circle" class="w-4 h-4 inline mr-2"></i>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- 2. FORM MANUAL -->
    <form action="<?= base_url('rtlh/store') ?>" method="post">
        <?= csrf_field() ?>
        <div class="space-y-8">
            <!-- SECTION 1: IDENTITAS PENERIMA -->
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-300">
                <div class="p-6 border-b dark:border-slate-800 bg-blue-50/30 dark:bg-blue-950/30">
                    <h3 class="font-black text-blue-900 dark:text-blue-400 uppercase tracking-widest text-xs flex items-center">
                        <span class="w-8 h-8 bg-blue-900 dark:bg-blue-700 text-white rounded-xl flex items-center justify-center mr-3 text-[10px] shadow-lg shadow-blue-900/20">I</span>
                        Identitas Pemilik (Penerima)
                    </h3>
                </div>
                <div class="p-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="lg:col-span-2">
                        <label class="block text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest">Nama Lengkap Kepala Keluarga</label>
                        <input type="text" name="nama_kepala_keluarga" required class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest">NIK (16 Digit)</label>
                        <input type="text" name="nik" maxlength="16" required class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-mono">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest">Nomor KK</label>
                        <input type="text" name="no_kk" maxlength="16" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-mono">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all appearance-none">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
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
                    <div>
                        <label class="block text-[10px] font-black text-blue-900 dark:text-blue-500 uppercase mb-2 tracking-widest">Jumlah Anggota Keluarga</label>
                        <input type="number" name="jumlah_anggota_keluarga" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all">
                    </div>
                </div>
            </div>

            <!-- SECTION 2: PROFIL RUMAH & LAHAN -->
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-300">
                <div class="p-6 border-b dark:border-slate-800 bg-emerald-50/30 dark:bg-emerald-950/30">
                    <h3 class="font-black text-emerald-700 dark:text-emerald-400 uppercase tracking-widest text-xs flex items-center">
                        <span class="w-8 h-8 bg-emerald-600 dark:bg-emerald-700 text-white rounded-xl flex items-center justify-center mr-3 text-[10px] shadow-lg shadow-emerald-900/20">II</span>
                        Profil Lokasi, Rumah & Lahan
                    </h3>
                </div>
                <div class="p-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="lg:col-span-2">
                        <label class="block text-[10px] font-black text-emerald-900 dark:text-emerald-500 uppercase mb-2 tracking-widest">Pilih Desa / Kelurahan</label>
                        <select name="desa_id" required onchange="document.getElementById('desa_name').value = this.options[this.selectedIndex].text" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-slate-200 outline-none transition-all appearance-none font-bold">
                            <option value="">Pilih Wilayah</option>
                            <?php foreach($all_desa as $d): ?>
                                <option value="<?= $d['desa_id'] ?>"><?= $d['desa_nama'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" name="desa" id="desa_name">
                    </div>
                    <div class="lg:col-span-2">
                        <label class="block text-[10px] font-black text-emerald-900 dark:text-emerald-500 uppercase mb-2 tracking-widest">Alamat Detail / Dusun / RT-RW</label>
                        <input type="text" name="alamat_detail" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-slate-200 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-emerald-900 dark:text-emerald-500 uppercase mb-2 tracking-widest">Luas Rumah (m²)</label>
                        <input type="number" step="0.01" name="luas_rumah_m2" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-slate-200 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-emerald-900 dark:text-emerald-500 uppercase mb-2 tracking-widest">Luas Lahan (m²)</label>
                        <input type="number" step="0.01" name="luas_lahan_m2" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-slate-200 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-emerald-900 dark:text-emerald-500 uppercase mb-2 tracking-widest">Jumlah Penghuni (Jiwa)</label>
                        <input type="number" name="jumlah_penghuni_jiwa" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-slate-200 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-emerald-900 dark:text-emerald-500 uppercase mb-2 tracking-widest">Fungsi Ruang</label>
                        <select name="fungsi_ruang" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-slate-200 outline-none transition-all appearance-none">
                            <option value="Hanya Tempat Tinggal">Hanya Tempat Tinggal</option>
                            <option value="Tempat Tinggal & Usaha">Tempat Tinggal & Usaha</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-emerald-900 dark:text-emerald-500 uppercase mb-2 tracking-widest">Jenis Kawasan</label>
                        <select name="jenis_kawasan" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-slate-200 outline-none transition-all appearance-none uppercase text-[10px] font-bold">
                            <option value="Perkotaan">Perkotaan</option>
                            <option value="Perdesaan">Perdesaan</option>
                            <option value="Kawasan Kumuh">Kawasan Kumuh</option>
                            <option value="Pesisir / Nelayan">Pesisir / Nelayan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-emerald-900 dark:text-emerald-500 uppercase mb-2 tracking-widest">Kepemilikan Rumah</label>
                        <select name="kepemilikan_rumah" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-slate-200 outline-none transition-all appearance-none">
                            <option value="Milik Sendiri">Milik Sendiri</option>
                            <option value="Sewa / Kontrak">Sewa / Kontrak</option>
                            <option value="Milik Keluarga / Orang Tua">Milik Keluarga / Orang Tua</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-emerald-900 dark:text-emerald-500 uppercase mb-2 tracking-widest">Kepemilikan Tanah</label>
                        <select name="kepemilikan_tanah" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-slate-200 outline-none transition-all appearance-none">
                            <option value="Milik Sendiri">Milik Sendiri</option>
                            <option value="Bukan Milik Sendiri">Bukan Milik Sendiri</option>
                            <option value="Tanah Negara">Tanah Negara</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-emerald-900 dark:text-emerald-500 uppercase mb-2 tracking-widest">Aset Rumah Lain?</label>
                        <select name="aset_rumah_di_lokasi_lain" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-slate-200 outline-none transition-all appearance-none">
                            <option value="Ada">Ada</option>
                            <option value="Tidak Ada">Tidak Ada</option>
                        </select>
                    </div>
                    <div class="lg:col-span-2">
                        <label class="block text-[10px] font-black text-emerald-900 dark:text-emerald-500 uppercase mb-2 tracking-widest">Pernah Mendapat Bantuan Perumahan?</label>
                        <input type="text" name="bantuan_perumahan" placeholder="Contoh: BSPS 2022 / Belum Pernah" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-slate-200 outline-none transition-all italic">
                    </div>
                </div>
            </div>

            <!-- SECTION 3: FASILITAS & SANITASI -->
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-300">
                <div class="p-6 border-b dark:border-slate-800 bg-amber-50/30 dark:bg-amber-950/30">
                    <h3 class="font-black text-amber-700 dark:text-amber-400 uppercase tracking-widest text-xs flex items-center">
                        <span class="w-8 h-8 bg-amber-600 dark:bg-amber-700 text-white rounded-xl flex items-center justify-center mr-3 text-[10px] shadow-lg shadow-amber-900/20">III</span>
                        Utilitas, Sanitasi & Koordinat
                    </h3>
                </div>
                <div class="p-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div>
                        <label class="block text-[10px] font-black text-amber-900 dark:text-amber-500 uppercase mb-2 tracking-widest">Sumber Penerangan</label>
                        <select name="sumber_penerangan" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 dark:text-slate-200 outline-none transition-all appearance-none font-bold">
                            <option value="Listrik PLN">Listrik PLN</option>
                            <option value="Listrik Non PLN">Listrik Non PLN</option>
                            <option value="Bukan Listrik">Bukan Listrik</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-amber-900 dark:text-amber-500 uppercase mb-2 tracking-widest">Detail Daya / Sumber</label>
                        <input type="text" name="sumber_penerangan_detail" placeholder="Contoh: 450 Watt / 900 Watt" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 dark:text-slate-200 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-amber-900 dark:text-amber-500 uppercase mb-2 tracking-widest">Sumber Air Minum (SAM)</label>
                        <select name="sumber_air_minum" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 dark:text-slate-200 outline-none transition-all appearance-none">
                            <option value="Leding / PAM">Leding / PAM</option>
                            <option value="Sumur Terlindungi">Sumur Terlindungi</option>
                            <option value="Sumur Tidak Terlindungi">Sumur Tidak Terlindungi</option>
                            <option value="Mata Air / Air Hujan">Mata Air / Air Hujan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-amber-900 dark:text-amber-500 uppercase mb-2 tracking-widest">Jarak SAM ke TPA Tinja</label>
                        <select name="jarak_sam_ke_tpa_tinja" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 dark:text-slate-200 outline-none transition-all appearance-none font-bold">
                            <option value="< 10 Meter">< 10 Meter</option>
                            <option value="> 10 Meter">> 10 Meter</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-amber-900 dark:text-amber-500 uppercase mb-2 tracking-widest">Kamar Mandi & Jamban</label>
                        <select name="kamar_mandi_dan_jamban" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 dark:text-slate-200 outline-none transition-all appearance-none">
                            <option value="Sendiri">Sendiri</option>
                            <option value="Bersama / Umum">Bersama / Umum</option>
                            <option value="Tidak Ada">Tidak Ada</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-amber-900 dark:text-amber-500 uppercase mb-2 tracking-widest">Jenis Jamban / Kloset</label>
                        <select name="jenis_jamban_kloset" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 dark:text-slate-200 outline-none transition-all appearance-none">
                            <option value="Leher Angsa">Leher Angsa</option>
                            <option value="Plengsengan">Plengsengan</option>
                            <option value="Cemplung">Cemplung</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-amber-900 dark:text-amber-500 uppercase mb-2 tracking-widest">Jenis TPA Tinja</label>
                        <select name="jenis_tpa_tinja" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 dark:text-slate-200 outline-none transition-all appearance-none">
                            <option value="Tangki Septik">Tangki Septik</option>
                            <option value="Lubang Tanah">Lubang Tanah</option>
                            <option value="Kolam / Sawah / Sungai">Kolam / Sawah / Sungai</option>
                            <option value="Tanpa TPA">Tanpa TPA</option>
                        </select>
                    </div>
                    <div class="lg:col-span-4 space-y-4">
                        <label class="block text-[10px] font-black text-amber-900 dark:text-amber-500 uppercase mb-2 tracking-widest flex items-center gap-2">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5"></i> Pilih Lokasi pada Peta (Click-to-Pin)
                        </label>
                        <div class="relative group">
                            <div class="absolute -inset-1 bg-gradient-to-r from-amber-600 to-orange-600 rounded-[2.5rem] blur opacity-10 transition duration-1000"></div>
                            <div class="relative bg-white dark:bg-slate-900 rounded-[2.5rem] overflow-hidden shadow-2xl border border-slate-100 dark:border-slate-800">
                                <div id="map-picker" class="w-full h-80 z-10" style="min-height: 320px; background: #ececec;"></div>
                                
                                <!-- GPS Button -->
                                <button type="button" onclick="getLocation()" class="absolute top-4 right-4 z-[1000] p-3 bg-white dark:bg-slate-800 text-blue-600 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-700 hover:bg-blue-600 hover:text-white transition-all active:scale-90 flex items-center gap-2 font-black text-[9px] uppercase tracking-widest">
                                    <i data-lucide="crosshair" class="w-4 h-4"></i> Lokasi Saya
                                </button>

                                <!-- Helper Overlay -->
                                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 z-[1000] pointer-events-none">
                                    <div class="bg-blue-950/80 backdrop-blur-md text-white px-4 py-2 rounded-xl text-[8px] font-black uppercase tracking-widest shadow-2xl border border-white/10 flex items-center gap-2">
                                        <i data-lucide="mouse-pointer-2" class="w-3 h-3"></i> Klik pada peta untuk menentukan titik rumah
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-amber-900 dark:text-amber-500 uppercase mb-2 tracking-widest">Koordinat Lokasi (Point)</label>
                        <input type="text" name="lokasi_koordinat" id="lokasi_koordinat" placeholder="POINT(120.25 -5.12)" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 dark:text-slate-200 outline-none transition-all font-mono text-xs italic" readonly>
                    </div>
                </div>
            </div>

            <!-- SECTION 4: PENILAIAN TEKNIS -->
...
</div>

<!-- Leaflet & Script -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    let map, marker;
    let rot = 0;

    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(initMapPicker, 500);
    });

    function initMapPicker() {
        const isDark = document.documentElement.classList.contains('dark');
        const standard = L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { attribution: '&copy; Sibaruki' });
        const satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: '&copy; Esri' });

        map = L.map('map-picker', { 
            zoomControl: false, 
            layers: [satellite] 
        }).setView([-5.1245, 120.2536], 15);

        L.control.zoom({ position: 'topright' }).addTo(map);

        // Standardized Layer Toggle
        const LayerToggle = L.Control.extend({
            onAdd: function(map) {
                const btn = L.DomUtil.create('button', 'bg-white dark:bg-slate-900 rounded-xl shadow-xl border border-slate-100 dark:border-slate-800 transition-all duration-300 active:scale-90 mt-2 flex items-center justify-center');
                btn.type = 'button'; btn.style.width = '44px'; btn.style.height = '44px'; btn.style.cursor = 'pointer';
                btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="${isDark?'#60a5fa':'#2563eb'}" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>`;
                
                L.DomEvent.disableClickPropagation(btn);
                L.DomEvent.on(btn, 'click', function(e) {
                    if (map.hasLayer(standard)) {
                        map.removeLayer(standard); map.addLayer(satellite);
                        btn.style.backgroundColor = '#2563eb';
                    } else {
                        map.removeLayer(satellite); map.addLayer(standard);
                        btn.style.backgroundColor = isDark ? '#0f172a' : '#ffffff';
                    }
                });
                return btn;
            }
        });
        new LayerToggle({ position: 'topright' }).addTo(map);

        map.on('click', function(e) {
            updateMarker(e.latlng.lat, e.latlng.lng);
        });
    }

    function updateMarker(lat, lng) {
        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng], { draggable: true }).addTo(map);
            marker.on('dragend', function(e) {
                const pos = e.target.getLatLng();
                updateInput(pos.lat, pos.lng);
            });
        }
        updateInput(lat, lng);
        map.panTo([lat, lng]);
    }

    function updateInput(lat, lng) {
        document.getElementById('lokasi_koordinat').value = `POINT(${lng.toFixed(8)} ${lat.toFixed(8)})`;
    }

    function getLocation() {
        if (navigator.geolocation) {
            showToast('Mengakses GPS...', 'success');
            navigator.geolocation.getCurrentPosition((position) => {
                updateMarker(position.coords.latitude, position.coords.longitude);
                map.setZoom(18);
            }, (err) => {
                showToast('Gagal mengakses lokasi. Pastikan GPS aktif.', 'error');
            });
        } else {
            showToast('Browser tidak mendukung Geolocation.', 'error');
        }
    }
</script>
<?= $this->endSection() ?>
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-300">
                <div class="p-6 border-b dark:border-slate-800 bg-rose-50/30 dark:bg-rose-950/30">
                    <h3 class="font-black text-rose-700 dark:text-rose-400 uppercase tracking-widest text-xs flex items-center">
                        <span class="w-8 h-8 bg-rose-600 dark:bg-rose-700 text-white rounded-xl flex items-center justify-center mr-3 text-[10px] shadow-lg shadow-rose-900/20">IV</span>
                        Penilaian Teknis Fisik Bangunan
                    </h3>
                </div>
                <div class="p-8 space-y-12">
                    <?php $optKondisi = $master['KONDISI'] ?? []; ?>

                    <!-- STRUKTUR UTAMA -->
                    <div>
                        <h4 class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.3em] mb-6 flex items-center"><span class="w-8 h-1 bg-rose-600 dark:bg-rose-700 mr-3"></span> I. Struktur Utama Bangunan</h4>
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

                    <!-- MATERIAL PENUTUP -->
                    <div>
                        <h4 class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.3em] mb-6 flex items-center"><span class="w-8 h-1 bg-rose-600 dark:bg-rose-700 mr-3"></span> II. Material Penutup (Atap, Dinding, Lantai)</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <?php 
                                $penutup = [
                                    'Penutup Atap' => ['mat' => 'mat_atap', 'st' => 'st_atap', 'm_key' => 'MATERIAL_ATAP'],
                                    'Dinding Utama' => ['mat' => 'mat_dinding', 'st' => 'st_dinding', 'm_key' => 'MATERIAL_DINDING'],
                                    'Lantai Utama' => ['mat' => 'mat_lantai', 'st' => 'st_lantai', 'm_key' => 'MATERIAL_LANTAI']
                                ];
                                foreach($penutup as $title => $conf):
                            ?>
                            <div class="p-6 bg-slate-50 dark:bg-slate-950/50 border border-slate-100 dark:border-slate-800 rounded-3xl space-y-5">
                                <p class="text-[10px] font-black text-slate-800 dark:text-white pb-3 uppercase tracking-wider border-b dark:border-slate-800"><?= $title ?></p>
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

                    <!-- KOMPONEN PENDUKUNG -->
                    <div>
                        <h4 class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.3em] mb-6 flex items-center"><span class="w-8 h-1 bg-rose-600 dark:bg-rose-700 mr-3"></span> III. Komponen Pendukung & Sirkulasi</h4>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <?php 
                                $pendukung = ['st_rangka_atap' => 'Rangka Atap', 'st_plafon' => 'Plafon', 'st_jendela' => 'Daun Jendela', 'st_ventilasi' => 'Ventilasi'];
                                foreach($pendukung as $key => $label) : 
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
                </div>
            </div>

            <!-- BUTTON SIMPAN -->
            <div class="flex justify-end pb-32">
                <button type="submit" class="group flex items-center space-x-6 bg-blue-950 dark:bg-blue-700 hover:bg-black text-white px-16 py-6 rounded-[2.5rem] font-black shadow-2xl transition-all active:scale-95">
                    <div class="flex flex-col text-right">
                        <span class="text-[10px] uppercase tracking-[0.3em] opacity-60">Konfirmasi Final</span>
                        <span class="text-xl uppercase tracking-tighter">Simpan Semua Data</span>
                    </div>
                    <i data-lucide="arrow-right-circle" class="w-10 h-10 group-hover:translate-x-2 transition-transform"></i>
                </button>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
