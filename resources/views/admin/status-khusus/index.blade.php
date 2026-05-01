@extends('layouts.master')

@section('content')
<div class="flex flex-col gap-8">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Status Khusus</h1>
            <p class="text-slate-500 text-sm mt-1">Pantau barang dengan status khusus</p>
        </div>
        <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition-all flex items-center gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Kembali
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card-premium p-6 border-none flex items-center justify-between group">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Barang Hilang</p>
                <h3 class="text-3xl font-black text-red-600">{{ $stats['hilang'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center">
                <i data-lucide="alert-circle" class="w-6 h-6"></i>
            </div>
        </div>
        <div class="card-premium p-6 border-none flex items-center justify-between group">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Tidak Digunakan</p>
                <h3 class="text-3xl font-black text-slate-800">{{ $stats['tidak_digunakan'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center">
                <i data-lucide="x-circle" class="w-6 h-6"></i>
            </div>
        </div>
        <div class="card-premium p-6 border-none flex items-center justify-between group">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Dalam Pengadaan</p>
                <h3 class="text-3xl font-black text-blue-600">{{ $stats['pengadaan'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center">
                <i data-lucide="truck" class="w-6 h-6"></i>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="flex gap-2 bg-slate-100/50 p-1 rounded-2xl w-fit">
        <a href="{{ route('special-status.index', ['tab' => 'hilang']) }}" class="px-6 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2 {{ $tab == 'hilang' ? 'bg-white shadow-sm text-blue-600' : 'text-slate-500 hover:text-slate-700' }}">
            <i data-lucide="clock" class="w-4 h-4"></i>
            Hilang ({{ $stats['hilang'] }})
        </a>
        <a href="{{ route('special-status.index', ['tab' => 'tidak-digunakan']) }}" class="px-6 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2 {{ $tab == 'tidak-digunakan' ? 'bg-white shadow-sm text-blue-600' : 'text-slate-500 hover:text-slate-700' }}">
            <i data-lucide="eye-off" class="w-4 h-4"></i>
            Tidak Digunakan ({{ $stats['tidak_digunakan'] }})
        </a>
        <a href="{{ route('special-status.index', ['tab' => 'pengadaan']) }}" class="px-6 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2 {{ $tab == 'pengadaan' ? 'bg-white shadow-sm text-blue-600' : 'text-slate-500 hover:text-slate-700' }}">
            <i data-lucide="package-plus" class="w-4 h-4"></i>
            Pengadaan ({{ $stats['pengadaan'] }})
        </a>
    </div>

    <!-- Search & Filter -->
    <div class="flex flex-col gap-6">
        <div class="relative group">
            <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"></i>
            <input type="text" id="search-input" value="{{ $search }}" placeholder="Cari barang..." 
                class="w-full pl-12 pr-4 py-4 bg-white border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all font-medium text-sm shadow-sm">
        </div>

        <!-- Items List -->
        <div class="flex flex-col gap-4">
            @forelse($items as $item)
            <div class="card-premium p-8 border-none flex flex-col gap-6 group hover:shadow-xl transition-all">
                <div class="flex justify-between items-start">
                    <div class="flex flex-col gap-1">
                        <h3 class="text-xl font-black text-slate-800">{{ $item->name }} <span class="text-xs font-bold text-slate-300 ml-2">{{ $item->kode_aset }}</span></h3>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ $item->category->name ?? 'Tanpa Kategori' }}</p>
                    </div>
                    <div class="flex gap-2">
                        <button class="p-2.5 bg-slate-50 text-slate-400 rounded-xl hover:bg-blue-50 hover:text-blue-600 transition-all"><i data-lucide="edit-3" class="w-5 h-5"></i></button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 pt-4 border-t border-slate-50">
                    <div class="flex flex-col gap-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Lokasi Terakhir</span>
                        <span class="text-sm font-bold text-slate-700">{{ $item->room->name ?? '-' }}</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Jumlah</span>
                        <span class="text-sm font-bold text-slate-700">
                            {{ $tab == 'hilang' ? $item->qty_hilang : ($tab == 'tidak-digunakan' ? $item->qty_tidak_digunakan : $item->qty_pengadaan) }} unit
                        </span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Update Terakhir</span>
                        <span class="text-sm font-bold text-slate-700">{{ $item->updated_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Tanggal Beli</span>
                        <span class="text-sm font-bold text-slate-700">{{ $item->purchase_date ? \Carbon\Carbon::parse($item->purchase_date)->format('d M Y') : '-' }}</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Harga</span>
                        <span class="text-sm font-bold text-slate-700">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                    </div>
                </div>

                @if($item->description || $item->keterangan)
                <div class="flex flex-col gap-2 pt-4 border-t border-slate-50">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Deskripsi</span>
                    <p class="text-sm text-slate-500 italic">{{ $item->description ?: $item->keterangan }}</p>
                </div>
                @endif
            </div>
            @empty
            <div class="card-premium p-20 border-none flex flex-col items-center justify-center text-center gap-4">
                <div class="w-20 h-20 bg-slate-50 text-slate-200 rounded-full flex items-center justify-center">
                    <i data-lucide="package-search" class="w-10 h-10"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-700">Tidak Ada Barang</h3>
                    <p class="text-sm text-slate-400">Belum ada barang dengan status ini.</p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination & Per Page -->
        @if($items->hasPages() || $items->total() > 10)
        <div class="flex justify-between items-center bg-white p-6 rounded-[32px] shadow-sm border border-slate-100">
            <div class="flex items-center gap-4">
                <span class="text-xs font-bold text-slate-400">Tampilkan</span>
                <select onchange="window.location.href = addQueryParam('per_page', this.value)" class="px-3 py-1.5 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold text-slate-600 focus:outline-none">
                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                    <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                </select>
            </div>
            {{ $items->links() }}
        </div>
        @endif
    </div>
</div>

<script>
    function addQueryParam(key, value) {
        const url = new URL(window.location.href);
        url.searchParams.set(key, value);
        return url.toString();
    }

    document.getElementById('search-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            window.location.href = addQueryParam('search', this.value);
        }
    });
</script>
@endsection
