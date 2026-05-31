@extends('layouts.master')

@section('content')
<div class="flex flex-col gap-8 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Laporan Inventaris</h1>
            <p class="text-slate-500 text-sm mt-1">Analisis dan statistik inventaris GKI Delima</p>
        </div>
        <div class="flex gap-3 w-full md:w-auto">
            <a href="{{ request()->fullUrlWithQuery(['export_pdf' => 1]) }}" class="flex-1 md:flex-none px-5 py-3 bg-blue-600 text-white rounded-2xl text-xs font-black uppercase shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all flex items-center justify-center gap-2">
                <i data-lucide="file-text" class="w-4 h-4"></i>
                Export PDF
            </a>
            <a href="{{ route('report.export-csv', request()->query()) }}" class="flex-1 md:flex-none px-5 py-3 bg-emerald-600 text-white rounded-2xl text-xs font-black uppercase shadow-lg shadow-emerald-100 hover:bg-emerald-700 transition-all flex items-center justify-center gap-2">
                <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                Export CSV
            </a>
            <a href="{{ route('dashboard') }}" class="flex-1 md:flex-none px-5 py-3 bg-white border border-slate-200 text-slate-700 rounded-2xl text-xs font-black uppercase shadow-sm hover:bg-slate-50 transition-all flex items-center justify-center gap-2">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Kembali
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card-premium p-4 border-none flex flex-wrap items-center justify-between gap-6">
        <div class="flex flex-wrap items-center gap-4">
            <div class="flex items-center gap-2 bg-slate-100 p-1 rounded-xl">
                <button onclick="changePeriod('monthly')" class="px-4 py-2 rounded-lg text-[10px] font-black uppercase transition-all {{ $period == 'monthly' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">Bulanan</button>
                <button onclick="changePeriod('yearly')" class="px-4 py-2 rounded-lg text-[10px] font-black uppercase transition-all {{ $period == 'yearly' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">Tahunan</button>
            </div>
            @if($period == 'monthly')
            <select onchange="updateFilter('month', this.value)" class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:outline-none focus:border-blue-400">
                @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ Carbon\Carbon::create()->month($m)->format('F') }}</option>
                @endforeach
            </select>
            @endif
            <select onchange="updateFilter('year', this.value)" class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:outline-none focus:border-blue-400">
                @foreach(range(date('Y') - 5, date('Y') + 1) as $y)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>

            <!-- Room Filter -->
            <select onchange="updateFilter('room_id', this.value)" class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:outline-none focus:border-blue-400">
                <option value="">Semua Ruangan</option>
                @foreach($rooms as $r)
                    <option value="{{ $r->id }}" {{ $roomId == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                @endforeach
            </select>

            <!-- Category Filter -->
            <select onchange="updateFilter('category_id', this.value)" class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:outline-none focus:border-blue-400">
                <option value="">Semua Kategori</option>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}" {{ $categoryId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="hidden lg:block">
            <span class="px-4 py-2 bg-blue-50 text-blue-600 rounded-full text-[10px] font-black uppercase tracking-widest">
                {{ $period == 'monthly' ? Carbon\Carbon::create()->month($month)->format('F') . ' ' . $year : $year }}
            </span>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card-premium p-6 border-none flex items-center justify-between group">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Jenis Barang</p>
                <h3 class="text-3xl font-black text-slate-800">{{ number_format($totalJenisBarang) }}</h3>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center">
                <i data-lucide="package" class="w-6 h-6"></i>
            </div>
        </div>
        <div class="card-premium p-6 border-none flex items-center justify-between group">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Quantity</p>
                <h3 class="text-3xl font-black text-emerald-600">{{ number_format($totalQuantity) }}</h3>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center">
                <i data-lucide="trending-up" class="w-6 h-6"></i>
            </div>
        </div>
        <div class="card-premium p-6 border-none flex items-center justify-between group">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Perlu Perhatian</p>
                <h3 class="text-3xl font-black text-rose-500">{{ number_format($perluPerhatian) }}</h3>
            </div>
            <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center">
                <i data-lucide="alert-triangle" class="w-6 h-6"></i>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-2xl w-fit">
        <button onclick="switchTab('ringkasan')" id="tab-ringkasan" class="tab-btn active px-6 py-3 rounded-xl text-xs font-black uppercase flex items-center gap-2 transition-all">
            <i data-lucide="layout-grid" class="w-4 h-4"></i> Ringkasan
        </button>
        <button onclick="switchTab('per-kategori')" id="tab-per-kategori" class="tab-btn px-6 py-3 rounded-xl text-xs font-black uppercase flex items-center gap-2 transition-all text-slate-500 hover:text-slate-700">
            <i data-lucide="list" class="w-4 h-4"></i> Per Kategori
        </button>
        <button onclick="switchTab('aktivitas')" id="tab-aktivitas" class="tab-btn px-6 py-3 rounded-xl text-xs font-black uppercase flex items-center gap-2 transition-all text-slate-500 hover:text-slate-700">
            <i data-lucide="activity" class="w-4 h-4"></i> Aktivitas
        </button>
    </div>

    <!-- Tab Contents -->
    <div id="content-ringkasan" class="tab-content flex flex-col gap-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="card-premium p-8 border-none flex flex-col gap-8">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Status Operasional</h3>
                <div class="flex flex-col md:flex-row items-center gap-8">
                    <div class="relative w-48 h-48 mx-auto md:mx-0">
                        <canvas id="operasionalChart"></canvas>
                    </div>
                    <div class="flex-1 flex flex-col gap-3">
                        @foreach($statusOperasional as $label => $val)
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full status-dot-{{ Str::slug($label) }}"></span>
                                <span class="text-xs font-bold text-slate-600">{{ $label }}</span>
                            </div>
                            <span class="text-xs font-black text-slate-800">{{ number_format($val) }} unit</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="card-premium p-8 border-none flex flex-col gap-8">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Kondisi Fisik</h3>
                <div class="flex flex-col md:flex-row items-center gap-8">
                    <div class="relative w-48 h-48 mx-auto md:mx-0">
                        <canvas id="kondisiChart"></canvas>
                    </div>
                    <div class="flex-1 flex flex-col gap-3">
                        @foreach($kondisiFisik as $label => $val)
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full kondisi-dot-{{ Str::slug($label) }}"></span>
                                <span class="text-xs font-bold text-slate-600">{{ $label }}</span>
                            </div>
                            <span class="text-xs font-black text-slate-800">{{ number_format($val) }} unit</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="card-premium p-8 border-none flex flex-col gap-8">
            <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Tren 6 Bulan Terakhir</h3>
            <div class="h-80 w-full">
                <canvas id="trendChart"></canvas>
            </div>
        </div>
    </div>

    <div id="content-per-kategori" class="tab-content hidden flex flex-col gap-8">
        <div class="card-premium p-8 border-none flex flex-col gap-8">
            <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Breakdown Per Kategori</h3>
            <div class="h-80 w-full">
                <canvas id="categoryBarChart"></canvas>
            </div>
        </div>
        <div class="card-premium p-0 border-none overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-50">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Detail Per Kategori</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kategori</th>
                            <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Jenis Barang</th>
                            <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Total Unit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($categoryBreakdown as $cat)
                        <tr>
                            <td class="px-8 py-4 text-sm font-bold text-slate-700">{{ $cat['name'] }}</td>
                            <td class="px-8 py-4 text-sm font-semibold text-slate-600 text-center">{{ $cat['items_count'] }}</td>
                            <td class="px-8 py-4 text-sm font-semibold text-slate-600 text-center">{{ number_format($cat['total_units']) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-slate-50/50 font-black">
                        <tr>
                            <td class="px-8 py-4 text-xs uppercase text-slate-800">TOTAL</td>
                            <td class="px-8 py-4 text-sm text-slate-800 text-center">{{ $categoryBreakdown->sum('items_count') }}</td>
                            <td class="px-8 py-4 text-sm text-slate-800 text-center">{{ number_format($categoryBreakdown->sum('total_units')) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div id="content-aktivitas" class="tab-content hidden flex flex-col gap-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Peminjaman -->
            <div class="card-premium p-8 border-none flex flex-col gap-6">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Aktivitas Peminjaman</h3>
                <div class="flex flex-col gap-4">
                    <div class="flex items-center justify-between p-6 bg-blue-50/50 border border-blue-100/50 rounded-2xl group hover:bg-blue-600 transition-all duration-300">
                        <span class="text-xs font-bold text-blue-600 group-hover:text-white">Total Peminjaman</span>
                        <span class="text-2xl font-black text-blue-800 group-hover:text-white">{{ $borrowingStats['total'] }}</span>
                    </div>
                    <div class="flex items-center justify-between p-6 bg-amber-50/50 border border-amber-100/50 rounded-2xl group hover:bg-amber-500 transition-all duration-300">
                        <span class="text-xs font-bold text-amber-600 group-hover:text-white">Sedang Dipinjam</span>
                        <span class="text-2xl font-black text-amber-800 group-hover:text-white">{{ $borrowingStats['current'] }}</span>
                    </div>
                    <div class="flex items-center justify-between p-6 bg-emerald-50/50 border border-emerald-100/50 rounded-2xl group hover:bg-emerald-500 transition-all duration-300">
                        <span class="text-xs font-bold text-emerald-600 group-hover:text-white">Sudah Dikembalikan</span>
                        <span class="text-2xl font-black text-emerald-800 group-hover:text-white">{{ $borrowingStats['returned'] }}</span>
                    </div>
                </div>
            </div>
            <!-- Perbaikan -->
            <div class="card-premium p-8 border-none flex flex-col gap-6">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Aktivitas Perbaikan</h3>
                <div class="flex flex-col gap-4">
                    <div class="flex items-center justify-between p-6 bg-purple-50/50 border border-purple-100/50 rounded-2xl group hover:bg-purple-600 transition-all duration-300">
                        <span class="text-xs font-bold text-purple-600 group-hover:text-white">Total Perbaikan</span>
                        <span class="text-2xl font-black text-purple-800 group-hover:text-white">{{ $repairStats['total'] }}</span>
                    </div>
                    <div class="flex items-center justify-between p-6 bg-orange-50/50 border border-orange-100/50 rounded-2xl group hover:bg-orange-500 transition-all duration-300">
                        <span class="text-xs font-bold text-orange-600 group-hover:text-white">Sedang Diperbaiki</span>
                        <span class="text-2xl font-black text-orange-800 group-hover:text-white">{{ $repairStats['current'] }}</span>
                    </div>
                    <div class="flex items-center justify-between p-6 bg-emerald-50/50 border border-emerald-100/50 rounded-2xl group hover:bg-emerald-500 transition-all duration-300">
                        <span class="text-xs font-bold text-emerald-600 group-hover:text-white">Sudah Selesai</span>
                        <span class="text-2xl font-black text-emerald-800 group-hover:text-white">{{ $repairStats['finished'] }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-premium p-8 border-none flex flex-col gap-8">
            <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Ringkasan Status</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($detailedStatus as $label => $val)
                <div class="p-6 bg-slate-50 border border-slate-100 rounded-2xl">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">{{ $label }}</p>
                    <h4 class="text-2xl font-black text-{{ Str::slug($label) == 'hilang' ? 'rose-500' : 'slate-800' }}">{{ number_format($val) }}</h4>
                </div>
                @endforeach
            </div>
        </div>
    </div>

<style>
    .status-dot-tersedia { background-color: #10b981; }
    .status-dot-dipinjam { background-color: #3b82f6; }
    .status-dot-sedang-diperbaiki { background-color: #f59e0b; }
    .status-dot-hilang { background-color: #ef4444; }

    .kondisi-dot-baik { background-color: #10b981; }
    .kondisi-dot-rusak-ringan { background-color: #f59e0b; }
    .kondisi-dot-rusak-berat { background-color: #ef4444; }

    .tab-btn.active {
        background-color: white;
        color: #1e293b;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function updateFilter(key, value) {
        const url = new URL(window.location.href);
        url.searchParams.set(key, value);
        window.location.href = url.toString();
    }

    function changePeriod(p) {
        const url = new URL(window.location.href);
        url.searchParams.set('period', p);
        window.location.href = url.toString();
    }

    function switchTab(tabId) {
        document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
        document.getElementById('content-' + tabId).classList.remove('hidden');
        
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('active', 'bg-white', 'text-slate-800', 'shadow-sm');
            b.classList.add('text-slate-500');
        });
        
        const activeBtn = document.getElementById('tab-' + tabId);
        activeBtn.classList.add('active', 'bg-white', 'text-slate-800', 'shadow-sm');
        activeBtn.classList.remove('text-slate-500');
    }

    // Charts
    const commonPieOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } }
    };

    // Operasional Chart
    new Chart(document.getElementById('operasionalChart'), {
        type: 'doughnut',
        data: {
            labels: @json(array_keys($statusOperasional)),
            datasets: [{
                data: @json(array_values($statusOperasional)),
                backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444'],
                borderWidth: 0,
                cutout: '70%'
            }]
        },
        options: commonPieOptions
    });

    // Kondisi Chart
    new Chart(document.getElementById('kondisiChart'), {
        type: 'doughnut',
        data: {
            labels: @json(array_keys($kondisiFisik)),
            datasets: [{
                data: @json(array_values($kondisiFisik)),
                backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                borderWidth: 0,
                cutout: '70%'
            }]
        },
        options: commonPieOptions
    });

    // Trend Chart
    new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: {
            labels: @json($trendMonths),
            datasets: [
                {
                    label: 'Peminjaman',
                    data: @json($borrowingTrend),
                    borderColor: '#3b82f6',
                    backgroundColor: '#3b82f620',
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Perbaikan',
                    data: @json($repairTrend),
                    borderColor: '#f59e0b',
                    backgroundColor: '#f59e0b20',
                    fill: true,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } } },
            scales: {
                y: { beginAtZero: true, grid: { borderDash: [5, 5] } },
                x: { grid: { display: false } }
            }
        }
    });

    // Category Bar Chart
    new Chart(document.getElementById('categoryBarChart'), {
        type: 'bar',
        data: {
            labels: @json($categoryBreakdown->pluck('name')),
            datasets: [
                {
                    label: 'Jumlah Item',
                    data: @json($categoryBreakdown->pluck('items_count')),
                    backgroundColor: '#3b82f6',
                    borderRadius: 8
                },
                {
                    label: 'Total Unit',
                    data: @json($categoryBreakdown->pluck('total_units')),
                    backgroundColor: '#10b981',
                    borderRadius: 8
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } },
            scales: {
                y: { beginAtZero: true },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endsection
