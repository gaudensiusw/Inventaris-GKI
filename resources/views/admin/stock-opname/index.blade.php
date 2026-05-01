@extends('layouts.master')

@section('content')
<div class="flex flex-col gap-8">
    <!-- Header Section -->
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Stock Opname</h1>
            <p class="text-slate-500 text-sm mt-1">{{ $stockOpnames->count() }} sesi audit tercatat</p>
        </div>
        <a href="{{ route('stock-opname.create') }}" class="px-6 py-3 bg-emerald-600 text-white rounded-2xl text-sm font-black uppercase shadow-xl shadow-emerald-200 hover:bg-emerald-700 hover:scale-105 active:scale-95 transition-all flex items-center gap-3">
            <i data-lucide="plus-circle" class="w-6 h-6"></i>
            <span>Mulai Audit Baru</span>
        </a>
    </div>

    <!-- Table Section -->
    <div class="card-premium shadow-card">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">ID Audit</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tanggal Audit</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Auditor</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Catatan</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($stockOpnames as $so)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-5">
                            <span class="text-sm font-bold text-slate-800">SO-{{ \Carbon\Carbon::parse($so->tgl_audit)->format('Ymd') }}-{{ str_pad($so->id_so, 3, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td class="px-6 py-5">
                            <p class="text-sm text-slate-600 font-medium">{{ \Carbon\Carbon::parse($so->tgl_audit)->format('d M Y') }}</p>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-[10px] font-bold">
                                    {{ substr($so->user->name ?? 'A', 0, 1) }}
                                </div>
                                <span class="text-sm font-medium text-slate-700">{{ $so->user->name ?? 'Unknown' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            @if($so->status == 'Completed')
                                <span class="px-3 py-1 bg-green-50 text-green-600 rounded-full text-[10px] font-bold uppercase tracking-tight">
                                    Selesai
                                </span>
                            @else
                                <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-full text-[10px] font-bold uppercase tracking-tight">
                                    Draft
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-5">
                            <p class="text-sm text-slate-500 italic max-w-xs truncate">{{ $so->keterangan ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <a href="{{ route('stock-opname.show', $so->id_so) }}" class="p-2 hover:bg-blue-50 text-blue-500 rounded-lg transition-colors inline-block">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <i data-lucide="clipboard-check" class="w-12 h-12 text-slate-200"></i>
                                <p class="text-slate-400 font-medium">Belum ada riwayat stock opname.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
