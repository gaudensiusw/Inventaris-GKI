@extends('layouts.master')

@section('content')
<div class="flex flex-col gap-8">
    <!-- Header Section -->
    <div class="flex justify-between items-center">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Detail Audit</h1>
                <span class="px-3 py-1 bg-green-50 text-green-600 rounded-full text-[10px] font-bold uppercase tracking-tight">Selesai</span>
            </div>
            <p class="text-slate-500 text-sm mt-1">ID Audit: SO-{{ \Carbon\Carbon::parse($stockOpname->tgl_audit)->format('Ymd') }}-{{ str_pad($stockOpname->id_so, 3, '0', STR_PAD_LEFT) }} | Dilakukan oleh {{ $stockOpname->user->name ?? 'System' }}</p>
        </div>
        <div class="flex gap-3">
            <button class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50 flex items-center gap-2 transition-all shadow-sm">
                <i data-lucide="printer" class="w-4 h-4"></i>
                <span>Print Report</span>
            </button>
            <a href="{{ route('stock-opname.index') }}" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-sm font-semibold hover:bg-slate-200 transition-all flex items-center gap-2">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card-premium shadow-card p-6 border-l-4 border-l-blue-500">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Item Diaudit</p>
            <h3 class="text-2xl font-black text-slate-800">{{ $stockOpname->details->count() }}</h3>
        </div>
        <div class="card-premium shadow-card p-6 border-l-4 border-l-green-500">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Item Sesuai</p>
            <h3 class="text-2xl font-black text-slate-800">{{ $stockOpname->details->where('selisih', 0)->count() }}</h3>
        </div>
        <div class="card-premium shadow-card p-6 border-l-4 border-l-amber-500">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Item Selisih</p>
            <h3 class="text-2xl font-black text-slate-800">{{ $stockOpname->details->where('selisih', '!=', 0)->count() }}</h3>
        </div>
    </div>

    <!-- Details Table -->
    <div class="card-premium shadow-card p-0 overflow-hidden">
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-100">
            <h3 class="font-bold text-slate-700">Hasil Audit per Item</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-50">
                        <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Barang</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Stok Sistem</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Stok Fisik</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Selisih</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($stockOpname->details as $detail)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-700">{{ $detail->item->name }}</span>
                                <span class="text-[10px] text-slate-400">{{ $detail->item->kode_aset }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-medium text-slate-600">{{ $detail->stok_sistem }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-blue-600">{{ $detail->stok_fisik }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($detail->selisih == 0)
                                <span class="text-sm font-bold text-green-500">0 (Pas)</span>
                            @elseif($detail->selisih > 0)
                                <span class="text-sm font-bold text-blue-500">+{{ $detail->selisih }} (Lebih)</span>
                            @else
                                <span class="text-sm font-bold text-red-500">{{ $detail->selisih }} (Kurang)</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-xs text-slate-500 italic">{{ $detail->keterangan ?? '-' }}</p>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
