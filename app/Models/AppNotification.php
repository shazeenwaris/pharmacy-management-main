<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppNotification extends Model
{
    protected $fillable = ['type', 'title', 'message', 'level', 'link', 'read_at'];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function markAsRead(): void
    {
        $this->update(['read_at' => now()]);
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }
}