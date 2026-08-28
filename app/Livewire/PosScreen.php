<?php

namespace App\Livewire;

use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PosScreen extends Component
{
    public string $search = '';
    public array $cart = [];
    public string $paymentMethod = 'cash';
    public float $paidAmount = 0;
    public float $discount = 0;
    public float $tax = 0;

    public function searchResults()
    {
        if (strlen($this->search) < 2) {
            return [];
        }

        return Medicine::where('status', true)
            ->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('generic_name', 'like', "%{$this->search}%")
                    ->orWhere('barcode', $this->search)
                    ->orWhere('sku', 'like', "%{$this->search}%");
            })
            ->limit(10)
            ->get();
    }

    public function addToCart($medicineId)
    {
        $medicine = Medicine::find($medicineId);

        if ($medicine->prescription_required) {
            $this->dispatch('notify', message: '⚠ Prescription Required for ' . $medicine->name);
        }

        $batch = MedicineBatch::where('medicine_id', $medicineId)
            ->where('status', true)
            ->where('quantity', '>', 0)
            ->where('expiry_date', '>', now())
            ->orderBy('expiry_date', 'asc')
            ->first();

        if (! $batch) {
            $this->dispatch('notify', message: 'No available stock for this medicine.');
            return;
        }

        if (isset($this->cart[$batch->id])) {
            $this->cart[$batch->id]['quantity']++;
        } else {
            $this->cart[$batch->id] = [
                'medicine_id' => $medicine->id,
                'medicine_name' => $medicine->name,
                'batch_id' => $batch->id,
                'batch_number' => $batch->batch_number,
                'expiry_date' => $batch->expiry_date->format('d M Y'),
                'available_qty' => $batch->quantity,
                'quantity' => 1,
                'unit_price' => (float) $medicine->sale_price,
                'discount' => 0,
            ];
        }

        $this->search = '';
    }

    /**
     * Called when the cashier presses Enter in the search box — this is
     * how barcode scanners behave (they type digits then send an Enter
     * keystroke). PDF section 9 flow:
     *   Scan Barcode -> Find Medicine -> Find Available Batch (FEFO)
     *   -> Add to Cart -> Generate Invoice
     * No manual click should be needed for a genuine barcode scan.
     */
    public function scanBarcode()
    {
        if (trim($this->search) === '') {
            return;
        }

        $medicine = Medicine::where('status', true)
            ->where('barcode', $this->search)
            ->first();

        if (! $medicine) {
            $this->dispatch('notify', message: '⚠ No medicine found for barcode: ' . $this->search);
            $this->search = '';
            return;
        }

        $this->addToCart($medicine->id);

        // Tell the browser to refocus the search input so the cashier
        // can immediately scan the next item without touching the mouse.
        $this->dispatch('focus-search');
    }

    public function removeFromCart($batchId)
    {
        unset($this->cart[$batchId]);
    }

    public function updateQuantity($batchId, $qty)
    {
        if (isset($this->cart[$batchId])) {
            $this->cart[$batchId]['quantity'] = max(1, (int) $qty);
        }
    }

    public function getSubtotalProperty()
    {
        return collect($this->cart)->sum(fn ($item) => ($item['unit_price'] * $item['quantity']) - $item['discount']);
    }

    public function getGrandTotalProperty()
    {
        return $this->subtotal + $this->tax - $this->discount;
    }

    public function getChangeProperty()
    {
        return max(0, $this->paidAmount - $this->grandTotal);
    }

    public function completeSale()
    {
        if (empty($this->cart)) {
            $this->dispatch('notify', message: 'Cart is empty.');
            return;
        }

        $saleId = null;

        DB::transaction(function () use (&$saleId) {
            $sale = Sale::create([
                'invoice_number' => 'INV-' . now()->format('Ymd') . '-' . rand(1000, 9999),
                'subtotal' => $this->subtotal,
                'discount' => $this->discount,
                'tax' => $this->tax,
                'grand_total' => $this->grandTotal,
                'paid_amount' => $this->paidAmount,
                'change_amount' => $this->change,
                'payment_method' => $this->paymentMethod,
                'created_by' => auth()->id(),
            ]);

            foreach ($this->cart as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'medicine_id' => $item['medicine_id'],
                    'medicine_batch_id' => $item['batch_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount' => $item['discount'],
                    'total' => ($item['unit_price'] * $item['quantity']) - $item['discount'],
                ]);
            }

            $this->cart = [];
            $this->paidAmount = 0;
            $this->discount = 0;
            $this->tax = 0;

            $saleId = $sale->id;
        });

        $this->redirect(route('filament.admin.pages.sales.invoice.{record}', ['record' => $saleId]));
    }

    public function render()
    {
        return view('livewire.pos-screen', [
            'results' => $this->searchResults(),
        ]);
    }
}