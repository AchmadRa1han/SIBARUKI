<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto space-y-8 pb-12">
    <div class="flex items-center gap-4">
        <a href="<?= base_url('arsinum') ?>" class="w-10 h-10 bg-white dark:bg-slate-900 rounded-xl flex items-center justify-center text-slate-600 dark:text-slate-400 shadow-sm border border-slate-100 dark:border-slate-800 hover:bg-blue-600 hover:text-white transition-all">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-blue-950 dark:text-white uppercase tracking-tight">Edit ARSINUM</h1>
            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest leading-none">Perbarui Data Proyek Air Siap Minum</p>
        </div>
    </div>

    <form action="<?= base_url('arsinum/update/' . $item['id']) ?>" method="post" class="space-y-6">
        <?= csrf_field() ?>
        
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-10 shadow-sm border border-slate-100 dark:border-slate-800 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="md:col-span-2 space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Jenis Pekerjaan / Nama Objek</label>
                    <input type="text" name="jenis_pekerjaan" value="<?= old('jenis_pekerjaan', $item['jenis_pekerjaan']) ?>" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-4 text-sm font-bold text-blue-950 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all uppercase">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Volume / Kapasitas</label>
                    <input type="text" name="volume" value="<?= old('volume', $item['volume']) ?>" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-4 text-sm font-bold text-blue-950 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Anggaran (Rp)</label>
                    <input type="number" name="anggaran" value="<?= old('anggaran', $item['anggaran']) ?>" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-4 text-sm font-bold text-blue-950 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Desa / Lokasi</label>
                    <input type="text" name="desa" value="<?= old('desa', $item['desa']) ?>" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-4 text-sm font-bold text-blue-950 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all uppercase">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Kecamatan</label>
                    <input type="text" name="kecamatan" value="<?= old('kecamatan', $item['kecamatan']) ?>" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-4 text-sm font-bold text-blue-950 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all uppercase">
                </div>
                <div class="md:col-span-2 space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Koordinat (Lat, Long)</label>
                    <input type="text" name="koordinat" value="<?= old('koordinat', $item['koordinat']) ?>" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-4 text-sm font-bold text-blue-950 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
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
