<x-filament-widgets::widget>
   
      
<div class="w-full">
    <div
        class="relative overflow-hidden rounded-2xl border border-[#00838F]/15 bg-white shadow-sm"
    >
        {{-- Decorative Background --}}
        <div class="absolute -right-16 -top-16 h-40 w-40 rounded-full bg-[#00838F]/10"></div>
        <div class="absolute -bottom-20 -left-10 h-40 w-40 rounded-full bg-[#00838F]/5"></div>

        <div class="relative flex flex-col gap-5 p-6 sm:p-7 lg:flex-row lg:items-center lg:justify-between">

            {{-- Left Content --}}
            <div class="flex items-start gap-4">
                <div
                    class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[#00838F]/10 text-[#00838F]"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-7 w-7"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 14.25l6-6m4.5 3.75a9 9 0 11-12.728 0A9 9 0 0119.5 12z"
                        />
                    </svg>
                </div>

                <div>
                    <div class="mb-1 flex items-center gap-2">
                        <span
                            class="rounded-full bg-[#00838F]/10 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wider text-[#00838F]"
                        >
                            Sales Management
                        </span>
                    </div>

                    <h2 class="text-xl font-bold tracking-tight text-gray-900 sm:text-2xl">
                        Sales History
                    </h2>

                    <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">
                        Review completed sales, customer transactions, payment details,
                        and sales activity from your pharmacy.
                    </p>
                </div>
            </div>

            {{-- Right Side --}}
            <div class="flex shrink-0 items-center gap-3">
                <div class="hidden text-right sm:block">
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                        Pharmacy
                    </p>
                    <p class="text-sm font-semibold text-gray-700">
                        Sales Overview
                    </p>
                </div>

                <div
                    class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#00838F] text-white shadow-sm"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3 3v18h18"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M7 16l4-5 3 3 5-7"
                        />
                    </svg>
                </div>
            </div>

        </div>
    </div>
</div>


    
</x-filament-widgets::widget>
