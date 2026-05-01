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
        $request->validate(['code' => 'required|string|max:50']);
        $code = $request->code;
        $item = Item::where('kode_aset', $code)->orWhere('item_id', $code)->first();

        if ($item) {
            return response()->json([
                'success' => true,
                'redirect_url' => route('inventory.index', ['search' => $item->kode_aset])
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Barang dengan kode tersebut tidak ditemukan.'
        ]);
    }
}
