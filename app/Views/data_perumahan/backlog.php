<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto space-y-6 pb-12 text-slate-900 dark:text-slate-200">
    
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-3 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 no-print">
        <a href="<?= base_url('dashboard') ?>" class="hover:text-blue-600 transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <a href="<?= base_url('data-perumahan') ?>" class="hover:text-blue-600 transition-colors">Rekapitulasi Desa</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">Manajemen Backlog</span>
    </nav>

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-rose-600/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
        <div class="relative z-10">
            <h1 class="text-2xl md:text-4xl font-black text-blue-950 dark:text-white uppercase tracking-tighter leading-none">Data <span class="text-rose-600">Backlog.</span></h1>
            <p class="text-slate-400 font-bold text-[10px] uppercase tracking-[0.2em] mt-3">Manajemen angka kekurangan perumahan (backlog) per wilayah</p>
        </div>
        <div class="flex items-center gap-3 relative z-10">
            <button type="submit" form="backlogForm" class="px-8 py-4 bg-blue-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-700 transition-all active:scale-95 flex items-center gap-3 shadow-xl shadow-blue-600/20">
                <i data-lucide="save" class="w-4 h-4"></i> Simpan Data Backlog
            </button>
        </div>
    </div>

    <!-- Main Content Table -->
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 overflow-hidden">
        <form action="<?= base_url('data-perumahan/update') ?>" method="POST" id="backlogForm">
            <?= csrf_field() ?>
            <input type="hidden" name="redirect_to" value="/data-perumahan/backlog">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 dark:bg-slate-800/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                            <th class="px-6 py-5 w-16 text-center">No</th>
                            <th class="px-6 py-5">Wilayah</th>
                            <th class="px-6 py-5 text-center">Angka Backlog</th>
                            <th class="px-6 py-5">Tahun Data</th>
                            <th class="px-6 py-5">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                        <?php 
                        $totalBacklog = 0;
                        if (!empty($data)): $no = 1; foreach($data as $item): 
                            $totalBacklog += $item['jumlah_backlog'];
                        ?>
                        <tr class="group hover:bg-rose-50/30 dark:hover:bg-rose-900/10 transition-all duration-200">
                            <td class="px-6 py-6 text-center">
                                <span class="text-[10px] font-black text-slate-300"><?= str_pad($no++, 2, '0', STR_PAD_LEFT) ?></span>
                                <input type="hidden" name="bd_id[]" value="<?= $item['bd_id'] ?>">
                            </td>
                            <td class="px-6 py-6">
                                <p class="text-[9px] font-black text-rose-600 uppercase tracking-widest mb-1"><?= $item['kecamatan_nama'] ?></p>
                                <p class="text-sm font-black text-blue-950 dark:text-white uppercase tracking-tight"><?= $item['desa_nama'] ?></p>
                            </td>
                            <td class="px-6 py-6">
                                <div class="flex items-center justify-center gap-3">
                                    <button type="button" onclick="adjustValue(this, -1)" class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 flex items-center justify-center hover:bg-rose-600 hover:text-white transition-all active:scale-90">
                                        <i data-lucide="minus" class="w-4 h-4"></i>
                                    </button>
                                    <input type="number" name="jumlah_backlog[]" value="<?= $item['jumlah_backlog'] ?>" 
                                        class="w-28 text-center bg-white dark:bg-slate-950 border-2 border-slate-100 dark:border-slate-800 rounded-xl py-3 text-lg font-black text-rose-600 focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 transition-all outline-none">
                                    <button type="button" onclick="adjustValue(this, 1)" class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 flex items-center justify-center hover:bg-emerald-600 hover:text-white transition-all active:scale-90">
                                        <i data-lucide="plus" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-6">
                                <input type="text" name="tahun[]" value="<?= $item['tahun'] ?: date('Y') ?>" 
                                    class="w-20 bg-transparent border-b border-slate-200 dark:border-slate-700 py-1 text-center font-bold text-xs outline-none focus:border-blue-500">
                            </td>
                            <td class="px-6 py-6">
                                <input type="text" name="keterangan[]" value="<?= $item['keterangan'] ?>" placeholder="Catatan..."
                                    class="w-full bg-transparent border-b border-slate-200 dark:border-slate-700 py-1 text-xs font-medium outline-none focus:border-blue-500">
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <tr class="bg-rose-600 text-white font-black uppercase tracking-widest text-[10px]">
                            <td colspan="2" class="px-6 py-8 text-right pr-12 text-lg">Total Backlog Kabupaten</td>
                            <td class="px-6 py-8 text-center">
                                <span class="text-3xl"><?= number_format($totalBacklog) ?></span>
                                <p class="text-[8px] opacity-70 mt-1">Unit Rumah</p>
                            </td>
                            <td colspan="2"></td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>

<script>
    function adjustValue(btn, delta) {
        const input = btn.parentElement.querySelector('input[type="number"]');
        let val = parseInt(input.value) || 0;
        val += delta;
        if (val < 0) val = 0;
        input.value = val;
        
        input.classList.add('scale-110');
        setTimeout(() => input.classList.remove('scale-110'), 200);
    }
</script>

<?= $this->endSection() ?>
