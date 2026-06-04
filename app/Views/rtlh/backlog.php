<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto space-y-6 pb-24 text-slate-900 dark:text-slate-200">
    
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-3 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 no-print">
        <a href="<?= base_url('dashboard') ?>" class="hover:text-blue-600 transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <a href="<?= base_url('rtlh') ?>" class="hover:text-blue-600 transition-colors">Data Rumah</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-blue-600">Manajemen Backlog</span>
    </nav>

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-blue-950 p-7 rounded-[2.5rem] text-white shadow-2xl shadow-blue-950/20 relative overflow-hidden transition-all duration-500">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-32 -mt-32 blur-3xl group-hover:scale-110 transition-transform duration-700"></div>
        <div class="relative z-10 flex items-center gap-5">
            <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-md border border-white/10 shadow-inner">
                <i data-lucide="alert-triangle" class="w-6 h-6 text-amber-400"></i>
            </div>
            <div>
                <h1 class="text-2xl md:text-3xl font-black uppercase tracking-tighter leading-none">Manajemen Backlog</h1>
                <p class="text-white/60 font-medium text-xs mt-2 tracking-wide">Data Individu Keluarga Belum Memiliki Rumah (By Name By Address)</p>
            </div>
        </div>
        <div class="flex items-center gap-3 relative z-10">
            <button onclick="toggleModal('modal-add')" class="bg-white text-blue-950 px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-[0.15em] shadow-xl hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i> Tambah Individu
            </button>
        </div>
    </div>

    <!-- Stats Summary (Optional/Concept) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-rose-50 dark:bg-rose-950/30 text-rose-600 rounded-xl flex items-center justify-center"><i data-lucide="users" class="w-6 h-6"></i></div>
            <div>
                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Total Terdata</p>
                <h3 class="text-2xl font-black text-blue-950 dark:text-white"><?= number_format($pager->getTotal()) ?></h3>
            </div>
        </div>
    </div>

    <!-- Filter & Table Card -->
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-xl overflow-hidden transition-all duration-500">
        <div class="p-8 border-b dark:border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <form action="<?= base_url('rtlh/backlog') ?>" method="get" class="flex flex-col md:flex-row items-center gap-3 w-full lg:w-auto">
                <div class="flex items-center gap-2 bg-blue-950/5 dark:bg-slate-800 px-3 py-1.5 rounded-xl border border-blue-950/10">
                    <span class="text-[9px] font-black text-blue-950/40 uppercase tracking-widest">Tampil</span>
                    <select name="per_page" onchange="this.form.submit()" class="bg-transparent border-none text-xs font-black text-blue-950 dark:text-white outline-none">
                        <?php foreach([10, 25, 50, 100] as $p): ?>
                            <option value="<?= $p ?>" <?= $perPage == $p ? 'selected' : '' ?>><?= $p ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="relative w-full md:w-80 group">
                    <input type="text" name="keyword" value="<?= $keyword ?>" placeholder="Cari NIK, Nama, atau Desa..." class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-blue-600 transition-all outline-none">
                    <i data-lucide="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-blue-600"></i>
                </div>
                <button type="submit" class="w-full md:w-auto px-8 py-3 bg-blue-950 dark:bg-blue-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:shadow-lg transition-all active:scale-95">Filter</button>
                <?php if($keyword): ?>
                    <a href="<?= base_url('rtlh/backlog') ?>" class="p-3 text-slate-400 hover:text-rose-500 transition-colors"><i data-lucide="refresh-cw" class="w-4 h-4"></i></a>
                <?php endif; ?>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
                        <th class="px-8 py-5">Informasi Individu</th>
                        <th class="px-8 py-5">Lokasi Terkini</th>
                        <th class="px-8 py-5">Status Hunian Saat Ini</th>
                        <th class="px-8 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-slate-800">
                    <?php if(!empty($backlog)): foreach($backlog as $item): ?>
                    <tr class="group hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-all duration-300">
                        <td class="px-8 py-6">
                            <p class="text-sm font-black text-blue-950 dark:text-white uppercase tracking-tight"><?= $item['nama_lengkap'] ?></p>
                            <div class="flex items-center gap-3 mt-1">
                                <span class="text-[10px] font-bold text-slate-400 tracking-widest uppercase">NIK: <?= $item['nik'] ?></span>
                                <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                <span class="text-[10px] font-bold text-slate-400 tracking-widest uppercase">KK: <?= $item['no_kk'] ?: '-' ?></span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <p class="text-[11px] font-black text-slate-700 dark:text-slate-300 uppercase"><?= $item['desa'] ?></p>
                            <p class="text-[10px] font-medium text-slate-400 mt-0.5 line-clamp-1 italic"><?= $item['alamat_detail'] ?: 'Alamat tidak diinput' ?></p>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded text-[9px] font-black uppercase tracking-widest border border-blue-100 dark:border-blue-800"><?= $item['keterangan_hunian'] ?: 'Menumpang' ?></span>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Di Rumah:</span>
                                </div>
                                <p class="text-[11px] font-black text-blue-950 dark:text-slate-200 uppercase"><?= $item['nama_pemilik_rumah'] ?: '-' ?></p>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex justify-end gap-2">
                                <button onclick="editBacklog(<?= htmlspecialchars(json_encode($item)) ?>)" class="p-2.5 bg-amber-500 text-white rounded-xl shadow-lg shadow-amber-500/20 hover:scale-110 active:scale-95 transition-all" title="Edit"><i data-lucide="edit-3" class="w-4 h-4"></i></button>
                                <button onclick="deleteBacklog(<?= $item['id'] ?>)" class="p-2.5 bg-rose-500 text-white rounded-xl shadow-lg shadow-rose-500/20 hover:scale-110 active:scale-95 transition-all" title="Hapus"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center opacity-20">
                                <i data-lucide="database-zap" class="w-16 h-16 mb-4 text-slate-400"></i>
                                <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-500">Data backlog tidak ditemukan</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="p-8 border-t dark:border-slate-800 bg-slate-50/30 dark:bg-slate-900/50">
            <?= $pager->makeLinks(1, $perPage, $pager->getTotal(), 'tailwind_pager') ?>
        </div>
    </div>
</div>

<!-- MODAL ADD -->
<div id="modal-add" class="fixed inset-0 z-[2000] hidden overflow-y-auto py-10">
    <div class="fixed inset-0 bg-blue-950/60 backdrop-blur-sm transition-opacity"></div>
    <div class="relative flex items-center justify-center p-4 min-h-full">
        <div class="bg-white dark:bg-slate-900 w-full max-w-2xl rounded-[2.5rem] shadow-2xl overflow-hidden animate-in zoom-in duration-300">
            <div class="p-8 bg-blue-950 text-white flex justify-between items-center border-b border-white/10 sticky top-0 z-20">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-white/10 rounded-2xl backdrop-blur-md"><i data-lucide="user-plus" class="w-6 h-6"></i></div>
                    <div>
                        <h3 class="text-xl font-black uppercase tracking-tighter">Tambah Data Backlog</h3>
                        <p class="text-white/50 text-[10px] font-bold uppercase tracking-widest mt-1">Input data keluarga belum memiliki rumah</p>
                    </div>
                </div>
                <button onclick="toggleModal('modal-add')" class="p-2 hover:bg-white/10 rounded-xl transition-colors"><i data-lucide="x" class="w-6 h-6"></i></button>
            </div>
            <form action="<?= base_url('rtlh/backlog/store') ?>" method="post" class="p-10">
                <?= csrf_field() ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">NIK Calon Penerima</label>
                        <input type="text" name="nik" required maxlength="16" minlength="16" class="w-full p-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl font-bold text-sm focus:ring-2 focus:ring-blue-600 transition-all outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Nomor Kartu Keluarga (KK)</label>
                        <input type="text" name="no_kk" maxlength="16" class="w-full p-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl font-bold text-sm focus:ring-2 focus:ring-blue-600 transition-all outline-none">
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap Kepala Keluarga</label>
                        <input type="text" name="nama_lengkap" required class="w-full p-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl font-bold text-sm focus:ring-2 focus:ring-blue-600 transition-all outline-none uppercase">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Desa/Kelurahan</label>
                        <select name="desa_id" required class="w-full p-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl font-bold text-sm focus:ring-2 focus:ring-blue-600 transition-all outline-none appearance-none">
                            <option value="">Pilih Lokasi</option>
                            <?php foreach($desa as $d): ?>
                                <option value="<?= $d['desa_id'] ?>"><?= $d['desa_nama'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Status Hunian</label>
                        <select name="keterangan_hunian" required class="w-full p-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl font-bold text-sm focus:ring-2 focus:ring-blue-600 transition-all outline-none appearance-none">
                            <option value="MENUMPANG KELUARGA">Menumpang Keluarga</option>
                            <option value="SEWA / KONTRAK">Sewa / Kontrak</option>
                            <option value="ASRAMA / MES">Asrama / Mes</option>
                            <option value="LAINNYA">Lainnya</option>
                        </select>
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Pemilik Rumah (Tumpangan/Sewa)</label>
                        <input type="text" name="nama_pemilik_rumah" required class="w-full p-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl font-bold text-sm focus:ring-2 focus:ring-blue-600 transition-all outline-none uppercase" placeholder="Nama pemilik rumah tempat tinggal sekarang">
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Alamat Detail</label>
                        <textarea name="alamat_detail" class="w-full p-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl font-bold text-sm focus:ring-2 focus:ring-blue-600 transition-all outline-none" rows="2"></textarea>
                    </div>
                    <input type="hidden" name="tahun_data" value="<?= date('Y') ?>">
                </div>
                <div class="mt-10 flex gap-3">
                    <button type="submit" class="flex-grow bg-blue-950 text-white py-4 rounded-2xl font-black uppercase tracking-widest shadow-xl shadow-blue-950/20 active:scale-95 transition-all">Simpan Data</button>
                    <button type="button" onclick="toggleModal('modal-add')" class="px-8 bg-slate-100 dark:bg-slate-800 text-slate-500 rounded-2xl font-black uppercase tracking-widest active:scale-95 transition-all border border-slate-200 dark:border-slate-700">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDIT -->
<div id="modal-edit" class="fixed inset-0 z-[2000] hidden overflow-y-auto py-10">
    <div class="fixed inset-0 bg-blue-950/60 backdrop-blur-sm transition-opacity"></div>
    <div class="relative flex items-center justify-center p-4 min-h-full">
        <div class="bg-white dark:bg-slate-900 w-full max-w-2xl rounded-[2.5rem] shadow-2xl overflow-hidden animate-in zoom-in duration-300">
            <div class="p-8 bg-amber-500 text-white flex justify-between items-center border-b border-white/10 sticky top-0 z-20">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-white/10 rounded-2xl backdrop-blur-md"><i data-lucide="edit-3" class="w-6 h-6"></i></div>
                    <div>
                        <h3 class="text-xl font-black uppercase tracking-tighter">Edit Data Backlog</h3>
                        <p class="text-white/50 text-[10px] font-bold uppercase tracking-widest mt-1">Perbarui informasi individu backlog</p>
                    </div>
                </div>
                <button onclick="toggleModal('modal-edit')" class="p-2 hover:bg-white/10 rounded-xl transition-colors"><i data-lucide="x" class="w-6 h-6"></i></button>
            </div>
            <form id="form-edit" action="" method="post" class="p-10">
                <?= csrf_field() ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2 opacity-60">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">NIK (Tidak dapat diubah)</label>
                        <input type="text" id="edit_nik" readonly class="w-full p-4 bg-slate-100 dark:bg-slate-800 border-none rounded-2xl font-bold text-sm outline-none cursor-not-allowed">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Nomor Kartu Keluarga (KK)</label>
                        <input type="text" name="no_kk" id="edit_no_kk" maxlength="16" class="w-full p-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl font-bold text-sm focus:ring-2 focus:ring-amber-500 transition-all outline-none">
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap Kepala Keluarga</label>
                        <input type="text" name="nama_lengkap" id="edit_nama_lengkap" required class="w-full p-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl font-bold text-sm focus:ring-2 focus:ring-amber-500 transition-all outline-none uppercase">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Desa/Kelurahan</label>
                        <select name="desa_id" id="edit_desa_id" required class="w-full p-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl font-bold text-sm focus:ring-2 focus:ring-amber-500 transition-all outline-none appearance-none">
                            <?php foreach($desa as $d): ?>
                                <option value="<?= $d['desa_id'] ?>"><?= $d['desa_nama'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Status Hunian</label>
                        <select name="keterangan_hunian" id="edit_keterangan_hunian" required class="w-full p-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl font-bold text-sm focus:ring-2 focus:ring-amber-500 transition-all outline-none appearance-none">
                            <option value="MENUMPANG KELUARGA">Menumpang Keluarga</option>
                            <option value="SEWA / KONTRAK">Sewa / Kontrak</option>
                            <option value="ASRAMA / MES">Asrama / Mes</option>
                            <option value="LAINNYA">Lainnya</option>
                        </select>
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Pemilik Rumah (Tumpangan/Sewa)</label>
                        <input type="text" name="nama_pemilik_rumah" id="edit_nama_pemilik_rumah" required class="w-full p-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl font-bold text-sm focus:ring-2 focus:ring-amber-500 transition-all outline-none uppercase">
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Alamat Detail</label>
                        <textarea name="alamat_detail" id="edit_alamat_detail" class="w-full p-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl font-bold text-sm focus:ring-2 focus:ring-amber-500 transition-all outline-none" rows="2"></textarea>
                    </div>
                    <input type="hidden" name="tahun_data" id="edit_tahun_data">
                </div>
                <div class="mt-10 flex gap-3">
                    <button type="submit" class="flex-grow bg-amber-500 text-white py-4 rounded-2xl font-black uppercase tracking-widest shadow-xl shadow-amber-500/20 active:scale-95 transition-all">Perbarui Data</button>
                    <button type="button" onclick="toggleModal('modal-edit')" class="px-8 bg-slate-100 dark:bg-slate-800 text-slate-500 rounded-2xl font-black uppercase tracking-widest active:scale-95 transition-all border border-slate-200 dark:border-slate-700">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleModal(id) {
        const modal = document.getElementById(id);
        modal.classList.toggle('hidden');
    }

    function editBacklog(data) {
        document.getElementById('edit_nik').value = data.nik;
        document.getElementById('edit_no_kk').value = data.no_kk;
        document.getElementById('edit_nama_lengkap').value = data.nama_lengkap;
        document.getElementById('edit_desa_id').value = data.desa_id;
        document.getElementById('edit_keterangan_hunian').value = data.keterangan_hunian;
        document.getElementById('edit_nama_pemilik_rumah').value = data.nama_pemilik_rumah;
        document.getElementById('edit_alamat_detail').value = data.alamat_detail;
        document.getElementById('edit_tahun_data').value = data.tahun_data;
        
        document.getElementById('form-edit').action = `<?= base_url('rtlh/backlog/update') ?>/${data.id}`;
        toggleModal('modal-edit');
    }

    async function deleteBacklog(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus data backlog ini?')) return;
        try {
            const response = await fetch(`<?= base_url('rtlh/backlog/delete') ?>/${id}`, {
                method: 'DELETE',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const res = await response.json();
            if (res.status === 'success') {
                window.showToast(res.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                window.showToast(res.message, 'error');
            }
        } catch (e) {
            window.showToast('Gagal menghapus data', 'error');
        }
    }
</script>

<?= $this->endSection() ?>
