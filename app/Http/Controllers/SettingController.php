<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = \App\Models\Setting::pluck('value', 'key')->toArray();
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'store_name' => 'required|string|max:255',
            'currency_symbol' => 'required|string|max:5',
            'tax_rate' => 'required|numeric|min:0|max:100',
        ]);

        $settings = [
            'store_name' => $request->store_name,
            'currency' => $request->currency,
            'currency_symbol' => $request->currency_symbol,
            'tax_rate' => $request->tax_rate,
            'store_address' => $request->store_address,
            'store_phone' => $request->store_phone,
            'store_email' => $request->store_email,
            'low_stock_alert' => $request->low_stock_alert,
            'invoice_prefix' => $request->has('invoice_prefix') ? 1 : 0,
        ];

        foreach ($settings as $key => $value) {
            \App\Models\Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->route('settings.index')->with('success', 'Settings updated');
    }
}
