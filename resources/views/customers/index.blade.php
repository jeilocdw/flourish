@extends('layouts.app')

@section('header', 'Customers')

@section('main')
<div class="bg-white rounded-lg shadow">
    <div class="p-4 border-b flex justify-between items-center">
        <form method="GET" class="flex gap-4">
            <input type="text" name="search" placeholder="Search customers..." class="px-4 py-2 border rounded-lg" value="{{ request('search') }}">
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Filter</button>
        </form>
        <a href="{{ route('customers.create') }}" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Add Customer</a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Address</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($customers as $customer)
                <tr>
                    <td class="px-4 py-3">{{ $customer->name }}</td>
                    <td class="px-4 py-3">{{ $customer->email ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $customer->phone ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $customer->address ?? '-' }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('customers.edit', $customer) }}" class="text-blue-600 hover:underline mr-2">Edit</a>
                        <form method="POST" action="{{ route('customers.destroy', $customer) }}" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Delete this customer?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">No customers found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if(method_exists($customers, 'links'))
    <div class="p-4 border-t">
        {{ $customers->links() }}
    </div>
    @endif
</div>
@endsection
