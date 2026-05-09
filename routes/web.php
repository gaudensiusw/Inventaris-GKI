<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\BorrowingController;
use App\Http\Controllers\Admin\RepairController;
use App\Http\Controllers\Admin\SpecialStatusController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StockOpnameController;
use App\Http\Controllers\Admin\DisposalController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HistoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\QrScannerController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\User\KatalogController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ============================================================
// ADMIN ROUTES (Super Admin + Admin)
// ============================================================
Route::prefix('admin')->middleware(['auth', 'role:Super Admin,Admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/inventory', [ItemController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/create', [ItemController::class, 'create'])->name('inventory.create');
    Route::post('/inventory/store', [ItemController::class, 'store'])->name('inventory.store');
    Route::get('/inventory/export', [ItemController::class, 'exportCsv'])->name('inventory.export');
    Route::delete('/inventory/{id}', [ItemController::class, 'destroy'])->name('inventory.destroy');
    Route::put('/inventory/{id}', [ItemController::class, 'update'])->name('inventory.update');
    
    Route::get('/room', [RoomController::class, 'index'])->name('room.index');
    Route::put('/room/{id}', [RoomController::class, 'update'])->name('room.update');
    
    Route::get('/stock-opname', [StockOpnameController::class, 'index'])->name('stock-opname.index');
    Route::get('/stock-opname/create', [StockOpnameController::class, 'create'])->name('stock-opname.create');
    Route::post('/stock-opname/store', [StockOpnameController::class, 'store'])->name('stock-opname.store');
    Route::get('/stock-opname/{stockOpname}', [StockOpnameController::class, 'show'])->name('stock-opname.show');
    Route::get('/stock-opname/{stockOpname}/print', [StockOpnameController::class, 'print'])->name('stock-opname.print');
    
    // QR Scanner
    Route::get('/qr-scanner', [QrScannerController::class, 'index'])->name('qr-scanner.index');
    Route::post('/qr-scanner/search', [QrScannerController::class, 'search'])->name('qr-scanner.search');
    
    // Borrowing (existing)
    Route::get('/borrowing', [BorrowingController::class, 'index'])->name('borrowing.index');
    Route::post('/borrowing/store', [BorrowingController::class, 'store'])->name('borrowing.store');
    Route::post('/borrowing/{id}/return', [BorrowingController::class, 'returnItem'])->name('borrowing.return');
    
    // Borrowing approval (new)
    Route::get('/borrowing/requests', [BorrowingController::class, 'pendingRequests'])->name('borrowing.requests');
    Route::post('/borrowing/requests/{id}/approve', [BorrowingController::class, 'approve'])->name('borrowing.approve');
    Route::post('/borrowing/requests/{id}/reject', [BorrowingController::class, 'reject'])->name('borrowing.reject');
    
    Route::get('/repair', [RepairController::class, 'index'])->name('repair.index');
    Route::post('/repair/store', [RepairController::class, 'store'])->name('repair.store');
    Route::post('/repair/{id}/complete', [RepairController::class, 'completeRepair'])->name('repair.complete');

    Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
    
    Route::get('/special-status', [SpecialStatusController::class, 'index'])->name('special-status.index');
    Route::get('/report', [ReportController::class, 'index'])->name('report.index');
    Route::get('/report/comparison', [ReportController::class, 'comparison'])->name('report.comparison');
    Route::get('/disposal', [DisposalController::class, 'index'])->name('disposal.index');
    Route::post('/disposal/{id}/restore', [DisposalController::class, 'restore'])->name('disposal.restore');

    Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
});

// ============================================================
// SUPER ADMIN ONLY ROUTES
// ============================================================
Route::prefix('admin')->middleware(['auth', 'role:Super Admin'])->group(function () {
    // User management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    
    // Category management
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    
    // Room create/delete (edit is for all admins above)
    Route::post('/room', [RoomController::class, 'store'])->name('room.store');
    Route::delete('/room/{id}', [RoomController::class, 'destroy'])->name('room.destroy');
    
    // Permanent delete (disposal)
    Route::delete('/disposal/{id}/force', [DisposalController::class, 'forceDelete'])->name('disposal.forceDelete');
});

// ============================================================
// PUBLIC ROUTES — Katalog & Form Peminjaman (Tanpa Login)
// ============================================================
Route::prefix('peminjaman')->name('user.')->group(function () {
    // Katalog barang publik
    Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog.index');
    
    // Form request peminjaman
    Route::get('/request/{itemId}', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/request', [OrderController::class, 'store'])->name('orders.store');
    
    // Cek status peminjaman (by kode)
    Route::get('/status', [OrderController::class, 'checkStatus'])->name('orders.status');
});

