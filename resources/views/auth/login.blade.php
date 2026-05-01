<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin - Inventaris GKI Delima</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-effect {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .bg-gradient-premium {
            background: radial-gradient(circle at top right, #1e293b, #0f172a);
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">
    <!-- Back to Home Button -->
    <a href="{{ route('home') }}" class="fixed top-8 left-8 flex items-center gap-2 px-5 py-2.5 bg-white text-slate-600 rounded-2xl text-xs font-black uppercase shadow-xl shadow-slate-200/50 hover:bg-slate-50 hover:scale-105 transition-all z-50">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        <span>Kembali ke Beranda</span>
    </a>

    <div class="max-w-6xl w-full grid grid-cols-1 lg:grid-cols-2 bg-white rounded-[40px] shadow-2xl overflow-hidden border border-slate-100">
        <!-- Left Side: Branding & Stats -->
        <div class="relative bg-gradient-premium p-12 lg:p-20 flex flex-col justify-between overflow-hidden">
            <!-- Decorative circles -->
            <div class="absolute -top-20 -left-20 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-indigo-500/10 rounded-full blur-3xl"></div>
            
            <div class="relative z-10">
                <div class="flex items-center gap-3 bg-white/5 w-fit px-4 py-2 rounded-2xl backdrop-blur-md border border-white/10 mb-12">
                    <div class="w-8 h-8 bg-blue-600 rounded-xl flex items-center justify-center text-white font-black text-sm shadow-lg shadow-blue-500/20">GKI</div>
                    <span class="text-white font-bold text-sm tracking-tight">Portal Admin</span>
                </div>
                
                <h1 class="text-5xl lg:text-6xl font-black text-white leading-[1.1] tracking-tighter mb-6">
                    Sistem Inventaris<br/>
                    <span class="text-slate-400">GKI Delima</span>
                </h1>
                <p class="text-slate-400 text-lg max-w-md leading-relaxed font-medium">
                    Kelola aset, peminjaman, dan perbaikan barang gereja secara profesional dalam satu dashboard terintegrasi.
                </p>
            </div>

            <div class="relative z-10 grid grid-cols-2 gap-6">
                <div class="glass-effect rounded-3xl p-6">
                    <h4 class="text-white text-3xl font-black mb-1">500+</h4>
                    <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest">Total Barang</p>
                </div>
                <div class="glass-effect rounded-3xl p-6">
                    <h4 class="text-white text-3xl font-black mb-1">Secure</h4>
                    <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest">Administrator</p>
                </div>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="p-12 lg:p-20 flex flex-col justify-center">
            <div class="max-w-md mx-auto w-full">
                <div class="flex flex-col gap-3 mb-12">
                    <div class="w-16 h-16 bg-slate-50 text-slate-800 rounded-2xl flex items-center justify-center mb-4 border border-slate-100 shadow-sm">
                        <i data-lucide="shield-check" class="w-8 h-8"></i>
                    </div>
                    <h2 class="text-4xl font-black text-slate-800 tracking-tight">Login Admin</h2>
                    <p class="text-slate-500 font-medium text-lg">Silakan masukkan kredensial Anda</p>
                </div>

                <form action="{{ route('login') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-600 ml-1">Alamat Email</label>
                        <div class="relative group">
                            <i data-lucide="mail" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 group-focus-within:text-slate-800 transition-colors"></i>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@gkidelima.org" required autofocus
                                class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-slate-100 focus:border-slate-800 transition-all font-medium text-sm">
                        </div>
                        @error('email') <span class="text-xs text-red-500 font-bold ml-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-600 ml-1">Kata Sandi</label>
                        <div class="relative group">
                            <i data-lucide="lock" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 group-focus-within:text-slate-800 transition-colors"></i>
                            <input type="password" name="password" placeholder="••••••••" required
                                class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-slate-100 focus:border-slate-800 transition-all font-medium text-sm">
                        </div>
                    </div>

                    <button type="submit" class="w-full py-5 bg-slate-900 text-white rounded-2xl font-black uppercase text-sm shadow-2xl shadow-slate-200 hover:bg-black active:scale-[0.98] transition-all">
                        Masuk Dashboard
                    </button>
                </form>

                <p class="text-center mt-12 text-xs font-bold text-slate-400">
                    Sistem Inventaris GKI Delima &bull; v2.0
                </p>
            </div>
        </div>
    </div>
    <script>lucide.createIcons();</script>
</body>
</html>
