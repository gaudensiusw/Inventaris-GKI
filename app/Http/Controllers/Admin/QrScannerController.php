<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class QrScannerController extends Controller
{
    public function index()
    {
        $recentItems = Item::latest()->take(5)->get();
        return view('admin.qr-scanner', compact('recentItems'));
    }

    public function search(Request $request)
    {
        $code = $request->code;
        $item = Item::where('item_id', $code)->first();

        if ($item) {
            return response()->json([
                'success' => true,
                'redirect_url' => route('inventory.index', ['search' => $item->item_id]) // Redirect to inventory with search or a detail page
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Barang dengan kode ' . $code . ' tidak ditemukan.'
        ]);
    }
}
