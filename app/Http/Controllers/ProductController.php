<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Product::with(['category', 'brand', 'unit']);
        
        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('sku', 'like', "%{$request->search}%")
                  ->orWhere('barcode', 'like', "%{$request->search}%");
        }
        
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        
        $products = $query->paginate(20);
        $categories = \App\Models\Category::where('is_active', true)->get();
        
        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = \App\Models\Category::where('is_active', true)->get();
        $brands = \App\Models\Brand::where('is_active', true)->get();
        $units = \App\Models\Unit::where('is_active', true)->get();
        
        return view('products.create', compact('categories', 'brands', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products',
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'cost_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
        ]);

        $product = \App\Models\Product::create($request->all());

        $storeId = auth()->user()->store_id;
        \App\Models\ProductStore::create([
            'product_id' => $product->id,
            'store_id' => $storeId,
            'quantity' => 0,
            'alert_quantity' => $request->alert_quantity ?? 10,
        ]);

        return redirect()->route('products.index')->with('success', 'Product created successfully');
    }

    public function show(\App\Models\Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(\App\Models\Product $product)
    {
        $categories = \App\Models\Category::where('is_active', true)->get();
        $brands = \App\Models\Brand::where('is_active', true)->get();
        $units = \App\Models\Unit::where('is_active', true)->get();
        
        return view('products.edit', compact('product', 'categories', 'brands', 'units'));
    }

    public function update(Request $request, \App\Models\Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'cost_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }

    public function destroy(\App\Models\Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }
}
