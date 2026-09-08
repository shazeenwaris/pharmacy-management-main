<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class MedicineBatch extends Model
{
    protected $fillable = [
        'medicine_id', 'batch_number', 'manufacturing_date', 'expiry_date',
        'purchase_price', 'sale_price', 'quantity',
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
    public function supplier(): \Illuminate\Database\Eloquent\Relations\BelongsTo
{
    return $this->belongsTo(Supplier::class);
}

    // Expiry status calculated from date, not manually trusted (spec requirement)
    public function getExpiryStatusAttribute(): string
    {
        $daysLeft = Carbon::now()->diffInDays($this->expiry_date, false);

        if ($daysLeft < 0) {
            return 'expired';
        }

      if ($daysLeft <= 7) {
    return 'expiring_soon';
}

        return 'active';
    }
}