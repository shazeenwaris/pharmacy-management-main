<x-filament-panels::page>

    @php
        $totalPayments = \App\Models\SupplierPayment::count();

        $totalPaid = \App\Models\SupplierPayment::sum('amount');

        $thisMonthPaid = \App\Models\SupplierPayment::whereMonth(
            'payment_date',
            now()->month
        )->whereYear(
            'payment_date',
            now()->year
        )->sum('amount');

        $thisYearPaid = \App\Models\SupplierPayment::whereYear(
            'payment_date',
            now()->year
        )->sum('amount');
    @endphp
    {{-- =====================================================
        PAYMENT PAGE HEADER
    ====================================================== --}}

    <div class="supplier-payment-page-header">

        <div class="supplier-payment-header-left">

            <div class="supplier-payment-page-icon">
                <x-heroicon-o-banknotes />
            </div>

            <div>

                <div class="supplier-payment-title-row">

                    <h1 class="supplier-payment-page-title">
                        Supplier Payments
                    </h1>

                    <span class="supplier-payment-page-badge">
                        Payment Management
                    </span>

                </div>

                <p class="supplier-payment-page-description">
                    Track supplier payments, payment history and financial transactions
                </p>

            </div>

        </div>


        <div class="supplier-payment-header-right">

            <div class="supplier-payment-total-mini">

                <div class="supplier-payment-total-icon">
                    <x-heroicon-o-banknotes />
                </div>

                <div>

                    <strong>
                        $ {{ number_format($totalPaid, 0) }}
                    </strong>

                    <span>
                        Total Paid
                    </span>

                </div>

            </div>


            <a
                href="{{ \App\Filament\Resources\SupplierPayments\Pages\CreateSupplierPayment::getUrl() }}"
                class="supplier-payment-add-button"
            >

                <x-heroicon-o-plus />

                <span>
                    Add Payment
                </span>

            </a>

        </div>

    </div>


    {{-- =====================================================
        PAYMENT STATS
    ====================================================== --}}

    <div class="supplier-payment-stats-grid">


        {{-- TOTAL PAYMENTS --}}
        <div class="supplier-payment-stat-card">

            <div class="supplier-payment-stat-top">

                <div class="supplier-payment-stat-icon total">
                    <x-heroicon-o-credit-card />
                </div>

                <span class="supplier-payment-stat-label">
                    Total Payments
                </span>

            </div>

            <div class="supplier-payment-stat-value">
                {{ number_format($totalPayments) }}
            </div>

            <div class="supplier-payment-stat-bottom">

                <span class="supplier-payment-stat-description">
                    All recorded payments
                </span>

                <x-heroicon-o-receipt-percent
                    class="supplier-payment-stat-corner-icon"
                />

            </div>

        </div>


        {{-- TOTAL PAID --}}
        <div class="supplier-payment-stat-card">

            <div class="supplier-payment-stat-top">

                <div class="supplier-payment-stat-icon paid">
                    <x-heroicon-o-check-badge />
                </div>

                <span class="supplier-payment-stat-label">
                    Total Paid
                </span>

            </div>

            <div class="supplier-payment-stat-value">
                $ {{ number_format($totalPaid, 0) }}
            </div>

            <div class="supplier-payment-stat-bottom">

                <span class="supplier-payment-stat-description paid-text">
                    Total amount paid
                </span>

                <x-heroicon-o-banknotes
                    class="supplier-payment-stat-corner-icon paid-icon"
                />

            </div>

        </div>


        {{-- THIS MONTH --}}
        <div class="supplier-payment-stat-card">

            <div class="supplier-payment-stat-top">

                <div class="supplier-payment-stat-icon month">
                    <x-heroicon-o-calendar-days />
                </div>

                <span class="supplier-payment-stat-label">
                    This Month
                </span>

            </div>

            <div class="supplier-payment-stat-value">
                $ {{ number_format($thisMonthPaid, 0) }}
            </div>

            <div class="supplier-payment-stat-bottom">

                <span class="supplier-payment-stat-description month-text">
                    Payments this month
                </span>

                <x-heroicon-o-arrow-trending-up
                    class="supplier-payment-stat-corner-icon month-icon"
                />

            </div>

        </div>


        {{-- THIS YEAR --}}
        <div class="supplier-payment-stat-card">

            <div class="supplier-payment-stat-top">

                <div class="supplier-payment-stat-icon year">
                    <x-heroicon-o-chart-bar />
                </div>

                <span class="supplier-payment-stat-label">
                    This Year
                </span>

            </div>

            <div class="supplier-payment-stat-value">
                $ {{ number_format($thisYearPaid, 0) }}
            </div>

            <div class="supplier-payment-stat-bottom">

                <span class="supplier-payment-stat-description year-text">
                    Payments this year
                </span>

                <x-heroicon-o-presentation-chart-line
                    class="supplier-payment-stat-corner-icon year-icon"
                />

            </div>

        </div>

    </div>


    {{-- =====================================================
        TABLE
    ====================================================== --}}

    <div class="supplier-payment-table-wrapper">

        <div class="supplier-payment-table-header">

            <div>

                <h2 class="supplier-payment-table-title">
                    Payment History
                </h2>

                <p class="supplier-payment-table-subtitle">
                    View and manage all supplier payment transactions
                </p>

            </div>

        </div>


        <div class="supplier-payment-filament-table">

            {{ $this->table }}

        </div>

    </div>

</x-filament-panels::page>