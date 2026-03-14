<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $storeId = Auth::user()->store_id;
        
        $query = \App\Models\Sale::with(['customer', 'user', 'store', 'items.product'])
            ->when($storeId, fn($q) => $q->where('store_id', $storeId));

        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->store_id) {
            $query->where('store_id', $request->store_id);
        }

        $sales = $query->orderBy('created_at', 'desc')->get();
        
        $totalOrders = $sales->count();
        $totalAmount = $sales->sum('total');
        $itemsSold = $sales->loadCount('items')->sum('items_count');
        $averageOrder = $totalOrders > 0 ? $totalAmount / $totalOrders : 0;
        
        $summary = [
            'total_sales' => $totalAmount,
            'total_orders' => $totalOrders,
            'average_order' => $averageOrder,
            'items_sold' => $itemsSold,
        ];

        $stores = \App\Models\Store::where('is_active', true)->get();

        return view('reports.sales', compact('sales', 'summary', 'stores'));
    }

    public function export(Request $request)
    {
        $storeId = Auth::user()->store_id;
        
        $query = \App\Models\Sale::with(['customer', 'user'])
            ->when($storeId, fn($q) => $q->where('store_id', $storeId));

        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $sales = $query->orderBy('created_at', 'desc')->get();

        $csv = "Invoice,Date,Customer,Cashier,Subtotal,Tax,Total,Payment Method,Status\n";
        
        foreach ($sales as $sale) {
            $csv .= "{$sale->invoice},{$sale->created_at->format('Y-m-d H:i')},";
            $csv .= "{$sale->customer?->name},{$sale->user->name},";
            $csv .= "{$sale->subtotal},{$sale->tax},{$sale->total},";
            $csv .= "{$sale->payment_method},{$sale->status}\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=sales_report.csv',
        ]);
    }
}
