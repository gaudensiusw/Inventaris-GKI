<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Room;
use App\Models\Item;
use App\Models\Order;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $categoriesCount = Category::count();
        $roomsCount = Room::count();
        $itemsCount = Item::count();
        $customersCount = User::role('Customer')->count();

        // Items currently being loaned
        $loanedItemsCount = Item::where('status', 'Dipinjam')->count();
        
        // Items in bad condition
        $damagedItemsCount = Item::whereIn('condition', ['Rusak ringan', 'Rusak berat'])->count();

        // Pending orders
        $pendingOrders = Order::where('status', 'Pending')->get();

        // Graphic Logic Customization (e.g. Items per category)
        $itemsPerCategory = DB::table('items')
                        ->addSelect(DB::raw('categories.name as name, count(items.id) as total'))
                        ->join('categories', 'categories.id', 'items.category_id')
                        ->groupBy('items.category_id', 'categories.name')
                        ->orderBy('total', 'DESC')
                        ->limit(5)->get();

        $label = [];
        $total = [];

        if (count($itemsPerCategory)) {
            foreach ($itemsPerCategory as $data) {
                $label[] = $data->name;
                $total[] = (int) $data->total;
            }
        } else {
            $label[] = '';
            $total[] = '';
        }

        return view('admin.dashboard', compact(
            'categoriesCount', 'roomsCount', 'itemsCount', 'customersCount', 
            'loanedItemsCount', 'damagedItemsCount', 'pendingOrders', 
            'label', 'total'
        ));
    }
}
