@extends('layouts.app')

@section('header', 'Purchases')

@section('main')
<div class="bg-white rounded-lg shadow">
    <div class="p-4 border-b flex justify-between items-center">
        <form method="GET" class="flex gap-4">
            <input type="text" name="search" placeholder="Search by reference..." class="px-4 py-2 border rounded-lg" value="{{ request('search') }}">
            <input type="date" name="date_from" class="px-4 py-2 border rounded-lg" value="{{ request('date_from') }}">
            <input type="date" name="date_to" class="px-4 py-2 border rounded-lg" value="{{ request('date_to') }}">
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Filter</button>
        </form>
        <a href="{{ route('purchases.create') }}" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Add Purchase</a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reference #</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Supplier</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Items</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($purchases as $purchase)
                <tr>
                    <td class="px-4 py-3">{{ $purchase->reference_number }}</td>
                    <td class="px-4 py-3">{{ $purchase->created_at->format('Y-m-d H:i') }}</td>
                    <td class="px-4 py-3">{{ $purchase->supplier?->name ?? 'N/A' }}</td>
                    <td class="px-4 py-3">{{ $purchase->items_count ?? $purchase->items->count() }}</td>
                    <td class="px-4 py-3">{{ format_currency($purchase->total) }}</td>
                    <td class="px-4 py-3">
                        @if($purchase->status === 'received')
                            <span class="px-2 py-1 bg-green-100 text-green-600 rounded text-xs">Received</span>
                        @elseif($purchase->status === 'pending')
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-600 rounded text-xs">Pending</span>
                        @elseif($purchase->status === 'ordered')
                            <span class="px-2 py-1 bg-blue-100 text-blue-600 rounded text-xs">Ordered</span>
                        @else
                            <span class="px-2 py-1 bg-red-100 text-red-600 rounded text-xs">{{ ucfirst($purchase->status) }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('purchases.show', $purchase) }}" class="text-blue-600 hover:underline mr-2">View</a>
                        <a href="{{ route('purchases.edit', $purchase) }}" class="text-gray-600 hover:underline">Edit</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">No purchases found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if(method_exists($purchases, 'links'))
    <div class="p-4 border-t">
        {{ $purchases->links() }}
    </div>
    @endif
</div>
@endsection
