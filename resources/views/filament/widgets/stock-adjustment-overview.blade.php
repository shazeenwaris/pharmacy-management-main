<x-filament-widgets::widget>
    <div class="space-y-5">

        {{-- Hero --}}
        <div
            class="relative overflow-hidden rounded-2xl px-6 py-6 shadow-sm"
            style="background: #00707A;"
        >
            {{-- Decorative circle --}}
            <div
                class="absolute -right-10 -top-16 h-40 w-40 rounded-full opacity-10"
                style="background: #ffffff;"
            ></div>

            <div
                class="absolute -right-24 -bottom-24 h-52 w-52 rounded-full opacity-10"
                style="background: #ffffff;"
            ></div>

            <div class="relative flex items-center justify-between gap-5">

                <div class="flex items-center gap-4">

                    {{-- Icon --}}
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full border border-white/20"
                        style="background: rgba(255,255,255,0.12);"
                    >
                        <x-heroicon-o-adjustments-horizontal
                            class="h-6 w-6 text-white"
                        />
                    </div>

                    <div>
                        <h2 class="text-xl font-bold tracking-tight text-white">
                            Stock Adjustment Management
                        </h2>

                        <p class="mt-1 text-sm text-white/75">
                            Track, correct and manage pharmacy stock adjustments
                        </p>
                    </div>

                </div>

                {{-- Create button --}}
                <a
                    href="{{ \App\Filament\Resources\StockAdjustments\Pages\CreateStockAdjustment::getUrl() }}"
                    class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-white px-5 py-2.5 text-sm font-semibold shadow-sm transition hover:bg-gray-50"
                    style="color: #00707A;"
                >
                    <x-heroicon-m-plus class="h-4 w-4" />
                    New Adjustment
                </a>

            </div>
        </div>


        {{-- Statistics --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

            {{-- Total --}}
            <div
                class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-gray-700 dark:bg-gray-900"
            >
                <div class="flex items-center gap-4">

                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-xl"
                        style="background: #e4f7f5;"
                    >
                        <x-heroicon-o-adjustments-horizontal
                            class="h-5 w-5"
                            style="color: #07877d;"
                        />
                    </div>

                    <div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($total) }}
                        </div>

                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Total Adjustments
                        </div>
                    </div>

                </div>
            </div>


            {{-- This Month --}}
            <div
                class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-gray-700 dark:bg-gray-900"
            >
                <div class="flex items-center gap-4">

                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-xl"
                        style="background: #fff0ee;"
                    >
                        <x-heroicon-o-clock
                            class="h-5 w-5"
                            style="color: #ef4444;"
                        />
                    </div>

                    <div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($thisMonth) }}
                        </div>

                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            This Month
                        </div>
                    </div>

                </div>
            </div>


            {{-- Stock Changes --}}
            <div
                class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-gray-700 dark:bg-gray-900"
            >
                <div class="flex items-center gap-4">

                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-xl"
                        style="background: #edf6ff;"
                    >
                        <x-heroicon-o-chart-bar
                            class="h-5 w-5"
                            style="color: #3b82f6;"
                        />
                    </div>

                    <div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($increased + $decreased) }}
                        </div>

                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Stock Changes
                        </div>
                    </div>

                </div>
            </div>

        </div>


        {{-- Information Banner --}}
        <div
            class="flex items-center gap-3 rounded-2xl border px-5 py-4"
            style="border-color: #d5eeee; background: linear-gradient(90deg, #f1fbfb 0%, #f8fdfd 100%);"
        >

            <div
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl"
                style="background: #d9f4f2;"
            >
                <x-heroicon-o-information-circle
                    class="h-5 w-5"
                    style="color: #07877d;"
                />
            </div>

            <div>
                <div class="text-sm font-semibold text-gray-700">
                    Keep stock records accurate
                </div>

                <div class="mt-0.5 text-xs text-gray-500">
                    Record damaged, expired, lost, returned and correction adjustments
                    to keep inventory quantities accurate.
                </div>
            </div>

        </div>

    </div>
</x-filament-widgets::widget>