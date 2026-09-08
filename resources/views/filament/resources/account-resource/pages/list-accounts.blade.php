<x-filament-panels::page>
    {{-- Teal Banner --}}
    <div class="relative overflow-hidden rounded-xl px-6 py-6 sm:px-8 sm:py-7" style="background: linear-gradient(to right, #00707A, #005a63);">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-white/15">
                    <x-heroicon-o-banknotes class="h-6 w-6 text-white" />
                </div>
                <div>
                    <h2 class="text-xl font-bold text-white">Accounts List</h2>
                    <p class="text-sm text-teal-50/90">Manage bank accounts and balances</p>
                </div>
            </div>

            <a href="{{ \App\Filament\Resources\Accounts\AccountResource::getUrl('create') }}"
               class="inline-flex items-center justify-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-semibold text-teal-700 shadow-sm transition hover:bg-teal-50">
                <x-heroicon-o-plus class="h-4 w-4" />
                Create Account
            </a>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="flex items-center gap-4 rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-gray-900">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-teal-50 dark:bg-teal-500/10">
                <x-heroicon-o-banknotes class="h-5 w-5 text-teal-600" />
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalAccounts }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Accounts</p>
            </div>
        </div>

        <div class="flex items-center gap-4 rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-gray-900">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-amber-50 dark:bg-amber-500/10">
                <x-heroicon-o-check-circle class="h-5 w-5 text-amber-600" />
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $activeAccounts }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Active Accounts</p>
            </div>
        </div>

        <div class="flex items-center gap-4 rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-gray-900">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-rose-50 dark:bg-rose-500/10">
                <x-heroicon-o-wallet class="h-5 w-5 text-rose-600" />
            </div>
            <div>
                <!-- <p class="text-2xl font-bold text-gray-900 dark:text-white">
                    PKR {{ number_format($totalBalance, 2) }}
                </p> -->
                <p style="font-size: 1.5rem; font-weight: 700; color: #123047; line-height: 1;">
    {{ format_currency($totalBalance) }}
</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Balance</p>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="mt-4">
        {{ $this->table }}
    </div>
</x-filament-panels::page>