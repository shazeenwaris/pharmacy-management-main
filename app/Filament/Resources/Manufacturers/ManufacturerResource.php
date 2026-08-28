<?php

namespace App\Filament\Resources\Manufacturers;

use App\Filament\Resources\Manufacturers\Pages\ListManufacturers;
use App\Filament\Resources\Manufacturers\Pages\CreateManufacturer;
use App\Filament\Resources\Manufacturers\Pages\EditManufacturer;
use App\Models\Manufacturer;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class ManufacturerResource extends Resource
{
    protected static ?string $model = Manufacturer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static string|UnitEnum|null $navigationGroup = 'Medicines';

    protected static ?string $navigationLabel = 'Manufacturers';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Manufacturer Details')
                    ->description('Basic identity and contact info')
                    ->icon(Heroicon::OutlinedBuildingOffice2)
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        TextInput::make('company_code')
                            ->maxLength(50),
                        TextInput::make('contact_person')
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->tel(),
                        TextInput::make('email')
                            ->email(),
                        TextInput::make('website')
                            ->url(),
                        TextInput::make('tax_ntn_number')
                            ->label('Tax / NTN Number'),
                    ]),

                Section::make('Address')
                    ->description('Manufacturer location details')
                    ->icon(Heroicon::OutlinedMapPin)
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('address')
                            ->columnSpanFull(),
                        TextInput::make('city'),
                        TextInput::make('country'),
                    ]),

                Section::make('Status')
                    ->icon(Heroicon::OutlinedCheckCircle)
                    ->columnSpanFull()
                    ->schema([
                        Toggle::make('status')
                            ->label('Active')
                            ->default(true)
                            ->onColor('success')
                            ->offColor('danger'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),
                TextColumn::make('city')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('phone'),
                TextColumn::make('email')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('medicines_count')
                    ->counts('medicines')
                    ->label('Medicines')
                    ->badge()
                    ->color('info'),
                IconColumn::make('status')
                    ->label('Active')
                    ->boolean(),
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
            ->defaultSort('name');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListManufacturers::route('/'),
            'create' => CreateManufacturer::route('/create'),
            'edit' => EditManufacturer::route('/{record}/edit'),
        ];
    }
}