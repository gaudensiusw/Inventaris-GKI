<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Inventaris GKI Delima</title>
    <link rel="icon" href="{{ asset('icon.png') }}" type="image/x-icon" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
    <style>
        body { background: #f8fafc; }
        .public-header {
            background: linear-gradient(135deg, #0f1729 0%, #1e293b 100%);
            color: #fff;
            padding: 32px 40px;
        }
        .public-header-inner {
            max-width: 1200px;
            margin: 0 auto;
        }
        .public-header .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }
        .public-header .brand-icon {
            width: 40px; height: 40px; border-radius: 10px;
            background: #3b82f6; display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 800; font-size: 13px;
        }
        .public-header .brand h2 { font-size: 18px; font-weight: 700; }
        .public-header .brand span { font-size: 12px; color: #94a3b8; }
        .public-stats { display: flex; gap: 16px; }
        .public-stat {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px; padding: 16px 20px; min-width: 160px;
        }
        .public-stat .value { font-size: 24px; font-weight: 700; }
        .public-stat .label { font-size: 11px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; }
        .public-content { padding: 24px 40px; max-width: 1200px; margin: 0 auto; }
        .public-nav { display: flex; justify-content: space-between; align-items: center; }
        .login-btn {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);
            color: #fff; text-decoration: none; font-size: 13px; font-weight: 600;
            padding: 8px 16px; border-radius: 8px; transition: all 0.2s;
        }
        .login-btn:hover { background: rgba(255,255,255,0.2); }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="public-header">
        <div class="public-header-inner">
            <div class="public-nav">
                <div class="brand">
                    <div class="brand-icon">GKI</div>
                    <div>
                        <h2>GKI Delima</h2>
                        <span>Sistem Inventaris</span>
                    </div>
                </div>
                <a href="{{ url('/login') }}" class="login-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2"/><path d="M20 12h-13l3 -3m0 6l-3 -3"/></svg>
                    Login Admin
                </a>
            </div>

            <h1 style="font-size:28px;font-weight:800;margin-bottom:6px;">Data Inventaris</h1>
            <p style="color:#94a3b8;font-size:14px;margin-bottom:20px;">Lihat daftar seluruh barang inventaris GKI Delima</p>

            <div class="public-stats">
                <div class="public-stat">
                    <div class="value">{{ $totalItems }}</div>
                    <div class="label">Total Barang</div>
                </div>
                <div class="public-stat">
                    <div class="value" style="color:#22c55e;">{{ $totalBaik }}</div>
                    <div class="label">Kondisi Baik</div>
                </div>
                <div class="public-stat">
                    <div class="value" style="color:#3b82f6;">Rp {{ number_format($totalValue / 1000000, 1) }}jt</div>
                    <div class="label">Total Nilai Aset</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="public-content">
        <!-- Filters -->
        <div class="data-table-wrapper">
            <div class="table-toolbar">
                <div class="table-search">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><circle cx="10" cy="10" r="7"/><line x1="21" y1="21" x2="15" y2="15"/></svg>
                    <form action="{{ url('/inventaris') }}" method="GET" style="width:100%;display:flex;gap:8px;align-items:center;">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari barang...">
                        <select name="category" class="form-control" style="width:auto;padding:8px 12px;font-size:13px;" onchange="this.form.submit()">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <select name="condition" class="form-control" style="width:auto;padding:8px 12px;font-size:13px;" onchange="this.form.submit()">
                            <option value="">Semua Kondisi</option>
                            <option value="Baik" {{ $condition == 'Baik' ? 'selected' : '' }}>Baik</option>
                            <option value="Rusak ringan" {{ $condition == 'Rusak ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="Rusak berat" {{ $condition == 'Rusak berat' ? 'selected' : '' }}>Rusak Berat</option>
                        </select>
                    </form>
                </div>
            </div>

            <table class="data-table">
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
                    @forelse($items as $i => $item)
                    <tr>
                        <td>{{ $i + $items->firstItem() }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                @if($item->image)
                                    <div style="width:32px;height:32px;border-radius:8px;overflow:hidden;background:#f1f5f9;flex-shrink:0;">
                                        <img src="{{ asset('storage/items/' . $item->image) }}" alt="" style="width:100%;height:100%;object-fit:cover;">
                                    </div>
                                @else
                                    <div style="width:32px;height:32px;border-radius:8px;background:#eff6ff;color:#3b82f6;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:11px;flex-shrink:0;">
                                        {{ strtoupper(substr($item->name, 0, 2)) }}
                                    </div>
                                @endif
                                <span style="font-weight:600;">{{ $item->name }}</span>
                            </div>
                        </td>
                        <td style="color:var(--text-muted);font-family:monospace;font-size:12px;">{{ $item->barcode }}</td>
                        <td>
                            <span class="badge badge-blue">{{ $item->category->name ?? '-' }}</span>
                        </td>
                        <td>{{ $item->room->name ?? '-' }}</td>
                        <td style="font-weight:600;">{{ $item->quantity }}</td>
                        <td>
                            @php
                                $condBadge = $item->condition == 'Baik' ? 'green' : ($item->condition == 'Rusak ringan' ? 'orange' : 'red');
                            @endphp
                            <span class="badge badge-{{ $condBadge }} badge-dot">{{ $item->condition }}</span>
                        </td>
                        <td>
                            @php
                                $statusBadge = match($item->status) {
                                    'Tersedia' => 'green',
                                    'Dipinjam' => 'blue',
                                    'Hilang' => 'red',
                                    default => 'orange'
                                };
                            @endphp
                            <span class="badge badge-{{ $statusBadge }}">{{ $item->status }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align:center;padding:40px;color:var(--text-muted);">
                            Belum ada data barang inventaris.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            @if($items->hasPages())
            <div style="padding:12px 20px;border-top:1px solid var(--border-color);display:flex;justify-content:center;">
                {{ $items->links() }}
            </div>
            @endif
        </div>

        <div style="text-align:center;padding:24px 0;color:var(--text-muted);font-size:12px;">
            © {{ date('Y') }} Sistem Inventaris GKI Delima — Halaman publik (read-only)
        </div>
    </div>
</body>
</html>
