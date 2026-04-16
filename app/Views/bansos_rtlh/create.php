<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<!-- Leaflet Assets -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="max-w-4xl mx-auto pb-20">
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-xl overflow-hidden">
        <div class="p-8 border-b dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-emerald-900/20">
                    <i data-lucide="award" class="w-6 h-6"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-blue-950 dark:text-white uppercase tracking-tight">Input Realisasi Bansos</h2>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Catat keberhasilan perbaikan rumah sesuai database</p>
                </div>
            </div>
            <a href="<?= base_url('bansos-rtlh') ?>" class="px-5 py-2.5 bg-white dark:bg-slate-800 text-slate-500 rounded-xl text-[10px] font-bold uppercase tracking-widest border border-slate-200 dark:border-slate-700 hover:bg-slate-50 transition-all flex items-center gap-2">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Kembali
            </a>
        </div>

        <form action="<?= base_url('bansos-rtlh/store') ?>" method="POST" enctype="multipart/form-data" class="p-10 space-y-10">
            <?= csrf_field() ?>
            
            <!-- Pilihan Data RTLH (Optional linking) -->
            <div class="bg-blue-50/50 dark:bg-blue-950/20 p-6 rounded-3xl border border-blue-100/50 dark:border-blue-900/30">
                <label class="block text-[10px] font-bold text-blue-900 dark:text-blue-400 uppercase mb-3 tracking-widest flex items-center gap-2">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i> Hubungkan dengan Data Survei RTLH (Opsional)
                </label>
                <select name="id_survei" id="id_survei" onchange="fillFromRtlh(this)" class="w-full p-4 bg-white dark:bg-slate-900 border border-blue-200 dark:border-blue-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 outline-none transition-all font-bold text-sm shadow-sm">
                    <option value="">-- Input Manual (Bukan dari Data Survei) --</option>
                    <?php foreach($rtlh as $r): ?>
                        <option value="<?= $r['id_survei'] ?>" data-nik="<?= $r['nik'] ?>" data-nama="<?= $r['nama_kepala_keluarga'] ?>" data-desa="<?= $r['desa'] ?>">
                            [ID: <?= $r['id_survei'] ?>] <?= $r['nama_kepala_keluarga'] ?> - <?= $r['desa'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="mt-2 text-[9px] text-blue-600/60 font-medium italic">*Jika dipilih, status rumah tersebut akan otomatis menjadi RLH (Tuntas).</p>
            </div>

            <!-- Identitas Penerima -->
            <div class="space-y-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] flex items-center gap-3">
                    <span class="w-8 h-[2px] bg-slate-200 dark:bg-slate-800"></span> Identitas Penerima
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">NIK Penerima</label>
                        <input type="text" name="nik" id="nik" placeholder="Masukkan 16 digit NIK" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-white outline-none transition-all font-bold" required>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                        <input type="text" name="nama_penerima" id="nama_penerima" placeholder="Nama sesuai KTP" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-white outline-none transition-all font-bold" required>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Wilayah Desa</label>
                        <input type="text" name="desa" id="desa" placeholder="Nama Desa / Kelurahan" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-white outline-none transition-all font-bold" required>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Tahun Anggaran</label>
                        <input type="number" name="tahun_anggaran" value="<?= date('Y') ?>" min="2000" max="2099" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-white outline-none transition-all font-bold" required>
                    </div>
                </div>
            </div>

            <!-- Detail Program & Lokasi -->
            <div class="space-y-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] flex items-center gap-3">
                    <span class="w-8 h-[2px] bg-slate-200 dark:bg-slate-800"></span> Detail Program & Lokasi Realisasi
                </p>
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Sumber Dana / Nama Program</label>
                        <input type="text" name="sumber_dana" placeholder="Contoh: BSPS, APBD Sinjai, DAK Bidang Perumahan" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-white outline-none transition-all font-bold" required>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Koordinat Realisasi (Map Picker)</label>
                        <div class="rounded-[2rem] overflow-hidden border border-slate-200 dark:border-slate-800 shadow-inner">
                            <div id="map" class="w-full h-72 z-10"></div>
                        </div>
                        <input type="text" name="lokasi_realisasi" id="lokasi_realisasi" placeholder="POINT(lng lat)" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl font-mono text-xs font-bold text-emerald-600 outline-none" readonly>
                        <p class="text-[9px] text-slate-400 italic ml-2">*Geser marker pada peta untuk menentukan lokasi tepat rumah yang telah diperbaiki.</p>
                    </div>
                </div>
            </div>

            <!-- Dokumentasi Realisasi -->
            <div class="space-y-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] flex items-center gap-3">
                    <span class="w-8 h-[2px] bg-slate-200 dark:bg-slate-800"></span> Dokumentasi Realisasi (Before & After)
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Foto Kondisi Awal (Before)</label>
                        <div class="relative group aspect-video bg-slate-50 dark:bg-slate-950 border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-2xl flex flex-col items-center justify-center overflow-hidden transition-all hover:border-emerald-500/50">
                            <input type="file" name="foto_before" accept="image/*" onchange="previewFile(this, 'preview-foto_before')" class="absolute inset-0 opacity-0 z-10 cursor-pointer">
                            <div id="placeholder-foto_before" class="flex flex-col items-center justify-center">
                                <i data-lucide="camera" class="w-6 h-6 text-slate-300 mb-2"></i>
                                <span class="text-[9px] font-bold text-slate-400 uppercase">Pilih Foto Sebelum</span>
                            </div>
                            <img id="preview-foto_before" class="absolute inset-0 w-full h-full object-cover hidden">
                        </div>
                        <p class="text-[8px] text-slate-400 italic ml-1">*Jika dihubungkan ke data survei, sistem akan otomatis mengambil foto dari database RTLH.</p>
                    </div>

                    <div class="space-y-3">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Foto Hasil Perbaikan (After)</label>
                        <div class="relative group aspect-video bg-slate-50 dark:bg-slate-950 border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-2xl flex flex-col items-center justify-center overflow-hidden transition-all hover:border-emerald-500/50">
                            <input type="file" name="foto_after" accept="image/*" onchange="previewFile(this, 'preview-foto_after')" class="absolute inset-0 opacity-0 z-10 cursor-pointer">
                            <div id="placeholder-foto_after" class="flex flex-col items-center justify-center">
                                <i data-lucide="camera" class="w-6 h-6 text-slate-300 mb-2"></i>
                                <span class="text-[9px] font-bold text-slate-400 uppercase">Pilih Foto Sesudah</span>
                            </div>
                            <img id="preview-foto_after" class="absolute inset-0 w-full h-full object-cover hidden">
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Keterangan Tambahan</label>
                <textarea name="keterangan" rows="3" placeholder="Informasi tambahan mengenai proses pembangunan atau kendala..." class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-white outline-none transition-all font-bold"></textarea>
            </div>

            <div class="pt-6">
                <button type="submit" class="w-full py-5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-[2rem] text-sm font-bold uppercase tracking-[0.2em] shadow-xl shadow-emerald-900/20 transition-all active:scale-[0.98] flex items-center justify-center gap-3">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                    Simpan & Update Status RTLH
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function fillFromRtlh(select) {
        const option = select.options[select.selectedIndex];
        if (option.value) {
            document.getElementById('nik').value = option.getAttribute('data-nik');
            document.getElementById('nama_penerima').value = option.getAttribute('data-nama');
            document.getElementById('desa').value = option.getAttribute('data-desa');
            
            // Lock inputs to prevent inconsistency if linked
            document.getElementById('nik').readOnly = true;
            document.getElementById('nama_penerima').readOnly = true;
            document.getElementById('desa').readOnly = true;
            document.getElementById('nik').classList.add('opacity-60');
            document.getElementById('nama_penerima').classList.add('opacity-60');
            document.getElementById('desa').classList.add('opacity-60');
        } else {
            document.getElementById('nik').value = '';
            document.getElementById('nama_penerima').value = '';
            document.getElementById('desa').value = '';
            
            document.getElementById('nik').readOnly = false;
            document.getElementById('nama_penerima').readOnly = false;
            document.getElementById('desa').readOnly = false;
            document.getElementById('nik').classList.remove('opacity-60');
            document.getElementById('nama_penerima').classList.remove('opacity-60');
            document.getElementById('desa').classList.remove('opacity-60');
        }
    }

    function previewFile(input, previewId) {
        const preview = document.getElementById(previewId);
        const placeholder = document.getElementById('placeholder-' + input.name);
        const file = input.files[0];
        const reader = new FileReader();

        reader.onloadend = function () {
            preview.src = reader.result;
            preview.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = "";
            preview.classList.add('hidden');
            if (placeholder) placeholder.classList.remove('hidden');
        }
    }

    // Map Initialization
    let map, marker;
    function initMap() {
        const defaultLat = -5.1245; 
        const defaultLng = 120.2536;

        const isDark = document.documentElement.classList.contains('dark');
        const cartoDB = L.tileLayer(isDark ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { 
            attribution: '&copy; CartoDB' 
        });
        const googleSat = L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains:['mt0','mt1','mt2','mt3'],
            attribution: '&copy; Google'
        });

        map = L.map('map', {
            center: [defaultLat, defaultLng],
            zoom: 11,
            layers: [cartoDB],
            zoomControl: false
        });

        let rot = 0;
        const LayerToggle = L.Control.extend({
            onAdd: function(map) {
                const btn = L.DomUtil.create('button', 'bg-white dark:bg-slate-900 rounded-lg shadow-xl border border-slate-100 dark:border-slate-800 transition-all duration-300 active:scale-90 mt-2 flex items-center justify-center');
                btn.type = 'button';
                btn.style.width = '38px'; btn.style.height = '38px'; btn.style.cursor = 'pointer';
                const isDark = document.documentElement.classList.contains('dark');
                const svgColor = isDark ? '#60a5fa' : '#2563eb';
                btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="${svgColor}" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:block; transition: transform 0.8s cubic-bezier(0.65, 0, 0.35, 1);"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>`;
                L.DomEvent.disableClickPropagation(btn);
                L.DomEvent.on(btn, 'click', function(e) {
                    L.DomEvent.stopPropagation(e);
                    L.DomEvent.preventDefault(e);
                    rot += 360;
                    const svg = btn.querySelector('svg');
                    svg.style.transform = `rotate(${rot}deg)`;
                    setTimeout(() => {
                        if (map.hasLayer(cartoDB)) { 
                            map.removeLayer(cartoDB); 
                            map.addLayer(googleSat); 
                            btn.style.backgroundColor = '#2563eb'; 
                            svg.setAttribute('stroke', '#ffffff'); 
                        }
                        else { 
                            map.removeLayer(googleSat); 
                            map.addLayer(cartoDB); 
                            btn.style.backgroundColor = isDark ? '#0f172a' : '#ffffff'; 
                            svg.setAttribute('stroke', svgColor); 
                        }
                    }, 200);
                });
                return btn;
            }
        });
        map.addControl(new LayerToggle({ position: 'topright' }));

        L.control.zoom({ position: 'bottomright' }).addTo(map);

        marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

        marker.on('dragend', function (e) {
            const latlng = marker.getLatLng();
            updateCoords(latlng.lat, latlng.lng);
        });

        map.on('click', function (e) {
            marker.setLatLng(e.latlng);
            updateCoords(e.latlng.lat, e.latlng.lng);
        });

        // Try to get current location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(pos => {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                map.setView([lat, lng], 16);
                marker.setLatLng([lat, lng]);
                updateCoords(lat, lng);
            });
        }
    }

    function updateCoords(lat, lng) {
        document.getElementById('lokasi_realisasi').value = `POINT(${lng} ${lat})`;
    }

    window.addEventListener('load', initMap);
</script>
<?= $this->endSection() ?>
