<?php

namespace App\Filament\Pharmacist\Pages;

use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class MyProfile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon =
        Heroicon::OutlinedUserCircle;

    protected static ?string $navigationLabel = 'My Profile';

    protected static ?string $title = 'My Profile';

    protected static ?string $slug = 'my-profile';

    protected string $view = 'filament.pages.my-profile';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(
            auth()->user()->only([
                'name',
                'email',
                'phone',
                'avatar',
            ])
        );
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                FileUpload::make('avatar')
                    ->label('Profile Photo')
                    ->image()
                    ->imageEditor()
                    ->avatar()
                    ->directory('profile-images')
                    ->disk('public')
                    ->visibility('public')
                    ->maxSize(2048)
                    ->acceptedFileTypes([
                        'image/jpeg',
                        'image/png',
                        'image/webp',
                    ])
                    ->helperText('Upload JPG, PNG or WEBP image. Maximum size: 2MB.'),

                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required()
                    ->unique(
                        table: 'users',
                        ignoreRecord: true,
                        ignorable: auth()->user()
                    ),

                TextInput::make('phone')
                    ->tel(),
            ])
            ->statePath('data');
    }

    // public function save(): void
    // {
    //     $data = $this->form->getState();

    //     auth()->user()->update($data);

    //     Notification::make()
    //         ->title('Profile updated successfully')
    //         ->success()
    //         ->persistent()
    //         ->send();
    // }
 public function save(): void
{
    $data = $this->form->getState();

    auth()->user()->update($data);

    Notification::make()
        ->title('Profile updated successfully')
        ->success()
        ->duration(1500)
        ->send();

    $this->redirect('/pharmacist');
}
}