<?php

namespace App\Filament\Pages;

use App\Models\Sale;
use Filament\Pages\Page;

class ViewInvoice extends Page
{
    protected string $view = 'filament.pages.view-invoice';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'sales/invoice/{record}';

    public ?Sale $sale = null;

    public function mount(int $record): void
    {
        $this->sale = Sale::with('items.medicine')->findOrFail($record);
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->can('create sales') ?? false;
    }

    public function getTitle(): string
    {
        return 'Invoice - ' . $this->sale->invoice_number;
    }
}