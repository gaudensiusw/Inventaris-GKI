<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Repair;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $perPage = min((int) $request->get('per_page', 10), 100);
        
        // History of Borrowings (Returned)
        $borrowHistory = Borrowing::with('item')
            ->where('status_pinjam', 'Kembali')
            ->latest('tgl_kembali_aktual')
            ->paginate($perPage, ['*'], 'borrow_page')
            ->withQueryString();

        // History of Repairs (Finished)
        $repairHistory = Repair::with('item')
            ->where('status', 'Selesai')
            ->latest('updated_at')
            ->paginate($perPage, ['*'], 'repair_page')
            ->withQueryString();

        return view('admin.history.index', compact('borrowHistory', 'repairHistory', 'perPage'));
    }
}
