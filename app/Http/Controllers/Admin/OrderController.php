<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::with('user')->orderBy('created_at', 'DESC')->paginate(10);

        return view('admin.order.index', compact('orders'));
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
        $request->validate([
            'status' => 'required|in:Pending,Approved,Rejected,Returned'
        ]);

        $order->update([
            'status' => $request->status,
        ]);

        if($request->status == 'Approved') {
            // Logika ketika disetujui, update status item jadi "Dipinjam" jika order memiliki item_id
            if (isset($order->item_id)) {
                $item = Item::find($order->item_id);
                if ($item) {
                    $item->update(['status' => 'Dipinjam']);
                }
            }
        } elseif ($request->status == 'Returned') {
             if (isset($order->item_id)) {
                $item = Item::find($order->item_id);
                if ($item) {
                    $item->update(['status' => 'Tersedia']);
                }
            }
        }

        return back()->with('toast_success', 'Status Pengajuan Berhasil Diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::find($id);
        if($order) {
            $order->delete();
        }
        return back()->with('toast_success', 'Pengajuan dihapus');
    }
}
