<?php

namespace App\Http\Controllers\Admin;

use App\Models\Item;
use App\Models\Repair;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RepairController extends Controller
{
    public function index()
    {
        $repairItems = Item::with(['category', 'room', 'activeRepair'])
            ->where('status', 'Sedang diperbaiki')
            ->orWhere('qty_diperbaiki', '>', 0)
            ->get();

        $totalRepair = $repairItems->count();

        $completingSoon = Repair::where('status', 'Dalam Perbaikan')
            ->whereNotNull('estimated_completion')
            ->where('estimated_completion', '<=', now()->addDays(3))
            ->where('estimated_completion', '>=', now())
            ->count();

        $overEstimate = Repair::where('status', 'Dalam Perbaikan')
            ->whereNotNull('estimated_completion')
            ->where('estimated_completion', '<', now())
            ->count();

        return view('admin.repair.index', compact(
            'repairItems', 'totalRepair', 'completingSoon', 'overEstimate'
        ));
    }
}
