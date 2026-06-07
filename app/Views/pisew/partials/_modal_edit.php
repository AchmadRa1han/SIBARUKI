<!-- PISEW MODAL COMPONENT -->
<div id="modal-pisew" class="fixed inset-0 z-[10002] hidden overflow-y-auto py-10 px-4">
    <div class="fixed inset-0 bg-blue-950/60 backdrop-blur-sm transition-opacity" onclick="UI.closeModal('modal-pisew')"></div>
    <div class="relative flex items-center justify-center p-4 min-h-full">
        <div class="bg-white dark:bg-slate-900 w-full max-w-4xl rounded-[2.5rem] shadow-2xl overflow-hidden animate-in zoom-in duration-300">
            <!-- Modal Header -->
            <div class="p-8 bg-blue-950 text-white flex justify-between items-center border-b border-white/10 sticky top-0 z-30">
                <div class="flex items-center gap-5">
                    <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-md border border-white/10">
                        <i data-lucide="map" class="w-6 h-6 text-indigo-400"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black uppercase tracking-tighter" id="modal-pisew-title">Data PISEW</h3>
                        <p class="text-[8px] font-bold uppercase tracking-widest text-white/60 mt-1">Registri Program Pengembangan Infrastruktur</p>
                    </div>
                </div>
                <button type="button" onclick="UI.closeModal('modal-pisew')" class="p-2 hover:bg-white/10 rounded-xl transition-colors"><i data-lucide="x" class="w-6 h-6"></i></button>
            </div>

            <form id="form-pisew" action="<?= base_url('pisew/store') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8 bg-slate-100 dark:bg-slate-950">
                    <div class="md:col-span-2">
                        <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Jenis Pekerjaan / Nama Kegiatan</label>
                        <input type="text" name="jenis_pekerjaan" id="inp_pis_jenis" required class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase" placeholder="PEMBANGUNAN JALAN DESA...">
                    </div>
                    
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Wilayah Kecamatan</label>
                        <input type="text" name="kecamatan" id="inp_pis_kecamatan" required class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                    </div>

                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Lokasi Desa</label>
                        <input type="text" name="lokasi_desa" id="inp_pis_desa" required class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                    </div>

                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Tahun Anggaran</label>
                        <input type="number" name="tahun" id="inp_pis_tahun" value="<?= date('Y') ?>" required class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold text-sm">
                    </div>

                    <div class="bg-indigo-50 dark:bg-indigo-900/20 p-6 rounded-2xl border border-indigo-100 dark:border-indigo-900/50">
                        <label class="block text-[8px] font-bold text-indigo-900 dark:text-indigo-400 uppercase mb-2 tracking-widest ml-1">Total Pagu Anggaran (Rp)</label>
                        <input type="number" name="anggaran" id="inp_pis_anggaran" required class="w-full bg-transparent border-none text-xl font-bold text-indigo-950 dark:text-white p-0 focus:ring-0 outline-none placeholder:opacity-20" placeholder="0">
                    </div>

                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Sumber Dana</label>
                        <input type="text" name="sumber_dana" id="inp_pis_sumber" class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase" placeholder="APBN / DAK / DLL">
                    </div>

                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Pelaksana</label>
                        <input type="text" name="pelaksana" id="inp_pis_pelaksana" class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Koordinat (Latitude, Longitude)</label>
                        <input type="text" name="koordinat" id="inp_pis_koordinat" class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-mono text-xs" placeholder="-5.123, 120.123">
                    </div>

                    <div class="md:col-span-2 grid grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest ml-1">Foto Kondisi Awal (Before)</label>
                            <div class="relative group aspect-video bg-white dark:bg-slate-900 border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-2xl flex flex-col items-center justify-center overflow-hidden transition-all hover:border-indigo-600">
                                <input type="file" name="foto_before" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 z-10 cursor-pointer" onchange="pisewModal.previewImg(this, 'before')">
                                <img id="pis_preview_img_before" class="hidden w-full h-full object-cover">
                                <div id="pis_placeholder_before" class="text-center">
                                    <i data-lucide="image-plus" class="w-6 h-6 text-slate-300 mx-auto mb-1"></i>
                                    <span class="text-[7px] font-bold text-slate-400 uppercase">Foto Before</span>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest ml-1">Foto Hasil Akhir (After)</label>
                            <div class="relative group aspect-video bg-white dark:bg-slate-900 border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-2xl flex flex-col items-center justify-center overflow-hidden transition-all hover:border-emerald-600">
                                <input type="file" name="foto_after" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 z-10 cursor-pointer" onchange="pisewModal.previewImg(this, 'after')">
                                <img id="pis_preview_img_after" class="hidden w-full h-full object-cover">
                                <div id="pis_placeholder_after" class="text-center">
                                    <i data-lucide="image-plus" class="w-6 h-6 text-slate-300 mx-auto mb-1"></i>
                                    <span class="text-[7px] font-bold text-slate-400 uppercase">Foto After</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="p-6 bg-slate-50 dark:bg-slate-900 border-t dark:border-slate-800 flex justify-end gap-3 rounded-b-[2.5rem]">
                    <button type="button" onclick="UI.closeModal('modal-pisew')" class="px-6 py-2.5 text-slate-500 font-bold uppercase tracking-widest text-[10px] hover:text-rose-500 transition-colors">Batal</button>
                    <button type="submit" id="btn-pis-submit" class="px-8 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-black uppercase tracking-widest text-[10px] shadow-lg shadow-indigo-600/20 active:scale-95 transition-all flex items-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i> Simpan Aset
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    (function() {
        const PISEW_UPLOAD_URL = <?= json_encode(base_url('uploads/pisew/')) ?>;
        
        window.pisewModal = {
            uploadUrl: PISEW_UPLOAD_URL.endsWith('/') ? PISEW_UPLOAD_URL : PISEW_UPLOAD_URL + '/',

            openAdd: function() {
                const form = document.getElementById('form-pisew');
                if (!form) return;
                form.reset();
                form.action = <?= json_encode(base_url('pisew/store')) ?>;
                
                document.getElementById('modal-pisew-title').innerText = "Tambah Data PISEW";
                
                ['before', 'after'].forEach(p => {
                    document.getElementById('pis_preview_img_' + p).classList.add('hidden');
                    document.getElementById('pis_placeholder_' + p).classList.remove('hidden');
                });
                
                const btn = document.getElementById('btn-pis-submit');
                if (btn) {
                    btn.className = "px-8 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-black uppercase tracking-widest text-[10px] shadow-lg shadow-emerald-600/20 active:scale-95 transition-all flex items-center gap-2";
                    btn.innerHTML = '<i data-lucide="plus-circle" class="w-4 h-4"></i> Tambah Baru';
                }

                if (window.UI) UI.openModal('modal-pisew');
            },

            openEdit: function(data) {
                if (!data) return;
                const form = document.getElementById('form-pisew');
                if (!form) return;
                form.reset();
                form.action = <?= json_encode(base_url('pisew/update')) ?> + '/' + (data.id || 0);
                
                document.getElementById('modal-pisew-title').innerText = "Edit Data PISEW";
                
                const fields = {
                    'inp_pis_jenis': data.jenis_pekerjaan,
                    'inp_pis_kecamatan': data.kecamatan,
                    'inp_pis_desa': data.lokasi_desa,
                    'inp_pis_tahun': data.tahun,
                    'inp_pis_anggaran': data.anggaran,
                    'inp_pis_sumber': data.sumber_dana,
                    'inp_pis_pelaksana': data.pelaksana,
                    'inp_pis_koordinat': data.koordinat
                };

                for (const id in fields) {
                    const el = document.getElementById(id);
                    if (el) el.value = fields[id] || '';
                }

                ['before', 'after'].forEach(p => {
                    const img = document.getElementById('pis_preview_img_' + p);
                    const ph = document.getElementById('pis_placeholder_' + p);
                    if (data['foto_' + p]) {
                        img.src = this.uploadUrl + data['foto_' + p];
                        img.classList.remove('hidden');
                        ph.classList.add('hidden');
                    } else {
                        img.classList.add('hidden');
                        ph.classList.remove('hidden');
                    }
                });

                const btn = document.getElementById('btn-pis-submit');
                if (btn) {
                    btn.className = "px-8 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-black uppercase tracking-widest text-[10px] shadow-lg shadow-indigo-600/20 active:scale-95 transition-all flex items-center gap-2";
                    btn.innerHTML = '<i data-lucide="save" class="w-4 h-4"></i> Simpan Perubahan';
                }

                if (window.UI) UI.openModal('modal-pisew');
            },

            previewImg: function(input, type) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.getElementById('pis_preview_img_' + type);
                        const ph = document.getElementById('pis_placeholder_' + type);
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
