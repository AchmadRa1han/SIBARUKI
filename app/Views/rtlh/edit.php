<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-5xl mx-auto pb-20">
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-300">
        <!-- HEADER -->
        <div class="p-8 border-b dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-black text-blue-950 dark:text-white tracking-tight uppercase tracking-wider">Edit Data RTLH Terpadu</h1>
                <p class="text-sm text-slate-400 dark:text-slate-500 font-medium italic">ID Survei: #<?= str_pad($rumah['id_survei'], 4, '0', STR_PAD_LEFT) ?></p>
            </div>
            <a href="<?= base_url('rtlh/detail/' . $rumah['id_survei']) ?>" class="text-slate-400 dark:text-slate-600 hover:text-rose-500 transition-colors">
                <i data-lucide="x-circle" class="w-8 h-8"></i>
            </a>
        </div>

        <form action="<?= base_url('rtlh/update/' . $rumah['id_survei']) ?>" method="post" class="p-10">
            <?= csrf_field() ?>

            <div class="space-y-12">
                <!-- BAGIAN 1: IDENTITAS PENERIMA -->
                <div>
                    <h3 class="text-[10px] font-black text-amber-600 dark:text-amber-500 uppercase tracking-[0.3em] mb-8 flex items-center">
                        <i data-lucide="user" class="w-4 h-4 mr-3"></i> I. Data Penerima (Pemilik)
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">NIK</label>
                            <input type="text" name="nik" value="<?= old('nik', $penerima['nik']) ?>" maxlength="16" required class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 transition-all font-mono">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Nomor KK</label>
                            <input type="text" name="no_kk" value="<?= old('no_kk', $penerima['no_kk']) ?>" maxlength="16" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 transition-all font-mono">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Nama Kepala Keluarga</label>
                            <input type="text" name="nama_kepala_keluarga" value="<?= old('nama_kepala_keluarga', $penerima['nama_kepala_keluarga']) ?>" required class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" value="<?= old('tempat_lahir', $penerima['tempat_lahir']) ?>" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl outline-none dark:text-slate-200">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" value="<?= old('tanggal_lahir', $penerima['tanggal_lahir']) ?>" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl outline-none dark:text-slate-200">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl outline-none dark:text-slate-200 appearance-none">
                                <option value="L" <?= $penerima['jenis_kelamin'] == 'L' ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="P" <?= $penerima['jenis_kelamin'] == 'P' ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Pendidikan</label>
                            <select name="pendidikan_id" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl outline-none dark:text-slate-200 appearance-none">
                                <?php foreach(($master['PENDIDIKAN'] ?? []) as $p): ?>
                                    <option value="<?= $p['id'] ?>" <?= $p['id'] == $penerima['pendidikan_id'] ? 'selected' : '' ?>><?= $p['nama_pilihan'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Pekerjaan</label>
                            <select name="pekerjaan_id" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl outline-none dark:text-slate-200 appearance-none">
                                <?php foreach(($master['PEKERJAAN'] ?? []) as $p): ?>
                                    <option value="<?= $p['id'] ?>" <?= $p['id'] == $penerima['pekerjaan_id'] ? 'selected' : '' ?>><?= $p['nama_pilihan'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Jml Anggota Keluarga</label>
                            <input type="number" name="jumlah_anggota_keluarga" value="<?= old('jumlah_anggota_keluarga', $penerima['jumlah_anggota_keluarga']) ?>" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl outline-none dark:text-slate-200">
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Penghasilan / Bulan</label>
                            <select name="penghasilan_per_bulan" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 dark:text-slate-200 appearance-none">
                                <option value="0 - 1,2 juta" <?= $penerima['penghasilan_per_bulan'] == '0 - 1,2 juta' ? 'selected' : '' ?>>0 - 1,2 juta</option>
                                <option value="1,3 - 2,5 juta" <?= $penerima['penghasilan_per_bulan'] == '1,3 - 2,5 juta' ? 'selected' : '' ?>>1,3 - 2,5 juta</option>
                                <option value="2,6 - 4,5 juta" <?= $penerima['penghasilan_per_bulan'] == '2,6 - 4,5 juta' ? 'selected' : '' ?>>2,6 - 4,5 juta</option>
                                <option value="> 4,5 juta" <?= $penerima['penghasilan_per_bulan'] == '> 4,5 juta' ? 'selected' : '' ?>>> 4,5 juta</option>
                            </select>
                        </div>
                    </div>
                </div>

                <hr class="border-slate-100 dark:border-slate-800">

                <!-- BAGIAN 2: DETAIL RUMAH -->
                <div>
                    <h3 class="text-[10px] font-black text-amber-600 dark:text-amber-500 uppercase tracking-[0.3em] mb-8 flex items-center">
                        <i data-lucide="home" class="w-4 h-4 mr-3"></i> II. Profil Rumah & Lingkungan
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Nama Desa</label>
                            <input type="text" name="desa" value="<?= old('desa', $rumah['desa']) ?>" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl outline-none dark:text-slate-200">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Desa ID</label>
                            <input type="number" name="desa_id" value="<?= old('desa_id', $rumah['desa_id']) ?>" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl outline-none dark:text-slate-200">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Jenis Kawasan</label>
                            <input type="text" name="jenis_kawasan" value="<?= old('jenis_kawasan', $rumah['jenis_kawasan']) ?>" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl outline-none dark:text-slate-200">
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Alamat Detail</label>
                            <textarea name="alamat_detail" rows="2" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl outline-none dark:text-slate-200"><?= old('alamat_detail', $rumah['alamat_detail']) ?></textarea>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Kepemilikan Rumah</label>
                            <input type="text" name="kepemilikan_rumah" value="<?= old('kepemilikan_rumah', $rumah['kepemilikan_rumah']) ?>" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl outline-none dark:text-slate-200">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Kepemilikan Tanah</label>
                            <input type="text" name="kepemilikan_tanah" value="<?= old('kepemilikan_tanah', $rumah['kepemilikan_tanah']) ?>" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl outline-none dark:text-slate-200">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Fungsi Ruang</label>
                            <input type="text" name="fungsi_ruang" value="<?= old('fungsi_ruang', $rumah['fungsi_ruang']) ?>" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl outline-none dark:text-slate-200">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Aset Rumah Lain</label>
                            <input type="text" name="aset_rumah_di_lokasi_lain" value="<?= old('aset_rumah_di_lokasi_lain', $rumah['aset_rumah_di_lokasi_lain']) ?>" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl outline-none dark:text-slate-200">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Bantuan Perumahan</label>
                            <input type="text" name="bantuan_perumahan" value="<?= old('bantuan_perumahan', $rumah['bantuan_perumahan']) ?>" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl outline-none dark:text-slate-200">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Jumlah Penghuni</label>
                            <input type="number" name="jumlah_penghuni_jiwa" value="<?= old('jumlah_penghuni_jiwa', $rumah['jumlah_penghuni_jiwa']) ?>" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl outline-none dark:text-slate-200">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Luas Rumah (m2)</label>
                            <input type="number" name="luas_rumah_m2" value="<?= old('luas_rumah_m2', $rumah['luas_rumah_m2']) ?>" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl outline-none dark:text-slate-200">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Luas Lahan (m2)</label>
                            <input type="number" name="luas_lahan_m2" value="<?= old('luas_lahan_m2', $rumah['luas_lahan_m2']) ?>" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl outline-none dark:text-slate-200">
                        </div>
                    </div>
                </div>

                <hr class="border-slate-100 dark:border-slate-800">

                <!-- BAGIAN 3: FASILITAS & SANITASI -->
                <div>
                    <h3 class="text-[10px] font-black text-amber-600 dark:text-amber-500 uppercase tracking-[0.3em] mb-8 flex items-center">
                        <i data-lucide="droplets" class="w-4 h-4 mr-3"></i> III. Fasilitas & Sanitasi
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Sumber Penerangan</label>
                            <select name="sumber_penerangan" id="sumber_penerangan" onchange="togglePenerangan()" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl outline-none dark:text-slate-200 appearance-none font-bold">
                                <option value="Tidak Ada" <?= $rumah['sumber_penerangan'] == 'Tidak Ada' ? 'selected' : '' ?>>TIDAK ADA</option>
                                <option value="Ada" <?= $rumah['sumber_penerangan'] == 'Ada' ? 'selected' : '' ?>>ADA</option>
                            </select>
                        </div>
                        <div id="detail_penerangan_wrapper" class="<?= $rumah['sumber_penerangan'] == 'Ada' ? '' : 'hidden' ?>">
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Detail Sumber</label>
                            <select name="sumber_penerangan_detail" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl outline-none dark:text-slate-200 appearance-none">
                                <option value="PLN dengan meteran" <?= $rumah['sumber_penerangan_detail'] == 'PLN dengan meteran' ? 'selected' : '' ?>>PLN dengan meteran</option>
                                <option value="PLN tanpa meteran" <?= $rumah['sumber_penerangan_detail'] == 'PLN tanpa meteran' ? 'selected' : '' ?>>PLN tanpa meteran</option>
                                <option value="Bukan PLN" <?= $rumah['sumber_penerangan_detail'] == 'Bukan PLN' ? 'selected' : '' ?>>Bukan PLN</option>
                                <option value="Bukan Listrik" <?= $rumah['sumber_penerangan_detail'] == 'Bukan Listrik' ? 'selected' : '' ?>>Bukan Listrik</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Sumber Air Minum</label>
                            <input type="text" name="sumber_air_minum" value="<?= old('sumber_air_minum', $rumah['sumber_air_minum']) ?>" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl outline-none dark:text-slate-200">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Jarak SAM ke TPA Tinja</label>
                            <input type="text" name="jarak_sam_ke_tpa_tinja" value="<?= old('jarak_sam_ke_tpa_tinja', $rumah['jarak_sam_ke_tpa_tinja']) ?>" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl outline-none dark:text-slate-200">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Kamar Mandi & Jamban</label>
                            <input type="text" name="kamar_mandi_dan_jamban" value="<?= old('kamar_mandi_dan_jamban', $rumah['kamar_mandi_dan_jamban']) ?>" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl outline-none dark:text-slate-200">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Jenis Jamban / Kloset</label>
                            <input type="text" name="jenis_jamban_kloset" value="<?= old('jenis_jamban_kloset', $rumah['jenis_jamban_kloset']) ?>" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl outline-none dark:text-slate-200">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Jenis TPA Tinja</label>
                            <input type="text" name="jenis_tpa_tinja" value="<?= old('jenis_tpa_tinja', $rumah['jenis_tpa_tinja']) ?>" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl outline-none dark:text-slate-200">
                        </div>
                        <div class="md:col-span-3 space-y-4">
                            <label class="block text-[10px] font-black text-amber-900 dark:text-amber-500 uppercase mb-2 tracking-widest flex items-center gap-2">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5"></i> Perbarui Lokasi pada Peta (Click-to-Pin)
                            </label>
                            <div class="relative group">
                                <div class="absolute -inset-1 bg-gradient-to-r from-amber-600 to-orange-600 rounded-[2.5rem] blur opacity-10 transition duration-1000"></div>
                                <div class="relative bg-white dark:bg-slate-900 rounded-[2.5rem] overflow-hidden shadow-2xl border border-slate-100 dark:border-slate-800">
                                    <div id="map-picker" class="w-full h-80 z-10" style="min-height: 320px; background: #ececec;"></div>
                                    
                                    <!-- GPS Button -->
                                    <button type="button" onclick="getLocation()" class="absolute top-4 right-4 z-[1000] p-3 bg-white dark:bg-slate-800 text-blue-600 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-700 hover:bg-blue-600 hover:text-white transition-all active:scale-90 flex items-center gap-2 font-black text-[9px] uppercase tracking-widest">
                                        <i data-lucide="crosshair" class="w-4 h-4"></i> Lokasi Saya
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Lokasi Koordinat (WKT)</label>
                            <input type="text" name="lokasi_koordinat" id="lokasi_koordinat" value="<?= old('lokasi_koordinat', $rumah['lokasi_koordinat']) ?>" placeholder="POINT(X Y)" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl outline-none font-mono text-xs dark:text-blue-400" readonly>
                        </div>
                    </div>
                </div>

                <hr class="border-slate-100 dark:border-slate-800">

                <!-- BAGIAN 4: KONDISI FISIK -->
...
</div>

<!-- Leaflet & Script -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    let map, marker;

    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(initMapPicker, 500);
    });

    function parseWKT(wkt) {
        if (!wkt || typeof wkt !== 'string') return null;
        const match = wkt.match(/POINT\s*\(\s*([-\d.]+)\s+([-\d.]+)\s*\)/i);
        if (match) {
            return { lng: parseFloat(match[1]), lat: parseFloat(match[2]) };
        }
        return null;
    }

    function initMapPicker() {
        const isDark = document.documentElement.classList.contains('dark');
        const standard = L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { attribution: '&copy; Sibaruki' });
        const satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: '&copy; Esri' });

        const initialWkt = document.getElementById('lokasi_koordinat').value;
        const coords = parseWKT(initialWkt);
        
        map = L.map('map-picker', { 
            zoomControl: false, 
            layers: [satellite] 
        }).setView(coords ? [coords.lat, coords.lng] : [-5.1245, 120.2536], coords ? 18 : 15);

        L.control.zoom({ position: 'topright' }).addTo(map);

        if (coords) {
            marker = L.marker([coords.lat, coords.lng], { draggable: true }).addTo(map);
            marker.on('dragend', function(e) {
                const pos = e.target.getLatLng();
                updateInput(pos.lat, pos.lng);
            });
        }

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

    function togglePenerangan() {
        const val = document.getElementById('sumber_penerangan').value;
        const wrapper = document.getElementById('detail_penerangan_wrapper');
        if (val === 'Ada') { wrapper.classList.remove('hidden'); } 
        else { wrapper.classList.add('hidden'); }
    }
</script>
<?= $this->endSection() ?>
                <div>
                    <h3 class="text-[10px] font-black text-amber-600 dark:text-amber-500 uppercase tracking-[0.3em] mb-8 flex items-center">
                        <i data-lucide="shield-check" class="w-4 h-4 mr-3"></i> IV. Kondisi Fisik Bangunan
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
                        <?php 
                            $komponen = [
                                'st_pondasi' => 'Pondasi', 'st_kolom' => 'Kolom', 'st_balok' => 'Balok', 
                                'st_sloof' => 'Sloof', 'st_rangka_atap' => 'Rangka Atap', 
                                'st_plafon' => 'Plafon', 'st_jendela' => 'Jendela', 'st_ventilasi' => 'Ventilasi'
                            ];
                            foreach($komponen as $key => $label) : 
                        ?>
                            <div>
                                <label class="block text-[9px] font-black text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest"><?= $label ?></label>
                                <select name="<?= $key ?>" class="w-full p-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-bold dark:text-slate-200 outline-none appearance-none">
                                    <option value="">Pilih Kondisi</option>
                                    <?php foreach(($master['KONDISI'] ?? []) as $rk) : ?>
                                        <option value="<?= $rk['id'] ?>" <?= $rk['id'] == ($kondisi[$key] ?? '') ? 'selected' : '' ?>><?= $rk['nama_pilihan'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <?php foreach(['atap' => 'ATAP', 'dinding' => 'DINDING', 'lantai' => 'LANTAI'] as $k => $l): ?>
                        <div class="p-6 bg-slate-50 dark:bg-slate-950/50 border border-slate-100 dark:border-slate-800 rounded-3xl transition-colors duration-300">
                            <p class="text-[10px] font-black text-slate-800 dark:text-slate-200 mb-5 border-b dark:border-slate-800 pb-3 uppercase tracking-widest"><?= $l ?></p>
                            <div class="space-y-5">
                                <div>
                                    <label class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest block mb-1">Material</label>
                                    <select name="mat_<?= $k ?>" class="w-full p-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-bold dark:text-slate-200 outline-none appearance-none">
                                        <?php foreach(($master['MATERIAL_'.strtoupper($k)] ?? []) as $m): ?>
                                            <option value="<?= $m['id'] ?>" <?= $m['id'] == ($kondisi['mat_'.$k] ?? '') ? 'selected' : '' ?>><?= $m['nama_pilihan'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest block mb-1">Kondisi</label>
                                    <select name="st_<?= $k ?>" class="w-full p-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-bold dark:text-slate-200 outline-none appearance-none">
                                        <?php foreach(($master['KONDISI'] ?? []) as $rk): ?>
                                            <option value="<?= $rk['id'] ?>" <?= $rk['id'] == ($kondisi['st_'.$k] ?? '') ? 'selected' : '' ?>><?= $rk['nama_pilihan'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- FOOTER: TOMBOL AKSI -->
            <div class="mt-12 flex items-center justify-end space-x-6 border-t dark:border-slate-800 pt-10">
                <a href="<?= base_url('rtlh/detail/' . $rumah['id_survei']) ?>" class="text-sm font-bold text-slate-400 dark:text-slate-600 hover:text-slate-600 dark:hover:text-slate-400 transition-colors">Batal</a>
                <button type="submit" class="bg-amber-500 dark:bg-amber-600 hover:bg-amber-600 dark:hover:bg-amber-500 text-white px-12 py-4 rounded-2xl font-black shadow-xl shadow-amber-200 dark:shadow-none transition-all flex items-center space-x-3 text-lg">
                    <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                    <span>Perbarui Data Terpadu</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    lucide.createIcons();
    function togglePenerangan() {
        const val = document.getElementById('sumber_penerangan').value;
        const wrapper = document.getElementById('detail_penerangan_wrapper');
        if (val === 'Ada') { wrapper.classList.remove('hidden'); } 
        else { wrapper.classList.add('hidden'); }
    }
</script>
<?= $this->endSection() ?>
