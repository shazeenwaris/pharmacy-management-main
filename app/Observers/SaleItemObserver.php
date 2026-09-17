<?php

namespace App\Observers;

use App\Models\MedicineBatch;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class SaleItemObserver
{
    /**
     * Handle SaleItem "created" event.
     *
     * Sale quantity is stored in selected sale unit.
     *
     * Example:
     *
     * 2 Strips × 10 Tablets
     * = 20 Tablets stock deduction
     */
    public function created(SaleItem $saleItem): void
    {
        if (! $saleItem->medicine_batch_id) {
            throw new RuntimeException(
                'Sale item has no medicine batch selected.'
            );
        }

        DB::transaction(function () use ($saleItem) {

            /*
            |--------------------------------------------------------------------------
            | LOCK EXACT BATCH
            |--------------------------------------------------------------------------
            */

            $batch = MedicineBatch::query()
                ->whereKey(
                    $saleItem->medicine_batch_id
                )
                ->lockForUpdate()
                ->first();

            if (! $batch) {
                throw new RuntimeException(
                    'Medicine batch not found for sale item.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | MEDICINE MATCH
            |--------------------------------------------------------------------------
            */

            if (
                (int) $batch->medicine_id !==
                (int) $saleItem->medicine_id
            ) {
                throw new RuntimeException(
                    'Selected medicine batch does not belong to this medicine.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | SALE QUANTITY
            |--------------------------------------------------------------------------
            */

            $quantity = (float) (
                $saleItem->quantity ?? 0
            );

            if ($quantity <= 0) {
                throw new RuntimeException(
                    'Sale quantity must be greater than zero.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | CONVERSION FACTOR
            |--------------------------------------------------------------------------
            */

            $conversionFactor = (float) (
                $saleItem->conversion_factor ?? 1
            );

            if ($conversionFactor <= 0) {
                throw new RuntimeException(
                    'Sale item has an invalid conversion factor.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | ACTUAL STOCK QUANTITY
            |--------------------------------------------------------------------------
            |
            | Example:
            |
            | 2 Strip × 10 = 20 Tablets
            |
            */

            $stockQuantity =
                $quantity *
                $conversionFactor;

            /*
            |--------------------------------------------------------------------------
            | STOCK CHECK
            |--------------------------------------------------------------------------
            */

            $availableStock = (float) (
                $batch->quantity ?? 0
            );

            if (
                $availableStock <
                $stockQuantity
            ) {
                throw new RuntimeException(
                    'Insufficient stock for ' .
                    (
                        $saleItem->medicine?->name
                        ?? 'this medicine'
                    ) .
                    '. Available base stock: ' .
                    $availableStock .
                    ', requested: ' .
                    $stockQuantity
                );
            }

            /*
            |--------------------------------------------------------------------------
            | STOCK OUT
            |--------------------------------------------------------------------------
            |
            | ONLY HERE STOCK IS DECREMENTED.
            |
            | PosScreen does NOT decrement stock.
            |
            */

            $batch->decrement(
                'quantity',
                $stockQuantity
            );
        });
    }
}