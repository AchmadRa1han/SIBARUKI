<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">
        <div class="p-6 border-b bg-gray-50/50">
            <h1 class="text-xl font-bold text-gray-800">Tambah Penerima RTLH</h1>
            <p class="text-sm text-gray-500">Lengkapi formulir di bawah untuk menambah data penerima baru</p>
        </div>

        <form action="<?= base_url('penerima-rtlh/store') ?>" method="post" class="p-8">
            <?= csrf_field() ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- NIK -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">NIK (16 Digit)</label>
                    <input type="text" name="nik" value="<?= old('nik') ?>" maxlength="16" required
                           class="w-full p-3 bg-gray-50 border rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all <?= session('errors.nik') ? 'border-red-500' : 'border-gray-200' ?>">
                    <?php if (session('errors.nik')) : ?>
                        <p class="text-red-500 text-xs mt-1 font-medium"><?= session('errors.nik') ?></p>
                    <?php endif; ?>
                </div>

                <!-- No KK -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">No. Kartu Keluarga</label>
                    <input type="text" name="no_kk" value="<?= old('no_kk') ?>" maxlength="16"
                           class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                </div>

                <!-- Nama -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap Kepala Keluarga</label>
                    <input type="text" name="nama_kepala_keluarga" value="<?= old('nama_kepala_keluarga') ?>" required
                           class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                </div>

                <!-- Tempat Lahir -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" value="<?= old('tempat_lahir') ?>"
                           class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                </div>

                <!-- Tanggal Lahir -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="<?= old('tanggal_lahir') ?>"
                           class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                </div>

                <!-- Jenis Kelamin -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Jenis Kelamin</label>
                    <select name="jenis_kelamin" required class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500/20">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L" <?= old('jenis_kelamin') == 'L' ? 'selected' : '' ?>>Laki-laki</option>
                        <option value="P" <?= old('jenis_kelamin') == 'P' ? 'selected' : '' ?>>Perempuan</option>
                    </select>
                </div>

                <!-- Jumlah Anggota Keluarga -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah Anggota Keluarga</label>
                    <input type="number" name="jumlah_anggota_keluarga" value="<?= old('jumlah_anggota_keluarga') ?>"
                           class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                </div>

                <!-- Pendidikan (Dari Master) -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Pendidikan</label>
                    <select name="pendidikan_id" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500/20">
                        <option value="">Pilih Pendidikan</option>
                        <?php foreach($pendidikan as $p) : ?>
                            <option value="<?= $p['id'] ?>" <?= old('pendidikan_id') == $p['id'] ? 'selected' : '' ?>><?= $p['nama_pilihan'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Pekerjaan (Dari Master) -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Pekerjaan</label>
                    <select name="pekerjaan_id" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500/20">
                        <option value="">Pilih Pekerjaan</option>
                        <?php foreach($pekerjaan as $p) : ?>
                            <option value="<?= $p['id'] ?>" <?= old('pekerjaan_id') == $p['id'] ? 'selected' : '' ?>><?= $p['nama_pilihan'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Penghasilan -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Penghasilan Per Bulan</label>
                    <input type="text" name="penghasilan_per_bulan" value="<?= old('penghasilan_per_bulan') ?>"
                           placeholder="Contoh: Rp 1.500.000"
                           class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                </div>
            </div>

            <div class="mt-10 flex items-center justify-end space-x-4 border-t pt-8">
                <a href="<?= base_url('penerima-rtlh') ?>" class="text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors">Batal</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-blue-200 transition-all flex items-center space-x-2">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>Simpan Data</span>
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
