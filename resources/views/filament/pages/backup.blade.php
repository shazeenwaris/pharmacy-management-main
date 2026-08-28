<x-filament-panels::page>

    {{-- ===== CREATE BACKUP ===== --}}
    <x-filament::section icon="heroicon-o-circle-stack" icon-color="primary">
        <x-slot name="heading">
            Create New Backup
        </x-slot>

        <x-slot name="description">
            Generates a full .sql dump of the database and stores it securely on the server.
        </x-slot>

        <div class="flex items-center justify-between gap-4 rounded-xl border border-gray-200 bg-gray-50 p-4 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary-50 text-primary-600 dark:bg-primary-500/10 dark:text-primary-400">
                    <x-filament::icon icon="heroicon-o-server-stack" class="h-5 w-5" />
                </span>
                <div>
                    <p class="text-sm font-medium text-gray-950 dark:text-white">Manual database snapshot</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Recommended before major changes or updates</p>
                </div>
            </div>

            <x-filament::button wire:click="createBackup" icon="heroicon-o-circle-stack" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="createBackup">Create Backup Now</span>
                <span wire:loading wire:target="createBackup">Creating&hellip;</span>
            </x-filament::button>
        </div>
    </x-filament::section>

       {{-- ===== EXISTING BACKUPS ===== --}}
    <x-filament::section class="mt-6" icon="heroicon-o-archive-box" icon-color="info">
        <x-slot name="heading">
            Existing Backups
        </x-slot>

        <x-slot name="description">
            {{ count($backups ?? []) }} backup{{ count($backups ?? []) === 1 ? '' : 's' }} stored on this server
        </x-slot>

        @if (empty($backups))
            <div class="flex flex-col items-center justify-center gap-3 rounded-xl border border-dashed border-gray-300 py-10 text-center dark:border-white/10">
                <span class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400 dark:bg-white/5 dark:text-gray-500">
                    <x-filament::icon icon="heroicon-o-inbox" class="h-6 w-6" />
                </span>
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">No backups yet</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">Click "Create Backup Now" above to generate your first one.</p>
            </div>
        @else
            <div class="space-y-2">
                @foreach ($backups as $backup)
                    <div class="flex items-center justify-between rounded-xl border border-gray-200 p-4 transition-colors hover:bg-gray-50 dark:border-white/10 dark:hover:bg-white/5">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-success-50 text-success-600 dark:bg-success-500/10 dark:text-success-400">
                                <x-filament::icon icon="heroicon-o-document-check" class="h-5 w-5" />
                            </span>
                            <div>
                                <p class="font-medium text-sm text-gray-950 dark:text-white">{{ $backup['name'] }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                                    <x-filament::icon icon="heroicon-o-clock" class="h-3.5 w-3.5" />
                                    {{ $backup['date'] }}
                                    <span class="text-gray-300 dark:text-gray-600">&middot;</span>
                                    {{ $backup['size'] }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('backup.download', ['path' => base64_encode($backup['path'])]) }}" class="fi-btn fi-btn-size-sm fi-btn-color-gray inline-flex items-center gap-1.5 rounded-lg border border-gray-300 px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-white/20 dark:text-gray-200 dark:hover:bg-white/10">
                                <x-filament::icon icon="heroicon-o-arrow-down-tray" class="h-4 w-4" />
                                Download
                            </a>

                            <x-filament::button size="sm" color="danger" outlined icon="heroicon-o-trash" wire:click="deleteBackup('{{ addslashes($backup['path']) }}')" wire:confirm="Are you sure you want to delete this backup?">
                                Delete
                            </x-filament::button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </x-filament::section>

    {{-- ===== RESTORE FROM BACKUP ===== --}}
    <x-filament::section class="mt-6" icon="heroicon-o-arrow-up-tray" icon-color="danger">
        <x-slot name="heading">
            Restore Database
        </x-slot>

        <x-slot name="description">
            Upload a previously generated backup file to restore your database
        </x-slot>

        <div class="mb-4 flex items-start gap-3 rounded-xl border border-danger-200 bg-danger-50 p-4 dark:border-danger-500/20 dark:bg-danger-500/10">
            <x-filament::icon icon="heroicon-o-exclamation-triangle" class="h-5 w-5 shrink-0 text-danger-600 dark:text-danger-400" />
            <div>
                <p class="text-sm font-semibold text-danger-700 dark:text-danger-400">This will overwrite your current database</p>
                <p class="text-xs text-danger-600/80 dark:text-danger-400/80">This action cannot be undone. Make sure you have a recent backup before proceeding.</p>
            </div>
        </div>

        <form wire:submit="restoreBackup">
            {{ $this->form }}

            <div class="mt-4">
                <x-filament::button
                    type="submit"
                    color="danger"
                    icon="heroicon-o-arrow-path"
                    wire:confirm="This will overwrite your current database. Are you absolutely sure?"
                >
                    Restore From File
                </x-filament::button>
            </div>
        </form>
    </x-filament::section>

</x-filament-panels::page>