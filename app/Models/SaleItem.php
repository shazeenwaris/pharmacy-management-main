<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SaleItem extends Model
{
    protected $fillable = [
        'sale_id',
        'medicine_id',
        'medicine_batch_id',
        'medicine_packaging_id',
        'sale_unit',
        'conversion_factor',
        'quantity',
        'unit_price',
        'discount',
        'total',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'conversion_factor' => 'decimal:3',
        'unit_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(
            MedicineBatch::class,
            'medicine_batch_id'
        );
    }

    public function packaging(): BelongsTo
    {
        return $this->belongsTo(
            MedicinePackaging::class,
            'medicine_packaging_id'
        );
    }

    public function returns(): HasMany
    {
        return $this->hasMany(
            SaleReturn::class,
            'sale_item_id'
        );
    }

    public function getReturnedQuantityAttribute(): int
    {
        return (int) $this->returns()->sum('quantity');
    }

    public function getReturnableQuantityAttribute(): int
    {
        return max(
            0,
            (int) $this->quantity - $this->returned_quantity
        );
    }

    /**
     * Actual quantity consumed from batch stock.
     *
     * Example:
     * 2 Strips × 10 tablets = 20 base units
     */
    public function getBaseQuantityAttribute(): float
    {
        return round(
            (float) $this->quantity *
            (float) ($this->conversion_factor ?: 1),
            3
        );
    }
}