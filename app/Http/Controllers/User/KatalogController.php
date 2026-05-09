<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Category;
use App\Models\Room;
use Illuminate\Http\Request;

class KatalogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $categoryId = $request->get('category_id');
        $roomId = $request->get('room_id');

        $query = Item::with(['category', 'room']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('kode_aset', 'like', "%{$search}%");
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($roomId) {
            $query->where('room_id', $roomId);
        }

        $items = $query->orderBy('name')->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $rooms = Room::orderBy('name')->get();

        return view('user.katalog.index', compact('items', 'categories', 'rooms', 'search', 'categoryId', 'roomId'));
    }
}
