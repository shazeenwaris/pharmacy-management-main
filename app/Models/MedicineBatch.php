<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class MedicineBatch extends Model
{
    protected $fillable = [
        'medicine_id', 'batch_number', 'manufacturing_date', 'expiry_date',
        'purchase_price', 'sale_price', 'quantity',
        'boxes_received', 'strips_per_box', 'units_per_strip',
        'supplier_name', 'purchase_reference', 'status', 'notes',
    ];

    protected $casts = [
        'manufacturing_date' => 'date',
        'expiry_date' => 'date',
        'status' => 'boolean',
        'purchase_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
    ];

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function getExpiryStatusAttribute(): string
    {

            if (! $this->expiry_date) {
        return 'no_expiry';
    }
        $daysLeft = Carbon::now()->diffInDays($this->expiry_date, false);

        if ($daysLeft < 0) {
            return 'expired';
        }

        if ($daysLeft <= 7) {
            return 'expiring_soon';
        }

        return 'active';
    }
    public function batchPackagings(): HasMany
{
    return $this->hasMany(
        MedicineBatchPackaging::class,
        'medicine_batch_id'
    )->orderBy('sort_order');
}
}