<div>

    <x-filament-panels::page>

       


        {{-- =====================================================
            TEAL BANNER
        ====================================================== --}}

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
                        <circle
                            cx="12"
                            cy="12"
                            r="8.5"
                        />

                        <path
                            stroke-linecap="round"
                            d="M12 7.5v4.8l3 1.8"
                        />

                    </svg>

                </div>


                {{-- Banner Text --}}
                <div>

                    <h2 class="text-base font-bold text-white">
                        Expiry Monitoring
                    </h2>

                    <p class="mt-0.5 text-xs text-white/80">
                        Monitor medicines that are approaching their expiry date
                    </p>

                </div>

            </div>

        </div>


        {{-- =====================================================
            OVERVIEW CARDS
        ====================================================== --}}

        <div class="mb-5 grid grid-cols-1 gap-4 md:grid-cols-3">


            {{-- EXPIRING SOON --}}
            <div
                class="rounded-xl bg-white p-5 shadow-sm"
                style="border:1px solid #E3ECEE;"
            >

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Expiring Soon
                        </p>

                        <p class="mt-2 text-2xl font-bold text-orange-500">
                            {{ $expiringSoon }}
                        </p>

                        <p class="mt-1 text-[11px] text-gray-400">
                            Medicines approaching expiry
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
                            <circle
                                cx="12"
                                cy="12"
                                r="8.5"
                            />

                            <path
                                stroke-linecap="round"
                                d="M12 7.5v4.8l3 1.8"
                            />

                        </svg>

                    </div>

                </div>

            </div>


            {{-- CRITICAL EXPIRY --}}
            <div
                class="rounded-xl bg-white p-5 shadow-sm"
                style="border:1px solid #E3ECEE;"
            >

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Critical Expiry
                        </p>

                        <p class="mt-2 text-2xl font-bold text-red-500">
                            {{ $criticalExpiry }}
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
                                d="M12 8v4m0 4h.01M10.3 3.9l-8 14A2 2 0 004 21h16a2 2 0 001.7-3.1l-8-14a2 2 0 00-3.4 0z"
                            />

                        </svg>

                    </div>

                </div>

            </div>


            {{-- WARNING EXPIRY --}}
            <div
                class="rounded-xl bg-white p-5 shadow-sm"
                style="border:1px solid #E3ECEE;"
            >

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Warning Expiry
                        </p>

                        <p class="mt-2 text-2xl font-bold text-gray-800">
                            {{ $warningExpiry }}
                        </p>

                        <p class="mt-1 text-[11px] text-gray-400">
                            Need monitoring
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
                            <circle
                                cx="12"
                                cy="12"
                                r="8.5"
                            />

                            <path
                                stroke-linecap="round"
                                d="M12 7.5v4.8l3 1.8"
                            />

                        </svg>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            EXPIRY ALERTS TABLE
        ====================================================== --}}

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
                        Expiry Alerts
                    </h3>

                    <p class="mt-1 text-xs text-gray-400">
                        Medicines currently approaching their expiry date
                    </p>

                </div>


                <div
                    class="rounded-lg px-3 py-1.5 text-xs font-semibold"
                    style="
                        background:#FFF7E8;
                        color:#D97706;
                    "
                >
                    {{ $expiringSoon }} Alerts
                </div>

            </div>


            <div class="p-1">

                {{ $this->table }}

            </div>

        </div>

    </x-filament-panels::page>

</div>