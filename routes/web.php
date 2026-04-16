<?php

use App\Http\Controllers\Admin\{
    DashboardController, CategoryController, PermissionController,
    ItemController, RoleController, RoomController,
    UserController, OrderController,
    SettingController
};
use App\Http\Controllers\Customer\{
    DashboardController as CustomerDashboardController, OrderController as CustomerOrderController, RentController as CustomerRentController,
    SettingController as CustomerSettingController
};
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::get('/home', function () {
    if (auth()->check()) {
        if (auth()->user()->hasRole(['Admin', 'Super Admin'])) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('customer.dashboard');
        }
    }
    return redirect('/login');
});

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'role:Admin|Super Admin']], function () {
    Route::get('/dashboard', DashboardController::class)
        ->name('dashboard')
        ->middleware('permission:index-dashboard');

    Route::resource('/category', CategoryController::class)
        ->except('show', 'create', 'edit')
        ->middleware('permission:index-category');

    Route::resource('/room', RoomController::class)
        ->except('show', 'create', 'edit')
        ->middleware('permission:index-room');

    Route::resource('/item', ItemController::class)
        ->except('show')
        ->middleware('permission:index-item');
    Route::get('/item/{item}/barcode', [ItemController::class, 'barcode'])->name('item.barcode');

    Route::resource('/order', OrderController::class)
        ->middleware('permission:index-order');

    Route::resource('/user', UserController::class)
        ->middleware('permission:index-user');

    Route::resource('/role', RoleController::class)
        ->middleware('permission:index-role');

    Route::resource('/permission', PermissionController::class)
        ->except('show', 'create', 'edit')
        ->middleware('permission:index-permission');

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
