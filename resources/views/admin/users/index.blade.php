@extends('layouts.master')

@section('content')
<div class="flex flex-col gap-8">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Manajemen Pengguna</h1>
            <p class="text-slate-500 text-sm mt-1">Kelola pengguna dan hak akses sistem</p>
        </div>
        <button onclick="openModal('addUserModal')" class="w-full sm:w-auto px-6 py-3 bg-blue-600 text-white rounded-2xl text-sm font-black uppercase shadow-xl shadow-blue-200 hover:bg-blue-700 hover:scale-105 active:scale-95 transition-all flex items-center justify-center gap-3">
            <i data-lucide="user-plus" class="w-5 h-5"></i>
            <span>Tambah Pengguna</span>
        </button>
    </div>

    <!-- Alerts -->
    @if(session('success'))
    <div class="p-4 bg-emerald-50 text-emerald-600 rounded-2xl border border-emerald-100 flex items-center gap-3 font-bold text-sm">
        <i data-lucide="check-circle" class="w-5 h-5"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 bg-red-50 text-red-600 rounded-2xl border border-red-100 flex items-center gap-3 font-bold text-sm">
        <i data-lucide="x-circle" class="w-5 h-5"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="p-4 bg-red-50 text-red-600 rounded-2xl border border-red-100 flex flex-col gap-2 font-bold text-sm">
        <div class="flex items-center gap-2">
            <i data-lucide="alert-circle" class="w-5 h-5"></i>
            <span>Terdapat Kesalahan Input</span>
        </div>
        <ul class="list-disc list-inside ml-1 text-red-500 font-medium">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Stats Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm flex items-center gap-6 group hover:shadow-xl hover:scale-[1.02] transition-all">
            <div class="w-16 h-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center group-hover:bg-red-500 group-hover:text-white transition-all duration-500">
                <i data-lucide="shield-check" class="w-8 h-8"></i>
            </div>
            <div>
                <h4 class="text-3xl font-black text-slate-800 tracking-tight">{{ $users->total() }}</h4>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Administrator</p>
            </div>
        </div>
        <div class="bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm flex items-center gap-6 group hover:shadow-xl hover:scale-[1.02] transition-all opacity-50 grayscale">
            <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center">
                <i data-lucide="users" class="w-8 h-8"></i>
            </div>
            <div>
                <h4 class="text-3xl font-black text-slate-800 tracking-tight">0</h4>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">User Biasa (Disabled)</p>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card-premium shadow-card p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pengguna</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Email</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Role</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($users as $user)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center font-black text-xs uppercase">
                                    {{ substr($user->name, 0, 2) }}
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-800">{{ $user->name }}</h4>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Admin</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-sm font-medium text-slate-600">{{ $user->email }}</span>
                        </td>
                        <td class="px-6 py-5">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-50 text-red-600 rounded-full text-[10px] font-bold uppercase tracking-tight border border-red-100">
                                <i data-lucide="shield" class="w-3 h-3"></i>
                                Admin
                            </span>
                        </td>
                        <td class="px-6 py-5">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-bold uppercase tracking-tight border border-emerald-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Aktif
                            </span>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex justify-end gap-1">
                                <button onclick="editUser({{ json_encode($user) }})" class="p-2 hover:bg-slate-100 text-slate-400 rounded-lg transition-colors"><i data-lucide="edit" class="w-4 h-4"></i></button>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirmSubmit(this, { title: 'Hapus Admin?', message: 'Admin ini tidak akan bisa login lagi ke sistem!', color: 'red', icon: 'user-minus' })">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 hover:bg-red-50 text-red-400 rounded-lg transition-colors"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Description Section -->
    <div class="bg-blue-50/50 border border-blue-100 p-8 rounded-[32px] flex flex-col gap-4">
        <h3 class="text-sm font-bold text-slate-700">Deskripsi Role</h3>
        <div class="flex flex-col gap-6">
            <div class="flex gap-4 items-start">
                <div class="w-8 h-8 bg-red-100 text-red-600 rounded-lg flex items-center justify-center shrink-0">
                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-800">Admin</h4>
                    <p class="text-xs text-slate-500 mt-1">Akses penuh ke semua fitur termasuk manajemen pengguna, inventaris, dan pengaturan sistem.</p>
                </div>
            </div>
            <div class="flex gap-4 items-start opacity-50 grayscale">
                <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center shrink-0">
                    <i data-lucide="eye" class="w-4 h-4"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-800">User (Disabled)</h4>
                    <p class="text-xs text-slate-500 mt-1">Hanya dapat melihat data inventaris tanpa bisa melakukan perubahan.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Pengguna -->
<div id="addUserModal" class="fixed inset-0 z-[100] {{ $errors->any() && !session('is_edit') ? '' : 'hidden' }} overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('addUserModal')"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-md bg-white rounded-[32px] shadow-2xl overflow-hidden border border-slate-100 transform transition-all">
            <div class="px-8 py-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-200">
                        <i data-lucide="user-plus" class="w-5 h-5"></i>
                    </div>
                    <h2 class="text-lg font-black text-slate-800 tracking-tight">Tambah Admin</h2>
                </div>
                <button onclick="closeModal('addUserModal')" class="p-2 hover:bg-slate-200 rounded-xl transition-all text-slate-400"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <form action="{{ route('users.store') }}" method="POST" class="p-8 space-y-5">
                @csrf
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all text-sm font-medium">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all text-sm font-medium">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Password</label>
                    <input type="password" name="password" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all text-sm font-medium">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all text-sm font-medium">
                </div>
                <div class="pt-4 flex gap-3">
                    <button type="button" onclick="closeModal('addUserModal')" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-xl text-xs font-black uppercase hover:bg-slate-200 transition-all">Batal</button>
                    <button type="submit" class="flex-[2] py-3 bg-blue-600 text-white rounded-xl text-xs font-black uppercase shadow-xl shadow-blue-200 hover:bg-blue-700 transition-all">Simpan Admin</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Pengguna -->
<div id="editUserModal" class="fixed inset-0 z-[100] {{ session('is_edit') ? '' : 'hidden' }} overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('editUserModal')"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-md bg-white rounded-[32px] shadow-2xl overflow-hidden border border-slate-100 transform transition-all">
            <div class="px-8 py-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-amber-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-amber-200">
                        <i data-lucide="edit" class="w-5 h-5"></i>
                    </div>
                    <h2 class="text-lg font-black text-slate-800 tracking-tight">Edit Admin</h2>
                </div>
                <button onclick="closeModal('editUserModal')" class="p-2 hover:bg-slate-200 rounded-xl transition-all text-slate-400"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <form id="editUserForm" action="" method="POST" class="p-8 space-y-5">
                @csrf
                @method('PUT')
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Nama Lengkap</label>
                    <input type="text" name="name" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all text-sm font-medium">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Alamat Email</label>
                    <input type="email" name="email" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all text-sm font-medium">
                </div>
                <div class="space-y-2 pt-2 border-t border-slate-50">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Password Baru (Opsional)</label>
                    <input type="password" name="password" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all text-sm font-medium" placeholder="Kosongkan jika tidak diubah">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all text-sm font-medium">
                </div>
                <div class="pt-4 flex gap-3">
                    <button type="button" onclick="closeModal('editUserModal')" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-xl text-xs font-black uppercase hover:bg-slate-200 transition-all">Batal</button>
                    <button type="submit" class="flex-[2] py-3 bg-amber-600 text-white rounded-xl text-xs font-black uppercase shadow-xl shadow-amber-200 hover:bg-amber-700 transition-all">Simpan Perubahan</button>
                </div>
            </form>
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
    function editUser(user) {
        const form = document.getElementById('editUserForm');
        form.action = `/admin/users/${user.id}`;
        form.querySelector('[name="name"]').value = user.name;
        form.querySelector('[name="email"]').value = user.email;
        openModal('editUserModal');
    }
</script>
@endsection
