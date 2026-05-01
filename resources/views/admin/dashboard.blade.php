@extends('layouts.master', ['title' => 'Dashboard'])

@section('content')
<div class="page-header">
    <div>
        <h1>Dashboard</h1>
        <p>Selamat datang kembali, {{ Auth::user()->name }}</p>
    </div>
    <a href="{{ route('admin.qr-scanner') }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="4" y="4" width="6" height="6"/><rect x="14" y="4" width="6" height="6"/><rect x="4" y="14" width="6" height="6"/><rect x="14" y="14" width="2" height="2"/></svg>
        Scan QR Code
    </a>
</div>

<!-- Stat Cards -->
<div class="stat-cards">
    <div class="stat-card">
        <div class="stat-card-info">
            <div class="stat-label">Total Barang</div>
            <div class="stat-value">{{ $itemsCount }}</div>
        </div>
        <div class="stat-card-icon blue">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3"/></svg>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-info">
            <div class="stat-label">Total Nilai</div>
            <div class="stat-value">Rp {{ number_format($totalValue / 1000000, 1) }}M</div>
            <div class="stat-sub">Estimasi nilai aset</div>
        </div>
        <div class="stat-card-icon green">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2"/><path d="M12 3v3m0 12v3"/></svg>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-info">
            <div class="stat-label">Kondisi Baik</div>
            <div class="stat-value">{{ $goodConditionCount }}</div>
            <div class="stat-sub">{{ $itemsCount > 0 ? round(($goodConditionCount/$itemsCount)*100) : 0 }}% dari total</div>
        </div>
        <div class="stat-card-icon green">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10"/></svg>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-info">
            <div class="stat-label">Perlu Perhatian</div>
            <div class="stat-value">{{ $needsAttentionCount }}</div>
            <div class="stat-sub">Perhatian diperlukan</div>
        </div>
        <div class="stat-card-icon orange">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v2m0 4v.01"/><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75"/></svg>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="charts-row">
    <div class="chart-card">
        <h3>Distribusi Kategori</h3>
        <canvas id="categoryChart" height="220"></canvas>
    </div>
    <div class="chart-card">
        <h3>Barang per Lokasi</h3>
        <canvas id="locationChart" height="220"></canvas>
    </div>
</div>

<!-- Aktivitas Terbaru -->
<div class="card">
    <div class="card-title">Aktivitas Terbaru</div>
    <ul class="activity-list">
        @forelse($latestItems as $item)
        <li class="activity-item">
            <div class="activity-icon" style="background:#eff6ff;color:#3b82f6;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3"/></svg>
            </div>
            <div class="activity-info">
                <div class="activity-title">{{ $item->name }}</div>
                <div class="activity-desc">Diperbarui oleh Admin · {{ $item->updated_at?->format('Y-m-d') ?? '-' }}</div>
            </div>
            <svg class="activity-arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6l-6 6"/></svg>
        </li>
        @empty
        <li class="activity-item">
            <div class="activity-info">
                <div class="activity-desc">Belum ada aktivitas</div>
            </div>
        </li>
        @endforelse
    </ul>
    <div style="padding-top:8px;">
        <a href="{{ route('admin.item.index') }}" style="color:#3b82f6;font-size:13px;font-weight:500;text-decoration:none;">Lihat Semua Inventaris →</a>
    </div>
</div>

<!-- Summary Cards -->
<div class="summary-cards">
    <div class="summary-card blue-gradient">
        <div class="summary-label">Total</div>
        <div class="summary-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2"/><rect x="9" y="3" width="6" height="4" rx="2"/></svg>
        </div>
        <div class="summary-value">{{ $categoriesCount }}</div>
        <div class="summary-desc">Jenis Barang Terdaftar</div>
    </div>
    <div class="summary-card green-gradient">
        <div class="summary-label">Kategori</div>
        <div class="summary-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="3 17 9 11 13 15 21 7"/><polyline points="14 7 21 7 21 14"/></svg>
        </div>
        <div class="summary-value">{{ $categoriesCount }}</div>
        <div class="summary-desc">Kategori Berbeda</div>
    </div>
    <div class="summary-card purple-gradient">
        <div class="summary-label">Status</div>
        <div class="summary-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="3 17 9 11 13 15 21 7"/></svg>
        </div>
        <div class="summary-value">{{ $goodPercentage }}%</div>
        <div class="summary-desc">Kondisi Baik</div>
    </div>
</div>
@endsection

@push('js')
<script>
// Distribusi Kategori - Pie Chart
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
new Chart(categoryCtx, {
    type: 'pie',
    data: {
        labels: {!! json_encode($categoryLabels) !!},
        datasets: [{
            data: {!! json_encode($categoryTotals) !!},
            backgroundColor: ['#3b82f6','#22c55e','#f59e0b','#ef4444','#8b5cf6','#ec4899','#14b8a6','#64748b'],
            borderWidth: 0,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom', labels: { padding: 16, font: { size: 11, family: 'Inter' } } }
        }
    }
});

// Barang per Lokasi - Bar Chart
const locationCtx = document.getElementById('locationChart').getContext('2d');
new Chart(locationCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($locationLabels) !!},
        datasets: [{
            label: 'Jumlah Barang',
            data: {!! json_encode($locationTotals) !!},
            backgroundColor: '#3b82f6',
            borderRadius: 4,
            barThickness: 28,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { font: { size: 11, family: 'Inter' } } },
            x: { grid: { display: false }, ticks: { font: { size: 10, family: 'Inter' }, maxRotation: 45 } }
        }
    }
});
</script>
@endpush
