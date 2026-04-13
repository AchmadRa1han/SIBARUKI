<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="space-y-6 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm relative overflow-hidden transition-all duration-300">
        <div class="absolute top-0 right-0 w-48 h-48 bg-emerald-600/5 rounded-full -mr-24 -mt-24 blur-3xl"></div>
        <div class="relative z-10 flex items-center gap-4">
            <a href="<?= base_url('dashboard') ?>" class="p-3 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl hover:bg-emerald-600 hover:text-white transition-all active:scale-95" title="Kembali">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-blue-950 dark:text-white uppercase tracking-tighter">Realisasi Bansos</h1>
                <p class="text-slate-500 dark:text-slate-400 font-medium text-xs mt-1">Rekam Jejak Realisasi Bantuan Perumahan Kabupaten Sinjai.</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2 relative z-10">
            <a href="<?= base_url('rtlh?status=Sudah Menerima') ?>" class="bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 px-4 py-2 rounded-xl text-[9px] font-bold uppercase tracking-widest border border-blue-100 dark:border-blue-800/50 shadow-sm hover:bg-blue-600 hover:text-white transition-all active:scale-95 flex items-center gap-2">
                <i data-lucide="home" class="w-3.5 h-3.5"></i> Lihat Unit RLH
            </a>
            <?php if (has_permission('edit_rtlh')): ?>
            <a href="<?= base_url('bansos-rtlh/create') ?>" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl text-[9px] font-bold uppercase tracking-widest shadow-lg shadow-emerald-600/20 transition-all active:scale-95 flex items-center gap-2 group">
                <i data-lucide="plus" class="w-4 h-4 group-hover:rotate-90 transition-transform"></i> Input Realisasi
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Filter & Search Card -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-100 dark:border-slate-800 p-3">
        <div class="flex flex-col lg:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-3 w-full lg:w-auto">
                <div class="flex bg-slate-100 dark:bg-slate-800 p-1 rounded-xl w-full md:w-auto">
                    <button class="px-4 py-2 bg-white dark:bg-slate-700 text-emerald-600 rounded-lg text-[9px] font-bold uppercase tracking-widest shadow-sm">Daftar Realisasi</button>
                </div>
            </div>

            <form action="<?= base_url('bansos-rtlh') ?>" method="get" class="flex flex-col md:flex-row items-center gap-2 w-full lg:w-auto" id="filter-form">
                <div class="relative w-full md:w-28">
                    <select name="per_page" onchange="submitWithScroll(this)" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-[9px] font-bold uppercase px-3 py-2 focus:ring-2 focus:ring-emerald-500 cursor-pointer appearance-none">
                        <?php foreach([5, 10, 25, 50, 100] as $p): ?>
                            <option value="<?= $p ?>" <?= ($perPage ?? 10) == $p ? 'selected' : '' ?>><?= $p ?> Baris</option>
                        <?php endforeach; ?>
                    </select>
                    <i data-lucide="chevron-down" class="w-3 h-3 text-slate-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                </div>

                <div class="relative w-full md:w-64">
                    <input type="text" name="keyword" value="<?= $keyword ?? '' ?>" placeholder="Cari Nama / NIK..." class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-[9px] font-bold uppercase px-3 py-2 pl-10 focus:ring-2 focus:ring-emerald-500 transition-all">
                    <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden relative">
        <!-- Floating Bulk Action Bar (Template only, function not yet added to controller) -->
        <div id="bulk-action-bar" class="absolute top-0 left-0 right-0 z-50 bg-blue-950 text-white p-4 transform -translate-y-full transition-transform duration-500 flex items-center justify-between px-8">
            <div class="flex items-center gap-4">
                <span id="selected-count" class="bg-emerald-600 px-3 py-1 rounded-lg text-[9px] font-bold tracking-widest shadow-lg shadow-emerald-600/20">0 TERPILIH</span>
                <p class="text-[9px] font-bold uppercase tracking-widest opacity-70 hidden md:block">Aksi massal tersedia</p>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="clearSelection()" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg text-[9px] font-bold uppercase tracking-widest transition-all active:scale-95">Batal</button>
            </div>
        </div>

        <div class="p-6 border-b border-slate-50 dark:border-slate-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-emerald-600/20">
                    <i data-lucide="award" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-blue-950 dark:text-white uppercase tracking-tight">Penerima Manfaat</h3>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-[0.2em]">Histori Penyaluran Bantuan Tuntas</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse table-fixed">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/50 text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                        <th class="px-6 py-4 w-16 text-center">
                            <input type="checkbox" id="select-all" class="w-4.5 h-4.5 rounded-lg border-2 border-slate-200 text-emerald-600 focus:ring-emerald-600/20 cursor-pointer transition-all">
                        </th>
                        <th class="px-4 py-4 w-64">Penerima Bantuan</th>
                        <th class="px-4 py-4 w-32 text-center">Tahun</th>
                        <th class="px-4 py-4 w-48">Sumber Dana</th>
                        <th class="px-6 py-4 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800 text-[10px]">
                    <?php if (!empty($bansos)): foreach($bansos as $item): ?>
                    <tr class="group hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-all duration-200">
                        <td class="px-6 py-3 text-center">
                            <input type="checkbox" name="ids[]" value="<?= $item['id'] ?>" class="row-checkbox w-4.5 h-4.5 rounded-lg border-2 border-slate-200 text-emerald-600 focus:ring-emerald-600/20 cursor-pointer transition-all">
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex flex-col gap-0.5">
                                <span class="font-bold text-blue-950 dark:text-white uppercase truncate block text-xs mb-0.5"><?= $item['nama_penerima'] ?></span>
                                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest"><?= $item['nik'] ?> | <?= $item['desa'] ?></span>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="px-2.5 py-0.5 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 rounded-lg font-bold text-[9px]">
                                <?= $item['tahun_anggaran'] ?>
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <span class="font-bold text-slate-600 dark:text-slate-400 uppercase tracking-tight"><?= $item['sumber_dana'] ?></span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <?php if($item['id_survei']): ?>
                                <a href="<?= base_url('rtlh/detail/'.$item['id_survei']) ?>" class="p-2 bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-600 hover:text-white transition-all shadow-sm border border-blue-100 dark:border-blue-900" title="Profil RTLH"><i data-lucide="home" class="w-3.5 h-3.5"></i></a>
                                <?php endif; ?>
                                <?php if (has_permission('delete_rtlh')): ?>
                                <button onclick="confirmDelete(<?= $item['id'] ?>, '<?= addslashes($item['nama_penerima']) ?>')" class="p-2 bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 rounded-lg hover:bg-rose-600 hover:text-white transition-all active:scale-95" title="Hapus"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="5" class="px-8 py-16 text-center text-slate-400 font-bold uppercase text-[9px] tracking-[0.3em]">Belum ada data realisasi bansos</td></tr>
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
    function submitWithScroll(el) {
        const mc = document.getElementById('main-content');
        if (mc) localStorage.setItem('bansosScrollPos', mc.scrollTop);
        const form = el.tagName === 'FORM' ? el : el.form;
        if (form) form.submit();
    }

    const selectAll = document.getElementById('select-all');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    const bulkBar = document.getElementById('bulk-action-bar');
    const selectedCount = document.getElementById('selected-count');

    function updateBulkBar() {
        const checked = document.querySelectorAll('.row-checkbox:checked');
        if (checked.length > 0) { bulkBar.classList.remove('-translate-y-full'); selectedCount.innerText = `${checked.length} TERPILIH`; }
        else { bulkBar.classList.add('-translate-y-full'); }
    }

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            rowCheckboxes.forEach(cb => {
                cb.checked = this.checked;
                const row = cb.closest('tr');
                if (this.checked) row.classList.add('bg-blue-50/50', 'dark:bg-blue-900/10');
                else row.classList.remove('bg-blue-50/50', 'dark:bg-blue-900/10');
            });
            updateBulkBar();
        });
    }

    rowCheckboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            const row = this.closest('tr');
            if (this.checked) row.classList.add('bg-blue-50/50', 'dark:bg-blue-900/10');
            else row.classList.remove('bg-blue-50/50', 'dark:bg-blue-900/10');
            const allChecked = document.querySelectorAll('.row-checkbox:checked').length === rowCheckboxes.length;
            if(selectAll) selectAll.checked = allChecked;
            updateBulkBar();
        });
    });

    function clearSelection() {
        if(selectAll) selectAll.checked = false;
        rowCheckboxes.forEach(cb => { cb.checked = false; cb.closest('tr').classList.remove('bg-blue-50/50', 'dark:bg-blue-900/10'); });
        updateBulkBar();
    }

    function confirmDelete(id, name) {
        customConfirm('Hapus Histori Bansos?', `Hapus data realisasi untuk ${name}?`, 'danger').then(conf => {
            if (conf) {
                const f = document.getElementById('delete-form');
                f.action = `<?= base_url('bansos-rtlh/delete') ?>/${id}`;
                f.submit();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const mc = document.getElementById('main-content');
        if (mc) {
            const sp = localStorage.getItem('bansosScrollPos');
            if (sp) { setTimeout(() => { mc.scrollTop = sp; localStorage.removeItem('bansosScrollPos'); }, 100); }
        }
    });
</script>
<?= $this->endSection() ?>
