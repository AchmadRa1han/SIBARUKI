<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto pb-20">
    <div class="flex items-center gap-4 mb-8">
        <a href="<?= base_url('psu') ?>" class="p-3 bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 hover:bg-slate-50 transition-all">
            <i data-lucide="arrow-left" class="w-5 h-5 text-slate-500"></i>
        </a>
        <div>
            <h1 class="text-3xl font-black text-blue-950 dark:text-white uppercase tracking-tight">Edit Jalan</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium text-sm">Perbarui informasi jaringan jalan PSU.</p>
        </div>
    </div>

    <form action="<?= base_url('psu/update/' . $jalan['id']) ?>" method="post" class="space-y-6">
        <?= csrf_field() ?>
        
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-10 shadow-sm border border-slate-100 dark:border-slate-800 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Jalan</label>
                    <input type="text" name="nama_jalan" value="<?= old('nama_jalan', $jalan['nama_jalan']) ?>" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-4 text-sm font-bold text-blue-950 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all placeholder:text-slate-300 uppercase">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nilai Jalan</label>
                    <input type="number" step="0.01" name="jalan" value="<?= old('jalan', $jalan['jalan']) ?>" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-4 text-sm font-bold text-blue-950 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Data Geospasial (WKT)</label>
                <textarea name="wkt" rows="6" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-3xl px-5 py-4 text-[11px] font-mono text-slate-600 dark:text-slate-400 focus:ring-2 focus:ring-blue-500 transition-all leading-relaxed"><?= old('wkt', $jalan['wkt']) ?></textarea>
                <p class="text-[9px] text-slate-400 font-medium ml-1 mt-2 flex items-center gap-1.5"><i data-lucide="info" class="w-3 h-3"></i> Pastikan format WKT tetap terjaga (LINESTRING).</p>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <button type="submit" class="bg-blue-950 text-white px-10 py-5 rounded-3xl text-[10px] font-black uppercase tracking-[0.2em] shadow-2xl hover:bg-black transition-all active:scale-95 flex items-center gap-3 group">
                Simpan Perubahan <i data-lucide="refresh-cw" class="w-4 h-4 group-hover:rotate-180 transition-transform duration-500"></i>
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
