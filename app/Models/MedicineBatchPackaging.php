<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicineBatchPackaging extends Model
{
    protected $fillable = [
        'medicine_batch_id',
        'unit_id',
        'conversion_factor',
        'can_sell',
        'sort_order',
    ];

    protected $casts = [
        'conversion_factor' => 'decimal:4',
        'can_sell' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function medicineBatch(): BelongsTo
    {
        return $this->belongsTo(
            MedicineBatch::class,
            'medicine_batch_id'
        );
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(
            Unit::class,
            'unit_id'
        );
    }
}