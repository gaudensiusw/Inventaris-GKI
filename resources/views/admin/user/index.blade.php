@extends('layouts.master', ['title' => 'Manajemen Pengguna'])

@section('content')
<div class="page-header">
    <div>
        <h1>Manajemen Pengguna</h1>
        <p>Kelola pengguna dan hak akses sistem</p>
    </div>
    <a href="#" class="btn btn-primary" data-toggle="modal">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="9" cy="7" r="4"/><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/><path d="M16 11h6m-3 -3v6"/></svg>
        Tambah Pengguna
    </a>
</div>

<!-- Role stat cards -->
<div style="display:flex;gap:16px;margin-bottom:24px;">
    @php
        $adminCount = $users->filter(fn($u) => $u->roles->pluck('name')->contains('Admin') || $u->roles->pluck('name')->contains('Super Admin'))->count();
        $userCount = $users->filter(fn($u) => $u->roles->pluck('name')->contains('Customer'))->count();
    @endphp
    <div class="stat-card" style="flex:1;max-width:400px;">
        <div class="stat-card-info">
            <div class="stat-value" style="color:#ef4444;">{{ $adminCount }}</div>
            <div class="stat-sub">Admin</div>
        </div>
        <div class="stat-card-icon red">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M12 3l8 4.5v8.5a12 12 0 0 1 -8 8.5a12 12 0 0 1 -8 -8.5v-8.5l8 -4.5"/></svg>
        </div>
    </div>
    <div class="stat-card" style="flex:1;max-width:400px;">
        <div class="stat-card-info">
            <div class="stat-value" style="color:#3b82f6;">{{ $userCount }}</div>
            <div class="stat-sub">User</div>
        </div>
        <div class="stat-card-icon blue">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><circle cx="12" cy="7" r="4"/><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/></svg>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="data-table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th>Pengguna</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:10px;">
                        @php
                            $isAdmin = $user->roles->pluck('name')->intersect(['Admin','Super Admin'])->isNotEmpty();
                        @endphp
                        <div class="user-avatar {{ $isAdmin ? 'admin-bg' : 'user-bg' }}">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <span style="font-weight:600;">{{ $user->name }}</span>
                    </div>
                </td>
                <td style="color:#64748b;">{{ $user->email }}</td>
                <td>
                    @foreach($user->roles as $role)
                        @php
                            $roleColor = in_array($role->name, ['Admin','Super Admin']) ? 'red' : 'blue';
                        @endphp
                        <span class="badge badge-{{ $roleColor }} badge-dot">{{ $role->name }}</span>
                    @endforeach
                </td>
                <td><span class="status-aktif">Aktif</span></td>
                <td>
                    <a href="#" style="color:#3b82f6;font-size:13px;font-weight:500;text-decoration:none;"
                       onclick="event.preventDefault(); document.getElementById('role-form-{{ $user->id }}').style.display = document.getElementById('role-form-{{ $user->id }}').style.display === 'none' ? 'flex' : 'none';">
                        Edit
                    </a>
                    <form id="role-form-{{ $user->id }}" action="{{ route('admin.user.update', $user) }}" method="POST" style="display:none;align-items:center;gap:6px;margin-top:6px;">
                        @csrf
                        @method('PUT')
                        <select name="role" class="form-control" style="width:auto;padding:4px 8px;font-size:12px;">
                            @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ $user->roles->first()?->name == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($users->hasPages())
    <div class="pagination-wrapper">{{ $users->links() }}</div>
    @endif
</div>

<!-- Deskripsi Role -->
<div class="role-description">
    <h3>Deskripsi Role</h3>
    <div class="role-item">
        <div class="role-dot red"></div>
        <div>
            <h4>Admin</h4>
            <p>Akses penuh ke semua fitur termasuk manajemen pengguna dan pengaturan sistem</p>
        </div>
    </div>
    <div class="role-item">
        <div class="role-dot blue"></div>
        <div>
            <h4>User</h4>
            <p>Hanya dapat melihat data inventaris tanpa bisa melakukan perubahan</p>
        </div>
    </div>
</div>
@endsection
