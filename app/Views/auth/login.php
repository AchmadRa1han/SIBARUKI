<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SIBARUKI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-md">
        <!-- Logo & Title -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-950 rounded-[2rem] shadow-xl shadow-blue-900/20 mb-4">
                <i data-lucide="home" class="text-white w-10 h-10"></i>
            </div>
            <h1 class="text-2xl font-black text-blue-950 uppercase tracking-wider">SIBARUKI</h1>
            <p class="text-slate-500 text-sm mt-1">Sistem Informasi Bedah Rumah Kabupaten Sinjai</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
            <h2 class="text-xl font-bold text-blue-950 mb-6 text-center">Selamat Datang</h2>
            
            <?php if(session()->getFlashdata('msg')):?>
                <div class="bg-rose-50 text-rose-700 p-4 rounded-2xl text-sm mb-6 flex items-center gap-3">
                    <i data-lucide="alert-circle" class="w-5 h-5"></i>
                    <?= session()->getFlashdata('msg') ?>
                </div>
            <?php endif;?>

            <form action="<?= base_url('login/process') ?>" method="POST" class="space-y-5">
                <?= csrf_field() ?>
                
                <div>
                    <label class="text-[10px] font-black text-blue-900 uppercase tracking-widest mb-2 block ml-1">Username</label>
                    <div class="relative">
                        <i data-lucide="user" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                        <input type="text" name="username" required
                            class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-900/10 focus:border-blue-900 transition-all text-slate-700"
                            placeholder="Masukkan username">
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-black text-blue-900 uppercase tracking-widest mb-2 block ml-1">Password</label>
                    <div class="relative">
                        <i data-lucide="lock" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                        <input type="password" name="password" required
                            class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-900/10 focus:border-blue-900 transition-all text-slate-700"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" 
                        class="w-full py-4 bg-blue-950 text-white font-bold rounded-2xl shadow-lg shadow-blue-900/30 hover:bg-blue-900 transition-all flex items-center justify-center gap-2 group">
                        Masuk Sistem
                        <i data-lucide="arrow-right" class="w-5 h-5 group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </div>
            </form>
        </div>

        <p class="text-center text-slate-400 text-[10px] uppercase font-bold tracking-widest mt-8">
            &copy; 2026 DINAS PERKIM KAB. SINJAI
        </p>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
