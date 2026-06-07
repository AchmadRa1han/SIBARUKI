<!-- RTLH IMPORT MODAL COMPONENT -->
<div id="modal-import" class="fixed inset-0 z-[10002] hidden overflow-y-auto py-10 px-4">
    <div class="fixed inset-0 bg-blue-950/60 backdrop-blur-sm transition-opacity" onclick="UI.closeModal('modal-import')"></div>
    <div class="relative flex items-center justify-center p-4 min-h-full">
        <div class="bg-white dark:bg-slate-900 w-full max-w-2xl rounded-[2.5rem] shadow-2xl overflow-hidden animate-in zoom-in duration-300">
            <!-- Modal Header -->
            <div class="p-8 bg-blue-950 text-white flex justify-between items-center border-b border-white/10 sticky top-0 z-30">
                <div class="flex items-center gap-5">
                    <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-md border border-white/10">
                        <i data-lucide="file-spreadsheet" class="w-6 h-6 text-emerald-400"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black uppercase tracking-tighter">Import Data Massal</h3>
                        <p class="text-[8px] font-bold uppercase tracking-widest text-white/60 mt-1">Sistem Otomatis Pecah Ke 3 Tabel</p>
                    </div>
                </div>
                <button type="button" onclick="UI.closeModal('modal-import')" class="p-2 hover:bg-white/10 rounded-xl transition-colors"><i data-lucide="x" class="w-6 h-6"></i></button>
            </div>

            <form action="<?= base_url('rtlh/import-csv') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                
                <div class="p-8 space-y-8 bg-slate-100 dark:bg-slate-950">
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-6 rounded-2xl border border-blue-100 dark:border-blue-800">
                        <h4 class="text-[10px] font-bold text-blue-900 dark:text-blue-400 uppercase tracking-widest mb-2 flex items-center gap-2">
                            <i data-lucide="info" class="w-4 h-4"></i> Informasi Pintar
                        </h4>
                        <p class="text-xs text-blue-800 dark:text-blue-300 leading-relaxed font-medium">
                            Anda cukup mengunggah <b>satu file</b> saja. Sistem *backend* kami telah dilengkapi dengan <b>Smart Mapper</b> yang akan otomatis memecah 1 baris data Anda menjadi 3 relasi tabel: <i>Data Penerima</i>, <i>Data Rumah</i>, dan <i>Data Kondisi Fisik</i>.
                        </p>
                        <div class="mt-4 p-3 bg-white/50 dark:bg-slate-900/50 rounded-xl text-[9px] font-mono text-blue-950 dark:text-blue-200">
                            <b>Wajib ada di file Anda:</b> Kolom NIK (Pastikan nama header ada tulisan "NIK").<br>
                            <b>Format File:</b> .xlsx, .xls, .csv
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Pilih File Data (Excel/CSV)</label>
                        <div class="relative group aspect-video md:aspect-[21/9] bg-white dark:bg-slate-900 border-2 border-dashed border-slate-300 dark:border-slate-700 rounded-3xl flex flex-col items-center justify-center overflow-hidden transition-all hover:border-emerald-500 hover:bg-emerald-50/50 dark:hover:bg-emerald-900/10">
                            <input type="file" name="csv_file" id="inp_import_csv" accept=".xlsx, .xls, .csv" required class="absolute inset-0 opacity-0 z-10 cursor-pointer" onchange="updateFileName(this)">
                            <div id="import_placeholder" class="flex flex-col items-center justify-center text-center p-6 transition-transform group-hover:scale-105">
                                <div class="w-16 h-16 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4 text-slate-400 group-hover:text-emerald-500 group-hover:bg-emerald-100 dark:group-hover:bg-emerald-900/30 transition-colors">
                                    <i data-lucide="upload-cloud" class="w-8 h-8"></i>
                                </div>
                                <h5 class="text-sm font-black text-slate-700 dark:text-white uppercase tracking-tight mb-1">Klik atau Drag File Kesini</h5>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Format yang didukung: .XLSX, .XLS, .CSV</p>
                            </div>
                            <div id="import_file_info" class="hidden flex-col items-center justify-center text-center p-6">
                                <i data-lucide="file-check-2" class="w-12 h-12 text-emerald-500 mb-3"></i>
                                <p id="import_file_name" class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest bg-emerald-50 dark:bg-emerald-900/30 px-4 py-2 rounded-xl border border-emerald-100 dark:border-emerald-800"></p>
                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-3">File Siap Diimpor</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="p-6 bg-slate-50 dark:bg-slate-900 border-t dark:border-slate-800 flex justify-end gap-3 rounded-b-[2.5rem]">
                    <button type="button" onclick="UI.closeModal('modal-import')" class="px-6 py-2.5 text-slate-500 font-bold uppercase tracking-widest text-[10px] hover:text-rose-500 transition-colors">Batal</button>
                    <button type="submit" onclick="this.innerHTML='<div class=\'w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin\'></div> Memproses...'; this.classList.add('opacity-80', 'pointer-events-none')" class="px-8 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-black uppercase tracking-widest text-[10px] shadow-lg shadow-emerald-600/20 active:scale-95 transition-all flex items-center gap-2">
                        <i data-lucide="play" class="w-4 h-4"></i> Mulai Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function updateFileName(input) {
        const placeholder = document.getElementById('import_placeholder');
        const fileInfo = document.getElementById('import_file_info');
        const fileNameDisplay = document.getElementById('import_file_name');
        
        if (input.files && input.files.length > 0) {
            const fileName = input.files[0].name;
            fileNameDisplay.innerText = fileName;
            placeholder.classList.add('hidden');
            fileInfo.classList.remove('hidden');
            fileInfo.classList.add('flex');
        } else {
            placeholder.classList.remove('hidden');
            fileInfo.classList.add('hidden');
            fileInfo.classList.remove('flex');
        }
    }
</script>