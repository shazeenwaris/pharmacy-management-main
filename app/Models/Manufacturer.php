<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Manufacturer extends Model
{
    protected $fillable = [
        'name', 'company_code', 'contact_person', 'phone', 'email',
        'address', 'city', 'country', 'website', 'tax_ntn_number', 'status',
    ];
    protected $casts = ['status' => 'boolean'];

    public function medicines(): HasMany
    {
        return $this->hasMany(Medicine::class);
    }
}