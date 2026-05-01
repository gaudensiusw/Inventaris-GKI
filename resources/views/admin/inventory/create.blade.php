@extends('layouts.master')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Breadcrumb & Title -->
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('inventory.index') }}" class="p-2 hover:bg-white rounded-xl transition-colors border border-transparent hover:border-slate-100">
            <i data-lucide="arrow-left" class="w-6 h-6 text-slate-400"></i>
        </a>
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Tambah Barang Baru</h1>
            <p class="text-slate-500 text-sm mt-1">Masukkan detail barang inventaris dengan lengkap</p>
        </div>
    </div>

    <form action="{{ route('inventory.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- Informasi Dasar Card -->
        <div class="card-premium p-8 space-y-6">
            <div class="flex items-center gap-3 pb-4 border-b border-slate-50">
                <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center font-bold text-xs italic">i</div>
                <h3 class="font-bold text-slate-700">Informasi Dasar</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Barang <span class="text-red-500">*</span></label>
                    <input type="text" name="name" placeholder="Contoh: Proyektor Epson EB-X41" 
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kategori Utama <span class="text-red-500">*</span></label>
                    <select name="category_id" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all appearance-none">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Lokasi dan Jumlah Card -->
        <div class="card-premium p-8 space-y-8">
            <div class="flex items-center gap-3 pb-4 border-b border-slate-50">
                <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center">
                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                </div>
                <h3 class="font-bold text-slate-700">Lokasi dan Jumlah</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Lokasi Penyimpanan <span class="text-red-500">*</span></label>
                    <select name="room_id" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all appearance-none">
                        <option value="">Pilih Lokasi</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}">{{ $room->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Jumlah Barang <span class="text-red-500">*</span></label>
                    <input type="number" id="total_qty" name="total_qty" value="1" min="1"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all font-bold text-lg">
                </div>
            </div>

            <!-- Breakdown Kondisi -->
            <div class="space-y-4 pt-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <i data-lucide="info" class="w-4 h-4 text-blue-500"></i>
                        <h4 class="text-sm font-bold text-slate-700">Breakdown Kondisi Fisik</h4>
                    </div>
                    <span class="px-2 py-0.5 bg-red-50 text-red-500 text-[10px] font-bold rounded-md">0 / <span class="target-qty">1</span></span>
                </div>
                <p class="text-[11px] text-slate-400">Distribusi barang berdasarkan kondisi fisik. Total harus sama dengan jumlah barang.</p>
                <div class="grid grid-cols-3 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-500 uppercase">Baik</label>
                        <input type="number" name="qty_baik" value="0" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:border-blue-400 outline-none text-sm font-semibold">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-500 uppercase">Rusak Ringan</label>
                        <input type="number" name="qty_rusak_ringan" value="0" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:border-blue-400 outline-none text-sm font-semibold">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-500 uppercase">Rusak Berat</label>
                        <input type="number" name="qty_rusak_berat" value="0" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:border-blue-400 outline-none text-sm font-semibold">
                    </div>
                </div>
            </div>

            <!-- Breakdown Status -->
            <div class="space-y-4 pt-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <i data-lucide="info" class="w-4 h-4 text-blue-500"></i>
                        <h4 class="text-sm font-bold text-slate-700">Breakdown Status Operasional</h4>
                    </div>
                    <span class="px-2 py-0.5 bg-red-50 text-red-500 text-[10px] font-bold rounded-md">0 / <span class="target-qty">1</span></span>
                </div>
                <p class="text-[11px] text-slate-400">Distribusi barang berdasarkan status operasional. Total harus sama dengan jumlah barang.</p>
                <div class="grid grid-cols-3 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-500 uppercase">Tersedia</label>
                        <input type="number" id="qty_tersedia" name="qty_tersedia" value="0" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:border-blue-400 outline-none text-sm font-semibold">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-500 uppercase">Dipinjam</label>
                        <input type="number" name="qty_dipinjam" value="0" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:border-blue-400 outline-none text-sm font-semibold">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-500 uppercase">Sedang Diperbaiki</label>
                        <input type="number" name="qty_perbaikan" value="0" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:border-blue-400 outline-none text-sm font-semibold">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-500 uppercase">Hilang</label>
                        <input type="number" name="qty_hilang" value="0" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:border-blue-400 outline-none text-sm font-semibold">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-500 uppercase">Tidak Digunakan</label>
                        <input type="number" name="qty_tidak_digunakan" value="0" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:border-blue-400 outline-none text-sm font-semibold">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-500 uppercase">Dalam Pengadaan</label>
                        <input type="number" name="qty_pengadaan" value="0" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:border-blue-400 outline-none text-sm font-semibold">
                    </div>
                </div>
                
                <!-- Auto-fill Tip -->
                <div class="p-4 bg-blue-50/50 rounded-xl border border-blue-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="text-lg">💡</span>
                        <p class="text-[11px] text-blue-700 font-medium leading-relaxed">
                            Tip: Isi status lainnya terlebih dahulu, lalu klik tombol ini untuk mengisi "Tersedia" secara otomatis.
                        </p>
                    </div>
                    <button type="button" onclick="autoFillTersedia()" class="px-3 py-1.5 bg-blue-600 text-white text-[11px] font-bold rounded-lg hover:bg-blue-700 transition-colors">
                        Auto-fill Tersedia
                    </button>
                </div>
            </div>
        </div>

        <!-- Informasi Keuangan Card -->
        <div class="card-premium p-8 space-y-6">
            <div class="flex items-center gap-3 pb-4 border-b border-slate-50">
                <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center">
                    <i data-lucide="dollar-sign" class="w-4 h-4"></i>
                </div>
                <h3 class="font-bold text-slate-700">Informasi Keuangan</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal Pembelian</label>
                    <input type="date" name="purchase_date" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-400">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Harga Satuan (Rp)</label>
                    <input type="number" name="price" value="0" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-400 font-bold">
                </div>
            </div>
        </div>

        <!-- Deskripsi Card -->
        <div class="card-premium p-8 space-y-6">
            <div class="flex items-center gap-3 pb-4 border-b border-slate-50">
                <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center">
                    <i data-lucide="align-left" class="w-4 h-4"></i>
                </div>
                <h3 class="font-bold text-slate-700">Deskripsi</h3>
            </div>
            <textarea name="description" rows="4" placeholder="Tambahkan catatan atau deskripsi tambahan..." 
                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-400"></textarea>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('inventory.index') }}" class="px-6 py-3 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-50 transition-colors flex items-center gap-2">
                <i data-lucide="x" class="w-4 h-4"></i> Batal
            </a>
            <button type="submit" class="btn-primary-custom flex items-center gap-2 shadow-blue-200">
                <i data-lucide="save" class="w-4 h-4"></i> Tambah Barang
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function autoFillTersedia() {
        const total = parseInt(document.getElementById('total_qty').value) || 0;
        const dipinjam = parseInt(document.getElementsByName('qty_dipinjam')[0].value) || 0;
        const perbaikan = parseInt(document.getElementsByName('qty_perbaikan')[0].value) || 0;
        const hilang = parseInt(document.getElementsByName('qty_hilang')[0].value) || 0;
        const tidakDigunakan = parseInt(document.getElementsByName('qty_tidak_digunakan')[0].value) || 0;
        const pengadaan = parseInt(document.getElementsByName('qty_pengadaan')[0].value) || 0;
        
        const otherTotal = dipinjam + perbaikan + hilang + tidakDigunakan + pengadaan;
        const tersedia = Math.max(0, total - otherTotal);
        
        document.getElementById('qty_tersedia').value = tersedia;
    }

    // Update target qty labels
    document.getElementById('total_qty').addEventListener('input', function() {
        const val = this.value || 0;
        document.querySelectorAll('.target-qty').forEach(el => el.textContent = val);
    });
</script>
@endpush
