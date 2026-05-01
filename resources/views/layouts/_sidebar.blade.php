<aside id="mainSidebar" class="fixed left-0 top-0 h-screen w-64 bg-white border-r border-slate-100 flex flex-col z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
    <!-- Brand -->
    <div class="px-6 py-8 flex items-center gap-3">
        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-blue-200">
            GKI
        </div>
        <div>
            <h1 class="text-sm font-bold text-slate-800 leading-tight">GKI Delima</h1>
            <p class="text-[10px] text-slate-400 uppercase tracking-widest font-medium">Sistem Inventaris</p>
        </div>
    </div>

    <!-- User Profile Quick Info -->
    <div class="px-4 mb-6">
        <div class="p-3 bg-slate-50 rounded-2xl flex items-center gap-3 border border-slate-100">
            <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold text-xs">
                {{ auth()->id() ?? 'A' }}
            </div>
            <div class="overflow-hidden">
                <h4 class="text-xs font-bold text-slate-700 truncate">{{ auth()->user()->name ?? 'Admin' }}</h4>
                <p class="text-[10px] text-slate-500">Administrator</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 space-y-1 overflow-y-auto custom-scrollbar">
        <!-- Main Group -->
        <div class="space-y-1">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('dashboard') ? 'sidebar-active' : 'text-slate-500 hover:bg-slate-50 transition-all' }} text-sm group">
                <i data-lucide="layout-grid" class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'group-hover:text-blue-600' }} transition-colors"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            <a href="{{ route('inventory.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('inventory.*') ? 'sidebar-active' : 'text-slate-500 hover:bg-slate-50 transition-all' }} text-sm group">
                <i data-lucide="package" class="w-5 h-5"></i>
                <span class="font-medium">Inventaris</span>
            </a>
            <a href="{{ route('stock-opname.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('stock-opname.*') ? 'sidebar-active' : 'text-slate-500 hover:bg-slate-50 transition-all' }} text-sm group">
                <i data-lucide="clipboard-check" class="w-5 h-5 group-hover:text-blue-600 transition-colors"></i>
                <span class="font-medium">Stock Opname</span>
            </a>
            <a href="{{ route('qr-scanner.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('qr-scanner.*') ? 'sidebar-active' : 'text-slate-500 hover:bg-slate-50 transition-all' }} text-sm group">
                <i data-lucide="qr-code" class="w-5 h-5 group-hover:text-blue-600 transition-colors"></i>
                <span class="font-medium">QR Scanner</span>
            </a>
        </div>

        <!-- Status Khusus Group -->
        <div class="pt-6 pb-2">
            <span class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status Khusus</span>
        </div>
        <div class="space-y-1">
            <a href="{{ route('borrowing.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('borrowing.*') ? 'sidebar-active' : 'text-slate-500 hover:bg-slate-50 transition-all' }} text-sm group">
                <i data-lucide="undo-2" class="w-5 h-5 group-hover:text-blue-600 transition-colors"></i>
                <span class="font-medium">Barang Dipinjam</span>
            </a>
            <a href="{{ route('repair.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('repair.*') ? 'sidebar-active' : 'text-slate-500 hover:bg-slate-50 transition-all' }} text-sm group">
                <i data-lucide="wrench" class="w-5 h-5 group-hover:text-blue-600 transition-colors"></i>
                <span class="font-medium">Dalam Perbaikan</span>
            </a>
            <a href="{{ route('special-status.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('special-status.*') ? 'sidebar-active' : 'text-slate-500 hover:bg-slate-50 transition-all' }} text-sm group">
                <i data-lucide="alert-circle" class="w-5 h-5 group-hover:text-blue-600 transition-colors"></i>
                <span class="font-medium">Status Lainnya</span>
            </a>
            <a href="{{ route('disposal.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('disposal.*') ? 'sidebar-active' : 'text-slate-500 hover:bg-slate-50 transition-all' }} text-sm group">
                <i data-lucide="trash-2" class="w-5 h-5 group-hover:text-blue-600 transition-colors"></i>
                <span class="font-medium">Penghapusan Barang</span>
            </a>
            <a href="{{ route('history.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('history.*') ? 'sidebar-active' : 'text-slate-500 hover:bg-slate-50 transition-all' }} text-sm group">
                <i data-lucide="history" class="w-5 h-5 {{ request()->routeIs('history.*') ? 'text-blue-600' : 'group-hover:text-blue-600' }} transition-colors"></i>
                <span class="font-medium">Riwayat</span>
            </a>
        </div>

        <!-- Lainnya Group -->
        <div class="pt-6 pb-2">
            <span class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Lainnya</span>
        </div>
        <div class="space-y-1 pb-6">
            <a href="{{ route('report.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('report.*') ? 'sidebar-active' : 'text-slate-500 hover:bg-slate-50 transition-all' }} text-sm group">
                <i data-lucide="file-text" class="w-5 h-5 group-hover:text-blue-600 transition-colors"></i>
                <span class="font-medium">Laporan</span>
            </a>
            <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('users.*') ? 'sidebar-active' : 'text-slate-500 hover:bg-slate-50 transition-all' }} text-sm group">
                <i data-lucide="users" class="w-5 h-5 group-hover:text-blue-600 transition-colors"></i>
                <span class="font-medium">Pengguna</span>
            </a>
            <a href="{{ route('setting.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('setting.*') ? 'sidebar-active' : 'text-slate-500 hover:bg-slate-50 transition-all' }} text-sm group">
                <i data-lucide="settings" class="w-5 h-5 group-hover:text-blue-600 transition-colors"></i>
                <span class="font-medium">Pengaturan</span>
            </a>
        </div>
    </nav>

    <!-- Sidebar Footer -->
    <div class="p-4 border-t border-slate-100 bg-slate-50/50">
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
        <button type="button" onclick="handleLogout()" 
            class="w-full flex items-center gap-3 px-3 py-3 rounded-2xl text-red-500 hover:bg-red-50 transition-all text-sm font-bold group">
            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center group-hover:bg-red-600 group-hover:text-white transition-all">
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
