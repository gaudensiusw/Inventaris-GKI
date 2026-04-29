@extends('layouts.master', ['title' => 'Laporan Inventaris'])

@section('content')
    <div class="page-header">
        <div>
            <h1>Laporan Inventaris</h1>
            <p>Analisis dan statistik inventaris GKI Delima</p>
        </div>
        <div style="display:flex;gap:8px;">
            <a href="{{ route('admin.report.export-pdf', ['month' => $month, 'year' => $year]) }}" class="btn btn-primary btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                    <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                    <polyline points="7 11 12 16 17 11" />
                    <line x1="12" y1="4" x2="12" y2="16" />
                </svg>
                Export PDF
            </a>
        </div>
    </div>

    <!-- Period Filter -->
    <form action="{{ route('admin.report.index') }}" method="GET" id="filterForm">
        <input type="hidden" name="tab" value="{{ $tab }}" id="tabInput">
        <div class="report-filter">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="#64748b" fill="none">
                <rect x="4" y="5" width="16" height="16" rx="2" />
                <line x1="16" y1="3" x2="16" y2="7" />
                <line x1="8" y1="3" x2="8" y2="7" />
                <line x1="4" y1="11" x2="20" y2="11" />
            </svg>
            <button type="submit" name="period" value="periodik" class="filter-btn {{ $period == 'periodik' ? 'active' : '' }}">Periodik</button>
            <button type="submit" name="period" value="bulanan" class="filter-btn {{ $period == 'bulanan' ? 'active' : '' }}">Bulanan</button>
            <button type="submit" name="period" value="tahunan" class="filter-btn {{ $period == 'tahunan' ? 'active' : '' }}">Tahunan</button>
            <select name="month" style="margin-left:8px;" onchange="document.getElementById('filterForm').submit()">
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->locale('id')->translatedFormat('F') }}
                    </option>
                @endfor
            </select>
            <select name="year" onchange="document.getElementById('filterForm').submit()">
                @for($y = 2024; $y <= 2026; $y++)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            <span style="margin-left:auto;color:#3b82f6;font-size:13px;font-weight:500;">
                {{ \Carbon\Carbon::create($year, $month)->locale('id')->translatedFormat('F Y') }}
            </span>
        </div>
    </form>

    <!-- Stat Cards -->
    <div class="stat-cards">
        <div class="stat-card">
            <div class="stat-card-info">
                <div class="stat-label">Total Jenis Barang</div>
                <div class="stat-value">{{ $totalTypes }}</div>
            </div>
            <div class="stat-card-icon blue">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                    <polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3" />
                </svg>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-info">
                <div class="stat-label">Total Quantity</div>
                <div class="stat-value">{{ $totalQuantity }}</div>
            </div>
            <div class="stat-card-icon purple">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                    <polyline points="3 17 9 11 13 15 21 7" />
                </svg>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-info">
                <div class="stat-label">Total Nilai Aset</div>
                <div class="stat-value">Rp {{ number_format($totalValue / 1000000, 1) }}jt</div>
            </div>
            <div class="stat-card-icon green">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                    <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                    <path d="M12 3v3m0 12v3" />
                </svg>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-info">
                <div class="stat-label">Perlu Perhatian</div>
                <div class="stat-value" style="color:#ef4444;">{{ $needsAttention }}</div>
            </div>
            <div class="stat-card-icon red">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                    <path d="M12 9v2m0 4v.01" />
                    <path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Report Tabs -->
    <div class="tabs" style="margin-bottom:20px;">
        <button class="tab {{ $tab == 'ringkasan' ? 'active' : '' }}" onclick="switchTab('ringkasan')">📋 Ringkasan</button>
        <button class="tab {{ $tab == 'kategori' ? 'active' : '' }}" onclick="switchTab('kategori')">📊 Per Kategori</button>
        <button class="tab {{ $tab == 'aktivitas' ? 'active' : '' }}" onclick="switchTab('aktivitas')">📈 Aktivitas</button>
        <button class="tab {{ $tab == 'keuangan' ? 'active' : '' }}" onclick="switchTab('keuangan')">💰 Keuangan</button>
    </div>

    <!-- Tab Content: Ringkasan -->
    <div id="tab-ringkasan" class="tab-content" style="{{ $tab != 'ringkasan' ? 'display:none;' : '' }}">
        <div class="charts-row">
            <div class="chart-card">
                <h3>Status Operasional</h3>
                <canvas id="statusChart" height="250"></canvas>
                <div class="chart-legend" style="margin-top:16px;">
                    @foreach($statusData as $label => $count)
                        <div class="legend-item">
                            <div class="legend-dot"
                                style="background:{{ $loop->index == 0 ? '#22c55e' : ($loop->index == 1 ? '#3b82f6' : ($loop->index == 2 ? '#f59e0b' : '#ef4444')) }};">
                            </div>
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
                            <div class="legend-dot" style="background:{{ $loop->index == 0 ? '#22c55e' : ($loop->index == 1 ? '#f59e0b' : '#ef4444') }};"></div>
                            {{ $label }} <strong>{{ $count }} unit</strong>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="chart-card">
            <h3>Tren 6 Bulan Terakhir</h3>
            <canvas id="trendChart" height="120"></canvas>
            <div class="chart-legend" style="justify-content:center;margin-top:12px;">
                <div class="legend-item">
                    <div class="legend-dot" style="background:#3b82f6;"></div> Peminjaman
                </div>
                <div class="legend-item">
                    <div class="legend-dot" style="background:#22c55e;"></div> Perbaikan
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Content: Per Kategori -->
    <div id="tab-kategori" class="tab-content" style="{{ $tab != 'kategori' ? 'display:none;' : '' }}">
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kategori</th>
                        <th>Jumlah Item</th>
                        <th>Total Qty</th>
                        <th>Total Nilai</th>
                        <th>Kondisi Baik</th>
                        <th>Kondisi Rusak</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categoryStats as $i => $cat)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td style="font-weight:600;">{{ $cat['name'] }}</td>
                        <td><span class="badge badge-blue">{{ $cat['items_count'] }}</span></td>
                        <td>{{ $cat['total_qty'] }}</td>
                        <td style="font-weight:600;">Rp {{ number_format($cat['total_value']) }}</td>
                        <td><span class="badge badge-green badge-dot">{{ $cat['baik'] }}</span></td>
                        <td><span class="badge badge-red badge-dot">{{ $cat['rusak'] }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center;color:var(--text-muted);padding:40px;">Belum ada data kategori</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tab Content: Aktivitas -->
    <div id="tab-aktivitas" class="tab-content" style="{{ $tab != 'aktivitas' ? 'display:none;' : '' }}">
        <div class="charts-row">
            <div class="card">
                <div class="card-title">Peminjaman Terbaru</div>
                <ul class="activity-list">
                    @forelse($recentOrders as $order)
                    <li class="activity-item">
                        <div class="activity-icon" style="background:#eff6ff;color:#3b82f6;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                <path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/><path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/>
                                <path d="M5 17h-2v-4m0 -6h14l3 6v4h-2m-4 0h-6"/>
                            </svg>
                        </div>
                        <div class="activity-info">
                            <div class="activity-title">{{ $order->item->name ?? 'Item dihapus' }}</div>
                            <div class="activity-desc">{{ $order->user->name ?? '-' }} • {{ $order->created_at->diffForHumans() }}</div>
                        </div>
                        <span class="badge badge-{{ $order->status == 'approved' ? 'green' : ($order->status == 'pending' ? 'orange' : 'gray') }}">
                            {{ ucfirst($order->status ?? 'pending') }}
                        </span>
                    </li>
                    @empty
                    <li style="padding:20px;text-align:center;color:var(--text-muted);">Belum ada peminjaman</li>
                    @endforelse
                </ul>
            </div>
            <div class="card">
                <div class="card-title">Perbaikan Terbaru</div>
                <ul class="activity-list">
                    @forelse($recentRepairs as $repair)
                    <li class="activity-item">
                        <div class="activity-icon" style="background:#fffbeb;color:#f59e0b;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                <path d="M7 10h3v-3l-3.5 -3.5a6 6 0 0 1 8 8l6 6a2 2 0 0 1 -3 3l-6 -6a6 6 0 0 1 -8 -8l3.5 3.5"/>
                            </svg>
                        </div>
                        <div class="activity-info">
                            <div class="activity-title">{{ $repair->item->name ?? 'Item dihapus' }}</div>
                            <div class="activity-desc">{{ $repair->description ?? '-' }} • {{ $repair->created_at->diffForHumans() }}</div>
                        </div>
                        <span class="badge badge-{{ ($repair->status ?? 'ongoing') == 'completed' ? 'green' : 'orange' }}">
                            {{ ucfirst($repair->status ?? 'ongoing') }}
                        </span>
                    </li>
                    @empty
                    <li style="padding:20px;text-align:center;color:var(--text-muted);">Belum ada perbaikan</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <!-- Tab Content: Keuangan -->
    <div id="tab-keuangan" class="tab-content" style="{{ $tab != 'keuangan' ? 'display:none;' : '' }}">
        <div class="charts-row">
            <div class="chart-card">
                <h3>Distribusi Nilai Aset per Kategori</h3>
                <canvas id="financeChart" height="300"></canvas>
            </div>
            <div class="card" style="margin-bottom:0;">
                <div class="card-title">Rincian Nilai Aset</div>
                <ul class="activity-list">
                    @foreach($financeByCategory as $fc)
                    <li class="activity-item">
                        <div class="activity-icon" style="background:#eff6ff;color:#3b82f6;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2"/>
                                <path d="M12 3v3m0 12v3"/>
                            </svg>
                        </div>
                        <div class="activity-info">
                            <div class="activity-title">{{ $fc['name'] }}</div>
                            <div class="activity-desc">{{ $fc['item_count'] }} item</div>
                        </div>
                        <span style="font-weight:700;font-size:13px;color:var(--text-primary);">Rp {{ number_format($fc['total_value']) }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
    function switchTab(tab) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
        // Show selected
        document.getElementById('tab-' + tab).style.display = 'block';
        // Update active tab button
        document.querySelectorAll('.tab').forEach(el => el.classList.remove('active'));
        event.target.classList.add('active');
        // Update hidden input for form
        document.getElementById('tabInput').value = tab;
    }

    // Charts - only init if canvas exists
    const statusCanvas = document.getElementById('statusChart');
    if (statusCanvas) {
        new Chart(statusCanvas.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode(array_keys($statusData)) !!},
                datasets: [{ data: {!! json_encode(array_values($statusData)) !!}, backgroundColor: ['#22c55e', '#3b82f6', '#f59e0b', '#ef4444'], borderWidth: 0 }]
            },
            options: { responsive: true, plugins: { legend: { display: false } } }
        });
    }

    const conditionCanvas = document.getElementById('conditionChart');
    if (conditionCanvas) {
        new Chart(conditionCanvas.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode(array_keys($conditionData)) !!},
                datasets: [{ data: {!! json_encode(array_values($conditionData)) !!}, backgroundColor: ['#22c55e', '#f59e0b', '#ef4444'], borderWidth: 0 }]
            },
            options: { responsive: true, plugins: { legend: { display: false } } }
        });
    }

    const trendCanvas = document.getElementById('trendChart');
    if (trendCanvas) {
        new Chart(trendCanvas.getContext('2d'), {
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
    }

    // Finance chart
    const financeCanvas = document.getElementById('financeChart');
    if (financeCanvas) {
        const financeData = {!! json_encode($financeByCategory->values()) !!};
        const colors = ['#3b82f6','#22c55e','#f59e0b','#ef4444','#8b5cf6','#ec4899','#14b8a6','#64748b'];
        new Chart(financeCanvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: financeData.map(d => d.name),
                datasets: [{
                    label: 'Nilai Aset (Rp)',
                    data: financeData.map(d => d.total_value),
                    backgroundColor: financeData.map((_, i) => colors[i % colors.length]),
                    borderRadius: 6,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { callback: v => 'Rp ' + (v/1000000).toFixed(1) + 'jt' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }
</script>
@endpush