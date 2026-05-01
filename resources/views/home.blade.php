<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inventaris GKI Delima</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-800">
    <!-- Navbar -->
    <nav class="bg-white/80 backdrop-blur-md border-b border-slate-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-200">
                        <i data-lucide="package" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-black text-slate-800 tracking-tight">GKI Delima</h1>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Sistem Inventaris</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('login') }}" class="px-6 py-2.5 bg-slate-800 text-white rounded-xl text-xs font-black uppercase hover:bg-slate-900 transition-all shadow-lg">Login Admin</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <header class="bg-white border-b border-slate-100 py-20">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h2 class="text-4xl md:text-5xl font-black text-slate-800 tracking-tighter mb-4">Daftar Barang & Aset Gereja</h2>
            <p class="text-slate-500 max-w-2xl mx-auto text-lg">Pantau ketersediaan dan kondisi barang inventaris GKI Delima secara transparan.</p>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-12">
        <!-- Filters -->
        <form action="{{ route('home') }}" method="GET" class="flex flex-col md:flex-row gap-6 mb-12">
            <div class="flex-1 relative group">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama barang atau kode..." 
                    class="w-full pl-12 pr-4 py-4 bg-white border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all font-medium text-sm shadow-sm">
            </div>
            <div class="flex gap-4">
                <select name="category_id" class="px-6 py-4 bg-white border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-50 transition-all text-sm font-bold text-slate-600">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-8 py-4 bg-blue-600 text-white rounded-2xl font-black uppercase text-xs shadow-xl shadow-blue-200 hover:bg-blue-700 transition-all">Filter</button>
            </div>
        </form>

        <!-- Items Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @forelse($items as $item)
            <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm hover:shadow-xl hover:scale-[1.02] transition-all p-6 flex flex-col gap-6 overflow-hidden relative group">
                <!-- Image Placeholder -->
                <div class="aspect-square bg-slate-50 rounded-2xl flex items-center justify-center text-slate-200 overflow-hidden relative">
                    <i data-lucide="image" class="w-12 h-12"></i>
                    <div class="absolute top-4 right-4">
                        <span class="px-3 py-1 bg-white/90 backdrop-blur-md text-[10px] font-black uppercase text-slate-600 rounded-full border border-slate-100">{{ $item->category->name ?? 'Aset' }}</span>
                    </div>
                </div>

                <div class="flex flex-col gap-1">
                    <h3 class="text-lg font-black text-slate-800 line-clamp-1">{{ $item->name }}</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $item->kode_aset }}</p>
                </div>

                <div class="flex flex-col gap-3">
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-bold text-slate-400 uppercase tracking-tighter">Lokasi</span>
                        <span class="font-black text-slate-700">{{ $item->room->name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-bold text-slate-400 uppercase tracking-tighter">Kondisi</span>
                        <div class="flex gap-1">
                            @if($item->qty_baik > 0) <span class="w-2 h-2 rounded-full bg-green-500" title="Baik"></span> @endif
                            @if($item->qty_rusak_ringan > 0) <span class="w-2 h-2 rounded-full bg-amber-500" title="Rusak Ringan"></span> @endif
                            @if($item->qty_rusak_berat > 0) <span class="w-2 h-2 rounded-full bg-red-500" title="Rusak Berat"></span> @endif
                        </div>
                    </div>
                    <div class="flex justify-between items-center text-xs pt-3 border-t border-slate-50">
                        <span class="font-bold text-slate-400 uppercase tracking-tighter">Tersedia</span>
                        <span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded-md font-black">{{ $item->qty_tersedia }} Unit</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-20 text-center flex flex-col items-center gap-4">
                <div class="w-20 h-20 bg-slate-50 text-slate-200 rounded-full flex items-center justify-center">
                    <i data-lucide="package-search" class="w-10 h-10"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-700">Barang tidak ditemukan</h3>
                <p class="text-slate-400">Coba gunakan kata kunci pencarian lain.</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($items->hasPages())
        <div class="mt-16 flex justify-center">
            {{ $items->links() }}
        </div>
        @endif
    </main>

    <footer class="bg-white border-t border-slate-100 py-12 mt-20">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">&copy; {{ date('Y') }} GKI Delima. All rights reserved.</p>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
