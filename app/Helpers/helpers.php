<?php

if (!function_exists('currency_symbol')) {
    function currency_symbol() {
        return \App\Models\Setting::where('key', 'currency_symbol')->value('value') ?? '$';
    }
}

if (!function_exists('format_currency')) {
    function format_currency($amount) {
        return currency_symbol() . number_format($amount, 2);
    }
}
