<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = \App\Models\Unit::paginate(20);
        return view('units.index', compact('units'));
    }

    public function create()
    {
        $baseUnits = \App\Models\Unit::where('is_active', true)->get();
        return view('units.create', compact('baseUnits'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255', 'short_name' => 'required|string|max:10']);
        \App\Models\Unit::create($request->all());
        return redirect()->route('units.index')->with('success', 'Unit created');
    }

    public function show(\App\Models\Unit $unit)
    {
        return view('units.show', compact('unit'));
    }

    public function edit(\App\Models\Unit $unit)
    {
        $baseUnits = \App\Models\Unit::where('is_active', true)->get();
        return view('units.edit', compact('unit', 'baseUnits'));
    }

    public function update(Request $request, \App\Models\Unit $unit)
    {
        $request->validate(['name' => 'required|string|max:255', 'short_name' => 'required|string|max:10']);
        $unit->update($request->all());
        return redirect()->route('units.index')->with('success', 'Unit updated');
    }

    public function destroy(\App\Models\Unit $unit)
    {
        $unit->delete();
        return redirect()->route('units.index')->with('success', 'Unit deleted');
    }
}
