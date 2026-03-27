<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" href="<?= base_url('sinjai.png') ?>" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #f1f5f9;
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-4xl bg-white rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col md:flex-row border border-white">
        
        <!-- LEFT SIDE: BRANDING -->
        <div class="w-full md:w-5/12 bg-blue-950 p-10 flex flex-col justify-between relative overflow-hidden">
            <!-- Decorative Light -->
            <div class="absolute -top-24 -left-24 w-64 h-64 bg-blue-600/20 rounded-full blur-3xl"></div>
            
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-7 h-7 bg-white/10 rounded-lg flex items-center justify-center backdrop-blur-sm border border-white/10">
                        <i data-lucide="shield-check" class="text-white w-3.5 h-3.5"></i>
                    </div>
                    <span class="text-white text-[10px] font-black uppercase tracking-[0.3em]">Official Portal</span>
                </div>

                <div class="text-center md:text-left mt-4">
                    <div class="relative inline-flex mb-6 group">
                        <div class="absolute inset-0 bg-blue-500/20 blur-3xl rounded-full scale-150 opacity-50"></div>
                        <img src="<?= base_url('sinjai.png') ?>" alt="Logo Sinjai" 
                            class="w-28 h-28 object-contain relative z-10 animate-float drop-shadow-[0_10px_20px_rgba(0,0,0,0.5)] transition-transform duration-700 group-hover:scale-110">
                    </div>
                    <h1 class="text-3xl font-black text-white tracking-tighter mb-2">SIBARUKI</h1>
                    <p class="text-blue-200/60 text-[10px] font-bold uppercase tracking-widest leading-relaxed">
                       Sama - samaki Bangun <br>Perumahan & Permukiman<br>Kabupaten Sinjai
                    </p>
                </div>
            </div>

            <div class="relative z-10 pt-8 md:pt-0">
                <div class="flex flex-col gap-1">
                    <p class="text-white/30 text-[8px] font-black uppercase tracking-[0.4em]">&copy; 2026 DINAS PERKIM</p>
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                        <span class="text-blue-300/40 text-[7px] font-bold uppercase tracking-widest">Sistem Aktif</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDE: LOGIN FORM -->
        <div class="w-full md:w-7/12 p-8 md:p-14 flex flex-col justify-center bg-white relative">
            <div class="max-w-sm mx-auto w-full">
                <div class="mb-8 text-center md:text-left">
                    <h2 class="text-2xl font-black text-blue-950 tracking-tight mb-1">Selamat Datang</h2>
                    <p class="text-slate-400 text-xs font-medium">Otentikasi kredensial dashboard Anda.</p>
                </div>

                <?php if(session()->getFlashdata('msg')):?>
                    <div class="bg-rose-50 border border-rose-100 text-rose-700 p-3 rounded-xl text-[10px] font-bold mb-6 flex items-center gap-3">
                        <i data-lucide="alert-circle" class="w-4 h-4"></i>
                        <?= session()->getFlashdata('msg') ?>
                    </div>
                <?php endif;?>

                <form action="<?= base_url('login/process') ?>" method="POST" class="space-y-4">
                    <?= csrf_field() ?>
                    
                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Username</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-lucide="user" class="w-5 h-5 text-slate-300 group-focus-within:text-blue-950 transition-colors"></i>
                            </div>
                            <input type="text" name="username" required
                                class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-600/5 focus:border-blue-950 transition-all text-sm font-bold text-slate-700 placeholder:text-slate-300"
                                placeholder="ID Pengguna">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Password</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-lucide="lock" class="w-5 h-5 text-slate-300 group-focus-within:text-blue-950 transition-colors"></i>
                            </div>
                            <input type="password" name="password" required
                                class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-600/5 focus:border-blue-950 transition-all text-sm font-bold text-slate-700 placeholder:text-slate-300"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" 
                            class="w-full py-4 bg-blue-950 text-white text-sm font-black uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-blue-900/20 hover:bg-black hover:-translate-y-0.5 transition-all active:scale-[0.98] flex items-center justify-center">
                            Login
                        </button>
                    </div>
                </form>

                <div class="mt-8 pt-6 border-t border-slate-50 flex items-center justify-between text-[9px] font-black uppercase tracking-widest text-slate-300">
                    <a href="#" class="hover:text-blue-600 transition-colors">Masalah Akses?</a>
                    <span>v2.0.4</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
