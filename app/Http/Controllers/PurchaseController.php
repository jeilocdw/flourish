<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Purchase::with(['supplier', 'user', 'store']);
        
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $purchases = $query->orderBy('created_at', 'desc')->paginate(20);
        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = \App\Models\Supplier::where('is_active', true)->get();
        $products = \App\Models\Product::where('is_active', true)->get();
        return view('purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'nullable|exists:suppliers,id',
            'items' => 'required|array|min:1',
        ]);

        $invoice = 'PO-' . date('Ymd') . '-' . Str::random(6);
        $total = 0;

        $purchase = \App\Models\Purchase::create([
            'invoice' => $invoice,
            'supplier_id' => $request->supplier_id,
            'store_id' => Auth::user()->store_id,
            'user_id' => Auth::id(),
            'total' => 0,
            'status' => 'completed',
        ]);

        foreach ($request->items as $item) {
            $product = \App\Models\Product::find($item['product_id']);
            $itemTotal = $product->cost_price * $item['quantity'];
            $total += $itemTotal;

            \App\Models\PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_cost' => $product->cost_price,
                'total' => $itemTotal,
            ]);

            $productStore = \App\Models\ProductStore::firstOrNew([
                'product_id' => $item['product_id'],
                'store_id' => Auth::user()->store_id,
            ]);
            $productStore->quantity += $item['quantity'];
            $productStore->save();
        }

        $purchase->update(['total' => $total]);

        return redirect()->route('purchases.index')->with('success', 'Purchase created');
    }

    public function show(\App\Models\Purchase $purchase)
    {
        $purchase->load(['items.product', 'supplier', 'user', 'store']);
        return view('purchases.show', compact('purchase'));
    }

    public function destroy(\App\Models\Purchase $purchase)
    {
        $purchase->delete();
        return redirect()->route('purchases.index')->with('success', 'Purchase deleted');
    }
}
