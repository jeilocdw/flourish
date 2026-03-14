@extends('layouts.app')

@section('header', 'Expenses')

@section('main')
<div class="bg-white rounded-lg shadow">
    <div class="p-4 border-b flex justify-between items-center">
        <form method="GET" class="flex gap-4">
            <input type="text" name="search" placeholder="Search expenses..." class="px-4 py-2 border rounded-lg" value="{{ request('search') }}">
            <select name="category" class="px-4 py-2 border rounded-lg">
                <option value="">All Categories</option>
                <option value="utilities" {{ request('category') == 'utilities' ? 'selected' : '' }}>Utilities</option>
                <option value="rent" {{ request('category') == 'rent' ? 'selected' : '' }}>Rent</option>
                <option value="salaries" {{ request('category') == 'salaries' ? 'selected' : '' }}>Salaries</option>
                <option value="supplies" {{ request('category') == 'supplies' ? 'selected' : '' }}>Supplies</option>
                <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Other</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Filter</button>
        </form>
        <a href="{{ route('expenses.create') }}" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Add Expense</a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($expenses as $expense)
                <tr>
                    <td class="px-4 py-3">{{ $expense->created_at->format('Y-m-d') }}</td>
                    <td class="px-4 py-3">{{ $expense->description }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs capitalize">{{ $expense->category }}</span>
                    </td>
                    <td class="px-4 py-3">{{ format_currency($expense->amount) }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('expenses.edit', $expense) }}" class="text-blue-600 hover:underline mr-2">Edit</a>
                        <form method="POST" action="{{ route('expenses.destroy', $expense) }}" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Delete this expense?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">No expenses found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if(method_exists($expenses, 'links'))
    <div class="p-4 border-t">
        {{ $expenses->links() }}
    </div>
    @endif
</div>
@endsection
