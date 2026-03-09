<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $storeId = Auth::user()->store_id;
        
        $query = \App\Models\ProductStore::with(['product.category', 'product.brand', 'store'])
            ->where('store_id', $storeId);

        $products = $query->paginate(20);
        
        return view('inventory.index', compact('products'));
    }

    public function lowStock()
    {
        $storeId = Auth::user()->store_id;
        
        $products = \App\Models\ProductStore::with(['product.category', 'product.brand', 'store'])
            ->where('store_id', $storeId)
            ->whereRaw('quantity <= alert_quantity')
            ->paginate(20);
        
        return view('inventory.low-stock', compact('products'));
    }

    public function expiring()
    {
        $storeId = Auth::user()->store_id;
        
        $products = \App\Models\Product::with(['category', 'brand', 'productStore' => function($q) use ($storeId) {
            $q->where('store_id', $storeId);
        }])
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays(30))
            ->where('expiry_date', '>=', now())
            ->paginate(20);
        
        return view('inventory.expiring', compact('products'));
    }
}
