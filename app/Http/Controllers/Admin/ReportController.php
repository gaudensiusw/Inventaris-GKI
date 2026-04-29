<?php

namespace App\Http\Controllers\Admin;

use App\Models\Item;
use App\Models\Category;
use App\Models\Order;
use App\Models\Repair;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'bulanan');
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        $tab = $request->get('tab', 'ringkasan');

        // Stat cards
        $totalTypes = Category::count();
        $totalQuantity = Item::sum('quantity');
        $totalValue = Item::sum(DB::raw('COALESCE(price, 0) * COALESCE(quantity, 1)'));
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

        // Tab: Per Kategori
        $categoryStats = Category::withCount('items')
            ->with(['items' => function($q) {
                $q->select('id', 'category_id', 'quantity', 'price', 'condition', 'status');
            }])
            ->get()
            ->map(function($cat) {
                return [
                    'name' => $cat->name,
                    'items_count' => $cat->items_count,
                    'total_qty' => $cat->items->sum('quantity'),
                    'total_value' => $cat->items->sum(fn($i) => ($i->price ?? 0) * ($i->quantity ?? 1)),
                    'baik' => $cat->items->where('condition', 'Baik')->count(),
                    'rusak' => $cat->items->whereIn('condition', ['Rusak ringan', 'Rusak berat'])->count(),
                ];
            });

        // Tab: Aktivitas (recent orders & repairs)
        $recentOrders = Order::with(['item', 'user'])
            ->latest()
            ->take(10)
            ->get();

        $recentRepairs = Repair::with('item')
            ->latest()
            ->take(10)
            ->get();

        // Tab: Keuangan
        $financeByCategory = Category::with(['items' => function($q) {
            $q->select('id', 'category_id', 'price', 'quantity');
        }])->get()->map(function($cat) {
            return [
                'name' => $cat->name,
                'total_value' => $cat->items->sum(fn($i) => ($i->price ?? 0) * ($i->quantity ?? 1)),
                'item_count' => $cat->items->count(),
            ];
        })->sortByDesc('total_value')->values();

        return view('admin.report.index', compact(
            'period', 'month', 'year', 'tab',
            'totalTypes', 'totalQuantity', 'totalValue', 'needsAttention',
            'statusData', 'conditionData',
            'trendLabels', 'trendPeminjaman', 'trendPerbaikan',
            'categoryStats', 'recentOrders', 'recentRepairs', 'financeByCategory'
        ));
    }

    public function exportPdf(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $totalTypes = Category::count();
        $totalQuantity = Item::sum('quantity');
        $totalValue = Item::sum(DB::raw('COALESCE(price, 0) * COALESCE(quantity, 1)'));
        $needsAttention = Item::whereIn('condition', ['Rusak ringan', 'Rusak berat'])->count()
                        + Item::whereIn('status', ['Hilang', 'Sedang diperbaiki'])->count();

        $statusData = [
            'Tersedia' => Item::where('status', 'Tersedia')->count(),
            'Dipinjam' => Item::where('status', 'Dipinjam')->count(),
            'Sedang Diperbaiki' => Item::where('status', 'Sedang diperbaiki')->count(),
            'Hilang' => Item::where('status', 'Hilang')->count(),
        ];

        $conditionData = [
            'Baik' => Item::where('condition', 'Baik')->count(),
            'Rusak Ringan' => Item::where('condition', 'Rusak ringan')->count(),
            'Rusak Berat' => Item::where('condition', 'Rusak berat')->count(),
        ];

        $categoryStats = Category::withCount('items')
            ->with(['items' => function($q) {
                $q->select('id', 'category_id', 'quantity', 'price', 'condition');
            }])
            ->get()
            ->map(function($cat) {
                return [
                    'name' => $cat->name,
                    'items_count' => $cat->items_count,
                    'total_qty' => $cat->items->sum('quantity'),
                    'total_value' => $cat->items->sum(fn($i) => ($i->price ?? 0) * ($i->quantity ?? 1)),
                    'baik' => $cat->items->where('condition', 'Baik')->count(),
                    'rusak' => $cat->items->whereIn('condition', ['Rusak ringan', 'Rusak berat'])->count(),
                ];
            });

        $items = Item::with(['category', 'room'])->orderBy('name')->get();

        $monthName = \Carbon\Carbon::create($year, $month)->locale('id')->translatedFormat('F Y');

        $pdf = Pdf::loadView('admin.report.report-pdf', compact(
            'totalTypes', 'totalQuantity', 'totalValue', 'needsAttention',
            'statusData', 'conditionData', 'categoryStats', 'items',
            'monthName', 'month', 'year'
        ));

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download("Laporan_Inventaris_GKI_{$monthName}.pdf");
    }
}
