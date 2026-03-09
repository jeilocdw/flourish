<div class="text-center mb-6">
    <h2 class="text-2xl font-bold">Flourish Supermarket</h2>
    <p class="text-gray-500">{{ $sale->store?->name ?? 'Main Store' }}</p>
    <p class="text-gray-500">{{ $sale->store?->address ?? '' }}</p>
</div>

<div class="border-t border-b py-4 mb-4">
    <div class="flex justify-between mb-2">
        <span>Invoice:</span>
        <span class="font-medium">{{ $sale->invoice }}</span>
    </div>
    <div class="flex justify-between mb-2">
        <span>Date:</span>
        <span>{{ $sale->created_at->format('Y-m-d H:i') }}</span>
    </div>
    <div class="flex justify-between">
        <span>Cashier:</span>
        <span>{{ $sale->user->name }}</span>
    </div>
    @if($sale->customer)
    <div class="flex justify-between">
        <span>Customer:</span>
        <span>{{ $sale->customer->name }}</span>
    </div>
    @endif
</div>

<table class="w-full mb-4">
    <thead>
        <tr class="border-b">
            <th class="text-left py-2">Item</th>
            <th class="text-right py-2">Qty</th>
            <th class="text-right py-2">Price</th>
            <th class="text-right py-2">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sale->items as $item)
        <tr>
            <td class="py-2">{{ $item->product->name }}</td>
            <td class="text-right py-2">{{ $item->quantity }}</td>
            <td class="text-right py-2">${{ number_format($item->unit_price, 2) }}</td>
            <td class="text-right py-2">${{ number_format($item->total, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="border-t pt-4">
    <div class="flex justify-between mb-2">
        <span>Subtotal:</span>
        <span>${{ number_format($sale->subtotal, 2) }}</span>
    </div>
    <div class="flex justify-between mb-2">
        <span>Tax:</span>
        <span>${{ number_format($sale->tax, 2) }}</span>
    </div>
    <div class="flex justify-between text-xl font-bold">
        <span>Total:</span>
        <span>${{ number_format($sale->total, 2) }}</span>
    </div>
    <div class="flex justify-between mt-2">
        <span>Paid:</span>
        <span>${{ number_format($sale->paid, 2) }}</span>
    </div>
    <div class="flex justify-between">
        <span>Change:</span>
        <span>${{ number_format($sale->change, 2) }}</span>
    </div>
</div>

<div class="text-center mt-6 text-gray-500">
    <p>Payment Method: {{ ucfirst($sale->payment_method) }}</p>
    <p class="mt-2">Thank you for shopping with us!</p>
</div>
