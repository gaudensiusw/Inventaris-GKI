@extends('layouts.master', ['title' => 'QR Code - ' . $item->name])

@section('content')
<div class="page-header">
    <div style="display:flex;align-items:center;gap:12px;">
        <a href="{{ route('admin.item.index') }}" class="btn-back">←</a>
        <div>
            <h1>QR Code</h1>
            <p>{{ $item->name }}</p>
        </div>
    </div>
    <button class="btn btn-primary" onclick="window.print()">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2"/><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4"/><rect x="7" y="13" width="10" height="8" rx="2"/></svg>
        Print QR Code
    </button>
</div>

<div style="display:flex;justify-content:center;padding:40px 0;">
    <div style="background:#fff;padding:40px;border-radius:16px;box-shadow:0 4px 20px rgba(0,0,0,0.06);text-align:center;border:1px solid var(--border-color);min-width:340px;">
        <h2 style="font-size:20px;font-weight:700;color:#1e293b;margin-bottom:4px;">{{ $item->name }}</h2>
        <p style="font-size:13px;color:#64748b;margin-bottom:20px;">{{ $item->category->name ?? '-' }} &bull; {{ $item->room->name ?? '-' }}</p>

        <div id="qrcode" style="display:flex;justify-content:center;margin:20px 0;"></div>

        <div style="font-family:monospace;font-size:14px;color:#1e293b;letter-spacing:2px;background:#f1f5f9;padding:8px 16px;border-radius:8px;display:inline-block;">
            {{ $item->barcode }}
        </div>

        <div style="margin-top:16px;display:flex;gap:8px;justify-content:center;flex-wrap:wrap;">
            @if($item->quantity)
            <span class="badge badge-blue">Qty: {{ $item->quantity }} {{ $item->unit ?? '' }}</span>
            @endif
            @if($item->condition)
            <span class="badge badge-{{ $item->condition == 'Baik' ? 'green' : 'orange' }}">{{ $item->condition }}</span>
            @endif
            @if($item->status)
            <span class="badge badge-{{ $item->status == 'Tersedia' ? 'green' : 'blue' }}">{{ $item->status }}</span>
            @endif
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
    new QRCode(document.getElementById("qrcode"), {
        text: "{{ url('/inventaris?search=' . $item->barcode) }}",
        width: 200,
        height: 200,
        colorDark: "#1e293b",
        colorLight: "#ffffff"
    });
</script>
@endpush
