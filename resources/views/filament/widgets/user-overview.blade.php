<x-filament-widgets::widget>
    <div class="space-y-5">

        {{-- Hero --}}
        <div
            class="relative overflow-hidden rounded-2xl px-6 py-6 shadow-sm"
            style="background: linear-gradient(135deg, #00707A 0%, #00707A 55%, #00707A 100%);"
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
                        <x-heroicon-o-users class="h-6 w-6 text-white" />
                    </div>

                    <div>
                        <h2 class="text-xl font-bold tracking-tight text-white">
                            User Management
                        </h2>

                        <p class="mt-1 text-sm text-white/75">
                            Manage pharmacy staff, roles and account access
                        </p>
                    </div>

                </div>

                <a
                    href="{{ \App\Filament\Resources\Users\Pages\CreateUser::getUrl() }}"
                    class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-white px-5 py-2.5 text-sm font-semibold shadow-sm transition hover:bg-gray-50"
                    style="color:#00707A;"
                >
                    <x-heroicon-m-plus class="h-4 w-4" />
                    New User
                </a>

            </div>
        </div>


        {{-- Statistics --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

            {{-- Total Users --}}
            <div
                class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-gray-700 dark:bg-gray-900"
            >
                <div class="flex items-center gap-4">

                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-xl"
                        style="background:#e4f7f5;"
                    >
                        <x-heroicon-o-users
                            class="h-5 w-5"
                            style="color:#07877d;"
                        />
                    </div>

                    <div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
    {{ $total !== null ? number_format($total) : '—' }}
</div>

                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Total Users
                        </div>
                    </div>

                </div>
            </div>


            {{-- Active --}}
            <div
                class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-gray-700 dark:bg-gray-900"
            >
                <div class="flex items-center gap-4">

                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-xl"
                        style="background:#e9f9ef;"
                    >
                        <x-heroicon-o-check-circle
                            class="h-5 w-5"
                            style="color:#16a34a;"
                        />
                    </div>

                    <div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($active) }}
                        </div>

                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Active Users
                        </div>
                    </div>

                </div>
            </div>


            {{-- Inactive --}}
            <div
                class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-gray-700 dark:bg-gray-900"
            >
                <div class="flex items-center gap-4">

                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-xl"
                        style="background:#fff0ee;"
                    >
                        <x-heroicon-o-x-circle
                            class="h-5 w-5"
                            style="color:#ef4444;"
                        />
                    </div>

                    <div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($inactive) }}
                        </div>

                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Inactive Users
                        </div>
                    </div>

                </div>
            </div>

        </div>


        {{-- Information Banner --}}
        <div
            class="flex items-center gap-3 rounded-2xl border px-5 py-4"
            style="border-color:#d5eeee; background:linear-gradient(90deg,#f1fbfb 0%,#f8fdfd 100%);"
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
                    Manage user access securely
                </div>

                <div class="mt-0.5 text-xs text-gray-500">
                    Keep pharmacy staff accounts, roles and active status
                    properly managed for secure system access.
                </div>
            </div>

        </div>

    </div>
</x-filament-widgets::widget>