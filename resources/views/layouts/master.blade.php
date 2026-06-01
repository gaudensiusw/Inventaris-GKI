<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>GKI Delima - Sistem Inventaris</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-gki.png') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Lucide Icons (pinned version) -->
    <script src="{{ asset('js/lucide.min.js') }}"></script>
    
    <!-- Tom Select -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }

        /* Tom Select Custom Styling */
        .ts-control {
            @apply !bg-slate-50 !border-slate-200 !rounded-2xl !px-4 !py-3 !text-sm !font-medium !shadow-none !transition-all;
        }
        .ts-control:focus {
            @apply !ring-4 !ring-blue-50 !border-blue-400;
        }
        .ts-dropdown {
            @apply !rounded-2xl !mt-2 !border-slate-100 !shadow-2xl !shadow-slate-200/50 !p-2 !z-[1000];
        }
        .ts-dropdown .active {
            @apply !bg-blue-50 !text-blue-600 !rounded-xl;
        }
        .ts-dropdown .option {
            @apply !px-4 !py-2.5 !text-sm !font-medium !text-slate-600 !rounded-xl;
        }
    </style>

    <script>
        let confirmCallback = null;

        function showConfirm({ title, message, icon, color, confirmText, requireReason, onConfirm }) {
            const modal = document.getElementById('confirmModal');
            const content = document.getElementById('confirmModalContent');
            const iconContainer = document.getElementById('confirmIconContainer');
            const iconEl = document.getElementById('confirmIcon');
            const titleEl = document.getElementById('confirmTitle');
            const messageEl = document.getElementById('confirmMessage');
            const btn = document.getElementById('confirmBtn');
            const reasonContainer = document.getElementById('confirmReasonContainer');
            const reasonInput = document.getElementById('confirmReasonInput');

            if (!modal) return;

            titleEl.innerText = title || 'Konfirmasi';
            messageEl.innerText = message || 'Apakah Anda yakin?';
            btn.innerText = confirmText || 'Ya, Lanjutkan';
            
            if (requireReason) {
                reasonContainer.classList.remove('hidden');
                reasonInput.value = '';
            } else {
                reasonContainer.classList.add('hidden');
            }
            
            const themeColor = color || 'blue';
            
            // Map colors to ensure tailwind classes are literal strings for the scanner
            const colorMaps = {
                red: {
                    bg: 'bg-red-600',
                    hover: 'hover:bg-red-700',
                    shadow: 'shadow-red-200',
                    iconBg: 'bg-red-50',
                    iconText: 'text-red-600'
                },
                blue: {
                    bg: 'bg-blue-600',
                    hover: 'hover:bg-blue-700',
                    shadow: 'shadow-blue-200',
                    iconBg: 'bg-blue-50',
                    iconText: 'text-blue-600'
                },
                emerald: {
                    bg: 'bg-emerald-600',
                    hover: 'hover:bg-emerald-700',
                    shadow: 'shadow-emerald-200',
                    iconBg: 'bg-emerald-50',
                    iconText: 'text-emerald-600'
                }
            };

            const config = colorMaps[themeColor] || colorMaps.blue;

            iconContainer.className = `w-20 h-20 rounded-full flex items-center justify-center ${config.iconBg} ${config.iconText}`;
            btn.className = `flex-[2] px-6 py-4 ${config.bg} text-white rounded-2xl text-xs font-black uppercase shadow-xl ${config.shadow} ${config.hover} transition-all`;
            iconEl.setAttribute('data-lucide', icon || 'help-circle');
            
            if (window.lucide) lucide.createIcons();

            confirmCallback = () => {
                let reason = null;
                if (requireReason) {
                    reason = reasonInput.value.trim();
                    if (!reason) {
                        alert('Alasan harus diisi!');
                        return false;
                    }
                }
                onConfirm(reason);
                return true;
            };
            
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
                confirmText: options.confirmText || 'Ya, Lanjutkan',
                requireReason: options.requireReason || false,
                onConfirm: (reason) => {
                    if (options.requireReason && reason) {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'delete_reason';
                        input.value = reason;
                        form.appendChild(input);
                    }
                    form.submit();
                }
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
                <div id="confirmReasonContainer" class="w-full hidden text-left flex flex-col gap-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-widest">Alasan Penghapusan <span class="text-red-500">*</span></label>
                    <textarea id="confirmReasonInput" rows="2" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-red-50 focus:border-red-400 transition-all font-medium text-sm shadow-sm" placeholder="Masukkan alasan penghapusan barang..."></textarea>
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
                        const result = confirmCallback();
                        if (result === false) return; // Callback can prevent closing
                    }
                    closeConfirmModal();
                };
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
