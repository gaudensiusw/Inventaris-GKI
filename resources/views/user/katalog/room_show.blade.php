@extends('layouts.user')

@section('content')
<div class="space-y-6">
    <!-- Back Link -->
    <a href="{{ route('user.katalog.rooms') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-blue-600 transition-colors font-semibold">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Kembali ke Lokasi Penyimpanan
    </a>

    <!-- Room Banner -->
    <div class="bg-white rounded-[32px] border border-slate-100 overflow-hidden relative min-h-[200px] flex flex-col justify-end shadow-sm group">
        <!-- Room Image Background -->
        @if($room->getRoomImage())
            <img src="{{ $room->getRoomImage() }}" alt="{{ $room->name }}" class="absolute inset-0 w-full h-full object-cover z-0">
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-slate-800 to-slate-900 z-0 flex items-center justify-center">
                <i data-lucide="map-pin" class="w-16 h-16 text-white/5 opacity-10"></i>
            </div>
        @endif
        <!-- Dark Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/50 to-transparent z-10"></div>

        <!-- Content Area -->
        <div class="relative z-20 p-8 flex flex-col sm:flex-row justify-between items-start sm:items-end gap-6 w-full">
            <div class="flex flex-col gap-2">
                <span class="px-3.5 py-1 bg-white/20 backdrop-blur-md border border-white/20 text-white text-[9px] font-black uppercase tracking-[0.2em] rounded-full w-fit">
                    Detail Lokasi
                </span>
                <h1 class="text-2xl md:text-3xl font-black text-white tracking-tight">{{ $room->name }}</h1>
                <p class="text-white/60 text-xs sm:text-sm max-w-xl font-medium leading-relaxed">{{ $room->description ?? 'Tidak ada deskripsi lokasi' }}</p>
            </div>

            <div class="shrink-0">
                <span class="px-4 py-2 bg-white/10 backdrop-blur-md text-white rounded-2xl text-xs font-bold border border-white/10">
                    Total: <b>{{ $items->count() }}</b> jenis barang
                </span>
            </div>
        </div>
    </div>

    <!-- Items Grid inside Room -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @forelse($items as $item)
        <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100 hover:shadow-md hover:border-slate-200 transition-all group flex flex-col justify-between">
            <div>
                <!-- Image Container -->
                <div class="relative w-full h-40 bg-slate-100 rounded-2xl overflow-hidden mb-4 border border-slate-50 flex items-center justify-center">
                    @if($item->getItemImage())
                        <img src="{{ $item->getItemImage() }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <!-- Elegant Placeholder Gradient with Icon -->
                        <div class="w-full h-full bg-gradient-to-br from-slate-50 to-slate-100 flex flex-col items-center justify-center gap-2 text-slate-400">
                            <i data-lucide="{{ $item->category->icon ?? 'package' }}" class="w-8 h-8 text-slate-300"></i>
                            <span class="text-[9px] font-bold uppercase tracking-widest text-slate-450">No Image</span>
                        </div>
                    @endif

                    <!-- Badges overlay -->
                    <div class="absolute top-3 left-3 right-3 flex items-center justify-between pointer-events-none">
                        <span class="px-2.5 py-1 bg-white/95 backdrop-blur-sm text-blue-600 rounded-full text-[9px] font-black uppercase tracking-wide shadow-sm border border-white/50">
                            {{ $item->category->name ?? '-' }}
                        </span>
                        @if($item->qty_tersedia > 0)
                            <span class="px-2 py-0.5 bg-emerald-500 text-white rounded-full text-[9px] font-bold shadow-sm">
                                Tersedia
                            </span>
                        @else
                            <span class="px-2 py-0.5 bg-red-500 text-white rounded-full text-[9px] font-bold shadow-sm">
                                Habis
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Item Info -->
                <h3 class="text-sm font-black text-slate-800 leading-tight mb-1 group-hover:text-blue-600 transition-colors">
                    {{ $item->name }}
                </h3>
                <p class="text-[11px] text-slate-400 font-mono mb-3">{{ $item->kode_aset }}</p>

                <!-- Details -->
                <div class="space-y-2 mb-4">
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
            <p class="text-xs text-slate-400 mt-1">Belum ada barang di ruangan ini.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
