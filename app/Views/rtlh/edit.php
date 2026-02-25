<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-6xl mx-auto pb-20">
    <form action="<?= base_url('rtlh/update/' . $rumah['id_survei']) ?>" method="post">
        <?= csrf_field() ?>

        <div class="space-y-8">
            <!-- HEADER -->
            <div class="flex justify-between items-center bg-white p-6 rounded-2xl border shadow-sm sticky top-4 z-20">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Edit Data RTLH Terpadu</h1>
                    <p class="text-sm text-gray-500 italic">ID Survei: #<?= $rumah['id_survei'] ?></p>
                </div>
                <div class="flex space-x-3">
                    <a href="<?= base_url('rtlh/detail/' . $rumah['id_survei']) ?>" class="text-sm font-bold text-gray-400 hover:text-gray-600 px-4 py-2 flex items-center">Batal</a>
                    <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-amber-100 transition-all flex items-center space-x-2">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                        <span>Update Seluruh Perubahan</span>
                    </button>
                </div>
            </div>

            <!-- BAGIAN 1: IDENTITAS PENERIMA -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b bg-blue-50/30">
                    <h3 class="font-bold text-blue-700 flex items-center uppercase tracking-wider text-sm">
                        <i data-lucide="user" class="w-5 h-5 mr-2"></i> I. Data Penerima (Pemilik)
                    </h3>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">NIK</label>
                        <input type="text" name="nik" value="<?= old('nik', $penerima['nik']) ?>" maxlength="16" required class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500/20">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Nomor KK</label>
                        <input type="text" name="no_kk" value="<?= old('no_kk', $penerima['no_kk']) ?>" maxlength="16" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500/20">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Nama Kepala Keluarga</label>
                        <input type="text" name="nama_kepala_keluarga" value="<?= old('nama_kepala_keluarga', $penerima['nama_kepala_keluarga']) ?>" required class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500/20">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="<?= old('tempat_lahir', $penerima['tempat_lahir']) ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="<?= old('tanggal_lahir', $penerima['tanggal_lahir']) ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none">
                            <option value="L" <?= $penerima['jenis_kelamin'] == 'L' ? 'selected' : '' ?>>Laki-laki</option>
                            <option value="P" <?= $penerima['jenis_kelamin'] == 'P' ? 'selected' : '' ?>>Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Pendidikan</label>
                        <select name="pendidikan_id" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none">
                            <?php foreach(($master['PENDIDIKAN'] ?? []) as $p): ?>
                                <option value="<?= $p['id'] ?>" <?= $p['id'] == $penerima['pendidikan_id'] ? 'selected' : '' ?>><?= $p['nama_pilihan'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Pekerjaan</label>
                        <select name="pekerjaan_id" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none">
                            <?php foreach(($master['PEKERJAAN'] ?? []) as $p): ?>
                                <option value="<?= $p['id'] ?>" <?= $p['id'] == $penerima['pekerjaan_id'] ? 'selected' : '' ?>><?= $p['nama_pilihan'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Anggota Keluarga (Orang)</label>
                        <input type="number" name="jumlah_anggota_keluarga" value="<?= old('jumlah_anggota_keluarga', $penerima['jumlah_anggota_keluarga']) ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none">
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Penghasilan / Bulan</label>
                        <input type="text" name="penghasilan_per_bulan" value="<?= old('penghasilan_per_bulan', $penerima['penghasilan_per_bulan']) ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none">
                    </div>
                </div>
            </div>

            <!-- BAGIAN 2: DETAIL RUMAH -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b bg-emerald-50/30">
                    <h3 class="font-bold text-emerald-700 flex items-center uppercase tracking-wider text-sm">
                        <i data-lucide="home" class="w-5 h-5 mr-2"></i> II. Profil Rumah & Lingkungan
                    </h3>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Nama Desa</label>
                        <input type="text" name="desa" value="<?= old('desa', $rumah['desa']) ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Desa ID</label>
                        <input type="number" name="desa_id" value="<?= old('desa_id', $rumah['desa_id']) ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Jenis Kawasan</label>
                        <input type="text" name="jenis_kawasan" value="<?= old('jenis_kawasan', $rumah['jenis_kawasan']) ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none">
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Alamat Detail</label>
                        <textarea name="alamat_detail" rows="2" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none"><?= old('alamat_detail', $rumah['alamat_detail']) ?></textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Kepemilikan Rumah</label>
                        <input type="text" name="kepemilikan_rumah" value="<?= old('kepemilikan_rumah', $rumah['kepemilikan_rumah']) ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Kepemilikan Tanah</label>
                        <input type="text" name="kepemilikan_tanah" value="<?= old('kepemilikan_tanah', $rumah['kepemilikan_tanah']) ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Fungsi Ruang</label>
                        <input type="text" name="fungsi_ruang" value="<?= old('fungsi_ruang', $rumah['fungsi_ruang']) ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Aset Rumah Lain</label>
                        <input type="text" name="aset_rumah_di_lokasi_lain" value="<?= old('aset_rumah_di_lokasi_lain', $rumah['aset_rumah_di_lokasi_lain']) ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Bantuan Perumahan</label>
                        <input type="text" name="bantuan_perumahan" value="<?= old('bantuan_perumahan', $rumah['bantuan_perumahan']) ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Jumlah Penghuni (Jiwa)</label>
                        <input type="number" name="jumlah_penghuni_jiwa" value="<?= old('jumlah_penghuni_jiwa', $rumah['jumlah_penghuni_jiwa']) ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Luas Rumah (m2)</label>
                        <input type="number" name="luas_rumah_m2" value="<?= old('luas_rumah_m2', $rumah['luas_rumah_m2']) ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Luas Lahan (m2)</label>
                        <input type="number" name="luas_lahan_m2" value="<?= old('luas_lahan_m2', $rumah['luas_lahan_m2']) ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none">
                    </div>
                </div>
            </div>

            <!-- BAGIAN 3: FASILITAS & SANITASI -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b bg-amber-50/30">
                    <h3 class="font-bold text-amber-700 flex items-center uppercase tracking-wider text-sm">
                        <i data-lucide="droplets" class="w-5 h-5 mr-2"></i> III. Fasilitas & Sanitasi
                    </h3>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Sumber Penerangan Logic -->
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Sumber Penerangan</label>
                        <select name="sumber_penerangan" id="sumber_penerangan" onchange="togglePenerangan()" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none">
                            <option value="Tidak Ada" <?= $rumah['sumber_penerangan'] == 'Tidak Ada' ? 'selected' : '' ?>>TIDAK ADA</option>
                            <option value="Ada" <?= $rumah['sumber_penerangan'] == 'Ada' ? 'selected' : '' ?>>ADA</option>
                        </select>
                    </div>
                    <div id="detail_penerangan_wrapper" class="<?= $rumah['sumber_penerangan'] == 'Ada' ? '' : 'hidden' ?>">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Detail Sumber</label>
                        <select name="sumber_penerangan_detail" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none">
                            <option value="PLN dengan meteran" <?= $rumah['sumber_penerangan_detail'] == 'PLN dengan meteran' ? 'selected' : '' ?>>PLN dengan meteran</option>
                            <option value="PLN tanpa meteran" <?= $rumah['sumber_penerangan_detail'] == 'PLN tanpa meteran' ? 'selected' : '' ?>>PLN tanpa meteran</option>
                            <option value="Bukan PLN" <?= $rumah['sumber_penerangan_detail'] == 'Bukan PLN' ? 'selected' : '' ?>>Bukan PLN</option>
                            <option value="Bukan Listrik" <?= $rumah['sumber_penerangan_detail'] == 'Bukan Listrik' ? 'selected' : '' ?>>Bukan Listrik</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Sumber Air Minum</label>
                        <input type="text" name="sumber_air_minum" value="<?= old('sumber_air_minum', $rumah['sumber_air_minum']) ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Jarak SAM ke TPA Tinja</label>
                        <input type="text" name="jarak_sam_ke_tpa_tinja" value="<?= old('jarak_sam_ke_tpa_tinja', $rumah['jarak_sam_ke_tpa_tinja']) ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Kamar Mandi & Jamban</label>
                        <input type="text" name="kamar_mandi_dan_jamban" value="<?= old('kamar_mandi_dan_jamban', $rumah['kamar_mandi_dan_jamban']) ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Jenis Jamban / Kloset</label>
                        <input type="text" name="jenis_jamban_kloset" value="<?= old('jenis_jamban_kloset', $rumah['jenis_jamban_kloset']) ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Jenis TPA Tinja</label>
                        <input type="text" name="jenis_tpa_tinja" value="<?= old('jenis_tpa_tinja', $rumah['jenis_tpa_tinja']) ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Lokasi Koordinat (WKT)</label>
                        <input type="text" name="lokasi_koordinat" value="<?= old('lokasi_koordinat', $rumah['lokasi_koordinat']) ?>" placeholder="POINT(X Y)" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none font-mono text-xs">
                    </div>
                </div>
            </div>

            <!-- BAGIAN 4: KONDISI FISIK -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b bg-rose-50/30">
                    <h3 class="font-bold text-rose-700 flex items-center uppercase tracking-wider text-sm">
                        <i data-lucide="shield-check" class="w-5 h-5 mr-2"></i> IV. Kondisi Fisik Bangunan
                    </h3>
                </div>
                <div class="p-8 space-y-8">
                    <?php $optKondisi = $master['KONDISI'] ?? []; ?>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <?php 
                            $komponen = [
                                'st_pondasi' => 'Pondasi', 'st_kolom' => 'Kolom', 'st_balok' => 'Balok', 
                                'st_sloof' => 'Sloof', 'st_rangka_atap' => 'Rangka Atap', 
                                'st_plafon' => 'Plafon', 'st_jendela' => 'Jendela', 'st_ventilasi' => 'Ventilasi'
                            ];
                            foreach($komponen as $key => $label) : 
                        ?>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-600 mb-2"><?= $label ?></label>
                                <select name="<?= $key ?>" class="w-full p-2 bg-gray-50 border border-gray-200 rounded-lg text-sm">
                                    <option value="">Pilih Kondisi</option>
                                    <?php foreach($optKondisi as $rk) : ?>
                                        <option value="<?= $rk['id'] ?>" <?= $rk['id'] == ($kondisi[$key] ?? '') ? 'selected' : '' ?>><?= $rk['nama_pilihan'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 pt-4">
                        <!-- Atap/Dinding/Lantai sama seperti sebelumnya -->
                        <?php foreach(['atap' => 'ATAP', 'dinding' => 'DINDING', 'lantai' => 'LANTAI'] as $k => $l): ?>
                        <div class="p-4 bg-gray-50 rounded-2xl border">
                            <p class="text-sm font-bold text-gray-700 mb-4"><?= $l ?></p>
                            <div class="space-y-4">
                                <div><label class="text-[9px] font-bold text-gray-400 uppercase">Material</label>
                                <select name="mat_<?= $k ?>" class="w-full p-2 border rounded-lg text-xs">
                                    <?php foreach(($master['MATERIAL_'.strtoupper($k)] ?? []) as $m): ?>
                                        <option value="<?= $m['id'] ?>" <?= $m['id'] == ($kondisi['mat_'.$k] ?? '') ? 'selected' : '' ?>><?= $m['nama_pilihan'] ?></option>
                                    <?php endforeach; ?>
                                </select></div>
                                <div><label class="text-[9px] font-bold text-gray-400 uppercase">Kondisi</label>
                                <select name="st_<?= $k ?>" class="w-full p-2 border rounded-lg text-xs">
                                    <?php foreach($optKondisi as $rk): ?>
                                        <option value="<?= $rk['id'] ?>" <?= $rk['id'] == ($kondisi['st_'.$k] ?? '') ? 'selected' : '' ?>><?= $rk['nama_pilihan'] ?></option>
                                    <?php endforeach; ?>
                                </select></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    lucide.createIcons();
    
    function togglePenerangan() {
        const val = document.getElementById('sumber_penerangan').value;
        const wrapper = document.getElementById('detail_penerangan_wrapper');
        if (val === 'Ada') {
            wrapper.classList.remove('hidden');
        } else {
            wrapper.classList.add('hidden');
        }
    }
</script>
<?= $this->endSection() ?>
