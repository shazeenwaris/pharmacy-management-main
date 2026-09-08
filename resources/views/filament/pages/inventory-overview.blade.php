<x-filament-panels::page>

    <div class="space-y-5">

        {{-- Hero --}}
        <div
            class="relative overflow-hidden rounded-2xl px-6 py-6 shadow-sm"
            style="background:linear-gradient(135deg,#00707A 0%,#00707A 55%,#00707A 100%);"
        >

            <div
                class="absolute -right-10 -top-16 h-40 w-40 rounded-full opacity-10"
                style="background:#fff;"
            ></div>

            <div
                class="absolute -right-24 -bottom-24 h-52 w-52 rounded-full opacity-10"
                style="background:#fff;"
            ></div>

            <div class="relative flex items-center justify-between gap-5">

                <div class="flex items-center gap-4">

                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full border border-white/20"
                        style="background:rgba(255,255,255,.12);"
                    >
                        <x-heroicon-o-cube class="h-6 w-6 text-white" />
                    </div>

                    <div>
                        <h2 class="text-xl font-bold tracking-tight text-white">
                            Inventory Management
                        </h2>

                        <p class="mt-1 text-sm text-white/75">
                            Monitor medicine stock levels and inventory availability
                        </p>
                    </div>

                </div>

                <div
                    class="hidden rounded-lg border border-white/20 px-4 py-2 text-sm font-medium text-white sm:block"
                    style="background:rgba(255,255,255,.10);"
                >
                    Inventory Overview
                </div>

            </div>
        </div>


        {{-- Statistics --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

            {{-- Total Medicines --}}
            <div
                class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-gray-700 dark:bg-gray-900"
            >
                <div class="flex items-center gap-4">

                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-xl"
                        style="background:#e4f7f5;"
                    >
                        <x-heroicon-o-cube
                            class="h-5 w-5"
                            style="color:#07877d;"
                        />
                    </div>

                    <div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($totalMedicines) }}
                        </div>

                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Total Medicines
                        </div>
                    </div>

                </div>
            </div>


            {{-- Total Stock --}}
            <div
                class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-gray-700 dark:bg-gray-900"
            >
                <div class="flex items-center gap-4">

                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-xl"
                        style="background:#edf6ff;"
                    >
                        <x-heroicon-o-archive-box
                            class="h-5 w-5"
                            style="color:#3b82f6;"
                        />
                    </div>

                    <div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($totalStock) }}
                        </div>

                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Total Units
                        </div>
                    </div>

                </div>
            </div>


            {{-- Low Stock --}}
            <div
                class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-gray-700 dark:bg-gray-900"
            >
                <div class="flex items-center gap-4">

                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-xl"
                        style="background:#fff7e8;"
                    >
                        <x-heroicon-o-exclamation-triangle
                            class="h-5 w-5"
                            style="color:#f59e0b;"
                        />
                    </div>

                    <div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($lowStock) }}
                        </div>

                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Low Stock
                        </div>
                    </div>

                </div>
            </div>

        </div>


        {{-- Secondary Cards --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

            {{-- Out of Stock --}}
            <div
                class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900"
            >
                <div class="flex items-center justify-between">

                    <div class="flex items-center gap-3">

                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl"
                            style="background:#fff0f0;"
                        >
                            <x-heroicon-o-x-circle
                                class="h-5 w-5"
                                style="color:#ef4444;"
                            />
                        </div>

                        <div>
                            <div class="text-sm font-semibold text-gray-700 dark:text-gray-200">
                                Out of Stock
                            </div>

                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                Medicines with no available stock
                            </div>
                        </div>

                    </div>

                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ number_format($outOfStock) }}
                    </div>

                </div>
            </div>


            {{-- Stock Health --}}
            <div
                class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900"
            >
                <div class="flex items-center justify-between">

                    <div class="flex items-center gap-3">

                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl"
                            style="background:#e9f9ef;"
                        >
                            <x-heroicon-o-check-circle
                                class="h-5 w-5"
                                style="color:#16a34a;"
                            />
                        </div>

                        <div>
                            <div class="text-sm font-semibold text-gray-700 dark:text-gray-200">
                                Inventory Monitoring
                            </div>

                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                Review low and unavailable medicines regularly
                            </div>
                        </div>

                    </div>

                    <span
                        class="rounded-full px-3 py-1 text-xs font-semibold"
                        style="background:#e9f9ef;color:#16a34a;"
                    >
                        Active
                    </span>

                </div>
            </div>

        </div>


        {{-- Information Banner --}}
        <div
            class="flex items-center gap-3 rounded-2xl border px-5 py-4"
            style="border-color:#d5eeee;background:linear-gradient(90deg,#f1fbfb 0%,#f8fdfd 100%);"
        >

            <div
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl"
                style="background:#d9f4f2;"
            >
                <x-heroicon-o-information-circle
                    class="h-5 w-5"
                    style="color:#07877d;"
                />
            </div>

            <div>
                <div class="text-sm font-semibold text-gray-700">
                    Keep pharmacy inventory healthy
                </div>

                <div class="mt-0.5 text-xs text-gray-500">
                    Monitor current stock against minimum levels and take action
                    before medicines become unavailable.
                </div>
            </div>

        </div>


        {{-- Existing Table --}}
        <div>
            {{ $this->table }}
        </div>

    </div>

</x-filament-panels::page>