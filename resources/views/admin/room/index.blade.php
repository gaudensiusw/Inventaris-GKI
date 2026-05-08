@extends('layouts.master')

@section('content')
<div class="flex flex-col gap-8">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Lokasi Penyimpanan</h1>
            <p class="text-slate-500 text-sm mt-1">Kelola daftar ruangan dan area penyimpanan barang.</p>
        </div>
        <button onclick="openModal('addRoomModal')" class="px-6 py-3 bg-blue-600 text-white rounded-2xl text-xs font-black uppercase shadow-xl shadow-blue-200 hover:bg-blue-700 transition-all flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Tambah Lokasi
        </button>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl text-sm font-bold flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-red-50 border border-red-100 text-red-600 rounded-2xl text-sm font-bold flex items-center gap-3">
            <i data-lucide="alert-circle" class="w-5 h-5"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Search & Filters -->
    <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="relative w-full md:w-96 group">
            <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-blue-600 transition-colors"></i>
            <input type="text" 
                id="roomSearch"
                value="{{ $search }}"
                placeholder="Cari lokasi atau deskripsi..." 
                class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-medium focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all shadow-sm"
                onkeypress="if(event.key === 'Enter') window.location.href = '?search=' + this.value">
            @if($search)
            <button onclick="window.location.href = '?'" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
            @endif
        </div>
    </div>

    <!-- Room Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($rooms as $room)
        <div class="card-premium p-0 border border-slate-200 group relative overflow-hidden flex flex-col shadow-card hover:shadow-xl transition-all duration-300">
            <!-- Room Image -->
            <div class="relative h-48 w-full overflow-hidden">
                @if($room->image)
                    <img src="{{ asset('storage/' . $room->image) }}" alt="{{ $room->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                @else
                    <div class="w-full h-full bg-slate-200 flex flex-col items-center justify-center text-slate-500 gap-2">
                        <i data-lucide="image" class="w-10 h-10 opacity-40"></i>
                        <span class="text-[10px] font-bold uppercase tracking-widest opacity-60">No Image</span>
                    </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 to-transparent opacity-40"></div>
                
                <!-- Actions on top of image -->
                <div class="absolute top-4 right-4 flex gap-2">
                    <button onclick="editRoom({{ $room->id }}, '{{ $room->name }}', '{{ $room->description }}', '{{ $room->image ? asset('storage/' . $room->image) : '' }}')" class="p-2 bg-white/80 backdrop-blur-sm text-slate-700 hover:bg-white hover:text-blue-600 rounded-xl transition-all shadow-lg border border-white/50">
                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                    </button>
                    <button onclick="confirmDeleteRoom({{ $room->id }}, '{{ $room->name }}')" class="p-2 bg-white/80 backdrop-blur-sm text-slate-700 hover:bg-red-500 hover:text-white rounded-xl transition-all shadow-lg border border-white/50">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>

            <div class="p-6 flex-1 flex flex-col bg-white">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-xl font-black text-slate-800">{{ $room->name }}</h3>
                    <div class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-[10px] font-black uppercase tracking-tight">
                        {{ $room->items_count }} Barang
                    </div>
                </div>
                <p class="text-xs text-slate-600 font-medium mb-4 line-clamp-2 leading-relaxed">{{ $room->description ?? 'Tidak ada deskripsi' }}</p>
                
                <div class="mt-auto pt-4 border-t border-slate-100 flex items-center gap-2">
                    <div class="w-6 h-6 bg-slate-100 text-slate-500 rounded-lg flex items-center justify-center">
                        <i data-lucide="map-pin" class="w-3 h-3"></i>
                    </div>
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Area Inventaris</span>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 flex flex-col items-center justify-center text-center bg-white rounded-[32px] border-2 border-dashed border-slate-100">
            <div class="w-20 h-20 bg-slate-50 text-slate-200 rounded-full flex items-center justify-center mb-4">
                <i data-lucide="map-pin-off" class="w-10 h-10"></i>
            </div>
            <h3 class="text-xl font-black text-slate-800">Lokasi Tidak Ditemukan</h3>
            <p class="text-slate-500 text-sm max-w-xs mt-2">Maaf, kami tidak menemukan lokasi dengan kata kunci "{{ $search }}".</p>
            <a href="?" class="mt-6 px-6 py-3 bg-slate-800 text-white rounded-2xl text-xs font-black uppercase hover:bg-slate-900 transition-all">Lihat Semua Lokasi</a>
        </div>
        @endforelse
    </div>
</div>

<!-- Add Modal -->
<div id="addRoomModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('addRoomModal')"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div id="addRoomModalContent" class="relative w-full max-w-md transform overflow-hidden rounded-[32px] bg-white p-8 shadow-2xl transition-all scale-95 opacity-0">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h3 class="text-xl font-black text-slate-800">Tambah Lokasi</h3>
                    <p class="text-xs text-slate-500 font-medium">Buat area penyimpanan baru.</p>
                </div>
                <button onclick="closeModal('addRoomModal')" class="p-2 text-slate-400 hover:bg-slate-50 rounded-xl transition-all">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('room.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="flex flex-col gap-6">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-slate-500 ml-1">Nama Lokasi <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required placeholder="Contoh: Ruang Konsistori"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all text-sm font-medium">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-slate-500 ml-1">Foto Ruangan</label>
                        <input type="file" name="image" accept="image/*"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all text-sm font-medium">
                        <p class="text-[10px] text-slate-400 ml-1">Format: JPG, PNG. Maksimal 2MB.</p>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-slate-500 ml-1">Deskripsi</label>
                        <textarea name="description" rows="3" placeholder="Tambahkan detail lokasi..." 
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all text-sm font-medium"></textarea>
                    </div>
                </div>

                <div class="mt-8 flex gap-3">
                    <button type="button" onclick="closeModal('addRoomModal')" class="flex-1 px-6 py-4 bg-slate-100 text-slate-600 rounded-2xl text-xs font-black uppercase hover:bg-slate-200 transition-all">Batal</button>
                    <button type="submit" class="flex-[2] px-6 py-4 bg-blue-600 text-white rounded-2xl text-xs font-black uppercase shadow-xl shadow-blue-200 hover:bg-blue-700 transition-all">Simpan Lokasi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editRoomModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('editRoomModal')"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div id="editRoomModalContent" class="relative w-full max-w-md transform overflow-hidden rounded-[32px] bg-white p-8 shadow-2xl transition-all scale-95 opacity-0">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h3 class="text-xl font-black text-slate-800">Edit Lokasi</h3>
                    <p class="text-xs text-slate-500 font-medium">Perbarui informasi ruangan.</p>
                </div>
                <button onclick="closeModal('editRoomModal')" class="p-2 text-slate-400 hover:bg-slate-50 rounded-xl transition-all">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form id="editRoomForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="flex flex-col gap-6">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-slate-500 ml-1">Nama Lokasi <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="edit_name" required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all text-sm font-medium">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-slate-500 ml-1">Foto Ruangan</label>
                        <div id="current_image_container" class="hidden mb-2">
                            <img id="current_image_preview" src="" class="w-full h-32 object-cover rounded-2xl border border-slate-100">
                            <p class="text-[10px] text-slate-400 mt-1 italic">Foto saat ini (biarkan kosong jika tidak ingin diubah)</p>
                        </div>
                        <input type="file" name="image" accept="image/*"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all text-sm font-medium">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-slate-500 ml-1">Deskripsi</label>
                        <textarea name="description" id="edit_description" rows="3"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all text-sm font-medium"></textarea>
                    </div>
                </div>

                <div class="mt-8 flex gap-3">
                    <button type="button" onclick="closeModal('editRoomModal')" class="flex-1 px-6 py-4 bg-slate-100 text-slate-600 rounded-2xl text-xs font-black uppercase hover:bg-slate-200 transition-all">Batal</button>
                    <button type="submit" class="flex-[2] px-6 py-4 bg-blue-600 text-white rounded-2xl text-xs font-black uppercase shadow-xl shadow-blue-200 hover:bg-blue-700 transition-all">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<form id="deleteRoomForm" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
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

    function editRoom(id, name, description, imageUrl) {
        const form = document.getElementById('editRoomForm');
        form.action = `/admin/room/${id}`;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_description').value = description;
        
        const imgContainer = document.getElementById('current_image_container');
        const imgPreview = document.getElementById('current_image_preview');
        
        if (imageUrl) {
            imgPreview.src = imageUrl;
            imgContainer.classList.remove('hidden');
        } else {
            imgContainer.classList.add('hidden');
        }
        
        openModal('editRoomModal');
    }

    function confirmDeleteRoom(id, name) {
        showConfirm({
            title: 'Hapus Lokasi?',
            message: `Apakah Anda yakin ingin menghapus "${name}"? Lokasi hanya bisa dihapus jika tidak ada barang di dalamnya.`,
            color: 'red',
            icon: 'trash-2',
            confirmText: 'Hapus Lokasi',
            onConfirm: () => {
                const form = document.getElementById('deleteRoomForm');
                form.action = `/admin/room/${id}`;
                form.submit();
            }
        });
    }
</script>
@endsection
