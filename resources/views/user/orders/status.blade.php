@extends('layouts.user')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Back Link -->
    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-blue-600 transition-colors font-semibold">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Kembali ke Daftar Barang
    </a>

    <!-- Page Header -->
    <div>
        <h1 class="text-2xl font-black text-slate-800">Cek Status Peminjaman</h1>
        <p class="text-sm text-slate-500 mt-1">Masukkan nama atau kode tracking untuk melihat status peminjaman Anda</p>
    </div>

    <!-- Search Form -->
    <form method="GET" action="{{ route('user.orders.status') }}" class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
        <div class="flex gap-3">
            <div class="relative flex-1">
                <i data-lucide="search" class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="q" value="{{ $search }}" placeholder="Masukkan nama, no. HP, atau kode tracking (REQ-XXXXXXXX)..."
                    class="w-full pl-11 pr-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all">
            </div>
            <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-2xl text-sm font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all">
                Cari
            </button>
        </div>
    </form>

    <!-- Results -->
    @if($search)
    <div class="space-y-3">
        @forelse($orders as $order)
        <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0
                        @switch($order->status)
                            @case('Pending') bg-amber-50 @break
                            @case('Disetujui') bg-emerald-50 @break
                            @case('Ditolak') bg-red-50 @break
                            @default bg-slate-50
                        @endswitch
                    ">
                        @switch($order->status)
                            @case('Pending')
                                <i data-lucide="clock" class="w-6 h-6 text-amber-500"></i>
                                @break
                            @case('Disetujui')
                                <i data-lucide="check-circle" class="w-6 h-6 text-emerald-600"></i>
                                @break
                            @case('Ditolak')
                                <i data-lucide="x-circle" class="w-6 h-6 text-red-500"></i>
                                @break
                            @default
                                <i data-lucide="help-circle" class="w-6 h-6 text-slate-400"></i>
                        @endswitch
                    </div>

                    <div>
                        <h3 class="text-sm font-black text-slate-800">{{ $order->item->name ?? 'Barang tidak ditemukan' }}</h3>
                        <p class="text-[11px] text-slate-400 mt-0.5">Peminjam: <strong class="text-slate-600">{{ $order->nama_peminjam }}</strong></p>
                        <div class="flex flex-wrap gap-x-4 gap-y-1 mt-1">
                            <span class="text-[11px] text-slate-400">Qty: <strong class="text-slate-600">{{ $order->qty }}</strong></span>
                            <span class="text-[11px] text-slate-400">Pinjam: <strong class="text-slate-600">{{ \Carbon\Carbon::parse($order->start_date)->format('d M Y') }}</strong></span>
                            <span class="text-[11px] text-slate-400">Kembali: <strong class="text-slate-600">{{ \Carbon\Carbon::parse($order->end_date)->format('d M Y') }}</strong></span>
                        </div>
                        @if($order->reject_reason)
                            <div class="mt-2 px-3 py-2 bg-red-50 rounded-xl">
                                <p class="text-xs text-red-600"><strong>Alasan ditolak:</strong> {{ $order->reject_reason }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Status Badge -->
                <div class="shrink-0">
                    <span class="px-4 py-2 rounded-xl text-xs font-bold
                        @switch($order->status)
                            @case('Pending') bg-amber-100 text-amber-700 @break
                            @case('Disetujui') bg-emerald-100 text-emerald-700 @break
                            @case('Ditolak') bg-red-100 text-red-700 @break
                            @default bg-slate-100 text-slate-600
                        @endswitch
                    ">
                        {{ $order->status }}
                    </span>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-3xl p-12 text-center border border-slate-100">
            <i data-lucide="search-x" class="w-12 h-12 text-slate-300 mx-auto mb-4"></i>
            <h3 class="text-sm font-bold text-slate-500">Tidak ditemukan</h3>
            <p class="text-xs text-slate-400 mt-1">Tidak ada peminjaman yang cocok dengan pencarian "{{ $search }}"</p>
        </div>
        @endforelse
    </div>
    @else
    <div class="bg-white rounded-3xl p-12 text-center border border-slate-100">
        <i data-lucide="search" class="w-12 h-12 text-slate-300 mx-auto mb-4"></i>
        <h3 class="text-sm font-bold text-slate-500">Cari Status Peminjaman</h3>
        <p class="text-xs text-slate-400 mt-1">Masukkan nama, nomor HP, atau kode tracking untuk melihat status</p>
    </div>
    @endif
</div>
@endsection
