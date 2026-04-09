<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto space-y-6 pb-32 text-slate-900 dark:text-slate-200">
    
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-3 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 no-print">
        <a href="<?= base_url('dashboard') ?>" class="hover:text-blue-600 transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <a href="<?= base_url('rtlh') ?>" class="hover:text-blue-600 transition-colors">RTLH</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">Perbarui Data</span>
    </nav>

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm transition-all duration-300 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-48 h-48 bg-amber-600/5 rounded-full -mr-24 -mt-24 blur-3xl"></div>
        <div class="relative z-10 flex items-center gap-4">
            <a href="<?= base_url('rtlh') ?>" class="p-3 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl hover:bg-blue-600 hover:text-white transition-all active:scale-95" title="Kembali">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 bg-amber-500 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-amber-500/20">
                    <i data-lucide="edit-3" class="w-7 h-7"></i>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-blue-950 dark:text-white uppercase tracking-tighter">Perbarui Data RTLH</h1>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">ID Registrasi:</span>
                        <span class="px-2.5 py-0.5 bg-blue-950 dark:bg-blue-800 text-white rounded-lg font-mono text-[10px] font-bold shadow-lg">SRV-<?= str_pad($rumah['id_survei'], 5, '0', STR_PAD_LEFT) ?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3 relative z-10">
            <a href="<?= base_url('rtlh/detail/' . $rumah['id_survei']) ?>" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-slate-700 transition-all active:scale-95">Batal</a>
        </div>
    </div>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="bg-rose-50 dark:bg-rose-950/30 border border-rose-100 dark:border-rose-900 text-rose-700 dark:text-rose-400 px-6 py-4 rounded-2xl text-xs font-bold shadow-sm flex items-center gap-3 animate-shake">
            <i data-lucide="alert-circle" class="w-5 h-5 shrink-0"></i>
            <p><?= session()->getFlashdata('error') ?></p>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('rtlh/update/' . $rumah['id_survei']) ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="space-y-10">
            
            <!-- SECTION 1: IDENTITAS PENERIMA -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all duration-300">
                <div class="p-6 border-b dark:border-slate-800 bg-blue-50/30 dark:bg-blue-950/30 flex items-center gap-3">
                    <div class="w-9 h-9 bg-blue-600 rounded-lg flex items-center justify-center text-white shadow-lg">
                        <i data-lucide="user" class="w-4.5 h-4.5"></i>
                    </div>
                    <div>
                        <h3 class="text-[11px] font-bold text-blue-900 dark:text-blue-400 uppercase tracking-[0.2em]">Identitas Pemilik</h3>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Informasi Personal & Kependudukan</p>
                    </div>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="lg:col-span-2">
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Nama Kepala Keluarga</label>
                        <input type="text" name="nama_kepala_keluarga" required value="<?= old('nama_kepala_keluarga', $penerima['nama_kepala_keluarga'] ?? '') ?>" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">NIK (16 Digit)</label>
                        <input type="text" name="nik" value="<?= $rumah['nik_pemilik'] ?>" readonly class="w-full p-3.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono font-bold text-slate-400 outline-none cursor-not-allowed text-sm">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Nomor KK</label>
                        <input type="text" name="no_kk" maxlength="16" value="<?= old('no_kk', $penerima['no_kk'] ?? '') ?>" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-mono font-bold text-sm">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="<?= old('tempat_lahir', $penerima['tempat_lahir'] ?? '') ?>" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="<?= old('tanggal_lahir', $penerima['tanggal_lahir'] ?? '') ?>" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all appearance-none font-bold">
                            <option value="L" <?= (old('jenis_kelamin', $penerima['jenis_kelamin'] ?? '') == 'L') ? 'selected' : '' ?>>Laki-laki</option>
                            <option value="P" <?= (old('jenis_kelamin', $penerima['jenis_kelamin'] ?? '') == 'P') ? 'selected' : '' ?>>Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Pendidikan</label>
                        <select name="pendidikan_id" onchange="toggleLainnya(this, 'pendidikan_id_manual')" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all appearance-none font-bold">
                            <option value="">Pilih Pendidikan</option>
                            <?php foreach(($master['PENDIDIKAN'] ?? []) as $rp): ?>
                                <option value="<?= $rp['id'] ?>" <?= $rp['id'] == old('pendidikan_id', $penerima['pendidikan_id'] ?? '') ? 'selected' : '' ?>><?= $rp['nama_pilihan'] ?></option>
                            <?php endforeach; ?>
                            <option value="lainnya">Lainnya...</option>
                        </select>
                        <input type="text" name="pendidikan_id_manual" id="pendidikan_id_manual" placeholder="Sebutkan pendidikan..." class="hidden w-full mt-2 p-3.5 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900 rounded-xl focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 dark:text-slate-200 outline-none transition-all font-bold text-sm animate-in fade-in slide-in-from-top-1">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Pekerjaan</label>
                        <select name="pekerjaan_id" onchange="toggleLainnya(this, 'pekerjaan_id_manual')" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all appearance-none font-bold">
                            <option value="">Pilih Pekerjaan</option>
                            <?php foreach(($master['PEKERJAAN'] ?? []) as $rj): ?>
                                <option value="<?= $rj['id'] ?>" <?= $rj['id'] == old('pekerjaan_id', $penerima['pekerjaan_id'] ?? '') ? 'selected' : '' ?>><?= $rj['nama_pilihan'] ?></option>
                            <?php endforeach; ?>
                            <option value="lainnya">Lainnya...</option>
                        </select>
                        <input type="text" name="pekerjaan_id_manual" id="pekerjaan_id_manual" placeholder="Sebutkan pekerjaan..." class="hidden w-full mt-2 p-3.5 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900 rounded-xl focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 dark:text-slate-200 outline-none transition-all font-bold text-sm animate-in fade-in slide-in-from-top-1">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Penghasilan / Bulan</label>
                        <input type="text" name="penghasilan_per_bulan" value="<?= old('penghasilan_per_bulan', $penerima['penghasilan_per_bulan'] ?? '') ?>" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Jml Anggota Keluarga</label>
                        <input type="number" name="jumlah_anggota_keluarga" min="0" value="<?= old('jumlah_anggota_keluarga', $penerima['jumlah_anggota_keluarga'] ?? '0') ?>" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold text-sm">
                    </div>
                </div>
            </div>

            <!-- SECTION 2: PROFIL RUMAH & LAHAN -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all duration-300">
                <div class="p-6 border-b dark:border-slate-800 bg-slate-50/30 dark:bg-slate-950/30 flex items-center gap-3">
                    <div class="w-9 h-9 bg-indigo-600 rounded-lg flex items-center justify-center text-white shadow-lg">
                        <i data-lucide="home" class="w-4.5 h-4.5"></i>
                    </div>
                    <div>
                        <h3 class="text-[11px] font-bold text-indigo-900 dark:text-indigo-400 uppercase tracking-[0.2em]">Profil Rumah & Lahan</h3>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Informasi Lokasi & Teknis Dasar</p>
                    </div>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="lg:col-span-2">
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Alamat Lengkap</label>
                        <textarea name="alamat_detail" rows="1" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 dark:text-slate-200 outline-none transition-all font-bold min-h-[50px]"><?= old('alamat_detail', $rumah['alamat_detail']) ?></textarea>
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Desa / Kelurahan</label>
                        <input type="text" name="desa" value="<?= old('desa', $rumah['desa']) ?>" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 dark:text-slate-200 outline-none transition-all font-bold">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Jenis Kawasan</label>
                        <input type="text" name="jenis_kawasan" value="<?= old('jenis_kawasan', $rumah['jenis_kawasan']) ?>" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 dark:text-slate-200 outline-none transition-all font-bold">
                    </div>
                    <div class="bg-indigo-50 dark:bg-indigo-950/30 p-5 rounded-2xl border border-indigo-100 dark:border-indigo-900/50">
                        <label class="block text-[8px] font-bold text-indigo-900 dark:text-indigo-400 uppercase mb-2 tracking-widest ml-1">Luas Rumah (m²)</label>
                        <input type="number" step="0.01" name="luas_rumah_m2" value="<?= old('luas_rumah_m2', $rumah['luas_rumah_m2']) ?>" class="w-full bg-transparent border-none text-xl font-bold text-indigo-950 dark:text-white p-0 focus:ring-0 outline-none">
                    </div>
                    <div class="bg-indigo-50 dark:bg-indigo-950/30 p-5 rounded-2xl border border-indigo-100 dark:border-indigo-900/50">
                        <label class="block text-[8px] font-bold text-indigo-900 dark:text-indigo-400 uppercase mb-2 tracking-widest ml-1">Luas Lahan (m²)</label>
                        <input type="number" step="0.01" name="luas_lahan_m2" value="<?= old('luas_lahan_m2', $rumah['luas_lahan_m2']) ?>" class="w-full bg-transparent border-none text-xl font-bold text-indigo-950 dark:text-white p-0 focus:ring-0 outline-none">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Jml Penghuni</label>
                        <input type="number" name="jumlah_penghuni_jiwa" min="0" value="<?= old('jumlah_penghuni_jiwa', $rumah['jumlah_penghuni_jiwa']) ?>" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 dark:text-slate-200 outline-none transition-all font-bold text-sm">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Fungsi Ruang</label>
                        <input type="text" name="fungsi_ruang" value="<?= old('fungsi_ruang', $rumah['fungsi_ruang']) ?>" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 dark:text-slate-200 outline-none transition-all font-bold">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Kepemilikan Rumah</label>
                        <input type="text" name="kepemilikan_rumah" value="<?= old('kepemilikan_rumah', $rumah['kepemilikan_rumah']) ?>" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 dark:text-slate-200 outline-none transition-all font-bold">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Kepemilikan Tanah</label>
                        <input type="text" name="kepemilikan_tanah" value="<?= old('kepemilikan_tanah', $rumah['kepemilikan_tanah']) ?>" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 dark:text-slate-200 outline-none transition-all font-bold">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Status Backlog</label>
                        <input type="text" name="status_backlog" value="<?= old('status_backlog', $rumah['status_backlog']) ?>" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 dark:text-slate-200 outline-none transition-all font-bold">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Desil Nasional</label>
                        <input type="text" name="desil_nasional" value="<?= old('desil_nasional', $rumah['desil_nasional']) ?>" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 dark:text-slate-200 outline-none transition-all font-bold">
                    </div>
                </div>
            </div>

            <!-- SECTION 3: PENILAIAN TEKNIS & GEOSPASIAL -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- 3.1 Penilaian Teknis -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all duration-300">
                    <div class="p-6 border-b dark:border-slate-800 bg-blue-50/30 dark:bg-blue-950/30 flex items-center gap-3">
                        <div class="w-9 h-9 bg-blue-950 dark:bg-blue-600 rounded-lg flex items-center justify-center text-white shadow-lg">
                            <i data-lucide="shield-check" class="w-4.5 h-4.5"></i>
                        </div>
                        <div>
                            <h3 class="text-[11px] font-bold text-blue-950 dark:text-white uppercase tracking-[0.2em]">Penilaian Teknis</h3>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Kualitas Fisik Bangunan</p>
                        </div>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php 
                            $komponen = [
                                'st_pondasi' => 'Pondasi', 'st_kolom' => 'Kolom', 'st_balok' => 'Balok', 'st_sloof' => 'Sloof', 
                                'st_rangka_atap' => 'Rangka Atap', 'st_plafon' => 'Plafon', 'st_jendela' => 'Jendela', 'st_ventilasi' => 'Ventilasi',
                                'mat_atap' => 'Material Atap', 'st_atap' => 'Kondisi Atap',
                                'mat_dinding' => 'Material Dinding', 'st_dinding' => 'Kondisi Dinding',
                                'mat_lantai' => 'Material Lantai', 'st_lantai' => 'Kondisi Lantai'
                            ];
                            foreach($komponen as $key => $label) : 
                                $kategori = (str_starts_with($key, 'mat_')) ? 'MATERIAL_' . strtoupper(explode('_', $key)[1]) : 'KONDISI';
                        ?>
                        <div>
                            <label class="block text-[8px] font-bold text-slate-400 uppercase mb-1.5 tracking-widest ml-1"><?= $label ?></label>
                            <select name="<?= $key ?>" onchange="toggleLainnya(this, '<?= $key ?>_manual')" class="w-full p-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all appearance-none text-[10px] font-bold">
                                <option value="">Pilih <?= (str_starts_with($key, 'mat_')) ? 'Material' : 'Kondisi' ?></option>
                                <?php if(isset($master[$kategori])): foreach($master[$kategori] as $opt) : ?>
                                    <option value="<?= $opt['id'] ?>" <?= $opt['id'] == old($key, $kondisi[$key] ?? '') ? 'selected' : '' ?>><?= $opt['nama_pilihan'] ?></option>
                                <?php endforeach; endif; ?>
                                <option value="lainnya">Lainnya...</option>
                            </select>
                            <input type="text" name="<?= $key ?>_manual" id="<?= $key ?>_manual" placeholder="Sebutkan <?= strtolower($label) ?>..." class="hidden w-full mt-1.5 p-2.5 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900 rounded-lg focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 dark:text-slate-200 outline-none transition-all font-bold text-[10px] animate-in fade-in slide-in-from-top-1">
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- 3.2 Geospasial Picker -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all duration-300">
                    <div class="p-6 border-b dark:border-slate-800 bg-rose-50/30 dark:bg-rose-950/30 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-rose-600 rounded-lg flex items-center justify-center text-white shadow-lg">
                                <i data-lucide="map" class="w-4.5 h-4.5"></i>
                            </div>
                            <div>
                                <h3 class="text-[11px] font-bold text-rose-950 dark:text-rose-400 uppercase tracking-[0.2em]">Titik Koordinat</h3>
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Geser marker untuk pembaruan</p>
                            </div>
                        </div>
                        <button type="button" onclick="getLocation()" class="p-2.5 bg-rose-50 dark:bg-rose-900/30 text-rose-600 rounded-lg hover:bg-rose-600 hover:text-white transition-all shadow-sm" title="GPS">
                            <i data-lucide="crosshair" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <div id="map-picker" class="w-full h-[300px] z-10" style="background: #ececec;"></div>
                    <div class="p-4 space-y-3">
                        <div class="flex items-center justify-between px-1">
                            <label class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Well-Known Text (WKT)</label>
                            <button type="button" onclick="syncFromText()" class="text-[8px] font-bold text-blue-600 uppercase tracking-widest flex items-center gap-1 hover:underline">
                                <i data-lucide="refresh-cw" class="w-2.5 h-2.5"></i> Sinkronkan
                            </button>
                        </div>
                        <input type="text" name="lokasi_koordinat" id="lokasi_koordinat" value="<?= old('lokasi_koordinat', $rumah['lokasi_koordinat']) ?>" class="w-full p-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-[10px] font-mono font-bold text-rose-600 dark:text-rose-400 outline-none">
                    </div>
                </div>
            </div>

            <!-- SECTION 4: FASILITAS & DOKUMENTASI -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all duration-300">
                <div class="p-6 border-b dark:border-slate-800 bg-blue-50/30 dark:bg-blue-950/30 flex items-center gap-3">
                    <div class="w-9 h-9 bg-blue-950 rounded-lg flex items-center justify-center text-white shadow-lg">
                        <i data-lucide="camera" class="w-4.5 h-4.5"></i>
                    </div>
                    <div>
                        <h3 class="text-[11px] font-bold text-blue-950 dark:text-white uppercase tracking-[0.2em]">Fasilitas & Dokumentasi</h3>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Utilitas Sanitasi & Foto Survei</p>
                    </div>
                </div>
                <div class="p-8 space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Sumber Air Minum</label>
                            <input type="text" name="sumber_air_minum" value="<?= old('sumber_air_minum', $rumah['sumber_air_minum']) ?>" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl font-bold outline-none focus:ring-4 focus:ring-blue-500/10">
                        </div>
                        <div>
                            <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Penerangan</label>
                            <input type="text" name="sumber_penerangan" value="<?= old('sumber_penerangan', $rumah['sumber_penerangan']) ?>" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl font-bold outline-none focus:ring-4 focus:ring-blue-500/10">
                        </div>
                        <div>
                            <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Sanitasi</label>
                            <input type="text" name="jenis_jamban_kloset" value="<?= old('jenis_jamban_kloset', $rumah['jenis_jamban_kloset']) ?>" class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl font-bold outline-none focus:ring-4 focus:ring-blue-500/10">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <?php 
                            $fotos = ['foto_depan' => 'Tampak Depan', 'foto_samping' => 'Tampak Samping', 'foto_belakang' => 'Tampak Belakang', 'foto_dalam' => 'Bagian Dalam'];
                            foreach($fotos as $f_key => $f_label):
                                $hasPhoto = !empty($rumah[$f_key]) && file_exists(FCPATH . 'uploads/rtlh/' . $rumah[$f_key]);
                        ?>
                        <div class="space-y-2">
                            <label class="block text-[8px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1"><?= $f_label ?></label>
                            <div class="relative group">
                                <input type="file" name="<?= $f_key ?>" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 z-10 cursor-pointer" onchange="previewImage(this, '<?= $f_key ?>_preview')">
                                <div id="<?= $f_key ?>_preview" class="w-full h-32 bg-slate-50 dark:bg-slate-950 border-2 <?= $hasPhoto ? 'border-solid border-blue-600/50' : 'border-dashed border-slate-200 dark:border-slate-800' ?> rounded-2xl flex flex-col items-center justify-center overflow-hidden transition-all group-hover:border-blue-600 group-hover:bg-blue-50/5">
                                    <?php if($hasPhoto): ?>
                                        <img src="<?= base_url('uploads/rtlh/' . $rumah[$f_key]) ?>" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-blue-950/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <span class="px-3 py-1.5 bg-white/20 backdrop-blur-md rounded-lg text-[7px] font-bold text-white uppercase tracking-widest border border-white/30">Ganti Foto</span>
                                        </div>
                                    <?php else: ?>
                                        <i data-lucide="image-plus" class="w-6 h-6 text-slate-300 mb-1.5"></i>
                                        <span class="text-[7px] font-bold text-slate-400 uppercase tracking-widest">Pilih Foto</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- ACTION BAR -->
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 pt-4">
                <div class="flex items-center gap-3 text-slate-400">
                    <i data-lucide="info" class="w-4 h-4"></i>
                    <p class="text-[9px] font-bold uppercase tracking-widest leading-relaxed">Pembaruan data akan dicatat dalam log audit sistem untuk monitoring histori.</p>
                </div>
                <button type="submit" class="group flex items-center space-x-6 bg-amber-500 hover:bg-amber-600 text-white pl-8 pr-4 py-4 rounded-xl font-bold shadow-xl shadow-amber-500/20 transition-all active:scale-95 w-full md:w-auto">
                    <div class="flex flex-col text-right">
                        <span class="text-[8px] uppercase tracking-[0.3em] opacity-60 mb-0.5">Simpan Perubahan</span>
                        <span class="text-base uppercase tracking-tighter">Perbarui Laporan</span>
                    </div>
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center group-hover:translate-x-1 transition-transform">
                        <i data-lucide="save" class="w-5 h-5"></i>
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
    });

    function parseWKT(wkt) {
        if (!wkt || typeof wkt !== 'string') return null;
        const match = wkt.match(/POINT\s*\(\s*([-\d.]+)\s+([-\d.]+)\s*\)/i);
        if (match) return { lng: parseFloat(match[1]), lat: parseFloat(match[2]) };
        return null;
    }

    function initMapPicker() {
        if (typeof L === 'undefined') { setTimeout(initMapPicker, 100); return; }
        const tiles = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: 'Esri' });
        const initialWkt = document.getElementById('lokasi_koordinat').value;
        const coords = parseWKT(initialWkt);
        
        map = L.map('map-picker', { zoomControl: false, layers: [tiles] }).setView(coords ? [coords.lat, coords.lng] : [-5.1245, 120.2536], coords ? 18 : 15);
        L.control.zoom({ position: 'topright' }).addTo(map);

        if (coords) {
            marker = L.marker([coords.lat, coords.lng], { draggable: true }).addTo(map);
            marker.on('dragend', (e) => updateInput(e.target.getLatLng().lat, e.target.getLatLng().lng));
        }

        map.on('click', (e) => updateMarker(e.latlng.lat, e.latlng.lng));
    }

    function updateMarker(lat, lng) {
        if (marker) marker.setLatLng([lat, lng]);
        else {
            marker = L.marker([lat, lng], { draggable: true }).addTo(map);
            marker.on('dragend', (e) => updateInput(e.target.getLatLng().lat, e.target.getLatLng().lng));
        }
        updateInput(lat, lng);
        map.panTo([lat, lng]);
    }

    function updateInput(lat, lng) {
        document.getElementById('lokasi_koordinat').value = `POINT(${lng.toFixed(8)} ${lat.toFixed(8)})`;
    }

    function syncFromText() {
        const coords = parseWKT(document.getElementById('lokasi_koordinat').value);
        if (coords) { updateMarker(coords.lat, coords.lng); map.setView([coords.lat, coords.lng], 18); }
        else alert('Format tidak valid.');
    }

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((pos) => {
                updateMarker(pos.coords.latitude, pos.coords.longitude);
                map.setZoom(18);
            }, () => alert('Gagal akses lokasi.'));
        }
    }

    function toggleLainnya(select, inputId) {
        const input = document.getElementById(inputId);
        if (select.value === 'lainnya') {
            input.classList.remove('hidden');
            input.focus();
            input.required = true;
        } else {
            input.classList.add('hidden');
            input.required = false;
        }
    }

    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = (e) => {
                preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                preview.classList.remove('border-dashed');
                preview.classList.add('border-solid', 'border-blue-600');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<?= $this->endSection() ?>
