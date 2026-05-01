@extends('layouts.master')

@section('content')
<div class="flex flex-col gap-8">
    <!-- Header Section -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Mulai Audit (Stock Opname)</h1>
            <p class="text-slate-500 text-sm mt-1">Lakukan verifikasi stok fisik di lapangan.</p>
        </div>
        <a href="{{ route('stock-opname.index') }}" class="px-4 py-2 text-slate-500 hover:text-slate-700 font-semibold flex items-center gap-2 transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
            <span>Kembali</span>
        </a>
    </div>

    <form id="auditForm" action="{{ route('stock-opname.store') }}" method="POST">
        @csrf
        
        @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl flex flex-col gap-2">
            <div class="flex items-center gap-2 text-red-600 font-bold">
                <i data-lucide="alert-circle" class="w-5 h-5"></i>
                <span>Terdapat Kesalahan Input</span>
            </div>
            <ul class="list-disc list-inside text-sm text-red-500 ml-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl flex items-center gap-3 text-red-600 font-bold">
            <i data-lucide="x-circle" class="w-5 h-5"></i>
            <span class="text-sm">{{ session('error') }}</span>
        </div>
        @endif
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Items List -->
            <div class="lg:col-span-2 flex flex-col gap-6">
                <div class="card-premium shadow-card p-0 overflow-hidden">
                    <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <h3 class="font-bold text-slate-700">Daftar Barang</h3>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">{{ count($items) }} Barang Terdaftar</p>
                        </div>
                        <div class="relative w-full sm:w-64 group">
                            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                            <input type="text" id="item-search" placeholder="Cari nama atau kode..." 
                                class="w-full pl-9 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all shadow-sm">
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse" id="audit-table">
                            <thead>
                                <tr class="border-b border-slate-50">
                                    <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama / ID</th>
                                    <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Stok Sistem</th>
                                    <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-32">Stok Fisik</th>
                                    <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($items as $index => $item)
                                @php $totalSystem = $item->qty_baik + $item->qty_rusak_ringan + $item->qty_rusak_berat; @endphp
                                <tr class="item-row hover:bg-slate-50/30 transition-colors">
                                    <input type="hidden" name="items[{{ $index }}][item_id]" value="{{ $item->id }}">
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-slate-700 item-name">{{ $item->name }}</span>
                                            <span class="text-[10px] text-slate-400 item-code">{{ $item->kode_aset }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-semibold text-slate-600">{{ $totalSystem }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="number" name="items[{{ $index }}][physical_qty]" value="{{ $totalSystem }}" 
                                            class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all font-bold text-blue-600">
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="text" name="items[{{ $index }}][notes]" placeholder="Catatan item..." 
                                            class="w-full px-3 py-1.5 bg-transparent border-b border-slate-100 text-xs focus:outline-none focus:border-blue-400 transition-all italic">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Column: Audit Info -->
            <div class="flex flex-col gap-6">
                <div class="card-premium shadow-card p-6 flex flex-col gap-6 sticky top-24">
                    <h3 class="font-bold text-slate-700 border-b border-slate-100 pb-4">Ringkasan Audit</h3>
                    
                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Tanggal Audit</label>
                        <input type="date" name="audit_date" value="{{ date('Y-m-d') }}" 
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all shadow-sm">
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Catatan Audit</label>
                        <textarea name="notes" rows="4" placeholder="Contoh: Audit rutin triwulan..." 
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all shadow-sm"></textarea>
                    </div>

                    <div class="pt-4">
                        <button type="button" onclick="confirmAudit()" class="w-full px-6 py-4 bg-emerald-600 text-white rounded-2xl text-sm font-black uppercase shadow-xl shadow-emerald-200 hover:bg-emerald-700 active:scale-[0.98] transition-all flex items-center justify-center gap-3">
                            <i data-lucide="check-circle" class="w-5 h-5"></i>
                            <span>Selesaikan Audit</span>
                        </button>
                        <div class="mt-4 p-4 bg-amber-50 rounded-xl border border-amber-100 flex gap-3">
                            <i data-lucide="alert-triangle" class="w-4 h-4 text-amber-500 shrink-0"></i>
                            <p class="text-[10px] text-amber-700 font-medium italic leading-relaxed">
                                Stok sistem akan diperbarui secara otomatis setelah audit diselesaikan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        function confirmAudit() {
            showConfirm({
                title: 'Selesaikan Audit?',
                message: 'Data stok sistem akan diperbarui sesuai dengan jumlah fisik yang Anda input!',
                color: 'emerald',
                icon: 'clipboard-check',
                onConfirm: () => {
                    document.getElementById('auditForm').submit();
                }
            });
        }
        document.getElementById('item-search').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.item-row');
            
            rows.forEach(row => {
                const name = row.querySelector('.item-name').innerText.toLowerCase();
                const code = row.querySelector('.item-code').innerText.toLowerCase();
                
                if (name.includes(searchTerm) || code.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</div>
@endsection
