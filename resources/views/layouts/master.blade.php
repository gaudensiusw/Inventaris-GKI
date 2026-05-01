<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GKI Delima - Sistem Inventaris</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
    </style>

    <script>
        let confirmCallback = null;

        function showConfirm({ title, message, icon, color, onConfirm }) {
            const modal = document.getElementById('confirmModal');
            const content = document.getElementById('confirmModalContent');
            const iconContainer = document.getElementById('confirmIconContainer');
            const iconEl = document.getElementById('confirmIcon');
            const titleEl = document.getElementById('confirmTitle');
            const messageEl = document.getElementById('confirmMessage');
            const btn = document.getElementById('confirmBtn');

            if (!modal) return;

            titleEl.innerText = title || 'Konfirmasi';
            messageEl.innerText = message || 'Apakah Anda yakin?';
            
            const themeColor = color || 'blue';
            iconContainer.className = `w-20 h-20 rounded-full flex items-center justify-center bg-${themeColor}-50 text-${themeColor}-600`;
            btn.className = `flex-[2] px-6 py-4 bg-${themeColor}-600 text-white rounded-2xl text-xs font-black uppercase shadow-xl shadow-${themeColor}-200 hover:bg-${themeColor}-700 transition-all`;
            iconEl.setAttribute('data-lucide', icon || 'help-circle');
            
            if (window.lucide) lucide.createIcons();

            confirmCallback = onConfirm;
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeConfirmModal() {
            const modal = document.getElementById('confirmModal');
            const content = document.getElementById('confirmModalContent');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        }

        function confirmSubmit(form, options = {}) {
            showConfirm({
                title: options.title || 'Konfirmasi',
                message: options.message || 'Apakah Anda yakin ingin melanjutkan?',
                icon: options.icon || 'alert-triangle',
                color: options.color || 'blue',
                onConfirm: () => form.submit()
            });
            return false;
        }
    </script>
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-800">
    <div class="flex min-h-screen relative overflow-x-hidden">
        <!-- Sidebar Overlay (Mobile Only) -->
        <div id="sidebarOverlay" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/50 z-40 hidden backdrop-blur-sm lg:hidden transition-all duration-300 opacity-0"></div>

        <!-- Sidebar -->
        @include('layouts._sidebar')

        <!-- Main Content -->
        <main class="flex-1 lg:ml-64 min-h-screen flex flex-col w-full">
            <!-- Mobile Header -->
            <header class="h-16 lg:h-20 flex items-center justify-between px-4 lg:px-8 bg-white lg:bg-transparent border-b lg:border-none border-slate-100 sticky top-0 z-30">
                <button onclick="toggleSidebar()" class="lg:hidden p-2 hover:bg-slate-50 rounded-xl transition-all">
                    <i data-lucide="menu" class="w-6 h-6 text-slate-600"></i>
                </button>

                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex text-[11px] font-semibold text-slate-400 bg-white px-3 py-1.5 rounded-full shadow-sm border border-slate-100 items-center gap-2">
                        <i data-lucide="calendar" class="w-3 h-3"></i>
                        <span>{{ now()->isoFormat('dddd, D MMMM YYYY') }}</span>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="px-4 lg:px-8 pb-12 flex-1">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Initialize Lucide Icons & Responsive Logic -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('mainSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (sidebar.classList.contains('-translate-x-full')) {
                // Open
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                setTimeout(() => overlay.classList.add('opacity-100'), 10);
                document.body.style.overflow = 'hidden';
            } else {
                // Close
                sidebar.classList.add('-translate-x-full');
                overlay.classList.remove('opacity-100');
                setTimeout(() => {
                    overlay.classList.add('hidden');
                }, 300);
                document.body.style.overflow = '';
            }
        }

        lucide.createIcons();
    </script>
    
    @stack('modals')

    <!-- Global Confirmation Modal -->
    <div id="confirmModal" class="fixed inset-0 bg-slate-900/50 hidden items-center justify-center z-[200] backdrop-blur-sm">
        <div class="bg-white rounded-[40px] w-full max-w-md overflow-hidden shadow-2xl transform transition-all scale-95 opacity-0 duration-300" id="confirmModalContent">
            <div class="p-8 flex flex-col items-center text-center gap-6">
                <div id="confirmIconContainer" class="w-20 h-20 rounded-full flex items-center justify-center">
                    <i id="confirmIcon" data-lucide="help-circle" class="w-10 h-10"></i>
                </div>
                <div>
                    <h3 id="confirmTitle" class="text-xl font-black text-slate-800 tracking-tight">Konfirmasi Tindakan</h3>
                    <p id="confirmMessage" class="text-slate-500 text-sm mt-2">Apakah Anda yakin ingin melakukan tindakan ini? Data yang dihapus tidak dapat dikembalikan.</p>
                </div>
                <div class="flex gap-3 w-full">
                    <button onclick="closeConfirmModal()" class="flex-1 px-6 py-4 bg-slate-100 text-slate-600 rounded-2xl text-xs font-black uppercase hover:bg-slate-200 transition-all">Batal</button>
                    <button id="confirmBtn" class="flex-[2] px-6 py-4 bg-blue-600 text-white rounded-2xl text-xs font-black uppercase shadow-xl shadow-blue-200 hover:bg-blue-700 transition-all">Ya, Lanjutkan</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const confirmBtn = document.getElementById('confirmBtn');
            if (confirmBtn) {
                confirmBtn.onclick = function() {
                    console.log('Confirm button clicked, executing callback...');
                    if (typeof confirmCallback === 'function') {
                        confirmCallback();
                    }
                    closeConfirmModal();
                };
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
