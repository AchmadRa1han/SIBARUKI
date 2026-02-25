<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Card Tabel 1 -->
    <div class="bg-white p-6 rounded-lg border shadow-sm hover:shadow-md transition">
        <h3 class="text-lg font-bold text-gray-800 mb-2">ref_master</h3>
        <p class="text-sm text-gray-500 mb-4">Mengelola data referensi kategori dan pilihan sistem.</p>
        <div class="flex justify-between items-center">
            <span class="text-xs font-semibold bg-blue-100 text-blue-700 px-2 py-1 rounded">3 Kolom</span>
            <a href="<?= base_url('ref-master') ?>" class="text-blue-600 font-medium text-sm hover:underline">Buka Tabel &rarr;</a>
        </div>
    </div>

    <!-- Placeholder untuk tabel berikutnya -->
    <div class="bg-gray-50 p-6 rounded-lg border border-dashed flex flex-col items-center justify-center text-center">
        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center mb-2">
            <span class="text-gray-500 text-xl">+</span>
        </div>
        <p class="text-sm text-gray-400 font-medium">Tabel Baru</p>
        <p class="text-xs text-gray-400">Beritahu saya struktur tabel Anda untuk menambahkannya di sini.</p>
    </div>
</div>
<?= $this->endSection() ?>
