<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $categories = Category::when($search, function($query) use($search){
            $query = $query->where('name', 'like', '%'.$search.'%');
        })->get();

        return view('landing.category.index', compact('categories', 'search'));
    }

    public function show($slug)
    {
        $category = Category::where('slug', $slug)->first();

        $items = Item::where('category_id', $category->id)->get();

        return view('landing.category.show', compact('category', 'items'));
    }
}
