<?php

namespace App\Http\Controllers\Admin;

use App\Models\Item;
use App\Models\Order;
use App\Models\Rent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BorrowedController extends Controller
{
    public function index()
    {
        $borrowedItems = Item::with(['category', 'room', 'rents.order.user'])
            ->where('status', 'Dipinjam')
            ->orWhere('qty_dipinjam', '>', 0)
            ->get();

        $totalBorrowed = $borrowedItems->count();
        $totalQty = $borrowedItems->sum('qty_dipinjam');

        // Items returning soon (within 3 days)
        $returningSoon = Order::where('status', 'Disetujui')
            ->whereNotNull('end_date')
            ->where('end_date', '<=', now()->addDays(3))
            ->where('end_date', '>=', now())
            ->count();

        // Overdue items
        $overdue = Order::where('status', 'Disetujui')
            ->whereNotNull('end_date')
            ->where('end_date', '<', now())
            ->count();

        return view('admin.borrowed.index', compact(
            'borrowedItems', 'totalBorrowed', 'totalQty', 'returningSoon', 'overdue'
        ));
    }
}
