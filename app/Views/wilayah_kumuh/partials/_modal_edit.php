<!-- WILAYAH KUMUH MODAL COMPONENT -->
<div id="modal-kumuh" class="fixed inset-0 z-[10002] hidden overflow-y-auto py-10 px-4">
    <div class="fixed inset-0 bg-blue-950/60 backdrop-blur-sm transition-opacity" onclick="UI.closeModal('modal-kumuh')"></div>
    <div class="relative flex items-center justify-center p-4 min-h-full">
        <div class="bg-white dark:bg-slate-900 w-full max-w-4xl rounded-[2.5rem] shadow-2xl overflow-hidden animate-in zoom-in duration-300">
            <!-- Modal Header -->
            <div class="p-8 bg-blue-950 text-white flex justify-between items-center border-b border-white/10 sticky top-0 z-30">
                <div class="flex items-center gap-5">
                    <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-md border border-white/10">
                        <i data-lucide="layers" class="w-6 h-6 text-rose-400"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black uppercase tracking-tighter" id="modal-kumuh-title">Data Wilayah Kumuh</h3>
                        <p class="text-[8px] font-bold uppercase tracking-widest text-white/60 mt-1">Registri Deliniasi & SK Kumuh</p>
                    </div>
                </div>
                <button type="button" onclick="UI.closeModal('modal-kumuh')" class="p-2 hover:bg-white/10 rounded-xl transition-colors"><i data-lucide="x" class="w-6 h-6"></i></button>
            </div>

            <form id="form-kumuh" action="<?= base_url('wilayah-kumuh/store') ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6 bg-slate-100 dark:bg-slate-950">
                    <div class="md:col-span-2">
                        <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Nama Kawasan</label>
                        <input type="text" name="Kawasan" id="inp_kum_kawasan" required class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase" placeholder="Masukkan Nama Kawasan...">
                    </div>
                    
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Kecamatan</label>
                        <input type="text" name="Kecamatan" id="inp_kum_kecamatan" required class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                    </div>

                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Kelurahan / Desa</label>
                        <input type="text" name="Kelurahan" id="inp_kum_kelurahan" required class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Luas (Ha)</label>
                            <input type="number" step="0.01" name="Luas_kumuh" id="inp_kum_luas" required class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold">
                        </div>
                        <div>
                            <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Skor Kumuh</label>
                            <input type="number" step="0.01" name="skor_kumuh" id="inp_kum_skor" required class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Nomor SK Kumuh</label>
                        <input type="text" name="Sk_Kumuh" id="inp_kum_sk" required class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase" placeholder="SK/01/2024...">
                    </div>

                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Sumber Data</label>
                        <input type="text" name="Sumber_data" id="inp_kum_sumber" class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase" placeholder="P3KE / DAK / DLL">
                    </div>

                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Kode RT/RW</label>
                        <input type="text" name="Kode_RT_RW" id="inp_kum_rt_rw" class="w-full p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest ml-1">Polygon Geospasial (WKT)</label>
                        <textarea name="WKT" id="inp_kum_wkt" rows="4" required class="w-full p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-mono text-[10px] leading-relaxed" placeholder="POLYGON((...))"></textarea>
                    </div>

                    <input type="hidden" name="Provinsi" value="Sulawesi Selatan">
                    <input type="hidden" name="Kab_Kota" value="Sinjai">
                    <input type="hidden" name="Kode_Prov" value="73">
                    <input type="hidden" name="Kode_Kab" value="07">
                </div>

                <!-- Footer -->
                <div class="p-6 bg-slate-50 dark:bg-slate-900 border-t dark:border-slate-800 flex justify-end gap-3 rounded-b-[2.5rem]">
                    <button type="button" onclick="UI.closeModal('modal-kumuh')" class="px-6 py-2.5 text-slate-500 font-bold uppercase tracking-widest text-[10px] hover:text-rose-500 transition-colors">Batal</button>
                    <button type="submit" id="btn-kum-submit" class="px-8 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-black uppercase tracking-widest text-[10px] shadow-lg shadow-rose-600/20 active:scale-95 transition-all flex items-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i> Simpan Kawasan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    (function() {
        window.kumuhModal = {
            openAdd: function() {
                const form = document.getElementById('form-kumuh');
                if (!form) return;
                form.reset();
                form.action = <?= json_encode(base_url('wilayah-kumuh/store')) ?>;
                
                document.getElementById('modal-kumuh-title').innerText = "Tambah Kawasan Kumuh";
                
                const btn = document.getElementById('btn-kum-submit');
                if (btn) {
                    btn.className = "px-8 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-black uppercase tracking-widest text-[10px] shadow-lg shadow-emerald-600/20 active:scale-95 transition-all flex items-center gap-2";
                    btn.innerHTML = '<i data-lucide="plus-circle" class="w-4 h-4"></i> Tambah Kawasan';
                }

                if (window.UI) UI.openModal('modal-kumuh');
            },

            openEdit: function(data) {
                if (!data) return;
                const form = document.getElementById('form-kumuh');
                if (!form) return;
                form.reset();
                form.action = <?= json_encode(base_url('wilayah-kumuh/update')) ?> + '/' + (data.FID || 0);
                
                document.getElementById('modal-kumuh-title').innerText = "Edit Kawasan Kumuh";
                
                const fields = {
                    'inp_kum_kawasan': data.Kawasan,
                    'inp_kum_kecamatan': data.Kecamatan,
                    'inp_kum_kelurahan': data.Kelurahan,
                    'inp_kum_luas': data.Luas_kumuh,
                    'inp_kum_skor': data.skor_kumuh,
                    'inp_kum_sk': data.Sk_Kumuh,
                    'inp_kum_sumber': data.Sumber_data,
                    'inp_kum_rt_rw': data.Kode_RT_RW,
                    'inp_kum_wkt': data.WKT
                };

                for (const id in fields) {
                    const el = document.getElementById(id);
                    if (el) el.value = fields[id] || '';
                }

                const btn = document.getElementById('btn-kum-submit');
                if (btn) {
                    btn.className = "px-8 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-black uppercase tracking-widest text-[10px] shadow-lg shadow-blue-600/20 active:scale-95 transition-all flex items-center gap-2";
                    btn.innerHTML = '<i data-lucide="save" class="w-4 h-4"></i> Simpan Perubahan';
                }

                if (window.UI) UI.openModal('modal-kumuh');
            }
        };
    })();
</script>
