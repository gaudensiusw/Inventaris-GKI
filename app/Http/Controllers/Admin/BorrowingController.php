<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Item;
use App\Models\Order;
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
            'pending_requests' => Order::where('status', 'Pending')->count(),
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
        $items = Item::orderBy('name')->get();
        
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

    public function returnItem(Request $request, $id)
    {
        $validated = $request->validate([
            'qty_kembali' => 'required|integer|min:1',
            'kondisi_kembali' => 'required|in:Baik,Rusak Ringan,Rusak Berat,Hilang',
            'catatan_kembali' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();
            
            $borrowing = Borrowing::findOrFail($id);
            
            // Validate qty_kembali doesn't exceed borrowed qty
            if ($validated['qty_kembali'] > $borrowing->qty) {
                return back()->with('error', 'Jumlah kembali tidak boleh melebihi jumlah pinjam (' . $borrowing->qty . ')!');
            }

            $borrowing->update([
                'status_pinjam' => 'Kembali',
                'tgl_kembali_aktual' => Carbon::now(),
                'qty_kembali' => $validated['qty_kembali'],
                'kondisi_kembali' => $validated['kondisi_kembali'],
                'catatan_kembali' => $validated['catatan_kembali'],
            ]);

            $item = Item::findOrFail($borrowing->id_barang);
            
            // Update physical and operational counts precisely
            $qtyKembali = $validated['qty_kembali'];
            switch ($validated['kondisi_kembali']) {
                case 'Baik':
                    $item->qty_tersedia += $qtyKembali;
                    break;
                case 'Rusak Ringan':
                    $item->qty_rusak_ringan += $qtyKembali;
                    $item->qty_tidak_digunakan += $qtyKembali;
                    $item->qty_baik = max(0, $item->qty_baik - $qtyKembali);
                    break;
                case 'Rusak Berat':
                    $item->qty_rusak_berat += $qtyKembali;
                    $item->qty_tidak_digunakan += $qtyKembali;
                    $item->qty_baik = max(0, $item->qty_baik - $qtyKembali);
                    break;
                case 'Hilang':
                    $item->qty_hilang += $qtyKembali;
                    $item->qty_baik = max(0, $item->qty_baik - $qtyKembali);
                    break;
            }

            // If returned less than borrowed, remaining is also counted as lost
            $notReturned = $borrowing->qty - $qtyKembali;
            if ($notReturned > 0) {
                $item->qty_hilang += $notReturned;
                $item->qty_baik = max(0, $item->qty_baik - $notReturned);
            }

            // Decrease borrowed qty
            $item->qty_dipinjam = max(0, $item->qty_dipinjam - $borrowing->qty);
            $item->save();

            DB::commit();
            return redirect()->back()->with('success', 'Barang berhasil dikembalikan! Kondisi: ' . $validated['kondisi_kembali']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal proses pengembalian: ' . $e->getMessage(), ['id' => $id]);
            return back()->with('error', 'Gagal memproses pengembalian. Silakan coba lagi.');
        }
    }

    /**
     * List pending borrowing requests from users
     */
    public function pendingRequests()
    {
        $requests = Order::with(['user', 'item.category', 'item.room'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $pendingCount = Order::where('status', 'Pending')->count();

        return view('admin.borrowing.requests', compact('requests', 'pendingCount'));
    }

    /**
     * Approve a borrowing request
     */
    public function approve($id)
    {
        $order = Order::findOrFail($id);

        if ($order->status !== 'Pending') {
            return back()->with('error', 'Request ini sudah diproses sebelumnya.');
        }

        $item = Item::findOrFail($order->id_barang);

        // Real-time stock check
        if ($item->qty_tersedia < $order->qty) {
            return back()->with('error', 'Stok tidak mencukupi! Tersedia: ' . $item->qty_tersedia . ', diminta: ' . $order->qty);
        }

        try {
            DB::beginTransaction();

            // Update order status
            $order->update([
                'status' => 'Disetujui',
                'approved_by' => auth()->id(),
            ]);

            // Create rent record
            Borrowing::create([
                'id_barang' => $order->id_barang,
                'qty' => $order->qty,
                'peminjam' => $order->nama_peminjam,
                'komisi_terkait' => null,
                'tgl_pinjam' => $order->start_date,
                'tgl_kembali_rencana' => $order->end_date,
                'id_user' => auth()->id(),
                'status_pinjam' => 'Dipinjam',
                'catatan' => $order->reason . ($order->catatan ? ' | ' . $order->catatan : ''),
            ]);

            // Update item qty
            $item->decrement('qty_tersedia', $order->qty);
            $item->increment('qty_dipinjam', $order->qty);

            DB::commit();
            return back()->with('success', 'Request peminjaman berhasil disetujui!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal approve request: ' . $e->getMessage());
            return back()->with('error', 'Gagal memproses persetujuan. Silakan coba lagi.');
        }
    }

    /**
     * Reject a borrowing request
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'reject_reason' => 'required|string|max:500',
        ]);

        $order = Order::findOrFail($id);

        if ($order->status !== 'Pending') {
            return back()->with('error', 'Request ini sudah diproses sebelumnya.');
        }

        $order->update([
            'status' => 'Ditolak',
            'approved_by' => auth()->id(),
            'reject_reason' => $request->reject_reason,
        ]);

        return back()->with('success', 'Request peminjaman telah ditolak.');
    }
}

