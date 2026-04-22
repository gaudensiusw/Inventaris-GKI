<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Room;
use App\Models\Item;
use App\Models\Order;
use App\Models\Category;
use App\Models\Repair;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        // Stat cards
        $itemsCount = Item::count();
        $totalValue = Item::sum(DB::raw('price * quantity'));
        $goodConditionCount = Item::where('condition', 'Baik')->count();
        $needsAttentionCount = Item::whereIn('condition', ['Rusak ringan', 'Rusak berat'])->count()
                             + Item::whereIn('status', ['Hilang', 'Sedang diperbaiki'])->count();

        // Distribusi Kategori (pie chart)
        $categoryDistribution = DB::table('items')
            ->join('categories', 'categories.id', '=', 'items.category_id')
            ->select('categories.name', DB::raw('count(items.id) as total'))
            ->groupBy('categories.name')
            ->orderBy('total', 'DESC')
            ->get();

        $categoryLabels = $categoryDistribution->pluck('name')->toArray();
        $categoryTotals = $categoryDistribution->pluck('total')->toArray();

        // Barang per Lokasi (bar chart)
        $locationDistribution = DB::table('items')
            ->join('rooms', 'rooms.id', '=', 'items.room_id')
            ->select('rooms.name', DB::raw('count(items.id) as total'))
            ->groupBy('rooms.name')
            ->orderBy('total', 'DESC')
            ->limit(8)
            ->get();

        $locationLabels = $locationDistribution->pluck('name')->toArray();
        $locationTotals = $locationDistribution->pluck('total')->toArray();

        // Aktivitas Terbaru (latest 5 items)
        $latestItems = Item::with('category')
            ->latest()
            ->limit(5)
            ->get();

        // Bottom summary
        $categoriesCount = Category::count();
        $goodPercentage = $itemsCount > 0
            ? round(($goodConditionCount / $itemsCount) * 100)
            : 0;

        return view('admin.dashboard', compact(
            'itemsCount', 'totalValue', 'goodConditionCount', 'needsAttentionCount',
            'categoryLabels', 'categoryTotals',
            'locationLabels', 'locationTotals',
            'latestItems', 'categoriesCount', 'goodPercentage'
        ));
    }
}
