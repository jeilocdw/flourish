@extends('layouts.app')

@section('header', 'Expiring Soon')

@section('main')
<div class="bg-white rounded-lg shadow">
    <div class="p-4 border-b flex justify-between items-center">
        <div class="flex gap-4">
            <a href="{{ route('inventory.index') }}" class="px-4 py-2 rounded-lg {{ request()->routeIs('inventory.index') ? 'bg-emerald-600 text-white' : 'bg-gray-200' }}">All Items</a>
            <a href="{{ route('inventory.lowStock') }}" class="px-4 py-2 rounded-lg {{ request()->routeIs('inventory.lowStock') ? 'bg-emerald-600 text-white' : 'bg-gray-200' }}">Low Stock</a>
            <a href="{{ route('inventory.expiring') }}" class="px-4 py-2 rounded-lg {{ request()->routeIs('inventory.expiring') ? 'bg-emerald-600 text-white' : 'bg-gray-200' }}">Expiring</a>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expiry Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($products as $item)
                <tr>
                    <td class="px-4 py-3">{{ $item->name ?? 'N/A' }}</td>
                    <td class="px-4 py-3">{{ $item->category?->name ?? 'N/A' }}</td>
                    <td class="px-4 py-3">{{ $item->productStore->first()?->quantity ?? 0 }}</td>
                    <td class="px-4 py-3">{{ $item->expiry_date?->format('Y-m-d') ?? 'N/A' }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 bg-orange-100 text-orange-600 rounded text-xs">Expiring Soon</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">No expiring products found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if(method_exists($products, 'links'))
    <div class="p-4 border-t">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection
