@extends('layouts.master')

@section('content')
<div class="flex flex-col gap-8">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Barang Dipinjam</h1>
            <p class="text-slate-500 text-sm mt-1">Kelola peminjaman barang dan pantau status pengembalian</p>
        </div>
        <button onclick="openModal('addBorrowModal')" class="px-6 py-3 bg-blue-600 text-white rounded-2xl text-xs font-black uppercase shadow-xl shadow-blue-200 hover:bg-blue-700 transition-all flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Tambah Peminjaman
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="card-premium p-6 border-none flex items-center justify-between group">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Aktif</p>
                <h3 class="text-2xl font-black text-slate-800">{{ $stats['total_rows'] }}</h3>
                <p class="text-[10px] text-blue-500 font-bold mt-1">Transaksi</p>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center">
                <i data-lucide="clock" class="w-6 h-6"></i>
            </div>
        </div>
        <div class="card-premium p-6 border-none flex items-center justify-between group">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Unit</p>
                <h3 class="text-2xl font-black text-emerald-600">{{ $borrowings->sum('qty') }}</h3>
                <p class="text-[10px] text-emerald-500 font-bold mt-1">Unit Fisik</p>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center">
                <i data-lucide="package" class="w-6 h-6"></i>
            </div>
        </div>
        <div class="card-premium p-6 border-none flex items-center justify-between group">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Hampir Deadline</p>
                <h3 class="text-2xl font-black text-orange-500">{{ $stats['coming_soon'] }}</h3>
                <p class="text-[10px] text-orange-500 font-bold mt-1">Deadline < 3 hari</p>
            </div>
            <div class="w-12 h-12 bg-orange-50 text-orange-500 rounded-2xl flex items-center justify-center">
                <i data-lucide="alert-circle" class="w-6 h-6"></i>
            </div>
        </div>
        <div class="card-premium p-6 border-none flex items-center justify-between group">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Terlambat</p>
                <h3 class="text-2xl font-black text-red-500">{{ $stats['late'] }}</h3>
                <p class="text-[10px] text-red-500 font-bold mt-1">Butuh Tindakan</p>
            </div>
            <div class="w-12 h-12 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center">
                <i data-lucide="x-circle" class="w-6 h-6"></i>
            </div>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="flex flex-col md:flex-row gap-4 items-center justify-between bg-white p-4 rounded-3xl shadow-sm border border-slate-100">
        <form action="{{ route('borrowing.index') }}" method="GET" class="relative w-full md:w-96">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari peminjam atau nama barang..." 
                class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all text-sm font-medium">
            <i data-lucide="search" class="absolute left-4 top-3.5 w-5 h-5 text-slate-400"></i>
        </form>
    </div>

    <!-- Main Table -->
    <div class="card-premium p-0 border-none overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Peminjam</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Barang</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Jumlah</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Jadwal Pinjam</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Deadline</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($borrowings as $borrow)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-700">{{ $borrow->peminjam }}</span>
                                <span class="text-[10px] text-slate-400 uppercase font-black tracking-tighter">{{ $borrow->komisi_terkait ?: '-' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center">
                                    <i data-lucide="package" class="w-4 h-4"></i>
                                </div>
                                <span class="text-sm font-medium text-slate-600">{{ $borrow->item->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-xs font-black">{{ $borrow->qty }} Unit</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs text-slate-500 font-medium">{{ \Carbon\Carbon::parse($borrow->tgl_pinjam)->format('d M Y') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $deadline = \Carbon\Carbon::parse($borrow->tgl_kembali_rencana);
                                $isOverdue = $deadline->isPast();
                            @endphp
                            <span class="text-xs font-bold {{ $isOverdue ? 'text-red-500' : 'text-emerald-500' }}">
                                {{ $deadline->format('d M Y') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('borrowing.return', $borrow->id_pinjam) }}" method="POST" onsubmit="return confirmSubmit(this, { title: 'Kembalikan Barang?', message: 'Pastikan barang telah diterima kembali dalam kondisi baik!', color: 'emerald', icon: 'undo-2' })">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-emerald-50 text-emerald-600 rounded-xl text-[10px] font-black uppercase hover:bg-emerald-600 hover:text-white transition-all">
                                    Tandai Selesai
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                            <div class="flex flex-col items-center gap-2">
                                <i data-lucide="inbox" class="w-10 h-10 opacity-20"></i>
                                <p class="text-sm font-medium">Tidak ada data peminjaman aktif.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($borrowings->hasPages())
        <div class="px-6 py-4 border-t border-slate-50">
            {{ $borrowings->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal Tambah Peminjaman -->
<div id="addBorrowModal" class="modal-backdrop hidden flex items-center justify-center z-[100]">
    <div class="bg-white rounded-[40px] w-full max-w-2xl overflow-hidden shadow-2xl transform transition-all scale-95 opacity-0 duration-300" id="addBorrowModalContent">
        <div class="px-8 py-6 bg-slate-50 border-b border-slate-100 flex justify-between items-center">
            <h2 class="text-xl font-black text-slate-800 tracking-tight">Catat Peminjaman Baru</h2>
            <button onclick="closeModal('addBorrowModal')" class="p-2 hover:bg-white rounded-xl transition-all">
                <i data-lucide="x" class="w-5 h-5 text-slate-400"></i>
            </button>
        </div>
        <form action="{{ route('borrowing.store') }}" method="POST" class="p-8 max-h-[70vh] overflow-y-auto custom-scrollbar">
            @csrf
            <div class="flex flex-col gap-6">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-500 ml-1">Pilih Barang <span class="text-red-500">*</span></label>
                    <select name="id_barang" required onchange="updateStockLabel(this)"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all text-sm font-medium">
                        <option value="">Pilih Barang</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}" data-stock="{{ $item->qty_tersedia }}">{{ $item->name }} (Tersedia: {{ $item->qty_tersedia }})</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-slate-500 ml-1">Nama Peminjam <span class="text-red-500">*</span></label>
                        <input type="text" name="peminjam" required placeholder="Contoh: Bpk. Andi"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all text-sm font-medium">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-slate-500 ml-1">Jumlah Pinjam <span class="text-red-500">*</span></label>
                        <input type="number" name="qty" required min="1" value="1" id="borrow_qty"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all text-sm font-bold text-blue-600">
                    </div>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-500 ml-1">Komisi/Kepanitiaan</label>
                    <input type="text" name="komisi_terkait" placeholder="Contoh: Panitia Paskah"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all text-sm font-medium">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-slate-500 ml-1">Tanggal Pinjam <span class="text-red-500">*</span></label>
                        <input type="date" name="tgl_pinjam" required value="{{ date('Y-m-d') }}"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all text-sm font-medium">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-slate-500 ml-1">Rencana Kembali <span class="text-red-500">*</span></label>
                        <input type="date" name="tgl_kembali_rencana" required value="{{ date('Y-m-d', strtotime('+3 days')) }}"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all text-sm font-medium">
                    </div>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-500 ml-1">Catatan</label>
                    <textarea name="catatan" rows="3" placeholder="Tambahkan catatan tambahan..." 
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all text-sm font-medium"></textarea>
                </div>
            </div>

            <div class="mt-8 flex gap-3">
                <button type="button" onclick="closeModal('addBorrowModal')" class="flex-1 px-6 py-4 bg-slate-100 text-slate-600 rounded-2xl text-xs font-black uppercase hover:bg-slate-200 transition-all">Batal</button>
                <button type="submit" class="flex-[2] px-6 py-4 bg-blue-600 text-white rounded-2xl text-xs font-black uppercase shadow-xl shadow-blue-200 hover:bg-blue-700 transition-all">Simpan Peminjaman</button>
            </div>
        </form>
    </div>
</div>

<script>
    function updateStockLabel(select) {
        const option = select.options[select.selectedIndex];
        const stock = option.getAttribute('data-stock');
        const qtyInput = document.getElementById('borrow_qty');
        if (stock) {
            qtyInput.max = stock;
        }
    }

    function openModal(id) {
        const modal = document.getElementById(id);
        const content = document.getElementById(id + 'Content');
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        const content = document.getElementById(id + 'Content');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // Auto-open if errors
    @if ($errors->any())
        window.addEventListener('DOMContentLoaded', (event) => {
            openModal('addBorrowModal');
        });
    @endif
</script>
@endsection
