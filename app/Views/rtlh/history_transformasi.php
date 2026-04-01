<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto space-y-6 pb-12 text-slate-900 dark:text-slate-200">
    
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-3 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 no-print">
        <a href="<?= base_url('dashboard') ?>" class="hover:text-blue-600 transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <a href="<?= base_url('rtlh') ?>" class="hover:text-blue-600 transition-colors">RTLH</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">Histori Transformasi</span>
    </nav>

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm transition-all duration-300 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-48 h-48 bg-emerald-600/5 rounded-full -mr-24 -mt-24 blur-3xl"></div>
        <div class="relative z-10">
            <h1 class="text-2xl md:text-3xl font-black text-blue-950 dark:text-white uppercase tracking-tighter">Histori Transformasi</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium text-xs mt-1">Rekam jejak perubahan kondisi rumah dari tidak layak menjadi layak huni.</p>
        </div>
        <div class="flex items-center gap-3 relative z-10">
            <a href="<?= base_url('rtlh') ?>" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-slate-700 transition-all active:scale-95 flex items-center gap-2">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
            </a>
        </div>
    </div>

    <!-- History List -->
    <div class="grid grid-cols-1 gap-6">
        <?php if (!empty($history)): foreach($history as $item): 
            $dataSebelum = json_decode($item['data_sebelum'], true);
            $rumahSeb = $dataSebelum['rumah'] ?? [];
            $kondisiSeb = $dataSebelum['kondisi'] ?? [];
        ?>
        <div class="bg-white dark:bg-slate-900 rounded-[2rem] shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden transition-all duration-300">
            <!-- Card Header -->
            <div class="p-6 border-b dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-600/20">
                        <i data-lucide="history" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-blue-950 dark:text-white uppercase tracking-tight"><?= $item['nama_penerima'] ?></h3>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-[0.2em] mt-0.5"><?= $item['nik'] ?> <span class="mx-2 text-slate-300">•</span> <?= $rumahSeb['desa'] ?? '-' ?></p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <div class="px-4 py-2 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-xl text-[9px] font-black uppercase tracking-widest border border-emerald-100 dark:border-emerald-800">
                        Program: <?= $item['sumber_bantuan'] ?>
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
                            <span class="w-8 h-8 bg-amber-100 dark:bg-amber-900/30 text-amber-600 rounded-lg flex items-center justify-center font-black text-xs shadow-sm">01</span>
                            <div>
                                <h4 class="text-[10px] font-black text-amber-600 uppercase tracking-[0.3em]">Kondisi Awal</h4>
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Status: Tidak Layak Huni</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <?php 
                                $mainFields = [
                                    ['label' => 'Struktur Atap', 'val' => ($ref[$kondisiSeb['st_atap'] ?? ''] ?? 'N/A')],
                                    ['label' => 'Kondisi Dinding', 'val' => ($ref[$kondisiSeb['st_dinding'] ?? ''] ?? 'N/A')],
                                    ['label' => 'Kondisi Lantai', 'val' => ($ref[$kondisiSeb['st_lantai'] ?? ''] ?? 'N/A')],
                                    ['label' => 'Struktur Pondasi', 'val' => $ref[$kondisiSeb['st_pondasi'] ?? ''] ?? 'N/A'],
                                ];
                                foreach($mainFields as $f):
                            ?>
                            <div class="p-4 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-100 dark:border-slate-800/50">
                                <p class="text-[8px] font-black text-slate-400 uppercase mb-1.5 tracking-widest"><?= $f['label'] ?></p>
                                <p class="text-[10px] font-black text-slate-700 dark:text-white uppercase tracking-wider"><?= $f['val'] ?></p>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="p-5 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800/50 space-y-4">
                            <h5 class="text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-200 dark:border-slate-800 pb-2 flex items-center gap-2">
                                <i data-lucide="info" class="w-3 h-3"></i> Fasilitas & Dimensi
                            </h5>
                            <div class="grid grid-cols-2 gap-y-4 gap-x-6">
                                <div>
                                    <p class="text-[7px] font-black text-slate-400 uppercase mb-0.5 tracking-widest">Luas</p>
                                    <p class="text-[10px] font-black text-blue-600 dark:text-blue-400 italic"><?= $rumahSeb['luas_rumah_m2'] ?? '0' ?> m² / <?= $rumahSeb['luas_lahan_m2'] ?? '0' ?> m²</p>
                                </div>
                                <div>
                                    <p class="text-[7px] font-black text-slate-400 uppercase mb-0.5 tracking-widest">Penghuni</p>
                                    <p class="text-[10px] font-black text-slate-700 dark:text-white uppercase"><?= $rumahSeb['jumlah_penghuni_jiwa'] ?? '0' ?> Jiwa</p>
                                </div>
                                <div>
                                    <p class="text-[7px] font-black text-slate-400 uppercase mb-0.5 tracking-widest">Air Minum</p>
                                    <p class="text-[10px] font-black text-slate-700 dark:text-white uppercase truncate"><?= $rumahSeb['sumber_air_minum'] ?? 'N/A' ?></p>
                                </div>
                                <div>
                                    <p class="text-[7px] font-black text-slate-400 uppercase mb-0.5 tracking-widest">Sanitasi</p>
                                    <p class="text-[10px] font-black text-slate-700 dark:text-white uppercase truncate"><?= $rumahSeb['kamar_mandi_dan_jamban'] ?? 'N/A' ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- AFTER (RLH) -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 rounded-lg flex items-center justify-center font-black text-xs shadow-sm">02</span>
                            <div>
                                <h4 class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.3em]">Kondisi Akhir</h4>
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Status: Layak Huni (RLH)</p>
                            </div>
                        </div>

                        <div class="bg-emerald-50 dark:bg-emerald-950/30 p-6 rounded-2xl border border-emerald-100 dark:border-emerald-900/30 relative overflow-hidden group h-full flex flex-col justify-center">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-600/5 rounded-full -mr-16 -mt-16 blur-2xl"></div>
                            <div class="flex items-start gap-4 relative z-10">
                                <div class="w-12 h-12 bg-white dark:bg-slate-800 rounded-xl flex items-center justify-center text-emerald-600 shadow-xl border border-emerald-50 dark:border-emerald-900/50 flex-shrink-0">
                                    <i data-lucide="check-circle-2" class="w-7 h-7"></i>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-emerald-800 dark:text-emerald-400 uppercase tracking-[0.2em] mb-2">Keterangan Transformasi</p>
                                    <p class="text-sm font-bold text-slate-700 dark:text-slate-200 leading-relaxed italic">"<?= $item['keterangan'] ?: 'Rumah telah diperbaiki secara menyeluruh dan dinyatakan memenuhi standar teknis layak huni (RLH).' ?>"</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-blue-950 rounded-xl text-white shadow-lg relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-24 h-24 bg-white/5 rounded-full -mr-12 -mt-12 blur-xl"></div>
                            <div class="flex items-center gap-3 relative z-10">
                                <div class="w-7 h-7 bg-white/10 rounded-lg flex items-center justify-center">
                                    <i data-lucide="calendar" class="w-3.5 h-3.5 text-blue-400"></i>
                                </div>
                                <span class="text-[9px] font-black uppercase tracking-widest text-blue-300">Verifikasi</span>
                            </div>
                            <span class="text-[10px] font-mono font-black relative z-10"><?= date('d/m/y H:i', strtotime($item['created_at'])) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; else: ?>
            <div class="bg-white dark:bg-slate-900 p-20 rounded-[2.5rem] text-center border border-dashed border-slate-200 dark:border-slate-800 transition-all duration-300">
                <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300 shadow-inner">
                    <i data-lucide="clipboard-x" class="w-10 h-10"></i>
                </div>
                <h3 class="text-xl font-black text-slate-400 uppercase tracking-tighter">Belum Ada Histori</h3>
                <p class="text-slate-400 mt-2 font-medium max-w-xs mx-auto text-sm">Rekam jejak akan muncul secara otomatis setelah data RTLH ditandai sebagai "Tuntas Bantuan".</p>
            </div>
        <?php endif; ?>
    </div>

    <?php if (!empty($pager)): ?>
    <div class="flex justify-center mt-10">
        <?= $pager->links('default', 'tailwind_full') ?>
    </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
