<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockOpnameHeader;
use App\Models\StockOpnameDetail;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class StockOpnameController extends Controller
{
    public function index()
    {
        $stockOpnames = StockOpnameHeader::with('user')->latest()->get();
        return view('admin.stock-opname.index', compact('stockOpnames'));
    }

    public function create(Request $request)
    {
        $search = $request->get('search');
        $query = Item::with(['category', 'room']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('kode_aset', 'like', "%{$search}%");
            });
        }

        $items = $query->get(); // We still get all for the form, but we'll add JS search too
        return view('admin.stock-opname.create', compact('items', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'audit_date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.physical_qty' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            $header = StockOpnameHeader::create([
                'tgl_audit' => $request->audit_date,
                'id_user' => auth()->id(),
                'status' => 'Completed',
                'keterangan' => $request->notes
            ]);

            foreach ($request->items as $itemData) {
                $item = Item::find($itemData['item_id']);
                // Standard total qty calculation: baik + rusak_ringan + rusak_berat
                $systemQty = $item->qty_baik + $item->qty_rusak_ringan + $item->qty_rusak_berat;
                $physicalQty = $itemData['physical_qty'];
                $difference = $physicalQty - $systemQty;

                StockOpnameDetail::create([
                    'id_so' => $header->id_so ?? $header->id,
                    'id_barang' => $item->id,
                    'stok_sistem' => $systemQty,
                    'stok_fisik' => $physicalQty,
                    'selisih' => $difference,
                    'keterangan' => $itemData['notes'] ?? null
                ]);

                // Update physical counts - simple logic: update qty_baik if difference exists
                // In a real scenario, you'd ask which condition changed, but for now we sync total.
                if ($difference != 0) {
                    $item->qty_baik = max(0, $item->qty_baik + $difference);
                    $item->qty_tersedia = max(0, $item->qty_tersedia + $difference);
                    $item->save();
                }
            }

            DB::commit();
            return redirect()->route('stock-opname.index')->with('success', 'Stock Opname berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal simpan stock opname: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->with('error', 'Terjadi kesalahan saat menyimpan audit. Silakan coba lagi.');
        }
    }

    public function show(StockOpnameHeader $stockOpname)
    {
        $stockOpname->load(['details.item', 'user']);
        return view('admin.stock-opname.show', compact('stockOpname'));
    }
}
