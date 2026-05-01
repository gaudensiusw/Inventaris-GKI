<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Category;
use App\Models\Room;
use App\Models\Borrowing;
use App\Models\Repair;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Based on User Request: Use count of unique items (rows) as the primary metric
        $totalItems = Item::count();
        
        // Kondisi Baik = Items where no units are broken
        $kondisiBaik = Item::where('qty_rusak_ringan', 0)
                          ->where('qty_rusak_berat', 0)
                          ->count();
        
        // Perlu Perbaikan = Items where at least one unit is broken
        $perluPerbaikan = Item::where(function($q) {
            $q->where('qty_rusak_ringan', '>', 0)
              ->orWhere('qty_rusak_berat', '>', 0);
        })->count();
        
        $totalCategories = Category::count();
        $totalUniqueItems = $totalItems; // Same as totalItems in this context
        
        $kondisiBaikPercent = $totalItems > 0 ? round(($kondisiBaik / $totalItems) * 100) : 0;

        // Status Breakdown (Keeping sums for units here to show physical counts)
        $statusBreakdown = [
            'dipinjam' => Item::sum('qty_dipinjam'),
            'diperbaiki' => Item::sum('qty_diperbaiki'),
            'hilang' => Item::sum('qty_hilang'),
            'pengadaan' => Item::sum('qty_pengadaan'),
            'rusak_ringan' => Item::sum('qty_rusak_ringan'),
            'rusak_berat' => Item::sum('qty_rusak_berat'),
            'tidak_digunakan' => Item::sum('qty_tidak_digunakan'),
            'baik' => Item::sum('qty_baik'),
        ];

        // Charts Data - Category (Based on Item Count per Category)
        $categoryData = Category::withCount('items')->get()->map(function($cat) {
            return ['name' => $cat->name, 'count' => $cat->items_count];
        })->filter(fn($c) => $c['count'] > 0)->values();

        // Charts Data - Location (Based on Item Count per Room)
        $locationData = Room::withCount('items')->get()->map(function($room) {
            return ['name' => $room->name, 'count' => $room->items_count];
        })->filter(fn($c) => $c['count'] > 0)->values();

        // Recent Activities
        $recentActivities = Item::with(['category'])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalItems', 'kondisiBaik', 'perluPerbaikan', 
            'totalCategories', 'totalUniqueItems', 'kondisiBaikPercent',
            'statusBreakdown', 'categoryData', 'locationData', 'recentActivities'
        ));
    }
}
