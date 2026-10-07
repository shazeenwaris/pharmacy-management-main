<?php

namespace App\Console\Commands;

use App\Models\AppNotification;
use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class GenerateStockAlerts extends Command
{
    protected $signature = 'alerts:generate';
    protected $description = 'Generate low stock, out of stock and expiry notifications';

    private const EXPIRY_DAYS     = 30;
    private const DEFAULT_REORDER = 10;

    public function handle(): int
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->warn('No users found.');
            return self::SUCCESS;
        }

        $this->stockAlerts($users);
        $this->expiryAlerts($users);

        $this->info('Alerts generated.');
        return self::SUCCESS;
    }

    /** Finds the low-stock level column in the medicines table */
    private function reorderColumn(): ?string
    {
        foreach (['reorder_level', 'min_stock', 'minimum_stock', 'alert_quantity', 'low_stock_alert', 'reorder_point', 'min_quantity'] as $col) {
            if (Schema::hasColumn((new Medicine)->getTable(), $col)) {
                return $col;
            }
        }
        return null;
    }

    private function stockAlerts($users): void
    {
        $totals = MedicineBatch::query()
            ->where('status', true)
            ->selectRaw('medicine_id, SUM(quantity) as total')
            ->groupBy('medicine_id')
            ->pluck('total', 'medicine_id');

        $column = $this->reorderColumn();

        Medicine::all()->each(function ($medicine) use ($totals, $users, $column) {
            $qty   = (int) ($totals[$medicine->id] ?? 0);
            $level = (int) (($column ? $medicine->{$column} : null) ?: self::DEFAULT_REORDER);

            if ($qty > $level) {
                return;
            }

            $out = $qty <= 0;

            $this->send(
                $users,
                $out ? 'out_of_stock' : 'low_stock',
                ($out ? 'Out of Stock: ' : 'Low Stock: ') . $medicine->name,
                $out
                    ? "{$medicine->name} is out of stock."
                    : "Only {$qty} units left (reorder level: {$level}).",
                'stock:' . $medicine->id . ':' . ($out ? 'out' : 'low')
            );
        });
    }

    private function expiryAlerts($users): void
    {
        $today = now()->startOfDay();

        MedicineBatch::query()
            ->with('medicine')
            ->where('status', true)
            ->where('quantity', '>', 0)
            ->whereNotNull('expiry_date')
            ->whereDate('expiry_date', '<=', $today->copy()->addDays(self::EXPIRY_DAYS))
            ->get()
            ->each(function ($batch) use ($users, $today) {
                $name    = $batch->medicine->name ?? 'Medicine';
                $expiry  = $batch->expiry_date->copy()->startOfDay();
                $expired = $expiry->lt($today);
                $days    = (int) $today->diffInDays($expiry);

                if ($expired) {
                    $when = 'expired on ' . $expiry->format('d M Y') . '.';
                } elseif ($days === 0) {
                    $when = 'expires today (' . $expiry->format('d M Y') . ').';
                } elseif ($days === 1) {
                    $when = 'expires tomorrow (' . $expiry->format('d M Y') . ').';
                } else {
                    $when = "expires in {$days} days (" . $expiry->format('d M Y') . ').';
                }

                $this->send(
                    $users,
                    'expiry',
                    ($expired ? 'Expired: ' : 'Expiring Soon: ') . $name,
                    "Batch {$batch->batch_number} {$when} Units: {$batch->quantity}",
                    ($expired ? 'expired:' : 'expiring:') . $batch->id
                );
            });
    }

    private function send($users, string $type, string $title, string $message, string $key): void
    {
        foreach ($users as $user) {
            // Skip if the same alert was already created in the last 24 hours
            $exists = AppNotification::query()
                ->where('notifiable_type', User::class)
                ->where('notifiable_id', $user->id)
                ->where('data->alert_key', $key)
                ->where('created_at', '>=', now()->subDay())
                ->exists();

            if ($exists) {
                continue;
            }

            AppNotification::create([
                'id'              => (string) Str::uuid(),
                'type'            => 'App\\Notifications\\StockAlertNotification',
                'notifiable_type' => User::class,
                'notifiable_id'   => $user->id,
                'data'            => [
                    'type'      => $type,
                    'title'     => $title,
                    'message'   => $message,
                    'alert_key' => $key,
                ],
            ]);
        }
    }
}