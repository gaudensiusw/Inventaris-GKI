<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\StockOpnameHeader;
use App\Models\Disposal;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);

        $query = Item::query();

        // If period is monthly, filter by purchase_date or activity date?
        // Usually reports show status summary for the period.
        
        $stock_opnames = StockOpnameHeader::whereYear('tgl_audit', $year);
        if($period == 'monthly') {
            $stock_opnames->whereMonth('tgl_audit', $month);
        }
        $stock_opnames = $stock_opnames->get();

        $disposals = Item::onlyTrashed()->whereYear('deleted_at', $year);
        if($period == 'monthly') {
            $disposals->whereMonth('deleted_at', $month);
        }
        $disposals = $disposals->with(['category', 'room'])->get();

        $items = Item::with(['category', 'room'])->get();

        return view('admin.report.index', compact('items', 'stock_opnames', 'disposals', 'period', 'month', 'year'));
    }
}
