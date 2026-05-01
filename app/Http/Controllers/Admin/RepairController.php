<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Repair;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class RepairController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $perPage = min((int) $request->get('per_page', 10), 100);
        
        $stats = [
            'total_repair' => Repair::where('status', '!=', 'Selesai')->count(),
            'total_qty' => Repair::where('status', '!=', 'Selesai')->sum('qty'),
            'coming_soon' => Repair::where('status', '!=', 'Selesai')
                ->whereBetween('estimated_completion', [Carbon::now(), Carbon::now()->addDays(3)])
                ->count(),
            'late' => Repair::where('status', '!=', 'Selesai')
                ->where('estimated_completion', '<', Carbon::now())
                ->count(),
        ];

        $query = Repair::with('item')->where('status', '!=', 'Selesai');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('item', function($sq) use ($search) {
                    $sq->withTrashed()->where('name', 'like', "%{$search}%");
                });
            });
        }

        $repairs = $query->paginate($perPage)->withQueryString();
        $items = Item::where('qty_tersedia', '>', 0)->get();
        
        return view('admin.repair.index', compact('repairs', 'search', 'perPage', 'stats', 'items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_barang' => 'required|exists:items,id',
            'qty' => 'required|integer|min:1',
            'jenis_perbaikan' => 'required|string|max:255',
            'tgl_service' => 'required|date',
            'estimated_completion' => 'required|date|after_or_equal:tgl_service',
            'biaya' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string'
        ]);

        $item = Item::findOrFail($validated['id_barang']);
        
        if ($item->qty_tersedia < $validated['qty']) {
            return back()->with('error', 'Stok barang tidak mencukupi untuk diperbaiki!')->withInput();
        }

        try {
            DB::beginTransaction();

            $validated['status'] = 'Proses';
            $validated['id_user'] = auth()->id();
            
            Repair::create($validated);

            $item->decrement('qty_tersedia', $validated['qty']);
            $item->increment('qty_diperbaiki', $validated['qty']);

            DB::commit();
            return redirect()->route('repair.index')->with('success', 'Data perbaikan berhasil dicatat!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal mencatat perbaikan: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->with('error', 'Gagal mencatat perbaikan. Silakan coba lagi.')->withInput();
        }
    }

    public function completeRepair($id)
    {
        try {
            DB::beginTransaction();

            $repair = Repair::findOrFail($id);
            $repair->update(['status' => 'Selesai']);

            $item = Item::findOrFail($repair->id_barang);
            $item->decrement('qty_diperbaiki', $repair->qty);
            $item->increment('qty_tersedia', $repair->qty);

            DB::commit();
            return redirect()->back()->with('success', 'Perbaikan berhasil diselesaikan!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal selesaikan perbaikan: ' . $e->getMessage(), ['id' => $id]);
            return back()->with('error', 'Gagal menyelesaikan perbaikan. Silakan coba lagi.');
        }
    }
}
