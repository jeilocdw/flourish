@extends('layouts.app')

@section('header', 'Edit Unit')

@section('main')
<div class="bg-white rounded-lg shadow p-6">
    <form method="POST" action="{{ route('units.update', $unit) }}">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input type="text" name="name" value="{{ $unit->name }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="e.g., Kilogram" required>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Short Name</label>
            <input type="text" name="short_name" value="{{ $unit->short_name }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="e.g., kg" required>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Base Unit (optional)</label>
            <select name="base_unit" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <option value="">None</option>
                @foreach($baseUnits as $baseUnit)
                    <option value="{{ $baseUnit->id }}" {{ $unit->base_unit == $baseUnit->id ? 'selected' : '' }}>{{ $baseUnit->name }} ({{ $baseUnit->short_name }})</option>
                @endforeach
            </select>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Operator</label>
            <select name="operator" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <option value="">None</option>
                <option value="*" {{ $unit->operator == '*' ? 'selected' : '' }}>Multiply (*)</option>
                <option value="/" {{ $unit->operator == '/' ? 'selected' : '' }}>Divide (/)</option>
            </select>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Operator Value</label>
            <input type="number" name="operator_value" value="{{ $unit->operator_value }}" step="any" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="e.g., 1000">
        </div>
        
        <div class="mb-4">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" {{ $unit->is_active ? 'checked' : '' }} class="rounded text-emerald-600">
                <span class="text-sm text-gray-700">Active</span>
            </label>
        </div>
        
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Update</button>
            <a href="{{ route('units.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Cancel</a>
        </div>
    </form>
</div>
@endsection
