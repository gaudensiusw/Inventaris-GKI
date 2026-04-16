<?php

namespace App\Http\Controllers\Customer;

use App\Models\Order;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
        $user = Auth::id();

        // Ambil riwayat pinjaman aktif milik user yang login
        $activeLoans = Order::with('item')
            ->where('user_id', $user)
            ->whereIn('status', ['Pending', 'Approved'])
            ->get();

        // Ambil semua item yang tersedia agar customer bisa meminjam dari dashboard
        $items = Item::with(['category', 'room'])->where('status', 'Tersedia')->get();

        return view('customer.dashboard', compact('activeLoans', 'items'));
    }
}
