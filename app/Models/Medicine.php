<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Medicine extends Model
{
    use LogsActivity;

    protected $fillable = [
        'image', 'name', 'generic_name', 'medicine_type', 'brand_name', 'sku', 'barcode',
        'category_id', 'manufacturer_id', 'supplier_id', 'dosage_form_id', 'unit_id', 'strength', 'pack_size',
        'purchase_price', 'sale_price', 'wholesale_price', 'minimum_sale_price',
        'tax_percent', 'discount_percent',
        'minimum_stock_level', 'maximum_stock_level', 'reorder_level',
        'prescription_required', 'status', 'description', 'notes','product_type',
    'packaging_type',
    'has_expiry',
    'strips_per_box',
    'units_per_strip',
    'sale_price',
     'shelf_location',
    ];

    protected $casts = [
        'prescription_required' => 'boolean',
        'status' => 'boolean',
        'purchase_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'wholesale_price' => 'decimal:2',
        'minimum_sale_price' => 'decimal:2',
        'tax_percent' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'has_expiry' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function dosageForm(): BelongsTo
    {
        return $this->belongsTo(DosageForm::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function batches(): HasMany
    {
        return $this->hasMany(MedicineBatch::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    // Current stock = sum of quantity across all batches of this medicine
    public function getCurrentStockAttribute(): int
    {
        return $this->batches()->sum('quantity');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'sale_price', 'purchase_price', 'status'])
            ->logOnlyDirty()
            ->useLogName('Medicine');
    }
}