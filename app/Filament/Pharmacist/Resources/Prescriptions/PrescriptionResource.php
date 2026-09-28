<?php

namespace App\Filament\Pharmacist\Resources\Prescriptions;

use App\Filament\Pharmacist\Resources\Prescriptions\Pages\ListPrescriptions;
use App\Filament\Pharmacist\Resources\Prescriptions\Pages\CreatePrescription;
use App\Filament\Pharmacist\Resources\Prescriptions\Pages\EditPrescription;
use App\Models\Prescription;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PrescriptionResource extends Resource
{
    protected static ?string $model = Prescription::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $navigationLabel = 'Prescriptions';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Patient & Doctor Info')
                    ->description('Who the prescription belongs to and when it was written')
                    ->icon(Heroicon::OutlinedUser)
                    ->columnSpanFull()
                    ->columns(3)
                    ->schema([
                        TextInput::make('prescription_number')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->default(fn () => 'RX-' . now()->format('Ymd') . '-' . rand(1000, 9999)),

                        Select::make('customer_id')
                            ->label('Customer (optional)')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->preload(),

                        TextInput::make('patient_name')
                            ->required(),

                        TextInput::make('doctor_name'),

                        DatePicker::make('prescription_date')
                            ->required()
                            ->default(now()),

                        Select::make('status')
                            ->options([
                                'active' => 'Pending Verification',
                                'verified' => 'Verified',
                                'dispensed' => 'Dispensed',
                                'expired' => 'Expired',
                            ])
                            ->default('active')
                            ->required(),
                    ]),

                Section::make('Prescription Image')
                    ->description('Attach a photo or scan of the original prescription')
                    ->icon(Heroicon::OutlinedPhoto)
                    ->columnSpanFull()
                    ->schema([
                        FileUpload::make('image_path')
                            ->image()
                            ->directory('prescriptions')
                            ->label('Upload Prescription Image/File'),
                    ]),

                Section::make('Medicines')
                    ->description('Add each medicine, dosage, and quantity prescribed')
                    ->icon(Heroicon::OutlinedBeaker)
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('items')
                            ->relationship('items')
                            ->schema([
                                Select::make('medicine_id')
                                    ->label('Medicine')
                                    ->relationship('medicine', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                TextInput::make('dosage')
                                    ->placeholder('e.g. 1 tablet twice a day'),

                                TextInput::make('quantity')
                                    ->numeric()
                                    ->required(),
                            ])
                            ->columns(3)
                            ->columnSpanFull()
                            ->addActionLabel('Add Medicine'),
                    ]),

                Section::make('Notes')
                    ->icon(Heroicon::OutlinedPencilSquare)
                    ->columnSpanFull()
                    ->schema([
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
                TextColumn::make('prescription_number')
                    ->label('RX Number')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('patient_name')
                    ->searchable(),

                TextColumn::make('doctor_name')
                    ->label('Doctor')
                    ->searchable(),

                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('items_count')
                    ->counts('items')
                    ->label('Medicines')
                    ->badge()
                    ->color('info'),

                TextColumn::make('prescription_date')
                    ->date()
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Pending Verification',
                        'verified' => 'Verified',
                        'dispensed' => 'Dispensed',
                        'expired' => 'Expired',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'warning',
                        'verified' => 'info',
                        'dispensed' => 'success',
                        'expired' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Pending Verification',
                        'verified' => 'Verified',
                        'dispensed' => 'Dispensed',
                        'expired' => 'Expired',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
                ->recordActionsColumnLabel('Actions')
            ->defaultSort('prescription_date', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPrescriptions::route('/'),
            'create' => CreatePrescription::route('/create'),
            'edit' => EditPrescription::route('/{record}/edit'),
        ];
    }
}