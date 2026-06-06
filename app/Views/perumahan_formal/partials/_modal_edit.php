<!-- PERUMAHAN FORMAL MODAL COMPONENT -->
<div id="modal-perumahan" class="fixed inset-0 z-[10002] hidden overflow-y-auto py-10 px-4">
    <div class="fixed inset-0 bg-blue-950/60 backdrop-blur-sm transition-opacity" onclick="UI.closeModal('modal-perumahan')"></div>
    <div class="relative flex items-center justify-center p-4 min-h-full">
        <div class="bg-white dark:bg-slate-900 w-full max-w-2xl rounded-[2.5rem] shadow-2xl overflow-hidden animate-in zoom-in duration-300">
            <!-- Modal Header -->
            <div class="p-8 bg-blue-950 text-white flex justify-between items-center border-b border-white/10 sticky top-0 z-30">
                <div class="flex items-center gap-5">
                    <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-md border border-white/10">
                        <i data-lucide="building" class="w-6 h-6 text-blue-400"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black uppercase tracking-tighter" id="modal-perumahan-title">Data Perumahan</h3>
                        <p class="text-[8px] font-bold uppercase tracking-widest text-white/60 mt-1">Registri Kawasan Perumahan Formal</p>
                    </div>
                </div>
                <button type="button" onclick="UI.closeModal('modal-perumahan')" class="p-2 hover:bg-white/10 rounded-xl transition-colors"><i data-lucide="x" class="w-6 h-6"></i></button>
            </div>

            <form id="form-perumahan" action="<?= base_url('perumahan-formal/store') ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Nama Perumahan</label>
                        <input type="text" name="nama_perumahan" id="inp_nama_perumahan" required class="w-full p-3.5 bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase" placeholder="Masukkan Nama Perumahan...">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Pengembang</label>
                        <input type="text" name="pengembang" id="inp_pengembang" required class="w-full p-3.5 bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold uppercase" placeholder="Nama Developer...">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Tahun Pembangunan</label>
                        <input type="number" name="tahun_pembangunan" id="inp_tahun" value="<?= date('Y') ?>" required class="w-full p-3.5 bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-bold">
                    </div>
                    <div class="md:col-span-2 bg-blue-50 dark:bg-blue-950/30 p-5 rounded-2xl border border-blue-100 dark:border-blue-900/50">
                        <label class="block text-[8px] font-bold text-blue-900 dark:text-blue-400 uppercase mb-2 tracking-widest ml-1">Luas Kawasan (Hektar)</label>
                        <input type="number" step="0.01" name="luas_kawasan_ha" id="inp_luas" required class="w-full bg-transparent border-none text-xl font-bold text-blue-950 dark:text-white p-0 focus:ring-0 outline-none placeholder:opacity-20" placeholder="0.00">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Latitude</label>
                        <input type="text" name="latitude" id="inp_lat" required class="w-full p-3.5 bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-mono text-xs" placeholder="-5.123">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Longitude</label>
                        <input type="text" name="longitude" id="inp_lng" required class="w-full p-3.5 bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-mono text-xs" placeholder="120.123">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-2 tracking-widest ml-1">Batas Kawasan (WKT Polygon) - Opsional</label>
                        <textarea name="wkt" id="inp_wkt" rows="3" class="w-full p-4 bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all font-mono text-xs leading-relaxed" placeholder="POLYGON((Long Lat, Long Lat, ...))"></textarea>
                    </div>
                </div>

                <!-- Footer -->
                <div class="p-6 bg-slate-100 dark:bg-slate-900 border-t dark:border-slate-800 flex justify-end gap-3 rounded-b-[2.5rem]">
                    <button type="button" onclick="UI.closeModal('modal-perumahan')" class="px-6 py-2.5 text-slate-500 font-bold uppercase tracking-widest text-[10px] hover:text-rose-500 transition-colors">Batal</button>
                    <button type="submit" class="px-8 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-black uppercase tracking-widest text-[10px] shadow-lg shadow-emerald-600/20 active:scale-95 transition-all flex items-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    (function() {
        window.perumahanModal = {
            openAdd: function() {
                const form = document.getElementById('form-perumahan');
                if (!form) return;
                
                form.reset();
                form.action = <?= json_encode(base_url('perumahan-formal/store')) ?>;
                
                const title = document.getElementById('modal-perumahan-title');
                if (title) title.innerText = "Tambah Data Perumahan";
                
                const btn = document.querySelector('#form-perumahan button[type="submit"]');
                if (btn) {
                    btn.className = "px-8 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-black uppercase tracking-widest text-[10px] shadow-lg shadow-emerald-600/20 active:scale-95 transition-all flex items-center gap-2";
                    btn.innerHTML = '<i data-lucide="plus-circle" class="w-4 h-4"></i> Tambah Baru';
                }

                if (window.UI) UI.openModal('modal-perumahan');
            },

            openEdit: function(data) {
                if (!data) return;
                
                const form = document.getElementById('form-perumahan');
                if (!form) return;
                
                form.reset();
                form.action = <?= json_encode(base_url('perumahan-formal/update')) ?> + '/' + (data.id || 0);
                
                const title = document.getElementById('modal-perumahan-title');
                if (title) title.innerText = "Edit Data Perumahan";
                
                // Populate fields
                const fields = ['nama_perumahan', 'pengembang', 'tahun_pembangunan', 'luas_kawasan_ha', 'latitude', 'longitude', 'wkt'];
                fields.forEach(f => {
                    const el = document.getElementById('inp_' + (f === 'tahun_pembangunan' ? 'tahun' : (f === 'luas_kawasan_ha' ? 'luas' : (f === 'latitude' ? 'lat' : (f === 'longitude' ? 'lng' : f)))));
                    if (el) el.value = data[f] || '';
                });

                const btn = document.querySelector('#form-perumahan button[type="submit"]');
                if (btn) {
                    btn.className = "px-8 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-black uppercase tracking-widest text-[10px] shadow-lg shadow-blue-600/20 active:scale-95 transition-all flex items-center gap-2";
                    btn.innerHTML = '<i data-lucide="save" class="w-4 h-4"></i> Simpan Perubahan';
                }

                if (window.UI) UI.openModal('modal-perumahan');
            }
        };
    })();
</script>