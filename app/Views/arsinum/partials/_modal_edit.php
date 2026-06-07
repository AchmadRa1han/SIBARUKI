<!-- ARSINUM MODAL COMPONENT -->
<div id="modal-arsinum" class="fixed inset-0 z-[10002] hidden overflow-y-auto py-10 px-4">
    <div class="fixed inset-0 bg-blue-950/60 backdrop-blur-sm transition-opacity" onclick="UI.closeModal('modal-arsinum')"></div>
    <div class="relative flex items-center justify-center p-4 min-h-full">
        <div class="bg-white dark:bg-slate-900 w-full max-w-4xl rounded-[2.5rem] shadow-2xl overflow-hidden animate-in zoom-in duration-300">
            <!-- Modal Header -->
            <div class="p-8 bg-blue-950 text-white flex justify-between items-center border-b border-white/10 sticky top-0 z-30">
                <div class="flex items-center gap-5">
                    <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-md border border-white/10">
                        <i data-lucide="droplets" class="w-6 h-6 text-blue-400"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black uppercase tracking-tighter" id="modal-arsinum-title">Data Arsinum</h3>
                        <p class="text-[8px] font-bold uppercase tracking-widest text-white/60 mt-1">Registri Aset Air Siap Minum</p>
                    </div>
                </div>
                <button type="button" onclick="UI.closeModal('modal-arsinum')" class="p-2 hover:bg-white/10 rounded-xl transition-colors"><i data-lucide="x" class="w-6 h-6"></i></button>
            </div>

            <form id="form-arsinum" action="<?= base_url('arsinum/store') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8 bg-slate-100 dark:bg-slate-950">
                    <div class="md:col-span-2">
                        <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Jenis Pekerjaan / Nama Objek</label>
                        <input type="text" name="jenis_pekerjaan" id="inp_ars_jenis" required class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase" placeholder="PEMBANGUNAN ARSINUM...">
                    </div>
                    
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Volume / Kapasitas</label>
                        <input type="text" name="volume" id="inp_ars_volume" required class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase" placeholder="1 UNIT">
                    </div>
                    
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Tahun Anggaran</label>
                        <input type="number" name="tahun" id="inp_ars_tahun" value="<?= date('Y') ?>" required class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold text-sm">
                    </div>

                    <div class="md:col-span-2 bg-blue-50 dark:bg-blue-900/20 p-6 rounded-2xl border border-blue-100 dark:border-blue-900/50">
                        <label class="block text-[8px] font-bold text-blue-900 dark:text-blue-400 uppercase mb-2 tracking-widest ml-1">Total Pagu Anggaran (Rp)</label>
                        <input type="number" name="anggaran" id="inp_ars_anggaran" required class="w-full bg-transparent border-none text-xl font-bold text-blue-950 dark:text-white p-0 focus:ring-0 outline-none placeholder:opacity-20" placeholder="0">
                    </div>

                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Sumber Dana</label>
                        <input type="text" name="sumber_dana" id="inp_ars_sumber" class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase" placeholder="APBD / DAK / DLL">
                    </div>

                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Pelaksana / Kontraktor</label>
                        <input type="text" name="pelaksana" id="inp_ars_pelaksana" class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                    </div>

                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Kecamatan</label>
                        <input type="text" name="kecamatan" id="inp_ars_kecamatan" required class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                    </div>

                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Desa / Kelurahan</label>
                        <input type="text" name="desa" id="inp_ars_desa" required class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                    </div>

                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Koordinat (Latitude, Longitude)</label>
                        <input type="text" name="koordinat" id="inp_ars_koordinat" class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-mono text-xs" placeholder="-5.123, 120.123">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Dokumentasi Foto (Setelah Selesai)</label>
                        <div class="relative group">
                            <input type="file" name="foto_after" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 z-10 cursor-pointer" onchange="arsinumModal.previewImg(this)">
                            <div id="ars_preview_box" class="w-full h-48 bg-white dark:bg-slate-900 border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-2xl flex flex-col items-center justify-center overflow-hidden transition-all group-hover:border-blue-600 group-hover:bg-blue-50/5">
                                <img id="ars_preview_img" class="hidden w-full h-full object-cover">
                                <div id="ars_preview_placeholder" class="text-center">
                                    <i data-lucide="image-plus" class="w-8 h-8 text-slate-300 mb-1.5 mx-auto"></i>
                                    <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Unggah Foto Sesudah (After)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="p-6 bg-slate-50 dark:bg-slate-900 border-t dark:border-slate-800 flex justify-end gap-3 rounded-b-[2.5rem]">
                    <button type="button" onclick="UI.closeModal('modal-arsinum')" class="px-6 py-2.5 text-slate-500 font-bold uppercase tracking-widest text-[10px] hover:text-rose-500 transition-colors">Batal</button>
                    <button type="submit" id="btn-ars-submit" class="px-8 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-black uppercase tracking-widest text-[10px] shadow-lg shadow-blue-600/20 active:scale-95 transition-all flex items-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i> Simpan Aset
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    (function() {
        const ARSINUM_UPLOAD_URL = <?= json_encode(base_url('uploads/arsinum/')) ?>;
        
        window.arsinumModal = {
            uploadUrl: ARSINUM_UPLOAD_URL.endsWith('/') ? ARSINUM_UPLOAD_URL : ARSINUM_UPLOAD_URL + '/',

            openAdd: function() {
                const form = document.getElementById('form-arsinum');
                if (!form) return;
                form.reset();
                form.action = <?= json_encode(base_url('arsinum/store')) ?>;
                
                document.getElementById('modal-arsinum-title').innerText = "Tambah Data Arsinum";
                document.getElementById('ars_preview_img').classList.add('hidden');
                document.getElementById('ars_preview_placeholder').classList.remove('hidden');
                
                const btn = document.getElementById('btn-ars-submit');
                if (btn) {
                    btn.className = "px-8 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-black uppercase tracking-widest text-[10px] shadow-lg shadow-emerald-600/20 active:scale-95 transition-all flex items-center gap-2";
                    btn.innerHTML = '<i data-lucide="plus-circle" class="w-4 h-4"></i> Tambah Baru';
                }

                if (window.UI) UI.openModal('modal-arsinum');
            },

            openEdit: function(data) {
                if (!data) return;
                const form = document.getElementById('form-arsinum');
                if (!form) return;
                form.reset();
                form.action = <?= json_encode(base_url('arsinum/update')) ?> + '/' + (data.id || 0);
                
                document.getElementById('modal-arsinum-title').innerText = "Edit Data Arsinum";
                
                const fields = {
                    'inp_ars_jenis': data.jenis_pekerjaan,
                    'inp_ars_volume': data.volume,
                    'inp_ars_tahun': data.tahun,
                    'inp_ars_anggaran': data.anggaran,
                    'inp_ars_sumber': data.sumber_dana,
                    'inp_ars_pelaksana': data.pelaksana,
                    'inp_ars_kecamatan': data.kecamatan,
                    'inp_ars_desa': data.desa,
                    'inp_ars_koordinat': data.koordinat
                };

                for (const id in fields) {
                    const el = document.getElementById(id);
                    if (el) el.value = fields[id] || '';
                }

                const img = document.getElementById('ars_preview_img');
                const ph = document.getElementById('ars_preview_placeholder');
                if (data.foto_after) {
                    img.src = this.uploadUrl + data.foto_after;
                    img.classList.remove('hidden');
                    ph.classList.add('hidden');
                } else {
                    img.classList.add('hidden');
                    ph.classList.remove('hidden');
                }

                const btn = document.getElementById('btn-ars-submit');
                if (btn) {
                    btn.className = "px-8 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-black uppercase tracking-widest text-[10px] shadow-lg shadow-blue-600/20 active:scale-95 transition-all flex items-center gap-2";
                    btn.innerHTML = '<i data-lucide="save" class="w-4 h-4"></i> Simpan Perubahan';
                }

                if (window.UI) UI.openModal('modal-arsinum');
            },

            previewImg: function(input) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.getElementById('ars_preview_img');
                        const ph = document.getElementById('ars_preview_placeholder');
                        img.src = e.target.result;
                        img.classList.remove('hidden');
                        ph.classList.add('hidden');
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }
        };
    })();
</script>
