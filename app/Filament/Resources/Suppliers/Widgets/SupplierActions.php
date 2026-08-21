<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Suppliers\SupplierResource;
use Filament\Widgets\Widget;

class SupplierActions extends Widget
{
    protected string $view = 'filament.widgets.supplier-actions';

    protected int|string|array $columnSpan = 'full';

    public function getCreateUrl(): string
    {
        return SupplierResource::getUrl('create');
    }

    protected function getExtraAttributes(): array
    {
        return [
            'class' => 'supplier-actions-widget !w-full !max-w-none',
        ];
    }
}