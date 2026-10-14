<?php

namespace App\Filament\Pages;

use App\Models\User;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use UnitEnum;

class EditProfile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-circle';

    protected static string|UnitEnum|null $navigationGroup = null;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'My Profile';

    protected static ?string $slug = 'my-profile';

    protected string $view = 'filament.pages.edit-profile';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(Auth::user()->only(['name', 'email', 'avatar']));
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Profile Photo')
                    ->description('Upload a photo to personalize your account')
                    ->icon(Heroicon::OutlinedCamera)
                    ->columnSpanFull()
                    ->schema([
                        FileUpload::make('avatar')
                            ->label('Avatar')
                            ->image()
                            ->disk('public')
                            ->directory('avatars')
                            ->avatar(),
                    ]),

                Section::make('Account Information')
                    ->description('Update your name and email address')
                    ->icon(Heroicon::OutlinedIdentification)
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(table: 'users', ignorable: fn () => Auth::user()),
                    ]),

                Section::make('Change Password')
                    ->description('Leave blank to keep your current password')
                    ->icon(Heroicon::OutlinedLockClosed)
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->minLength(8)
                            ->dehydrated(fn ($state) => filled($state)),

                        TextInput::make('password_confirmation')
                            ->password()
                            ->revealable()
                            ->same('password')
                            ->dehydrated(false),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        Auth::user()->update($data);

        Notification::make()
            ->title('Profile updated successfully')
            ->success()
            ->send();

        $this->redirect('/' . self::currentPanelId());
    }

    /**
     * Builds the "My Profile" URL for whichever panel is currently active
     * (admin, cashier, pharmacist, ...), instead of always pointing at admin.
     */
    public static function getUrl(array $parameters = [], bool $isAbsolute = true, ?string $panel = null, ?Model $tenant = null, bool $shouldGuessMissingParameters = false, ?string $configuration = null): string
    {
        $panel ??= self::currentPanelId();

        return route("filament.{$panel}.pages.my-profile");
    }

    /**
     * The id of the panel currently being browsed (e.g. "admin", "cashier",
     * "pharmacist"), falling back to "admin" only if none can be detected.
     */
    protected static function currentPanelId(): string
    {
        return Filament::getCurrentPanel()?->getId() ?? 'admin';
    }
}