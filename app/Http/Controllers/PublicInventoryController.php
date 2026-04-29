<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicInventoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $categoryId = $request->category;
        $condition = $request->condition;

        $items = Item::with(['category', 'room'])
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            })
            ->when($categoryId, function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            })
            ->when($condition, function ($q) use ($condition) {
                $q->where('condition', $condition);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $categories = Category::all();
        $totalItems = Item::count();
        $totalBaik = Item::where('condition', 'Baik')->count();
        $totalValue = Item::sum(DB::raw('COALESCE(price, 0) * COALESCE(quantity, 1)'));

        return view('public.inventory', compact(
            'items', 'categories', 'search', 'categoryId', 'condition',
            'totalItems', 'totalBaik', 'totalValue'
        ));
    }
}
