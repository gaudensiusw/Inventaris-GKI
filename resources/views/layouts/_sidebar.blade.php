<aside id="mainSidebar" class="fixed left-0 top-0 h-screen w-64 bg-slate-900 border-r border-slate-800 flex flex-col z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
    <!-- Brand -->
    <div class="px-6 py-8 flex items-center gap-4">
        <div class="w-11 h-11 bg-blue-600 rounded-2xl flex items-center justify-center text-white font-black text-sm shadow-xl shadow-blue-500/20">
            GKI
        </div>
        <div>
            <h1 class="text-sm font-black text-white leading-tight tracking-tight">GKI Delima</h1>
            <p class="text-[10px] text-slate-500 uppercase font-bold tracking-[0.1em]">Sistem Inventaris</p>
        </div>
    </div>

    <!-- User Profile Quick Info -->
    <div class="px-4 mb-8">
        <div class="p-4 bg-slate-800/80 rounded-[24px] flex items-center gap-3 border border-slate-700/50 shadow-inner">
            <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-black text-xs shadow-lg shadow-blue-500/20">
                {{ auth()->id() ?? 'A' }}
            </div>
            <div class="overflow-hidden">
                <h4 class="text-xs font-black text-white truncate">{{ auth()->user()->name ?? 'Admin Utama' }}</h4>
                <p class="text-[10px] text-blue-400 font-bold uppercase tracking-wider">Administrator</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 space-y-1.5 overflow-y-auto custom-scrollbar">
        <!-- Main Group -->
        <div class="space-y-1">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white transition-all' }} text-sm group">
                <i data-lucide="layout-grid" class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-white' : 'group-hover:text-blue-400' }} transition-colors"></i>
                <span class="font-bold">Dashboard</span>
            </a>
            <a href="{{ route('inventory.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('inventory.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white transition-all' }} text-sm group">
                <i data-lucide="package" class="w-5 h-5 {{ request()->routeIs('inventory.*') ? 'text-white' : 'group-hover:text-blue-400' }} transition-colors"></i>
                <span class="font-bold">Inventaris</span>
            </a>
            <a href="{{ route('room.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('room.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white transition-all' }} text-sm group">
                <i data-lucide="map-pin" class="w-5 h-5 {{ request()->routeIs('room.*') ? 'text-white' : 'group-hover:text-blue-400' }} transition-colors"></i>
                <span class="font-bold">Lokasi Penyimpanan</span>
            </a>
            <a href="{{ route('stock-opname.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('stock-opname.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white transition-all' }} text-sm group">
                <i data-lucide="clipboard-check" class="w-5 h-5 {{ request()->routeIs('stock-opname.*') ? 'text-white' : 'group-hover:text-blue-400' }} transition-colors"></i>
                <span class="font-bold">Stock Opname</span>
            </a>
            <a href="{{ route('qr-scanner.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('qr-scanner.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white transition-all' }} text-sm group">
                <i data-lucide="qr-code" class="w-5 h-5 {{ request()->routeIs('qr-scanner.*') ? 'text-white' : 'group-hover:text-blue-400' }} transition-colors"></i>
                <span class="font-bold">QR Scanner</span>
            </a>
        </div>

        <!-- Status Khusus Group -->
        <div class="pt-6 pb-2 px-4">
            <span class="text-[10px] font-black text-slate-600 uppercase tracking-[0.2em]">Status Khusus</span>
        </div>
        <div class="space-y-1">
            <a href="{{ route('borrowing.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('borrowing.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white transition-all' }} text-sm group">
                <i data-lucide="undo-2" class="w-5 h-5 {{ request()->routeIs('borrowing.*') ? 'text-white' : 'group-hover:text-blue-400' }} transition-colors"></i>
                <span class="font-bold">Barang Dipinjam</span>
            </a>
            <a href="{{ route('repair.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('repair.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white transition-all' }} text-sm group">
                <i data-lucide="wrench" class="w-5 h-5 {{ request()->routeIs('repair.*') ? 'text-white' : 'group-hover:text-blue-400' }} transition-colors"></i>
                <span class="font-bold">Dalam Perbaikan</span>
            </a>
            <a href="{{ route('special-status.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('special-status.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white transition-all' }} text-sm group">
                <i data-lucide="alert-circle" class="w-5 h-5 {{ request()->routeIs('special-status.*') ? 'text-white' : 'group-hover:text-blue-400' }} transition-colors"></i>
                <span class="font-bold">Status Lainnya</span>
            </a>
            <a href="{{ route('disposal.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('disposal.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white transition-all' }} text-sm group">
                <i data-lucide="trash-2" class="w-5 h-5 {{ request()->routeIs('disposal.*') ? 'text-white' : 'group-hover:text-blue-400' }} transition-colors"></i>
                <span class="font-bold">Penghapusan Barang</span>
            </a>
            <a href="{{ route('history.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('history.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white transition-all' }} text-sm group">
                <i data-lucide="history" class="w-5 h-5 {{ request()->routeIs('history.*') ? 'text-white' : 'group-hover:text-blue-400' }} transition-colors"></i>
                <span class="font-bold">Riwayat</span>
            </a>
        </div>

        <!-- Lainnya Group -->
        <div class="pt-6 pb-2 px-4">
            <span class="text-[10px] font-black text-slate-600 uppercase tracking-[0.2em]">Lainnya</span>
        </div>
        <div class="space-y-1 pb-6">
            <a href="{{ route('report.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('report.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white transition-all' }} text-sm group">
                <i data-lucide="file-text" class="w-5 h-5 {{ request()->routeIs('report.*') ? 'text-white' : 'group-hover:text-blue-400' }} transition-colors"></i>
                <span class="font-bold">Laporan</span>
            </a>
            <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('users.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white transition-all' }} text-sm group">
                <i data-lucide="users" class="w-5 h-5 {{ request()->routeIs('users.*') ? 'text-white' : 'group-hover:text-blue-400' }} transition-colors"></i>
                <span class="font-bold">Pengguna</span>
            </a>
            <a href="{{ route('setting.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('setting.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white transition-all' }} text-sm group">
                <i data-lucide="settings" class="w-5 h-5 {{ request()->routeIs('setting.*') ? 'text-white' : 'group-hover:text-blue-400' }} transition-colors"></i>
                <span class="font-bold">Pengaturan</span>
            </a>
        </div>
    </nav>

    <!-- Sidebar Footer -->
    <div class="p-4 border-t border-slate-800 bg-slate-800/20">
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
        <button type="button" onclick="handleLogout()" 
            class="w-full flex items-center gap-3 px-4 py-3.5 rounded-2xl text-red-400 hover:bg-red-500/10 transition-all text-sm font-black group">
            <div class="w-9 h-9 bg-red-500/20 rounded-xl flex items-center justify-center group-hover:bg-red-500 group-hover:text-white transition-all">
                <i data-lucide="log-out" class="w-4 h-4"></i>
            </div>
            <span>Keluar Aplikasi</span>
        </button>
    </div>
    <script>
        function handleLogout() {
            showConfirm({
                title: 'Keluar Aplikasi?',
                message: 'Anda akan keluar dari sesi admin.',
                color: 'red',
                icon: 'log-out',
                onConfirm: () => {
                    console.log('Logging out...');
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>
</aside>
