@extends('layouts.master')

@section('content')
<div class="max-w-4xl">
    <!-- Header -->
    <div class="mb-10">
        <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Pengaturan</h1>
        <p class="text-slate-500 text-sm mt-1">Kelola preferensi dan konfigurasi sistem</p>
    </div>

    <div class="space-y-6">
        <!-- Pengaturan Umum -->
        <div class="card-premium">
            <div class="p-8 space-y-8">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                        <i data-lucide="settings" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800">Pengaturan Umum</h3>
                        <p class="text-[11px] text-slate-400 font-medium">Konfigurasi dasar sistem</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Organisasi</label>
                        <input type="text" value="GKI Delima" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-400 font-medium">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Alamat</label>
                        <input type="text" value="Jakarta, Indonesia" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-400 font-medium">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Zona Waktu</label>
                        <select class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-400 font-medium appearance-none">
                            <option>WIB (UTC+7)</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifikasi -->
        <div class="card-premium">
            <div class="p-8 space-y-8">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-green-50 text-green-600 rounded-xl flex items-center justify-center">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800">Notifikasi</h3>
                        <p class="text-[11px] text-slate-400 font-medium">Atur preferensi notifikasi</p>
                    </div>
                </div>

                <div class="divide-y divide-slate-50">
                    <div class="py-4 flex justify-between items-center">
                        <div>
                            <h4 class="text-sm font-bold text-slate-700">Email Notifikasi</h4>
                            <p class="text-xs text-slate-400">Terima notifikasi melalui email</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    <div class="py-4 flex justify-between items-center">
                        <div>
                            <h4 class="text-sm font-bold text-slate-700">Notifikasi Barang Rusak</h4>
                            <p class="text-xs text-slate-400">Dapatkan alert ketika ada barang rusak</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    <div class="py-4 flex justify-between items-center">
                        <div>
                            <h4 class="text-sm font-bold text-slate-700">Laporan Mingguan</h4>
                            <p class="text-xs text-slate-400">Terima ringkasan laporan setiap minggu</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Keamanan -->
        <div class="card-premium">
            <div class="p-8 space-y-8">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-red-50 text-red-600 rounded-xl flex items-center justify-center">
                        <i data-lucide="lock" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800">Keamanan</h3>
                        <p class="text-[11px] text-slate-400 font-medium">Pengaturan keamanan akun</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <button class="w-full p-4 bg-slate-50 border border-slate-100 rounded-xl text-left hover:bg-white hover:border-blue-400 transition-all group">
                        <h4 class="text-sm font-bold text-slate-700 group-hover:text-blue-600 transition-colors">Ubah Password</h4>
                        <p class="text-[11px] text-slate-400">Perbarui password Anda secara berkala</p>
                    </button>
                    <div class="flex justify-between items-center">
                        <div>
                            <h4 class="text-sm font-bold text-slate-700">Two-Factor Authentication</h4>
                            <p class="text-xs text-slate-400">Tambah lapisan keamanan ekstra</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Manajemen Data -->
        <div class="card-premium">
            <div class="p-8 space-y-8">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center">
                        <i data-lucide="database" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800">Manajemen Data</h3>
                        <p class="text-[11px] text-slate-400 font-medium">Backup dan restore data</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <button class="w-full py-3 bg-blue-600 text-white rounded-xl text-xs font-bold shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all">Export Data</button>
                    <button class="w-full py-3 bg-white border border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-50 transition-all">Import Data</button>
                    <button class="w-full py-3 bg-white border border-red-200 text-red-500 rounded-xl text-xs font-bold hover:bg-red-50 transition-all mt-4">Reset Semua Data</button>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex justify-end gap-3 py-10">
            <button class="px-6 py-2 bg-white border border-slate-200 rounded-lg text-sm font-bold text-slate-600">Batal</button>
            <button class="btn-primary-custom">Simpan Perubahan</button>
        </div>
    </div>
</div>
@endsection
