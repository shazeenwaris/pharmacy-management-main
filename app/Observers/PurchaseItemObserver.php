<?php

namespace App\Observers;

use App\Models\PurchaseItem;

class PurchaseItemObserver
{
    public function created(PurchaseItem $purchaseItem): void
    {
        // Batch creation removed — batches are managed manually
        // via the Batches & Expiry module only, not auto-created from Purchases.
    }
}