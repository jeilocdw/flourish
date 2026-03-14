@extends('layouts.app')

@section('header', 'Sales Report')

@section('main')
<div class="bg-white rounded-lg shadow">
    <div class="p-4 border-b">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from', now()->startOfMonth()->format('Y-m-d')) }}" class="px-4 py-2 border rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to', now()->endOfMonth()->format('Y-m-d')) }}" class="px-4 py-2 border rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Store</label>
                <select name="store_id" class="px-4 py-2 border rounded-lg">
                    <option value="">All Stores</option>
                    @foreach($stores as $store)
                        <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>{{ $store->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Generate Report</button>
            <a href="{{ route('reports.sales') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Reset</a>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-500">Total Sales</p>
        <p class="text-2xl font-bold">${{ number_format($summary['total_sales'] ?? 0, 2) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-500">Total Orders</p>
        <p class="text-2xl font-bold">{{ $summary['total_orders'] ?? 0 }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-500">Average Order</p>
        <p class="text-2xl font-bold">${{ number_format($summary['average_order'] ?? 0, 2) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-500">Items Sold</p>
        <p class="text-2xl font-bold">{{ $summary['items_sold'] ?? 0 }}</p>
    </div>
</div>

<div class="bg-white rounded-lg shadow mt-4">
    <div class="p-4 border-b flex justify-between items-center">
        <h3 class="font-medium">Sales Details</h3>
        <a href="{{ route('reports.sales.export', request()->query()) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Export</a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice #</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Store</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Items</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Profit</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($sales as $sale)
                <tr>
                    <td class="px-4 py-3">{{ $sale->created_at->format('Y-m-d') }}</td>
                    <td class="px-4 py-3">{{ $sale->invoice_number }}</td>
                    <td class="px-4 py-3">{{ $sale->customer?->name ?? 'Walk-in' }}</td>
                    <td class="px-4 py-3">{{ $sale->store?->name ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $sale->items->sum('quantity') }}</td>
                    <td class="px-4 py-3">${{ number_format($sale->total_amount, 2) }}</td>
                    <td class="px-4 py-3">${{ number_format($sale->profit ?? 0, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">No sales found for the selected period</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if(method_exists($sales, 'links'))
    <div class="p-4 border-t">
        {{ $sales->links() }}
    </div>
    @endif
</div>
@endsection
