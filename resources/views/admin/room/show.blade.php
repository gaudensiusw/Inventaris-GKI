@extends('layouts.master')

@section('content')
<div class="flex flex-col gap-8">
    <!-- Back Link & Header -->
    <div class="flex flex-col gap-4">
        <a href="{{ route('room.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-blue-600 transition-colors font-bold w-fit">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Kembali ke Lokasi Penyimpanan
        </a>

        <!-- Room Premium Card Banner -->
        <div class="card-premium p-0 border border-slate-200 overflow-hidden relative min-h-[220px] flex flex-col justify-end shadow-card rounded-[32px] group">
            <!-- Room Image Background -->
            @if($room->getRoomImage())
                <img src="{{ $room->getRoomImage() }}" alt="{{ $room->name }}" class="absolute inset-0 w-full h-full object-cover z-0">
            @else
                <div class="absolute inset-0 bg-gradient-to-br from-slate-800 to-slate-900 z-0 flex items-center justify-center">
                    <i data-lucide="map-pin" class="w-20 h-20 text-white/5 opacity-10"></i>
                </div>
            @endif
            <!-- Dark Blur Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/60 to-transparent z-10"></div>

            <!-- Content Area inside image banner -->
            <div class="relative z-20 p-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-6 w-full">
                <div class="flex flex-col gap-2">
                    <span class="px-3.5 py-1 bg-white/20 backdrop-blur-md border border-white/20 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-full w-fit">
                        Detail Ruangan
                    </span>
                    <h1 class="text-3xl md:text-4xl font-black text-white tracking-tight">{{ $room->name }}</h1>
                    <p class="text-white/60 text-sm max-w-xl font-medium leading-relaxed">{{ $room->description ?? 'Tidak ada deskripsi lokasi' }}</p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <span class="px-4 py-2.5 bg-white/10 backdrop-blur-md text-white rounded-2xl text-xs font-bold border border-white/10">
                        Total: <b>{{ $items->count() }}</b> jenis barang
                    </span>
                    @if($allItems->count() > 0)
                    <button onclick="openModal('bulkMoveModal')" class="px-6 py-3.5 bg-amber-500 hover:bg-amber-600 text-white rounded-2xl text-xs font-black uppercase shadow-lg shadow-amber-500/20 active:scale-95 transition-all flex items-center gap-2" title="Pindahkan seluruh barang di ruangan ini ke lokasi lain secara masal">
                        <i data-lucide="shuffle" class="w-5 h-5"></i>
                        <span>Pindahkan Masal</span>
                    </button>
                    @endif
                    <button onclick="openModal('addItemModal')" class="px-6 py-3.5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-xs font-black uppercase shadow-lg shadow-blue-500/20 active:scale-95 transition-all flex items-center gap-2">
                        <i data-lucide="plus-circle" class="w-5 h-5"></i>
                        <span>Tambah Barang di Ruang Ini</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-100 text-emerald-600 px-6 py-4 rounded-2xl flex items-center gap-3">
        <i data-lucide="check-circle" class="w-5 h-5"></i>
        <span class="text-sm font-bold">{{ session('success') }}</span>
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

    <!-- Items Listing Table inside Room -->
    <div class="card-premium shadow-card p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">ID / Nama</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kategori</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kondisi</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Jumlah</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($items as $item)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl overflow-hidden flex items-center justify-center group-hover:scale-110 transition-transform shrink-0 border border-slate-100">
                                    @if($item->getItemImage())
                                        <img src="{{ $item->getItemImage() }}" class="w-full h-full object-cover" alt="{{ $item->name }}">
                                    @else
                                        <div class="w-full h-full bg-blue-50 text-blue-600 flex items-center justify-center">
                                            <i data-lucide="{{ $item->category->icon ?? 'package' }}" class="w-5 h-5"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-800">{{ $item->name }}</h4>
                                    <p class="text-xs text-slate-400 font-medium tracking-tight flex items-center gap-2">
                                        {{ $item->kode_aset }}
                                        <span class="relative group/qr cursor-pointer">
                                            <i data-lucide="qr-code" class="w-3 h-3 text-blue-400"></i>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover/qr:block z-50 animate-in fade-in zoom-in duration-200">
                                                <div class="p-2 bg-white rounded-xl shadow-2xl border border-slate-100">
                                                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(120)->generate($item->kode_aset) !!}
                                                </div>
                                            </div>
                                        </span>
                                    </p>
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
                                @if($item->qty_rusak_berat > 0)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-red-50 text-red-600 rounded-md text-[10px] font-bold">
                                        <i data-lucide="x" class="w-3 h-3"></i> {{ $item->qty_rusak_berat }} Rusak Berat
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex flex-col gap-1">
                                <span class="text-sm font-bold text-slate-800">Total: {{ $item->quantity }}</span>
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
                        <td class="px-6 py-5 text-right print:hidden">
                            <div class="flex justify-end gap-1 items-center">
                                <button onclick="printLabel({{ json_encode($item) }})" class="p-2 hover:bg-emerald-50 text-emerald-500 rounded-lg transition-colors" title="Print Label QR"><i data-lucide="printer" class="w-4 h-4"></i></button>
                                <button onclick="viewItem({{ json_encode($item) }})" class="p-2 hover:bg-blue-50 text-blue-500 rounded-lg transition-colors" title="Detail Barang"><i data-lucide="eye" class="w-4 h-4"></i></button>
                                <button onclick="editItem({{ json_encode($item) }})" class="p-2 hover:bg-slate-100 text-slate-400 rounded-lg transition-colors" title="Edit Barang"><i data-lucide="edit" class="w-4 h-4"></i></button>
                                <form action="{{ route('inventory.destroy', $item->id) }}" method="POST" onsubmit="return confirmSubmit(this, { title: 'Hapus Barang?', message: 'Barang akan dipindahkan ke daftar penghapusan dan tidak tampil di inventaris aktif.', color: 'red', icon: 'trash-2', requireReason: true })" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="redirect_to_room" value="{{ $room->id }}">
                                    <button type="submit" class="p-2 bg-red-50 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all shadow-sm shadow-red-100" title="Hapus Barang">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <i data-lucide="inbox" class="w-12 h-12 text-slate-200"></i>
                                <p class="text-slate-400 font-medium">Belum ada barang di ruangan ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('modals')
<!-- Modal Pindahkan Masal Barang -->
<div id="bulkMoveModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('bulkMoveModal')"></div>
    
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-3xl bg-white rounded-[32px] shadow-2xl transform transition-all overflow-hidden border border-slate-100 flex flex-col">
            <!-- Modal Header -->
            <div class="px-8 py-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-amber-200">
                        <i data-lucide="shuffle" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-slate-800 tracking-tight">Pindahkan Masal Barang</h2>
                        <p class="text-xs text-slate-500 font-medium">Kosongkan ruangan "{{ $room->name }}" dengan memindahkan setiap barang ke lokasi baru.</p>
                    </div>
                </div>
                <button onclick="closeModal('bulkMoveModal')" class="p-2 hover:bg-slate-200 rounded-xl transition-all text-slate-400"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>

            <form action="{{ route('room.bulk-move', $room->id) }}" method="POST" class="flex flex-col flex-1">
                @csrf
                <!-- Form Body Scrollable -->
                <div class="p-8 max-h-[60vh] overflow-y-auto custom-scrollbar flex flex-col gap-4">
                    
                    <div class="p-4 bg-amber-50 border border-amber-100 rounded-2xl flex gap-3 text-amber-800">
                        <i data-lucide="info" class="w-5 h-5 shrink-0 mt-0.5"></i>
                        <div class="flex flex-col gap-1">
                            <p class="text-xs font-bold leading-relaxed">Pilih lokasi baru untuk setiap barang di bawah ini.</p>
                            <p class="text-[10px] font-medium leading-relaxed opacity-90">Barang dengan tanda <span class="px-1.5 py-0.5 bg-red-100 text-red-600 rounded font-bold">Disposal</span> adalah barang yang berada di riwayat penghapusan. Mereka juga harus dipindahkan agar ruangan ini bersih sepenuhnya dan bisa dihapus.</p>
                        </div>
                    </div>

                    <div class="border border-slate-100 rounded-2xl overflow-hidden mt-2">
                        <table class="w-full text-left border-collapse text-xs font-medium">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-100">
                                    <th class="px-5 py-3.5 text-slate-400 font-bold uppercase tracking-wider">Nama Barang</th>
                                    <th class="px-5 py-3.5 text-slate-400 font-bold uppercase tracking-wider text-center">Status</th>
                                    <th class="px-5 py-3.5 text-slate-400 font-bold uppercase tracking-wider text-center">Jumlah</th>
                                    <th class="px-5 py-3.5 text-slate-400 font-bold uppercase tracking-wider">Lokasi Baru</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($allItems as $itemOption)
                                <tr class="hover:bg-slate-50/20 transition-colors">
                                    <td class="px-5 py-4">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-slate-800">{{ $itemOption->name }}</span>
                                            <span class="text-[10px] text-slate-400 font-bold mt-0.5">{{ $itemOption->kode_aset }}</span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        @if($itemOption->trashed())
                                            <span class="px-2 py-0.5 bg-red-100 text-red-600 rounded text-[9px] font-black uppercase tracking-wider">Disposal</span>
                                        @else
                                            <span class="px-2 py-0.5 bg-green-100 text-green-600 rounded text-[9px] font-black uppercase tracking-wider">Aktif</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-center font-bold text-slate-700">
                                        {{ $itemOption->quantity }}
                                    </td>
                                    <td class="px-5 py-4">
                                        <select name="items[{{ $itemOption->id }}]" required
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-400 transition-all font-bold text-slate-700">
                                            <option value="">-- Pilih Lokasi Baru --</option>
                                            @foreach($rooms as $targetRoomOption)
                                                @if($targetRoomOption->id != $room->id)
                                                    <option value="{{ $targetRoomOption->id }}">{{ $targetRoomOption->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Form Footer -->
                <div class="px-8 py-6 border-t border-slate-50 flex justify-end gap-3 bg-slate-50/50">
                    <button type="button" onclick="closeModal('bulkMoveModal')" class="px-6 py-3 bg-slate-100 text-slate-500 rounded-2xl text-xs font-black uppercase hover:bg-slate-200 transition-all">Batal</button>
                    <button type="submit" class="px-8 py-3 bg-amber-500 text-white rounded-2xl text-xs font-black uppercase shadow-xl shadow-amber-200 hover:bg-amber-600 transition-all flex items-center gap-2">
                        <i data-lucide="shuffle" class="w-4 h-4"></i>
                        <span>Pindahkan Semua Barang</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Barang (Spesifik Ruangan) -->
<div id="addItemModal" class="fixed inset-0 z-[100] {{ $errors->any() && !session('is_edit') ? '' : 'hidden' }} overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('addItemModal')"></div>
    
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-2xl bg-white rounded-[32px] shadow-2xl transform transition-all overflow-hidden border border-slate-100">
            <!-- Modal Header -->
            <div class="px-8 py-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-200">
                        <i data-lucide="plus" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-slate-800 tracking-tight">Tambah Barang di {{ $room->name }}</h2>
                        <p class="text-xs text-slate-500 font-medium">Barang baru otomatis terdaftar di ruangan ini.</p>
                    </div>
                </div>
                <button onclick="closeModal('addItemModal')" class="p-2 hover:bg-slate-200 rounded-xl transition-all text-slate-400"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>

            <form action="{{ route('inventory.store') }}" method="POST" class="p-8 max-h-[70vh] overflow-y-auto custom-scrollbar">
                @csrf
                <!-- Redirect marker to return to this room view after creation -->
                <input type="hidden" name="redirect_to_room" value="{{ $room->id }}">

                @include('admin.inventory._form_fields', ['isEdit' => false, 'rooms' => $rooms, 'activeRoomId' => $room->id, 'categories' => $categories])

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
                <!-- Redirect marker to return to this room view after edit -->
                <input type="hidden" name="redirect_to_room" value="{{ $room->id }}">
                <input type="hidden" id="edit_item_id" name="item_id" value="{{ old('item_id', session('edit_item_id')) }}">
                @include('admin.inventory._form_fields', ['isEdit' => true, 'rooms' => $rooms, 'categories' => $categories])

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
        <div class="relative w-full max-w-2xl bg-white rounded-[32px] shadow-2xl transform transition-all overflow-hidden border border-slate-100 flex flex-col">
            <!-- Top Blue Header -->
            <div class="bg-blue-600 p-8 flex items-center justify-between">
                <div class="flex items-center gap-6">
                    <div class="flex flex-col items-center gap-2">
                        <div class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center shadow-lg p-2" id="view_qr_container"></div>
                        <p class="text-[10px] font-black text-blue-100 uppercase tracking-widest">Scan Me</p>
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

            <!-- Details Body -->
            <div class="p-8 max-h-[60vh] overflow-y-auto custom-scrollbar">
                <!-- Item Image Container -->
                <div id="view_image_container" class="hidden mb-8 w-full h-56 rounded-[24px] overflow-hidden border border-slate-100 shadow-sm relative">
                    <img id="view_image" src="" class="w-full h-full object-cover" alt="Item Image">
                </div>

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
        // Populate modal
        document.getElementById('view_name').innerText = item.name || '-';
        document.getElementById('view_kode').innerText = item.kode_aset || '-';
        document.getElementById('view_tersedia').innerText = 'Tersedia: ' + (item.qty_tersedia || 0);
        document.getElementById('view_kategori').innerText = (item.category && item.category.name) ? item.category.name : '-';
        document.getElementById('view_lokasi').innerText = '{{ $room->name }}';
        document.getElementById('view_total').innerText = (item.quantity || 0) + ' Unit';
        document.getElementById('view_deskripsi').innerText = item.description || 'Tidak ada deskripsi';

        // QR Code
        const qrContainer = document.getElementById('view_qr_container');
        qrContainer.innerHTML = `<img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${item.kode_aset}" class="w-full h-full" alt="QR Code">`;

        // Handle image
        const imgContainer = document.getElementById('view_image_container');
        const imgEl = document.getElementById('view_image');
        if (item.image_url) {
            imgEl.src = item.image_url;
            imgContainer.classList.remove('hidden');
        } else {
            imgEl.src = '';
            imgContainer.classList.add('hidden');
        }

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
        form.querySelector('[name="purchase_date"]').value = item.purchase_date;
        form.querySelector('[name="description"]').value = item.description || '';

        openModal('editItemModal');
    }

    function printLabel(item) {
        const printWindow = window.open('', '_blank', 'width=400,height=400');
        const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${item.kode_aset}`;
        
        printWindow.document.write(`
            <html>
                <head>
                    <title>Print Label - ${item.name}</title>
                    <style>
                        body { font-family: sans-serif; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh; margin: 0; }
                        .label-container { text-align: center; border: 2px solid #eee; padding: 20px; border-radius: 10px; }
                        img { width: 200px; height: 200px; margin-bottom: 10px; }
                        h2 { margin: 0; font-size: 18px; color: #333; }
                        p { margin: 5px 0 0; font-size: 14px; font-weight: bold; color: #666; }
                    </style>
                </head>
                <body onload="window.print(); window.close();">
                    <div class="label-container">
                        <img src="${qrUrl}" alt="QR Code">
                        <h2>${item.name}</h2>
                        <p>${item.kode_aset}</p>
                    </div>
                </body>
            </html>
        `);
        printWindow.document.close();
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
@endsection
