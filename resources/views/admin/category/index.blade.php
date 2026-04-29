@extends('layouts.master', ['title' => 'Kategori'])

@section('content')
<div class="page-header">
    <div>
        <h1>Kategori</h1>
        <p>Kelola kategori barang inventaris</p>
    </div>
    <button class="btn btn-primary" onclick="document.getElementById('addCategoryForm').style.display = document.getElementById('addCategoryForm').style.display === 'none' ? 'block' : 'none'">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Kategori
    </button>
</div>

<!-- Add Category Form (toggled) -->
<div id="addCategoryForm" style="display:none;margin-bottom:20px;">
    <div class="form-section">
        <div class="form-section-title">Tambah Kategori Baru</div>
        <form action="{{ route('admin.category.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label>Nama Kategori <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="Masukkan nama kategori" value="{{ old('name') }}" required>
                    @error('name')
                        <p style="color:var(--red);font-size:12px;margin-top:4px;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Foto Kategori</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    @error('image')
                        <p style="color:var(--red);font-size:12px;margin-top:4px;">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div style="display:flex;gap:8px;justify-content:flex-end;">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('addCategoryForm').style.display='none'">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2"/><circle cx="12" cy="14" r="2"/><polyline points="14 4 14 8 8 8 8 4"/></svg>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Stats -->
<div style="display:flex;gap:16px;margin-bottom:24px;">
    <div class="stat-card" style="flex:1;max-width:300px;">
        <div class="stat-card-info">
            <div class="stat-label">Total Kategori</div>
            <div class="stat-value">{{ $categories->total() }}</div>
        </div>
        <div class="stat-card-icon blue">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4h6v6h-6z"/><path d="M14 4h6v6h-6z"/><path d="M4 14h6v6h-6z"/><path d="M17 17m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"/></svg>
        </div>
    </div>
</div>

<!-- Search -->
<div class="data-table-wrapper">
    <div class="table-toolbar">
        <div class="table-search">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><circle cx="10" cy="10" r="7"/><line x1="21" y1="21" x2="15" y2="15"/></svg>
            <form action="{{ route('admin.category.index') }}" method="GET" style="width:100%;">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari kategori...">
            </form>
        </div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width:50px;">#</th>
                <th style="width:60px;">Foto</th>
                <th>Nama Kategori</th>
                <th style="width:100px;">Jumlah Item</th>
                <th style="width:150px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $i => $category)
            <tr>
                <td>{{ $i + $categories->firstItem() }}</td>
                <td>
                    @if($category->image)
                        <div style="width:36px;height:36px;border-radius:8px;background:#f1f5f9;overflow:hidden;">
                            <img src="{{ asset('storage/categories/' . $category->image) }}" alt="{{ $category->name }}" style="width:100%;height:100%;object-fit:cover;">
                        </div>
                    @else
                        <div style="width:36px;height:36px;border-radius:8px;background:var(--blue);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;">
                            {{ strtoupper(substr($category->name, 0, 1)) }}
                        </div>
                    @endif
                </td>
                <td style="font-weight:600;">{{ $category->name }}</td>
                <td>
                    <span class="badge badge-blue">{{ $category->items_count ?? $category->items()->count() }} item</span>
                </td>
                <td>
                    <div style="display:flex;gap:6px;">
                        <button class="btn btn-outline btn-sm" onclick="toggleEdit({{ $category->id }})">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4"/><line x1="13.5" y1="6.5" x2="17.5" y2="10.5"/></svg>
                            Edit
                        </button>
                        <form id="delete-form-{{ $category->id }}" action="{{ route('admin.category.destroy', $category->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-sm" style="background:#fef2f2;color:#ef4444;border:1px solid #fecaca;" onclick="deleteData({{ $category->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><line x1="4" y1="7" x2="20" y2="7"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/></svg>
                                Hapus
                            </button>
                        </form>
                    </div>

                    <!-- Inline Edit Form -->
                    <form id="edit-form-{{ $category->id }}" action="{{ route('admin.category.update', $category->id) }}" method="POST" enctype="multipart/form-data" style="display:none;margin-top:8px;">
                        @csrf
                        @method('PUT')
                        <div style="display:flex;gap:8px;align-items:flex-end;">
                            <div style="flex:1;">
                                <input type="text" name="name" class="form-control" value="{{ $category->name }}" style="padding:6px 10px;font-size:12px;">
                            </div>
                            <div>
                                <input type="file" name="image" class="form-control" style="padding:4px 8px;font-size:11px;">
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                            <button type="button" class="btn btn-outline btn-sm" onclick="toggleEdit({{ $category->id }})">Batal</button>
                        </div>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center;padding:40px;color:var(--text-muted);">
                    Belum ada kategori. Klik "Tambah Kategori" untuk membuat kategori baru.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($categories->hasPages())
    <div style="padding:12px 20px;border-top:1px solid var(--border-color);">
        {{ $categories->links() }}
    </div>
    @endif
</div>
@endsection

@push('js')
<script>
function toggleEdit(id) {
    const form = document.getElementById('edit-form-' + id);
    form.style.display = form.style.display === 'none' ? 'flex' : 'none';
}
</script>
@endpush
