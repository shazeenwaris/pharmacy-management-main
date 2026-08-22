<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseReturn extends Model
{
    protected $fillable = [
        'purchase_id', 'medicine_id', 'medicine_batch_id', 'batch_number',
        'quantity', 'purchase_price', 'refund_amount', 'reason', 'return_date', 'notes',
    ];

    protected $casts = [
        'return_date' => 'date',
        'purchase_price' => 'decimal:2',
        'refund_amount' => 'decimal:2',
    ];

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(MedicineBatch::class, 'medicine_batch_id');
    }
}