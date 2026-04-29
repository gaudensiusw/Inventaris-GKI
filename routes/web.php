<?php

use App\Http\Controllers\Admin\{
    DashboardController, CategoryController, PermissionController,
    ItemController, RoleController, RoomController,
    UserController, OrderController,
    SettingController, QrScannerController,
    BorrowedController, RepairController,
    SpecialStatusController, HistoryController,
    ReportController
};
use App\Http\Controllers\Customer\{
    DashboardController as CustomerDashboardController, OrderController as CustomerOrderController, RentController as CustomerRentController,
    SettingController as CustomerSettingController
};
use App\Http\Controllers\PublicInventoryController;
use Illuminate\Support\Facades\Route;

// Public routes (no login required)
Route::get('/', function () {
    return redirect()->to(url('/inventaris'));
});

Route::get('/inventaris', [PublicInventoryController::class, 'index'])->name('public.inventory');

Route::get('/home', function () {
    if (auth()->check()) {
        if (auth()->user()->hasRole(['Admin', 'Super Admin'])) {
            return redirect()->route('admin.dashboard');
        }
    }
    return redirect()->to(url('/inventaris'));
});

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'role:Admin|Super Admin']], function () {
    // Dashboard
    Route::get('/dashboard', DashboardController::class)
        ->name('dashboard');

    // Inventaris (Items)
    Route::resource('/item', ItemController::class)
        ->except('show');
    Route::get('/item/{item}/barcode', [ItemController::class, 'barcode'])->name('item.barcode');

    // QR Scanner
    Route::get('/qr-scanner', [QrScannerController::class, 'index'])->name('qr-scanner');
    Route::get('/qr-scanner/search', [QrScannerController::class, 'search'])->name('qr-scanner.search');

    // Status Khusus - Barang Dipinjam
    Route::get('/borrowed', [BorrowedController::class, 'index'])->name('borrowed.index');

    // Status Khusus - Dalam Perbaikan
    Route::get('/repair', [RepairController::class, 'index'])->name('repair.index');

    // Status Khusus - Status Lainnya
    Route::get('/special-status', [SpecialStatusController::class, 'index'])->name('special-status.index');

    // Riwayat
    Route::get('/history', [HistoryController::class, 'index'])->name('history.index');

    // Laporan
    Route::get('/report/export-pdf', [ReportController::class, 'exportPdf'])->name('report.export-pdf');
    Route::get('/report', [ReportController::class, 'index'])->name('report.index');

    // Pengguna
    Route::resource('/user', UserController::class);

    // Legacy routes (still functional)
    Route::resource('/category', CategoryController::class)
        ->except('show', 'create', 'edit');
    Route::resource('/room', RoomController::class)
        ->except('show', 'create', 'edit');
    Route::resource('/order', OrderController::class);
    Route::resource('/role', RoleController::class);
    Route::resource('/permission', PermissionController::class)
        ->except('show', 'create', 'edit');

    // Pengaturan
    Route::controller(SettingController::class)->group(function(){
        Route::get('/setting', 'index')->name('setting.index');
        Route::put('/setting/update/{user}', 'update')->name('setting.update');
    });
});

Route::group(['prefix' => 'customer', 'as' => 'customer.', 'middleware' => ['auth', 'role:Customer']], function (){
    Route::get('/dashboard', CustomerDashboardController::class)->name('dashboard');
    Route::resource('/order', CustomerOrderController::class);
    Route::resource('/rent', CustomerRentController::class);
    Route::controller(CustomerSettingController::class)->group(function(){
        Route::get('/setting', 'index')->name('setting.index');
        Route::put('/setting/update/{user}', 'update')->name('setting.update');
    });
});
