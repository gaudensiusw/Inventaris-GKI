<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Repair;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'peminjaman');

        $peminjaman = collect();
        $perbaikan = collect();
        $statusKhusus = collect();

        if ($tab === 'peminjaman') {
            $peminjaman = Order::with(['user', 'rents.item'])
                ->where('status', 'Dikembalikan')
                ->latest()
                ->paginate(10);
        } elseif ($tab === 'perbaikan') {
            $perbaikan = Repair::with('item')
                ->where('status', 'Selesai')
                ->latest()
                ->paginate(10);
        }

        $peminjamanCount = Order::where('status', 'Dikembalikan')->count();
        $perbaikanCount = Repair::where('status', 'Selesai')->count();

        return view('admin.history.index', compact(
            'tab', 'peminjaman', 'perbaikan', 'statusKhusus',
            'peminjamanCount', 'perbaikanCount'
        ));
    }
}
