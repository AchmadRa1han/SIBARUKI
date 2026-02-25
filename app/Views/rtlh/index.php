<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
    <div class="p-8 border-b flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/50">
        <div>
            <h1 class="text-2xl font-black text-blue-950 tracking-tight">Manajemen Data RTLH</h1>
            <p class="text-sm text-slate-400 font-medium">Integrasi data penerima, profil rumah, dan teknis bangunan</p>
        </div>
        <a href="<?= base_url('rtlh/create') ?>" class="bg-blue-900 hover:bg-blue-950 text-white px-6 py-3 rounded-2xl transition-all flex items-center space-x-2 text-sm font-bold shadow-xl shadow-blue-900/20">
            <i data-lucide="plus-circle" class="w-5 h-5"></i>
            <span>Tambah Data Terpadu</span>
        </a>
    </div>

    <div class="p-8">
        <?php if (session()->getFlashdata('message')) : ?>
            <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-6 py-4 rounded-2xl mb-8 flex items-center space-x-3 text-sm font-bold shadow-sm">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                <span><?= session()->getFlashdata('message') ?></span>
            </div>
        <?php endif; ?>

        <div class="overflow-x-auto rounded-[1.5rem] border border-slate-100 mb-8 shadow-inner bg-slate-50/30">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white text-blue-950 uppercase text-[10px] font-black tracking-[0.15em]">
                        <th class="p-5 border-b border-slate-100 text-center">ID</th>
                        <th class="p-5 border-b border-slate-100">Nama Pemilik</th>
                        <th class="p-5 border-b border-slate-100">Lokasi / Desa</th>
                        <th class="p-5 border-b border-slate-100">Luas Rumah</th>
                        <th class="p-5 border-b border-slate-100">Penghuni</th>
                        <th class="p-5 border-b border-slate-100 text-center uppercase">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm text-slate-700 bg-white/50">
                    <?php foreach ($rumah as $row) : ?>
                        <tr class="hover:bg-blue-50/50 transition-colors group">
                            <td class="p-5 text-center">
                                <span class="font-mono text-[10px] font-bold text-blue-900 bg-blue-50 px-2 py-1 rounded-md border border-blue-100">
                                    #<?= str_pad($row['id_survei'], 4, '0', STR_PAD_LEFT) ?>
                                </span>
                            </td>
                            <td class="p-5">
                                <p class="font-black text-slate-800 uppercase tracking-tight"><?= $row['pemilik'] ?? 'TIDAK DIKETAHUI' ?></p>
                                <p class="text-[10px] font-bold text-blue-900/40 uppercase font-mono mt-0.5"><?= $row['nik_pemilik'] ?></p>
                            </td>
                            <td class="p-5">
                                <p class="font-bold text-slate-700"><?= $row['desa'] ?></p>
                                <p class="text-[10px] text-slate-400 truncate w-48 font-medium italic mt-0.5"><?= $row['alamat_detail'] ?></p>
                            </td>
                            <td class="p-5">
                                <span class="font-black text-blue-950"><?= $row['luas_rumah_m2'] ?></span>
                                <span class="text-[10px] font-bold text-slate-400 ml-0.5 italic">m²</span>
                            </td>
                            <td class="p-5">
                                <span class="font-bold text-slate-700"><?= $row['jumlah_penghuni_jiwa'] ?></span>
                                <span class="text-[10px] font-medium text-slate-400 ml-0.5">Jiwa</span>
                            </td>
                            <td class="p-5 text-center">
                                <div class="flex justify-center items-center space-x-3">
                                    <a href="<?= base_url('rtlh/detail/' . $row['id_survei']) ?>" 
                                       class="p-2.5 bg-blue-900 text-white rounded-xl shadow-lg shadow-blue-900/20 hover:bg-blue-950 hover:-translate-y-0.5 transition-all" 
                                       title="Lihat Detail Lengkap">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <form action="<?= base_url('rtlh/delete/' . $row['id_survei']) ?>" method="post" onsubmit="return confirm('Hapus seluruh rangkaian data RTLH ini?')">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="p-2 text-rose-300 hover:text-rose-600 transition-colors" title="Hapus Permanen">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex justify-center">
            <?= $pager->links('group1', 'tailwind_full') ?>
        </div>
    </div>
</div>

<script>lucide.createIcons();</script>
<?= $this->endSection() ?>
