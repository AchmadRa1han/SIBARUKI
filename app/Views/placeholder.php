<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="flex flex-col items-center justify-center min-h-[60vh] text-center px-4">
    <div class="w-24 h-24 bg-blue-50 dark:bg-blue-900/20 rounded-[2rem] flex items-center justify-center mb-8 animate-bounce">
        <i data-lucide="construction" class="w-12 h-12 text-blue-600"></i>
    </div>
    <h1 class="text-3xl font-black text-blue-950 dark:text-white uppercase tracking-tight mb-4"><?= $title ?></h1>
    <div class="max-w-lg bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm">
        <p class="text-slate-500 dark:text-slate-400 font-medium leading-relaxed italic">
            <?= $message ?>
        </p>
    </div>
    <a href="<?= base_url('dashboard') ?>" class="mt-8 text-blue-600 dark:text-blue-400 font-black uppercase text-[10px] tracking-[0.2em] hover:underline flex items-center gap-2">
        <i data-lucide="arrow-left" class="w-3 h-3"></i> Kembali ke Dashboard
    </a>
</div>
<?= $this->endSection() ?>
