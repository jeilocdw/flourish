@extends('layouts.app')

@section('header', 'Add Product')

@section('main')
<div class="bg-white rounded-lg shadow p-6">
    <form method="POST" action="{{ route('products.store') }}" class="space-y-4">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Product Name *</label>
                <input type="text" name="name" class="w-full px-4 py-2 border rounded-lg" required>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">SKU *</label>
                <input type="text" name="sku" class="w-full px-4 py-2 border rounded-lg" required>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Barcode</label>
                <input type="text" name="barcode" class="w-full px-4 py-2 border rounded-lg">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                <select name="category_id" class="w-full px-4 py-2 border rounded-lg" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                <select name="brand_id" class="w-full px-4 py-2 border rounded-lg">
                    <option value="">Select Brand</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Unit *</label>
                <select name="unit_id" class="w-full px-4 py-2 border rounded-lg" required>
                    <option value="">Select Unit</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->short_name }})</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cost Price *</label>
                <input type="number" name="cost_price" step="0.01" class="w-full px-4 py-2 border rounded-lg" required>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sell Price *</label>
                <input type="number" name="sell_price" step="0.01" class="w-full px-4 py-2 border rounded-lg" required>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tax Rate (%)</label>
                <input type="number" name="tax_rate" step="0.01" value="0" class="w-full px-4 py-2 border rounded-lg">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Alert Quantity</label>
                <input type="number" name="alert_quantity" value="10" class="w-full px-4 py-2 border rounded-lg">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Initial Stock Quantity</label>
                <input type="number" name="stock_quantity" value="0" class="w-full px-4 py-2 border rounded-lg">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Expiry Date</label>
                <input type="date" name="expiry_date" class="w-full px-4 py-2 border rounded-lg">
            </div>
        </div>
        
        <div class="flex gap-4">
            <button type="submit" class="px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Save Product</button>
            <a href="{{ route('products.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Cancel</a>
        </div>
    </form>
</div>
@endsection
