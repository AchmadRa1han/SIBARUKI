<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="space-y-6 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-blue-950 dark:text-white uppercase tracking-tight">Bansos Perbaikan RTLH</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium text-sm">Rekam Jejak Realisasi Bantuan Perumahan Kabupaten Sinjai.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="<?= base_url('rtlh?status=Sudah Menerima') ?>" class="bg-blue-50 text-blue-600 px-4 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest shadow-sm border border-blue-100 hover:bg-blue-600 hover:text-white transition-all flex items-center gap-2">
                <i data-lucide="home" class="w-3.5 h-3.5"></i> Lihat Unit RLH
            </a>
            <?php if (has_permission('edit_rtlh')): ?>
            <a href="<?= base_url('bansos-rtlh/create') ?>" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest shadow-xl transition-all flex items-center gap-2 group">
                <i data-lucide="plus" class="w-3.5 h-3.5 group-hover:rotate-90 transition-transform"></i> Input Realisasi
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="p-6 border-b border-slate-50 dark:border-slate-800 flex flex-col lg:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-emerald-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-emerald-900/20">
                    <i data-lucide="award" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-blue-950 dark:text-white uppercase tracking-tight">Daftar Penerima Manfaat</h3>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Histori penyaluran bantuan tuntas</p>
                </div>
            </div>

            <form action="<?= base_url('bansos-rtlh') ?>" method="get" class="relative w-full md:w-64">
                <input type="text" name="keyword" value="<?= $keyword ?? '' ?>" placeholder="Cari Nama / NIK..." class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-[9px] font-bold px-3 py-2.5 pl-9 focus:ring-2 focus:ring-emerald-500 transition-all uppercase">
                <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/50 text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                        <th class="px-8 py-4">Penerima Bantuan</th>
                        <th class="px-8 py-4 text-center">Tahun</th>
                        <th class="px-8 py-4">Sumber Dana</th>
                        <th class="px-8 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800 text-[10px]">
                    <?php if (!empty($bansos)): foreach($bansos as $item): ?>
                    <tr class="group hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-all duration-200">
                        <td class="px-8 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-blue-950 dark:text-white uppercase"><?= $item['nama_penerima'] ?></span>
                                <span class="text-[8px] font-mono text-slate-400"><?= $item['nik'] ?> | <?= $item['desa'] ?></span>
                            </div>
                        </td>
                        <td class="px-8 py-4 text-center">
                            <span class="px-3 py-1 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 rounded-lg font-bold"><?= $item['tahun_anggaran'] ?></span>
                        </td>
                        <td class="px-8 py-4">
                            <span class="font-bold text-slate-600 dark:text-slate-400 uppercase"><?= $item['sumber_dana'] ?></span>
                        </td>
                        <td class="px-8 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <?php if($item['id_survei']): ?>
                                <a href="<?= base_url('rtlh/detail/'.$item['id_survei']) ?>" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-all" title="Lihat Profil RTLH"><i data-lucide="home" class="w-3.5 h-3.5"></i></a>
                                <?php endif; ?>
                                <?php if (has_permission('delete_rtlh')): ?>
                                <button onclick="confirmDelete(<?= $item['id'] ?>, '<?= addslashes($item['nama_penerima']) ?>')" class="p-2 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-600 hover:text-white transition-all"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="4" class="px-8 py-12 text-center text-slate-400 font-bold uppercase text-[10px]">Belum ada data realisasi bansos</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if (!empty($pager)): ?>
        <div class="p-6 bg-slate-50/50 dark:bg-slate-800/50 flex justify-center border-t border-slate-100 dark:border-slate-800">
            <?= $pager->links('default', 'tailwind_full') ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<form id="delete-form" action="" method="post" class="hidden"><?= csrf_field() ?></form>

<script>
    function confirmDelete(id, name) {
        customConfirm('Hapus Histori Bansos?', `Hapus data realisasi untuk ${name}?`, 'danger').then(conf => {
            if (conf) {
                const f = document.getElementById('delete-form');
                f.action = `<?= base_url('bansos-rtlh/delete') ?>/${id}`;
                f.submit();
            }
        });
    }
</script>
<?= $this->endSection() ?>
