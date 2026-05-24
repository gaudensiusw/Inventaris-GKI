<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>GKI Delima - Peminjaman Inventaris</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@0.344.0"></script>
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-800 min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="bg-white border-b border-slate-100 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Brand — klik logo kembali ke halaman utama -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
                    <div class="w-11 h-11 rounded-xl overflow-hidden bg-white shadow-sm border border-slate-100 flex items-center justify-center p-1">
                        <img src="{{ asset('images/logo-gki.png') }}" alt="Logo GKI Delima" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h1 class="text-sm font-black text-slate-800">GKI Delima</h1>
                        <p class="text-[10px] text-slate-400 font-semibold">Peminjaman Inventaris</p>
                    </div>
                </a>

                <!-- Nav Links -->
                <div class="flex items-center gap-1 sm:gap-2">
                    <a href="{{ route('home') }}" class="px-3 sm:px-4 py-2 rounded-xl text-sm font-bold {{ request()->routeIs('home') ? 'bg-blue-50 text-blue-600' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700' }} transition-all">
                        <span class="flex items-center gap-2">
                            <i data-lucide="home" class="w-4 h-4"></i>
                            <span class="hidden sm:inline">Beranda</span>
                        </span>
                    </a>
                    <a href="{{ route('user.katalog.index') }}" class="px-3 sm:px-4 py-2 rounded-xl text-sm font-bold {{ request()->routeIs('user.katalog.*') ? 'bg-blue-50 text-blue-600' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700' }} transition-all">
                        <span class="flex items-center gap-2">
                            <i data-lucide="clipboard-list" class="w-4 h-4"></i>
                            <span class="hidden sm:inline">Form Peminjaman</span>
                        </span>
                    </a>
                    <a href="{{ route('user.katalog.rooms') }}" class="px-3 sm:px-4 py-2 rounded-xl text-sm font-bold {{ request()->routeIs('user.katalog.rooms*') ? 'bg-blue-50 text-blue-600' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700' }} transition-all">
                        <span class="flex items-center gap-2">
                            <i data-lucide="map-pin" class="w-4 h-4"></i>
                            <span class="hidden sm:inline">Lokasi</span>
                        </span>
                    </a>
                    <a href="{{ route('user.katalog.qr-scanner') }}" class="px-3 sm:px-4 py-2 rounded-xl text-sm font-bold {{ request()->routeIs('user.katalog.qr-scanner') ? 'bg-blue-50 text-blue-600' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700' }} transition-all">
                        <span class="flex items-center gap-2">
                            <i data-lucide="scan" class="w-4 h-4"></i>
                            <span class="hidden sm:inline">Scan QR</span>
                        </span>
                    </a>
                    <a href="{{ route('user.orders.status') }}" class="px-3 sm:px-4 py-2 rounded-xl text-sm font-bold {{ request()->routeIs('user.orders.status') ? 'bg-blue-50 text-blue-600' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700' }} transition-all">
                        <span class="flex items-center gap-2">
                            <i data-lucide="search" class="w-4 h-4"></i>
                            <span class="hidden sm:inline">Cek Status</span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                <span class="text-sm font-semibold text-emerald-700">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl flex items-center gap-3">
                <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 shrink-0"></i>
                <span class="text-sm font-semibold text-red-700">{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-100 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-xs text-slate-400">&copy; {{ date('Y') }} GKI Delima — Sistem Inventaris</p>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
    @stack('scripts')
</body>
</html>
