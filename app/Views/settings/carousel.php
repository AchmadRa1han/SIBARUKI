<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="space-y-10 pb-24">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-4xl font-bold text-blue-950 dark:text-white uppercase tracking-tight">Pengaturan Carousel</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium mt-1">Kelola gambar dan teks yang muncul pada halaman utama dengan ukuran rekomendasi <strong class="text-blue-600">380 x 520 piksel</strong> (Rasio Vertikal / Portrait).</p>
        </div>
        <a href="<?= base_url('sys_settings') ?>" class="flex items-center space-x-2 bg-slate-100 dark:bg-slate-800 px-6 py-3 rounded-2xl text-xs font-bold uppercase tracking-widest text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali</span>
        </a>
    </div>

    <!-- CAROUSEL MANAGER -->
    <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 p-8 lg:p-12 shadow-sm">
        <form action="<?= base_url('sys_settings/update-carousel') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div id="carousel-container" class="space-y-8">
                <?php if (!empty($carousel)): ?>
                    <?php foreach ($carousel as $index => $item): ?>
                    <div class="carousel-item p-8 bg-slate-50 dark:bg-slate-950/50 rounded-2xl border border-slate-100 dark:border-slate-800 flex flex-col lg:flex-row gap-8 items-start">
                        <!-- Preview container (Recommended Aspect Ratio: Vertikal / Portrait) -->
                        <div onclick="triggerFileInput('<?= $index ?>')" class="w-full lg:w-[190px] h-[260px] rounded-2xl overflow-hidden shadow-lg bg-slate-200 dark:bg-slate-800 flex-shrink-0 relative cursor-pointer group">
                            <img src="<?= base_url($item['image']) ?>" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" id="img-preview-<?= $index ?>" style="object-position: <?= $item['position'] ?? '50%' ?> center;">
                            <input type="hidden" name="old_image[<?= $index ?>]" value="<?= $item['image'] ?>">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-white text-[10px] font-bold uppercase tracking-widest gap-2">
                                <i data-lucide="upload-cloud" class="w-5 h-5"></i>
                                <span>Ganti Gambar</span>
                            </div>
                        </div>
                        <div class="flex-grow space-y-5 w-full">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Ganti Gambar (Opsional)</label>
                                    <input type="file" id="file-input-<?= $index ?>" name="image[<?= $index ?>]" onchange="previewFile(this, '<?= $index ?>')" class="w-full text-xs font-medium text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-bold file:uppercase file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Caption Gambar</label>
                                    <input type="text" name="caption[<?= $index ?>]" value="<?= $item['caption'] ?>" class="w-full bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-blue-600 transition-all" placeholder="Masukkan deskripsi gambar..." required>
                                </div>
                            </div>
                            <!-- Crop alignment control -->
                            <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-xl p-4 flex flex-col md:flex-row items-center gap-4">
                                <div class="flex items-center gap-2 text-slate-400 shrink-0">
                                    <i data-lucide="crop" class="w-4 h-4"></i>
                                    <span class="text-[9px] font-bold uppercase tracking-widest">Fokus Potong Horizontal (X-Axis)</span>
                                </div>
                                <input type="range" name="position[<?= $index ?>]" min="0" max="100" value="<?= intval($item['position'] ?? 50) ?>" oninput="updateCropPosition(this, '<?= $index ?>')" class="w-full h-1 bg-slate-100 dark:bg-slate-800 rounded-lg appearance-none cursor-pointer accent-blue-600">
                                <span id="pos-val-<?= $index ?>" class="text-[10px] font-mono font-bold text-slate-500 shrink-0"><?= $item['position'] ?? '50%' ?></span>
                            </div>
                            <div class="flex justify-between items-center pt-2">
                                <button type="button" onclick="removeItem(this)" class="text-[10px] font-bold text-rose-600 uppercase tracking-widest flex items-center gap-2 hover:translate-x-1 transition-all">
                                    <i data-lucide="trash-2" class="w-3 h-3"></i> Hapus Baris
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="mt-12 flex flex-col md:flex-row items-center justify-between gap-6 border-t border-slate-100 dark:border-slate-800 pt-10">
                <button type="button" onclick="addNewItem()" class="px-8 py-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 rounded-2xl text-[10px] font-bold uppercase tracking-widest hover:bg-emerald-100 transition-all flex items-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i> Tambah Gambar
                </button>
                <button type="submit" class="w-full md:w-fit px-12 py-5 bg-blue-600 text-white rounded-2xl text-xs font-bold uppercase tracking-widest shadow-2xl shadow-blue-600/30 hover:scale-105 active:scale-95 transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Placeholder SVG cantik dengan rasio Vertikal
    const placeholderSvg = `data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='380' height='520' viewBox='0 0 380 520'><defs><linearGradient id='g' x1='0%25' y1='0%25' x2='100%25' y2='100%25'><stop offset='0%25' stop-color='%23eff6ff'/><stop offset='100%25' stop-color='%23dbeafe'/></linearGradient></defs><rect width='380' height='520' fill='url(%23g)'/><circle cx='190' cy='260' r='50' fill='%232563eb' opacity='0.1'/><path d='M160 275 L190 245 L220 275 Z M170 275 L170 305 L210 305 L210 275 Z' fill='none' stroke='%232563eb' stroke-width='3' stroke-linecap='round' stroke-linejoin='round' opacity='0.5'/><text x='190' y='335' fill='%233b82f6' font-family='sans-serif' font-weight='bold' font-size='10' letter-spacing='1.5' text-anchor='middle' opacity='0.6'>KLIK UNTUK UNGGAH GAMBAR</text></svg>`;

    function triggerFileInput(index) {
        const fileInput = document.getElementById(`file-input-${index}`);
        if (fileInput) {
            fileInput.click();
        }
    }

    function previewFile(input, index) {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById(`img-preview-${index}`);
                if (img) {
                    img.src = e.target.result;
                }
            }
            reader.readAsDataURL(file);
        }
    }

    function updateCropPosition(slider, index) {
        const val = slider.value + '%';
        document.getElementById(`pos-val-${index}`).innerText = val;
        const img = document.getElementById(`img-preview-${index}`);
        if (img) {
            img.style.objectPosition = `${val} center`;
        }
    }

    function addNewItem() {
        const container = document.getElementById('carousel-container');
        const uniqueId = Date.now();
        
        const html = `
            <div class="carousel-item p-8 bg-slate-50 dark:bg-slate-950/50 rounded-2xl border border-slate-100 dark:border-slate-800 flex flex-col lg:flex-row gap-8 items-start animate-in zoom-in duration-300">
                <!-- Preview container (Recommended Aspect Ratio: Vertikal / Portrait) -->
                <div onclick="triggerFileInput('${uniqueId}')" class="w-full lg:w-[190px] h-[260px] rounded-2xl overflow-hidden shadow-lg bg-slate-200 dark:bg-slate-800 flex-shrink-0 relative cursor-pointer group">
                    <img id="img-preview-${uniqueId}" src="${placeholderSvg}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" style="object-position: 50% center;">
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-white text-[10px] font-bold uppercase tracking-widest gap-2">
                        <i data-lucide="upload-cloud" class="w-5 h-5"></i>
                        <span>Pilih Gambar</span>
                    </div>
                </div>
                <div class="flex-grow space-y-5 w-full">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pilih Gambar</label>
                            <input type="file" id="file-input-${uniqueId}" name="image[${uniqueId}]" onchange="previewFile(this, '${uniqueId}')" class="w-full text-xs font-medium text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-bold file:uppercase file:bg-blue-600 file:text-white hover:file:bg-blue-700" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Caption Gambar</label>
                            <input type="text" name="caption[${uniqueId}]" class="w-full bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-blue-600 transition-all" placeholder="Masukkan caption gambar..." required>
                        </div>
                    </div>
                    <!-- Crop alignment control -->
                    <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-xl p-4 flex flex-col md:flex-row items-center gap-4">
                        <div class="flex items-center gap-2 text-slate-400 shrink-0">
                            <i data-lucide="crop" class="w-4 h-4"></i>
                            <span class="text-[9px] font-bold uppercase tracking-widest">Fokus Potong Horizontal (X-Axis)</span>
                        </div>
                        <input type="range" name="position[${uniqueId}]" min="0" max="100" value="50" oninput="updateCropPosition(this, '${uniqueId}')" class="w-full h-1 bg-slate-100 dark:bg-slate-800 rounded-lg appearance-none cursor-pointer accent-blue-600">
                        <span id="pos-val-${uniqueId}" class="text-[10px] font-mono font-bold text-slate-500 shrink-0">50%</span>
                    </div>
                    <div class="flex justify-between items-center pt-2">
                        <button type="button" onclick="removeItem(this)" class="text-[10px] font-bold text-rose-600 uppercase tracking-widest flex items-center gap-2 hover:translate-x-1 transition-all">
                            <i data-lucide="trash-2" class="w-3 h-3"></i> Hapus Baris
                        </button>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        lucide.createIcons();
    }

    function removeItem(btn) {
        btn.closest('.carousel-item').remove();
    }

    lucide.createIcons();
</script>
<?= $this->endSection() ?>
