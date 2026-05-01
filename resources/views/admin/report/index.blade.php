@extends('layouts.master')

@section('content')
<div class="flex flex-col gap-8">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Laporan Inventaris</h1>
            <p class="text-slate-500 text-sm mt-1">Cetak dan tinjau laporan aset GKI Delima.</p>
        </div>
        <div class="flex gap-3">
            <button class="btn-primary-custom flex items-center gap-2">
                <i data-lucide="printer" class="w-5 h-5"></i>
                <span>Cetak PDF</span>
            </button>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="card-premium shadow-card p-8">
        <form action="{{ route('report.index') }}" method="GET" class="flex flex-wrap items-end gap-6">
            <div class="flex flex-col gap-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Periode Laporan</label>
                <div class="flex bg-slate-50 p-1 rounded-2xl border border-slate-100">
                    <button type="button" onclick="setPeriod('monthly')" id="btn-monthly" class="px-6 py-2 rounded-xl text-xs font-bold transition-all {{ $period == 'monthly' ? 'bg-white shadow-sm text-blue-600' : 'text-slate-400' }}">Bulanan</button>
                    <button type="button" onclick="setPeriod('yearly')" id="btn-yearly" class="px-6 py-2 rounded-xl text-xs font-bold transition-all {{ $period == 'yearly' ? 'bg-white shadow-sm text-blue-600' : 'text-slate-400' }}">Tahunan</button>
                    <input type="hidden" name="period" id="period-input" value="{{ $period }}">
                </div>
            </div>

            <div id="month-select" class="flex flex-col gap-2 {{ $period == 'yearly' ? 'hidden' : '' }}">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Pilih Bulan</label>
                <select name="month" class="px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-bold text-slate-600 focus:outline-none focus:ring-4 focus:ring-blue-50 transition-all">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Pilih Tahun</label>
                <select name="year" class="px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-bold text-slate-600 focus:outline-none focus:ring-4 focus:ring-blue-50 transition-all">
                    @foreach(range(date('Y'), 2020) as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="p-2.5 bg-slate-800 text-white rounded-2xl hover:bg-slate-900 transition-all shadow-lg">
                <i data-lucide="filter" class="w-5 h-5"></i>
            </button>
        </form>
    </div>

    <!-- Summary Reports -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Stock Opname Summary -->
        <div class="card-premium shadow-card p-0 flex flex-col overflow-hidden">
            <div class="px-8 py-6 bg-slate-50/50 border-b border-slate-100">
                <h3 class="font-bold text-slate-700">Ringkasan Audit (Stock Opname)</h3>
            </div>
            <div class="p-0">
                <table class="w-full text-left">
                    <thead class="bg-slate-50/30">
                        <tr>
                            <th class="px-8 py-3 text-[10px] font-black text-slate-400 uppercase">ID Audit</th>
                            <th class="px-8 py-3 text-[10px] font-black text-slate-400 uppercase">Tanggal</th>
                            <th class="px-8 py-3 text-[10px] font-black text-slate-400 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($stock_opnames as $so)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-4 text-sm font-bold text-slate-700">SO-{{ \Carbon\Carbon::parse($so->tgl_audit)->format('Ymd') }}-{{ str_pad($so->id_so ?? $so->id, 3, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-8 py-4 text-sm text-slate-500 font-medium">{{ \Carbon\Carbon::parse($so->tgl_audit)->format('d M Y') }}</td>
                            <td class="px-8 py-4">
                                <span class="px-2 py-0.5 bg-green-50 text-green-600 rounded-full text-[10px] font-black uppercase">{{ $so->status }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-8 py-12 text-center text-slate-400 italic text-sm">Tidak ada audit pada periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Disposals Summary -->
        <div class="card-premium shadow-card p-0 flex flex-col overflow-hidden">
            <div class="px-8 py-6 bg-slate-50/50 border-b border-slate-100">
                <h3 class="font-bold text-slate-700">Barang Keluar (Penghapusan)</h3>
            </div>
            <div class="p-0">
                <table class="w-full text-left">
                    <thead class="bg-slate-50/30">
                        <tr>
                            <th class="px-8 py-3 text-[10px] font-black text-slate-400 uppercase">Barang</th>
                            <th class="px-8 py-3 text-[10px] font-black text-slate-400 uppercase">Tanggal Dihapus</th>
                            <th class="px-8 py-3 text-[10px] font-black text-slate-400 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($disposals as $disp)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-4">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-slate-700">{{ $disp->name }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $disp->kode_aset }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-4 text-sm text-slate-500 font-medium">{{ \Carbon\Carbon::parse($disp->deleted_at)->format('d M Y') }}</td>
                            <td class="px-8 py-4">
                                <span class="px-2 py-0.5 bg-red-50 text-red-600 rounded-full text-[10px] font-black uppercase">Soft Deleted</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-8 py-12 text-center text-slate-400 italic text-sm">Tidak ada barang dihapus pada periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function setPeriod(type) {
        document.getElementById('period-input').value = type;
        const monthSelect = document.getElementById('month-select');
        const btnMonthly = document.getElementById('btn-monthly');
        const btnYearly = document.getElementById('btn-yearly');

        if (type === 'yearly') {
            monthSelect.classList.add('hidden');
            btnYearly.classList.add('bg-white', 'shadow-sm', 'text-blue-600');
            btnYearly.classList.remove('text-slate-400');
            btnMonthly.classList.remove('bg-white', 'shadow-sm', 'text-blue-600');
            btnMonthly.classList.add('text-slate-400');
        } else {
            monthSelect.classList.remove('hidden');
            btnMonthly.classList.add('bg-white', 'shadow-sm', 'text-blue-600');
            btnMonthly.classList.remove('text-slate-400');
            btnYearly.classList.remove('bg-white', 'shadow-sm', 'text-blue-600');
            btnYearly.classList.add('text-slate-400');
        }
    }
</script>
@endsection
