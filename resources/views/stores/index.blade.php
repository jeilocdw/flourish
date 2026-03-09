@extends('layouts.app')

@section('header', 'Stores')

@section('main')
<div class="bg-white rounded-lg shadow">
    <div class="p-4 border-b flex justify-between items-center">
        <form method="GET" class="flex gap-4">
            <input type="text" name="search" placeholder="Search stores..." class="px-4 py-2 border rounded-lg" value="{{ request('search') }}">
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Filter</button>
        </form>
        <a href="{{ route('stores.create') }}" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Add Store</a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Address</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($stores as $store)
                <tr>
                    <td class="px-4 py-3">{{ $store->name }}</td>
                    <td class="px-4 py-3">{{ $store->code }}</td>
                    <td class="px-4 py-3">{{ $store->address ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $store->phone ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @if($store->is_active)
                            <span class="px-2 py-1 bg-green-100 text-green-600 rounded text-xs">Active</span>
                        @else
                            <span class="px-2 py-1 bg-red-100 text-red-600 rounded text-xs">Inactive</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('stores.edit', $store) }}" class="text-blue-600 hover:underline mr-2">Edit</a>
                        <form method="POST" action="{{ route('stores.destroy', $store) }}" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Delete this store?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">No stores found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if(method_exists($stores, 'links'))
    <div class="p-4 border-t">
        {{ $stores->links() }}
    </div>
    @endif
</div>
@endsection
