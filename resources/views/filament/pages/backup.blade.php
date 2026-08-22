<x-filament-panels::page>

    {{-- ===== CREATE BACKUP ===== --}}
    <x-filament::section>
        <x-slot name="heading">
            Create New Backup
        </x-slot>

        <x-slot name="description">
            Generates a full .sql dump of the database and stores it on the server.
        </x-slot>

        <x-filament::button wire:click="createBackup" icon="heroicon-o-circle-stack">
            Create Backup Now
        </x-filament::button>
    </x-filament::section>

    {{-- ===== EXISTING BACKUPS ===== --}}
    <x-filament::section class="mt-6">
        <x-slot name="heading">
            Existing Backups
        </x-slot>

        @if (empty($backups))
            <p class="text-sm text-gray-500 dark:text-gray-400">
                No backups yet. Click "Create Backup Now" above to generate one.
            </p>
        @else
            <div class="space-y-2">
                @foreach ($backups as $backup)
                    <div class="flex items-center justify-between rounded-lg border border-gray-200 dark:border-gray-700 p-3">
                        <div>
                            <p class="font-medium text-sm">{{ $backup['name'] }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $backup['date'] }} &middot; {{ $backup['size'] }}
                            </p>
                        </div>

                        <div class="flex items-center gap-2">
                            <a
                                href="{{ route('backup.download', ['path' => base64_encode($backup['path'])]) }}"
                                class="fi-btn fi-btn-size-sm fi-color-primary inline-flex items-center gap-1 rounded-lg px-3 py-1.5 text-sm font-medium text-primary-600 hover:underline"
                            >
                                Download
                            </a>

                            <x-filament::button
                                size="sm"
                                color="danger"
                                wire:click="deleteBackup('{{ addslashes($backup['path']) }}')"
                                wire:confirm="Are you sure you want to delete this backup?"
                            >
                                Delete
                            </x-filament::button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </x-filament::section>

    {{-- ===== RESTORE FROM BACKUP ===== --}}
    <x-filament::section class="mt-6">
        <x-slot name="heading">
            Restore Database
        </x-slot>

        <x-slot name="description">
            ⚠ This will overwrite your current database with the uploaded backup. This action cannot be undone.
        </x-slot>

        <form wire:submit="restoreBackup">
            {{ $this->form }}

            <div class="mt-4">
                <x-filament::button
                    type="submit"
                    color="danger"
                    wire:confirm="This will overwrite your current database. Are you absolutely sure?"
                >
                    Restore From File
                </x-filament::button>
            </div>
        </form>
    </x-filament::section>

</x-filament-panels::page>