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

        <!-- Toolbar Filter Lanjutan (Khusus Admin/Global) -->
        <?php if (session()->get('role_scope') === 'global') : ?>
        <div class="mb-8 bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm transition-all">
            <div class="flex items-center gap-4 mb-8 border-b dark:border-slate-800 pb-6">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-900 dark:text-blue-400 shadow-inner">
                    <i data-lucide="sliders-horizontal" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black text-blue-950 dark:text-white uppercase tracking-widest">Pusat Filter Data</h3>
                    <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase">Analisis data RTLH berdasarkan berbagai parameter teknis</p>
                </div>
                <?php if(array_filter($filters)): ?>
                <a href="<?= base_url('rtlh') ?>" class="ml-auto flex items-center gap-2 px-4 py-2 bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-rose-100 transition-all border border-rose-100 dark:border-rose-900">
                    <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> Reset Semua Filter
                </a>
                <?php endif; ?>
            </div>
            
            <form action="<?= base_url('rtlh') ?>" method="get" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php if ($keyword = request()->getGet('keyword')): ?>
                    <input type="hidden" name="keyword" value="<?= $keyword ?>">
                <?php endif; ?>
                
                <!-- Filter Desa -->
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-[0.2em] ml-1">Wilayah Desa</label>
                    <div class="relative">
                        <select name="desa_id" onchange="this.form.submit()" class="w-full pl-5 pr-10 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-2xl text-xs font-bold text-slate-700 dark:text-slate-300 outline-none focus:ring-4 focus:ring-blue-500/10 appearance-none cursor-pointer transition-all">
                            <option value="">Seluruh Desa</option>
                            <?php foreach($all_desa as $d): ?>
                                <option value="<?= $d['desa_id'] ?>" <?= ($filters['desa_id'] == $d['desa_id']) ? 'selected' : '' ?>><?= $d['desa_nama'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400"></i>
                    </div>
                </div>

                <!-- Filter Kepemilikan -->
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-[0.2em] ml-1">Status Milik</label>
                    <div class="relative">
                        <select name="milik" onchange="this.form.submit()" class="w-full pl-5 pr-10 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-2xl text-xs font-bold text-slate-700 dark:text-slate-300 outline-none focus:ring-4 focus:ring-blue-500/10 appearance-none cursor-pointer transition-all">
                            <option value="">Semua Status</option>
                            <?php foreach($options['milik'] as $m): ?>
                                <option value="<?= $m ?>" <?= ($filters['milik'] == $m) ? 'selected' : '' ?>><?= $m ?></option>
                            <?php endforeach; ?>
                        </select>
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400"></i>
                    </div>
                </div>

                <!-- Filter Kawasan -->
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-[0.2em] ml-1">Jenis Kawasan</label>
                    <div class="relative">
                        <select name="kawasan" onchange="this.form.submit()" class="w-full pl-5 pr-10 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-2xl text-xs font-bold text-slate-700 dark:text-slate-300 outline-none focus:ring-4 focus:ring-blue-500/10 appearance-none cursor-pointer transition-all">
                            <option value="">Semua Kawasan</option>
                            <?php foreach($options['kawasan'] as $k): ?>
                                <option value="<?= $k ?>" <?= ($filters['kawasan'] == $k) ? 'selected' : '' ?>><?= $k ?></option>
                            <?php endforeach; ?>
                        </select>
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400"></i>
                    </div>
                </div>

                <!-- Filter Air Minum -->
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-blue-900 dark:text-blue-400 uppercase tracking-[0.2em] ml-1">Sumber Air</label>
                    <div class="relative">
                        <select name="air" onchange="this.form.submit()" class="w-full pl-5 pr-10 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-2xl text-xs font-bold text-slate-700 dark:text-slate-300 outline-none focus:ring-4 focus:ring-blue-500/10 appearance-none cursor-pointer transition-all">
                            <option value="">Semua Sumber</option>
                            <?php foreach($options['air'] as $a): ?>
                                <option value="<?= $a ?>" <?= ($filters['air'] == $a) ? 'selected' : '' ?>><?= $a ?></option>
                            <?php endforeach; ?>
                        </select>
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400"></i>
                    </div>
                </div>
            </form>
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
                                <p class="text-[10px] font-bold text-blue-900/40 dark:text-blue-500/40 uppercase font-mono mt-0.5"><?= $row['nik_pemilik'] ?></p>
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
                                    <a href="<?= base_url('rtlh/detail/' . $row['id_survei']) ?>" 
                                       class="p-2.5 bg-blue-900 dark:bg-blue-700 text-white rounded-xl shadow-lg shadow-blue-900/20 hover:bg-blue-950 dark:hover:bg-blue-600 hover:-translate-y-0.5 transition-all" 
                                       title="Lihat Detail Lengkap">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
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
