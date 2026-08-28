<?php

namespace App\Filament\Resources\SaleReturns\Schemas;

use App\Models\Sale;
use App\Models\SaleItem;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class SaleReturnForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Return Details')
                    ->description('Select the original invoice and medicine being returned')
                    ->icon(Heroicon::OutlinedDocumentText)
                    ->columnSpanFull()
                    ->columns(3)
                    ->schema([
                        Select::make('sale_id')
                            ->label('Original Invoice')
                            ->options(fn () => Sale::query()->pluck('invoice_number', 'id'))
                            ->searchable()
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('sale_item_id', null)),

                        Select::make('sale_item_id')
                            ->label('Medicine (from invoice)')
                            ->options(function (Get $get) {
                                if (! $get('sale_id')) {
                                    return [];
                                }

                                return SaleItem::where('sale_id', $get('sale_id'))
                                    ->with('medicine')
                                    ->get()
                                    ->mapWithKeys(fn ($item) => [
                                        $item->id => $item->medicine->name . ' (Qty sold: ' . $item->quantity . ')',
                                    ]);
                            })
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Set $set, Get $get) {
                                $item = SaleItem::find($get('sale_item_id'));
                                if ($item) {
                                    $set('medicine_id', $item->medicine_id);
                                    $set('medicine_batch_id', $item->medicine_batch_id);
                                    $set('quantity', 1);
                                    $set('refund_amount', round($item->unit_price * 1, 2));
                                }
                            }),

                        DatePicker::make('return_date')
                            ->label('Return Date')
                            ->default(now())
                            ->required(),
                    ]),

                Section::make('Quantity & Refund')
                    ->description('How much is being returned and the refund amount')
                    ->icon(Heroicon::OutlinedCalculator)
                    ->columnSpanFull()
                    ->columns(3)
                    ->schema([
                        TextInput::make('quantity')
                            ->label('Quantity')
                            ->numeric()
                            ->minValue(1)
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, Get $get) {
                                $item = SaleItem::find($get('sale_item_id'));
                                if ($item) {
                                    $set('refund_amount', round($item->unit_price * (int) $get('quantity'), 2));
                                }
                            }),

                        TextInput::make('refund_amount')
                            ->label('Refund Amount')
                            ->numeric()
                            ->prefix(fn () => currency_symbol())
                            ->required()
                            ->readOnly(),

                        Select::make('reason')
                            ->label('Reason')
                            ->options([
                                'defective' => 'Defective / Damaged',
                                'wrong_item' => 'Wrong Item Sold',
                                'customer_request' => 'Customer Changed Mind',
                                'expired' => 'Expired',
                                'other' => 'Other',
                            ])
                            ->required()
                            ->native(false),

                        Hidden::make('medicine_id'),
                        Hidden::make('medicine_batch_id'),
                    ]),
            ]);
    }
}