<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HeldSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'hold_number',
        'user_id',
        'customer_id',
        'cart',
        'subtotal',
        'discount',
        'tax',
        'grand_total',
        'paid_amount',
        'payment_method',
        'status',
        'note',
    ];

    protected $casts = [
        'cart'        => 'array',
        'subtotal'    => 'decimal:2',
        'discount'    => 'decimal:2',
        'tax'         => 'decimal:2',
        'grand_total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function heldBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}