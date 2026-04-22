<?php

namespace App\Http\Controllers\Admin;

use App\Models\Item;
use App\Models\Category;
use App\Models\Order;
use App\Models\Repair;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'bulanan');
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        // Stat cards
        $totalTypes = Category::count();
        $totalQuantity = Item::sum('quantity');
        $totalValue = Item::sum(DB::raw('price * quantity'));
        $needsAttention = Item::whereIn('condition', ['Rusak ringan', 'Rusak berat'])->count()
                        + Item::whereIn('status', ['Hilang', 'Sedang diperbaiki'])->count();

        // Status Operasional pie chart
        $statusData = [
            'Tersedia' => Item::where('status', 'Tersedia')->count(),
            'Dipinjam' => Item::where('status', 'Dipinjam')->count(),
            'Sedang Diperbaiki' => Item::where('status', 'Sedang diperbaiki')->count(),
            'Hilang' => Item::where('status', 'Hilang')->count(),
        ];

        // Kondisi Fisik pie chart
        $conditionData = [
            'Baik' => Item::where('condition', 'Baik')->count(),
            'Rusak Ringan' => Item::where('condition', 'Rusak ringan')->count(),
            'Rusak Berat' => Item::where('condition', 'Rusak berat')->count(),
        ];

        // Trend 6 bulan terakhir
        $trendLabels = [];
        $trendPeminjaman = [];
        $trendPerbaikan = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $trendLabels[] = $date->locale('id')->translatedFormat('M Y');
            $trendPeminjaman[] = Order::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)->count();
            $trendPerbaikan[] = Repair::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)->count();
        }

        return view('admin.report.index', compact(
            'period', 'month', 'year',
            'totalTypes', 'totalQuantity', 'totalValue', 'needsAttention',
            'statusData', 'conditionData',
            'trendLabels', 'trendPeminjaman', 'trendPerbaikan'
        ));
    }
}
