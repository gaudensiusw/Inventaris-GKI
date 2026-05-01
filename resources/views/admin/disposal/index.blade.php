@extends('layouts.master')

@section('content')
<div class="flex flex-col gap-8">
    <!-- Header Section -->
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Penghapusan Barang</h1>
            <p class="text-slate-500 text-sm mt-1">Daftar barang yang telah dikeluarkan dari sistem inventaris.</p>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card-premium shadow-card p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Barang</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tanggal Dihapus</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kategori & Lokasi</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($disposals as $disposal)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-700">{{ $disposal->name }}</span>
                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $disposal->kode_aset }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <p class="text-sm text-slate-600 font-medium">{{ \Carbon\Carbon::parse($disposal->deleted_at)->format('d M Y H:i') }}</p>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex flex-col gap-1">
                                <span class="text-xs font-bold text-slate-700">{{ $disposal->category->name ?? '-' }}</span>
                                <span class="text-[10px] text-slate-500">{{ $disposal->room->name ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <form action="{{ route('disposal.restore', $disposal->id) }}" method="POST" class="inline-block" onsubmit="return confirmSubmit(this, { title: 'Pulihkan Barang?', message: 'Barang ini akan dikembalikan ke daftar inventaris aktif.', color: 'emerald', icon: 'refresh-ccw' })">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-emerald-50 text-emerald-600 rounded-xl hover:bg-emerald-500 hover:text-white transition-all shadow-sm shadow-emerald-100 flex items-center gap-2 text-xs font-bold uppercase">
                                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                                    Restore
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center gap-3 text-slate-300">
                                <i data-lucide="trash-2" class="w-12 h-12"></i>
                                <p class="text-sm font-bold uppercase tracking-widest">Belum ada data penghapusan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($disposals->hasPages())
        <div class="p-6 border-t border-slate-100 flex justify-center">
            {{ $disposals->links() }}
        </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($disposals->hasPages() || $disposals->total() > 10)
    <div class="flex justify-between items-center bg-white p-6 rounded-[32px] shadow-sm border border-slate-100">
        <div class="flex items-center gap-4">
            <span class="text-xs font-bold text-slate-400">Tampilkan</span>
            <select onchange="window.location.href = addQueryParam('per_page', this.value)" class="px-3 py-1.5 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold text-slate-600 focus:outline-none">
                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
            </select>
        </div>
        {{ $disposals->links() }}
    </div>
    @endif
</div>

<script>
    function addQueryParam(key, value) {
        const url = new URL(window.location.href);
        url.searchParams.set(key, value);
        return url.toString();
    }
</script>
@endsection
