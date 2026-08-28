<x-filament-widgets::widget>
    <div class="space-y-5">

        {{-- Hero --}}
        <div
            class="relative overflow-hidden rounded-2xl px-6 py-6 shadow-sm"
            style="background: linear-gradient(135deg, #087f73 0%, #118b80 55%, #08766d 100%);"
        >

            {{-- Decorative circles --}}
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

                    {{-- Icon --}}
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full border border-white/20"
                        style="background:rgba(255,255,255,0.12);"
                    >
                        <x-heroicon-o-clock class="h-6 w-6 text-white" />
                    </div>


                    <div>

                        <h2 class="text-xl font-bold tracking-tight text-white">
                            Activity Management
                        </h2>

                        <p class="mt-1 text-sm text-white/75">
                            Monitor and track all pharmacy system activities
                        </p>

                    </div>

                </div>


                {{-- Activity info --}}
                <div
                    class="hidden rounded-lg border border-white/20 px-4 py-2 text-sm font-medium text-white sm:block"
                    style="background:rgba(255,255,255,0.10);"
                >
                    System Audit Log
                </div>

            </div>

        </div>


        {{-- Statistics --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">


            {{-- Total Activities --}}
            <div
                class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-gray-700 dark:bg-gray-900"
            >

                <div class="flex items-center gap-4">

                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-xl"
                        style="background:#e4f7f5;"
                    >
                        <x-heroicon-o-list-bullet
                            class="h-5 w-5"
                            style="color:#07877d;"
                        />
                    </div>


                    <div>

                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($total) }}
                        </div>

                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Total Activities
                        </div>

                    </div>

                </div>

            </div>


            {{-- Today --}}
            <div
                class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-gray-700 dark:bg-gray-900"
            >

                <div class="flex items-center gap-4">

                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-xl"
                        style="background:#edf6ff;"
                    >
                        <x-heroicon-o-calendar-days
                            class="h-5 w-5"
                            style="color:#3b82f6;"
                        />
                    </div>


                    <div>

                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($today) }}
                        </div>

                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Today's Activities
                        </div>

                    </div>

                </div>

            </div>


            {{-- Changes --}}
            <div
                class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-gray-700 dark:bg-gray-900"
            >

                <div class="flex items-center gap-4">

                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-xl"
                        style="background:#fff7e8;"
                    >
                        <x-heroicon-o-arrow-path
                            class="h-5 w-5"
                            style="color:#f59e0b;"
                        />
                    </div>


                    <div>

                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($created + $updated + $deleted) }}
                        </div>

                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Recorded Changes
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
                    Keep your pharmacy activity records transparent
                </div>

                <div class="mt-0.5 text-xs text-gray-500">
                    Monitor user actions, module changes and important system
                    events for better security and accountability.
                </div>

            </div>

        </div>

    </div>
</x-filament-widgets::widget>