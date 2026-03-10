<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="space-y-6 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-blue-950 dark:text-white uppercase tracking-tight">Histori Transformasi RTLH</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium text-sm">Rekam jejak perubahan kondisi rumah dari tidak layak menjadi layak huni.</p>
        </div>
        <a href="<?= base_url('rtlh') ?>" class="bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm border border-slate-200 dark:border-slate-700 hover:bg-slate-50 transition-all flex items-center gap-2 w-fit">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Kembali ke Data
        </a>
    </div>

    <!-- History List -->
    <div class="grid grid-cols-1 gap-6">
        <?php if (!empty($history)): foreach($history as $item): 
            $dataSebelum = json_decode($item['data_sebelum'], true);
            $rumahSeb = $dataSebelum['rumah'] ?? [];
            $kondisiSeb = $dataSebelum['kondisi'] ?? [];
        ?>
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
            <!-- Card Header -->
            <div class="p-6 border-b dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-900 rounded-2xl flex items-center justify-center text-white shadow-lg">
                        <i data-lucide="history" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-blue-950 dark:text-white uppercase tracking-tight"><?= $item['nama_penerima'] ?></h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest"><?= $item['nik'] ?> | <?= $rumahSeb['desa'] ?? '-' ?></p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <div class="px-4 py-2 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 rounded-xl text-[9px] font-black uppercase tracking-widest border border-emerald-200 dark:border-emerald-800">
                        Sumber: <?= $item['sumber_bantuan'] ?>
                    </div>
                    <div class="px-4 py-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl text-[9px] font-black uppercase tracking-widest border border-blue-100 dark:border-blue-800">
                        Tahun <?= $item['tahun_anggaran'] ?>
                    </div>
                </div>
            </div>

            <!-- Comparison Body -->
            <div class="p-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 relative">
                    <!-- Divider Line -->
                    <div class="hidden lg:block absolute left-1/2 top-0 bottom-0 w-px bg-slate-100 dark:bg-slate-800 -translate-x-1/2"></div>

                    <!-- BEFORE (RTLH) -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="w-8 h-8 bg-amber-100 dark:bg-amber-900/30 text-amber-600 rounded-lg flex items-center justify-center font-black text-xs">1</span>
                            <h4 class="text-[10px] font-black text-amber-600 uppercase tracking-[0.2em]">Kondisi Awal (RTLH)</h4>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <?php 
                                $fields = [
                                    ['label' => 'Kondisi Atap', 'val' => $ref[$kondisiSeb['st_atap'] ?? ''] ?? 'N/A'],
                                    ['label' => 'Kondisi Dinding', 'val' => $ref[$kondisiSeb['st_dinding'] ?? ''] ?? 'N/A'],
                                    ['label' => 'Kondisi Lantai', 'val' => $ref[$kondisiSeb['st_lantai'] ?? ''] ?? 'N/A'],
                                    ['label' => 'Material Dinding', 'val' => $ref[$kondisiSeb['mat_dinding'] ?? ''] ?? 'N/A'],
                                ];
                                foreach($fields as $f):
                            ?>
                            <div class="p-4 bg-slate-50 dark:bg-slate-950/50 rounded-2xl border border-slate-100 dark:border-slate-800/50">
                                <p class="text-[8px] font-bold text-slate-400 uppercase mb-1"><?= $f['label'] ?></p>
                                <p class="text-[11px] font-black text-slate-700 dark:text-slate-300 uppercase"><?= $f['val'] ?></p>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- AFTER (RLH) -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 rounded-lg flex items-center justify-center font-black text-xs">2</span>
                            <h4 class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em]">Kondisi Pasca Bantuan (RLH)</h4>
                        </div>

                        <!-- Info Transformasi -->
                        <div class="bg-emerald-50 dark:bg-emerald-900/10 p-6 rounded-[2rem] border border-emerald-100 dark:border-emerald-900/30">
                            <div class="flex items-start gap-4">
                                <div class="p-3 bg-white dark:bg-slate-800 rounded-2xl text-emerald-600 shadow-sm border border-emerald-100 dark:border-emerald-800">
                                    <i data-lucide="check-circle-2" class="w-6 h-6"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-emerald-800 dark:text-emerald-400 uppercase tracking-widest mb-1">Status Perubahan</p>
                                    <p class="text-sm font-bold text-slate-700 dark:text-slate-300 leading-relaxed italic">"<?= $item['keterangan'] ?: 'Rumah telah diperbaiki dan dinyatakan layak huni sesuai standar teknis.' ?>"</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-blue-950 rounded-2xl text-white shadow-xl">
                            <div class="flex items-center gap-3">
                                <i data-lucide="info" class="w-4 h-4 text-blue-400"></i>
                                <span class="text-[9px] font-black uppercase tracking-widest">Verifikasi Tuntas</span>
                            </div>
                            <span class="text-[10px] font-mono opacity-60"><?= date('d/m/Y H:i', strtotime($item['created_at'])) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; else: ?>
            <div class="bg-white dark:bg-slate-900 p-20 rounded-[3rem] text-center border border-dashed border-slate-200 dark:border-slate-800">
                <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                    <i data-lucide="clipboard-x" class="w-10 h-10"></i>
                </div>
                <h3 class="text-xl font-black text-slate-400 uppercase tracking-widest">Belum ada histori transformasi</h3>
                <p class="text-sm text-slate-400 mt-2 font-medium">Data akan muncul di sini setelah Anda menandai data RTLH sebagai "Tuntas".</p>
            </div>
        <?php endif; ?>
    </div>

    <?php if (!empty($pager)): ?>
    <div class="flex justify-center mt-8">
        <?= $pager->links('default', 'tailwind_full') ?>
    </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
