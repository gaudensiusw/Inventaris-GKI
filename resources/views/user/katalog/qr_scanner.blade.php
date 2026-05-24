@extends('layouts.user')

@section('content')
    <div class="space-y-6">
        <!-- Back Link -->
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-blue-600 transition-colors font-semibold">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Kembali ke Beranda
        </a>

        <!-- Header Section -->
        <div>
            <h1 class="text-2xl font-black text-slate-800">QR Code Scanner</h1>
            <p class="text-slate-500 text-sm mt-1">Pindai QR code pada label barang inventaris untuk melihat informasi dan mengajukan peminjaman secara instan.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left: Camera Section -->
            <div class="flex flex-col gap-6">
                <div class="bg-slate-900 rounded-[32px] overflow-hidden relative min-h-[400px] flex flex-col items-center justify-center border-none shadow-md group">
                    <!-- Camera Feed Container -->
                    <div id="reader" class="w-full h-full absolute inset-0 z-10 opacity-0 transition-opacity duration-500">
                    </div>

                    <!-- UI Overlay when camera is off -->
                    <div id="camera-placeholder" class="z-0 flex flex-col items-center gap-4 text-center p-8">
                        <div class="w-20 h-20 bg-white/10 rounded-3xl flex items-center justify-center text-white/50 backdrop-blur-md mb-2">
                            <i data-lucide="camera" class="w-10 h-10"></i>
                        </div>
                        <h3 class="text-white font-bold text-lg">Kamera Belum Aktif</h3>
                        <p class="text-white/40 text-sm max-w-[250px]">Klik tombol di bawah untuk mengaktifkan scanner kamera HP Anda.</p>
                    </div>

                    <!-- Scanning Animation -->
                    <div id="scanner-overlay" class="hidden absolute inset-0 z-20 pointer-events-none">
                        <div class="absolute inset-0 border-[40px] border-black/40"></div>
                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 border-2 border-blue-500 rounded-3xl shadow-[0_0_50px_rgba(59,130,246,0.5)]">
                            <div class="absolute top-0 left-0 w-full h-1 bg-blue-500 shadow-[0_0_15px_rgba(59,130,246,1)] animate-scan"></div>
                        </div>
                    </div>
                </div>

                <button id="start-btn" class="w-full py-4 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 transition-all shadow-xl shadow-blue-200 flex items-center justify-center gap-2 group">
                    <i data-lucide="aperture" class="w-5 h-5 group-hover:rotate-90 transition-transform duration-500"></i>
                    <span class="font-bold">Buka Kamera Scanner</span>
                </button>
                <button id="stop-btn" class="hidden px-4 py-4 bg-red-500 text-white rounded-2xl font-bold hover:bg-red-600 transition-all shadow-xl shadow-red-200 flex items-center justify-center gap-2">
                    <i data-lucide="camera-off" class="w-5 h-5"></i>
                    <span>Matikan Kamera</span>
                </button>
            </div>

            <!-- Right: Manual Input & Info -->
            <div class="flex flex-col gap-6">
                <!-- Manual Search Card -->
                <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm p-8 flex flex-col gap-6">
                    <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                        <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                            <i data-lucide="keyboard" class="w-5 h-5"></i>
                        </div>
                        <h3 class="font-bold text-slate-750">Input Kode Manual</h3>
                    </div>

                    <div class="flex flex-col gap-4">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Masukkan Kode Barang</label>
                        <div class="relative group">
                            <i data-lucide="hash" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                            <input type="text" id="manual-code" placeholder="Contoh: 10001"
                                class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all font-bold text-slate-700">
                        </div>
                        <button id="search-btn" class="w-full py-4 bg-slate-800 text-white rounded-2xl font-bold hover:bg-slate-900 transition-all shadow-lg flex items-center justify-center gap-2">
                            <i data-lucide="search" class="w-5 h-5"></i>
                            <span>Cari Barang</span>
                        </button>
                    </div>

                    <div class="flex flex-col gap-3 pt-2">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Kode Barang Terbaru</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($recentItems as $item)
                                <button onclick="document.getElementById('manual-code').value='{{ $item->kode_aset ?? $item->entno }}'"
                                    class="px-3 py-1.5 bg-slate-100 hover:bg-blue-100 hover:text-blue-600 rounded-lg text-xs font-bold text-slate-500 transition-all">
                                    {{ $item->kode_aset ?? $item->entno }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Instructions Card -->
                <div class="bg-blue-650 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-[32px] p-8 text-white relative overflow-hidden shadow-lg">
                    <div class="relative z-10 flex flex-col gap-4">
                        <div class="flex items-center gap-3">
                            <i data-lucide="info" class="w-6 h-6 text-blue-200"></i>
                            <h3 class="font-bold text-lg">Panduan Peminjaman via QR</h3>
                        </div>
                        <ul class="space-y-4">
                            <li class="flex gap-4 items-start">
                                <span class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center text-xs font-bold shrink-0">1</span>
                                <p class="text-sm text-blue-50 font-medium">Aktifkan kamera dengan menekan tombol <b>Buka Kamera</b>.</p>
                            </li>
                            <li class="flex gap-4 items-start">
                                <span class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center text-xs font-bold shrink-0">2</span>
                                <p class="text-sm text-blue-50 font-medium">Arahkan kamera ke label stiker QR barang di ruangan gereja.</p>
                            </li>
                            <li class="flex gap-4 items-start">
                                <span class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center text-xs font-bold shrink-0">3</span>
                                <p class="text-sm text-blue-50 font-medium">Setelah deteksi sukses, informasi barang akan muncul di layar. Klik tombol <b>"Ajukan Peminjaman"</b> untuk mengisi form pesan.</p>
                            </li>
                        </ul>
                    </div>
                    <!-- Decorative Circle -->
                    <div class="absolute -bottom-12 -right-12 w-48 h-48 bg-white/10 rounded-full blur-2xl"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div id="preview-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closePreview()"></div>

        <div class="relative bg-white w-full max-w-lg rounded-[32px] shadow-2xl overflow-hidden transform transition-all scale-95 opacity-0 duration-300" id="modal-content">
             <!-- Modal Header / Image Area -->
            <div class="relative h-48 bg-gradient-to-br from-blue-600 to-indigo-700 p-8 flex items-end justify-between overflow-hidden">
                <img id="modal-header-bg" src="" class="absolute inset-0 w-full h-full object-cover hidden z-0 opacity-90 transition-all duration-300">
                <div id="modal-header-overlay" class="absolute inset-0 bg-slate-950/40 hidden z-0"></div>

                <div class="absolute top-0 left-0 w-full h-full opacity-10 z-0">
                    <div class="absolute -top-10 -left-10 w-40 h-40 bg-white rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white rounded-full blur-3xl"></div>
                </div>

                <div class="relative z-10">
                    <div id="modal-category" class="bg-white/20 backdrop-blur-md border border-white/20 text-white text-[10px] font-black uppercase tracking-[0.2em] px-3 py-1 rounded-full mb-3 w-fit">
                        Kategori
                    </div>
                    <h2 id="modal-item-name" class="text-2xl font-black text-white leading-tight">Nama Barang</h2>
                </div>

                <div class="relative z-10 flex flex-col items-end">
                    <div id="modal-status-badge" class="bg-emerald-500 text-white text-[10px] font-bold px-3 py-1 rounded-lg shadow-lg mb-2">
                        Tersedia
                    </div>
                    <div id="modal-asset-code" class="text-blue-100 text-xs font-mono font-bold tracking-wider">
                        INV-000
                    </div>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-8">
                <div class="grid grid-cols-2 gap-6 mb-8">
                    <div class="flex flex-col gap-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Lokasi / Ruangan</span>
                        <div class="flex items-center gap-2 text-slate-700 font-bold">
                            <i data-lucide="map-pin" class="w-4 h-4 text-blue-500"></i>
                            <span id="modal-room">Ruang Utama</span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kondisi Fisik</span>
                        <div class="flex items-center gap-2 text-slate-700 font-bold">
                            <i data-lucide="shield-check" class="w-4 h-4 text-emerald-500"></i>
                            <span id="modal-condition">Baik</span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Kuantitas</span>
                        <div class="flex items-center gap-2 text-slate-700 font-bold">
                            <i data-lucide="package" class="w-4 h-4 text-indigo-500"></i>
                            <span id="modal-qty">1 Unit</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col gap-3">
                    <a id="modal-view-link" href="#" class="w-full py-4 bg-emerald-600 text-white rounded-2xl font-bold hover:bg-emerald-700 transition-all shadow-xl shadow-emerald-200 flex items-center justify-center gap-2">
                        <i data-lucide="calendar" class="w-5 h-5"></i>
                        <span>Ajukan Peminjaman Barang</span>
                    </a>
                    <button onclick="closePreview()" class="w-full py-4 bg-slate-100 text-slate-600 rounded-2xl font-bold hover:bg-slate-200 transition-all flex items-center justify-center gap-2">
                        <i data-lucide="x" class="w-5 h-5"></i>
                        <span>Tutup & Scan Lagi</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const reader = document.getElementById('reader');
            const startBtn = document.getElementById('start-btn');
            const stopBtn = document.getElementById('stop-btn');
            const placeholder = document.getElementById('camera-placeholder');
            const overlay = document.getElementById('scanner-overlay');
            const searchBtn = document.getElementById('search-btn');
            const manualInput = document.getElementById('manual-code');

            let html5QrCode = null;

            const qrConfig = { fps: 10, qrbox: { width: 250, height: 250 } };

            startBtn.addEventListener('click', () => {
                console.log("Starting public scanner...");

                try {
                    if (!html5QrCode) {
                        html5QrCode = new Html5Qrcode("reader");
                    }

                    startBtn.classList.add('hidden');
                    stopBtn.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                    reader.classList.remove('opacity-0');
                    overlay.classList.remove('hidden');

                    html5QrCode.start(
                        { facingMode: "environment" },
                        qrConfig,
                        (decodedText, decodedResult) => {
                            console.log("Code detected:", decodedText);
                            handleScan(decodedText);
                            stopBtn.click();
                        },
                        (errorMessage) => {
                            // scan loop
                        }
                    ).catch((err) => {
                        console.error("Camera start failed:", err);
                        alert("Gagal mengaktifkan kamera: " + err + "\n\nCatatan: Pastikan Anda memberikan izin akses kamera dan mengakses situs menggunakan HTTPS.");
                        resetUI();
                    });
                } catch (e) {
                    console.error("Initialization error:", e);
                    alert("Gagal inisialisasi scanner: " + e.message);
                }
            });

            stopBtn.addEventListener('click', () => {
                if (html5QrCode) {
                    html5QrCode.stop().then(() => {
                        console.log("Scanner stopped.");
                        resetUI();
                    }).catch(err => {
                        console.warn("Error stopping scanner:", err);
                        resetUI();
                    });
                } else {
                    resetUI();
                }
            });

            searchBtn.addEventListener('click', () => {
                const code = manualInput.value.trim();
                if (code) {
                    handleScan(code);
                } else {
                    alert("Silakan masukkan kode barang.");
                }
            });

            manualInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    searchBtn.click();
                }
            });

            function handleScan(code) {
                searchBtn.disabled = true;
                searchBtn.innerHTML = '<i data-lucide="loader-2" class="w-5 h-5 animate-spin"></i><span>Mencari...</span>';
                lucide.createIcons();

                fetch('{{ route("user.katalog.qr-search") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ code: code })
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            showPreview(data.item, data.redirect_url);
                            resetBtnState();
                        } else {
                            alert(data.message || "Barang tidak ditemukan.");
                            resetBtnState();
                        }
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                        alert("Terjadi kesalahan saat mencari barang. Silakan coba lagi.");
                        resetBtnState();
                    });
            }

            function showPreview(item, redirectUrl) {
                document.getElementById('modal-item-name').innerText = item.name;
                document.getElementById('modal-asset-code').innerText = item.kode_aset;
                document.getElementById('modal-category').innerText = item.category;
                document.getElementById('modal-room').innerText = item.room;
                document.getElementById('modal-condition').innerText = item.condition;
                document.getElementById('modal-qty').innerText = item.quantity + ' Unit';
                document.getElementById('modal-status-badge').innerText = item.status;
                document.getElementById('modal-view-link').href = redirectUrl;

                const badge = document.getElementById('modal-status-badge');
                if (item.status === 'Tersedia') {
                    badge.className = 'bg-emerald-500 text-white text-[10px] font-bold px-3 py-1 rounded-lg shadow-lg mb-2';
                } else {
                    badge.className = 'bg-amber-500 text-white text-[10px] font-bold px-3 py-1 rounded-lg shadow-lg mb-2';
                }

                const headerBg = document.getElementById('modal-header-bg');
                const headerOverlay = document.getElementById('modal-header-overlay');
                if (item.image_url) {
                    headerBg.src = item.image_url;
                    headerBg.classList.remove('hidden');
                    headerOverlay.classList.remove('hidden');
                } else {
                    headerBg.src = '';
                    headerBg.classList.add('hidden');
                    headerOverlay.classList.add('hidden');
                }

                const modal = document.getElementById('preview-modal');
                const content = document.getElementById('modal-content');

                modal.classList.remove('hidden');
                setTimeout(() => {
                    content.classList.remove('scale-95', 'opacity-0');
                    content.classList.add('scale-100', 'opacity-100');
                }, 10);

                lucide.createIcons();
            }

            window.closePreview = function () {
                const modal = document.getElementById('preview-modal');
                const content = document.getElementById('modal-content');

                content.classList.add('scale-95', 'opacity-0');
                content.classList.remove('scale-100', 'opacity-100');

                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            };

            function resetBtnState() {
                searchBtn.disabled = false;
                searchBtn.innerHTML = '<i data-lucide="search" class="w-5 h-5"></i><span>Cari Barang</span>';
                lucide.createIcons();
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
