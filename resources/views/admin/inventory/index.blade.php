@extends('layouts.master')

@section('content')
<div class="flex flex-col gap-8">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Inventaris</h1>
            <p class="text-slate-500 text-sm mt-1">{{ $items->total() }} barang ditemukan</p>
        </div>
        <button onclick="openModal('addItemModal')" class="w-full sm:w-auto px-6 py-3 bg-blue-600 text-white rounded-2xl text-sm font-black uppercase shadow-xl shadow-blue-200 hover:bg-blue-700 hover:scale-105 active:scale-95 transition-all flex items-center justify-center gap-3">
            <i data-lucide="plus-circle" class="w-6 h-6"></i>
            <span>Tambah Barang Baru</span>
        </button>
    </div>

    <!-- Notifications & Validation Errors -->
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-100 text-emerald-600 px-6 py-4 rounded-2xl flex items-center gap-3 animate-pulse">
        <i data-lucide="check-circle" class="w-5 h-5"></i>
        <span class="text-sm font-bold">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-100 text-red-600 px-6 py-4 rounded-2xl flex items-center gap-3">
        <i data-lucide="alert-circle" class="w-5 h-5"></i>
        <span class="text-sm font-bold">{{ session('error') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-amber-50 border border-amber-100 text-amber-700 px-6 py-4 rounded-2xl flex flex-col gap-2">
        <div class="flex items-center gap-3">
            <i data-lucide="alert-triangle" class="w-5 h-5"></i>
            <span class="text-sm font-bold">Harap perbaiki kesalahan berikut:</span>
        </div>
        <ul class="list-disc ml-10 text-xs font-medium">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Search & Filter Bar -->
    <div class="flex flex-wrap gap-4 items-center">
        <div class="flex-1 min-w-[300px] relative group">
            <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
            <input type="text" id="search-input" value="{{ $search }}" placeholder="Cari nama barang, ID, atau deskripsi..." 
                class="w-full pl-12 pr-4 py-3 bg-white border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all shadow-sm">
        </div>
        <div class="flex gap-2">
            <select onchange="window.location.href = addQueryParam('category_id', this.value)" class="px-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-semibold text-slate-700 hover:bg-slate-50 focus:outline-none transition-all shadow-sm min-w-[150px]">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <a href="{{ route('inventory.export') }}" class="px-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-semibold text-slate-700 hover:bg-slate-50 flex items-center gap-2 transition-all shadow-sm">
                <i data-lucide="download" class="w-4 h-4"></i>
                <span>Export</span>
            </a>
            <button onclick="window.print()" class="px-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-semibold text-slate-700 hover:bg-slate-50 flex items-center gap-2 transition-all shadow-sm">
                <i data-lucide="printer" class="w-4 h-4"></i>
                <span>Print</span>
            </button>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card-premium shadow-card p-0 overflow-hidden print:shadow-none print:border-none">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">ID / Nama</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kategori</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kondisi</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Lokasi</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Jumlah</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Harga</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right print:hidden">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($items as $item)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <i data-lucide="{{ $item->category->icon ?? 'package' }}" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-800">{{ $item->name }}</h4>
                                    <p class="text-xs text-slate-400 font-medium tracking-tight">{{ $item->kode_aset }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-[10px] font-bold uppercase tracking-tight">
                                {{ $item->category->name }}
                            </span>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex flex-wrap gap-1.5">
                                @if($item->qty_baik > 0)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-green-50 text-green-600 rounded-md text-[10px] font-bold">
                                        <i data-lucide="check" class="w-3 h-3"></i> {{ $item->qty_baik }} Baik
                                    </span>
                                @endif
                                @if($item->qty_rusak_ringan > 0)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-amber-50 text-amber-600 rounded-md text-[10px] font-bold">
                                        <i data-lucide="alert-triangle" class="w-3 h-3"></i> {{ $item->qty_rusak_ringan }} Rusak
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <p class="text-sm text-slate-600 font-medium">{{ $item->room->name }}</p>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex flex-col gap-1">
                                @php
                                    $calculatedTotal = $item->qty_baik + $item->qty_rusak_ringan + $item->qty_rusak_berat;
                                @endphp
                                <span class="text-sm font-bold text-slate-800">Total: {{ $calculatedTotal }}</span>
                                <div class="flex gap-2">
                                    <span class="text-[10px] font-bold text-blue-500 inline-flex items-center gap-1">
                                        <i data-lucide="box" class="w-3 h-3"></i> {{ $item->qty_tersedia }} Tersedia
                                    </span>
                                    @if($item->qty_dipinjam > 0)
                                        <span class="text-[10px] font-bold text-purple-500 inline-flex items-center gap-1">
                                            <i data-lucide="undo-2" class="w-3 h-3"></i> {{ $item->qty_dipinjam }} Dipinjam
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <p class="text-sm font-bold text-slate-700">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        </td>
                        <td class="px-6 py-5 text-right print:hidden">
                            <div class="flex justify-end gap-1">
                                <button onclick="viewItem({{ json_encode($item) }})" class="p-2 hover:bg-blue-50 text-blue-500 rounded-lg transition-colors"><i data-lucide="eye" class="w-4 h-4"></i></button>
                                <button onclick="editItem({{ json_encode($item) }})" class="p-2 hover:bg-slate-100 text-slate-400 rounded-lg transition-colors"><i data-lucide="edit" class="w-4 h-4"></i></button>
                                <form action="{{ route('inventory.destroy', $item->id) }}" method="POST" onsubmit="return confirmSubmit(this, { title: 'Hapus Barang?', message: 'Barang akan dipindahkan ke daftar penghapusan dan tidak tampil di inventaris aktif.', color: 'red', icon: 'trash-2' })">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-red-50 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all shadow-sm shadow-red-100">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <i data-lucide="inbox" class="w-12 h-12 text-slate-200"></i>
                                <p class="text-slate-400 font-medium">Belum ada barang di inventaris.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination & Per Page -->
    @if($items->hasPages() || $items->total() > 10)
    <div class="flex justify-between items-center bg-white p-6 rounded-[32px] shadow-sm border border-slate-100 mt-8 print:hidden">
        <div class="flex items-center gap-4">
            <span class="text-xs font-bold text-slate-400">Tampilkan</span>
            <select onchange="window.location.href = addQueryParam('per_page', this.value)" class="px-3 py-1.5 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold text-slate-600 focus:outline-none">
                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
            </select>
        </div>
        {{ $items->links() }}
    </div>
    @endif
</div>

<script>
    function addQueryParam(key, value) {
        const url = new URL(window.location.href);
        url.searchParams.set(key, value);
        return url.toString();
    }
    document.getElementById('search-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') window.location.href = addQueryParam('search', this.value);
    });
</script>
@endsection

@push('modals')
<!-- Modal Tambah Barang -->
<div id="addItemModal" class="fixed inset-0 z-[100] {{ $errors->any() && !session('is_edit') ? '' : 'hidden' }} overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('addItemModal')"></div>
    
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-2xl bg-white rounded-[32px] shadow-2xl shadow-slate-200/50 transform transition-all overflow-hidden border border-slate-100">
            <!-- Modal Header -->
            <div class="px-8 py-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-200">
                        <i data-lucide="plus" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-slate-800 tracking-tight">Tambah Barang Baru</h2>
                        <p class="text-xs text-slate-500 font-medium">Masukkan detail barang inventaris secara lengkap.</p>
                    </div>
                </div>
                <button onclick="closeModal('addItemModal')" class="p-2 hover:bg-slate-200 rounded-xl transition-all text-slate-400"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>

            <form action="{{ route('inventory.store') }}" method="POST" class="p-8 max-h-[70vh] overflow-y-auto custom-scrollbar">
                @csrf
                @include('admin.inventory._form_fields', ['isEdit' => false])

                <!-- Form Footer -->
                <div class="mt-8 pt-6 border-t border-slate-50 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('addItemModal')" class="px-6 py-3 bg-slate-100 text-slate-500 rounded-2xl text-xs font-black uppercase hover:bg-slate-200 transition-all">Batal</button>
                    <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-2xl text-xs font-black uppercase shadow-xl shadow-blue-200 hover:bg-blue-700 transition-all flex items-center gap-2">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        <span>Simpan Barang</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Barang -->
<div id="editItemModal" class="fixed inset-0 z-[100] {{ session('is_edit') ? '' : 'hidden' }} overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('editItemModal')"></div>
    
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-2xl bg-white rounded-[32px] shadow-2xl shadow-slate-200/50 transform transition-all overflow-hidden border border-slate-100">
            <!-- Modal Header -->
            <div class="px-8 py-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-amber-200">
                        <i data-lucide="edit" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-slate-800 tracking-tight">Edit Barang</h2>
                        <p class="text-xs text-slate-500 font-medium">Ubah detail barang inventaris.</p>
                    </div>
                </div>
                <button onclick="closeModal('editItemModal')" class="p-2 hover:bg-slate-200 rounded-xl transition-all text-slate-400"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>

            <form id="editForm" action="" method="POST" class="p-8 max-h-[70vh] overflow-y-auto custom-scrollbar">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_item_id" name="item_id" value="{{ old('item_id', session('edit_item_id')) }}">
                @include('admin.inventory._form_fields', ['isEdit' => true])

                <!-- Form Footer -->
                <div class="mt-8 pt-6 border-t border-slate-50 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('editItemModal')" class="px-6 py-3 bg-slate-100 text-slate-500 rounded-2xl text-xs font-black uppercase hover:bg-slate-200 transition-all">Batal</button>
                    <button type="submit" class="px-8 py-3 bg-amber-600 text-white rounded-2xl text-xs font-black uppercase shadow-xl shadow-amber-200 hover:bg-amber-700 transition-all flex items-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal View Barang -->
<div id="viewItemModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('viewItemModal')"></div>
    
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-2xl bg-white rounded-[32px] shadow-2xl shadow-slate-200/50 transform transition-all overflow-hidden border border-slate-100 flex flex-col">
            <!-- Top Blue Header -->
            <div class="bg-blue-600 p-8 flex items-center justify-between">
                <div class="flex items-center gap-6">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-md">
                        <i data-lucide="package" class="w-8 h-8 text-white"></i>
                    </div>
                    <div>
                        <h2 id="view_name" class="text-2xl font-black text-white">Nama Barang</h2>
                        <p id="view_kode" class="text-blue-100 mt-1 font-medium">KODE-ASET</p>
                    </div>
                </div>
                <div>
                    <span id="view_tersedia" class="px-4 py-2 bg-white/20 text-white rounded-full text-sm font-bold backdrop-blur-md shadow-sm">
                        Tersedia: 0
                    </span>
                </div>
            </div>

            <!-- Details Grid -->
            <div class="p-8 max-h-[60vh] overflow-y-auto custom-scrollbar">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-10 gap-x-12">
                    <!-- Kategori -->
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center shrink-0">
                            <i data-lucide="box" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Kategori</p>
                            <p id="view_kategori" class="text-slate-800 font-semibold mt-1">-</p>
                        </div>
                    </div>

                    <!-- Lokasi -->
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center shrink-0">
                            <i data-lucide="map-pin" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Lokasi</p>
                            <p id="view_lokasi" class="text-slate-800 font-semibold mt-1">-</p>
                        </div>
                    </div>

                    <!-- Jumlah -->
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-500 flex items-center justify-center shrink-0">
                            <i data-lucide="hash" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Jumlah</p>
                            <p id="view_total" class="text-slate-800 font-semibold mt-1">0 Unit</p>
                        </div>
                    </div>

                    <!-- Tanggal Pembelian -->
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-500 flex items-center justify-center shrink-0">
                            <i data-lucide="calendar" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Tanggal Pembelian</p>
                            <p id="view_tanggal" class="text-slate-800 font-semibold mt-1">-</p>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="flex items-start gap-4 col-span-1 md:col-span-2">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center shrink-0">
                            <i data-lucide="file-text" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Deskripsi</p>
                            <p id="view_deskripsi" class="text-slate-700 mt-1 leading-relaxed">-</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Actions -->
            <div class="bg-slate-50 p-6 flex justify-end gap-3 border-t border-slate-100">
                <button type="button" onclick="closeModal('viewItemModal')" class="px-6 py-3 bg-slate-100 text-slate-500 rounded-xl text-sm font-bold hover:bg-slate-200 transition-all">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function viewItem(item) {
        // Populate view modal
        document.getElementById('view_name').innerText = item.name || '-';
        document.getElementById('view_kode').innerText = item.kode_aset || '-';
        document.getElementById('view_tersedia').innerText = 'Tersedia: ' + (item.qty_tersedia || 0);
        
        document.getElementById('view_kategori').innerText = (item.category && item.category.name) ? item.category.name : '-';
        document.getElementById('view_lokasi').innerText = (item.room && item.room.name) ? item.room.name : '-';
        
        const totalQty = (item.qty_baik || 0) + (item.qty_rusak_ringan || 0) + (item.qty_rusak_berat || 0);
        document.getElementById('view_total').innerText = totalQty + ' Unit';
        
        document.getElementById('view_tanggal').innerText = item.purchase_date || '-';
        document.getElementById('view_deskripsi').innerText = item.description || 'Tidak ada deskripsi';
        
        openModal('viewItemModal');
    }

    function editItem(item) {
        const form = document.getElementById('editForm');
        form.action = `/admin/inventory/${item.id}`;
        
        // Fill form fields
        form.querySelector('[name="name"]').value = item.name;
        form.querySelector('[name="category_id"]').value = item.category_id;
        form.querySelector('[name="room_id"]').value = item.room_id;
        form.querySelector('[name="quantity"]').value = item.quantity;
        form.querySelector('[name="qty_baik"]').value = item.qty_baik;
        form.querySelector('[name="qty_rusak_ringan"]').value = item.qty_rusak_ringan;
        form.querySelector('[name="qty_rusak_berat"]').value = item.qty_rusak_berat;
        form.querySelector('[name="qty_tersedia"]').value = item.qty_tersedia;
        form.querySelector('[name="qty_dipinjam"]').value = item.qty_dipinjam;
        form.querySelector('[name="qty_diperbaiki"]').value = item.qty_diperbaiki;
        form.querySelector('[name="qty_hilang"]').value = item.qty_hilang;
        form.querySelector('[name="qty_tidak_digunakan"]').value = item.qty_tidak_digunakan;
        form.querySelector('[name="qty_pengadaan"]').value = item.qty_pengadaan;
        form.querySelector('[name="price"]').value = item.price;
        form.querySelector('[name="purchase_date"]').value = item.purchase_date;
        form.querySelector('[name="description"]').value = item.description || '';

        openModal('editItemModal');
    }

    // Auto-open modal if there are validation errors
    window.addEventListener('DOMContentLoaded', (event) => {
        @if ($errors->any())
            @if(session('is_edit'))
                const editId = document.getElementById('edit_item_id').value;
                if(editId) document.getElementById('editForm').action = `/admin/inventory/${editId}`;
                openModal('editItemModal');
            @else
                openModal('addItemModal');
            @endif
        @endif
    });
</script>
@endpush
