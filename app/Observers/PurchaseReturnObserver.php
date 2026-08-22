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
}