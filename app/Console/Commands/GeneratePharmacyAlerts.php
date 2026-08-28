<?php

namespace App\Console\Commands;

use App\Models\AppNotification;
use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\Supplier;
use Illuminate\Console\Command;

class GeneratePharmacyAlerts extends Command
{
    protected $signature = 'pharmacy:generate-alerts';
    protected $description = 'Generate low stock, expiry, out of stock and payment due notifications';

    public function handle(): void
    {
        $this->generateLowStockAlerts();
        $this->generateOutOfStockAlerts();
        $this->generateExpiryAlerts();
        $this->generatePaymentDueAlerts();

        $this->info('Pharmacy alerts generated successfully.');
    }

    protected function generateLowStockAlerts(): void
    {
        $medicines = Medicine::where('status', true)->get();

        foreach ($medicines as $medicine) {
            $currentStock = MedicineBatch::where('medicine_id', $medicine->id)
                ->where('status', true)
                ->sum('quantity');

            if ($currentStock > 0 && $currentStock <= $medicine->minimum_stock_level) {
                $this->createIfNotExists(
                    type: 'low_stock',
                    title: 'Low Stock Alert',
                    message: "{$medicine->name} is below minimum stock level. Current: {$currentStock}, Minimum: {$medicine->minimum_stock_level}.",
                    level: 'warning',
                    link: "/admin/medicines/{$medicine->id}/edit"
                );
            }
        }
    }

    protected function generateOutOfStockAlerts(): void
    {
        $medicines = Medicine::where('status', true)->get();

        foreach ($medicines as $medicine) {
            $currentStock = MedicineBatch::where('medicine_id', $medicine->id)
                ->where('status', true)
                ->sum('quantity');

            if ($currentStock <= 0) {
                $this->createIfNotExists(
                    type: 'out_of_stock',
                    title: 'Out of Stock',
                    message: "{$medicine->name} is out of stock.",
                    level: 'danger',
                    link: "/admin/medicines/{$medicine->id}/edit"
                );
            }
        }
    }

    protected function generateExpiryAlerts(): void
    {
        $batches = MedicineBatch::with('medicine')
            ->where('status', true)
            ->where('quantity', '>', 0)
            ->whereBetween('expiry_date', [now(), now()->addDays(30)])
            ->get();

        foreach ($batches as $batch) {
            $daysLeft = now()->diffInDays($batch->expiry_date, false);

            $this->createIfNotExists(
                type: 'expiry',
                title: 'Expiry Alert',
                message: "{$batch->medicine->name} (Batch: {$batch->batch_number}) expires in {$daysLeft} days.",
                level: $daysLeft <= 7 ? 'danger' : 'warning',
                link: "/admin/medicine-batches/{$batch->id}/edit"
            );
        }
    }

    protected function generatePaymentDueAlerts(): void
    {
        $suppliers = Supplier::where('status', true)->get();

        foreach ($suppliers as $supplier) {
            $balance = $supplier->payable_balance;

            if ($balance > 0) {
                $this->createIfNotExists(
                    type: 'payment_due',
                    title: 'Payment Due',
                    message: "Supplier {$supplier->supplier_name} has an outstanding balance of " . format_currency($balance) . ".",
                    level: 'warning',
                    link: "/admin/suppliers/{$supplier->id}/edit"
                );
            }
        }
    }

    protected function createIfNotExists(string $type, string $title, string $message, string $level, ?string $link = null): void
    {
        $exists = AppNotification::where('type', $type)
            ->where('title', $title)
            ->where('message', $message)
            ->whereNull('read_at')
            ->exists();

        if (! $exists) {
            AppNotification::create([
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'level' => $level,
                'link' => $link,
            ]);
        }
    }
}