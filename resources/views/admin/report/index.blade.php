@extends('layouts.master', ['title' => 'Laporan Inventaris'])

@section('content')
<div class="page-header">
    <div>
        <h1>Laporan Inventaris</h1>
        <p>Analisis dan statistik inventaris GKI Delima</p>
    </div>
    <div style="display:flex;gap:8px;">
        <button class="btn btn-outline btn-sm" onclick="window.print()">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"/><polyline points="7 11 12 16 17 11"/><line x1="12" y1="4" x2="12" y2="16"/></svg>
            Export PDF
        </button>
        <a href="{{ route('admin.dashboard') }}" class="btn-back">← Kembali</a>
    </div>
</div>

<!-- Period Filter -->
<div class="report-filter">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="#64748b" fill="none"><rect x="4" y="5" width="16" height="16" rx="2"/><line x1="16" y1="3" x2="16" y2="7"/><line x1="8" y1="3" x2="8" y2="7"/><line x1="4" y1="11" x2="20" y2="11"/></svg>
    <button class="filter-btn {{ $period == 'periodik' ? 'active' : '' }}">Periodik</button>
    <button class="filter-btn {{ $period == 'bulanan' ? 'active' : '' }}">Bulanan</button>
    <button class="filter-btn {{ $period == 'tahunan' ? 'active' : '' }}">Tahunan</button>
    <select style="margin-left:8px;">
        @for($m = 1; $m <= 12; $m++)
        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->locale('id')->translatedFormat('F') }}</option>
        @endfor
    </select>
    <select>
        @for($y = 2024; $y <= 2026; $y++)
        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
        @endfor
    </select>
    <span style="margin-left:auto;color:#3b82f6;font-size:13px;font-weight:500;">{{ \Carbon\Carbon::create($year, $month)->locale('id')->translatedFormat('F Y') }}</span>
</div>

<!-- Stat Cards -->
<div class="stat-cards">
    <div class="stat-card">
        <div class="stat-card-info">
            <div class="stat-label">Total Jenis Barang</div>
            <div class="stat-value">{{ $totalTypes }}</div>
        </div>
        <div class="stat-card-icon blue">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3"/></svg>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-info">
            <div class="stat-label">Total Quantity</div>
            <div class="stat-value">{{ $totalQuantity }}</div>
        </div>
        <div class="stat-card-icon purple">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><polyline points="3 17 9 11 13 15 21 7"/></svg>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-info">
            <div class="stat-label">Total Nilai Aset</div>
            <div class="stat-value">Rp {{ number_format($totalValue / 1000000, 1) }}jt</div>
        </div>
        <div class="stat-card-icon green">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2"/><path d="M12 3v3m0 12v3"/></svg>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-info">
            <div class="stat-label">Perlu Perhatian</div>
            <div class="stat-value" style="color:#ef4444;">{{ $needsAttention }}</div>
        </div>
        <div class="stat-card-icon red">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M12 9v2m0 4v.01"/><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75"/></svg>
        </div>
    </div>
</div>

<!-- Report tabs -->
<div class="tabs" style="margin-bottom:20px;">
    <button class="tab active">📋 Ringkasan</button>
    <button class="tab">📊 Per Kategori</button>
    <button class="tab">📈 Aktivitas</button>
    <button class="tab">💰 Keuangan</button>
</div>

<!-- Charts Row -->
<div class="charts-row">
    <div class="chart-card">
        <h3>Status Operasional</h3>
        <canvas id="statusChart" height="250"></canvas>
        <div class="chart-legend" style="margin-top:16px;">
            @foreach($statusData as $label => $count)
            <div class="legend-item">
                <div class="legend-dot" style="background:{{ $loop->index == 0 ? '#22c55e' : ($loop->index == 1 ? '#3b82f6' : ($loop->index == 2 ? '#f59e0b' : '#ef4444')) }};"></div>
                {{ $label }} <strong>{{ $count }} unit</strong>
            </div>
            @endforeach
        </div>
    </div>
    <div class="chart-card">
        <h3>Kondisi Fisik</h3>
        <canvas id="conditionChart" height="250"></canvas>
        <div class="chart-legend" style="margin-top:16px;">
            @foreach($conditionData as $label => $count)
            <div class="legend-item">
                <div class="legend-dot" style="background:{{ $loop->index == 0 ? '#22c55e' : '#f59e0b' }};"></div>
                {{ $label }} <strong>{{ $count }} unit</strong>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Trend Chart -->
<div class="chart-card">
    <h3>Tren 6 Bulan Terakhir</h3>
    <canvas id="trendChart" height="120"></canvas>
    <div class="chart-legend" style="justify-content:center;margin-top:12px;">
        <div class="legend-item"><div class="legend-dot" style="background:#3b82f6;"></div> Peminjaman</div>
        <div class="legend-item"><div class="legend-dot" style="background:#22c55e;"></div> Perbaikan</div>
    </div>
</div>
@endsection

@push('js')
<script>
// Status Operasional Pie
new Chart(document.getElementById('statusChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode(array_keys($statusData)) !!},
        datasets: [{ data: {!! json_encode(array_values($statusData)) !!}, backgroundColor: ['#22c55e','#3b82f6','#f59e0b','#ef4444'], borderWidth: 0 }]
    },
    options: { responsive: true, plugins: { legend: { display: false } } }
});

// Kondisi Fisik Pie
new Chart(document.getElementById('conditionChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode(array_keys($conditionData)) !!},
        datasets: [{ data: {!! json_encode(array_values($conditionData)) !!}, backgroundColor: ['#22c55e','#f59e0b','#ef4444'], borderWidth: 0 }]
    },
    options: { responsive: true, plugins: { legend: { display: false } } }
});

// Trend Line
new Chart(document.getElementById('trendChart').getContext('2d'), {
    type: 'line',
    data: {
        labels: {!! json_encode($trendLabels) !!},
        datasets: [
            { label: 'Peminjaman', data: {!! json_encode($trendPeminjaman) !!}, borderColor: '#3b82f6', backgroundColor: 'rgba(59,130,246,0.1)', fill: true, tension: 0.4, borderWidth: 2 },
            { label: 'Perbaikan', data: {!! json_encode($trendPerbaikan) !!}, borderColor: '#22c55e', backgroundColor: 'rgba(34,197,94,0.05)', fill: true, tension: 0.4, borderWidth: 2 }
        ]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: '#f1f5f9' } }, x: { grid: { display: false } } } }
});
</script>
@endpush
