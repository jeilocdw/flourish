<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Sale::with(['customer', 'user', 'store']);
        
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        $sales = $query->orderBy('created_at', 'desc')->paginate(20);
        return view('sales.index', compact('sales'));
    }

    public function show(\App\Models\Sale $sale)
    {
        $sale->load(['items.product', 'customer', 'user', 'store', 'payments']);
        return view('sales.show', compact('sale'));
    }

    public function destroy(\App\Models\Sale $sale)
    {
        $sale->delete();
        return redirect()->route('sales.index')->with('success', 'Sale deleted');
    }
}
