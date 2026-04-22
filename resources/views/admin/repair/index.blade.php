@extends('layouts.master', ['title' => 'Barang Sedang Diperbaiki'])

@section('content')
<div class="page-header">
    <div>
        <h1>Barang Sedang Diperbaiki</h1>
        <p>Pantau status perbaikan barang inventaris</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn-back">← Kembali</a>
</div>

<!-- Stat Cards -->
<div class="stat-cards" style="grid-template-columns:repeat(3,1fr);">
    <div class="stat-card">
        <div class="stat-card-info">
            <div class="stat-label">Total Dalam Perbaikan</div>
            <div class="stat-value">{{ $totalRepair }}</div>
        </div>
        <div class="stat-card-icon purple">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M7 10h3v-3l-3.5 -3.5a6 6 0 0 1 8 8l6 6a2 2 0 0 1 -3 3l-6 -6a6 6 0 0 1 -8 -8l3.5 3.5"/></svg>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-info">
            <div class="stat-label">Akan Selesai Segera</div>
            <div class="stat-value">{{ $completingSoon }}</div>
        </div>
        <div class="stat-card-icon orange">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><circle cx="12" cy="12" r="9"/><polyline points="12 7 12 12 15 15"/></svg>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-info">
            <div class="stat-label">Melebihi Estimasi</div>
            <div class="stat-value" style="color:#ef4444;">{{ $overEstimate }}</div>
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
        <input type="text" placeholder="Cari barang atau tempat perbaikan..." style="width:100%;">
    </div>
</div>

<!-- Repair Items -->
@forelse($repairItems as $item)
@php $repair = $item->activeRepair; @endphp
<div class="detail-card">
    <div class="detail-card-header">
        <div>
            <div class="detail-card-title">
                {{ $item->name }}
                <span class="badge badge-gray">{{ $item->inv_code }}</span>
                @if($repair && $repair->estimated_completion && $repair->estimated_completion < now())
                <span class="badge badge-red">Melebihi estimasi {{ now()->diffInDays($repair->estimated_completion) }} hari</span>
                @endif
            </div>
            <div class="detail-card-subtitle">{{ $item->category->name ?? '-' }}</div>
        </div>
    </div>

    <div class="detail-grid">
        <div class="detail-item">
            <div class="detail-label">Tempat Perbaikan</div>
            <div class="detail-value">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="#3b82f6" fill="none"><circle cx="12" cy="12" r="9"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ $repair->repair_location ?? 'Belum ditentukan' }}
            </div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Tanggal Masuk Perbaikan</div>
            <div class="detail-value">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="#3b82f6" fill="none"><rect x="4" y="5" width="16" height="16" rx="2"/></svg>
                {{ $repair ? $repair->repair_date->format('d F Y') : '-' }}
            </div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Estimasi Selesai</div>
            <div class="detail-value" style="color:#22c55e;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="#22c55e" fill="none"><path d="M5 12l5 5l10 -10"/></svg>
                {{ $repair && $repair->estimated_completion ? $repair->estimated_completion->format('d F Y') : '-' }}
            </div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Lokasi Aset</div>
            <div class="detail-value">{{ $item->room->name ?? '-' }}</div>
        </div>
    </div>

    @if($repair && $repair->notes)
    <div style="margin-top:12px;">
        <div class="detail-item">
            <div class="detail-label">Catatan Perbaikan</div>
            <div class="detail-value">{{ $repair->notes }}</div>
        </div>
    </div>
    @endif

    <div style="text-align:left;margin-top:16px;">
        <button class="btn btn-success btn-sm">✓ Tanda Selesai</button>
    </div>
</div>
@empty
<div class="card">
    <div class="empty-state">
        <p>Tidak Ada Barang Dalam Perbaikan</p>
        <span>Semua barang inventaris dalam kondisi baik</span>
    </div>
</div>
@endforelse
@endsection
