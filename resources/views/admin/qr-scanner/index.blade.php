@extends('layouts.master', ['title' => 'QR Code Scanner'])

@section('content')
<div class="page-header">
    <div>
        <h1>QR Code Scanner</h1>
        <p>Scan QR code barang atau masukkan kode secara manual</p>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
    <!-- Camera Scanner -->
    <div class="form-section" style="margin-bottom:0;">
        <div class="form-section-title">📷 Scan Kamera</div>
        <div id="reader" style="width:100%;border-radius:12px;overflow:hidden;background:#0f172a;min-height:300px;"></div>
        <div style="display:flex;gap:8px;margin-top:12px;">
            <button class="btn btn-primary" id="startBtn" onclick="startScanner()">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M5 7h1a2 2 0 0 0 2 -2a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1a2 2 0 0 0 2 2h1a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-9a2 2 0 0 1 2 -2"/><circle cx="12" cy="13" r="3"/></svg>
                Buka Kamera
            </button>
            <button class="btn btn-outline" id="stopBtn" onclick="stopScanner()" style="display:none;">Stop</button>
        </div>
        <div id="scanResult" style="display:none;margin-top:12px;padding:12px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;">
            <p style="font-size:13px;color:#16a34a;font-weight:600;">✅ QR Code Terdeteksi!</p>
            <p id="scanResultText" style="font-size:13px;color:#1e293b;margin-top:4px;"></p>
        </div>
    </div>

    <!-- Manual Input -->
    <div>
        <div class="form-section" style="margin-bottom:16px;">
            <div class="form-section-title">⌨️ Input Manual</div>
            <form action="{{ route('admin.qr-scanner.search') }}" method="GET">
                <div class="form-group">
                    <label>Masukkan Kode Barang</label>
                    <input type="text" name="code" class="form-control" placeholder="Contoh: INV-0001" id="codeInput" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    Cari Barang
                </button>
            </form>

            @if(session('error'))
            <div style="margin-top:12px;padding:12px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;">
                <p style="font-size:13px;color:#dc2626;">{{ session('error') }}</p>
            </div>
            @endif
        </div>

        <!-- Sample Codes -->
        @if(count($sampleCodes))
        <div class="form-section" style="margin-bottom:16px;">
            <div class="form-section-title">💡 Kode Tersedia</div>
            <div style="display:flex;flex-wrap:wrap;gap:8px;">
                @foreach($sampleCodes as $code)
                <button type="button" class="btn btn-outline btn-sm" onclick="document.getElementById('codeInput').value='{{ $code }}'">
                    {{ $code }}
                </button>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Info -->
        <div class="form-section">
            <div class="form-section-title">ℹ️ Cara Penggunaan</div>
            <div style="font-size:13px;color:var(--text-muted);line-height:1.8;">
                <p>1. Klik <strong>Buka Kamera</strong> dan arahkan ke QR code barang</p>
                <p>2. Atau ketik kode barang secara manual di kolom input</p>
                <p>3. Sistem akan menampilkan detail barang yang ditemukan</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
let html5QrCode = null;

function startScanner() {
    html5QrCode = new Html5Qrcode("reader");
    document.getElementById('startBtn').style.display = 'none';
    document.getElementById('stopBtn').style.display = 'inline-flex';

    html5QrCode.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: { width: 250, height: 250 } },
        (decodedText) => {
            // QR detected
            document.getElementById('scanResult').style.display = 'block';
            document.getElementById('scanResultText').textContent = decodedText;

            // If it's a URL, redirect
            if (decodedText.startsWith('http')) {
                stopScanner();
                window.location.href = decodedText;
            } else {
                // Try to search by code
                document.getElementById('codeInput').value = decodedText;
                stopScanner();
                document.querySelector('form').submit();
            }
        },
        (errorMessage) => { /* ignore scan errors */ }
    ).catch((err) => {
        alert('Tidak bisa mengakses kamera. Pastikan izin kamera sudah diberikan.');
        document.getElementById('startBtn').style.display = 'inline-flex';
        document.getElementById('stopBtn').style.display = 'none';
    });
}

function stopScanner() {
    if (html5QrCode && html5QrCode.isScanning) {
        html5QrCode.stop().then(() => {
            document.getElementById('startBtn').style.display = 'inline-flex';
            document.getElementById('stopBtn').style.display = 'none';
        });
    }
}
</script>
@endpush
