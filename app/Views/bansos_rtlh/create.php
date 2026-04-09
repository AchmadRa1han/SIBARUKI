<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto pb-20">
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="p-8 border-b dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-emerald-900/20">
                    <i data-lucide="award" class="w-6 h-6"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-blue-950 dark:text-white uppercase tracking-tight">Input Realisasi Bansos</h2>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Catat keberhasilan perbaikan rumah</p>
                </div>
            </div>
            <a href="<?= base_url('bansos-rtlh') ?>" class="px-5 py-2.5 bg-white dark:bg-slate-800 text-slate-500 rounded-xl text-[10px] font-bold uppercase tracking-widest border border-slate-200 dark:border-slate-700 hover:bg-slate-50 transition-all flex items-center gap-2">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Kembali
            </a>
        </div>

        <form action="<?= base_url('bansos-rtlh/store') ?>" method="POST" class="p-10 space-y-8">
            <!-- Pilihan Data RTLH (Optional linking) -->
            <div class="bg-blue-50/50 dark:bg-blue-950/20 p-6 rounded-3xl border border-blue-100/50 dark:border-blue-900/30">
                <label class="block text-[10px] font-bold text-blue-900 dark:text-blue-400 uppercase mb-3 tracking-widest flex items-center gap-2">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i> Hubungkan dengan Data Survei RTLH (Opsional)
                </label>
                <select name="id_survei" id="id_survei" onchange="fillFromRtlh(this)" class="w-full p-4 bg-white dark:bg-slate-900 border border-blue-200 dark:border-blue-800 rounded-2xl focus:ring-4 focus:ring-blue-500/10 outline-none transition-all font-bold text-sm">
                    <option value="">-- Input Manual (Bukan dari Data Survei) --</option>
                    <?php foreach($rtlh as $r): ?>
                        <option value="<?= $r['id_survei'] ?>" data-nik="<?= $r['nik'] ?>" data-nama="<?= $r['nama_kepala_keluarga'] ?>" data-desa="<?= $r['desa'] ?>">
                            [ID: <?= $r['id_survei'] ?>] <?= $r['nama_kepala_keluarga'] ?> - <?= $r['desa'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="mt-2 text-[9px] text-blue-600/60 font-medium italic">*Jika dipilih, status rumah tersebut akan otomatis menjadi RLH (Tuntas).</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">NIK Penerima</label>
                    <input type="text" name="nik" id="nik" placeholder="Masukkan 16 digit NIK" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-white outline-none transition-all font-bold" required>
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                    <input type="text" name="nama_penerima" id="nama_penerima" placeholder="Nama sesuai KTP" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-white outline-none transition-all font-bold" required>
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Wilayah Desa</label>
                    <input type="text" name="desa" id="desa" placeholder="Nama Desa / Kelurahan" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-white outline-none transition-all font-bold" required>
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Tahun Anggaran</label>
                    <input type="number" name="tahun_anggaran" value="<?= date('Y') ?>" min="2000" max="2099" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-white outline-none transition-all font-bold" required>
                </div>
                <div class="md:col-span-2 space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Sumber Dana / Nama Program</label>
                    <input type="text" name="sumber_dana" placeholder="Contoh: BSPS, APBD Sinjai, DAK Bidang Perumahan" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-white outline-none transition-all font-bold" required>
                </div>
                <div class="md:col-span-2 space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Keterangan Tambahan</label>
                    <textarea name="keterangan" rows="3" placeholder="Informasi tambahan jika ada..." class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-white outline-none transition-all font-bold"></textarea>
                </div>
            </div>

            <div class="pt-6">
                <button type="submit" class="w-full py-5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-3xl text-sm font-bold uppercase tracking-[0.2em] shadow-xl shadow-emerald-900/20 transition-all active:scale-[0.98] flex items-center justify-center gap-3">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                    Simpan & Update Status RTLH
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function fillFromRtlh(select) {
        const option = select.options[select.selectedIndex];
        if (option.value) {
            document.getElementById('nik').value = option.getAttribute('data-nik');
            document.getElementById('nama_penerima').value = option.getAttribute('data-nama');
            document.getElementById('desa').value = option.getAttribute('data-desa');
            
            // Lock inputs to prevent inconsistency if linked
            document.getElementById('nik').readOnly = true;
            document.getElementById('nama_penerima').readOnly = true;
            document.getElementById('desa').readOnly = true;
            document.getElementById('nik').classList.add('opacity-60');
            document.getElementById('nama_penerima').classList.add('opacity-60');
            document.getElementById('desa').classList.add('opacity-60');
        } else {
            document.getElementById('nik').value = '';
            document.getElementById('nama_penerima').value = '';
            document.getElementById('desa').value = '';
            
            document.getElementById('nik').readOnly = false;
            document.getElementById('nama_penerima').readOnly = false;
            document.getElementById('desa').readOnly = false;
            document.getElementById('nik').classList.remove('opacity-60');
            document.getElementById('nama_penerima').classList.remove('opacity-60');
            document.getElementById('desa').classList.remove('opacity-60');
        }
    }
</script>
<?= $this->endSection() ?>
