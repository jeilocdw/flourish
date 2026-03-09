<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        $stores = \App\Models\Store::paginate(20);
        return view('stores.index', compact('stores'));
    }

    public function create()
    {
        return view('stores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:stores',
        ]);

        \App\Models\Store::create($request->all());

        return redirect()->route('stores.index')->with('success', 'Store created');
    }

    public function edit(\App\Models\Store $store)
    {
        return view('stores.edit', compact('store'));
    }

    public function update(Request $request, \App\Models\Store $store)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:stores,code,' . $store->id,
        ]);

        $store->update($request->all());

        return redirect()->route('stores.index')->with('success', 'Store updated');
    }

    public function destroy(\App\Models\Store $store)
    {
        if ($store->is_default) {
            return redirect()->route('stores.index')->with('error', 'Cannot delete default store');
        }
        $store->delete();
        return redirect()->route('stores.index')->with('success', 'Store deleted');
    }
}
