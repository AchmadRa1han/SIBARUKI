<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto space-y-6 pb-32 text-slate-900 dark:text-slate-200">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white dark:bg-slate-900 p-8 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm relative overflow-hidden transition-all duration-300">
        <div class="absolute top-0 right-0 w-48 h-48 bg-blue-600/5 rounded-full -mr-24 -mt-24 blur-3xl"></div>
        <div class="relative z-10 flex items-center gap-4">
            <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-blue-600/20">
                <i data-lucide="bar-chart-2" class="w-7 h-7"></i>
            </div>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-blue-950 dark:text-white uppercase tracking-tighter">Data Umum Perumahan</h1>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Rekapitulasi Agregat Jumlah Rumah, RLH & Backlog per Desa</p>
            </div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-100 dark:border-emerald-900 text-emerald-700 dark:text-emerald-400 px-6 py-4 rounded-2xl text-xs font-bold shadow-sm flex items-center gap-3 animate-in fade-in">
            <i data-lucide="check-circle" class="w-5 h-5 shrink-0"></i>
            <p><?= session()->getFlashdata('success') ?></p>
        </div>
    <?php endif; ?>

    <!-- Table Section -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden relative">
        <div class="p-6 border-b border-slate-50 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-600/20">
                    <i data-lucide="database" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-blue-950 dark:text-white uppercase tracking-tight">Manajemen Data Agregat</h3>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-[0.2em]">Update data untuk ditampilkan di halaman depan</p>
                </div>
            </div>
            <button type="submit" form="form-update" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl text-[10px] font-bold uppercase tracking-widest shadow-lg shadow-blue-600/20 transition-all active:scale-95 flex items-center gap-2 group">
                <i data-lucide="save" class="w-4 h-4"></i> Simpan Perubahan
            </button>
        </div>

        <div class="overflow-x-auto p-6">
            <form id="form-update" action="<?= base_url('data-perumahan/update') ?>" method="POST">
                <?= csrf_field() ?>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-[9px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-slate-800">
                            <th class="px-4 py-3">Kecamatan</th>
                            <th class="px-4 py-3">Desa / Kelurahan</th>
                            <th class="px-4 py-3 text-center">Jumlah Rumah</th>
                            <th class="px-4 py-3 text-center">Jumlah RLH</th>
                            <th class="px-4 py-3 text-center text-rose-500">Jumlah Backlog</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800 text-[10px] font-bold">
                        <?php 
                        $total_rumah = 0; $total_rlh = 0; $total_backlog = 0;
                        foreach($data as $idx => $d): 
                            $total_rumah += $d['jumlah_rumah'];
                            $total_rlh += $d['jumlah_rlh'];
                            $total_backlog += $d['jumlah_backlog'];
                        ?>
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-4 py-3 text-slate-500"><?= $d['kecamatan_nama'] ?></td>
                            <td class="px-4 py-3 text-blue-950 dark:text-white"><?= $d['desa_nama'] ?></td>
                            <td class="px-4 py-3">
                                <input type="hidden" name="id[]" value="<?= $d['id'] ?>">
                                <input type="number" name="jumlah_rumah[]" value="<?= $d['jumlah_rumah'] ?>" min="0" class="w-full p-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-center font-mono outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                            </td>
                            <td class="px-4 py-3">
                                <input type="number" name="jumlah_rlh[]" value="<?= $d['jumlah_rlh'] ?>" min="0" class="w-full p-2 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-900 rounded-lg text-center font-mono outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-emerald-700 dark:text-emerald-400">
                            </td>
                            <td class="px-4 py-3">
                                <input type="number" name="jumlah_backlog[]" value="<?= $d['jumlah_backlog'] ?>" min="0" class="w-full p-2 bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-900 rounded-lg text-center font-mono outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 text-rose-700 dark:text-rose-400">
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="bg-blue-50 dark:bg-blue-900/20 text-blue-900 dark:text-blue-400 text-xs font-black uppercase tracking-widest border-t-2 border-blue-200 dark:border-blue-800">
                            <td colspan="2" class="px-4 py-4 text-right">TOTAL AGREGAT KABUPATEN</td>
                            <td class="px-4 py-4 text-center font-mono"><?= number_format($total_rumah) ?></td>
                            <td class="px-4 py-4 text-center font-mono text-emerald-600"><?= number_format($total_rlh) ?></td>
                            <td class="px-4 py-4 text-center font-mono text-rose-600"><?= number_format($total_backlog) ?></td>
                        </tr>
                    </tfoot>
                </table>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>
<?= $this->endSection() ?>
