@extends('layouts.master', ['title' => 'Lokasi / Ruangan'])

@section('content')
<div class="page-header">
    <div>
        <h1>Lokasi / Ruangan</h1>
        <p>Kelola lokasi penyimpanan barang inventaris</p>
    </div>
    <button class="btn btn-primary" onclick="document.getElementById('addRoomForm').style.display = document.getElementById('addRoomForm').style.display === 'none' ? 'block' : 'none'">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Ruangan
    </button>
</div>

<!-- Add Room Form (toggled) -->
<div id="addRoomForm" style="display:none;margin-bottom:20px;">
    <div class="form-section">
        <div class="form-section-title">Tambah Ruangan Baru</div>
        <form action="{{ route('admin.room.store') }}" method="POST">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label>Nama Ruangan <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="Contoh: Gudang Lt. 2" value="{{ old('name') }}" required>
                    @error('name')
                        <p style="color:var(--red);font-size:12px;margin-top:4px;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <input type="text" name="description" class="form-control" placeholder="Keterangan lokasi (opsional)" value="{{ old('description') }}">
                </div>
            </div>
            <div style="display:flex;gap:8px;justify-content:flex-end;">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('addRoomForm').style.display='none'">Batal</button>
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
            <div class="stat-label">Total Ruangan</div>
            <div class="stat-value">{{ $rooms->total() }}</div>
        </div>
        <div class="stat-card-icon purple">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l18 0"/><path d="M5 21v-14l8 -4v18"/><path d="M19 21v-10l-6 -4"/><path d="M9 9l0 .01"/><path d="M9 12l0 .01"/><path d="M9 15l0 .01"/></svg>
        </div>
    </div>
</div>

<!-- Room Table -->
<div class="data-table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:50px;">#</th>
                <th>Nama Ruangan</th>
                <th>Deskripsi</th>
                <th style="width:100px;">Jumlah Item</th>
                <th style="width:180px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rooms as $i => $room)
            <tr>
                <td>{{ $i + $rooms->firstItem() }}</td>
                <td>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:32px;height:32px;border-radius:8px;background:#f5f3ff;color:#8b5cf6;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:12px;">
                            {{ strtoupper(substr($room->name, 0, 1)) }}
                        </div>
                        <span style="font-weight:600;">{{ $room->name }}</span>
                    </div>
                </td>
                <td style="color:var(--text-muted);font-size:13px;">{{ $room->description ?? '-' }}</td>
                <td>
                    <span class="badge badge-purple">{{ $room->items()->count() }} item</span>
                </td>
                <td>
                    <div style="display:flex;gap:6px;">
                        <button class="btn btn-outline btn-sm" onclick="toggleRoomEdit({{ $room->id }})">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4"/><line x1="13.5" y1="6.5" x2="17.5" y2="10.5"/></svg>
                            Edit
                        </button>
                        <form id="delete-form-{{ $room->id }}" action="{{ route('admin.room.destroy', $room->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-sm" style="background:#fef2f2;color:#ef4444;border:1px solid #fecaca;" onclick="deleteData({{ $room->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><line x1="4" y1="7" x2="20" y2="7"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/></svg>
                                Hapus
                            </button>
                        </form>
                    </div>

                    <!-- Inline Edit Form -->
                    <form id="edit-form-{{ $room->id }}" action="{{ route('admin.room.update', $room->id) }}" method="POST" style="display:none;margin-top:8px;">
                        @csrf
                        @method('PUT')
                        <div style="display:flex;gap:8px;align-items:flex-end;">
                            <div style="flex:1;">
                                <input type="text" name="name" class="form-control" value="{{ $room->name }}" style="padding:6px 10px;font-size:12px;">
                            </div>
                            <div style="flex:1;">
                                <input type="text" name="description" class="form-control" value="{{ $room->description }}" placeholder="Deskripsi" style="padding:6px 10px;font-size:12px;">
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                            <button type="button" class="btn btn-outline btn-sm" onclick="toggleRoomEdit({{ $room->id }})">Batal</button>
                        </div>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center;padding:40px;color:var(--text-muted);">
                    Belum ada ruangan. Klik "Tambah Ruangan" untuk membuat ruangan baru.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($rooms->hasPages())
    <div style="padding:12px 20px;border-top:1px solid var(--border-color);">
        {{ $rooms->links() }}
    </div>
    @endif
</div>
@endsection

@push('js')
<script>
function toggleRoomEdit(id) {
    const form = document.getElementById('edit-form-' + id);
    form.style.display = form.style.display === 'none' ? 'flex' : 'none';
}
</script>
@endpush
