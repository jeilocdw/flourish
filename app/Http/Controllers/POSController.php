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
        $storeId = Auth::user()->store_id ?? 1;
        
        return view('pos', compact('categories', 'customers', 'storeId'));
    }

    public function products(Request $request)
    {
        $storeId = Auth::user()->store_id ?? 1;
        $search = $request->search;
        $categoryId = $request->category_id;
        
        $products = \App\Models\Product::with(['category', 'productStore'])
            ->when($search, function($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->when($categoryId, function($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->get()
            ->map(function($product) use ($storeId) {
                $storeStock = $product->productStore->where('store_id', $storeId)->first();
                $product->stock_quantity = $storeStock ? $storeStock->quantity : 0;
                return $product;
            });
        
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
        $storeId = Auth::user()->store_id ?? 1;
        
        \Illuminate\Support\Facades\Log::info('Processing sale with items:', ['items' => $items, 'count' => count($items)]);
        
        $subtotal = 0;
        $tax = 0;

        foreach ($items as $item) {
            $product = \App\Models\Product::find($item['product_id']);
            if (!$product) {
                return response()->json(['error' => 'Product not found: ' . $item['product_id']], 422);
            }
            $subtotal += $product->sell_price * $item['quantity'];
            $tax += ($product->sell_price * $item['quantity']) * ($product->tax_rate / 100);
        }

        $total = $subtotal + $tax;
        $paid = $request->paid_amount;

        if ($paid < $total) {
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
            'payment_method' => $request->payment_method,
            'status' => 'completed',
        ]);

        foreach ($items as $item) {
            $product = \App\Models\Product::find($item['product_id']);
            if (!$product) {
                continue;
            }
            $itemTotal = $product->sell_price * $item['quantity'];
            
            try {
                \App\Models\SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->sell_price,
                    'total' => $itemTotal,
                ]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Error creating sale item: ' . $e->getMessage());
            }

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
            'change' => $paid - $total,
        ]);
    }

    public function receipt(\App\Models\Sale $sale)
    {
        $sale->load(['items.product', 'customer', 'user', 'store']);
        return view('pos.receipt', compact('sale'));
    }
}
