<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Category;
use App\Models\Room;
use App\Models\Borrowing;
use App\Models\Repair;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);

        $data = $this->getReportData($period, $month, $year);

        if ($request->has('export_pdf')) {
            $pdf = Pdf::loadView('admin.report.pdf', $data);
            return $pdf->download('Laporan-Inventaris-' . Carbon::create($year, $month)->format('F-Y') . '.pdf');
        }

        return view('admin.report.index', array_merge($data, [
            'period' => $period,
            'month' => (int)$month,
            'year' => (int)$year
        ]));
    }

    private function getReportData($period, $month, $year)
    {
        $targetDate = Carbon::create($year, $month, 1);

        // 1. Summary Cards (Current state of all items)
        $totalJenisBarang = Item::count();
        $totalQuantity = Item::sum('quantity');
        $perluPerhatian = Item::where(function($q) {
            $q->where('qty_rusak_ringan', '>', 0)
              ->orWhere('qty_rusak_berat', '>', 0);
        })->count();

        // 2. Status Operasional (Pie Chart) - Units
        $statusOperasional = [
            'Tersedia' => Item::sum('qty_tersedia'),
            'Dipinjam' => Item::sum('qty_dipinjam'),
            'Sedang Diperbaiki' => Item::sum('qty_diperbaiki'),
            'Hilang' => Item::sum('qty_hilang'),
        ];

        // 3. Kondisi Fisik (Pie Chart) - Units
        $kondisiFisik = [
            'Baik' => Item::sum('qty_baik'),
            'Rusak Ringan' => Item::sum('qty_rusak_ringan'),
            'Rusak Berat' => Item::sum('qty_rusak_berat'),
        ];

        // 4. Trend 6 Months (Ending at selected date)
        $trendMonths = [];
        $borrowingTrend = [];
        $repairTrend = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = (clone $targetDate)->subMonths($i);
            $trendMonths[] = $date->format('M Y');
            
            $borrowingTrend[] = Borrowing::whereYear('tgl_pinjam', $date->year)
                ->whereMonth('tgl_pinjam', $date->month)
                ->count();
                
            $repairTrend[] = Repair::whereYear('tgl_service', $date->year)
                ->whereMonth('tgl_service', $date->month)
                ->count();
        }

        // 5. Per Kategori
        $categoryBreakdown = Category::withCount('items')->get()->map(function($cat) {
            return [
                'name' => $cat->name,
                'items_count' => $cat->items_count,
                'total_units' => Item::where('category_id', $cat->id)->sum('quantity'),
            ];
        });

        // 6. Aktivitas (Period Specific)
        $borrowingQuery = Borrowing::whereYear('tgl_pinjam', $year);
        $repairQuery = Repair::whereYear('tgl_service', $year);
        $returnQuery = Borrowing::whereYear('tgl_kembali_aktual', $year);
        $repairFinishQuery = Repair::whereYear('updated_at', $year)->where('status', 'Selesai');

        if ($period == 'monthly') {
            $borrowingQuery->whereMonth('tgl_pinjam', $month);
            $repairQuery->whereMonth('tgl_service', $month);
            $returnQuery->whereMonth('tgl_kembali_aktual', $month);
            $repairFinishQuery->whereMonth('updated_at', $month);
        }

        $borrowingStats = [
            'total' => $borrowingQuery->count(),
            'current' => Borrowing::where('status_pinjam', 'Dipinjam')->count(),
            'returned' => $returnQuery->count(),
        ];

        $repairStats = [
            'total' => $repairQuery->count(),
            'current' => Repair::where('status', 'Proses')->count(),
            'finished' => $repairFinishQuery->count(),
        ];

        // 7. Status Detail for Activity Tab
        $detailedStatus = [
            'Tersedia' => Item::sum('qty_tersedia'),
            'Dipinjam' => Item::sum('qty_dipinjam'),
            'Sedang Diperbaiki' => Item::sum('qty_diperbaiki'),
            'Hilang' => Item::sum('qty_hilang'),
            'Tidak Digunakan' => Item::sum('qty_tidak_digunakan'),
            'Dalam Pengadaan' => Item::sum('qty_pengadaan'),
        ];

        return [
            'totalJenisBarang' => $totalJenisBarang,
            'totalQuantity' => $totalQuantity,
            'perluPerhatian' => $perluPerhatian,
            'statusOperasional' => $statusOperasional,
            'kondisiFisik' => $kondisiFisik,
            'trendMonths' => $trendMonths,
            'borrowingTrend' => $borrowingTrend,
            'repairTrend' => $repairTrend,
            'categoryBreakdown' => $categoryBreakdown,
            'borrowingStats' => $borrowingStats,
            'repairStats' => $repairStats,
            'detailedStatus' => $detailedStatus,
        ];
    }
}
