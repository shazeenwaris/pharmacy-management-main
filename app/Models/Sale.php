<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Sale extends Model
{
    use LogsActivity;

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'subtotal',
        'discount',
        'tax',
        'grand_total',
        'paid_amount',
        'change_amount',
        'payment_method',
        'created_by',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(SalePayment::class);
    }
public function returns(): HasMany
{
    return $this->hasMany(SaleReturn::class);
}
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Payment Status
    |--------------------------------------------------------------------------
    */

    public function getPaymentStatusAttribute(): string
    {
        $grandTotal = (float) $this->grand_total;
        $paidAmount = (float) $this->paid_amount;

        if ($paidAmount <= 0) {
            return 'credit';
        }

        if ($paidAmount >= $grandTotal) {
            return 'paid';
        }

        return 'partial';
    }

    /*
    |--------------------------------------------------------------------------
    | Due Amount
    |--------------------------------------------------------------------------
    */

    public function getDueAmountAttribute(): float
    {
        return max(
            0,
            (float) $this->grand_total - (float) $this->paid_amount
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Activity Log
    |--------------------------------------------------------------------------
    */

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'invoice_number',
                'grand_total',
                'payment_method',
            ])
            ->logOnlyDirty()
            ->useLogName('Sale');
    }
}