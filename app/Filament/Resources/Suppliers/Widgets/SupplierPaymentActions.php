<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\SupplierPayments\SupplierPaymentResource;
use Filament\Widgets\Widget;

class SupplierPaymentActions extends Widget
{
    protected string $view = 'filament.widgets.supplier-payment-actions';

    protected int|string|array $columnSpan = 'full';

    public function getCreateUrl(): string
    {
        return SupplierPaymentResource::getUrl('create');
    }

    protected function getExtraAttributes(): array
    {
        return [
            'class' => 'supplier-payment-actions-widget !w-full !max-w-none',
        ];
    }
}