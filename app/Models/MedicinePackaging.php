<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicinePackaging extends Model
{
    protected $fillable = [
        'medicine_id',
        'name',
        'conversion_factor',
        'is_base_unit',
        'can_sell',
        'sort_order',
    ];

    protected $casts = [
        'conversion_factor' => 'decimal:4',
        'is_base_unit' => 'boolean',
        'can_sell' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }
}