@extends('layouts.master', ['title' => 'Pengaturan'])

@section('content')
<div class="page-header">
    <div>
        <h1>Pengaturan</h1>
        <p>Kelola preferensi dan konfigurasi sistem</p>
    </div>
</div>

<form action="{{ route('admin.setting.update', Auth::user()) }}" method="POST">
    @csrf
    @method('PUT')

    <!-- Pengaturan Umum -->
    <div class="settings-section">
        <div class="settings-section-header">
            <div class="settings-icon blue">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z"/><circle cx="12" cy="12" r="3"/></svg>
            </div>
            <div>
                <h3>Pengaturan Umum</h3>
                <p>Konfigurasi dasar sistem</p>
            </div>
        </div>

        <div class="form-group">
            <label>Nama Organisasi</label>
            <input type="text" class="form-control" value="GKI Delima" name="org_name">
        </div>
        <div class="form-group">
            <label>Alamat</label>
            <input type="text" class="form-control" value="Jakarta, Indonesia" name="address">
        </div>
        <div class="form-group">
            <label>Zona Waktu</label>
            <select class="form-control" name="timezone">
                <option value="Asia/Jakarta" selected>WIB (UTC+7)</option>
                <option value="Asia/Makassar">WITA (UTC+8)</option>
                <option value="Asia/Jayapura">WIT (UTC+9)</option>
            </select>
        </div>
    </div>

    <!-- Notifikasi -->
    <div class="settings-section">
        <div class="settings-section-header">
            <div class="settings-icon green">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M10 5a2 2 0 0 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6"/><path d="M9 17v1a3 3 0 0 0 6 0v-1"/></svg>
            </div>
            <div>
                <h3>Notifikasi</h3>
                <p>Atur preferensi notifikasi</p>
            </div>
        </div>

        <div class="settings-toggle">
            <div class="settings-toggle-info">
                <h4>Email Notifikasi</h4>
                <p>Terima notifikasi melalui email</p>
            </div>
            <label class="switch">
                <input type="checkbox" name="email_notification" checked>
                <span class="slider"></span>
            </label>
        </div>
        <div class="settings-toggle">
            <div class="settings-toggle-info">
                <h4>Notifikasi Barang Rusak</h4>
                <p>Dapatkan alert ketika ada barang rusak</p>
            </div>
            <label class="switch">
                <input type="checkbox" name="damaged_notification" checked>
                <span class="slider"></span>
            </label>
        </div>
        <div class="settings-toggle">
            <div class="settings-toggle-info">
                <h4>Laporan Mingguan</h4>
                <p>Terima ringkasan laporan setiap minggu</p>
            </div>
            <label class="switch">
                <input type="checkbox" name="weekly_report">
                <span class="slider"></span>
            </label>
        </div>
    </div>

    <!-- Keamanan -->
    <div class="settings-section">
        <div class="settings-section-header">
            <div class="settings-icon red">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><rect x="5" y="11" width="14" height="10" rx="2"/><circle cx="12" cy="16" r="1"/><path d="M8 11v-4a4 4 0 0 1 8 0v4"/></svg>
            </div>
            <div>
                <h3>Keamanan</h3>
                <p>Pengaturan keamanan akun</p>
            </div>
        </div>

        <div class="form-group" style="background:#f8fafc;padding:14px;border-radius:8px;">
            <h4 style="font-size:14px;font-weight:600;">Ubah Password</h4>
            <p style="font-size:12px;color:#64748b;margin:0;">Perbarui password Anda</p>
        </div>

        <div class="settings-toggle" style="margin-top:12px;">
            <div class="settings-toggle-info">
                <h4>Two-Factor Authentication</h4>
                <p>Tambah lapisan keamanan ekstra</p>
            </div>
            <label class="switch">
                <input type="checkbox" name="two_factor">
                <span class="slider"></span>
            </label>
        </div>
    </div>

    <!-- Manajemen Data -->
    <div class="settings-section">
        <div class="settings-section-header">
            <div class="settings-icon purple">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><ellipse cx="12" cy="6" rx="8" ry="3"/><path d="M4 6v6a8 3 0 0 0 16 0v-6"/><path d="M4 12v6a8 3 0 0 0 16 0v-6"/></svg>
            </div>
            <div>
                <h3>Manajemen Data</h3>
                <p>Backup dan restore data</p>
            </div>
        </div>

        <button type="button" class="btn btn-primary" style="width:100%;justify-content:center;margin-bottom:12px;">Export Data</button>
        <button type="button" class="btn btn-outline" style="width:100%;justify-content:center;">Import Data</button>
    </div>

    <div style="display:flex;justify-content:flex-end;padding:8px 0 24px;">
        <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
    </div>
</form>
@endsection
