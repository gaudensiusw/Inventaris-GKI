@extends(auth()->user()->isUser() ? 'layouts.user' : 'layouts.master')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Back Link (Dynamic based on Role) -->
    @if(auth()->user()->isUser())
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-blue-600 transition-colors font-semibold">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Kembali ke Beranda
        </a>
    @else
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-blue-600 transition-colors font-semibold">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Kembali ke Dashboard
        </a>
    @endif

    <!-- Page Header -->
    <div>
        <h1 class="text-2xl font-black text-slate-800 tracking-tight">Profil Saya</h1>
        <p class="text-sm text-slate-500 mt-1">Perbarui informasi profil dan kata sandi akun Anda</p>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
            <span class="text-sm font-semibold text-emerald-700">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 rounded-2xl flex flex-col gap-2">
            <div class="flex items-center gap-3">
                <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 shrink-0"></i>
                <span class="text-sm font-bold text-red-700">Harap perbaiki kesalahan berikut:</span>
            </div>
            <ul class="list-disc ml-8 text-xs font-semibold text-red-650">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Profile Edit Form Card -->
    <form method="POST" action="{{ route('profile.update') }}" class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 space-y-6">
        @csrf
        @method('PUT')

        <!-- Informasi Profil Section -->
        <div class="space-y-4">
            <h3 class="text-xs font-black text-blue-600 uppercase tracking-[0.2em] border-b border-blue-50 pb-2">Informasi Profil</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-505 ml-1">Nama Lengkap *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required placeholder="Masukkan nama lengkap Anda"
                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all font-medium">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-505 ml-1">Nomor HP / Kontak</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 08123456789"
                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all font-medium">
                    <p class="text-[10px] text-slate-400 ml-1">💡 Digunakan untuk pengisian otomatis form peminjaman.</p>
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-505 ml-1">Alamat Email *</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required placeholder="Masukkan alamat email Anda"
                    class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all font-medium">
            </div>
        </div>

        <!-- Ubah Password Section -->
        <div class="space-y-4 pt-4 border-t border-slate-50">
            <h3 class="text-xs font-black text-blue-600 uppercase tracking-[0.2em] border-b border-blue-50 pb-2">Ubah Kata Sandi (Opsional)</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-550 ml-1">Kata Sandi Baru</label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak ingin diubah"
                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all font-medium">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-550 ml-1">Konfirmasi Kata Sandi Baru</label>
                    <input type="password" name="password_confirmation" placeholder="Ulangi kata sandi baru"
                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all font-medium">
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="pt-4 flex justify-end gap-3">
            @if(auth()->user()->isUser())
                <a href="{{ route('home') }}" class="px-6 py-3.5 bg-slate-100 text-slate-600 rounded-2xl text-xs font-black uppercase hover:bg-slate-200 transition-all">Batal</a>
            @else
                <a href="{{ route('dashboard') }}" class="px-6 py-3.5 bg-slate-100 text-slate-600 rounded-2xl text-xs font-black uppercase hover:bg-slate-200 transition-all">Batal</a>
            @endif
            <button type="submit" class="px-8 py-3.5 bg-blue-600 text-white rounded-2xl text-xs font-black uppercase hover:bg-blue-700 shadow-xl shadow-blue-200 transition-all flex items-center gap-2">
                <i data-lucide="save" class="w-4 h-4"></i>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
