<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class Backup extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-circle-stack';

    protected static ?string $navigationLabel = 'Backup & Restore';

    protected static ?string $title = 'Database Backup & Restore';

    protected static string|\UnitEnum|null $navigationGroup = null;

    protected string $view = 'filament.pages.backup';

    public ?array $data = [];

    public array $backups = [];

    protected function backupsPath(): string
    {
        return storage_path('app/backups');
    }

    public function mount(): void
    {
        $this->form->fill();
        $this->loadBackups();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('restore_file')
                    ->label('Upload .sql backup file to restore')
                    ->acceptedFileTypes(['application/sql', 'text/plain', '.sql'])
                    ->disk('local')
                    ->directory('backup-uploads')
                    ->required(),
            ])
            ->statePath('data');
    }

    protected function loadBackups(): void
    {
        if (! File::isDirectory($this->backupsPath())) {
            File::makeDirectory($this->backupsPath(), 0755, true);
        }

        $files = collect(File::files($this->backupsPath()))
            ->filter(fn ($file) => str_ends_with($file->getFilename(), '.sql'))
            ->sortByDesc(fn ($file) => $file->getMTime())
            ->map(fn ($file) => [
                'path' => $file->getPathname(),
                'name' => $file->getFilename(),
                'size' => $this->formatBytes($file->getSize()),
                'date' => date('d M Y, h:i A', $file->getMTime()),
            ])
            ->values()
            ->toArray();

        $this->backups = $files;
    }

    protected function formatBytes(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        }

        return round($bytes / 1024, 2) . ' KB';
    }

    protected function getProcessEnv(): array
    {
        return array_merge(
            $_SERVER,
            [
                'SystemRoot' => getenv('SystemRoot') ?: 'C:\\Windows',
                'WINDIR' => getenv('WINDIR') ?: 'C:\\Windows',
            ]
        );
    }

    public function createBackup(): void
    {
        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');
        $dbHost = config('database.connections.mysql.host');
        $dbPort = config('database.connections.mysql.port', 3306);

        $mysqldumpPath = config('backup.mysqldump_path', 'mysqldump');

        if (! File::isDirectory($this->backupsPath())) {
            File::makeDirectory($this->backupsPath(), 0755, true);
        }

        $filename = 'backup_' . now()->format('Y_m_d_His') . '.sql';
        $fullPath = $this->backupsPath() . DIRECTORY_SEPARATOR . $filename;

        $command = [
            $mysqldumpPath,
            '--protocol=tcp',
            '-h' . $dbHost,
            '-P' . $dbPort,
            '-u' . $dbUser,
        ];

        if (! empty($dbPass)) {
            $command[] = '-p' . $dbPass;
        }

        $command[] = $dbName;

        $process = new Process($command, null, $this->getProcessEnv());
        $process->setTimeout(300);

        try {
            $process->mustRun();

            File::put($fullPath, $process->getOutput());

            Notification::make()
                ->title('Backup created successfully')
                ->success()
                ->send();

            $this->loadBackups();
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Backup failed')
                ->body('Make sure mysqldump path is configured correctly. Error: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function downloadBackup(string $path): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        return response()->download($path);
    }

    public function deleteBackup(string $path): void
    {
        File::delete($path);

        Notification::make()
            ->title('Backup deleted')
            ->success()
            ->send();

        $this->loadBackups();
    }

    public function restoreBackup(): void
    {
        $state = $this->form->getState();

        $uploadedFile = $state['restore_file'] ?? null;

        if (! $uploadedFile) {
            Notification::make()
                ->title('Please select a backup file first')
                ->warning()
                ->send();

            return;
        }

        $fullPath = \Illuminate\Support\Facades\Storage::disk('local')->path('backup-uploads/' . $uploadedFile);

        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');
        $dbHost = config('database.connections.mysql.host');
        $dbPort = config('database.connections.mysql.port', 3306);

        $mysqlPath = config('backup.mysql_path', 'mysql');

        $command = [
            $mysqlPath,
            '--protocol=tcp',
            '-h' . $dbHost,
            '-P' . $dbPort,
            '-u' . $dbUser,
        ];

        if (! empty($dbPass)) {
            $command[] = '-p' . $dbPass;
        }

        $command[] = $dbName;

        $process = new Process($command, null, $this->getProcessEnv());
        $process->setInput(File::get($fullPath));
        $process->setTimeout(300);

        try {
            $process->mustRun();

            Notification::make()
                ->title('Database restored successfully')
                ->success()
                ->send();

            $this->form->fill();
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Restore failed')
                ->body('Error: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}