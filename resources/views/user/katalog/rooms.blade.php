@extends('layouts.user')

@section('content')
<div class="space-y-6">
    <!-- Back Link -->
    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-blue-600 transition-colors font-semibold">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Kembali ke Beranda
    </a>

    <!-- Page Header -->
    <div>
        <h1 class="text-2xl font-black text-slate-800">Lokasi Penyimpanan</h1>
        <p class="text-sm text-slate-500 mt-1">Cari dan jelajahi barang inventaris gereja berdasarkan lokasinya</p>
    </div>

    <!-- Search & Filters -->
    <form method="GET" action="{{ route('user.katalog.rooms') }}" class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
        <div class="flex gap-4">
            <div class="flex-1 relative">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama lokasi atau deskripsi..."
                    class="w-full pl-10 pr-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all">
            </div>
            <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-2xl text-sm font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all">
                Cari
            </button>
            @if($search)
            <a href="{{ route('user.katalog.rooms') }}" class="px-4 py-3 bg-slate-100 text-slate-500 rounded-2xl text-sm font-bold hover:bg-slate-200 transition-all flex items-center">
                <i data-lucide="x" class="w-4 h-4"></i>
            </a>
            @endif
        </div>
    </form>

    <!-- Rooms Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($rooms as $room)
        <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm hover:shadow-md hover:scale-[1.01] transition-all group overflow-hidden flex flex-col relative">

            <!-- Room Image -->
            <div class="relative h-48 w-full overflow-hidden">
                @if($room->getRoomImage())
                    <img src="{{ $room->getRoomImage() }}" alt="{{ $room->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-slate-50 to-slate-100 flex flex-col items-center justify-center text-slate-400 gap-2">
                        <i data-lucide="image" class="w-10 h-10 opacity-40"></i>
                        <span class="text-[10px] font-bold uppercase tracking-widest opacity-60">No Image</span>
                    </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 to-transparent opacity-40"></div>
            </div>

            <!-- Card Body -->
            <div class="p-6 flex-1 flex flex-col justify-between bg-white relative z-20">
                <div>
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-lg font-black text-slate-800 leading-tight group-hover:text-blue-600 transition-colors">{{ $room->name }}</h3>
                        <span class="px-2.5 py-1 bg-blue-50 text-blue-600 rounded-full text-[9px] font-black uppercase tracking-wide shrink-0">
                            {{ $room->items_count }} Barang
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 font-medium mb-4 line-clamp-2 leading-relaxed">{{ $room->description ?? 'Tidak ada deskripsi lokasi' }}</p>
                </div>
                
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest inline-flex items-center gap-1.5">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-blue-500"></i> Area Inventaris
                    </span>
                    <span class="text-xs font-bold text-blue-600 inline-flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                        Jelajahi <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                    </span>
                </div>
            </div>

            <!-- Clickable Card Link covering everything -->
            <a href="{{ route('user.katalog.rooms.show', $room->id) }}" class="absolute inset-0 z-30"></a>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-3xl p-12 text-center border border-slate-100">
            <i data-lucide="map-pin-off" class="w-12 h-12 text-slate-300 mx-auto mb-4"></i>
            <h3 class="text-sm font-bold text-slate-500">Lokasi tidak ditemukan</h3>
            <p class="text-xs text-slate-400 mt-1">Coba gunakan kata kunci pencarian lain.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
