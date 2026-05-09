@extends('layouts.user')

@section('content')
<div class="space-y-6">
    <!-- Back Link -->
    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-blue-600 transition-colors font-semibold">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Kembali ke Daftar Barang
    </a>

    <!-- Page Header -->
    <div>
        <h1 class="text-2xl font-black text-slate-800">Katalog & Form Peminjaman</h1>
        <p class="text-sm text-slate-500 mt-1">Cari dan ajukan peminjaman barang inventaris gereja</p>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route('user.katalog.index') }}" class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-2">Cari Barang</label>
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Nama atau kode aset..."
                        class="w-full pl-10 pr-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-2">Kategori</label>
                <select name="category_id" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all bg-white">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-2">Ruangan</label>
                <select name="room_id" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all bg-white">
                    <option value="">Semua Ruangan</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" {{ $roomId == $room->id ? 'selected' : '' }}>{{ $room->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-2xl text-sm font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all">
                    <span class="flex items-center justify-center gap-2">
                        <i data-lucide="search" class="w-4 h-4"></i>
                        Filter
                    </span>
                </button>
                @if($search || $categoryId || $roomId)
                <a href="{{ route('user.katalog.index') }}" class="px-4 py-3 bg-slate-100 text-slate-500 rounded-2xl text-sm font-bold hover:bg-slate-200 transition-all">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </a>
                @endif
            </div>
        </div>
    </form>

    <!-- Results Info -->
    <div class="flex items-center justify-between">
        <p class="text-sm text-slate-500">
            Menampilkan <span class="font-bold text-slate-700">{{ $items->total() }}</span> barang
        </p>
    </div>

    <!-- Items Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @forelse($items as $item)
        <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100 hover:shadow-md hover:border-slate-200 transition-all group">
            <!-- Category Badge -->
            <div class="flex items-center justify-between mb-3">
                <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-[10px] font-bold uppercase tracking-wide">
                    {{ $item->category->name ?? '-' }}
                </span>
                @if($item->qty_tersedia > 0)
                    <span class="px-2 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-bold">
                        Tersedia
                    </span>
                @else
                    <span class="px-2 py-1 bg-red-50 text-red-500 rounded-full text-[10px] font-bold">
                        Habis
                    </span>
                @endif
            </div>

            <!-- Item Info -->
            <h3 class="text-sm font-black text-slate-800 leading-tight mb-1 group-hover:text-blue-600 transition-colors">
                {{ $item->name }}
            </h3>
            <p class="text-[11px] text-slate-400 font-mono mb-3">{{ $item->kode_aset }}</p>

            <!-- Details -->
            <div class="space-y-2 mb-4">
                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400"></i>
                    <span>{{ $item->room->name ?? 'Tidak ada lokasi' }}</span>
                </div>
                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <i data-lucide="package" class="w-3.5 h-3.5 text-slate-400"></i>
                    <span>Stok tersedia: <strong class="{{ $item->qty_tersedia > 0 ? 'text-emerald-600' : 'text-red-500' }}">{{ $item->qty_tersedia }}</strong> unit</span>
                </div>
                @if($item->qty_dipinjam > 0)
                <div class="flex items-center gap-2 text-xs text-amber-600">
                    <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                    <span>{{ $item->qty_dipinjam }} unit sedang dipinjam</span>
                </div>
                @endif
            </div>

            <!-- Action -->
            @if($item->qty_tersedia > 0)
                <a href="{{ route('user.orders.create', $item->id) }}" 
                   class="block w-full text-center px-4 py-3 bg-blue-600 text-white rounded-2xl text-xs font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all">
                    <span class="flex items-center justify-center gap-2">
                        <i data-lucide="hand" class="w-3.5 h-3.5"></i>
                        Ajukan Peminjaman
                    </span>
                </a>
            @else
                <button disabled class="block w-full text-center px-4 py-3 bg-slate-100 text-slate-400 rounded-2xl text-xs font-bold cursor-not-allowed">
                    Tidak Tersedia
                </button>
            @endif
        </div>
        @empty
        <div class="col-span-full bg-white rounded-3xl p-12 text-center border border-slate-100">
            <i data-lucide="package-x" class="w-12 h-12 text-slate-300 mx-auto mb-4"></i>
            <h3 class="text-sm font-bold text-slate-500">Tidak ada barang ditemukan</h3>
            <p class="text-xs text-slate-400 mt-1">Coba ubah filter pencarian Anda</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($items->hasPages())
    <div class="flex justify-center">
        {{ $items->links() }}
    </div>
    @endif
</div>
@endsection
