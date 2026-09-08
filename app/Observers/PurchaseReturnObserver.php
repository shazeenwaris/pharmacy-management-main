<?php

namespace App\Observers;

use App\Models\PurchaseReturn;

class PurchaseReturnObserver
{
    public function created(PurchaseReturn $purchaseReturn): void
    {
        if ($purchaseReturn->batch) {
            $newQuantity = max(0, $purchaseReturn->batch->quantity - $purchaseReturn->quantity);
            $purchaseReturn->batch->update(['quantity' => $newQuantity]);
        }
    }

    public function deleted(PurchaseReturn $purchaseReturn): void
    {
        // Reverse the stock decrease if a return record is deleted,
        // so inventory stays accurate.
        if ($purchaseReturn->batch) {
            $purchaseReturn->batch->update([
                'quantity' => $purchaseReturn->batch->quantity + $purchaseReturn->quantity,
            ]);
        }
    }
}