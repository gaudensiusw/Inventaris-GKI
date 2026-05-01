<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BorrowingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $perPage = min((int) $request->get('per_page', 10), 100);
        
        $stats = [
            'total_rows' => Borrowing::where('status_pinjam', 'Dipinjam')->count(),
            'total_qty' => Borrowing::where('status_pinjam', 'Dipinjam')->count(), // For now assuming 1 per row if qty not separate
            'coming_soon' => Borrowing::where('status_pinjam', 'Dipinjam')
                ->whereBetween('tgl_kembali_rencana', [Carbon::now(), Carbon::now()->addDays(3)])
                ->count(),
            'late' => Borrowing::where('status_pinjam', 'Dipinjam')
                ->where('tgl_kembali_rencana', '<', Carbon::now())
                ->count(),
        ];

        $query = Borrowing::with('item')->where('status_pinjam', 'Dipinjam');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('item', function($sq) use ($search) {
                    $sq->withTrashed()->where('name', 'like', "%{$search}%");
                })->orWhere('peminjam', 'like', "%{$search}%");
            });
        }

        $borrowings = $query->paginate($perPage)->withQueryString();
        $items = Item::where('qty_tersedia', '>', 0)->get();
        
        return view('admin.borrowing.index', compact('borrowings', 'search', 'perPage', 'stats', 'items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_barang' => 'required|exists:items,id',
            'qty' => 'required|integer|min:1',
            'peminjam' => 'required|string|max:255',
            'komisi_terkait' => 'nullable|string|max:255',
            'tgl_pinjam' => 'required|date',
            'tgl_kembali_rencana' => 'required|date|after_or_equal:tgl_pinjam',
            'catatan' => 'nullable|string'
        ]);

        $item = Item::findOrFail($validated['id_barang']);
        
        if ($item->qty_tersedia < $validated['qty']) {
            return back()->with('error', 'Stok barang tidak mencukupi untuk dipinjam!')->withInput();
        }

        try {
            DB::beginTransaction();

            $validated['status_pinjam'] = 'Dipinjam';
            $validated['id_user'] = auth()->id();
            
            Borrowing::create($validated);

            $item->decrement('qty_tersedia', $validated['qty']);
            $item->increment('qty_dipinjam', $validated['qty']);

            DB::commit();
            return redirect()->route('borrowing.index')->with('success', 'Peminjaman berhasil dicatat!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal mencatat peminjaman: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->with('error', 'Gagal mencatat peminjaman. Silakan coba lagi.')->withInput();
        }
    }

    public function returnItem($id)
    {
        try {
            DB::beginTransaction();
            
            $borrowing = Borrowing::findOrFail($id);
            $borrowing->update([
                'status_pinjam' => 'Kembali',
                'tgl_kembali_aktual' => Carbon::now()
            ]);

            $item = Item::findOrFail($borrowing->id_barang);
            $item->decrement('qty_dipinjam', $borrowing->qty);
            $item->increment('qty_tersedia', $borrowing->qty);

            DB::commit();
            return redirect()->back()->with('success', 'Barang berhasil dikembalikan!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal proses pengembalian: ' . $e->getMessage(), ['id' => $id]);
            return back()->with('error', 'Gagal memproses pengembalian. Silakan coba lagi.');
        }
    }
}
