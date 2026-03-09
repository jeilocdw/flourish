<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class POSController extends Controller
{
    public function index()
    {
        $categories = \App\Models\Category::where('is_active', true)->get();
        $customers = \App\Models\Customer::where('is_active', true)->get();
        
        return view('pos', compact('categories', 'customers'));
    }

    public function products(Request $request)
    {
        $storeId = Auth::user()->store_id;
        
        $query = \App\Models\Product::with(['category', 'productStore' => function($q) use ($storeId) {
            $q->where('store_id', $storeId);
        }])->where('is_active', true);

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('sku', 'like', "%{$request->search}%")
                  ->orWhere('barcode', 'like', "%{$request->search}%");
            });
        }

        $products = $query->limit(50)->get();
        
        return response()->json($products);
    }

    public function processSale(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'payment_method' => 'required|in:cash,card,mobile,bank_transfer',
            'paid_amount' => 'required|numeric|min:0',
        ]);

        $items = $request->items;
        $storeId = Auth::user()->store_id;
        
        $subtotal = 0;
        $tax = 0;

        foreach ($items as $item) {
            $product = \App\Models\Product::find($item['product_id']);
            $subtotal += $product->sell_price * $item['quantity'];
            $tax += ($product->sell_price * $item['quantity']) * ($product->tax_rate / 100);
        }

        $total = $subtotal + $tax;
        $paid = $request->paid_amount;
        $change = $paid - $total;

        if ($change < 0) {
            return response()->json(['error' => 'Insufficient payment'], 422);
        }

        $invoice = 'INV-' . date('Ymd') . '-' . Str::random(6);

        $sale = \App\Models\Sale::create([
            'invoice' => $invoice,
            'store_id' => $storeId,
            'customer_id' => $request->customer_id,
            'user_id' => Auth::id(),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'paid' => $paid,
            'change' => $change,
            'payment_method' => $request->payment_method,
            'status' => 'completed',
        ]);

        foreach ($items as $item) {
            $product = \App\Models\Product::find($item['product_id']);
            $itemTotal = $product->sell_price * $item['quantity'];
            
            \App\Models\SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $product->sell_price,
                'total' => $itemTotal,
            ]);

            $productStore = \App\Models\ProductStore::where('product_id', $item['product_id'])
                ->where('store_id', $storeId)
                ->first();
            
            if ($productStore) {
                $productStore->decrement('quantity', $item['quantity']);
            }
        }

        \App\Models\Payment::create([
            'sale_id' => $sale->id,
            'amount' => $paid,
            'payment_method' => $request->payment_method,
        ]);

        return response()->json([
            'success' => true,
            'sale_id' => $sale->id,
            'invoice' => $invoice,
            'change' => $change,
        ]);
    }

    public function receipt(\App\Models\Sale $sale)
    {
        $sale->load(['items.product', 'customer', 'user', 'store']);
        return view('pos.receipt', compact('sale'));
    }
}
