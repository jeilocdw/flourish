<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);

Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('products', \App\Http\Controllers\ProductController::class);
    Route::resource('categories', \App\Http\Controllers\CategoryController::class);
    Route::resource('brands', \App\Http\Controllers\BrandController::class);
    Route::resource('units', \App\Http\Controllers\UnitController::class);
    Route::resource('customers', \App\Http\Controllers\CustomerController::class);
    Route::resource('suppliers', \App\Http\Controllers\SupplierController::class);
    Route::resource('sales', \App\Http\Controllers\SaleController::class);
    Route::resource('purchases', \App\Http\Controllers\PurchaseController::class);
    Route::resource('expenses', \App\Http\Controllers\ExpenseController::class);
    Route::resource('users', \App\Http\Controllers\UserController::class);
    Route::resource('stores', \App\Http\Controllers\StoreController::class);
    
    Route::get('/pos', [\App\Http\Controllers\POSController::class, 'index'])->name('pos.index');
    Route::get('/pos/products', [\App\Http\Controllers\POSController::class, 'products']);
    Route::post('/pos/process-sale', [\App\Http\Controllers\POSController::class, 'processSale']);
    Route::get('/pos/receipt/{sale}', [\App\Http\Controllers\POSController::class, 'receipt']);
    
    Route::get('/inventory', [\App\Http\Controllers\InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/low-stock', [\App\Http\Controllers\InventoryController::class, 'lowStock']);
    Route::get('/inventory/expiring', [\App\Http\Controllers\InventoryController::class, 'expiring']);
    
    Route::get('/reports/sales', [\App\Http\Controllers\ReportController::class, 'sales']);
    Route::get('/reports/export', [\App\Http\Controllers\ReportController::class, 'export']);
    
    Route::get('/settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
});
