@extends('layouts.master', ['title' => 'Riwayat'])

@section('content')
<div class="page-header">
    <div>
        <h1>Riwayat</h1>
        <p>History peminjaman, perbaikan, dan status khusus</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn-back">← Kembali</a>
</div>

<!-- Stat Cards -->
<div class="stat-cards" style="grid-template-columns:repeat(3,1fr);">
    <div class="stat-card">
        <div class="stat-card-info">
            <div class="stat-label">Peminjaman Selesai</div>
            <div class="stat-value">{{ $peminjamanCount }}</div>
        </div>
        <div class="stat-card-icon blue">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M5 12l5 5l10 -10"/></svg>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-info">
            <div class="stat-label">Perbaikan Selesai</div>
            <div class="stat-value">{{ $perbaikanCount }}</div>
        </div>
        <div class="stat-card-icon purple">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M7 10h3v-3l-3.5 -3.5a6 6 0 0 1 8 8l6 6a2 2 0 0 1 -3 3l-6 -6a6 6 0 0 1 -8 -8l3.5 3.5"/></svg>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-info">
            <div class="stat-label">Status Khusus Selesai</div>
            <div class="stat-value">0</div>
        </div>
        <div class="stat-card-icon teal">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><circle cx="12" cy="12" r="9"/><polyline points="12 7 12 12 15 15"/></svg>
        </div>
    </div>
</div>

<!-- Search -->
<div class="card" style="padding:12px 16px;margin-bottom:16px;">
    <div class="table-search" style="max-width:100%;">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" placeholder="Cari barang, peminjam, atau lokasi..." style="width:100%;">
    </div>
</div>

<!-- Tabs -->
<div class="tabs">
    <a href="{{ route('admin.history.index', ['tab' => 'peminjaman']) }}" class="tab {{ $tab == 'peminjaman' ? 'active' : '' }}">Peminjaman ({{ $peminjamanCount }})</a>
    <a href="{{ route('admin.history.index', ['tab' => 'perbaikan']) }}" class="tab {{ $tab == 'perbaikan' ? 'active' : '' }}">Perbaikan ({{ $perbaikanCount }})</a>
    <a href="{{ route('admin.history.index', ['tab' => 'status_khusus']) }}" class="tab {{ $tab == 'status_khusus' ? 'active' : '' }}">Status Khusus (0)</a>
</div>

<!-- Content -->
@if($tab == 'peminjaman' && $peminjaman instanceof \Illuminate\Pagination\LengthAwarePaginator && $peminjaman->count())
    @foreach($peminjaman as $order)
    <div class="detail-card">
        <div class="detail-card-title">{{ $order->user->name ?? 'Unknown' }}</div>
        <div class="detail-card-subtitle">{{ $order->reason }} · Selesai {{ $order->updated_at->format('d M Y') }}</div>
    </div>
    @endforeach
    {{ $peminjaman->appends(['tab' => 'peminjaman'])->links() }}
@elseif($tab == 'perbaikan' && $perbaikan instanceof \Illuminate\Pagination\LengthAwarePaginator && $perbaikan->count())
    @foreach($perbaikan as $repair)
    <div class="detail-card">
        <div class="detail-card-title">{{ $repair->item->name ?? 'Unknown' }}</div>
        <div class="detail-card-subtitle">{{ $repair->repair_location }} · Selesai {{ $repair->actual_completion ? $repair->actual_completion->format('d M Y') : $repair->updated_at->format('d M Y') }}</div>
    </div>
    @endforeach
    {{ $perbaikan->appends(['tab' => 'perbaikan'])->links() }}
@else
<div class="card">
    <div class="empty-state">
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"><polyline points="12 8 12 12 14 14"/><path d="M3.05 11a9 9 0 1 1 .5 4m-.5 5v-5h5"/></svg>
        <p>Tidak Ada Riwayat</p>
        <span>Belum ada peminjaman yang selesai</span>
    </div>
</div>
@endif
@endsection
