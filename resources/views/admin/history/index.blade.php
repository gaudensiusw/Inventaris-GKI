@extends('layouts.master')

@section('content')
<div class="flex flex-col gap-12">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Riwayat Aktivitas</h1>
            <p class="text-slate-500 text-sm mt-1">Lacak semua aktivitas peminjaman dan perbaikan yang telah selesai</p>
        </div>
    </div>

    <!-- Borrowing History -->
    <div class="flex flex-col gap-6">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                <i data-lucide="undo-2" class="w-5 h-5"></i>
            </div>
            <h2 class="text-xl font-black text-slate-800 tracking-tight">Riwayat Pengembalian</h2>
        </div>

        <div class="card-premium p-0 border-none overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Barang</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Peminjam</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tgl Pinjam</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tgl Kembali</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($borrowHistory as $history)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center">
                                        <i data-lucide="package" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-700">{{ $history->item->name ?? 'Barang Dihapus' }}</p>
                                        <p class="text-[10px] text-slate-400">{{ $history->item->kode_aset ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-slate-600">{{ $history->peminjam }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs text-slate-500">{{ $history->tgl_pinjam ? \Carbon\Carbon::parse($history->tgl_pinjam)->format('d/m/Y') : '-' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs font-bold text-emerald-600">{{ $history->tgl_kembali_aktual ? \Carbon\Carbon::parse($history->tgl_kembali_aktual)->format('d/m/Y') : '-' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[10px] font-black uppercase">Berhasil Kembali</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-400 text-sm">Belum ada riwayat pengembalian.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4">
            {{ $borrowHistory->links() }}
        </div>
    </div>

    <!-- Repair History -->
    <div class="flex flex-col gap-6">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center">
                <i data-lucide="wrench" class="w-5 h-5"></i>
            </div>
            <h2 class="text-xl font-black text-slate-800 tracking-tight">Riwayat Perbaikan</h2>
        </div>

        <div class="card-premium p-0 border-none overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Barang</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Vendor/Tempat</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tgl Selesai</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($repairHistory as $history)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-orange-50 text-orange-600 rounded-lg flex items-center justify-center">
                                        <i data-lucide="package" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-700">{{ $history->item->name ?? 'Barang Dihapus' }}</p>
                                        <p class="text-[10px] text-slate-400">{{ $history->item->kode_aset ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-slate-600">{{ $history->vendor_name ?: '-' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs font-bold text-emerald-600">{{ $history->updated_at ? \Carbon\Carbon::parse($history->updated_at)->format('d/m/Y') : '-' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs text-slate-500 line-clamp-1">{{ $history->keterangan ?: '-' }}</p>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-slate-400 text-sm">Belum ada riwayat perbaikan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4">
            {{ $repairHistory->links() }}
        </div>
    </div>
</div>
@endsection
