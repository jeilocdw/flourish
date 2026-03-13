<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'quantity' => $request->stock_quantity ?? 0,
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
        $product->load('productStore');
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

        $product->update($request->except(['stock_quantity']));

        if ($request->has('stock_quantity')) {
            $storeId = auth()->user()->store_id;
            $productStore = \App\Models\ProductStore::where('product_id', $product->id)
                ->where('store_id', $storeId)
                ->first();
            if ($productStore) {
                $productStore->update(['quantity' => $request->stock_quantity]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }

    public function destroy(\App\Models\Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }

    public function export()
    {
        $products = \App\Models\Product::with(['category', 'brand', 'unit', 'productStores'])->get();
        
        $csv = "Name,SKU,Barcode,Category,Brand,Unit,Cost Price,Sell Price,Tax Rate,Alert Quantity,Expiry Date,Quantity\n";
        
        foreach ($products as $p) {
            $qty = $p->productStores->sum('quantity');
            $csv .= "\"{$p->name}\",\"{$p->sku}\",\"{$p->barcode}\",\"{$p->category?->name}\",\"{$p->brand?->name}\",\"{$p->unit?->name}\",{$p->cost_price},{$p->sell_price},{$p->tax_rate},{$p->alert_quantity},\"{$p->expiry_date}\",{$qty}\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=products_export.csv',
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ]);

        $file = fopen($request->file('file')->getRealPath(), 'r');
        $header = fgetcsv($file);
        
        $created = 0;
        $errors = [];
        
        while (($row = fgetcsv($file)) !== false) {
            $data = array_combine($header, $row);
            
            $categoryId = null;
            if (!empty($data['Category'])) {
                $category = \App\Models\Category::where('name', $data['Category'])->first();
                $categoryId = $category ? $category->id : null;
            }
            
            $brandId = null;
            if (!empty($data['Brand'])) {
                $brand = \App\Models\Brand::where('name', $data['Brand'])->first();
                $brandId = $brand ? $brand->id : null;
            }
            
            $unitId = 1;
            if (!empty($data['Unit'])) {
                $unit = \App\Models\Unit::where('name', $data['Unit'])->first();
                $unitId = $unit ? $unit->id : 1;
            }
            
            if (!$categoryId) {
                $errors[] = "Category not found for: {$data['Name']}";
                continue;
            }
            
            try {
                $product = \App\Models\Product::create([
                    'name' => $data['Name'],
                    'sku' => $data['SKU'],
                    'barcode' => $data['Barcode'] ?? null,
                    'category_id' => $categoryId,
                    'brand_id' => $brandId,
                    'unit_id' => $unitId,
                    'cost_price' => $data['Cost Price'] ?? 0,
                    'sell_price' => $data['Sell Price'] ?? 0,
                    'tax_rate' => $data['Tax Rate'] ?? 0,
                    'alert_quantity' => $data['Alert Quantity'] ?? 10,
                    'expiry_date' => !empty($data['Expiry Date']) ? $data['Expiry Date'] : null,
                ]);
                
                $storeId = Auth::user()->store_id;
                \App\Models\ProductStore::create([
                    'product_id' => $product->id,
                    'store_id' => $storeId,
                    'quantity' => $data['Quantity'] ?? 0,
                    'alert_quantity' => $data['Alert Quantity'] ?? 10,
                ]);
                
                $created++;
            } catch (\Exception $e) {
                $errors[] = "Error creating {$data['Name']}: " . $e->getMessage();
            }
        }
        
        fclose($file);
        
        if (count($errors) > 0) {
            return back()->with('error', "Imported {$created} products. Errors: " . implode(', ', array_slice($errors, 0, 5)));
        }
        
        return redirect()->route('products.index')->with('success', "Successfully imported {$created} products");
    }

    public function template()
    {
        $categories = \App\Models\Category::where('is_active', true)->pluck('name')->toArray();
        $brands = \App\Models\Brand::where('is_active', true)->pluck('name')->toArray();
        $units = \App\Models\Unit::where('is_active', true)->pluck('name')->toArray();
        
        $defaultCategory = !empty($categories) ? $categories[0] : '';
        $defaultBrand = !empty($brands) ? $brands[0] : '';
        $defaultUnit = !empty($units) ? $units[0] : 'pc';
        
        $csv = "Name,SKU,Barcode,Category,Brand,Unit,Cost Price,Sell Price,Tax Rate,Alert Quantity,Expiry Date,Quantity\n";
        $csv .= "Sample Product,SKU001,,{$defaultCategory},{$defaultBrand},{$defaultUnit},10.00,15.00,0,10,,100\n";
        
        $csv .= "\nAvailable Categories: " . implode(', ', $categories) . "\n";
        $csv .= "Available Brands: " . implode(', ', $brands) . "\n";
        $csv .= "Available Units: " . implode(', ', $units) . "\n";

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=products_template.csv',
        ]);
    }
}
