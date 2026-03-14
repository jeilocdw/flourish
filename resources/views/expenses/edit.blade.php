@extends('layouts.app')

@section('header', 'Edit Expense')

@section('main')
<div class="bg-white rounded-lg shadow p-6">
    <form method="POST" action="{{ route('expenses.update', $expense) }}">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
            <input type="text" name="category" value="{{ $expense->category }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="e.g., Rent, Utilities, Supplies" required>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
            <input type="number" name="amount" value="{{ $expense->amount }}" step="0.01" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
            <input type="date" name="date" value="{{ $expense->date->format('Y-m-d') }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" rows="3" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">{{ $expense->description }}</textarea>
        </div>
        
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Update</button>
            <a href="{{ route('expenses.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Cancel</a>
        </div>
    </form>
</div>
@endsection
