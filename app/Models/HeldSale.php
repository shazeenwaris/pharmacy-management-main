<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;

class HeldSale extends Model
{
    protected $fillable = [
        'reference', 'customer_id', 'cart', 'discount', 'tax', 'held_by', 'note',
    ];

    protected $casts = [
        'cart' => 'array',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function heldBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'held_by');
    }
}