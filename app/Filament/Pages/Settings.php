<?php

namespace App\Filament\Pages;

use App\Models\PharmacySetting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use BackedEnum;
use UnitEnum;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Settings';

    protected static ?string $title = 'Pharmacy Settings';

    // Keep it out of the main sidebar group if you already group it manually
    protected static string|UnitEnum|null $navigationGroup = null;

    protected static ?int $navigationSort = 100;

    protected string $view = 'filament.pages.settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = PharmacySetting::current();

        $this->form->fill($settings->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Pharmacy Info')
                    ->columns(2)
                    ->schema([
                        TextInput::make('pharmacy_name')
                            ->label('Pharmacy Name')
                            ->required()
                            ->maxLength(255),

                        FileUpload::make('logo')
                            ->label('Logo')
                            ->image()
                            ->directory('pharmacy-logo')
                            ->columnSpan(1),

                        Textarea::make('address')
                            ->label('Address')
                            ->columnSpanFull(),

                        TextInput::make('phone')
                            ->label('Phone')
                            ->tel(),

                        TextInput::make('email')
                            ->label('Email')
                            ->email(),
                    ]),

                Section::make('Invoice Settings')
                    ->columns(2)
                    ->schema([
                        TextInput::make('invoice_prefix')
                            ->label('Invoice Prefix')
                            ->default('INV-'),

                        TextInput::make('invoice_start_number')
                            ->label('Invoice Start Number')
                            ->numeric()
                            ->default(1),

                        Textarea::make('invoice_footer_note')
                            ->label('Invoice Footer Note')
                            ->columnSpanFull(),
                    ]),

                Section::make('Tax & Currency')
                    ->columns(3)
                    ->schema([
                        TextInput::make('default_tax_percentage')
                            ->label('Default Tax %')
                            ->numeric()
                            ->suffix('%')
                            ->default(0),

                        TextInput::make('currency_code')
                            ->label('Currency Code')
                            ->default('PKR'),

                        TextInput::make('currency_symbol')
                            ->label('Currency Symbol')
                            ->default('Rs.'),
                    ]),

                Section::make('Stock & Expiry Alerts')
                    ->columns(2)
                    ->schema([
                        TextInput::make('low_stock_threshold')
                            ->label('Low Stock Threshold')
                            ->numeric()
                            ->default(10)
                            ->helperText('Medicines below this quantity are marked as Low Stock.'),

                        TextInput::make('expiry_warning_days')
                            ->label('Expiry Warning (days)')
                            ->numeric()
                            ->default(30)
                            ->helperText('Medicines expiring within this many days trigger an alert.'),
                    ]),

                Section::make('Payment Methods')
                    ->schema([
                        CheckboxList::make('payment_methods')
                            ->label('Enabled Payment Methods')
                            ->options([
                                'cash' => 'Cash',
                                'card' => 'Card',
                                'bank_transfer' => 'Bank Transfer',
                                'other' => 'Other',
                            ])
                            ->columns(4),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
{
    $settings = PharmacySetting::current();
    $settings->update($this->form->getState());

    \Illuminate\Support\Facades\Cache::forget('pharmacy_currency_symbol');

    Notification::make()
        ->title('Settings saved successfully')
        ->success()
        ->send();
}
}