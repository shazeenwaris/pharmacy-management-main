<?php

namespace App\Filament\Pharmacist\Pages;

use App\Models\HeldSale;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class HeldSales extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPause;

    protected static ?string $navigationLabel = 'Held Sales';

    protected static ?string $title = 'Held Sales';

    protected string $view = 'filament.pharmacist.pages.held-sales';


    public function table(Table $table): Table
    {
        return $table
            ->query(HeldSale::query())
            ->columns([
                TextColumn::make('reference')
                    ->label('Reference')
                    ->searchable(),
                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->default('Walk-in'),
                TextColumn::make('heldBy.name')
                    ->label('Held By'),
                TextColumn::make('created_at')
                    ->label('Held At')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('View')
                    ->icon(Heroicon::OutlinedEye)
                    ->color('info')
                    ->modalHeading(fn (HeldSale $record) => 'Held Sale — ' . $record->reference)
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close')
                    ->modalContent(function (HeldSale $record) {
                        $cart = collect($record->cart ?? []);

                        $rows = $cart->map(function ($item) {
                            $name = $item['medicine_name'] ?? $item['name'] ?? 'N/A';
                            $qty = $item['quantity'] ?? 0;
                            $price = (float) ($item['unit_price'] ?? $item['price'] ?? 0);
                            $lineTotal = $qty * $price;

                            return "<tr>
                                <td style='padding:8px; border-bottom:1px solid #eef2f3;'>{$name}</td>
                                <td style='padding:8px; border-bottom:1px solid #eef2f3; text-align:center;'>{$qty}</td>
                                <td style='padding:8px; border-bottom:1px solid #eef2f3; text-align:right;'>" . format_currency($price) . "</td>
                                <td style='padding:8px; border-bottom:1px solid #eef2f3; text-align:right; font-weight:600;'>" . format_currency($lineTotal) . "</td>
                            </tr>";
                        })->implode('');

                        $customerName = $record->customer->name ?? 'Walk-in';
                        $heldByName = $record->heldBy->name ?? '-';
                        $heldAt = $record->created_at->format('d M Y, h:i A');

                        return new \Illuminate\Support\HtmlString("
                            <div style='font-size:13px;'>
                                <div style='display:flex; justify-content:space-between; margin-bottom:12px; color:#405A68;'>
                                    <span><strong>Customer:</strong> {$customerName}</span>
                                    <span><strong>Held By:</strong> {$heldByName}</span>
                                    <span><strong>Held At:</strong> {$heldAt}</span>
                                </div>

                                <table style='width:100%; border-collapse:collapse;'>
                                    <thead>
                                        <tr style='text-align:left; border-bottom:2px solid #E2EEEE; font-size:11px; text-transform:uppercase; color:#718792;'>
                                            <th style='padding:8px;'>Medicine</th>
                                            <th style='padding:8px; text-align:center;'>Qty</th>
                                            <th style='padding:8px; text-align:right;'>Price</th>
                                            <th style='padding:8px; text-align:right;'>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>{$rows}</tbody>
                                </table>

                                <div style='margin-top:12px; text-align:right; font-size:12px; color:#718792;'>
                                    Discount: " . format_currency($record->discount) . " &nbsp;|&nbsp; Tax: " . format_currency($record->tax) . "
                                </div>

                                " . ($record->note ? "<div style='margin-top:10px; padding:8px; background:#F8FAFA; border-radius:6px; font-size:12px; color:#405A68;'><strong>Note:</strong> {$record->note}</div>" : "") . "
                            </div>
                        ");
                    }),
                       
                Action::make('resume')
                    ->label('Resume')
                    ->icon(Heroicon::OutlinedPlay)
                    ->color('success')
                    ->url(fn (HeldSale $record) => '/pharmacist/sales?resume=' . $record->id),

                Action::make('delete')
                    ->label('Discard')
                    ->icon(Heroicon::OutlinedTrash)
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (HeldSale $record) {
                        $record->delete();

                        Notification::make()
                            ->title('Held sale discarded')
                            ->success()
                            ->send();
                    }),
            ])
             
            ->defaultSort('created_at', 'desc');
    }
}