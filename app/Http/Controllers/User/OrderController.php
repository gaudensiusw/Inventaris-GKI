<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function create($itemId)
    {
        $item = Item::with(['category', 'room'])->findOrFail($itemId);
        
        if ($item->qty_tersedia <= 0) {
            return redirect()->route('user.katalog.index')
                ->with('error', 'Barang ini sedang tidak tersedia untuk dipinjam.');
        }

        return view('user.orders.create', compact('item'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_barang' => 'required|exists:items,id',
            'qty' => 'required|integer|min:1',
            'nama_peminjam' => 'required|string|max:255',
            'kontak_peminjam' => 'required|string|max:255',
            'reason' => 'required|string|max:500',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'catatan' => 'nullable|string|max:500',
        ]);

        $item = Item::findOrFail($validated['id_barang']);

        // Real-time stock check
        if ($item->qty_tersedia < $validated['qty']) {
            return back()->with('error', 'Stok tidak mencukupi! Tersedia: ' . $item->qty_tersedia . ' unit.')
                ->withInput();
        }

        try {
            // Generate a tracking code for the requester
            $kodeRequest = 'REQ-' . strtoupper(Str::random(8));

            Order::create([
                'user_id' => null, // public user, no account
                'id_barang' => $validated['id_barang'],
                'qty' => $validated['qty'],
                'nama_peminjam' => $validated['nama_peminjam'],
                'kontak_peminjam' => $validated['kontak_peminjam'],
                'reason' => $validated['reason'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'status' => 'Pending',
                'catatan' => $kodeRequest . ($validated['catatan'] ? ' | ' . $validated['catatan'] : ''),
            ]);

            return redirect()->route('user.katalog.index')
                ->with('success', 'Permintaan peminjaman berhasil diajukan! Kode tracking: ' . $kodeRequest . '. Silakan hubungi admin untuk persetujuan.');
        } catch (\Exception $e) {
            Log::error('Gagal membuat request peminjaman: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengajukan peminjaman. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Public status check — user can look up their request by tracking code or name
     */
    public function checkStatus(Request $request)
    {
        $search = $request->get('q');
        $orders = collect();

        if ($search) {
            $orders = Order::with(['item.category'])
                ->where(function ($q) use ($search) {
                    $q->where('nama_peminjam', 'like', "%{$search}%")
                      ->orWhere('kontak_peminjam', 'like', "%{$search}%")
                      ->orWhere('catatan', 'like', "%{$search}%"); // tracking code is in catatan
                })
                ->latest()
                ->limit(20)
                ->get();
        }

        return view('user.orders.status', compact('orders', 'search'));
    }
}
