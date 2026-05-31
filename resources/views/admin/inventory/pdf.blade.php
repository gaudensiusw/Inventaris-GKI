<!DOCTYPE html>
<html>
<head>
    <title>Daftar Inventaris Barang</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 25px; }
        .header h1 { margin: 0; font-size: 20px; color: #1e293b; }
        .header p { margin: 5px 0; color: #64748b; }
        
        .filter-info {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            color: #2563eb;
            margin-top: 5px;
            background: #eff6ff;
            padding: 8px 12px;
            border-radius: 6px;
            display: inline-block;
        }
        
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #e2e8f0; padding: 8px 10px; text-align: left; }
        th { background: #f8fafc; font-size: 9px; text-transform: uppercase; color: #475569; font-weight: bold; }
        
        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 4px;
        }
        .badge-success { background-color: #d1fae5; color: #065f46; }
        .badge-danger { background-color: #fee2e2; color: #991b1b; }
        .badge-warning { background-color: #fef3c7; color: #92400e; }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .footer { margin-top: 40px; text-align: right; font-size: 9px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Daftar Inventaris Barang</h1>
        <p>GKI Delima - Data per {{ date('d F Y') }}</p>
        
        @if((isset($search) && $search) || (isset($selectedRoom) && $selectedRoom) || (isset($selectedCategory) && $selectedCategory))
            <div class="filter-info">
                Filter Aktif: 
                @if($search) Pencarian "{{ $search }}" @endif
                @if($selectedRoom) {{ $search ? ' | ' : '' }} Ruangan: {{ $selectedRoom }} @endif
                @if($selectedCategory) {{ ($search || $selectedRoom) ? ' | ' : '' }} Kategori: {{ $selectedCategory }} @endif
            </div>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Kode Aset</th>
                <th style="width: 35%;">Nama Barang</th>
                <th style="width: 15%;">Kategori</th>
                <th style="width: 15%;">Ruangan</th>
                <th style="width: 7%;" class="text-right">Total</th>
                <th style="width: 8%;" class="text-right">Tersedia</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td style="font-family: monospace;">{{ $item->kode_aset }}</td>
                    <td style="font-weight: bold; color: #1e293b;">{{ $item->name }}</td>
                    <td>{{ $item->category->name ?? '-' }}</td>
                    <td>{{ $item->room->name ?? '-' }}</td>
                    <td class="text-right">{{ number_format($item->quantity) }}</td>
                    <td class="text-right font-bold {{ $item->qty_tersedia > 0 ? 'text-emerald-600' : 'text-red-500' }}">
                        {{ number_format($item->qty_tersedia) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="color: #64748b; padding: 20px;">
                        Tidak ada data inventaris barang yang sesuai dengan filter.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak otomatis oleh Sistem Inventaris GKI Delima pada {{ date('Y-m-d H:i') }}
    </div>
</body>
</html>
