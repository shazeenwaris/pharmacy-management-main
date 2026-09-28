<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppNotification extends Model
{
    protected $table = 'notifications';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Notification Data Helpers
    |--------------------------------------------------------------------------
    */

    public function getNotificationTypeAttribute(): string
    {
        return $this->data['type'] ?? 'general';
    }

    public function getTitleAttribute(): string
    {
        return $this->data['title'] ?? 'Notification';
    }

    public function getMessageAttribute(): string
    {
        return $this->data['message'] ?? '';
    }

    public function getLinkAttribute(): ?string
    {
        return $this->data['link'] ?? null;
    }

    /*
    |--------------------------------------------------------------------------
    | Read / Unread
    |--------------------------------------------------------------------------
    */

    public function markAsRead(): void
    {
        $this->update([
            'read_at' => now(),
        ]);
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }
}