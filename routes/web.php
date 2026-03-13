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
    
    Route::get('/products/export', [\App\Http\Controllers\ProductController::class, 'export'])->name('products.export');
    Route::get('/products/template', [\App\Http\Controllers\ProductController::class, 'template'])->name('products.template');
    Route::post('/products/import', [\App\Http\Controllers\ProductController::class, 'import'])->name('products.import');
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
    Route::get('/inventory/low-stock', [\App\Http\Controllers\InventoryController::class, 'lowStock'])->name('inventory.lowStock');
    Route::get('/inventory/expiring', [\App\Http\Controllers\InventoryController::class, 'expiring'])->name('inventory.expiring');
    
    Route::get('/reports/sales', [\App\Http\Controllers\ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/export', [\App\Http\Controllers\ReportController::class, 'export'])->name('reports.export');
    
    Route::get('/settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
});
