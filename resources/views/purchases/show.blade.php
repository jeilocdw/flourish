@extends('layouts.app')

@section('header', 'Purchase Details')

@section('main')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h2 class="text-xl font-bold">Invoice: {{ $purchase->invoice }}</h2>
            <p class="text-gray-500">Date: {{ $purchase->created_at->format('M d, Y H:i') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('purchases.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Back</a>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6 mb-6">
        <div>
            <h3 class="font-semibold mb-2">Supplier</h3>
            <p>{{ $purchase->supplier->name ?? 'N/A' }}</p>
        </div>
        <div>
            <h3 class="font-semibold mb-2">Status</h3>
            <span class="px-2 py-1 bg-blue-100 text-blue-600 rounded text-xs">{{ ucfirst($purchase->status) }}</span>
        </div>
    </div>

    <h3 class="font-semibold mb-2">Items</h3>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">Product</th>
                    <th class="px-4 py-2 text-right">Qty</th>
                    <th class="px-4 py-2 text-right">Unit Cost</th>
                    <th class="px-4 py-2 text-right">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($purchase->items as $item)
                <tr>
                    <td class="px-4 py-2">{{ $item->product->name ?? 'Product #' . $item->product_id }}</td>
                    <td class="px-4 py-2 text-right">{{ $item->quantity }}</td>
                    <td class="px-4 py-2 text-right">{{ format_currency($item->unit_cost) }}</td>
                    <td class="px-4 py-2 text-right">{{ format_currency($item->total) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-4 text-center text-gray-500">No items found</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot class="bg-gray-50">
                <tr>
                    <td colspan="3" class="px-4 py-2 text-right font-bold">Total</td>
                    <td class="px-4 py-2 text-right font-bold">{{ format_currency($purchase->total) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
