<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="bg-white rounded-2xl border shadow-sm overflow-hidden">
    <div class="p-6 border-b flex justify-between items-center bg-gray-50/50">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Kondisi Fisik Rumah</h1>
            <p class="text-sm text-gray-500">Detail penilaian struktur dan material komponen rumah</p>
        </div>
        <a href="<?= base_url('kondisi-fisik/create') ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl transition flex items-center space-x-2 text-sm font-bold">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Tambah Penilaian</span>
        </a>
    </div>

    <div class="p-6">
        <?php if (session()->getFlashdata('message')) : ?>
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-6 flex items-center space-x-3 text-sm">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                <span><?= session()->getFlashdata('message') ?></span>
            </div>
        <?php endif; ?>

        <div class="overflow-x-auto rounded-xl border">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 uppercase text-[10px] font-bold tracking-widest">
                        <th class="p-4 border-b">Pemilik & Lokasi</th>
                        <th class="p-4 border-b">Komponen Struktur</th>
                        <th class="p-4 border-b">Atap</th>
                        <th class="p-4 border-b">Dinding</th>
                        <th class="p-4 border-b">Lantai</th>
                        <th class="p-4 border-b text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y text-sm text-gray-700">
                    <?php foreach ($kondisi as $row) : ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-4">
                                <p class="font-bold text-gray-900"><?= $row['pemilik'] ?></p>
                                <p class="text-xs text-gray-500 italic"><?= $row['desa'] ?> (#<?= $row['id_survei'] ?>)</p>
                            </td>
                            <td class="p-4 space-y-1">
                                <p class="text-[11px]"><span class="font-bold text-gray-400">Pondasi:</span> <?= $row['pondasi'] ?? '-' ?></p>
                                <p class="text-[11px]"><span class="font-bold text-gray-400">Kolom:</span> <?= $row['kolom'] ?? '-' ?></p>
                                <p class="text-[11px]"><span class="font-bold text-gray-400">Balok:</span> <?= $row['balok'] ?? '-' ?></p>
                                <p class="text-[11px]"><span class="font-bold text-gray-400">Sloof:</span> <?= $row['sloof'] ?? '-' ?></p>
                            </td>
                            <td class="p-4">
                                <p class="font-medium text-gray-900"><?= $row['atap_mat'] ?? '-' ?></p>
                                <p class="text-[10px] text-gray-500">Kondisi: <?= $row['atap_st'] ?? '-' ?></p>
                            </td>
                            <td class="p-4">
                                <p class="font-medium text-gray-900"><?= $row['dinding_mat'] ?? '-' ?></p>
                                <p class="text-[10px] text-gray-500">Kondisi: <?= $row['dinding_st'] ?? '-' ?></p>
                            </td>
                            <td class="p-4">
                                <p class="font-medium text-gray-900"><?= $row['lantai_mat'] ?? '-' ?></p>
                                <p class="text-[10px] text-gray-500">Kondisi: <?= $row['lantai_st'] ?? '-' ?></p>
                            </td>
                            <td class="p-4">
                                <div class="flex justify-center space-x-2">
                                    <a href="<?= base_url('kondisi-fisik/edit/' . $row['id_survei']) ?>" class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition" title="Edit">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>
                                    <form action="<?= base_url('kondisi-fisik/delete/' . $row['id_survei']) ?>" method="post" class="inline" onsubmit="return confirm('Hapus penilaian ini?')">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus">
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

        <!-- Pagination -->
        <div class="mt-4 flex justify-center">
            <?= $pager->links('group1', 'tailwind_full') ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
