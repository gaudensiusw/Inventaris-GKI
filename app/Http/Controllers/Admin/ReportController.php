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
        $roomId = $request->get('room_id');
        $categoryId = $request->get('category_id');

        $data = $this->getReportData($period, $month, $year, $roomId, $categoryId);
        $rooms = Room::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        if ($request->has('export_pdf')) {
            $pdf = Pdf::loadView('admin.report.pdf', array_merge($data, [
                'period' => $period,
                'month' => (int)$month,
                'year' => (int)$year,
                'selectedRoom' => $roomId ? Room::find($roomId)->name : null,
                'selectedCategory' => $categoryId ? Category::find($categoryId)->name : null,
            ]));
            return $pdf->download('Laporan-Inventaris-' . ($period == 'monthly' ? Carbon::create($year, $month)->format('F-Y') : $year) . '.pdf');
        }

        return view('admin.report.index', array_merge($data, [
            'period' => $period,
            'month' => (int)$month,
            'year' => (int)$year,
            'roomId' => $roomId,
            'categoryId' => $categoryId,
            'rooms' => $rooms,
            'categories' => $categories,
        ]));
    }

    public function exportCsv(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);
        $roomId = $request->get('room_id');
        $categoryId = $request->get('category_id');

        $data = $this->getReportData($period, $month, $year, $roomId, $categoryId);
        $selectedRoomName = $roomId ? Room::find($roomId)->name : 'Semua Ruangan';
        $selectedCategoryName = $categoryId ? Category::find($categoryId)->name : 'Semua Kategori';
        
        $filename = 'Laporan-Inventaris-' . ($period == 'monthly' ? Carbon::create($year, $month)->format('F-Y') : $year) . '.csv';

        $callback = function() use ($data, $period, $month, $year, $selectedRoomName, $selectedCategoryName) {
            $file = fopen('php://output', 'w');
            
            // Title
            fputcsv($file, ['LAPORAN INVENTARIS GKI DELIMA']);
            fputcsv($file, ['Periode', $period == 'monthly' ? Carbon::create($year, $month)->format('F Y') : $year]);
            fputcsv($file, ['Filter Ruangan', $selectedRoomName]);
            fputcsv($file, ['Filter Kategori', $selectedCategoryName]);
            fputcsv($file, []);

            // 1. Ringkasan
            fputcsv($file, ['1. RINGKASAN INVENTARIS']);
            fputcsv($file, ['Total Jenis Barang', $data['totalJenisBarang']]);
            fputcsv($file, ['Total Kuantitas Aset', $data['totalQuantity']]);
            fputcsv($file, ['Barang Rusak (Perlu Perhatian)', $data['perluPerhatian']]);
            fputcsv($file, []);

            // 2. Status Operasional
            fputcsv($file, ['2. STATUS OPERASIONAL (UNIT)']);
            foreach ($data['statusOperasional'] as $status => $qty) {
                fputcsv($file, [$status, $qty]);
            }
            fputcsv($file, []);

            // 3. Kondisi Fisik
            fputcsv($file, ['3. KONDISI FISIK (UNIT)']);
            foreach ($data['kondisiFisik'] as $kondisi => $qty) {
                fputcsv($file, [$kondisi, $qty]);
            }
            fputcsv($file, []);

            // 4. Breakdown Per Kategori
            fputcsv($file, ['4. RINCIAN PER KATEGORI']);
            fputcsv($file, ['Nama Kategori', 'Jumlah Jenis Barang', 'Total Unit']);
            foreach ($data['categoryBreakdown'] as $cat) {
                fputcsv($file, [$cat['name'], $cat['items_count'], $cat['total_units']]);
            }
            fputcsv($file, []);

            // 5. Statistik Aktivitas
            fputcsv($file, ['5. STATISTIK AKTIVITAS']);
            fputcsv($file, ['Kategori Aktivitas', 'Jumlah']);
            fputcsv($file, ['Total Transaksi Peminjaman', $data['borrowingStats']['total']]);
            fputcsv($file, ['Peminjaman Aktif (Sedang Dipinjam)', $data['borrowingStats']['current']]);
            fputcsv($file, ['Peminjaman Selesai (Dikembalikan)', $data['borrowingStats']['returned']]);
            fputcsv($file, ['Total Perbaikan', $data['repairStats']['total']]);
            fputcsv($file, ['Sedang Diperbaiki', $data['repairStats']['current']]);
            fputcsv($file, ['Perbaikan Selesai', $data['repairStats']['finished']]);
            fputcsv($file, []);

            // 6. DAFTAR DETAIL BARANG INVENTARIS
            fputcsv($file, ['6. DAFTAR DETAIL BARANG INVENTARIS']);
            fputcsv($file, ['No', 'Kode Aset', 'Nama Barang', 'Kategori', 'Ruangan', 'Kondisi Baik', 'Kondisi Rusak Ringan', 'Kondisi Rusak Berat', 'Total Stok', 'Tersedia']);
            foreach ($data['items'] as $index => $item) {
                fputcsv($file, [
                    $index + 1,
                    $item->kode_aset,
                    $item->name,
                    $item->category->name ?? '-',
                    $item->room->name ?? '-',
                    $item->qty_baik,
                    $item->qty_rusak_ringan,
                    $item->qty_rusak_berat,
                    $item->quantity,
                    $item->qty_tersedia
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=" . $filename,
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ]);
    }

    private function getReportData($period, $month, $year, $roomId = null, $categoryId = null)
    {
        $targetDate = Carbon::create($year, $month, 1);

        $applyFilters = function ($query) use ($roomId, $categoryId) {
            if ($roomId) {
                $query->where('room_id', $roomId);
            }
            if ($categoryId) {
                $query->where('category_id', $categoryId);
            }
            return $query;
        };

        // 1. Summary Cards (Current state of all items)
        $totalJenisBarang = $applyFilters(Item::query())->count();
        $totalQuantity = $applyFilters(Item::query())->sum('quantity');
        $perluPerhatian = $applyFilters(Item::where(function($q) {
            $q->where('qty_rusak_ringan', '>', 0)
              ->orWhere('qty_rusak_berat', '>', 0);
        }))->count();

        // 2. Status Operasional (Pie Chart) - Units
        $statusOperasional = [
            'Tersedia' => $applyFilters(Item::query())->sum('qty_tersedia'),
            'Dipinjam' => $applyFilters(Item::query())->sum('qty_dipinjam'),
            'Sedang Diperbaiki' => $applyFilters(Item::query())->sum('qty_diperbaiki'),
            'Hilang' => $applyFilters(Item::query())->sum('qty_hilang'),
        ];

        // 3. Kondisi Fisik (Pie Chart) - Units
        $kondisiFisik = [
            'Baik' => $applyFilters(Item::query())->sum('qty_baik'),
            'Rusak Ringan' => $applyFilters(Item::query())->sum('qty_rusak_ringan'),
            'Rusak Berat' => $applyFilters(Item::query())->sum('qty_rusak_berat'),
        ];

        // 4. Trend 6 Months (Ending at selected date)
        $trendMonths = [];
        $borrowingTrend = [];
        $repairTrend = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = (clone $targetDate)->subMonths($i);
            $trendMonths[] = $date->format('M Y');
            
            $borrowingQuery = Borrowing::whereYear('tgl_pinjam', $date->year)
                ->whereMonth('tgl_pinjam', $date->month);
            
            $repairQuery = Repair::whereYear('tgl_service', $date->year)
                ->whereMonth('tgl_service', $date->month);

            if ($roomId || $categoryId) {
                $filterCallback = function($q) use ($roomId, $categoryId) {
                    if ($roomId) $q->where('room_id', $roomId);
                    if ($categoryId) $q->where('category_id', $categoryId);
                };
                $borrowingQuery->whereHas('item', $filterCallback);
                $repairQuery->whereHas('item', $filterCallback);
            }
                
            $borrowingTrend[] = $borrowingQuery->count();
            $repairTrend[] = $repairQuery->count();
        }

        // 5. Per Kategori
        $categoryBreakdown = Category::withCount(['items' => function($q) use ($roomId) {
            if ($roomId) $q->where('room_id', $roomId);
        }])->get()->map(function($cat) use ($roomId) {
            $totalUnitsQuery = Item::where('category_id', $cat->id);
            if ($roomId) {
                $totalUnitsQuery->where('room_id', $roomId);
            }
            return [
                'name' => $cat->name,
                'items_count' => $cat->items_count,
                'total_units' => $totalUnitsQuery->sum('quantity'),
            ];
        });

        // 6. Aktivitas (Period Specific)
        $borrowingQuery = Borrowing::whereYear('tgl_pinjam', $year);
        $repairQuery = Repair::whereYear('tgl_service', $year);
        $returnQuery = Borrowing::whereYear('tgl_kembali_aktual', $year);
        $repairFinishQuery = Repair::whereYear('updated_at', $year)->where('status', 'Selesai');

        if ($roomId || $categoryId) {
            $filterCallback = function($q) use ($roomId, $categoryId) {
                if ($roomId) $q->where('room_id', $roomId);
                if ($categoryId) $q->where('category_id', $categoryId);
            };
            $borrowingQuery->whereHas('item', $filterCallback);
            $repairQuery->whereHas('item', $filterCallback);
            $returnQuery->whereHas('item', $filterCallback);
            $repairFinishQuery->whereHas('item', $filterCallback);
        }

        if ($period == 'monthly') {
            $borrowingQuery->whereMonth('tgl_pinjam', $month);
            $repairQuery->whereMonth('tgl_service', $month);
            $returnQuery->whereMonth('tgl_kembali_aktual', $month);
            $repairFinishQuery->whereMonth('updated_at', $month);
        }

        $currentBorrowingQuery = Borrowing::where('status_pinjam', 'Dipinjam');
        $currentRepairQuery = Repair::where('status', 'Proses');
        if ($roomId || $categoryId) {
            $filterCallback = function($q) use ($roomId, $categoryId) {
                if ($roomId) $q->where('room_id', $roomId);
                if ($categoryId) $q->where('category_id', $categoryId);
            };
            $currentBorrowingQuery->whereHas('item', $filterCallback);
            $currentRepairQuery->whereHas('item', $filterCallback);
        }

        $borrowingStats = [
            'total' => $borrowingQuery->count(),
            'current' => $currentBorrowingQuery->count(),
            'returned' => $returnQuery->count(),
        ];

        $repairStats = [
            'total' => $repairQuery->count(),
            'current' => $currentRepairQuery->count(),
            'finished' => $repairFinishQuery->count(),
        ];

        // 7. Status Detail for Activity Tab
        $detailedStatus = [
            'Tersedia' => $applyFilters(Item::query())->sum('qty_tersedia'),
            'Dipinjam' => $applyFilters(Item::query())->sum('qty_dipinjam'),
            'Sedang Diperbaiki' => $applyFilters(Item::query())->sum('qty_diperbaiki'),
            'Hilang' => $applyFilters(Item::query())->sum('qty_hilang'),
            'Tidak Digunakan' => $applyFilters(Item::query())->sum('qty_tidak_digunakan'),
            'Dalam Pengadaan' => $applyFilters(Item::query())->sum('qty_pengadaan'),
        ];

        // 8. List of matching items for detail view in exports
        $itemsQuery = Item::with(['category', 'room']);
        if ($roomId) {
            $itemsQuery->where('room_id', $roomId);
        }
        if ($categoryId) {
            $itemsQuery->where('category_id', $categoryId);
        }
        $items = $itemsQuery->orderBy('name')->get();

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
            'items' => $items,
        ];
    }

    public function comparison()
    {
        // Get all completed audit headers ordered by date
        $headers = \App\Models\StockOpnameHeader::where('status', 'Completed')
            ->orderBy('tgl_audit', 'asc')
            ->get();
            
        // Get all active items
        $items = Item::with(['category', 'room'])->orderBy('name')->get();
        
        // Load details efficiently: group by item ID and then index by SO header ID
        $details = \App\Models\StockOpnameDetail::whereIn('id_so', $headers->pluck('id_so'))
            ->get()
            ->groupBy('id_barang')
            ->map(function ($itemDetails) {
                return $itemDetails->keyBy('id_so');
            });

        return view('admin.report.comparison', compact('headers', 'items', 'details'));
    }
}
