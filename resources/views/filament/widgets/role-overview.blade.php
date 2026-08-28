<x-filament-widgets::widget>
    <div class="space-y-5">

        {{-- Hero --}}
        <div
            class="relative overflow-hidden rounded-2xl px-6 py-6 shadow-sm"
            style="background: #00707A;"
        >
            <div
                class="absolute -right-10 -top-16 h-40 w-40 rounded-full opacity-10"
                style="background:#ffffff;"
            ></div>

            <div
                class="absolute -right-24 -bottom-24 h-52 w-52 rounded-full opacity-10"
                style="background:#ffffff;"
            ></div>

            <div class="relative flex items-center justify-between gap-5">

                <div class="flex items-center gap-4">

                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full border border-white/20"
                        style="background:rgba(255,255,255,0.12);"
                    >
                        <x-heroicon-o-shield-check class="h-6 w-6 text-white" />
                    </div>

                    <div>
                        <h2 class="text-xl font-bold tracking-tight text-white">
                            Roles & Permissions
                        </h2>

                        <p class="mt-1 text-sm text-white/75">
                            Manage staff roles and control system permissions
                        </p>
                    </div>

                </div>

                <a
                    href="{{ \App\Filament\Resources\Roles\Pages\CreateRole::getUrl() }}"
                    class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-white px-5 py-2.5 text-sm font-semibold shadow-sm transition hover:bg-gray-50"
                    style="color:#087f73;"
                >
                    <x-heroicon-m-plus class="h-4 w-4" />
                    New Role
                </a>

            </div>
        </div>


        {{-- Statistics --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

            {{-- Total Roles --}}
            <div
                class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-gray-700 dark:bg-gray-900"
            >
                <div class="flex items-center gap-4">

                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-xl"
                        style="background:#e4f7f5;"
                    >
                        <x-heroicon-o-shield-check
                            class="h-5 w-5"
                            style="color:#07877d;"
                        />
                    </div>

                    <div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($totalRoles) }}
                        </div>

                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Total Roles
                        </div>
                    </div>

                </div>
            </div>


            {{-- Permissions --}}
            <div
                class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-gray-700 dark:bg-gray-900"
            >
                <div class="flex items-center gap-4">

                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-xl"
                        style="background:#edf6ff;"
                    >
                        <x-heroicon-o-key
                            class="h-5 w-5"
                            style="color:#3b82f6;"
                        />
                    </div>

                    <div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($totalPermissions) }}
                        </div>

                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Total Permissions
                        </div>
                    </div>

                </div>
            </div>


            {{-- Assigned Users --}}
            <div
                class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-gray-700 dark:bg-gray-900"
            >
                <div class="flex items-center gap-4">

                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-xl"
                        style="background:#e9f9ef;"
                    >
                        <x-heroicon-o-users
                            class="h-5 w-5"
                            style="color:#16a34a;"
                        />
                    </div>

                    <div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($assignedUsers) }}
                        </div>

                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Assigned Users
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
                    Secure pharmacy access
                </div>

                <div class="mt-0.5 text-xs text-gray-500">
                    Assign appropriate roles and permissions to control what
                    pharmacy staff can access and manage.
                </div>
            </div>

        </div>

    </div>
</x-filament-widgets::widget>