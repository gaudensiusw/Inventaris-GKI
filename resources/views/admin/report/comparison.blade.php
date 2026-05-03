@extends('layouts.master')

@section('content')
<div class="flex flex-col gap-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-500/20">
                    <i data-lucide="archive" class="w-5 h-5"></i>
                </div>
                <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Arsip Laporan</h1>
            </div>
            <p class="text-slate-500 text-sm">Perbandingan jumlah stok barang berdasarkan riwayat audit tahunan (Stock Opname).</p>
        </div>
        <div class="flex items-center gap-3 w-full md:w-auto">
            <div class="relative flex-1 md:w-80">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                <input type="text" id="tableSearch" placeholder="Cari nama barang atau kode..." 
                    class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-medium focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all outline-none">
            </div>
            <button onclick="window.print()" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl text-xs font-black uppercase hover:bg-slate-50 transition-all flex items-center gap-2 shadow-sm">
                <i data-lucide="printer" class="w-4 h-4"></i>
                Cetak
            </button>
        </div>
    </div>

    <!-- Stats Overview (Small) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card-premium p-6 flex items-center gap-4 border-none shadow-sm">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center">
                <i data-lucide="calendar" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Periode</p>
                <h3 class="text-xl font-black text-slate-800">{{ $headers->count() }} Tahun</h3>
            </div>
        </div>
        <div class="card-premium p-6 flex items-center gap-4 border-none shadow-sm">
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center">
                <i data-lucide="package" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Item</p>
                <h3 class="text-xl font-black text-slate-800">{{ $items->count() }} Jenis</h3>
            </div>
        </div>
        <div class="card-premium p-6 flex items-center gap-4 border-none shadow-sm">
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center">
                <i data-lucide="check-circle" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Status Audit</p>
                <h3 class="text-xl font-black text-slate-800">Terverifikasi</h3>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card-premium p-0 border-none overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="comparisonTable">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="p-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] whitespace-nowrap">No</th>
                        <th class="p-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] whitespace-nowrap">Informasi Barang</th>
                        <th class="p-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] whitespace-nowrap">Kategori & Lokasi</th>
                        
                        @foreach($headers as $header)
                            <th class="p-6 text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] whitespace-nowrap text-center bg-blue-50/30">
                                QTY<br>
                                <span class="text-xs text-blue-400">{{ \Carbon\Carbon::parse($header->tgl_audit)->format('Y') }}</span>
                            </th>
                        @endforeach
                        
                        <th class="p-6 text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em] whitespace-nowrap text-center bg-emerald-50/30">
                            QTY<br>
                            <span class="text-xs text-emerald-500">Live</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($items as $index => $item)
                    <tr class="item-row hover:bg-slate-50/50 transition-colors group">
                        <td class="p-6 text-sm font-bold text-slate-400 group-hover:text-blue-500 transition-colors">{{ $index + 1 }}</td>
                        <td class="p-6">
                            <div class="font-black text-slate-800 text-sm group-hover:text-blue-600 transition-colors">{{ $item->name }}</div>
                            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-1">{{ $item->kode_aset ?? 'TANPA KODE' }}</div>
                        </td>
                        <td class="p-6">
                            <div class="flex flex-col gap-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 w-fit">
                                    {{ $item->category->name ?? 'N/A' }}
                                </span>
                                <span class="text-[11px] text-slate-400 font-medium flex items-center gap-1">
                                    <i data-lucide="map-pin" class="w-3 h-3"></i>
                                    {{ $item->room->name ?? 'N/A' }}
                                </span>
                            </div>
                        </td>
                        
                        @foreach($headers as $header)
                            @php
                                $detail = isset($details[$item->id]) ? $details[$item->id]->get($header->id_so) : null;
                                $qty = $detail ? $detail->stok_fisik : 0;
                            @endphp
                            <td class="p-6 text-center bg-blue-50/5">
                                <span class="text-sm font-black {{ $qty > 0 ? 'text-slate-700' : 'text-slate-300' }}">
                                    {{ $qty }}
                                </span>
                            </td>
                        @endforeach
                        
                        <td class="p-6 text-center bg-emerald-50/5">
                            <div class="inline-flex flex-col items-center">
                                <span class="text-sm font-black text-emerald-600">
                                    {{ $item->quantity }}
                                </span>
                                <span class="text-[10px] text-emerald-400 font-bold uppercase tracking-tighter">{{ $item->unit ?? 'Unit' }}</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    
                    @if($items->isEmpty())
                    <tr>
                        <td colspan="{{ 4 + $headers->count() }}" class="p-20 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mb-6">
                                    <i data-lucide="inbox" class="w-10 h-10 text-slate-300"></i>
                                </div>
                                <h4 class="text-lg font-black text-slate-800">Data Kosong</h4>
                                <p class="text-sm font-medium max-w-xs mt-2">Belum ada data barang atau riwayat audit yang tersedia untuk dibandingkan.</p>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('tableSearch').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('.item-row');
        
        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            if (text.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
@endpush

<style>
    @media print {
        aside, header, #tableSearch, button { display: none !important; }
        main { margin-left: 0 !important; padding: 0 !important; }
        .card-premium { box-shadow: none !important; border: 1px solid #e2e8f0 !important; }
        .overflow-x-auto { overflow: visible !important; }
        table { border-collapse: collapse !important; }
        th, td { border: 1px solid #e2e8f0 !important; }
        .bg-blue-50\/30 { background-color: #eff6ff !important; -webkit-print-color-adjust: exact; }
        .bg-emerald-50\/30 { background-color: #ecfdf5 !important; -webkit-print-color-adjust: exact; }
    }
</style>
@endsection
