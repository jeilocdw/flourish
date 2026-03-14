<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $storeId = Auth::user()->store_id;
        
        $query = \App\Models\Sale::with(['customer', 'user', 'store'])
            ->when($storeId, fn($q) => $q->where('store_id', $storeId));
        
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
        
        $saleIds = $sales->pluck('id');
        $itemCounts = \App\Models\SaleItem::whereIn('sale_id', $saleIds)
            ->select('sale_id', \Illuminate\Support\Facades\DB::raw('COUNT(*) as count'))
            ->groupBy('sale_id')
            ->pluck('count', 'sale_id');
        
        return view('sales.index', compact('sales', 'itemCounts'));
    }

    public function cleanup()
    {
        $user = Auth::user();
        if (!$user || !in_array($user->role, ['admin', 'manager'])) {
            abort(403, 'Only admins and managers can cleanup sales.');
        }
        
        $salesWithoutItems = \App\Models\Sale::whereDoesntHave('items')->get();
        $count = $salesWithoutItems->count();
        $salesWithoutItems->each->delete();
        
        return redirect()->route('sales.index')->with('success', "Deleted $count sales without items");
    }

    public function show($id)
    {
        $sale = \App\Models\Sale::findOrFail($id);
        $items = \App\Models\SaleItem::where('sale_id', $id)->get();
        
        // Get product for each item
        foreach ($items as $item) {
            $item->product = \App\Models\Product::find($item->product_id);
        }
        
        return view('sales.show', compact('sale', 'items'));
    }

    public function destroy(\App\Models\Sale $sale)
    {
        $sale->delete();
        return redirect()->route('sales.index')->with('success', 'Sale deleted');
    }
}
