@extends('layouts.app')

@section('header', 'Products')

@section('main')
<div class="bg-white rounded-lg shadow">
    <div class="p-4 border-b">
        <form method="GET" class="flex flex-wrap gap-4 mb-4">
            <input type="text" name="search" placeholder="Search products..." class="px-4 py-2 border rounded-lg" value="{{ request('search') }}">
            <select name="category_id" class="px-4 py-2 border rounded-lg">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Filter</button>
        </form>
        
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('products.create') }}" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Add Product</a>
            <a href="{{ route('products.template') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Download Template</a>
            <a href="{{ route('products.export') }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Export CSV</a>
            <button onclick="document.getElementById('importModal').classList.remove('hidden')" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700">Import CSV</button>
        </div>
    </div>
    
    <!-- Import Modal -->
    <div id="importModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold mb-4">Import Products</h3>
            <form method="POST" action="{{ route('products.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Select CSV File</label>
                    <input type="file" name="file" accept=".csv,.txt" class="w-full px-4 py-2 border rounded-lg" required>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Import</button>
                    <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cost</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($products as $product)
                <tr>
                    <td class="px-4 py-3">{{ $product->name }}</td>
                    <td class="px-4 py-3">{{ $product->sku }}</td>
                    <td class="px-4 py-3">{{ $product->category?->name }}</td>
                    <td class="px-4 py-3">{{ format_currency($product->cost_price) }}</td>
                    <td class="px-4 py-3">{{ format_currency($product->sell_price) }}</td>
                    <td class="px-4 py-3">
                        @php $stock = $product->productStore->sum('quantity') @endphp
                        <span class="{{ $stock < 10 ? 'text-red-600' : '' }}">{{ $stock }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('products.edit', $product) }}" class="text-blue-600 hover:underline mr-2">Edit</a>
                        <form method="POST" action="{{ route('products.destroy', $product) }}" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Delete this product?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">No products found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="p-4 border-t">
        {{ $products->links() }}
    </div>
</div>
@endsection
