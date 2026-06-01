@extends('layouts.user')

@section('content')
<div class="space-y-8">
    <!-- Hero / Header -->
    <header class="bg-white border border-slate-100 rounded-[32px] py-12 px-6 text-center shadow-sm">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-3xl md:text-4xl font-black text-slate-800 tracking-tighter mb-4 leading-tight">Daftar Barang & Aset Gereja</h2>
            <p class="text-slate-500 max-w-2xl mx-auto text-sm leading-relaxed">Pantau ketersediaan, spesifikasi, dan jadwal peminjaman barang inventaris GKI Delima secara transparan.</p>
        </div>
    </header>

    <!-- Filters Form -->
    <form action="{{ route('home') }}" method="GET" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1 relative group">
            <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama barang atau kode..." 
                class="w-full pl-11 pr-4 py-3.5 bg-white border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all font-semibold text-xs shadow-sm">
        </div>
        <div class="flex gap-3">
            <select name="category_id" onchange="this.form.submit()" class="px-5 py-3.5 bg-white border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-50 transition-all text-xs font-black text-slate-650 cursor-pointer shadow-sm">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-6 py-3.5 bg-blue-600 text-white rounded-2xl font-black uppercase text-[10px] tracking-wider shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all">Filter</button>
        </div>
    </form>

    <!-- Items Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($items as $item)
        <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm hover:shadow-xl hover:scale-[1.02] transition-all p-5 flex flex-col gap-5 overflow-hidden relative group">
            <!-- Image Container -->
            <a href="{{ route('user.katalog.show', $item->id) }}" class="block aspect-square bg-slate-50 rounded-2xl flex items-center justify-center overflow-hidden relative border border-slate-100/50">
                @if($item->getItemImage())
                    <img src="{{ $item->getItemImage() }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-slate-50 to-slate-100 flex flex-col items-center justify-center gap-2 text-slate-400">
                        <i data-lucide="{{ $item->category->icon ?? 'package' }}" class="w-12 h-12 text-slate-300"></i>
                        <span class="text-[9px] font-bold uppercase tracking-widest text-slate-450">No Image</span>
                    </div>
                @endif
                <div class="absolute top-3 right-3 pointer-events-none">
                    <span class="px-2.5 py-0.5 bg-white/90 backdrop-blur-md text-[9px] font-black uppercase text-blue-600 rounded-full border border-slate-100 shadow-sm">{{ $item->category->name ?? 'Aset' }}</span>
                </div>
            </a>

            <div class="flex flex-col gap-1">
                <a href="{{ route('user.katalog.show', $item->id) }}" class="block hover:text-blue-600 transition-colors">
                    <h3 class="text-base font-black text-slate-800 line-clamp-1 leading-tight">{{ $item->name }}</h3>
                </a>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $item->kode_aset }}</p>
            </div>

            <div class="flex flex-col gap-2.5">
                <div class="flex justify-between items-center text-[11px] font-semibold">
                    <span class="text-slate-400 uppercase tracking-tighter">Lokasi</span>
                    <span class="text-slate-700 font-bold">{{ $item->room->name ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center text-[11px] font-semibold">
                    <span class="text-slate-400 uppercase tracking-tighter">Kondisi</span>
                    <div class="flex gap-1">
                           @if($item->qty_baik > 0) <span class="w-2.5 h-2.5 rounded-full bg-emerald-500" title="Baik: {{ $item->qty_baik }} unit"></span> @endif
                           @if($item->qty_rusak_ringan > 0) <span class="w-2.5 h-2.5 rounded-full bg-amber-500" title="Rusak Ringan: {{ $item->qty_rusak_ringan }} unit"></span> @endif
                           @if($item->qty_rusak_berat > 0) <span class="w-2.5 h-2.5 rounded-full bg-red-500" title="Rusak Berat: {{ $item->qty_rusak_berat }} unit"></span> @endif
                    </div>
                </div>
                <div class="flex justify-between items-center text-[11px] pt-2.5 border-t border-slate-50 font-bold">
                    <span class="text-slate-400 uppercase tracking-tighter">Tersedia</span>
                    <span class="text-emerald-600 font-black">{{ $item->qty_tersedia }} / {{ $item->quantity }} Unit</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col gap-2 mt-auto">
                <a href="{{ route('user.katalog.show', $item->id) }}" 
                   class="block w-full text-center px-4 py-2.5 bg-slate-50 text-slate-650 hover:bg-slate-100 hover:text-blue-600 rounded-2xl text-[10px] font-black uppercase tracking-wider transition-all flex items-center justify-center gap-1.5 border border-slate-100">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    Detail & Jadwal Booking
                </a>
                @if($item->qty_tersedia > 0)
                    <a href="{{ route('user.orders.create', $item->id) }}" 
                       class="block w-full text-center px-4 py-2.5 bg-blue-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-wider hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all flex items-center justify-center gap-1.5">
                        <i data-lucide="hand" class="w-3.5 h-3.5"></i>
                        Ajukan Peminjaman
                    </a>
                @else
                    <button disabled class="block w-full text-center px-4 py-2.5 bg-slate-100 text-slate-400 rounded-2xl text-[10px] font-black uppercase cursor-not-allowed">
                        Tidak Tersedia
                    </button>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 text-center flex flex-col items-center gap-4 bg-white rounded-[32px] border border-slate-100 p-12">
            <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-2xl flex items-center justify-center">
                <i data-lucide="package-search" class="w-8 h-8"></i>
            </div>
            <h3 class="text-base font-bold text-slate-700">Barang tidak ditemukan</h3>
            <p class="text-xs text-slate-400">Coba gunakan kata kunci pencarian lain.</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($items->hasPages())
    <div class="mt-12 flex justify-center">
        {{ $items->links() }}
    </div>
    @endif
</div>
@endsection
