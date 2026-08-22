<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PharmacySetting extends Model
{
    protected $fillable = [
        'pharmacy_name',
        'logo',
        'address',
        'phone',
        'email',
        'invoice_prefix',
        'invoice_start_number',
        'invoice_footer_note',
        'default_tax_percentage',
        'currency_code',
        'currency_symbol',
        'low_stock_threshold',
        'expiry_warning_days',
        'payment_methods',
    ];

    protected $casts = [
        'payment_methods' => 'array',
        'default_tax_percentage' => 'decimal:2',
    ];

    /**
     * Always work with a single settings row (singleton pattern).
     */
    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1]);
    }
}