<?php

namespace App\Filament\Resources\MedicineBatches;

use App\Filament\Resources\MedicineBatches\Pages\ListMedicineBatches;
use App\Filament\Resources\MedicineBatches\Pages\CreateMedicineBatch;
use App\Filament\Resources\MedicineBatches\Pages\EditMedicineBatch;
use App\Models\MedicineBatch;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Repeater;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class MedicineBatchResource extends Resource
{
    protected static ?string $model = MedicineBatch::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static string|UnitEnum|null $navigationGroup = 'Medicines';

    protected static ?int $navigationSort = 6;

    protected static ?string $navigationLabel = 'Batches & Expiry';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasAnyPermission(['track batches', 'track expiry']) ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('track batches') ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->can('track batches') ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->can('track batches') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Batch Details')
                    ->description('Core identity of this batch and current quantity')
                    ->icon(Heroicon::OutlinedCalendarDays)
                    ->columnSpanFull()
                    ->columns(3)
                    ->schema([
                        Select::make('medicine_id')
                            ->label('Medicine')
                            ->relationship('medicine', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('batch_number')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('quantity')
                            ->numeric()
                            ->default(0)
                            ->required(),

                        DatePicker::make('manufacturing_date'),

                                                DatePicker::make('expiry_date')
                            ->required()
                            ->minDate(now()->addDay())
                            ->helperText('Must be a future date — today or past dates are not allowed.'),
                    ]),

                Section::make('Pricing')
                    ->description('Purchase and sale price for this batch')
                    ->icon(Heroicon::OutlinedBanknotes)
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('purchase_price')
                            ->numeric()
                            ->prefix(fn () => currency_symbol()),

                        TextInput::make('sale_price')
                            ->numeric()
                            ->prefix(fn () => currency_symbol()),
                    ]),

               Repeater::make('batchPackagings')
    ->relationship('batchPackagings')
    ->label('Sale Units')
    ->addActionLabel('Add Sale Unit')
    ->defaultItems(1)
    ->reorderable('sort_order')
    ->columns(3)
    ->schema([
        Select::make('unit_id')
            ->label('Unit')
            ->relationship('unit', 'name')
            ->searchable()
            ->preload()
            ->required(),

      TextInput::make('conversion_factor')
    ->label('Conversion Factor')
    ->numeric()
    ->minValue(0.0001)
    ->required()
    ->helperText('How many base units are consumed when 1 sale unit is sold.'
            ),

        Toggle::make('can_sell')
            ->label('Can Sell')
            ->default(true)
            ->onColor('success')
            ->offColor('danger'),
    ])
    ->itemLabel(
        fn (array $state): ?string =>
            $state['unit_id'] ?? null
                ? 'Sale Unit'
                : 'New Sale Unit'
    )
    ->columnSpanFull(),

                Section::make('Status & Notes')
                    ->icon(Heroicon::OutlinedCheckCircle)
                    ->columnSpanFull()
                    ->schema([
                        Toggle::make('status')
                            ->label('Active')
                            ->default(true)
                            ->onColor('success')
                            ->offColor('danger'),

                        Textarea::make('notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('medicine.name')
                    ->label('Medicine')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('batch_number')
                    ->label('Batch #')
                    ->searchable(),

                TextColumn::make('expiry_date')
                    ->date()
                    ->sortable()
                    ->color(fn ($record) => match ($record->expiry_status) {
                        'expired' => 'danger',
                        'expiring_soon' => 'warning',
                        default => 'success',
                    }),

                TextColumn::make('quantity')
                    ->sortable(),

                TextColumn::make('sale_price')
                    ->label('Sale Price')
                    ->formatStateUsing(fn ($state) => format_currency($state))
                    ->sortable(),

                TextColumn::make('supplier.supplier_name')
                    ->label('Supplier')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('expiry_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'expired' => 'danger',
                        'expiring_soon' => 'warning',
                        default => 'success',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'expired' => 'Expired',
                        'expiring_soon' => 'Expiring Soon',
                        default => 'Active',
                    }),
            ])
            ->filters([
                SelectFilter::make('medicine_id')
                    ->label('Medicine')
                    ->relationship('medicine', 'name')
                    ->searchable()
                    ->preload(),

                Filter::make('expiring_soon')
                    ->label('Expiring Soon (30 days)')
                    ->query(fn (Builder $query): Builder => $query
                        ->whereBetween('expiry_date', [now(), now()->addDays(30)])),

                Filter::make('expired')
                    ->label('Expired')
                    ->query(fn (Builder $query): Builder => $query
                        ->where('expiry_date', '<', now())),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('expiry_date', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMedicineBatches::route('/'),
            'create' => CreateMedicineBatch::route('/create'),
            'edit' => EditMedicineBatch::route('/{record}/edit'),
        ];
    }
}