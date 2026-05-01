@extends('layouts.master')

@section('content')
<div class="flex flex-col gap-8">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Dashboard</h1>
            <p class="text-slate-500 text-sm mt-1">Selamat datang kembali, {{ auth()->user()->name }}</p>
        </div>
        <a href="{{ route('qr-scanner.index') }}" class="px-6 py-3 bg-blue-600 text-white rounded-2xl text-xs font-black uppercase shadow-xl shadow-blue-200 hover:bg-blue-700 transition-all flex items-center gap-2">
            <i data-lucide="scan" class="w-4 h-4"></i>
            Scan QR Code
        </a>
    </div>

    <!-- Top Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card-premium p-6 border-none flex items-center justify-between group">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Barang</p>
                <h3 class="text-3xl font-black text-slate-800">{{ number_format($totalItems) }}</h3>
                <p class="text-[10px] text-blue-500 font-bold mt-1">+12% dari bulan lalu</p>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center">
                <i data-lucide="package" class="w-6 h-6"></i>
            </div>
        </div>
        <div class="card-premium p-6 border-none flex items-center justify-between group">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Kondisi Baik</p>
                <h3 class="text-3xl font-black text-emerald-600">{{ number_format($kondisiBaik) }}</h3>
                <p class="text-[10px] text-emerald-500 font-bold mt-1">{{ round(($kondisiBaik / max(1, $totalItems)) * 100) }}% dari total</p>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center">
                <i data-lucide="check-circle" class="w-6 h-6"></i>
            </div>
        </div>
        <div class="card-premium p-6 border-none flex items-center justify-between group">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Perlu Perbaikan</p>
                <h3 class="text-3xl font-black text-orange-500">{{ number_format($perluPerbaikan) }}</h3>
                <p class="text-[10px] text-orange-500 font-bold mt-1">Perhatian diperlukan</p>
            </div>
            <div class="w-12 h-12 bg-orange-50 text-orange-500 rounded-2xl flex items-center justify-center">
                <i data-lucide="alert-triangle" class="w-6 h-6"></i>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="card-premium p-8 border-none flex flex-col gap-6">
            <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Distribusi Kategori</h3>
            <div class="relative aspect-square max-h-[300px] mx-auto">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
        <div class="card-premium p-8 border-none flex flex-col gap-6">
            <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Barang per Lokasi</h3>
            <div class="relative h-[300px]">
                <canvas id="locationChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="card-premium p-0 border-none overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-50 flex justify-between items-center">
            <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Aktivitas Terbaru</h3>
            <a href="{{ route('inventory.index') }}" class="text-[10px] font-black text-blue-600 uppercase hover:underline">Lihat Semua Inventaris ></a>
        </div>
        <div class="divide-y divide-slate-50">
            @foreach($recentActivities as $activity)
            <div class="px-8 py-4 flex items-center justify-between hover:bg-slate-50 transition-colors">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-slate-100 text-slate-400 rounded-xl flex items-center justify-center">
                        <i data-lucide="package" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-700">{{ $activity->name }}</h4>
                        <p class="text-[10px] text-slate-400 font-medium tracking-tight uppercase">Diperbarui oleh Admin • {{ $activity->updated_at ? $activity->updated_at->format('Y-m-d') : '-' }}</p>
                    </div>
                </div>
                <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300"></i>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Footer Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="p-8 bg-blue-600 rounded-[32px] text-white flex flex-col gap-2 relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
            <p class="text-[10px] font-bold uppercase tracking-widest opacity-60">Total</p>
            <h3 class="text-3xl font-black">{{ $totalUniqueItems }}</h3>
            <p class="text-xs font-bold opacity-80">Jenis Barang Terdaftar</p>
        </div>
        <div class="p-8 bg-emerald-500 rounded-[32px] text-white flex flex-col gap-2 relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
            <p class="text-[10px] font-bold uppercase tracking-widest opacity-60">Kategori</p>
            <h3 class="text-3xl font-black">{{ $totalCategories }}</h3>
            <p class="text-xs font-bold opacity-80">Kategori Berbeda</p>
        </div>
        <div class="p-8 bg-purple-600 rounded-[32px] text-white flex flex-col gap-2 relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
            <p class="text-[10px] font-bold uppercase tracking-widest opacity-60">Status</p>
            <h3 class="text-3xl font-black">{{ $kondisiBaikPercent }}%</h3>
            <p class="text-xs font-bold opacity-80">Kondisi Baik</p>
        </div>
    </div>

    <!-- Status Breakdown Grid -->
    <div class="card-premium p-8 border-none flex flex-col gap-8">
        <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Status Kondisi Barang</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Row 1 -->
            <div class="p-4 bg-blue-50/50 border border-blue-100/50 rounded-2xl flex flex-col gap-1">
                <div class="flex items-center gap-2 text-blue-600 mb-1">
                    <i data-lucide="undo-2" class="w-4 h-4"></i>
                    <span class="text-[10px] font-black uppercase">Dipinjam</span>
                </div>
                <h4 class="text-xl font-black text-slate-800">{{ $statusBreakdown['dipinjam'] }}</h4>
                <p class="text-[10px] text-slate-400 font-bold uppercase">Total Unit Fisik</p>
            </div>
            <div class="p-4 bg-orange-50/50 border border-orange-100/50 rounded-2xl flex flex-col gap-1">
                <div class="flex items-center gap-2 text-orange-600 mb-1">
                    <i data-lucide="wrench" class="w-4 h-4"></i>
                    <span class="text-[10px] font-black uppercase">Diperbaiki</span>
                </div>
                <h4 class="text-xl font-black text-slate-800">{{ $statusBreakdown['diperbaiki'] }}</h4>
                <p class="text-[10px] text-slate-400 font-bold uppercase">Total Unit Fisik</p>
            </div>
            <div class="p-4 bg-red-50/50 border border-red-100/50 rounded-2xl flex flex-col gap-1">
                <div class="flex items-center gap-2 text-red-600 mb-1">
                    <i data-lucide="help-circle" class="w-4 h-4"></i>
                    <span class="text-[10px] font-black uppercase">Hilang</span>
                </div>
                <h4 class="text-xl font-black text-slate-800">{{ $statusBreakdown['hilang'] }}</h4>
                <p class="text-[10px] text-slate-400 font-bold uppercase">Total Unit Fisik</p>
            </div>
            <div class="p-4 bg-purple-50/50 border border-purple-100/50 rounded-2xl flex flex-col gap-1">
                <div class="flex items-center gap-2 text-purple-600 mb-1">
                    <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                    <span class="text-[10px] font-black uppercase">Pengadaan</span>
                </div>
                <h4 class="text-xl font-black text-slate-800">{{ $statusBreakdown['pengadaan'] }}</h4>
                <p class="text-[10px] text-slate-400 font-bold uppercase">Total Unit Fisik</p>
            </div>
            <!-- Row 2 -->
            <div class="p-4 bg-amber-50/50 border border-amber-100/50 rounded-2xl flex flex-col gap-1">
                <div class="flex items-center gap-2 text-amber-600 mb-1">
                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                    <span class="text-[10px] font-black uppercase">Rusak Ringan</span>
                </div>
                <h4 class="text-xl font-black text-slate-800">{{ $statusBreakdown['rusak_ringan'] }}</h4>
                <p class="text-[10px] text-slate-400 font-bold uppercase">Total Unit Fisik</p>
            </div>
            <div class="p-4 bg-rose-50/50 border border-rose-100/50 rounded-2xl flex flex-col gap-1">
                <div class="flex items-center gap-2 text-rose-600 mb-1">
                    <i data-lucide="x-circle" class="w-4 h-4"></i>
                    <span class="text-[10px] font-black uppercase">Rusak Berat</span>
                </div>
                <h4 class="text-xl font-black text-slate-800">{{ $statusBreakdown['rusak_berat'] }}</h4>
                <p class="text-[10px] text-slate-400 font-bold uppercase">Total Unit Fisik</p>
            </div>
            <div class="p-4 bg-slate-100/50 border border-slate-200/50 rounded-2xl flex flex-col gap-1">
                <div class="flex items-center gap-2 text-slate-500 mb-1">
                    <i data-lucide="archive" class="w-4 h-4"></i>
                    <span class="text-[10px] font-black uppercase">Tidak Digunakan</span>
                </div>
                <h4 class="text-xl font-black text-slate-800">{{ $statusBreakdown['tidak_digunakan'] }}</h4>
                <p class="text-[10px] text-slate-400 font-bold uppercase">Total Unit Fisik</p>
            </div>
            <div class="p-4 bg-emerald-50/50 border border-emerald-100/50 rounded-2xl flex flex-col gap-1">
                <div class="flex items-center gap-2 text-emerald-600 mb-1">
                    <i data-lucide="check" class="w-4 h-4"></i>
                    <span class="text-[10px] font-black uppercase">Baik</span>
                </div>
                <h4 class="text-xl font-black text-slate-800">{{ $statusBreakdown['baik'] }}</h4>
                <p class="text-[10px] text-slate-400 font-bold uppercase">Total Unit Fisik</p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Category Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'pie',
        data: {
            labels: {!! json_encode($categoryData->pluck('name')) !!},
            datasets: [{
                data: {!! json_encode($categoryData->pluck('count')) !!},
                backgroundColor: [
                    '#3b82f6', '#10b981', '#8b5cf6', '#f59e0b', '#ef4444', '#64748b'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: { size: 10, weight: 'bold' }
                    }
                }
            }
        }
    });

    // Location Chart
    const locationCtx = document.getElementById('locationChart').getContext('2d');
    new Chart(locationCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($locationData->pluck('name')) !!},
            datasets: [{
                label: 'Jumlah Barang',
                data: {!! json_encode($locationData->pluck('count')) !!},
                backgroundColor: '#3b82f6',
                borderRadius: 12,
                barThickness: 40
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { display: false },
                    ticks: { font: { size: 10, weight: 'bold' } }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 10, weight: 'bold' } }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
@endsection
