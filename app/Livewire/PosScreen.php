    {
        if (empty($this->cart)) {
            $this->dispatch('notify', message: 'Cart is empty.');
            return;
        }

        $saleId = null;

        DB::transaction(function () use (&$saleId) {
            $sale = Sale::create([
                'invoice_number' => 'INV-' . now()->format('Ymd') . '-' . rand(1000, 9999),
                'customer_id' => $this->customerId ?: null,
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
            $this->customerId = null;

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