<?php

namespace App\Filament\Resources\DosageForms;

use App\Filament\Resources\DosageForms\Pages\ManageDosageForms;
use App\Models\DosageForm;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class DosageFormResource extends Resource
{
    protected static ?string $model = DosageForm::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBeaker;

    protected static string|UnitEnum|null $navigationGroup = 'Medicines';

    protected static ?string $navigationLabel = 'Dosage Forms';

    protected static ?int $navigationSort = 5;

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('manage medicines') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('manage medicines') ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->can('manage medicines') ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->can('manage medicines') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Dosage Form Details')
                    ->description('e.g. Tablet, Syrup, Injection, Cream, Drops')
                    ->icon(Heroicon::OutlinedBeaker)
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Toggle::make('status')
                            ->label('Active')
                            ->default(true)
                            ->onColor('success')
                            ->offColor('danger'),

                        Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
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

                TextColumn::make('medicines_count')
                    ->counts('medicines')
                    ->label('Medicines')
                    ->badge()
                    ->color('info'),

                IconColumn::make('status')
                    ->label('Active')
                    ->boolean(),
            ])
            ->filters([
                TernaryFilter::make('status')
                    ->label('Status')
                    ->placeholder('All dosage forms')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->recordActionsColumnLabel('Actions')
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
            'index' => ManageDosageForms::route('/'),
        ];
    }
}