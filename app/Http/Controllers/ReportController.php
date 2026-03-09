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
        
        $query = \App\Models\Sale::with(['customer', 'user'])
            ->when($storeId, fn($q) => $q->where('store_id', $storeId));

        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $sales = $query->orderBy('created_at', 'desc')->get();
        
        $totalSales = $sales->count();
        $totalAmount = $sales->sum('total');
        $totalTax = $sales->sum('tax');

        return view('reports.sales', compact('sales', 'totalSales', 'totalAmount', 'totalTax'));
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
