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
        $item = Item::where('kode_aset', $code)
            ->orWhere('barcode', $code)
            ->orWhere('entno', $code)
            ->first();

        if ($item) {
            $item->load(['category', 'room']);
            return response()->json([
                'success' => true,
                'item' => [
                    'name' => $item->name,
                    'kode_aset' => $item->kode_aset,
                    'quantity' => $item->quantity,
                    'status' => $item->status,
                    'condition' => $item->condition,
                    'category' => $item->category->name ?? 'Tanpa Kategori',
                    'room' => $item->room->name ?? 'Tanpa Ruangan',
                    'image_url' => $item->image ? asset('storage/' . $item->image) : null,
                ],
                'redirect_url' => route('inventory.index', ['search' => $item->kode_aset])
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Barang dengan kode tersebut tidak ditemukan.'
        ]);
    }
}
