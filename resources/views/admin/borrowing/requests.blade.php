@extends('layouts.master')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800">Request Peminjaman</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola permintaan peminjaman dari user</p>
        </div>
        <a href="{{ route('borrowing.index') }}" class="px-6 py-3 bg-slate-100 text-slate-600 rounded-2xl text-sm font-bold hover:bg-slate-200 transition-all flex items-center gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Kembali ke Peminjaman
        </a>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
            <span class="text-sm font-semibold text-emerald-700">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-red-50 border border-red-200 rounded-2xl flex items-center gap-3">
            <i data-lucide="alert-circle" class="w-5 h-5 text-red-600"></i>
            <span class="text-sm font-semibold text-red-700">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Pending Badge -->
    <div class="flex items-center gap-2">
        <span class="px-4 py-2 bg-amber-50 text-amber-700 rounded-xl text-sm font-bold border border-amber-200">
            {{ $pendingCount }} request menunggu persetujuan
        </span>
    </div>

    <!-- Requests Table -->
    <div class="bg-white rounded-[32px] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-4 text-left text-[11px] font-black text-slate-500 uppercase tracking-wider">Pemohon</th>
                        <th class="px-6 py-4 text-left text-[11px] font-black text-slate-500 uppercase tracking-wider">Barang</th>
                        <th class="px-6 py-4 text-center text-[11px] font-black text-slate-500 uppercase tracking-wider">Qty</th>
                        <th class="px-6 py-4 text-left text-[11px] font-black text-slate-500 uppercase tracking-wider">Periode</th>
                        <th class="px-6 py-4 text-left text-[11px] font-black text-slate-500 uppercase tracking-wider">Alasan</th>
                        <th class="px-6 py-4 text-center text-[11px] font-black text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-[11px] font-black text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($requests as $req)
                    <tr class="hover:bg-blue-50/30 transition-colors">
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-slate-700">{{ $req->nama_peminjam ?? '-' }}</span>
                            <p class="text-[11px] text-slate-400">{{ $req->kontak_peminjam ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-slate-700">{{ $req->item->name ?? 'Barang tidak ditemukan' }}</span>
                            <p class="text-[11px] text-slate-400">{{ $req->item->kode_aset ?? '-' }} · {{ $req->item->category->name ?? '-' }}</p>
                            <p class="text-[11px] text-slate-400">Stok tersedia: <strong class="{{ ($req->item->qty_tersedia ?? 0) >= $req->qty ? 'text-emerald-600' : 'text-red-500' }}">{{ $req->item->qty_tersedia ?? 0 }}</strong></p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-sm font-black text-slate-700">{{ $req->qty }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs text-slate-600">{{ \Carbon\Carbon::parse($req->start_date)->format('d M Y') }}</span>
                            <br>
                            <span class="text-xs text-slate-400">s/d {{ \Carbon\Carbon::parse($req->end_date)->format('d M Y') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-xs text-slate-600 max-w-[200px] truncate" title="{{ $req->reason }}">{{ $req->reason }}</p>
                            @if($req->catatan)
                                <p class="text-[11px] text-slate-400 mt-1">{{ $req->catatan }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 rounded-full text-xs font-bold
                                @switch($req->status)
                                    @case('Pending') bg-amber-100 text-amber-700 @break
                                    @case('Disetujui') bg-emerald-100 text-emerald-700 @break
                                    @case('Ditolak') bg-red-100 text-red-700 @break
                                    @default bg-slate-100 text-slate-600
                                @endswitch
                            ">{{ $req->status }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($req->status === 'Pending')
                            <div class="flex items-center justify-center gap-2">
                                <form method="POST" action="{{ route('borrowing.approve', $req->id) }}"
                                    onsubmit="return confirmSubmit(this, {title: 'Setujui Peminjaman?', message: 'Stok barang akan berkurang {{ $req->qty }} unit.', icon: 'check-circle', color: 'emerald', confirmText: 'Ya, Setujui'})">
                                    @csrf
                                    <button type="submit" class="p-2 bg-emerald-50 text-emerald-600 rounded-xl hover:bg-emerald-100 transition-all" title="Setujui">
                                        <i data-lucide="check" class="w-4 h-4"></i>
                                    </button>
                                </form>
                                <button onclick="openRejectModal({{ $req->id }})" class="p-2 bg-red-50 text-red-500 rounded-xl hover:bg-red-100 transition-all" title="Tolak">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                </button>
                            </div>
                            @else
                                @if($req->approver)
                                    <p class="text-[11px] text-slate-400 text-center">oleh {{ $req->approver->name }}</p>
                                @endif
                                @if($req->reject_reason)
                                    <p class="text-[11px] text-red-500 text-center mt-1" title="{{ $req->reject_reason }}">{{ Str::limit($req->reject_reason, 30) }}</p>
                                @endif
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-sm text-slate-400">Tidak ada request peminjaman.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($requests->hasPages())
    <div class="flex justify-center">
        {{ $requests->links() }}
    </div>
    @endif
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-slate-900/50 hidden items-center justify-center z-[200] backdrop-blur-sm">
    <div class="bg-white rounded-[32px] w-full max-w-md overflow-hidden shadow-2xl p-8">
        <div class="flex items-center justify-center mb-6">
            <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center">
                <i data-lucide="x-circle" class="w-8 h-8 text-red-500"></i>
            </div>
        </div>
        <h3 class="text-lg font-black text-slate-800 text-center mb-2">Tolak Peminjaman</h3>
        <p class="text-sm text-slate-500 text-center mb-6">Berikan alasan penolakan untuk pemohon</p>
        <form id="rejectForm" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-2">Alasan Penolakan *</label>
                <textarea name="reject_reason" rows="3" required class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-4 focus:ring-red-50 focus:border-red-400 transition-all resize-none" placeholder="Contoh: Stok barang sedang habis..."></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden'); document.getElementById('rejectModal').classList.remove('flex')" 
                    class="flex-1 px-6 py-3.5 bg-slate-100 text-slate-600 rounded-2xl text-sm font-bold hover:bg-slate-200 transition-all">Batal</button>
                <button type="submit" class="flex-[2] px-6 py-3.5 bg-red-600 text-white rounded-2xl text-sm font-bold hover:bg-red-700 shadow-lg shadow-red-200 transition-all">Tolak Peminjaman</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openRejectModal(orderId) {
    document.getElementById('rejectForm').action = '/admin/borrowing/requests/' + orderId + '/reject';
    document.getElementById('rejectModal').classList.remove('hidden');
    document.getElementById('rejectModal').classList.add('flex');
}
</script>
@endpush
