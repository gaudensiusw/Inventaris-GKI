@extends('layouts.master', ['title' => 'Status Khusus'])

@section('content')
<div class="page-header">
    <div>
        <h1>Status Khusus</h1>
        <p>Pantau barang dengan status khusus</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn-back">← Kembali</a>
</div>

<!-- Stat Cards -->
<div class="stat-cards" style="grid-template-columns:repeat(3,1fr);">
    <div class="stat-card">
        <div class="stat-card-info">
            <div class="stat-label">Barang Hilang</div>
            <div class="stat-value" style="color:#ef4444;">{{ $hilangCount }}</div>
        </div>
        <div class="stat-card-icon red">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><circle cx="12" cy="12" r="9"/><line x1="9" y1="9" x2="15" y2="15"/><line x1="15" y1="9" x2="9" y2="15"/></svg>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-info">
            <div class="stat-label">Tidak Digunakan</div>
            <div class="stat-value">{{ $tidakDigunakanCount }}</div>
        </div>
        <div class="stat-card-icon gray" style="background:#f1f5f9;color:#64748b;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><circle cx="12" cy="12" r="9"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-info">
            <div class="stat-label">Dalam Pengadaan</div>
            <div class="stat-value" style="color:#3b82f6;">{{ $pengadaanCount }}</div>
        </div>
        <div class="stat-card-icon blue">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M12 5l0 14"/><path d="M5 12l14 0"/></svg>
        </div>
    </div>
</div>

<!-- Tabs -->
<div class="tabs">
    <a href="{{ route('admin.special-status.index', ['tab' => 'hilang']) }}" class="tab {{ $tab == 'hilang' ? 'active' : '' }}">⚠ Hilang ({{ $hilangCount }})</a>
    <a href="{{ route('admin.special-status.index', ['tab' => 'tidak_digunakan']) }}" class="tab {{ $tab == 'tidak_digunakan' ? 'active' : '' }}">⊘ Tidak Digunakan ({{ $tidakDigunakanCount }})</a>
    <a href="{{ route('admin.special-status.index', ['tab' => 'pengadaan']) }}" class="tab {{ $tab == 'pengadaan' ? 'active' : '' }}">⊕ Pengadaan ({{ $pengadaanCount }})</a>
</div>

<!-- Search -->
<div class="card" style="padding:12px 16px;margin-bottom:16px;">
    <div class="table-search" style="max-width:100%;">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" placeholder="Cari barang..." style="width:100%;">
    </div>
</div>

<!-- Items -->
@forelse($items as $item)
<div class="detail-card">
    <div class="detail-card-header">
        <div>
            <div class="detail-card-title">
                {{ $item->name }}
                <span class="badge badge-gray">{{ $item->inv_code }}</span>
                @if($item->status == 'Hilang')
                <span class="badge badge-red badge-dot">Hilang</span>
                @elseif($item->status == 'Tidak digunakan')
                <span class="badge badge-gray badge-dot">Tidak Digunakan</span>
                @else
                <span class="badge badge-blue badge-dot">Dalam Pengadaan</span>
                @endif
            </div>
            <div class="detail-card-subtitle">{{ $item->category->name ?? '-' }}</div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;">
        <div class="detail-item">
            <div class="detail-label" style="color:#ef4444;">Kategori</div>
            <div class="detail-value" style="color:#ef4444;">{{ $item->category->name ?? '-' }}</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Lokasi Terakhir</div>
            <div class="detail-value">{{ $item->room->name ?? '-' }}</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Jumlah</div>
            <div class="detail-value">{{ $item->quantity }} unit</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Tanggal Beli</div>
            <div class="detail-value">{{ $item->purchase_date ? $item->purchase_date->format('d F Y') : '-' }}</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Harga</div>
            <div class="detail-value">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Update Terakhir</div>
            <div class="detail-value">{{ $item->updated_at->format('d M Y') }}</div>
        </div>
    </div>

    @if($item->description)
    <div style="margin-top:12px;">
        <div class="detail-item">
            <div class="detail-label" style="color:#ef4444;">Deskripsi</div>
            <div class="detail-value">{{ $item->description }}</div>
        </div>
    </div>
    @endif
</div>
@empty
<div class="card">
    <div class="empty-state">
        <p>Tidak Ada Barang</p>
        <span>Tidak ada barang dengan status ini</span>
    </div>
</div>
@endforelse
@endsection
