<?php

namespace App\Notifications;

use App\Models\MedicineBatch;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MedicineExpiryNotification extends Notification
{
    use Queueable;

    public function __construct(
        public MedicineBatch $batch
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $daysLeft = now()
            ->startOfDay()
            ->diffInDays($this->batch->expiry_date, false);

        return [
            'type' => 'expiry',
            'alert' => 'expiry_30',
            'title' => 'Medicine Expiring Soon',
            'message' => $this->batch->medicine->name
                . ' (Batch ' . $this->batch->batch_number . ') '
                . 'will expire in ' . $daysLeft . ' days.',
            'medicine_id' => $this->batch->medicine_id,
            'batch_id' => $this->batch->id,
            'days_left' => $daysLeft,
        ];
    }
}