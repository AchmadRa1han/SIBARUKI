<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="space-y-10 pb-24">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-4xl font-black text-blue-950 dark:text-white uppercase tracking-tight">Pengaturan Carousel</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium mt-1">Kelola gambar dan teks yang muncul pada halaman utama.</p>
        </div>
        <a href="<?= base_url('settings') ?>" class="flex items-center space-x-2 bg-slate-100 dark:bg-slate-800 px-6 py-3 rounded-2xl text-xs font-black uppercase tracking-widest text-slate-600 dark:text-slate-300">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali</span>
        </a>
    </div>

    <!-- CAROUSEL MANAGER -->
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 p-8 lg:p-12 shadow-sm">
        <form action="<?= base_url('settings/update-carousel') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div id="carousel-container" class="space-y-8">
                <?php if (!empty($carousel)): ?>
                    <?php foreach ($carousel as $index => $item): ?>
                    <div class="carousel-item p-8 bg-slate-50 dark:bg-slate-950/50 rounded-[2rem] border border-slate-100 dark:border-slate-800 flex flex-col lg:flex-row gap-8 items-start">
                        <div class="w-full lg:w-64 h-40 rounded-2xl overflow-hidden shadow-lg bg-slate-200 dark:bg-slate-800 flex-shrink-0">
                            <img src="<?= base_url($item['image']) ?>" class="w-full h-full object-cover">
                            <input type="hidden" name="old_image[<?= $index ?>]" value="<?= $item['image'] ?>">
                        </div>
                        <div class="flex-grow space-y-4 w-full">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Ganti Gambar (Opsional)</label>
                                    <input type="file" name="image[<?= $index ?>]" class="w-full text-xs font-medium text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Caption Gambar</label>
                                    <input type="text" name="caption[<?= $index ?>]" value="<?= $item['caption'] ?>" class="w-full bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-blue-600 transition-all" required>
                                </div>
                            </div>
                            <button type="button" onclick="removeItem(this)" class="text-[10px] font-black text-rose-600 uppercase tracking-widest flex items-center gap-2 hover:translate-x-1 transition-all">
                                <i data-lucide="trash-2" class="w-3 h-3"></i> Hapus Baris
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="mt-12 flex flex-col md:flex-row items-center justify-between gap-6 border-t border-slate-100 dark:border-slate-800 pt-10">
                <button type="button" onclick="addNewItem()" class="px-8 py-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-100 transition-all flex items-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i> Tambah Gambar
                </button>
                <button type="submit" class="w-full md:w-fit px-12 py-5 bg-blue-600 text-white rounded-[2rem] text-xs font-black uppercase tracking-widest shadow-2xl shadow-blue-600/30 hover:scale-105 active:scale-95 transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function addNewItem() {
        const container = document.getElementById('carousel-container');
        const index = container.querySelectorAll('.carousel-item').length;
        
        const html = `
            <div class="carousel-item p-8 bg-slate-50 dark:bg-slate-950/50 rounded-[2rem] border border-slate-100 dark:border-slate-800 flex flex-col lg:flex-row gap-8 items-start animate-in zoom-in duration-300">
                <div class="w-full lg:w-64 h-40 rounded-2xl overflow-hidden shadow-lg bg-slate-200 dark:bg-slate-800 flex-shrink-0 flex items-center justify-center">
                    <i data-lucide="image" class="w-12 h-12 text-slate-400"></i>
                </div>
                <div class="flex-grow space-y-4 w-full">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Pilih Gambar</label>
                            <input type="file" name="image[${index}]" class="w-full text-xs font-medium text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-blue-600 file:text-white hover:file:bg-blue-700" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Caption Gambar</label>
                            <input type="text" name="caption[${index}]" class="w-full bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-blue-600 transition-all" placeholder="Masukkan judul..." required>
                        </div>
                    </div>
                    <button type="button" onclick="removeItem(this)" class="text-[10px] font-black text-rose-600 uppercase tracking-widest flex items-center gap-2 hover:translate-x-1 transition-all">
                        <i data-lucide="trash-2" class="w-3 h-3"></i> Hapus Baris
                    </button>
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
