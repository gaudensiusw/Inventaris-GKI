<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stock Opname - {{ $stockOpname->tgl_audit }}</title>
    <style>
        body { font-family: 'Inter', sans-serif; color: #1e293b; line-height: 1.5; margin: 0; padding: 40px; }
        .header { text-align: center; margin-bottom: 40px; border-bottom: 2px solid #e2e8f0; padding-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 800; color: #0f172a; }
        .header p { margin: 5px 0 0; font-size: 14px; color: #64748b; }
        
        .meta-grid { display: grid; grid-template-cols: 1fr 1fr; gap: 20px; margin-bottom: 30px; }
        .meta-item { background: #f8fafc; padding: 15px; border-radius: 12px; }
        .meta-label { font-size: 10px; font-bold; text-transform: uppercase; color: #94a3b8; letter-spacing: 0.05em; }
        .meta-value { font-size: 14px; font-weight: 700; color: #334155; margin-top: 4px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #f1f5f9; padding: 12px 15px; text-align: left; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #475569; border-bottom: 2px solid #e2e8f0; }
        td { padding: 12px 15px; font-size: 13px; border-bottom: 1px solid #f1f5f9; }
        
        .status-badge { display: inline-block; padding: 2px 8px; border-radius: 99px; font-size: 10px; font-weight: 700; text-transform: uppercase; }
        .status-ok { background: #f0fdf4; color: #166534; }
        .status-diff { background: #fef2f2; color: #991b1b; }
        
        .footer { margin-top: 60px; display: grid; grid-template-cols: 1fr 1fr; gap: 40px; }
        .signature { text-align: center; }
        .signature-line { margin-top: 60px; border-top: 1px solid #334155; display: inline-block; width: 200px; }
        
        @media print {
            body { padding: 20px; }
            button { display: none; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h1>LAPORAN STOCK OPNAME</h1>
        <p>Inventaris GKI - Manajemen Aset Gereja</p>
    </div>

    <div class="meta-grid">
        <div class="meta-item">
            <div class="meta-label">ID Audit</div>
            <div class="meta-value">SO-{{ \Carbon\Carbon::parse($stockOpname->tgl_audit)->format('Ymd') }}-{{ str_pad($stockOpname->id_so ?? $stockOpname->id, 3, '0', STR_PAD_LEFT) }}</div>
        </div>
        <div class="meta-item">
            <div class="meta-label">Tanggal Audit</div>
            <div class="meta-value">{{ \Carbon\Carbon::parse($stockOpname->tgl_audit)->translatedFormat('d F Y') }}</div>
        </div>
        <div class="meta-item">
            <div class="meta-label">Auditor</div>
            <div class="meta-value">{{ $stockOpname->user->name ?? 'System' }}</div>
        </div>
        <div class="meta-item">
            <div class="meta-label">Total Item</div>
            <div class="meta-value">{{ $stockOpname->details->count() }} Barang</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Kode Aset</th>
                <th>Sistem</th>
                <th>Fisik</th>
                <th>Selisih</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stockOpname->details as $detail)
            <tr>
                <td style="font-weight: 700;">{{ $detail->item->name ?? 'Barang Terhapus' }}</td>
                <td style="font-family: monospace;">{{ $detail->item->kode_aset ?? '-' }}</td>
                <td>{{ $detail->stok_sistem }}</td>
                <td style="font-weight: 700;">{{ $detail->stok_fisik }}</td>
                <td>
                    @if($detail->selisih == 0)
                        <span class="status-badge status-ok">Pas</span>
                    @else
                        <span class="status-badge status-diff">{{ $detail->selisih > 0 ? '+' : '' }}{{ $detail->selisih }}</span>
                    @endif
                </td>
                <td style="font-style: italic; color: #64748b; font-size: 11px;">{{ $detail->keterangan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="signature">
            <p style="font-size: 12px; margin-bottom: 40px;">Auditor,</p>
            <div class="signature-line"></div>
            <p style="font-size: 13px; font-weight: 700; margin-top: 5px;">{{ $stockOpname->user->name ?? '....................' }}</p>
        </div>
        <div class="signature">
            <p style="font-size: 12px; margin-bottom: 40px;">Mengetahui,</p>
            <div class="signature-line"></div>
            <p style="font-size: 13px; font-weight: 700; margin-top: 5px;">Majelis / PJ Inventaris</p>
        </div>
    </div>

    <div class="no-print" style="margin-top: 40px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #2563eb; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 700;">Cetak Ulang</button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #64748b; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 700; margin-left: 10px;">Tutup</button>
    </div>
</body>
</html>
