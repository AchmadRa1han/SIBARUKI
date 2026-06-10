<!-- RTLH Multi-Step Modal Component -->
<div id="modal-rtlh" class="fixed inset-0 z-[10002] hidden overflow-y-auto py-10 px-4">
    <div class="fixed inset-0 bg-blue-950/60 backdrop-blur-sm transition-opacity" onclick="UI.closeModal('modal-rtlh')"></div>
    <div class="relative flex items-center justify-center p-4 min-h-full">
        <div class="bg-white dark:bg-slate-900 w-full max-w-5xl rounded-[2.5rem] shadow-2xl overflow-hidden animate-in zoom-in duration-300">
            <!-- Modal Header -->
            <div class="p-8 bg-blue-950 text-white flex justify-between items-center border-b border-white/10 sticky top-0 z-30">
                <div class="flex items-center gap-5">
                    <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-md border border-white/10">
                        <i data-lucide="file-edit" class="w-6 h-6 text-blue-400" id="modal-rtlh-icon"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black uppercase tracking-tighter" id="modal-rtlh-title">Data Rumah</h3>
                        <!-- Stepper Progress -->
                        <div class="flex items-center gap-4 mt-2">
                            <div class="flex items-center gap-2">
                                <span class="step-dot w-5 h-5 rounded-full bg-blue-600 text-[10px] font-black flex items-center justify-center text-white" data-step="1">1</span>
                                <span class="text-[8px] font-bold uppercase tracking-widest text-white">Identitas & Lokasi</span>
                            </div>
                            <div class="w-4 h-px bg-white/20"></div>
                            <div class="flex items-center gap-2">
                                <span class="step-dot w-5 h-5 rounded-full bg-white/10 text-[10px] font-black flex items-center justify-center text-white/40" data-step="2">2</span>
                                <span class="text-[8px] font-bold uppercase tracking-widest text-white/40">Fasilitas</span>
                            </div>
                            <div class="w-4 h-px bg-white/20"></div>
                            <div class="flex items-center gap-2">
                                <span class="step-dot w-5 h-5 rounded-full bg-white/10 text-[10px] font-black flex items-center justify-center text-white/40" data-step="3">3</span>
                                <span class="text-[8px] font-bold uppercase tracking-widest text-white/40">Teknis & Foto</span>
                            </div>
                        </div>
                    </div>
                </div>
                <button onclick="UI.closeModal('modal-rtlh')" class="p-2 hover:bg-white/10 rounded-xl transition-colors"><i data-lucide="x" class="w-6 h-6"></i></button>
            </div>

            <form id="form-rtlh" action="<?= base_url('rtlh/store') ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                <?= csrf_field() ?>
                
                <!-- STEP 1: IDENTITAS & LOKASI -->
                <div class="modal-step" id="step-rtlh-1">
                    <div class="p-10 grid grid-cols-1 lg:grid-cols-2 gap-10">
                        <div class="space-y-6">
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Kepala Keluarga</label>
                                <input type="text" name="nama_kepala_keluarga" id="inp_nama" required class="w-full mt-1.5 p-4 bg-slate-100 dark:bg-slate-800 border-none rounded-2xl font-bold text-sm outline-none focus:ring-2 focus:ring-blue-600">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">NIK (16 Digit)</label>
                                    <input type="text" name="nik" id="inp_nik" maxlength="16" required class="w-full mt-1.5 p-4 bg-slate-100 dark:bg-slate-800 border-none rounded-2xl font-bold text-sm outline-none focus:ring-2 focus:ring-blue-600">
                                </div>
                                <div>
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">No. Kartu Keluarga</label>
                                    <input type="text" name="no_kk" id="inp_no_kk" maxlength="16" class="w-full mt-1.5 p-4 bg-slate-100 dark:bg-slate-800 border-none rounded-2xl font-bold text-sm outline-none focus:ring-2 focus:ring-blue-600">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" id="inp_jk" class="w-full mt-1.5 p-4 bg-slate-100 dark:bg-slate-800 border-none rounded-2xl font-bold text-xs outline-none">
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Jumlah Anggota Keluarga</label>
                                    <input type="number" name="jumlah_anggota_keluarga" id="inp_jml_keluarga" class="w-full mt-1.5 p-4 bg-slate-100 dark:bg-slate-800 border-none rounded-2xl font-bold text-sm outline-none">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" id="inp_tempat_lahir" class="w-full mt-1.5 p-4 bg-slate-100 dark:bg-slate-800 border-none rounded-2xl font-bold text-sm outline-none">
                                </div>
                                <div>
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" id="inp_tgl_lahir" class="w-full mt-1.5 p-4 bg-slate-100 dark:bg-slate-800 border-none rounded-2xl font-bold text-sm outline-none">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Pendidikan</label>
                                    <select name="pendidikan_id" id="inp_pendidikan_id" class="w-full mt-1.5 p-4 bg-slate-100 dark:bg-slate-800 border-none rounded-2xl font-bold text-xs outline-none">
                                        <option value="">Pilih</option>
                                        <?php foreach(($master['PENDIDIKAN'] ?? []) as $edu): ?><option value="<?= $edu['id'] ?>"><?= $edu['nama_pilihan'] ?></option><?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Pekerjaan</label>
                                    <select name="pekerjaan_id" id="inp_pekerjaan_id" class="w-full mt-1.5 p-4 bg-slate-100 dark:bg-slate-800 border-none rounded-2xl font-bold text-xs outline-none">
                                        <option value="">Pilih</option>
                                        <?php foreach(($master['PEKERJAAN'] ?? []) as $job): ?><option value="<?= $job['id'] ?>"><?= $job['nama_pilihan'] ?></option><?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Penghasilan Per Bulan</label>
                                <select name="penghasilan_per_bulan" id="inp_penghasilan" class="w-full mt-1.5 p-4 bg-slate-100 dark:bg-slate-800 border-none rounded-2xl font-bold text-xs outline-none">
                                    <option value="">Pilih</option>
                                    <?php foreach(($master['PENGHASILAN'] ?? []) as $inc): ?><option value="<?= $inc['id'] ?>"><?= $inc['nama_pilihan'] ?></option><?php endforeach; ?>
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Desa/Kelurahan</label>
                                    <select name="desa_id" id="inp_desa_id" required class="w-full mt-1.5 p-4 bg-slate-100 dark:bg-slate-800 border-none rounded-2xl font-bold text-xs outline-none focus:ring-2 focus:ring-blue-600">
                                        <option value="">Pilih Lokasi</option>
                                        <?php foreach(($desa_list ?? []) as $d): ?>
                                            <option value="<?= $d['desa_id'] ?>"><?= $d['desa'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="hidden" name="desa" id="inp_desa_nama">
                                </div>
                                <div>
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Luas Rumah (m²)</label>
                                    <input type="number" step="0.01" name="luas_rumah_m2" id="inp_luas_rumah" class="w-full mt-1.5 p-4 bg-slate-100 dark:bg-slate-800 border-none rounded-2xl font-bold text-sm outline-none focus:ring-2 focus:ring-blue-600">
                                </div>
                                <div>
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Luas Lahan (m²)</label>
                                    <input type="number" step="0.01" name="luas_lahan_m2" id="inp_luas_lahan" class="w-full mt-1.5 p-4 bg-slate-100 dark:bg-slate-800 border-none rounded-2xl font-bold text-sm outline-none focus:ring-2 focus:ring-blue-600">
                                </div>
                            </div>
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Alamat Detail</label>
                                <textarea name="alamat_detail" id="inp_alamat" rows="2" class="w-full mt-1.5 p-4 bg-slate-100 dark:bg-slate-800 border-none rounded-2xl font-bold text-sm outline-none focus:ring-2 focus:ring-blue-600"></textarea>
                            </div>
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Koordinat (WKT Point)</label>
                                <input type="text" name="lokasi_koordinat" id="inp_coords" readonly class="w-full mt-1.5 p-4 bg-blue-50 dark:bg-blue-900/20 border-none rounded-2xl font-mono text-xs text-blue-600 outline-none" placeholder="Klik pada peta untuk mengambil lokasi...">
                            </div>
                        </div>
                        <div class="relative rounded-[2rem] overflow-hidden border-4 border-slate-100 dark:border-slate-800 shadow-inner bg-slate-100 dark:bg-slate-800 min-h-[400px]">
                            <div id="modalMap" class="absolute inset-0 z-10"></div>
                            <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 bg-blue-950/80 backdrop-blur-md px-4 py-2 rounded-full border border-white/10">
                                <p class="text-[8px] font-black text-white uppercase tracking-widest whitespace-nowrap">Klik atau geser marker untuk menentukan lokasi</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 2: FASILITAS -->
                <div class="modal-step hidden" id="step-rtlh-2">
                    <div class="p-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div class="space-y-6">
                            <h4 class="text-[10px] font-black text-blue-600 uppercase border-b pb-3 tracking-widest flex items-center gap-2">
                                <i data-lucide="home" class="w-3 h-3"></i> Aset & Kawasan
                            </h4>
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Kepemilikan Rumah</label>
                                <select name="kepemilikan_rumah" id="inp_milik_rumah" class="w-full mt-1.5 p-4 bg-slate-100 dark:bg-slate-800 border-none rounded-2xl font-bold text-xs">
                                    <option value="">Pilih</option>
                                    <?php foreach(($master['KEPEMILIKAN_RUMAH'] ?? []) as $rm): ?><option value="<?= $rm['id'] ?>"><?= $rm['nama_pilihan'] ?></option><?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Kepemilikan Tanah</label>
                                <select name="kepemilikan_tanah" id="inp_milik_tanah" class="w-full mt-1.5 p-4 bg-slate-100 dark:bg-slate-800 border-none rounded-2xl font-bold text-xs">
                                    <option value="">Pilih</option>
                                    <?php foreach(($master['KEPEMILIKAN_TANAH'] ?? []) as $rt): ?><option value="<?= $rt['id'] ?>"><?= $rt['nama_pilihan'] ?></option><?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Jenis Kawasan</label>
                                <select name="jenis_kawasan" id="inp_kawasan" class="w-full mt-1.5 p-4 bg-slate-100 dark:bg-slate-800 border-none rounded-2xl font-bold text-xs">
                                    <option value="">Pilih</option>
                                    <?php foreach(($master['JENIS_KAWASAN'] ?? []) as $jk): ?><option value="<?= $jk['id'] ?>"><?= $jk['nama_pilihan'] ?></option><?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Fungsi Ruang</label>
                                <input type="text" name="fungsi_ruang" id="inp_fungsi_ruang" class="w-full mt-1.5 p-4 bg-slate-100 dark:bg-slate-800 border-none rounded-2xl font-bold text-xs outline-none" placeholder="Misal: Rumah Tinggal">
                            </div>
                        </div>
                        <div class="space-y-6">
                            <h4 class="text-[10px] font-black text-blue-600 uppercase border-b pb-3 tracking-widest flex items-center gap-2">
                                <i data-lucide="zap" class="w-3 h-3"></i> Utilitas Dasar
                            </h4>
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Sumber Penerangan</label>
                                <select name="sumber_penerangan" id="inp_listric" class="w-full mt-1.5 p-4 bg-slate-100 dark:bg-slate-800 border-none rounded-2xl font-bold text-xs">
                                    <option value="">Pilih</option>
                                    <?php foreach(($master['SUMBER_PENERANGAN'] ?? []) as $sp): ?><option value="<?= $sp['id'] ?>"><?= $sp['nama_pilihan'] ?></option><?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Sumber Air Minum</label>
                                <select name="sumber_air_minum" id="inp_air" class="w-full mt-1.5 p-4 bg-slate-100 dark:bg-slate-800 border-none rounded-2xl font-bold text-xs">
                                    <option value="">Pilih</option>
                                    <?php foreach(($master['SUMBER_AIR_MINUM'] ?? []) as $sa): ?><option value="<?= $sa['id'] ?>"><?= $sa['nama_pilihan'] ?></option><?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Jarak SAM ke TPA Tinja</label>
                                <input type="text" name="jarak_sam_ke_tpa_tinja" id="inp_jarak_sam" class="w-full mt-1.5 p-4 bg-slate-100 dark:bg-slate-800 border-none rounded-2xl font-bold text-xs outline-none" placeholder="Misal: < 10 Meter">
                            </div>
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Desil Nasional (Data P3KE)</label>
                                <input type="text" name="desil_nasional" id="inp_desil" class="w-full mt-1.5 p-4 bg-slate-100 dark:bg-slate-800 border-none rounded-2xl font-bold text-xs outline-none">
                            </div>
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Status Backlog</label>
                                <select name="status_backlog" id="inp_status_backlog" class="w-full mt-1.5 p-4 bg-slate-100 dark:bg-slate-800 border-none rounded-2xl font-bold text-xs outline-none">
                                    <option value="TIDAK BACKLOG">TIDAK BACKLOG</option>
                                    <option value="BACKLOG">BACKLOG (BUTUH RUMAH)</option>
                                </select>
                            </div>
                        </div>
                        <div class="space-y-6">
                            <h4 class="text-[10px] font-black text-blue-600 uppercase border-b pb-3 tracking-widest flex items-center gap-2">
                                <i data-lucide="droplet" class="w-3 h-3"></i> Sanitasi & Kesehatan
                            </h4>
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Fasilitas BAB</label>
                                <select name="kamar_mandi_dan_jamban" id="inp_bab" class="w-full mt-1.5 p-4 bg-slate-100 dark:bg-slate-800 border-none rounded-2xl font-bold text-xs">
                                    <option value="SENDIRI">SENDIRI</option>
                                    <option value="BERSAMA">BERSAMA / UMUM</option>
                                    <option value="TIDAK ADA">TIDAK ADA</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Tempat Pembuangan Akhir Tinja</label>
                                <input type="text" name="jenis_tpa_tinja" id="inp_tpa" class="w-full mt-1.5 p-4 bg-slate-100 dark:bg-slate-800 border-none rounded-2xl font-bold text-xs outline-none" placeholder="Misal: Septic Tank">
                            </div>
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Status Verifikasi / Bantuan</label>
                                <select name="status_bantuan" id="inp_status_bantuan" required class="w-full mt-1.5 p-4 bg-slate-100 dark:bg-slate-800 border-none rounded-2xl font-bold text-xs outline-none">
                                    <option value="Target">Target Bantuan</option>
                                    <option value="Rtlh">RTLH (Belum Penanganan)</option>
                                    <option value="Rlh">RLH (Sudah Layak)</option>
                                    <option value="Unknown">Belum Diverifikasi / Unknown</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 3: TEKNIS & FOTO -->
                <div class="modal-step hidden" id="step-rtlh-3">
                    <div class="p-10 space-y-12">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                            <div class="col-span-full">
                                <h4 class="text-xs font-black text-blue-600 uppercase tracking-widest border-l-4 border-blue-600 pl-4">III. Penilaian Kondisi Komponen Fisik</h4>
                            </div>
                            <?php 
                                $kompFields = [
                                    ['st_pondasi', 'Pondasi'], ['st_kolom', 'Tiang / Kolom'], ['st_balok', 'Balok'], ['st_sloof', 'Sloof'],
                                    ['st_rangka_atap', 'Rangka Atap'], ['st_plafon', 'Plafon'], ['st_jendela', 'Jendela'], ['st_ventilasi', 'Ventilasi'],
                                    ['mat_atap', 'Material Atap', 'MATERIAL_ATAP'], ['st_atap', 'Kondisi Atap'], 
                                    ['mat_dinding', 'Material Dinding', 'MATERIAL_DINDING'], ['st_dinding', 'Kondisi Dinding'],
                                    ['mat_lantai', 'Material Lantai', 'MATERIAL_LANTAI'], ['st_lantai', 'Kondisi Lantai']
                                ];
                                foreach($kompFields as $k):
                                    $cat = $k[2] ?? 'KONDISI';
                            ?>
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1"><?= $k[1] ?></label>
                                <select name="<?= $k[0] ?>" id="inp_<?= $k[0] ?>" class="w-full mt-1.5 p-3 bg-slate-100 dark:bg-slate-800 border-none rounded-xl font-bold text-[10px] outline-none focus:ring-2 focus:ring-blue-600">
                                    <option value="">Pilih</option>
                                    <?php foreach(($master[$cat] ?? []) as $opt): ?>
                                        <option value="<?= $opt['id'] ?>"><?= $opt['nama_pilihan'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                            <div class="col-span-full">
                                <h4 class="text-xs font-black text-rose-600 uppercase border-l-4 border-rose-600 pl-4">IV. Dokumentasi Visual</h4>
                            </div>
                            <?php 
                                $fotos = [['foto_depan', 'Tampak Depan'], ['foto_samping', 'Samping'], ['foto_belakang', 'Belakang'], ['foto_dalam', 'Interior']];
                                foreach($fotos as $f):
                            ?>
                            <div class="space-y-3">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1"><?= $f[1] ?></label>
                                <div class="relative group aspect-square rounded-3xl border-2 border-dashed border-slate-200 dark:border-slate-800 flex flex-col items-center justify-center overflow-hidden transition-all hover:border-blue-500 cursor-pointer" onclick="document.getElementById('inp_<?= $f[0] ?>').click()">
                                    <img id="prev_<?= $f[0] ?>" class="absolute inset-0 w-full h-full object-cover hidden">
                                    <div class="text-center z-10 p-4" id="placeholder_<?= $f[0] ?>">
                                        <i data-lucide="image-plus" class="w-8 h-8 text-slate-300 mb-2 mx-auto"></i>
                                        <p class="text-[7px] font-bold text-slate-400 uppercase tracking-widest">Pilih Gambar</p>
                                    </div>
                                    <input type="file" name="<?= $f[0] ?>" id="inp_<?= $f[0] ?>" class="hidden" accept="image/*" onchange="rtlhModal.previewImg(this, '<?= $f[0] ?>')">
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="p-8 bg-slate-100 dark:bg-slate-900 border-t dark:border-slate-800 flex justify-between items-center">
                    <button type="button" id="btn-rtlh-prev" onclick="rtlhModal.moveStep(-1)" class="hidden px-8 py-3 bg-white dark:bg-slate-800 text-slate-500 rounded-2xl font-black uppercase tracking-widest text-[10px] shadow-sm border border-slate-200 dark:border-slate-700 hover:bg-slate-100 transition-all">Sebelumnya</button>
                    <div class="flex-grow"></div>
                    <div class="flex gap-4">
                        <button type="button" onclick="UI.closeModal('modal-rtlh')" class="px-6 py-3 text-slate-400 font-black uppercase tracking-widest text-[10px] hover:text-rose-500 transition-colors">Batal</button>
                        <button type="button" id="btn-rtlh-next" onclick="rtlhModal.moveStep(1)" class="px-10 py-3 bg-blue-950 dark:bg-blue-600 text-white rounded-2xl font-black uppercase tracking-widest text-[10px] shadow-xl shadow-blue-950/20 active:scale-95 transition-all">Selanjutnya</button>
                        <button type="submit" id="btn-rtlh-save" class="hidden px-10 py-3 bg-emerald-600 text-white rounded-2xl font-black uppercase tracking-widest text-[10px] shadow-xl shadow-emerald-600/20 active:scale-95 transition-all">Simpan Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // RTLH Modal Controller Object - Hardened against PHP errors
    (function() {
        const RTLH_UPLOAD_URL = <?= json_encode(base_url('uploads/rtlh/')) ?>;
        
        window.rtlhModal = {
            currentStep: 1,
            map: null,
            marker: null,
            uploadUrl: RTLH_UPLOAD_URL.endsWith('/') ? RTLH_UPLOAD_URL : RTLH_UPLOAD_URL + '/',

            init: function() {
                // Listen to Modal Opened Event
                window.addEventListener('modalOpened', (e) => {
                    if (e && e.detail && e.detail.id === 'modal-rtlh') {
                        this.initMap();
                        this.showStep(1);
                    }
                });

                // Sync Desa Name
                const elDesa = document.getElementById('inp_desa_id');
                if (elDesa) {
                    elDesa.addEventListener('change', function() {
                        const text = this.options[this.selectedIndex].text;
                        const elNama = document.getElementById('inp_desa_nama');
                        if (elNama) elNama.value = text;
                    });
                }
            },

            initMap: function() {
                if (this.map) {
                    setTimeout(() => this.map.invalidateSize(), 300);
                    return;
                }
                
                try {
                    this.map = L.map('modalMap', { zoomControl: false }).setView([-5.1245, 120.2536], 12);
                    L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
                        maxZoom: 20,
                        subdomains:['mt0','mt1','mt2','mt3'],
                        attribution: '&copy; Google'
                    }).addTo(this.map);

                    this.map.on('click', (e) => this.setMarker(e.latlng.lat, e.latlng.lng));
                    setTimeout(() => this.map.invalidateSize(), 300);
                } catch (err) { console.error('Modal map init error:', err); }
            },

            setMarker: function(lat, lng) {
                if (!this.map) return;
                const parsedLat = parseFloat(lat);
                const parsedLng = parseFloat(lng);
                if (isNaN(parsedLat) || isNaN(parsedLng)) return;

                if (this.marker) {
                    this.marker.setLatLng([parsedLat, parsedLng]);
                } else {
                    this.marker = L.marker([parsedLat, parsedLng], { draggable: true }).addTo(this.map);
                    this.marker.on('dragend', (e) => {
                        const pos = e.target.getLatLng();
                        const elCoords = document.getElementById('inp_coords');
                        if (elCoords) elCoords.value = `POINT(${pos.lng.toFixed(7)} ${pos.lat.toFixed(7)})`;
                    });
                }
                const elCoords = document.getElementById('inp_coords');
                if (elCoords) elCoords.value = `POINT(${parsedLng.toFixed(7)} ${parsedLat.toFixed(7)})`;
                this.map.setView([parsedLat, parsedLng], 18);
            },

            openAdd: function() {
                const form = document.getElementById('form-rtlh');
                if (!form) return;
                
                form.reset();
                form.action = <?= json_encode(base_url('rtlh/store')) ?>;
                
                const title = document.getElementById('modal-rtlh-title');
                if (title) title.innerText = "Tambah Data Rumah";
                
                const nik = document.getElementById('inp_nik');
                if (nik) {
                    nik.readOnly = false;
                    nik.classList.remove('bg-slate-100', 'cursor-not-allowed', 'opacity-60');
                }
                
                // Clear photos
                ['foto_depan', 'foto_samping', 'foto_belakang', 'foto_dalam'].forEach(f => {
                    const prev = document.getElementById('prev_' + f);
                    const placeholder = document.getElementById('placeholder_' + f);
                    if (prev) prev.classList.add('hidden');
                    if (placeholder) placeholder.classList.remove('hidden');
                });

                if (this.marker && this.map) {
                    this.map.removeLayer(this.marker);
                    this.marker = null;
                }

                if (window.UI) UI.openModal('modal-rtlh');
            },

            openEdit: function(data) {
                if (!data) return;
                const r = data.r || {};
                const p = data.p || {};
                const c = data.c || {};
                
                if (!r || !r.id_survei) {
                    console.error('rtlhModal: Invalid or empty house data passed to openEdit', data);
                    alert('Gagal memuat data edit rumah. Silakan refresh halaman dan coba lagi.');
                    return;
                }
                
                const form = document.getElementById('form-rtlh');
                if (!form) return;
                
                form.reset();
                form.action = <?= json_encode(base_url('rtlh/update')) ?> + '/' + (r.id_survei || 0);
                
                const title = document.getElementById('modal-rtlh-title');
                if (title) title.innerText = "Perbarui Data Rumah";
                
                // Basic Fields
                const fields = {
                    'inp_nama': p.nama_kepala_keluarga || '',
                    'inp_nik': r.nik_pemilik || '',
                    'inp_no_kk': p.no_kk || '',
                    'inp_jk': p.jenis_kelamin || 'L',
                    'inp_jml_keluarga': p.jumlah_anggota_keluarga || '',
                    'inp_tempat_lahir': p.tempat_lahir || '',
                    'inp_tgl_lahir': p.tanggal_lahir || '',
                    'inp_pendidikan_id': p.pendidikan_id || '',
                    'inp_pekerjaan_id': p.pekerjaan_id || '',
                    'inp_penghasilan': p.penghasilan_per_bulan || '',
                    'inp_desa_id': r.desa_id || '',
                    'inp_desa_nama': r.desa || '',
                    'inp_luas_rumah': r.luas_rumah_m2 || '',
                    'inp_luas_lahan': r.luas_lahan_m2 || '',
                    'inp_alamat': r.alamat_detail || '',
                    'inp_coords': r.wkt || '',
                    'inp_milik_rumah': r.kepemilikan_rumah || '',
                    'inp_milik_tanah': r.kepemilikan_tanah || '',
                    'inp_kawasan': r.jenis_kawasan || '',
                    'inp_fungsi_ruang': r.fungsi_ruang || '',
                    'inp_listric': r.sumber_penerangan || '',
                    'inp_air': r.sumber_air_minum || '',
                    'inp_jarak_sam': r.jarak_sam_ke_tpa_tinja || '',
                    'inp_desil': r.desil_nasional || '',
                    'inp_status_backlog': r.status_backlog || 'TIDAK BACKLOG',
                    'inp_bab': r.kamar_mandi_dan_jamban || 'SENDIRI',
                    'inp_tpa': r.jenis_tpa_tinja || '',
                    'inp_status_bantuan': (r.status_bantuan === 'Belum Menerima' ? 'Rtlh' : (r.status_bantuan === 'Sudah Menerima' ? 'Rlh' : (r.status_bantuan || 'Unknown')))
                };

                for (const id in fields) {
                    const el = document.getElementById(id);
                    if (el) el.value = fields[id];
                }

                // NIK read-only on edit
                const nikEl = document.getElementById('inp_nik');
                if (nikEl) {
                    nikEl.readOnly = true;
                    nikEl.classList.add('bg-slate-100', 'cursor-not-allowed', 'opacity-60');
                }

                // Technical Fields
                const tech = ['st_pondasi', 'st_kolom', 'st_balok', 'st_sloof', 'st_rangka_atap', 'st_plafon', 'st_jendela', 'st_ventilasi', 'mat_atap', 'st_atap', 'mat_dinding', 'st_dinding', 'mat_lantai', 'st_lantai'];
                tech.forEach(f => {
                    const el = document.getElementById('inp_' + f);
                    if (el) el.value = c[f] || '';
                });

                // Photos
                ['foto_depan', 'foto_samping', 'foto_belakang', 'foto_dalam'].forEach(f => {
                    const prev = document.getElementById('prev_' + f);
                    const placeholder = document.getElementById('placeholder_' + f);
                    if (prev) {
                        if (r[f]) {
                            prev.src = this.uploadUrl + r[f];
                            prev.classList.remove('hidden');
                            if (placeholder) placeholder.classList.add('hidden');
                        } else {
                            prev.classList.add('hidden');
                            if (placeholder) placeholder.classList.remove('hidden');
                        }
                    }
                });

                if (window.UI) UI.openModal('modal-rtlh');
                
                if (r.wkt && typeof wellknown !== 'undefined') {
                    try {
                        const geo = wellknown.parse(r.wkt);
                        if (geo && geo.coordinates) {
                            setTimeout(() => this.setMarker(geo.coordinates[1], geo.coordinates[0]), 500);
                        }
                    } catch(e) { console.error('Map parse error:', e); }
                }
            },

            moveStep: function(delta) {
                const next = this.currentStep + delta;
                if (next >= 1 && next <= 3) this.showStep(next);
            },

            showStep: function(step) {
                this.currentStep = step;
                document.querySelectorAll('.modal-step').forEach(s => s.classList.add('hidden'));
                const targetStep = document.getElementById('step-rtlh-' + step);
                if (targetStep) targetStep.classList.remove('hidden');

                // Update Stepper UI
                document.querySelectorAll('.step-dot').forEach(dot => {
                    const dStep = parseInt(dot.dataset.step);
                    if (dStep === step) {
                        dot.className = "step-dot w-5 h-5 rounded-full bg-blue-600 text-[10px] font-black flex items-center justify-center text-white ring-4 ring-blue-500/20";
                        if (dot.nextElementSibling) dot.nextElementSibling.className = "text-[8px] font-bold uppercase tracking-widest text-white";
                        dot.innerHTML = dStep;
                    } else if (dStep < step) {
                        dot.className = "step-dot w-5 h-5 rounded-full bg-emerald-500 text-[10px] font-black flex items-center justify-center text-white";
                        if (dot.nextElementSibling) dot.nextElementSibling.className = "text-[8px] font-bold uppercase tracking-widest text-white/60";
                        dot.innerHTML = '✓';
                    } else {
                        dot.className = "step-dot w-5 h-5 rounded-full bg-white/10 text-[10px] font-black flex items-center justify-center text-white/40";
                        if (dot.nextElementSibling) dot.nextElementSibling.className = "text-[8px] font-bold uppercase tracking-widest text-white/40";
                        dot.innerHTML = dStep;
                    }
                });

                // Update Buttons
                const btnPrev = document.getElementById('btn-rtlh-prev');
                const btnNext = document.getElementById('btn-rtlh-next');
                const btnSave = document.getElementById('btn-rtlh-save');
                
                if (btnPrev) btnPrev.classList.toggle('hidden', step === 1);
                if (btnNext) btnNext.classList.toggle('hidden', step === 3);
                if (btnSave) btnSave.classList.toggle('hidden', step !== 3);
                
                if (step === 1 && this.map) setTimeout(() => this.map.invalidateSize(), 100);
            },

            previewImg: function(input, id) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const prev = document.getElementById('prev_' + id);
                        const placeholder = document.getElementById('placeholder_' + id);
                        if (prev) { prev.src = e.target.result; prev.classList.remove('hidden'); }
                        if (placeholder) placeholder.classList.add('hidden');
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }
        };

        // Initialize RTLH Modal
        document.addEventListener('DOMContentLoaded', () => {
            rtlhModal.init();
        });
    })();
</script>
