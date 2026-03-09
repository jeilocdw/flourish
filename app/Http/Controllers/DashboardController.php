<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $storeId = $user->store_id;

        $totalProducts = \App\Models\Product::count();
        $totalSales = \App\Models\Sale::count();
        
        $todaySales = \App\Models\Sale::whereDate('created_at', today())
            ->when($storeId, fn($q) => $q->where('store_id', $storeId))
            ->count();
        
        $todayTotal = \App\Models\Sale::whereDate('created_at', today())
            ->when($storeId, fn($q) => $q->where('store_id', $storeId))
            ->sum('total');

        $lowStock = \App\Models\ProductStore::where('quantity', '<=', \DB::raw('alert_quantity'))
            ->when($storeId, fn($q) => $q->where('store_id', $storeId))
            ->count();

        $expiringSoon = \App\Models\Product::whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays(30))
            ->where('expiry_date', '>=', now())
            ->count();

        return view('dashboard', compact(
            'totalProducts', 'totalSales', 'todaySales', 'todayTotal', 'lowStock', 'expiringSoon'
        ));
    }
}
