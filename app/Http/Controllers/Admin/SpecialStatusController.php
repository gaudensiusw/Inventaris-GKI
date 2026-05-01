<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class SpecialStatusController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'hilang');
        $search = $request->get('search');
        $perPage = min((int) $request->get('per_page', 10), 100);
        
        $stats = [
            'hilang' => Item::sum('qty_hilang'),
            'tidak_digunakan' => Item::sum('qty_tidak_digunakan'),
            'pengadaan' => Item::sum('qty_pengadaan'),
        ];

        $query = Item::query();
        
        if ($tab == 'hilang') {
            $query->where('qty_hilang', '>', 0);
        } elseif ($tab == 'tidak-digunakan') {
            $query->where('qty_tidak_digunakan', '>', 0);
        } elseif ($tab == 'pengadaan') {
            $query->where('qty_pengadaan', '>', 0);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('kode_aset', 'like', "%{$search}%");
            });
        }

        $items = $query->with(['category', 'room'])->paginate($perPage)->withQueryString();

        return view('admin.status-khusus.index', compact('items', 'tab', 'stats', 'search', 'perPage'));
    }
}
