@extends('layouts.app')

@section('header', 'Sale Details')

@section('main')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h2 class="text-xl font-bold">Invoice: {{ $sale->invoice }}</h2>
            <p class="text-gray-500">Date: {{ $sale->created_at->format('M d, Y H:i') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('pos.receipt', $sale) }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700" target="_blank">Print Receipt</a>
            <a href="{{ route('sales.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Back</a>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6 mb-6">
        <div>
            <h3 class="font-semibold mb-2">Store</h3>
            <p>{{ $sale->store->name ?? 'N/A' }}</p>
        </div>
        <div>
            <h3 class="font-semibold mb-2">Cashier</h3>
            <p>{{ $sale->user->name ?? 'N/A' }}</p>
        </div>
        <div>
            <h3 class="font-semibold mb-2">Customer</h3>
            <p>{{ $sale->customer->name ?? 'Walk-in Customer' }}</p>
        </div>
        <div>
            <h3 class="font-semibold mb-2">Payment Method</h3>
            <p class="capitalize">{{ $sale->payment_method }}</p>
        </div>
    </div>

    <h3 class="font-semibold mb-2">Items ({{ $items->count() }})</h3>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">Product</th>
                    <th class="px-4 py-2 text-right">Price</th>
                    <th class="px-4 py-2 text-right">Qty</th>
                    <th class="px-4 py-2 text-right">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($items as $item)
                <tr>
                    <td class="px-4 py-2">{{ $item->product->name ?? 'Product #' . $item->product_id }}</td>
                    <td class="px-4 py-2 text-right">{{ format_currency($item->unit_price) }}</td>
                    <td class="px-4 py-2 text-right">{{ $item->quantity }}</td>
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
                    <td colspan="3" class="px-4 py-2 text-right font-semibold">Subtotal</td>
                    <td class="px-4 py-2 text-right">{{ format_currency($sale->subtotal) }}</td>
                </tr>
                <tr>
                    <td colspan="3" class="px-4 py-2 text-right font-semibold">Tax</td>
                    <td class="px-4 py-2 text-right">{{ format_currency($sale->tax) }}</td>
                </tr>
                <tr>
                    <td colspan="3" class="px-4 py-2 text-right font-bold">Total</td>
                    <td class="px-4 py-2 text-right font-bold">{{ format_currency($sale->total) }}</td>
                </tr>
                <tr>
                    <td colspan="3" class="px-4 py-2 text-right">Paid</td>
                    <td class="px-4 py-2 text-right">{{ format_currency($sale->paid) }}</td>
                </tr>
                <tr>
                    <td colspan="3" class="px-4 py-2 text-right">Change</td>
                    <td class="px-4 py-2 text-right">{{ format_currency($sale->change_amount ?? 0) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
