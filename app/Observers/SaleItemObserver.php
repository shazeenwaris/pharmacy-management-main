<?php

namespace App\Observers;

use App\Models\SaleItem;

class SaleItemObserver
{
    public function created(SaleItem $saleItem): void
    {
        if ($saleItem->batch) {
            $newQuantity = max(0, $saleItem->batch->quantity - $saleItem->quantity);
            $saleItem->batch->update(['quantity' => $newQuantity]);
        }
    }
}