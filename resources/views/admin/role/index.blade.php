@extends('layouts.master', ['title' => 'Role & Permission'])

@section('content')
<div class="page-header">
    <div>
        <h1>Role & Permission</h1>
        <p>Kelola role dan hak akses pengguna sistem</p>
    </div>
    <button class="btn btn-primary" onclick="document.getElementById('addRoleForm').style.display = document.getElementById('addRoleForm').style.display === 'none' ? 'block' : 'none'">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Role
    </button>
</div>

<!-- Add Role Form (toggled) -->
<div id="addRoleForm" style="display:none;margin-bottom:20px;">
    <div class="form-section">
        <div class="form-section-title">Tambah Role Baru</div>
        <form action="{{ route('admin.role.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Nama Role <span class="required">*</span></label>
                <input type="text" name="name" class="form-control" placeholder="Masukkan nama role" value="{{ old('name') }}" required>
                @error('name')
                    <p style="color:var(--red);font-size:12px;margin-top:4px;">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group">
                <label>Permission</label>
                <div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:8px;">
                    @foreach($permissions as $permission)
                    <label style="display:flex;align-items:center;gap:6px;padding:6px 12px;border:1px solid var(--border-color);border-radius:8px;font-size:12px;cursor:pointer;transition:all 0.15s;">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" style="accent-color:var(--blue);">
                        {{ $permission->name }}
                    </label>
                    @endforeach
                </div>
            </div>
            <div style="display:flex;gap:8px;justify-content:flex-end;">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('addRoleForm').style.display='none'">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2"/><circle cx="12" cy="14" r="2"/><polyline points="14 4 14 8 8 8 8 4"/></svg>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Role Cards -->
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:16px;margin-bottom:24px;">
    @foreach($roles as $role)
    <div class="card" style="margin-bottom:0;">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px;">
            <div>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                    @php
                        $roleColor = in_array($role->name, ['Admin','Super Admin']) ? 'red' : ($role->name === 'Customer' ? 'blue' : 'purple');
                    @endphp
                    <div style="width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:12px;
                        {{ $roleColor === 'red' ? 'background:#fef2f2;color:#ef4444;' : ($roleColor === 'blue' ? 'background:#eff6ff;color:#3b82f6;' : 'background:#f5f3ff;color:#8b5cf6;') }}">
                        {{ strtoupper(substr($role->name, 0, 1)) }}
                    </div>
                    <h3 style="font-size:16px;font-weight:700;">{{ $role->name }}</h3>
                </div>
                <p style="font-size:12px;color:var(--text-muted);">{{ $role->users->count() }} pengguna</p>
            </div>
            <div style="display:flex;gap:4px;">
                <button class="btn btn-outline btn-icon btn-sm" onclick="toggleRoleEdit({{ $role->id }})" title="Edit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4"/><line x1="13.5" y1="6.5" x2="17.5" y2="10.5"/></svg>
                </button>
                @if(!in_array($role->name, ['Super Admin', 'Admin', 'Customer']))
                <form id="delete-form-{{ $role->id }}" action="{{ route('admin.role.destroy', $role->id) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="button" class="btn btn-icon btn-sm" style="background:#fef2f2;color:#ef4444;border:1px solid #fecaca;" onclick="deleteData({{ $role->id }})" title="Hapus">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><line x1="4" y1="7" x2="20" y2="7"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/></svg>
                    </button>
                </form>
                @endif
            </div>
        </div>

        <!-- Permissions list -->
        <div style="margin-bottom:12px;">
            <p style="font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:8px;">Permissions</p>
            <div style="display:flex;flex-wrap:wrap;gap:4px;">
                @forelse($role->permissions as $perm)
                    <span class="badge badge-{{ $roleColor }}">{{ $perm->name }}</span>
                @empty
                    <span style="font-size:12px;color:var(--text-muted);font-style:italic;">Tidak ada permission</span>
                @endforelse
            </div>
        </div>

        <!-- Edit Form (hidden) -->
        <div id="role-edit-{{ $role->id }}" style="display:none;border-top:1px solid var(--border-color);padding-top:12px;">
            <form action="{{ route('admin.role.update', $role->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="form-group" style="margin-bottom:12px;">
                    <label style="font-size:12px;">Nama Role</label>
                    <input type="text" name="name" class="form-control" value="{{ $role->name }}" style="padding:8px 12px;font-size:13px;">
                </div>
                <div class="form-group" style="margin-bottom:12px;">
                    <label style="font-size:12px;">Permission</label>
                    <div style="display:flex;flex-wrap:wrap;gap:6px;margin-top:6px;">
                        @foreach($permissions as $permission)
                        <label style="display:flex;align-items:center;gap:4px;padding:4px 10px;border:1px solid var(--border-color);border-radius:6px;font-size:11px;cursor:pointer;">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" style="accent-color:var(--blue);"
                                @checked($role->permissions()->find($permission->id))>
                            {{ $permission->name }}
                        </label>
                        @endforeach
                    </div>
                </div>
                <div style="display:flex;gap:6px;justify-content:flex-end;">
                    <button type="button" class="btn btn-outline btn-sm" onclick="toggleRoleEdit({{ $role->id }})">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    @endforeach
</div>

@if($roles->hasPages())
<div style="display:flex;justify-content:center;">
    {{ $roles->links() }}
</div>
@endif
@endsection

@push('js')
<script>
function toggleRoleEdit(id) {
    const form = document.getElementById('role-edit-' + id);
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}
</script>
@endpush
