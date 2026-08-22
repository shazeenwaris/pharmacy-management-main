<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Prescription extends Model
{
    use LogsActivity;

    protected $fillable = [
        'prescription_number', 'customer_id', 'patient_name', 'doctor_name',
        'prescription_date', 'image_path', 'notes', 'status', 'created_by',
    ];

    protected $casts = [
        'prescription_date' => 'date',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['prescription_number', 'patient_name', 'status'])
            ->logOnlyDirty()
            ->useLogName('Prescription');
    }
}