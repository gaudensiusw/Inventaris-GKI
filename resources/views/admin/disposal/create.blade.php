@extends('layouts.master')

@section('content')
<div class="flex flex-col gap-8 max-w-4xl">
    <!-- Header Section -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Hapus Barang dari Inventaris</h1>
            <p class="text-slate-500 text-sm mt-1">Gunakan form ini untuk mencatat barang yang rusak total, dijual, atau hilang.</p>
        </div>
        <a href="{{ route('disposal.index') }}" class="px-4 py-2 text-slate-500 hover:text-slate-700 font-semibold flex items-center gap-2 transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
            <span>Kembali</span>
        </a>
    </div>

    <form action="{{ route('disposal.store') }}" method="POST">
        @csrf
        <div class="card-premium shadow-card p-8 flex flex-col gap-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Item Selection -->
                <div class="flex flex-col gap-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Pilih Barang</label>
                    <select name="item_id" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all shadow-sm font-medium text-slate-700">
                        <option value="">-- Pilih Barang --</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}">{{ $item->item_id }} - {{ $item->name }} (Stok: {{ $item->total_qty }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Date -->
                <div class="flex flex-col gap-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Tanggal Penghapusan</label>
                    <input type="date" name="disposal_date" value="{{ date('Y-m-d') }}" 
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all shadow-sm">
                </div>

                <!-- Quantity -->
                <div class="flex flex-col gap-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Jumlah Unit</label>
                    <input type="number" name="qty" placeholder="0" min="1"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all shadow-sm font-bold text-blue-600">
                </div>

                <!-- Reason -->
                <div class="flex flex-col gap-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Alasan Penghapusan</label>
                    <select name="reason" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all shadow-sm font-medium text-slate-700">
                        <option value="Rusak Total">Rusak Total</option>
                        <option value="Dijual">Dijual</option>
                        <option value="Dihibahkan">Dihibahkan</option>
                        <option value="Hilang">Hilang</option>
                    </select>
                </div>
            </div>

            <!-- Notes -->
            <div class="flex flex-col gap-2">
                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Catatan / Keterangan</label>
                <textarea name="notes" rows="4" placeholder="Detail tambahan mengenai penghapusan barang ini..." 
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all shadow-sm"></textarea>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="btn-primary-custom flex items-center gap-2 px-8 py-4">
                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                    <span>Konfirmasi Penghapusan</span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
