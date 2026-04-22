@extends('layouts.master', ['title' => 'QR Code Scanner'])

@section('content')
<div class="card">
    <div class="qr-section">
        <div class="qr-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><rect x="4" y="4" width="6" height="6"/><rect x="14" y="4" width="6" height="6"/><rect x="4" y="14" width="6" height="6"/><rect x="14" y="14" width="2" height="2"/><path d="M20 14v2h-2"/><path d="M14 20h2v-2"/><path d="M20 20v-2h-2"/></svg>
        </div>
        <h1 style="font-size:24px;font-weight:700;margin-bottom:4px;">QR Code Scanner</h1>
        <p style="color:#64748b;font-size:13px;">Scan QR code barang atau masukkan kode secara manual</p>

        <!-- Manual Input -->
        <div style="max-width:500px;margin:24px auto;">
            <label style="display:block;text-align:left;font-size:12px;font-weight:600;margin-bottom:6px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" style="vertical-align:-2px;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                Masukkan Kode QR Manual
            </label>
            <form action="{{ route('admin.qr-scanner.search') }}" method="GET" class="qr-search-box">
                <input type="text" name="code" placeholder="Contoh: QR-INV-001" required>
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    Cari
                </button>
            </form>
        </div>

        @if(session('error'))
        <p style="color:#ef4444;font-size:13px;margin-bottom:16px;">{{ session('error') }}</p>
        @endif

        <!-- Sample Codes -->
        @if(count($sampleCodes))
        <div style="margin-bottom:24px;">
            <p style="font-size:12px;color:#f59e0b;font-weight:500;margin-bottom:8px;">💡 Coba Kode Contoh:</p>
            <div class="code-chips">
                @foreach($sampleCodes as $code)
                <span class="code-chip" onclick="document.querySelector('[name=code]').value='{{ $code }}'">{{ $code }}</span>
                @endforeach
            </div>
        </div>
        @endif

        <p style="color:#94a3b8;font-size:13px;margin-bottom:8px;">Atau gunakan kamera untuk scan QR code</p>
        <button class="btn btn-outline" onclick="alert('Fitur kamera QR scanner akan segera tersedia!')">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M5 7h1a2 2 0 0 0 2 -2a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1a2 2 0 0 0 2 2h1a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-9a2 2 0 0 1 2 -2"/><circle cx="12" cy="13" r="3"/></svg>
            Buka Kamera Scanner
        </button>
    </div>
</div>

<!-- Info Cards -->
<div class="info-cards">
    <div class="info-card blue-tint">
        <h4>Cara Scan</h4>
        <p>Arahkan kamera ke QR code pada barang hingga terdeteksi otomatis</p>
    </div>
    <div class="info-card green-tint">
        <h4>Input Manual</h4>
        <p>Ketik kode QR secara manual jika kamera tidak tersedia</p>
    </div>
    <div class="info-card purple-tint">
        <h4>Detail Lengkap</h4>
        <p>Lihat informasi lengkap barang setelah berhasil dipindai</p>
    </div>
</div>
@endsection
