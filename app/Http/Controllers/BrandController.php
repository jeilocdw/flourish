<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $brands = \App\Models\Brand::withCount('products')->paginate(20);
        return view('brands.index', compact('brands'));
    }

    public function create()
    {
        return view('brands.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        \App\Models\Brand::create($request->all());
        return redirect()->route('brands.index')->with('success', 'Brand created');
    }

    public function show(\App\Models\Brand $brand)
    {
        return view('brands.show', compact('brand'));
    }

    public function edit(\App\Models\Brand $brand)
    {
        return view('brands.edit', compact('brand'));
    }

    public function update(Request $request, \App\Models\Brand $brand)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $brand->update($request->all());
        return redirect()->route('brands.index')->with('success', 'Brand updated');
    }

    public function destroy(\App\Models\Brand $brand)
    {
        $brand->delete();
        return redirect()->route('brands.index')->with('success', 'Brand deleted');
    }
}
