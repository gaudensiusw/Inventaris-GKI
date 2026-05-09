@extends('layouts.master')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800">Manajemen Kategori</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola kategori barang inventaris</p>
        </div>
        <button onclick="document.getElementById('addCategoryModal').classList.remove('hidden'); document.getElementById('addCategoryModal').classList.add('flex')" 
            class="px-6 py-3 bg-blue-600 text-white rounded-2xl text-sm font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Tambah Kategori
        </button>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
            <span class="text-sm font-semibold text-emerald-700">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-red-50 border border-red-200 rounded-2xl flex items-center gap-3">
            <i data-lucide="alert-circle" class="w-5 h-5 text-red-600"></i>
            <span class="text-sm font-semibold text-red-700">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Categories Table -->
    <div class="bg-white rounded-[32px] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-4 text-left text-[11px] font-black text-slate-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-[11px] font-black text-slate-500 uppercase tracking-wider">Nama Kategori</th>
                        <th class="px-6 py-4 text-left text-[11px] font-black text-slate-500 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-6 py-4 text-center text-[11px] font-black text-slate-500 uppercase tracking-wider">Jumlah Barang</th>
                        <th class="px-6 py-4 text-center text-[11px] font-black text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($categories as $i => $category)
                    <tr class="hover:bg-blue-50/30 transition-colors">
                        <td class="px-6 py-4 text-sm font-bold text-slate-400">{{ $i + 1 }}</td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-black text-slate-800">{{ $category->name }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-slate-500">{{ $category->description ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-xs font-bold">
                                {{ $category->items_count }} barang
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="openEditCategory({{ $category->id }}, '{{ addslashes($category->name) }}', '{{ addslashes($category->description ?? '') }}')" 
                                    class="p-2 bg-amber-50 text-amber-600 rounded-xl hover:bg-amber-100 transition-all" title="Edit">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </button>
                                @if($category->items_count === 0)
                                <form method="POST" action="{{ route('categories.destroy', $category->id) }}" 
                                    onsubmit="return confirmSubmit(this, {title: 'Hapus Kategori?', message: 'Kategori {{ addslashes($category->name) }} akan dihapus.', icon: 'trash-2', color: 'red', confirmText: 'Ya, Hapus'})">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-red-50 text-red-500 rounded-xl hover:bg-red-100 transition-all" title="Hapus">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-sm text-slate-400">Belum ada kategori.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div id="addCategoryModal" class="fixed inset-0 bg-slate-900/50 hidden items-center justify-center z-[200] backdrop-blur-sm">
    <div class="bg-white rounded-[32px] w-full max-w-md overflow-hidden shadow-2xl p-8">
        <h3 class="text-lg font-black text-slate-800 mb-6">Tambah Kategori Baru</h3>
        <form method="POST" action="{{ route('categories.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-2">Nama Kategori *</label>
                <input type="text" name="name" required class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all" placeholder="Contoh: Alat Multimedia">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-2">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all resize-none" placeholder="Deskripsi kategori (opsional)"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="document.getElementById('addCategoryModal').classList.add('hidden'); document.getElementById('addCategoryModal').classList.remove('flex')" 
                    class="flex-1 px-6 py-3.5 bg-slate-100 text-slate-600 rounded-2xl text-sm font-bold hover:bg-slate-200 transition-all">Batal</button>
                <button type="submit" class="flex-[2] px-6 py-3.5 bg-blue-600 text-white rounded-2xl text-sm font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Category Modal -->
<div id="editCategoryModal" class="fixed inset-0 bg-slate-900/50 hidden items-center justify-center z-[200] backdrop-blur-sm">
    <div class="bg-white rounded-[32px] w-full max-w-md overflow-hidden shadow-2xl p-8">
        <h3 class="text-lg font-black text-slate-800 mb-6">Edit Kategori</h3>
        <form id="editCategoryForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-2">Nama Kategori *</label>
                <input type="text" name="name" id="editCategoryName" required class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-2">Deskripsi</label>
                <textarea name="description" id="editCategoryDesc" rows="3" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all resize-none"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="document.getElementById('editCategoryModal').classList.add('hidden'); document.getElementById('editCategoryModal').classList.remove('flex')" 
                    class="flex-1 px-6 py-3.5 bg-slate-100 text-slate-600 rounded-2xl text-sm font-bold hover:bg-slate-200 transition-all">Batal</button>
                <button type="submit" class="flex-[2] px-6 py-3.5 bg-amber-500 text-white rounded-2xl text-sm font-bold hover:bg-amber-600 shadow-lg shadow-amber-200 transition-all">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openEditCategory(id, name, description) {
    document.getElementById('editCategoryForm').action = '/admin/categories/' + id;
    document.getElementById('editCategoryName').value = name;
    document.getElementById('editCategoryDesc').value = description;
    document.getElementById('editCategoryModal').classList.remove('hidden');
    document.getElementById('editCategoryModal').classList.add('flex');
}
</script>
@endpush
