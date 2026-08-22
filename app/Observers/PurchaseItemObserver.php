<?php

namespace App\Observers;

use App\Models\PurchaseItem;
use App\Models\MedicineBatch;

class PurchaseItemObserver
{
    public function created(PurchaseItem $purchaseItem): void
    {
        MedicineBatch::create([
            'medicine_id' => $purchaseItem->medicine_id,
            'batch_number' => $purchaseItem->batch_number,
            'manufacturing_date' => $purchaseItem->manufacturing_date,
            'expiry_date' => $purchaseItem->expiry_date,
            'quantity' => $purchaseItem->quantity,
            'purchase_price' => $purchaseItem->purchase_price,
            'sale_price' => $purchaseItem->sale_price,
            'supplier_name' => $purchaseItem->purchase->supplier->supplier_name ?? null,
            'purchase_reference' => $purchaseItem->purchase->invoice_number,
            'status' => true,
        ]);
    }
}