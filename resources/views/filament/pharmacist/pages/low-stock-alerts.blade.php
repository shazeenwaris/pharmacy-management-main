
<div>
    <x-filament-panels::page>

       

        {{-- =========================================================
            TEAL BANNER
        ========================================================== --}}

        <div
            class="mb-5 overflow-hidden rounded-xl"
            style="
                background: linear-gradient(
                    135deg,
                    #007F86 0%,
                    #008D94 100%
                );
            "
        >

            <div class="flex items-center gap-4 px-6 py-5">

                {{-- Icon --}}
                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full"
                    style="
                        background: rgba(255,255,255,0.14);
                        border: 1px solid rgba(255,255,255,0.18);
                    "
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        class="h-6 w-6 text-white"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 9v4m0 4h.01M10.29 3.86l-8.18 14a2 2 0 001.72 3h16.34a2 2 0 001.72-3l-8.18-14a2 2 0 00-3.44 0z"
                        />
                    </svg>

                </div>


                {{-- Banner Text --}}
                <div>

                    <h2 class="text-base font-bold text-white">
                        Low Stock Monitoring
                    </h2>

                    <p class="mt-0.5 text-xs text-white/80">
                        Monitor medicines that need stock attention
                    </p>

                </div>

            </div>

        </div>


        {{-- =========================================================
            STATIC / OVERVIEW CARDS
        ========================================================== --}}

        <div class="mb-5 grid grid-cols-1 gap-4 md:grid-cols-3">


            {{-- LOW STOCK --}}
            <div
                class="rounded-xl bg-white p-5 shadow-sm"
                style="border:1px solid #E3ECEE;"
            >

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Low Stock Items
                        </p>

                        <p class="mt-2 text-2xl font-bold text-gray-800">
                            {{ $lowStockItems }}
                        </p>

                        <p class="mt-1 text-[11px] text-gray-400">
                            Medicines requiring attention
                        </p>

                    </div>


                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-lg"
                        style="
                            background:#FFF4E5;
                            color:#F59E0B;
                        "
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            class="h-5 w-5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 9v4m0 4h.01M10.29 3.86l-8.18 14a2 2 0 001.72 3h16.34a2 2 0 001.72-3l-8.18-14a2 2 0 00-3.44 0z"
                            />
                        </svg>

                    </div>

                </div>

            </div>


            {{-- CRITICAL --}}
            <div
                class="rounded-xl bg-white p-5 shadow-sm"
                style="border:1px solid #E3ECEE;"
            >

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Critical Items
                        </p>

                        <p class="mt-2 text-2xl font-bold text-red-500">
                            {{ $criticalItems }}
                        </p>

                        <p class="mt-1 text-[11px] text-gray-400">
                            Require immediate attention
                        </p>

                    </div>


                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-lg"
                        style="
                            background:#FDECEC;
                            color:#EF4444;
                        "
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            class="h-5 w-5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 9v4m0 4h.01M10.29 3.86l-8.18 14a2 2 0 001.72 3h16.34a2 2 0 001.72 3h-16.34a2 2 0 001.72-3l8.18-14a2 2 0 003.44 0z"
                            />
                        </svg>

                    </div>

                </div>

            </div>


            {{-- TOTAL MEDICINES --}}
            <div
                class="rounded-xl bg-white p-5 shadow-sm"
                style="border:1px solid #E3ECEE;"
            >

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Total Medicines
                        </p>

                        <p class="mt-2 text-2xl font-bold text-gray-800">
                            {{ $totalMedicines }}
                        </p>

                        <p class="mt-1 text-[11px] text-gray-400">
                            Medicines in inventory
                        </p>

                    </div>


                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-lg"
                        style="
                            background:#E0F7FA;
                            color:#00838F;
                        "
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            class="h-5 w-5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9 3h6m-7 4h8m-9 4h10m-9 4h8m-9 4h10"
                            />
                        </svg>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
            ALERT TABLE
        ========================================================== --}}

        <div
            class="overflow-hidden rounded-xl bg-white shadow-sm"
            style="border:1px solid #E3ECEE;"
        >

            <div
                class="flex items-center justify-between px-5 py-4"
                style="border-bottom:1px solid #E8EFF0;"
            >

                <div>

                    <h3 class="text-sm font-semibold text-gray-800">
                        Stock Alerts
                    </h3>

                    <p class="mt-1 text-xs text-gray-400">
                        Medicines currently below their stock level
                    </p>

                </div>


                <div
                    class="rounded-lg px-3 py-1.5 text-xs font-semibold"
                    style="
                        background:#F1FBFC;
                        color:#00838F;
                    "
                >
                    {{ $lowStockItems }} Alerts
                </div>

            </div>


            <div class="p-1">

                {{ $this->table }}

            </div>

        </div>

    </x-filament-panels::page>

</div>