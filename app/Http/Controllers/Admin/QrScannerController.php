<?php

namespace App\Http\Controllers\Admin;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QrScannerController extends Controller
{
    public function index()
    {
        $sampleCodes = Item::limit(5)->pluck('barcode')->toArray();
        return view('admin.qr-scanner.index', compact('sampleCodes'));
    }

    public function search(Request $request)
    {
        $item = Item::with(['category', 'room'])
            ->where('barcode', $request->code)
            ->first();

        if ($item) {
            return redirect()->route('admin.item.edit', $item);
        }

        return back()->with('error', 'Barang dengan kode tersebut tidak ditemukan.');
    }
}
