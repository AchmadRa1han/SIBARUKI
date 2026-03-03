<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-300">
    <div class="p-8 border-b dark:border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/50 dark:bg-slate-900/50">
        <div>
            <h1 class="text-2xl font-black text-blue-950 dark:text-white tracking-tight">Manajemen Data RTLH</h1>
            <p class="text-sm text-slate-400 dark:text-slate-500 font-medium">Integrasi data penerima, profil rumah, dan teknis bangunan</p>
        </div>
        <?php if (has_permission('create_rtlh')): ?>
        <a href="<?= base_url('rtlh/create') ?>" class="bg-blue-900 dark:bg-blue-700 hover:bg-blue-950 dark:hover:bg-blue-600 text-white px-6 py-3 rounded-2xl transition-all flex items-center space-x-2 text-sm font-bold shadow-xl shadow-blue-900/20">
            <i data-lucide="plus-circle" class="w-5 h-5"></i>
            <span>Tambah Data Terpadu</span>
        </a>
        <?php endif; ?>
    </div>

    <div class="p-4 lg:p-8">
        <?php if (session()->getFlashdata('message')) : ?>
            <div class="bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-100 dark:border-emerald-900 text-emerald-700 dark:text-emerald-400 px-6 py-4 rounded-2xl mb-8 flex items-center space-x-3 text-sm font-bold shadow-sm">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                <span><?= session()->getFlashdata('message') ?></span>
            </div>
        <?php endif; ?>

        <div class="overflow-x-auto rounded-[1.5rem] border border-slate-100 dark:border-slate-800 mb-8 shadow-inner bg-slate-50/30 dark:bg-slate-950/50">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-white dark:bg-slate-900 text-blue-950 dark:text-blue-400 uppercase text-[10px] font-black tracking-[0.15em] transition-colors duration-300">
                        <th class="p-5 border-b border-slate-100 dark:border-slate-800 text-center">ID</th>
                        <th class="p-5 border-b border-slate-100 dark:border-slate-800">Nama Pemilik</th>
                        <th class="p-5 border-b border-slate-100 dark:border-slate-800">Lokasi / Desa</th>
                        <th class="p-5 border-b border-slate-100 dark:border-slate-800">Luas Rumah</th>
                        <th class="p-5 border-b border-slate-100 dark:border-slate-800">Penghuni</th>
                        <th class="p-5 border-b border-slate-100 dark:border-slate-800 text-center uppercase">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800 text-sm text-slate-700 dark:text-slate-400 bg-white/50 dark:bg-slate-900/50 transition-colors duration-300">
                    <?php foreach ($rumah as $row) : ?>
                        <tr class="hover:bg-blue-50/50 dark:hover:bg-blue-900/10 transition-colors group">
                            <td class="p-5 text-center">
                                <span class="font-mono text-[10px] font-bold text-blue-900 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/50 px-2 py-1 rounded-md border border-blue-100 dark:border-blue-900">
                                    #<?= str_pad($row['id_survei'], 4, '0', STR_PAD_LEFT) ?>
                                </span>
                            </td>
                            <td class="p-5">
                                <p class="font-black text-slate-800 dark:text-slate-200 uppercase tracking-tight group-hover:text-blue-900 dark:group-hover:text-blue-400 transition-colors"><?= $row['pemilik'] ?? 'TIDAK DIKETAHUI' ?></p>
                            </td>
                            <td class="p-5">
                                <p class="font-bold text-slate-700 dark:text-slate-300"><?= $row['desa'] ?></p>
                                <p class="text-[10px] text-slate-400 dark:text-slate-500 truncate w-48 font-medium italic mt-0.5"><?= $row['alamat_detail'] ?></p>
                            </td>
                            <td class="p-5">
                                <span class="font-black text-blue-950 dark:text-slate-200"><?= $row['luas_rumah_m2'] ?></span>
                                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 ml-0.5 italic">m²</span>
                            </td>
                            <td class="p-5">
                                <span class="font-bold text-slate-700 dark:text-slate-300"><?= $row['jumlah_penghuni_jiwa'] ?></span>
                                <span class="text-[10px] font-medium text-slate-400 dark:text-slate-500 ml-0.5">Jiwa</span>
                            </td>
                            <td class="p-5 text-center">
                                <div class="flex justify-center items-center space-x-3">
                                    <?php if (has_permission('view_rtlh_detail')): ?>
                                    <a href="<?= base_url('rtlh/detail/' . $row['id_survei']) ?>" 
                                       class="p-2.5 bg-blue-900 dark:bg-blue-700 text-white rounded-xl shadow-lg shadow-blue-900/20 hover:bg-blue-950 dark:hover:bg-blue-600 hover:-translate-y-0.5 transition-all" 
                                       title="Lihat Detail Lengkap">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <?php endif; ?>
                                    <?php if (has_permission('delete_rtlh')): ?>
                                    <button type="button" onclick="confirmDeleteRtlh(this)" data-url="<?= base_url('rtlh/delete/' . $row['id_survei']) ?>" class="p-2 text-rose-300 dark:text-rose-900 hover:text-rose-600 dark:hover:text-rose-400 transition-colors" title="Hapus Permanen">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="p-6 bg-slate-50/50 dark:bg-slate-950/50 border-t dark:border-slate-800 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-4 min-w-[200px]">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tampilkan:</span>
                <select onchange="changePerPage(this.value)" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-1.5 text-xs font-bold text-slate-600 dark:text-slate-300 outline-none focus:ring-4 focus:ring-blue-500/10 transition-all cursor-pointer">
                    <?php foreach([10, 25, 50, 100] as $count): ?>
                        <option value="<?= $count ?>" <?= $perPage == $count ? 'selected' : '' ?>><?= $count ?> Data</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="flex-grow flex justify-center">
                <?= $pager->links('group1', 'tailwind_full') ?>
            </div>

            <!-- Spacer to maintain center alignment if needed -->
            <div class="hidden md:block min-w-[200px]"></div>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();

    async function confirmDeleteRtlh(btn) {
        const url = btn.getAttribute('data-url');
        const ok = await customConfirm('Hapus Data RTLH?', 'Data akan dipindahkan ke Recycle Bin dan bisa dipulihkan nanti.', 'danger');
        if (ok) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '<?= csrf_token() ?>';
            csrf.value = '<?= csrf_hash() ?>';
            form.appendChild(csrf);
            document.body.appendChild(form);
            form.submit();
        }
    }

    function changePerPage(count) {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', count);
        url.searchParams.set('page_group1', 1); // Reset ke hal 1
        window.location.href = url.href;
    }
</script>
<?= $this->endSection() ?>
