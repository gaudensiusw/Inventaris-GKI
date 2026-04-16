<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $search = $request->search;

        $items = Item::with('category', 'room')
        ->when($search, function($query) use($search){
            $query = $query->where('name', 'like', '%'.$search.'%');
        })->latest()->paginate(6);

        $categories = Category::with('items')->limit(12)->get();

        return view('landing.welcome', compact('items', 'categories', 'search'));
    }
}
