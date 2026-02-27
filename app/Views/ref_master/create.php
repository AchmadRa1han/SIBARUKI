<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-300">
        <div class="p-8 border-b dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-black text-blue-950 dark:text-white uppercase tracking-wider">Tambah Referensi</h1>
                <p class="text-sm text-slate-400 dark:text-slate-500 font-medium italic">Definisikan kategori dan pilihan master baru.</p>
            </div>
            <a href="<?= base_url('ref-master') ?>" class="text-slate-400 dark:text-slate-600 hover:text-rose-500 transition-colors">
                <i data-lucide="x-circle" class="w-8 h-8"></i>
            </a>
        </div>

        <form action="<?= base_url('ref-master/store') ?>" method="post" class="p-10 space-y-8">
            <?= csrf_field() ?>
            
            <div>
                <label class="block text-[10px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-widest mb-2 ml-1">Kategori Master</label>
                <input type="text" name="kategori" value="<?= old('kategori') ?>" required
                       class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all uppercase placeholder:normal-case"
                       placeholder="Contoh: JENIS_ATAP, PEKERJAAN">
                <?php if (session('errors.kategori')) : ?>
                    <p class="text-rose-500 text-[10px] font-bold mt-2 ml-1 uppercase"><?= session('errors.kategori') ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label class="block text-[10px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-widest mb-2 ml-1">Nama Pilihan</label>
                <input type="text" name="nama_pilihan" value="<?= old('nama_pilihan') ?>" required
                       class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-slate-200 outline-none transition-all placeholder:italic"
                       placeholder="Contoh: Seng, Kayu, Buruh Harian">
                <?php if (session('errors.nama_pilihan')) : ?>
                    <p class="text-rose-500 text-[10px] font-bold mt-2 ml-1 uppercase"><?= session('errors.nama_pilihan') ?></p>
                <?php endif; ?>
            </div>

            <div class="pt-8 flex justify-end gap-6 border-t border-slate-50 dark:border-slate-800">
                <a href="<?= base_url('ref-master') ?>" class="px-6 py-4 text-xs font-black text-slate-400 dark:text-slate-600 uppercase tracking-widest hover:text-slate-600 dark:hover:text-slate-400 transition-colors">Batal</a>
                <button type="submit" class="bg-blue-950 dark:bg-blue-700 text-white px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-blue-950/20 dark:shadow-none hover:bg-blue-900 dark:hover:bg-blue-600 transition-all">
                    Simpan Referensi
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
