@extends('layouts.user')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Back Link -->
    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-blue-600 transition-colors font-semibold">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Kembali ke Daftar Barang
    </a>

    <!-- Page Header -->
    <div>
        <h1 class="text-2xl font-black text-slate-800">Ajukan Peminjaman</h1>
        <p class="text-sm text-slate-500 mt-1">Lengkapi form berikut untuk mengajukan peminjaman barang</p>
    </div>

    <!-- Item Info Card -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
        <div class="flex items-start gap-4">
            <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center shrink-0">
                <i data-lucide="package" class="w-7 h-7 text-blue-600"></i>
            </div>
            <div>
                <h3 class="text-base font-black text-slate-800">{{ $item->name }}</h3>
                <p class="text-xs text-slate-400 font-mono mt-0.5">{{ $item->kode_aset }}</p>
                <div class="flex flex-wrap gap-2 mt-3">
                    <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-[10px] font-bold">{{ $item->category->name ?? '-' }}</span>
                    <span class="px-3 py-1 bg-slate-50 text-slate-500 rounded-full text-[10px] font-bold">{{ $item->room->name ?? '-' }}</span>
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-bold">Tersedia: {{ $item->qty_tersedia }} unit</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Request Form -->
    <form method="POST" action="{{ route('user.orders.store') }}" class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 space-y-5">
        @csrf
        <input type="hidden" name="id_barang" value="{{ $item->id }}">

        <!-- Identitas Peminjam -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-2">Nama Peminjam *</label>
                <input type="text" name="nama_peminjam" value="{{ old('nama_peminjam') }}" required placeholder="Nama lengkap Anda"
                    class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all @error('nama_peminjam') border-red-400 @enderror">
                @error('nama_peminjam') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-2">No. HP / Email *</label>
                <input type="text" name="kontak_peminjam" value="{{ old('kontak_peminjam') }}" required placeholder="081234567890 atau email"
                    class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all @error('kontak_peminjam') border-red-400 @enderror">
                @error('kontak_peminjam') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-500 mb-2">Jumlah yang Dipinjam *</label>
            <input type="number" name="qty" value="{{ old('qty', 1) }}" min="1" max="{{ $item->qty_tersedia }}"
                class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all @error('qty') border-red-400 @enderror">
            <p class="text-[11px] text-slate-400 mt-1">Maksimal: {{ $item->qty_tersedia }} unit</p>
            @error('qty') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-2">Tanggal Pinjam *</label>
                <input type="date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}"
                    class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all @error('start_date') border-red-400 @enderror">
                @error('start_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-2">Tanggal Kembali *</label>
                <input type="date" name="end_date" value="{{ old('end_date') }}" min="{{ date('Y-m-d') }}"
                    class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all @error('end_date') border-red-400 @enderror">
                @error('end_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-500 mb-2">Keperluan / Alasan Peminjaman *</label>
            <textarea name="reason" rows="3" placeholder="Jelaskan keperluan peminjaman barang ini..."
                class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all resize-none @error('reason') border-red-400 @enderror">{{ old('reason') }}</textarea>
            @error('reason') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-500 mb-2">Catatan Tambahan</label>
            <textarea name="catatan" rows="2" placeholder="Catatan tambahan (opsional)..."
                class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all resize-none">{{ old('catatan') }}</textarea>
        </div>

        <div class="pt-2 flex gap-3">
            <a href="{{ route('user.katalog.index') }}" class="px-6 py-3.5 bg-slate-100 text-slate-600 rounded-2xl text-sm font-bold hover:bg-slate-200 transition-all">
                Batal
            </a>
            <button type="submit" class="flex-1 px-6 py-3.5 bg-blue-600 text-white rounded-2xl text-sm font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all">
                <span class="flex items-center justify-center gap-2">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    Ajukan Peminjaman
                </span>
            </button>
        </div>
    </form>
</div>
@endsection
