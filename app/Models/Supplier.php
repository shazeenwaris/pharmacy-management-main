<?php

namespace App\Models;
use App\Models\SupplierPayment:
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $fillable = [
        'supplier_name', 'company_name', 'contact_person', 'phone', 'whatsapp', 'email',
        'address', 'city', 'country',
        'tax_ntn_number', 'opening_balance', 'credit_limit', 'payment_terms',
        'status', 'notes',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'credit_limit' => 'decimal:2',
    ];

    public function payments(): HasMany
    {
        return $this->hasMany(SupplierPayment::class);
    }

    /**
     * Current payable balance = Opening Balance - Total Payments Made.
     * NOTE: Purchase invoices will be added to this calculation once the
     * Purchases module is built (opening_balance + purchases - payments).
     */
    public function getPayableBalanceAttribute(): float
    {
        return (float) $this->opening_balance - (float) $this->payments()->sum('amount');
    }
}