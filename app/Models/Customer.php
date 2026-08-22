<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Customer extends Model
{
    use LogsActivity;

    protected $fillable = [
        'name', 'phone', 'email', 'address', 'date_of_birth',
        'opening_balance', 'credit_limit', 'status', 'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'opening_balance' => 'decimal:2',
        'credit_limit' => 'decimal:2',
        'status' => 'boolean',
    ];

    public function payments(): HasMany
    {
        return $this->hasMany(CustomerPayment::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function getDueBalanceAttribute(): float
    {
        $totalSales = (float) $this->sales()->sum('grand_total');
        $totalPaidInSales = (float) $this->sales()->sum('paid_amount');
        $totalPayments = (float) $this->payments()->sum('amount');

        return (float) $this->opening_balance + ($totalSales - $totalPaidInSales) - $totalPayments;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'phone', 'status'])
            ->logOnlyDirty()
            ->useLogName('Customer');
    }
}