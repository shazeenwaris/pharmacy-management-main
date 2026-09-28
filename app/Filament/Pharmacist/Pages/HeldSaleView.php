<?php

namespace App\Filament\Pharmacist\Pages;

use App\Models\HeldSale;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class HeldSaleView extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEye;

    protected static ?string $navigationLabel = 'Held Sale';

    protected static ?string $title = 'Held Sale Details';

    protected string $view = 'filament.pharmacist.pages.held-sale-view';

    protected static string $routePath = 'held-sales/{record}';

    public HeldSale $record;

    public function mount(HeldSale $record): void
    {
        $this->record = $record;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('resume')
                ->label('Resume Sale')
                ->icon(Heroicon::OutlinedPlay)
                ->color('success')
                ->url(fn () => '/pharmacist/sales?resume=' . $this->record->id),

            Action::make('discard')
                ->label('Discard')
                ->icon(Heroicon::OutlinedTrash)
                ->color('danger')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->delete();

                    Notification::make()
                        ->title('Held sale discarded')
                        ->success()
                        ->send();

                    $this->redirect('/pharmacist/held-sales');
                }),
        ];
    }
}

