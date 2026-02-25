<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Edit Ref Master</h1>

    <form action="<?= base_url('ref-master/update/' . $ref['id']) ?>" method="post">
        <?= csrf_field() ?>
        
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">Kategori</label>
            <input type="text" name="kategori" value="<?= old('kategori', $ref['kategori']) ?>" 
                   class="w-full p-2 border rounded-md focus:ring-2 focus:ring-blue-400 outline-none <?= session('errors.kategori') ? 'border-red-500' : '' ?>">
            <?php if (session('errors.kategori')) : ?>
                <p class="text-red-500 text-sm mt-1"><?= session('errors.kategori') ?></p>
            <?php endif; ?>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-medium mb-2">Nama Pilihan</label>
            <input type="text" name="nama_pilihan" value="<?= old('nama_pilihan', $ref['nama_pilihan']) ?>" 
                   class="w-full p-2 border rounded-md focus:ring-2 focus:ring-blue-400 outline-none <?= session('errors.nama_pilihan') ? 'border-red-500' : '' ?>">
            <?php if (session('errors.nama_pilihan')) : ?>
                <p class="text-red-500 text-sm mt-1"><?= session('errors.nama_pilihan') ?></p>
            <?php endif; ?>
        </div>

        <div class="flex items-center space-x-4">
            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-md transition">
                Update
            </button>
            <a href="<?= base_url('ref-master') ?>" class="text-gray-600 hover:underline">Batal</a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
