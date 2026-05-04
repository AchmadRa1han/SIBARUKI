<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto space-y-6 pb-12 text-slate-900 dark:text-slate-200">
    
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-3 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 no-print">
        <a href="<?= base_url('dashboard') ?>" class="hover:text-blue-600 transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">Rekapitulasi & Statistik Desa</span>
    </nav>

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-blue-600/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
        <div class="relative z-10">
            <h1 class="text-2xl md:text-4xl font-black text-blue-950 dark:text-white uppercase tracking-tighter leading-none">Rekapitulasi <span class="text-blue-600">Desa.</span></h1>
            <p class="text-slate-400 font-bold text-[10px] uppercase tracking-[0.2em] mt-3">Manajemen statistik makro, capaian rlh, dan data backlog per wilayah</p>
        </div>
        <div class="flex items-center gap-3 relative z-10">
            <a href="<?= base_url('data-perumahan/sync') ?>" class="px-6 py-3 bg-emerald-600 text-white rounded-2xl text-[10px] font-bold uppercase tracking-widest hover:bg-emerald-700 transition-all active:scale-95 flex items-center gap-2 shadow-lg shadow-emerald-600/20">
                <i data-lucide="refresh-cw" class="w-4 h-4"></i> Sinkronisasi RLH
            </a>
            <button type="submit" form="mainForm" class="px-6 py-3 bg-blue-600 text-white rounded-2xl text-[10px] font-bold uppercase tracking-widest hover:bg-blue-700 transition-all active:scale-95 flex items-center gap-2 shadow-lg shadow-blue-600/20">
                <i data-lucide="save" class="w-4 h-4"></i> Simpan Perubahan
            </button>
        </div>
    </div>

    <!-- Info Alerts -->
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 p-4 rounded-2xl flex items-start gap-4">
        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-800 rounded-xl flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0">
            <i data-lucide="info" class="w-5 h-5"></i>
        </div>
        <div>
            <h4 class="text-[10px] font-bold text-blue-950 dark:text-blue-200 uppercase tracking-widest mb-1">Panduan Pengelolaan Data</h4>
            <p class="text-xs text-blue-700/70 dark:text-blue-400/70 font-medium leading-relaxed">Gunakan tombol <b>Sinkronisasi RLH</b> untuk memperbarui kolom <b>RLH</b> berdasarkan data survei lapangan yang sudah masuk ke sistem. Kolom <b>Total Rumah</b> dan <b>Backlog</b> diisi secara manual sesuai data target wilayah Anda.</p>
        </div>
    </div>

    <!-- Main Content Table -->
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 overflow-hidden">
        <form action="<?= base_url('data-perumahan/update') ?>" method="POST" id="mainForm">
            <?= csrf_field() ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 dark:bg-slate-800/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                            <th class="px-6 py-5 w-16 text-center">No</th>
                            <th class="px-6 py-5">Kecamatan / Desa</th>
                            <th class="px-6 py-5 text-center">Data Lapangan (RTLH)</th>
                            <th class="px-6 py-5 text-center bg-blue-50/30 dark:bg-blue-900/10">Total Rumah</th>
                            <th class="px-6 py-5 text-center bg-emerald-50/30 dark:bg-emerald-900/10">Capaian RLH</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                        <?php 
                        $grandTotalRtlh = 0;
                        $grandTotalRumah = 0;
                        $grandTotalRlh = 0;
                        
                        if (!empty($data)): $no = 1; foreach($data as $item): 
                            $grandTotalRtlh += $item['total_rtlh'];
                            $grandTotalRumah += $item['jumlah_rumah'];
                            $grandTotalRlh += $item['jumlah_rlh'];
                        ?>
                        <tr class="group hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-all duration-200">
                            <td class="px-6 py-6 text-center">
                                <span class="text-[10px] font-black text-slate-300 group-hover:text-blue-600 transition-colors"><?= str_pad($no++, 2, '0', STR_PAD_LEFT) ?></span>
                                <input type="hidden" name="dp_id[]" value="<?= $item['dp_id'] ?>">
                            </td>
                            <td class="px-6 py-6">
                                <p class="text-[9px] font-black text-blue-600 uppercase tracking-widest mb-1"><?= $item['kecamatan_nama'] ?></p>
                                <p class="text-sm font-black text-blue-950 dark:text-white uppercase tracking-tight"><?= $item['desa_nama'] ?></p>
                            </td>
                            <td class="px-6 py-6 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-lg font-black text-slate-700 dark:text-slate-300"><?= $item['total_rtlh'] ?></span>
                                    <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Unit Masuk</span>
                                </div>
                            </td>
                            
                            <!-- Total Rumah (Manual) -->
                            <td class="px-6 py-6 bg-blue-50/30 dark:bg-blue-900/10">
                                <div class="relative group/input">
                                    <input type="number" name="jumlah_rumah[]" value="<?= $item['jumlah_rumah'] ?>" 
                                        class="w-24 mx-auto block text-center bg-white dark:bg-slate-950 border-2 border-slate-100 dark:border-slate-800 rounded-xl py-2 px-3 text-sm font-black text-blue-600 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                                </div>
                            </td>

                            <!-- Capaian RLH (Syncable) -->
                            <td class="px-6 py-6 bg-emerald-50/30 dark:bg-emerald-900/10">
                                <div class="flex flex-col items-center gap-2">
                                    <input type="number" name="jumlah_rlh[]" value="<?= $item['jumlah_rlh'] ?>" 
                                        class="w-24 text-center bg-white dark:bg-slate-950 border-2 border-emerald-100 dark:border-emerald-900 rounded-xl py-2 px-3 text-sm font-black text-emerald-600 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all outline-none">
                                    <p class="text-[8px] font-bold text-emerald-600/50 uppercase tracking-widest">Auto: <?= $item['rlh_auto_count'] ?></p>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <!-- Grand Total Row -->
                        <tr class="bg-blue-950 text-white font-black uppercase tracking-widest text-[10px]">
                            <td colspan="2" class="px-6 py-8 text-right pr-12">Total Seluruh Kabupaten Sinjai</td>
                            <td class="px-6 py-8 text-center">
                                <span class="text-xl"><?= number_format($grandTotalRtlh) ?></span>
                                <p class="text-[8px] opacity-60">Unit Lapangan</p>
                            </td>
                            <td class="px-6 py-8 text-center bg-blue-600/20">
                                <span class="text-xl"><?= number_format($grandTotalRumah) ?></span>
                                <p class="text-[8px] opacity-60">Total Rumah</p>
                            </td>
                            <td class="px-6 py-8 text-center bg-emerald-600/20">
                                <span class="text-xl"><?= number_format($grandTotalRlh) ?></span>
                                <p class="text-[8px] opacity-60">Capaian RLH</p>
                            </td>
                        </tr>
                        <?php else: ?>
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center opacity-20">
                                    <i data-lucide="database-zap" class="w-16 h-16 mb-4"></i>
                                    <p class="text-sm font-black uppercase tracking-widest">Database Statistik Kosong</p>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </form>
    </div>

    <!-- Action Bar (Sticky Bottom) -->
    <div class="sticky bottom-6 z-50 px-6 py-4 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-200 dark:border-slate-800 rounded-[2rem] shadow-2xl flex items-center justify-between no-print mx-4">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                <i data-lucide="check-square" class="w-5 h-5"></i>
            </div>
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Simpan hasil perubahan data makro sebelum meninggalkan halaman ini.</p>
        </div>
        <button type="submit" form="mainForm" class="px-8 py-3 bg-blue-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-700 transition-all active:scale-95 shadow-xl shadow-blue-600/20 flex items-center gap-3">
            Simpan Perubahan <i data-lucide="save" class="w-4 h-4"></i>
        </button>
    </div>
</div>

<script>
    function adjustValue(btn, delta) {
        const input = btn.parentElement.querySelector('input[type="number"]');
        let val = parseInt(input.value) || 0;
        val += delta;
        if (val < 0) val = 0;
        input.value = val;
        
        // Visual feedback
        input.classList.add('scale-110', 'border-blue-500');
        setTimeout(() => input.classList.remove('scale-110', 'border-blue-500'), 200);
    }
</script>

<?= $this->endSection() ?>
