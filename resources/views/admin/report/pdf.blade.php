<!DOCTYPE html>
<html>
<head>
    <title>Laporan Inventaris</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; font-size: 24px; }
        .header p { margin: 5px 0; color: #666; }
        
        .section-title { font-weight: bold; font-size: 14px; border-bottom: 2px solid #eee; padding-bottom: 5px; margin-top: 20px; margin-bottom: 15px; }
        
        .grid { width: 100%; margin-bottom: 20px; }
        .grid-item { width: 33.33%; float: left; padding: 10px; box-sizing: border-box; }
        .card { background: #f9f9f9; padding: 15px; border-radius: 10px; text-align: center; }
        .card p { margin: 0; font-size: 10px; color: #888; text-transform: uppercase; }
        .card h2 { margin: 5px 0 0 0; font-size: 20px; }
        
        .clearfix::after { content: ""; clear: both; display: table; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #eee; padding: 10px; text-align: left; }
        th { background: #f5f5f5; font-size: 10px; text-transform: uppercase; }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .footer { margin-top: 50px; text-align: right; font-size: 10px; color: #aaa; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Inventaris</h1>
        <p>GKI Delima - Data per {{ date('d F Y') }}</p>
    </div>

    <div class="clearfix">
        <div class="grid-item">
            <div class="card">
                <p>Total Jenis Barang</p>
                <h2>{{ number_format($totalJenisBarang) }}</h2>
            </div>
        </div>
        <div class="grid-item">
            <div class="card">
                <p>Total Quantity</p>
                <h2>{{ number_format($totalQuantity) }}</h2>
            </div>
        </div>
        <div class="grid-item">
            <div class="card">
                <p>Perlu Perhatian</p>
                <h2>{{ number_format($perluPerhatian) }}</h2>
            </div>
        </div>
    </div>

    <div class="clearfix">
        <div style="width: 50%; float: left; padding-right: 10px;">
            <div class="section-title">Status Operasional</div>
            <table>
                <thead>
                    <tr><th>Status</th><th class="text-right">Unit</th></tr>
                </thead>
                <tbody>
                    @foreach($statusOperasional as $label => $val)
                    <tr><td>{{ $label }}</td><td class="text-right">{{ number_format($val) }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="width: 50%; float: left; padding-left: 10px;">
            <div class="section-title">Kondisi Fisik</div>
            <table>
                <thead>
                    <tr><th>Kondisi</th><th class="text-right">Unit</th></tr>
                </thead>
                <tbody>
                    @foreach($kondisiFisik as $label => $val)
                    <tr><td>{{ $label }}</td><td class="text-right">{{ number_format($val) }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="clearfix" style="margin-top: 20px;">
        <div class="section-title">Detail Per Kategori</div>
        <table>
            <thead>
                <tr>
                    <th>Kategori</th>
                    <th class="text-center">Jenis</th>
                    <th class="text-center">Total Unit</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categoryBreakdown as $cat)
                <tr>
                    <td>{{ $cat['name'] }}</td>
                    <td class="text-center">{{ $cat['items_count'] }}</td>
                    <td class="text-center">{{ number_format($cat['total_units']) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="clearfix" style="margin-top: 20px;">
        <div style="width: 50%; float: left; padding-right: 10px;">
            <div class="section-title">Aktivitas Peminjaman</div>
            <table>
                <tr><td>Total Periode Ini</td><td class="text-right">{{ $borrowingStats['total'] }}</td></tr>
                <tr><td>Sedang Dipinjam</td><td class="text-right">{{ $borrowingStats['current'] }}</td></tr>
                <tr><td>Sudah Dikembalikan</td><td class="text-right">{{ $borrowingStats['returned'] }}</td></tr>
            </table>
        </div>
        <div style="width: 50%; float: left; padding-left: 10px;">
            <div class="section-title">Aktivitas Perbaikan</div>
            <table>
                <tr><td>Total Periode Ini</td><td class="text-right">{{ $repairStats['total'] }}</td></tr>
                <tr><td>Sedang Diperbaiki</td><td class="text-right">{{ $repairStats['current'] }}</td></tr>
                <tr><td>Sudah Selesai</td><td class="text-right">{{ $repairStats['finished'] }}</td></tr>
            </table>
        </div>
    </div>

    <div class="footer">
        Dicetak otomatis oleh Sistem Inventaris GKI Delima pada {{ date('Y-m-d H:i') }}
    </div>
</body>
</html>
