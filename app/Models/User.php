<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles, LogsActivity;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'is_active',
        'avatar',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'is_active'])
            ->logOnlyDirty()
            ->useLogName('User');
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar ? Storage::url($this->avatar) : null;
    }

<<<<<<< HEAD
        public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->hasRole('Admin');
        }

        if ($panel->getId() === 'cashier') {
            return $this->hasAnyRole(['Cashier', 'Admin']);
        }

        if ($panel->getId() === 'pharmacist') {
            return $this->hasAnyRole(['Pharmacist', 'Cashier', 'Inventory Manager', 'Admin']);
        }

        return false;
    }
    // public function canAccessPanel(Panel $panel): bool
    // {
    //     if ($panel->getId() === 'admin') {
    //         return $this->hasRole('Admin');
    //     }

    //     if ($panel->getId() === 'pharmacist') {
    //         return $this->hasAnyRole(['Pharmacist', 'Cashier', 'Inventory Manager', 'Admin']);
    //     }

    //     return false;
    // }
    
=======
   public function canAccessPanel(Panel $panel): bool
{
    if (! $this->is_active) {
        return false;
    }

    return match ($panel->getId()) {
        'admin' => $this->hasRole('Admin'),

        'cashier' => $this->hasAnyRole([
            'Admin',
            'Cashier',
        ]),

        'pharmacist' => $this->hasAnyRole([
            'Admin',
            'Cashier',
            'Pharmacist',
            'Inventory Manager',
        ]),

        default => false,
    };
}
>>>>>>> 404f8ffa9cd0037be434329645d15733c8b2fee9
}