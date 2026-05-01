<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class DisposalController extends Controller
{
    public function index(Request $request)
    {
        $perPage = min((int) $request->get('per_page', 10), 100);
        $disposals = Item::onlyTrashed()->with(['category', 'room'])->latest('deleted_at')->paginate($perPage)->withQueryString();
        return view('admin.disposal.index', compact('disposals', 'perPage'));
    }

    public function restore($id)
    {
        $item = Item::onlyTrashed()->findOrFail($id);
        $item->restore();
        return redirect()->route('disposal.index')->with('success', 'Barang berhasil dipulihkan (Restore) ke inventaris.');
    }
}
