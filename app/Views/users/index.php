<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="space-y-8">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-black text-blue-950 uppercase tracking-wider">Manajemen Pengguna</h1>
            <p class="text-slate-400 text-sm font-medium italic">Kelola hak akses dan penugasan wilayah petugas.</p>
        </div>
        <a href="<?= base_url('users/create') ?>" class="bg-blue-900 hover:bg-blue-800 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-blue-900/20 transition-all flex items-center gap-2 group">
            <i data-lucide="user-plus" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
            <span>Tambah User</span>
        </a>
    </div>

    <?php if(session()->getFlashdata('message')): ?>
        <div class="bg-emerald-50 text-emerald-700 p-4 rounded-2xl text-sm font-bold flex items-center gap-3 border border-emerald-100">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            <?= session()->getFlashdata('message') ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100">
                    <th class="p-6 text-[10px] font-black text-blue-900 uppercase tracking-widest">Username</th>
                    <th class="p-6 text-[10px] font-black text-blue-900 uppercase tracking-widest">Instansi</th>
                    <th class="p-6 text-[10px] font-black text-blue-900 uppercase tracking-widest">Role</th>
                    <th class="p-6 text-[10px] font-black text-blue-900 uppercase tracking-widest">Dibuat Pada</th>
                    <th class="p-6 text-[10px] font-black text-blue-900 uppercase tracking-widest text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php foreach($users as $user): ?>
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="p-6">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center font-black text-xs uppercase">
                                <?= substr($user['username'], 0, 1) ?>
                            </div>
                            <span class="font-bold text-slate-700"><?= $user['username'] ?></span>
                        </div>
                    </td>
                    <td class="p-6 text-sm text-slate-500 font-medium"><?= $user['instansi'] ?: '-' ?></td>
                    <td class="p-6">
                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest <?= $user['role_name'] == 'admin' ? 'bg-indigo-50 text-indigo-600' : 'bg-amber-50 text-amber-600' ?>">
                            <?= $user['role_name'] ?>
                        </span>
                    </td>
                    <td class="p-6 text-xs text-slate-400 font-bold uppercase"><?= date('d M Y', strtotime($user['created_at'])) ?></td>
                    <td class="p-6">
                        <div class="flex justify-center gap-2">
                            <a href="<?= base_url('users/edit/'.$user['id']) ?>" class="p-2 text-slate-400 hover:text-blue-600 transition-colors">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </a>
                            <?php if($user['username'] !== 'admin'): ?>
                            <form action="<?= base_url('users/delete/'.$user['id']) ?>" method="post" onsubmit="return confirm('Hapus user ini?')">
                                <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 transition-colors">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
