<?php

namespace App\Models;

use App\Models\SupplierPayment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
<<<<<<< HEAD
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
=======
>>>>>>> d06efcbf243a71ca1589c114591841d520a8f34f

class Supplier extends Model
{
    use LogsActivity;

    protected $fillable = [
        'supplier_name',
        'company_name',
        'contact_person',
        'phone',
        'whatsapp',
        'email',
        'address',
        'city',
        'country',
        'tax_ntn_number',
        'opening_balance',
        'credit_limit',
        'payment_terms',
        'status',
        'notes',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'credit_limit' => 'decimal:2',
    ];

    public function payments(): HasMany
    {
        return $this->hasMany(SupplierPayment::class);
    }

<<<<<<< HEAD
=======
    /**
     * Current payable balance =
     * Opening Balance - Total Payments Made.
     */
>>>>>>> d06efcbf243a71ca1589c114591841d520a8f34f
    public function getPayableBalanceAttribute(): float
    {
        return (float) $this->opening_balance
            - (float) $this->payments()->sum('amount');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['supplier_name', 'status', 'credit_limit'])
            ->logOnlyDirty()
            ->useLogName('Supplier');
    }
}
