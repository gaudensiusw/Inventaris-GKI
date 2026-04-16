<?php

namespace App\Http\Controllers\Customer;

use App\Models\Order;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::with('item')->where('user_id', Auth::id())->orderBy('created_at', 'DESC')->paginate(10);

        return view('customer.order.index', compact('orders'));
    }

    /**
     * Show form to create order
     */
    public function create(Request $request)
    {
        $itemSlug = $request->query('item');
        $item = Item::where('slug', $itemSlug)->firstOrFail();

        return view('customer.order.create', compact('item'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'identity_number' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        Order::create([
            'user_id' => Auth::id(),
            'item_id' => $request->item_id,
            'identity_number' => $request->identity_number,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'Pending',
        ]);

        return redirect()->route('customer.order.index')->with('toast_success', 'Pengajuan Peminjaman Barang Berhasil Diajukan');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        // 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        if ($order->status == 'Pending') {
            $order->delete();
            return back()->with('toast_success', 'Pengajuan Peminjaman Dibatalkan');
        }
        return back()->with('toast_error', 'Tidak dapat membatalkan pengajuan');
    }
}
