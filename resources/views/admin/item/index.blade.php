@extends('layouts.master', ['title' => 'Inventaris'])

@section('content')
<div class="page-header">
    <div>
        <h1>Inventaris</h1>
        <p>{{ $items->total() }} dari {{ $items->total() }} barang ditampilkan</p>
    </div>
    <a href="{{ route('admin.item.create') }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Barang
    </a>
</div>

<div class="data-table-wrapper">
    <div class="table-toolbar">
        <div class="table-search">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <form action="{{ route('admin.item.index') }}" method="GET">
                <input type="text" name="search" placeholder="Cari nama barang, ID, atau deskripsi..." value="{{ request('search') }}">
            </form>
        </div>
        <div class="table-actions">
            <button class="btn btn-outline btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5.5 5h13a1 1 0 0 1 .5 1.5l-5 5.5v7l-4 -3v-4l-5 -5.5a1 1 0 0 1 .5 -1.5"/></svg>
                Filter
            </button>
            <button class="btn btn-outline btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"/><polyline points="7 11 12 16 17 11"/><line x1="12" y1="4" x2="12" y2="16"/></svg>
                Export
            </button>
            <button class="btn btn-outline btn-sm" onclick="window.print()">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2"/><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4"/><rect x="7" y="13" width="10" height="8" rx="2"/></svg>
                Print
            </button>
        </div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>ID / Nama</th>
                <th>Kategori</th>
                <th>Kondisi</th>
                <th>Lokasi</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:32px;height:32px;border-radius:8px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="#64748b" fill="none"><polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3"/></svg>
                        </div>
                        <div>
                            <div style="font-weight:600;color:#1e293b;">{{ $item->name }}</div>
                            <div style="font-size:11px;color:#94a3b8;">{{ $item->inv_code }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    @php
                        $catColors = ['Per Ruangan' => 'blue', 'Kendaraan' => 'purple', 'Alat Musik' => 'orange', 'Multimedia' => 'teal', 'Logistik' => 'green'];
                        $catColor = $catColors[$item->category->name] ?? 'gray';
                    @endphp
                    <span class="badge badge-{{ $catColor }}">{{ $item->category->name ?? '-' }}</span>
                </td>
                <td>
                    @php
                        $conditions = [];
                        if($item->qty_baik > 0) $conditions[] = ['label' => $item->qty_baik . ' Baik', 'color' => 'green'];
                        if($item->qty_rusak_ringan > 0) $conditions[] = ['label' => $item->qty_rusak_ringan . ' Rusak', 'color' => 'orange'];
                        if($item->qty_rusak_berat > 0) $conditions[] = ['label' => $item->qty_rusak_berat . ' Rusak', 'color' => 'red'];
                        if(empty($conditions)) {
                            $condColor = $item->condition == 'Baik' ? 'green' : ($item->condition == 'Rusak ringan' ? 'orange' : 'red');
                            $conditions[] = ['label' => $item->condition, 'color' => $condColor];
                        }
                    @endphp
                    <div style="display:flex;gap:4px;flex-wrap:wrap;">
                        @foreach($conditions as $cond)
                        <span class="badge badge-{{ $cond['color'] }}">✓ {{ $cond['label'] }}</span>
                        @endforeach
                    </div>
                </td>
                <td>{{ $item->room->name ?? '-' }}</td>
                <td>
                    <div>
                        <strong>Total: {{ $item->quantity }}</strong>
                        @if($item->qty_tersedia > 0)
                        <span class="badge badge-green badge-dot" style="margin-left:4px;">{{ $item->qty_tersedia }} Tersedia</span>
                        @endif
                        @if($item->qty_dipinjam > 0)
                        <span class="badge badge-blue badge-dot" style="margin-left:4px;">{{ $item->qty_dipinjam }} Dipinjam</span>
                        @endif
                        @if($item->qty_hilang > 0)
                        <span class="badge badge-red badge-dot" style="margin-left:4px;">{{ $item->qty_hilang }} Hilang</span>
                        @endif
                    </div>
                </td>
                <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                <td>
                    <div style="display:flex;gap:4px;">
                        <a href="{{ route('admin.item.barcode', $item) }}" class="btn btn-icon btn-outline btn-sm" title="Barcode">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><rect x="4" y="4" width="6" height="6"/><rect x="14" y="4" width="6" height="6"/><rect x="4" y="14" width="6" height="6"/></svg>
                        </a>
                        <a href="{{ route('admin.item.edit', $item) }}" class="btn btn-icon btn-outline btn-sm" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4"/><line x1="13.5" y1="6.5" x2="17.5" y2="10.5"/></svg>
                        </a>
                        <button onclick="deleteData({{ $item->id }})" class="btn btn-icon btn-sm" style="background:#fef2f2;color:#ef4444;" title="Hapus">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><line x1="4" y1="7" x2="20" y2="7"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/></svg>
                        </button>
                        <form id="delete-form-{{ $item->id }}" action="{{ route('admin.item.destroy', $item) }}" method="POST" style="display:none;">@csrf @method('DELETE')</form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7">
                    <div class="empty-state">
                        <p>Belum ada barang</p>
                        <span>Tambahkan barang inventaris baru</span>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($items->hasPages())
    <div class="pagination-wrapper">
        {{ $items->links() }}
    </div>
    @endif
</div>
@endsection
