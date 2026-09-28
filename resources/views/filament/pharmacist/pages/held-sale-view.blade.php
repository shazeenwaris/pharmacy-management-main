```blade
<x-filament-panels::page>

    <div class="space-y-6">

        {{-- Sale Information --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <p class="text-sm text-gray-500">Reference</p>
                <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                    {{ $record->reference }}
                </p>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <p class="text-sm text-gray-500">Customer</p>
                <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                    {{ $record->customer?->name ?? 'Walk-in Customer' }}
                </p>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <p class="text-sm text-gray-500">Held By</p>
                <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                    {{ $record->heldBy?->name ?? 'Admin' }}
                </p>
            </div>

        </div>

        {{-- Held At --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <div class="flex flex-col gap-1">
                <span class="text-sm text-gray-500">Held At</span>

                <span class="font-medium text-gray-900 dark:text-white">
                    {{ $record->created_at?->format('d M Y, h:i A') }}
                </span>
            </div>
        </div>

        {{-- Sale Data --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">

            <div class="mb-5">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Held Sale Information
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Details of this held transaction.
                </p>
            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-left text-sm">

                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th class="px-4 py-3 font-semibold">Field</th>
                            <th class="px-4 py-3 font-semibold">Value</th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr class="border-b border-gray-100 dark:border-gray-800">
                            <td class="px-4 py-3 text-gray-500">
                                Reference
                            </td>

                            <td class="px-4 py-3 font-medium">
                                {{ $record->reference }}
                            </td>
                        </tr>

                        <tr class="border-b border-gray-100 dark:border-gray-800">
                            <td class="px-4 py-3 text-gray-500">
                                Customer
                            </td>

                            <td class="px-4 py-3 font-medium">
                                {{ $record->customer?->name ?? 'Walk-in Customer' }}
                            </td>
                        </tr>

                        <tr>
                            <td class="px-4 py-3 text-gray-500">
                                Held By
                            </td>

                            <td class="px-4 py-3 font-medium">
                                {{ $record->heldBy?->name ?? 'Admin' }}
                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</x-filament-panels::page>
```
