<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Inventaris GKI Delima</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #1e293b; line-height: 1.5; }

        .header { text-align: center; padding: 20px 0; border-bottom: 3px solid #3b82f6; margin-bottom: 20px; }
        .header h1 { font-size: 20px; color: #1e293b; margin-bottom: 4px; }
        .header p { font-size: 12px; color: #64748b; }
        .header .period { font-size: 13px; color: #3b82f6; font-weight: 700; margin-top: 6px; }

        .section { margin-bottom: 20px; }
        .section-title { font-size: 14px; font-weight: 700; color: #1e293b; margin-bottom: 10px; padding-bottom: 6px; border-bottom: 1px solid #e2e8f0; }

        .stats { display: table; width: 100%; margin-bottom: 20px; }
        .stat-box { display: table-cell; width: 25%; text-align: center; padding: 12px; border: 1px solid #e2e8f0; }
        .stat-box .value { font-size: 22px; font-weight: 700; color: #1e293b; }
        .stat-box .label { font-size: 10px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        table th { background: #f8fafc; color: #64748b; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; padding: 8px 10px; text-align: left; border-bottom: 2px solid #e2e8f0; }
        table td { padding: 7px 10px; border-bottom: 1px solid #f1f5f9; font-size: 11px; }
        table tbody tr:nth-child(even) { background: #fafbfc; }

        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: 600; }
        .badge-green { background: #f0fdf4; color: #16a34a; }
        .badge-red { background: #fef2f2; color: #dc2626; }
        .badge-blue { background: #eff6ff; color: #2563eb; }
        .badge-orange { background: #fffbeb; color: #d97706; }

        .summary-row { display: table; width: 100%; margin-bottom: 16px; }
        .summary-col { display: table-cell; width: 50%; padding-right: 10px; vertical-align: top; }
        .summary-col:last-child { padding-right: 0; padding-left: 10px; }

        .footer { text-align: center; padding-top: 16px; border-top: 1px solid #e2e8f0; color: #94a3b8; font-size: 10px; margin-top: 20px; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN INVENTARIS</h1>
        <p>Gereja Kristen Indonesia (GKI) Delima</p>
        <div class="period">Periode: {{ $monthName }}</div>
    </div>

    <!-- Stats Summary -->
    <div class="stats">
        <div class="stat-box">
            <div class="value">{{ $totalTypes }}</div>
            <div class="label">Total Kategori</div>
        </div>
        <div class="stat-box">
            <div class="value">{{ $totalQuantity }}</div>
            <div class="label">Total Quantity</div>
        </div>
        <div class="stat-box">
            <div class="value">Rp {{ number_format($totalValue) }}</div>
            <div class="label">Total Nilai Aset</div>
        </div>
        <div class="stat-box">
            <div class="value" style="color:#ef4444;">{{ $needsAttention }}</div>
            <div class="label">Perlu Perhatian</div>
        </div>
    </div>

    <!-- Status & Condition Summary -->
    <div class="summary-row">
        <div class="summary-col">
            <div class="section">
                <div class="section-title">Status Operasional</div>
                <table>
                    <thead><tr><th>Status</th><th>Jumlah</th></tr></thead>
                    <tbody>
                        @foreach($statusData as $label => $count)
                        <tr>
                            <td>{{ $label }}</td>
                            <td><strong>{{ $count }}</strong> unit</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="summary-col">
            <div class="section">
                <div class="section-title">Kondisi Fisik</div>
                <table>
                    <thead><tr><th>Kondisi</th><th>Jumlah</th></tr></thead>
                    <tbody>
                        @foreach($conditionData as $label => $count)
                        <tr>
                            <td>{{ $label }}</td>
                            <td><strong>{{ $count }}</strong> unit</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Per Kategori -->
    <div class="section">
        <div class="section-title">Ringkasan per Kategori</div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kategori</th>
                    <th>Jumlah Item</th>
                    <th>Total Qty</th>
                    <th>Nilai Aset</th>
                    <th>Baik</th>
                    <th>Rusak</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categoryStats as $i => $cat)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><strong>{{ $cat['name'] }}</strong></td>
                    <td>{{ $cat['items_count'] }}</td>
                    <td>{{ $cat['total_qty'] }}</td>
                    <td>Rp {{ number_format($cat['total_value']) }}</td>
                    <td><span class="badge badge-green">{{ $cat['baik'] }}</span></td>
                    <td><span class="badge badge-red">{{ $cat['rusak'] }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Daftar Barang -->
    <div class="section">
        <div class="section-title">Daftar Seluruh Barang</div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Barang</th>
                    <th>Kode</th>
                    <th>Kategori</th>
                    <th>Lokasi</th>
                    <th>Qty</th>
                    <th>Kondisi</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><strong>{{ $item->name }}</strong></td>
                    <td>{{ $item->barcode }}</td>
                    <td>{{ $item->category->name ?? '-' }}</td>
                    <td>{{ $item->room->name ?? '-' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>
                        @php
                            $condBadge = $item->condition == 'Baik' ? 'green' : ($item->condition == 'Rusak ringan' ? 'orange' : 'red');
                        @endphp
                        <span class="badge badge-{{ $condBadge }}">{{ $item->condition }}</span>
                    </td>
                    <td>
                        @php
                            $statusBadge = $item->status == 'Tersedia' ? 'green' : ($item->status == 'Dipinjam' ? 'blue' : ($item->status == 'Hilang' ? 'red' : 'orange'));
                        @endphp
                        <span class="badge badge-{{ $statusBadge }}">{{ $item->status }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y - H:i') }} WIB</p>
        <p>Sistem Inventaris GKI Delima</p>
    </div>
</body>
</html>
