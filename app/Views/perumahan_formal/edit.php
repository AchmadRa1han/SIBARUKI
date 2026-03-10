<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto space-y-8 pb-12">
    <div class="flex items-center gap-4">
        <a href="<?= base_url('perumahan-formal/detail/'.$item['id']) ?>" class="w-10 h-10 bg-white dark:bg-slate-900 rounded-xl flex items-center justify-center text-slate-600 dark:text-slate-400 shadow-sm border border-slate-100 dark:border-slate-800 hover:bg-blue-600 hover:text-white transition-all">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-blue-950 dark:text-white uppercase tracking-tight">Edit Perumahan Formal</h1>
            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Perbarui Data Kawasan Perumahan</p>
        </div>
    </div>

    <form action="<?= base_url('perumahan-formal/update/'.$item['id']) ?>" method="post" class="space-y-6">
        <?= csrf_field() ?>
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-8 shadow-sm border border-slate-100 dark:border-slate-800">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2 space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Perumahan</label>
                    <input type="text" name="nama_perumahan" value="<?= old('nama_perumahan', $item['nama_perumahan']) ?>" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all uppercase">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Pengembang</label>
                    <input type="text" name="pengembang" value="<?= old('pengembang', $item['pengembang']) ?>" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all uppercase">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tahun Pembangunan</label>
                    <input type="number" name="tahun_pembangunan" value="<?= old('tahun_pembangunan', $item['tahun_pembangunan']) ?>" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Luas Kawasan (Ha)</label>
                    <input type="number" step="0.01" name="luas_kawasan_ha" value="<?= old('luas_kawasan_ha', $item['luas_kawasan_ha']) ?>" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Longitude</label>
                    <input type="text" name="longitude" value="<?= old('longitude', $item['longitude']) ?>" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Latitude</label>
                    <input type="text" name="latitude" value="<?= old('latitude', $item['latitude']) ?>" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
                <div class="md:col-span-2 space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Data Spasial (WKT)</label>
                    <textarea name="wkt" rows="3" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all font-mono text-xs"><?= old('wkt', $item['wkt']) ?></textarea>
                </div>
            </div>
        </div>
        <div class="flex justify-end gap-4">
            <button type="submit" class="px-10 py-3.5 bg-blue-950 text-white rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl hover:bg-black transition-all">Perbarui Data</button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
