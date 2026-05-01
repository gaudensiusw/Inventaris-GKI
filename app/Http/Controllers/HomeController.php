<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $categoryId = $request->get('category_id');
        $perPage = $request->get('per_page', 12);

        $query = Item::with(['category', 'room']);

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('kode_aset', 'like', "%{$search}%");
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $items = $query->latest()->paginate($perPage)->withQueryString();
        $categories = Category::all();

        return view('home', compact('items', 'categories', 'search', 'categoryId', 'perPage'));
    }
}
