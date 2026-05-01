@extends('layouts.master')

@section('content')
<div class="flex flex-col gap-8">
    <!-- Header Section -->
    <div>
        <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">QR Code Scanner</h1>
        <p class="text-slate-500 text-sm mt-1">Scan QR code barang atau masukkan kode secara manual untuk melihat detail.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left: Camera Section -->
        <div class="flex flex-col gap-6">
            <div class="card-premium shadow-card p-6 overflow-hidden relative min-h-[400px] flex flex-col items-center justify-center bg-slate-900 border-none group">
                <!-- Camera Feed Placeholder / Container -->
                <div id="reader" class="w-full h-full absolute inset-0 z-10 opacity-0 transition-opacity duration-500"></div>
                
                <!-- UI Overlay when camera is off -->
                <div id="camera-placeholder" class="z-0 flex flex-col items-center gap-4 text-center p-8">
                    <div class="w-20 h-20 bg-white/10 rounded-3xl flex items-center justify-center text-white/50 backdrop-blur-md mb-2">
                        <i data-lucide="camera" class="w-10 h-10"></i>
                    </div>
                    <h3 class="text-white font-bold text-lg">Kamera Belum Aktif</h3>
                    <p class="text-white/40 text-sm max-w-[250px]">Klik tombol di bawah untuk mengaktifkan scanner kamera.</p>
                </div>

                <!-- Scanning Animation (Hidden by default) -->
                <div id="scanner-overlay" class="hidden absolute inset-0 z-20 pointer-events-none">
                    <div class="absolute inset-0 border-[40px] border-black/40"></div>
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 border-2 border-blue-500 rounded-3xl shadow-[0_0_50px_rgba(59,130,246,0.5)]">
                        <div class="absolute top-0 left-0 w-full h-1 bg-blue-500 shadow-[0_0_15px_rgba(59,130,246,1)] animate-scan"></div>
                    </div>
                </div>
            </div>
            
            <button id="start-btn" class="btn-primary-custom w-full py-4 flex items-center justify-center gap-2 group">
                <i data-lucide="aperture" class="w-5 h-5 group-hover:rotate-90 transition-transform duration-500"></i>
                <span class="font-bold">Buka Kamera Scanner</span>
            </button>
            <button id="stop-btn" class="hidden px-4 py-4 bg-red-100 text-red-600 rounded-2xl font-bold hover:bg-red-200 transition-all flex items-center justify-center gap-2">
                <i data-lucide="camera-off" class="w-5 h-5"></i>
                <span>Matikan Kamera</span>
            </button>
        </div>

        <!-- Right: Manual Input & Info -->
        <div class="flex flex-col gap-6">
            <!-- Manual Search Card -->
            <div class="card-premium shadow-card p-8 flex flex-col gap-6">
                <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                        <i data-lucide="keyboard" class="w-5 h-5"></i>
                    </div>
                    <h3 class="font-bold text-slate-700">Input Manual</h3>
                </div>

                <div class="flex flex-col gap-4">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Masukkan Kode Barang</label>
                    <div class="relative group">
                        <i data-lucide="hash" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                        <input type="text" id="manual-code" placeholder="Contoh: INV-001" 
                            class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all font-bold text-slate-700">
                    </div>
                    <button id="search-btn" class="w-full py-4 bg-slate-800 text-white rounded-2xl font-bold hover:bg-slate-900 transition-all shadow-lg flex items-center justify-center gap-2">
                        <i data-lucide="search" class="w-5 h-5"></i>
                        <span>Cari Barang</span>
                    </button>
                </div>

                <div class="flex flex-col gap-3 pt-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Kode Tersedia Baru-baru Ini</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($recentItems as $item)
                            <button onclick="document.getElementById('manual-code').value='{{ $item->item_id }}'" 
                                class="px-3 py-1.5 bg-slate-100 hover:bg-blue-100 hover:text-blue-600 rounded-lg text-xs font-bold text-slate-500 transition-all">
                                {{ $item->item_id }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Instructions Card -->
            <div class="card-premium shadow-card p-8 bg-blue-600 text-white border-none relative overflow-hidden">
                <div class="relative z-10 flex flex-col gap-4">
                    <div class="flex items-center gap-3">
                        <i data-lucide="info" class="w-6 h-6 text-blue-200"></i>
                        <h3 class="font-bold text-lg">Cara Penggunaan</h3>
                    </div>
                    <ul class="space-y-4">
                        <li class="flex gap-4 items-start">
                            <span class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center text-xs font-bold shrink-0">1</span>
                            <p class="text-sm text-blue-50 font-medium">Klik <b>Buka Kamera</b> dan berikan izin akses browser.</p>
                        </li>
                        <li class="flex gap-4 items-start">
                            <span class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center text-xs font-bold shrink-0">2</span>
                            <p class="text-sm text-blue-50 font-medium">Arahkan kamera ke QR Code barang hingga terdeteksi otomatis.</p>
                        </li>
                        <li class="flex gap-4 items-start">
                            <span class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center text-xs font-bold shrink-0">3</span>
                            <p class="text-sm text-blue-50 font-medium">Atau ketik kode barang secara manual jika QR code rusak.</p>
                        </li>
                    </ul>
                </div>
                <!-- Decorative Circle -->
                <div class="absolute -bottom-12 -right-12 w-48 h-48 bg-white/10 rounded-full blur-2xl"></div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const reader = document.getElementById('reader');
        const startBtn = document.getElementById('start-btn');
        const stopBtn = document.getElementById('stop-btn');
        const placeholder = document.getElementById('camera-placeholder');
        const overlay = document.getElementById('scanner-overlay');
        const searchBtn = document.getElementById('search-btn');
        const manualInput = document.getElementById('manual-code');

        let html5QrCode = new Html5Qrcode("reader");

        const qrConfig = { fps: 10, qrbox: { width: 250, height: 250 } };

        startBtn.addEventListener('click', () => {
            startBtn.classList.add('hidden');
            stopBtn.classList.remove('hidden');
            placeholder.classList.add('hidden');
            reader.classList.remove('opacity-0');
            overlay.classList.remove('hidden');

            html5QrCode.start(
                { facingMode: "environment" }, 
                qrConfig,
                (decodedText, decodedResult) => {
                    handleScan(decodedText);
                },
                (errorMessage) => {
                    // Optional: handle errors
                }
            ).catch((err) => {
                alert("Error starting camera: " + err);
                resetUI();
            });
        });

        stopBtn.addEventListener('click', () => {
            html5QrCode.stop().then(() => {
                resetUI();
            });
        });

        searchBtn.addEventListener('click', () => {
            const code = manualInput.value;
            if (code) handleScan(code);
        });

        function handleScan(code) {
            fetch('{{ route("qr-scanner.search") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ code: code })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect_url;
                } else {
                    alert(data.message);
                }
            });
        }

        function resetUI() {
            startBtn.classList.remove('hidden');
            stopBtn.classList.add('hidden');
            placeholder.classList.remove('hidden');
            reader.classList.add('opacity-0');
            overlay.classList.add('hidden');
        }
    });
</script>

<style>
    @keyframes scan {
        0%, 100% { top: 0; }
        50% { top: 100%; }
    }
    .animate-scan {
        position: absolute;
        animation: scan 2s infinite ease-in-out;
    }
</style>
@endsection
