<?php

namespace App\Http\Controllers\Admin;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SpecialStatusController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'hilang');

        $statusMap = [
            'hilang' => 'Hilang',
            'tidak_digunakan' => 'Tidak digunakan',
            'pengadaan' => 'Dalam pengadaan',
        ];

        $currentStatus = $statusMap[$tab] ?? 'Hilang';
        $items = Item::with(['category', 'room'])->where('status', $currentStatus)->get();

        $hilangCount = Item::where('status', 'Hilang')->count();
        $tidakDigunakanCount = Item::where('status', 'Tidak digunakan')->count();
        $pengadaanCount = Item::where('status', 'Dalam pengadaan')->count();

        return view('admin.special-status.index', compact(
            'items', 'tab', 'hilangCount', 'tidakDigunakanCount', 'pengadaanCount'
        ));
    }
}
