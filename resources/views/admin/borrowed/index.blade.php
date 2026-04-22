@extends('layouts.master', ['title' => 'Barang Dipinjam'])

@section('content')
<div class="page-header">
    <div>
        <h1>Barang Dipinjam</h1>
        <p>Kelola dan pantau barang yang sedang dipinjam</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn-back">← Kembali</a>
</div>

<!-- Stat Cards -->
<div class="stat-cards">
    <div class="stat-card">
        <div class="stat-card-info">
            <div class="stat-label">Item Dipinjam</div>
            <div class="stat-value">{{ $totalBorrowed }}</div>
        </div>
        <div class="stat-card-icon blue">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3"/></svg>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-info">
            <div class="stat-label">Total Quantity</div>
            <div class="stat-value">{{ $totalQty }}</div>
        </div>
        <div class="stat-card-icon green">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M5 12l5 5l10 -10"/></svg>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-info">
            <div class="stat-label">Akan Kembali Segera</div>
            <div class="stat-value">{{ $returningSoon }}</div>
        </div>
        <div class="stat-card-icon orange">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><circle cx="12" cy="12" r="9"/><polyline points="12 7 12 12 15 15"/></svg>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-info">
            <div class="stat-label">Terlambat Kembali</div>
            <div class="stat-value" style="color:#ef4444;">{{ $overdue }}</div>
        </div>
        <div class="stat-card-icon red">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><rect x="4" y="5" width="16" height="16" rx="2"/><line x1="16" y1="3" x2="16" y2="7"/><line x1="8" y1="3" x2="8" y2="7"/><line x1="4" y1="11" x2="20" y2="11"/></svg>
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

<!-- Borrowed Items -->
@forelse($borrowedItems as $item)
<div class="detail-card">
    <div class="detail-card-header">
        <div>
            <div class="detail-card-title">{{ $item->name }} <span class="badge badge-gray">{{ $item->inv_code }}</span> <span class="badge badge-blue">Sudah dipinjam</span></div>
            <div class="detail-card-subtitle">{{ $item->category->name ?? '-' }} · Total: {{ $item->quantity }} unit</div>
        </div>
        <a href="{{ route('admin.item.edit', $item) }}" style="color:#3b82f6;font-size:13px;text-decoration:none;">Edit</a>
    </div>

    @php
        $activeRent = $item->rents->where('status', 'Dipinjam')->first();
        $order = $activeRent ? $activeRent->order : null;
    @endphp

    @if($order)
    <div style="margin-bottom:12px;">
        <span class="badge badge-blue">Peminjaman #{{ $order->id }}</span>
        @if($order->end_date && $order->end_date < now())
        <span class="badge badge-red">Terlambat {{ now()->diffInDays($order->end_date) }} hari</span>
        @endif
    </div>

    <div class="detail-grid">
        <div class="detail-item">
            <div class="detail-label">Jumlah Dipinjam</div>
            <div class="detail-value">{{ $item->qty_dipinjam ?: 1 }} unit</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Peminjam</div>
            <div class="detail-value">{{ $order->user->name ?? '-' }}</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Lokasi Peminjaman</div>
            <div class="detail-value" style="color:#3b82f6;">{{ $item->room->name ?? '-' }}</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Tanggal Pinjam</div>
            <div class="detail-value">{{ $order->start_date ? $order->start_date->format('d F Y') : '-' }}</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Tanggal Kembali</div>
            <div class="detail-value">{{ $order->end_date ? $order->end_date->format('d F Y') : '-' }}</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Catatan</div>
            <div class="detail-value">{{ $order->reason ?? '-' }}</div>
        </div>
    </div>
    @else
    <p style="color:#94a3b8;font-size:13px;">Detail peminjaman tidak tersedia</p>
    @endif

    <div style="text-align:right;margin-top:16px;">
        <button class="btn btn-success btn-sm">✓ Sudah Dikembalikan</button>
    </div>
</div>
@empty
<div class="card">
    <div class="empty-state">
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"><polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3"/></svg>
        <p>Tidak Ada Barang Dipinjam</p>
        <span>Semua barang inventaris sedang tersedia</span>
    </div>
</div>
@endforelse
@endsection
