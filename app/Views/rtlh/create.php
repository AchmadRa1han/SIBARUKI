<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto space-y-8 pb-32">
    
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-3 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 no-print">
        <a href="<?= base_url('dashboard') ?>" class="hover:text-blue-600 transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <a href="<?= base_url('rtlh') ?>" class="hover:text-blue-600 transition-colors">RTLH</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">Input Data Baru</span>
    </nav>

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 bg-white dark:bg-slate-900 p-10 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm transition-all duration-300 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-blue-600/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
        <div class="relative z-10">
            <h1 class="text-3xl md:text-4xl font-black text-blue-950 dark:text-white uppercase tracking-tighter">Input RTLH Terpadu</h1>
            <p class="text-sm text-slate-400 dark:text-slate-500 font-medium mt-1">Lengkapi instrumen survei teknis atau gunakan fitur impor massal.</p>
        </div>
        <div class="flex items-center gap-4 relative z-10">
            <a href="<?= base_url('rtlh') ?>" class="px-8 py-4 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-slate-700 transition-all active:scale-95">Batal</a>
        </div>
    </div>

    <!-- 1. FORM IMPORT (TERPISAH) -->
    <div class="bg-emerald-50 dark:bg-emerald-950/20 rounded-[2.5rem] p-10 border border-emerald-100 dark:border-emerald-900/30 relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-600/5 rounded-full -mr-16 -mt-16 blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 relative z-10">
            <div class="flex items-center gap-6">
                <div class="w-14 h-14 bg-emerald-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-emerald-600/20">
                    <i data-lucide="file-up" class="w-7 h-7"></i>
                </div>
                <div>
                    <h3 class="text-lg font-black text-emerald-900 dark:text-emerald-400 uppercase tracking-tight">Import via CSV</h3>
                    <p class="text-[10px] text-emerald-600/70 dark:text-emerald-500/50 font-bold uppercase tracking-[0.2em]">Unggah file untuk pengisian data massal</p>
                </div>
            </div>
            <form action="<?= base_url('rtlh/import-csv') ?>" method="post" enctype="multipart/form-data" class="flex flex-col md:flex-row items-center gap-4 w-full lg:w-auto">
                <?= csrf_field() ?>
                <div class="relative w-full md:w-auto">
                    <input type="file" name="csv_file" accept=".csv" required class="block w-full text-[10px] text-emerald-900 dark:text-emerald-400 file:mr-6 file:py-3 file:px-8 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-widest file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 cursor-pointer transition-all">
                </div>
                <button type="submit" class="w-full md:w-auto bg-emerald-900 dark:bg-emerald-600 text-white px-10 py-4 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-emerald-900/20 hover:bg-black dark:hover:bg-emerald-700 transition-all active:scale-95 flex items-center justify-center gap-2">
                    <i data-lucide="upload-cloud" class="w-4 h-4"></i> Mulai Impor
                </button>
            </form>
        </div>
    </div>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="bg-rose-50 dark:bg-rose-950/30 border border-rose-100 dark:border-rose-900 text-rose-700 dark:text-rose-400 px-8 py-5 rounded-[2rem] text-sm font-bold shadow-sm flex items-center gap-4 animate-shake">
            <i data-lucide="alert-circle" class="w-6 h-6 shrink-0"></i>
            <p><?= session()->getFlashdata('error') ?></p>
        </div>
    <?php endif; ?>

    <!-- 2. FORM MANUAL -->
    <form action="<?= base_url('rtlh/store') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="space-y-12">
            
            <!-- SECTION 1: IDENTITAS PENERIMA -->
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all duration-300">
                <div class="p-8 border-b dark:border-slate-800 bg-blue-50/30 dark:bg-blue-950/30 flex items-center gap-4">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                        <i data-lucide="user" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-xs font-black text-blue-900 dark:text-blue-400 uppercase tracking-[0.2em]">Identitas Pemilik</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Informasi Personal & Kependudukan</p>
                    </div>
                </div>
                <div class="p-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="lg:col-span-2">
                        <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Nama Lengkap Kepala Keluarga</label>
                        <input type="text" name="nama_kepala_keluarga" required placeholder="Sesuai KTP..." class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold placeholder:opacity-30">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">NIK (16 Digit)</label>
                        <input type="text" name="nik" maxlength="16" required placeholder="0000000000000000" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-mono font-bold placeholder:opacity-30">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Nomor KK</label>
                        <input type="text" name="no_kk" maxlength="16" placeholder="0000000000000000" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-mono font-bold placeholder:opacity-30">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" placeholder="Kota/Kab..." class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold placeholder:opacity-30">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all appearance-none font-bold">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Pendidikan</label>
                        <select name="pendidikan_id" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all appearance-none font-bold">
                            <option value="">Pilih Pendidikan</option>
                            <?php if(isset($master['PENDIDIKAN'])): foreach($master['PENDIDIKAN'] as $p): ?>
                                <option value="<?= $p['id'] ?>"><?= $p['nama_pilihan'] ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Pekerjaan</label>
                        <select name="pekerjaan_id" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all appearance-none font-bold">
                            <option value="">Pilih Pekerjaan</option>
                            <?php if(isset($master['PEKERJAAN'])): foreach($master['PEKERJAAN'] as $p): ?>
                                <option value="<?= $p['id'] ?>"><?= $p['nama_pilihan'] ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Penghasilan / Bulan</label>
                        <input type="text" name="penghasilan_per_bulan" placeholder="Contoh: Rp 1.500.000" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold placeholder:opacity-30">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Jml Anggota Keluarga</label>
                        <input type="number" name="jumlah_anggota_keluarga" min="0" value="0" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold">
                    </div>
                </div>
            </div>

            <!-- SECTION 2: PROFIL RUMAH & LAHAN -->
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all duration-300">
                <div class="p-8 border-b dark:border-slate-800 bg-slate-50/30 dark:bg-slate-950/30 flex items-center gap-4">
                    <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                        <i data-lucide="home" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-xs font-black text-indigo-900 dark:text-indigo-400 uppercase tracking-[0.2em]">Profil Rumah & Lahan</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Informasi Lokasi & Teknis Dasar</p>
                    </div>
                </div>
                <div class="p-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="lg:col-span-2">
                        <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Alamat Lengkap</label>
                        <textarea name="alamat_detail" rows="1" placeholder="Nama Jalan, RT/RW, No. Rumah..." class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 dark:text-slate-200 outline-none transition-all font-bold placeholder:opacity-30 min-h-[58px]"></textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Desa / Kelurahan</label>
                        <select name="desa" id="desa-select" required class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 dark:text-slate-200 outline-none transition-all appearance-none font-bold">
                            <option value="">Pilih Desa</option>
                            <?php if(isset($desa_list)): foreach($desa_list as $d): ?>
                                <option value="<?= $d['desa'] ?>" data-id="<?= $d['desa_id'] ?>"><?= $d['desa'] ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                        <input type="hidden" name="desa_id" id="desa_id_input">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Jenis Kawasan</label>
                        <input type="text" name="jenis_kawasan" placeholder="Kawasan Lindung / Budidaya..." class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 dark:text-slate-200 outline-none transition-all font-bold placeholder:opacity-30">
                    </div>
                    <div class="bg-indigo-50 dark:bg-indigo-950/30 p-6 rounded-[2rem] border border-indigo-100 dark:border-indigo-900/50">
                        <label class="block text-[10px] font-black text-indigo-900 dark:text-indigo-400 uppercase mb-3 tracking-widest ml-1">Luas Rumah (m²)</label>
                        <input type="number" step="0.01" name="luas_rumah_m2" value="0" class="w-full bg-transparent border-none text-2xl font-black text-indigo-950 dark:text-white p-0 focus:ring-0 outline-none">
                    </div>
                    <div class="bg-indigo-50 dark:bg-indigo-950/30 p-6 rounded-[2rem] border border-indigo-100 dark:border-indigo-900/50">
                        <label class="block text-[10px] font-black text-indigo-900 dark:text-indigo-400 uppercase mb-3 tracking-widest ml-1">Luas Lahan (m²)</label>
                        <input type="number" step="0.01" name="luas_lahan_m2" value="0" class="w-full bg-transparent border-none text-2xl font-black text-indigo-950 dark:text-white p-0 focus:ring-0 outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Jml Penghuni</label>
                        <input type="number" name="jumlah_penghuni_jiwa" min="0" value="0" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 dark:text-slate-200 outline-none transition-all font-bold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Kepemilikan Rumah</label>
                        <select name="kepemilikan_rumah" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 dark:text-slate-200 outline-none transition-all appearance-none font-bold">
                            <option value="Milik Sendiri">Milik Sendiri</option>
                            <option value="Sewa / Kontrak">Sewa / Kontrak</option>
                            <option value="Milik Orang Lain">Milik Orang Lain</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Kepemilikan Tanah</label>
                        <select name="kepemilikan_tanah" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 dark:text-slate-200 outline-none transition-all appearance-none font-bold">
                            <option value="Sertifikat (SHM)">Sertifikat (SHM)</option>
                            <option value="Bukan Milik Sendiri">Bukan Milik Sendiri</option>
                            <option value="Tanah Garapan">Tanah Garapan</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Desil Nasional</label>
                        <input type="text" name="desil_nasional" placeholder="Contoh: Desil 1" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 dark:text-slate-200 outline-none transition-all font-bold placeholder:opacity-30">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase mb-3 tracking-widest ml-1">Status Backlog</label>
                        <select name="status_backlog" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 dark:text-slate-200 outline-none transition-all appearance-none font-bold">
                            <option value="Tidak">Tidak</option>
                            <option value="Ya">Ya</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: PENILAIAN TEKNIS & GEOSPASIAL -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- 3.1 Penilaian Teknis -->
                <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all duration-300">
                    <div class="p-8 border-b dark:border-slate-800 bg-slate-50/30 dark:bg-slate-950/30 flex items-center gap-4">
                        <div class="w-10 h-10 bg-blue-950 dark:bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                            <i data-lucide="shield-check" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="text-xs font-black text-blue-950 dark:text-white uppercase tracking-[0.2em]">Penilaian Teknis</h3>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Kualitas Fisik Bangunan</p>
                        </div>
                    </div>
                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php 
                            $struk = ['Pondasi', 'Kolom', 'Balok', 'Sloof', 'Rangka_Atap', 'Plafon', 'Jendela', 'Ventilasi'];
                            foreach($struk as $s):
                                $field = 'st_' . strtolower($s);
                        ?>
                        <div>
                            <label class="block text-[9px] font-black text-slate-400 uppercase mb-2 tracking-widest ml-1"><?= str_replace('_', ' ', $s) ?></label>
                            <select name="<?= $field ?>" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all appearance-none text-[11px] font-bold">
                                <?php if(isset($master['KONDISI'])): foreach($master['KONDISI'] as $k): ?>
                                    <option value="<?= $k['id'] ?>"><?= $k['nama_pilihan'] ?></option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- 3.2 Geospasial Picker -->
                <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all duration-300">
                    <div class="p-8 border-b dark:border-slate-800 bg-slate-50/30 dark:bg-slate-950/30 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-rose-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                                <i data-lucide="map" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h3 class="text-xs font-black text-rose-950 dark:text-rose-400 uppercase tracking-[0.2em]">Titik Koordinat</h3>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Klik peta untuk menentukan lokasi</p>
                            </div>
                        </div>
                        <button type="button" onclick="getLocation()" class="p-3 bg-rose-50 dark:bg-rose-900/30 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition-all shadow-sm" title="Gunakan GPS Saya">
                            <i data-lucide="crosshair" class="w-5 h-5"></i>
                        </button>
                    </div>
                    <div id="map-picker" class="w-full h-[350px] z-10" style="background: #ececec;"></div>
                    <div class="p-6">
                        <input type="text" name="lokasi_koordinat" id="lokasi_koordinat" required placeholder="POINT(Longitude Latitude)" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-[11px] font-mono font-black text-rose-600 dark:text-rose-400 placeholder:opacity-30 outline-none">
                    </div>
                </div>
            </div>

            <!-- SECTION 4: FASILITAS & DOKUMENTASI -->
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all duration-300">
                <div class="p-8 border-b dark:border-slate-800 bg-slate-50/30 dark:bg-blue-950/30 flex items-center gap-4">
                    <div class="w-10 h-10 bg-blue-950 rounded-xl flex items-center justify-center text-white shadow-lg">
                        <i data-lucide="camera" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-xs font-black text-blue-950 dark:text-white uppercase tracking-[0.2em]">Fasilitas & Dokumentasi</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Utilitas Sanitasi & Foto Survei</p>
                    </div>
                </div>
                <div class="p-10 space-y-10">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-3 tracking-widest ml-1">Sumber Air Minum</label>
                            <input type="text" name="sumber_air_minum" placeholder="Contoh: Sumur Gali, PDAM..." class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl font-bold outline-none focus:ring-4 focus:ring-blue-500/10">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-3 tracking-widest ml-1">Sumber Penerangan</label>
                            <input type="text" name="sumber_penerangan" placeholder="Contoh: PLN 450W, Non PLN..." class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl font-bold outline-none focus:ring-4 focus:ring-blue-500/10">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-3 tracking-widest ml-1">Jamban & Kloset</label>
                            <input type="text" name="jenis_jamban_kloset" placeholder="Contoh: Leher Angsa, Plengsengan..." class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl font-bold outline-none focus:ring-4 focus:ring-blue-500/10">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <?php 
                            $fotos = [
                                'foto_depan' => 'Tampak Depan',
                                'foto_samping' => 'Tampak Samping',
                                'foto_belakang' => 'Tampak Belakang',
                                'foto_dalam' => 'Bagian Dalam'
                            ];
                            foreach($fotos as $f_key => $f_label):
                        ?>
                        <div class="space-y-3">
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1"><?= $f_label ?></label>
                            <div class="relative group">
                                <input type="file" name="<?= $f_key ?>" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 z-10 cursor-pointer" onchange="previewImage(this, '<?= $f_key ?>_preview')">
                                <div id="<?= $f_key ?>_preview" class="w-full h-40 bg-slate-50 dark:bg-slate-950 border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-3xl flex flex-col items-center justify-center overflow-hidden transition-all group-hover:border-blue-600 group-hover:bg-blue-50/10">
                                    <i data-lucide="image-plus" class="w-8 h-8 text-slate-300 mb-2"></i>
                                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Pilih Foto</span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- ACTION BAR -->
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 pt-8">
                <div class="flex items-center gap-4 text-slate-400">
                    <i data-lucide="info" class="w-5 h-5"></i>
                    <p class="text-[10px] font-bold uppercase tracking-widest leading-relaxed">Pastikan seluruh data survei telah tervalidasi sebelum melakukan konfirmasi penyimpanan.</p>
                </div>
                <button type="submit" class="group flex items-center space-x-8 bg-blue-600 hover:bg-blue-700 text-white pl-12 pr-6 py-6 rounded-[2.5rem] font-black shadow-2xl shadow-blue-600/20 transition-all active:scale-95 w-full md:w-auto">
                    <div class="flex flex-col text-right">
                        <span class="text-[10px] uppercase tracking-[0.3em] opacity-60 mb-1">Konfirmasi Final</span>
                        <span class="text-xl uppercase tracking-tighter">Simpan Laporan</span>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center group-hover:translate-x-2 transition-transform">
                        <i data-lucide="arrow-right" class="w-6 h-6"></i>
                    </div>
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Leaflet & Script -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    let map, marker;

    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
        setTimeout(initMapPicker, 500);

        // Update desa_id when desa is selected
        const desaSelect = document.getElementById('desa-select');
        const desaIdInput = document.getElementById('desa_id_input');
        if (desaSelect && desaIdInput) {
            desaSelect.addEventListener('change', () => {
                const selected = desaSelect.options[desaSelect.selectedIndex];
                desaIdInput.value = selected.getAttribute('data-id') || '';
            });
        }
    });

    function initMapPicker() {
        if (typeof L === 'undefined') { setTimeout(initMapPicker, 100); return; }
        const isDark = document.documentElement.classList.contains('dark');
        const standard = L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { attribution: '&copy; Sibaruki' });
        const satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: '&copy; Esri' });

        map = L.map('map-picker', { 
            zoomControl: false, 
            layers: [satellite] 
        }).setView([-5.1245, 120.2536], 15);

        L.control.zoom({ position: 'topright' }).addTo(map);

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
            navigator.geolocation.getCurrentPosition((position) => {
                updateMarker(position.coords.latitude, position.coords.longitude);
                map.setZoom(18);
            }, (err) => {
                alert('Gagal mengakses lokasi. Pastikan GPS aktif.');
            });
        }
    }

    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                preview.classList.remove('border-dashed');
                preview.classList.add('border-solid', 'border-blue-500');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<?= $this->endSection() ?>
