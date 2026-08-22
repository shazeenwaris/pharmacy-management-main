<?php

if (! function_exists('currency_symbol')) {
    function currency_symbol(): string
    {
        return \Illuminate\Support\Facades\Cache::remember('pharmacy_currency_symbol', 3600, function () {
            return \App\Models\PharmacySetting::current()->currency_symbol ?? 'Rs.';
        });
    }
}

if (! function_exists('format_currency')) {
    function format_currency($amount): string
    {
        return currency_symbol() . ' ' . number_format((float) $amount, 2);
    }
}