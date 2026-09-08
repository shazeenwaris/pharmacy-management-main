<?php

namespace App\Filament\Resources\Suppliers\Schemas;
use App\Filament\Resources\Suppliers\SupplierResource;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class SupplierInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            /*
|--------------------------------------------------------------------------
| TOP AREA
|--------------------------------------------------------------------------
*/

Grid::make([
    'default' => 1,
    'lg' => 12,
])
    ->schema([

        /*
        |--------------------------------------------------------------------------
        | SUPPLIER PROFILE
        |--------------------------------------------------------------------------
        */

        Section::make()
            ->schema([

                TextEntry::make('supplier_profile')
                    ->label('')
                    ->state(function ($record) {

                        $supplierName = e(
                            $record->supplier_name ?? 'Unknown Supplier'
                        );

                       

                        $status = strtolower(
                            $record->status ?? 'unknown'
                        );

                        $statusLabel = ucfirst($status);

                        return new HtmlString(
                            '
                            <div class="supplier-profile-inner">

                                <div class="supplier-profile-icon">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.6"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.017a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.622 1.622a3.003 3.003 0 01-.622 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .414.336.75.75.75z"
                                        />
                                    </svg>
                                </div>

                                <div class="supplier-profile-label">
                                    SUPPLIER
                                </div>

                                <div class="supplier-profile-name">
                                    ' . $supplierName . '
                                </div>

                                <div class="supplier-profile-status ' .
                                    ($status === 'active'
                                        ? 'is-active'
                                        : 'is-inactive') .
                                '">
                                    <span></span>
                                    ' . $statusLabel . '
                                </div>

                                <div class="supplier-profile-divider"></div>

                              
                                <div class="supplier-profile-dots"></div>

                            </div>
                            '
                        );
                    })
                    ->html(),

            ])
            ->columnSpan([
                'default' => 1,
                'lg' => 4,
            ])
            ->extraAttributes([
                'class' => 'supplier-profile-card',
            ]),


        /*
        |--------------------------------------------------------------------------
        | CONTACT INFORMATION
        |--------------------------------------------------------------------------
        */

        Section::make('Contact Information')
            ->icon('heroicon-m-user')
            ->schema([

                TextEntry::make('contact_information')
                    ->label('')
                    ->state(function ($record) {

                        $contactPerson = e(
                            $record->contact_person ?? 'Not provided'
                        );

                        $email = e(
                            $record->email ?? 'Not provided'
                        );

                        $phone = e(
                            $record->phone ?? 'Not provided'
                        );

                        $whatsapp = e(
                            $record->whatsapp ?? 'Not provided'
                        );

                        return new HtmlString(
                            '

                            <div class="supplier-contact-grid">

                                <!-- CONTACT PERSON -->

                                <div class="supplier-contact-item">

                                    <div class="supplier-contact-icon contact-person-icon">

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.7"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M15.75 6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"
                                            />

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M4.5 20.25a7.5 7.5 0 0115 0"
                                            />
                                        </svg>

                                    </div>

                                    <div class="supplier-contact-content">

                                        <div class="supplier-contact-label">
                                            CONTACT PERSON
                                        </div>

                                        <div class="supplier-contact-value">
                                            ' . $contactPerson . '
                                        </div>

                                    </div>

                                </div>


                                <!-- EMAIL -->

                                <div class="supplier-contact-item">

                                    <div class="supplier-contact-icon email-icon">

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.7"
                                            stroke="currentColor"
                                        >
                                            <rect
                                                x="3.5"
                                                y="5"
                                                width="17"
                                                height="14"
                                                rx="2"
                                            />

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M4.5 7l7.5 5.5L19.5 7"
                                            />
                                        </svg>

                                    </div>

                                    <div class="supplier-contact-content">

                                        <div class="supplier-contact-label">
                                            EMAIL
                                        </div>

                                        <div class="supplier-contact-value">
                                            ' . $email . '
                                        </div>

                                    </div>

                                </div>


                                <!-- PHONE -->

                                <div class="supplier-contact-item">

                                    <div class="supplier-contact-icon phone-icon">

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.7"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M2.25 6.75a2.25 2.25 0 012.25-2.25h2.25c.414 0 .79.228.986.593l1.5 2.75a1.125 1.125 0 01-.178 1.33L7.75 9.75a13.5 13.5 0 006.5 6.5l.572-1.308a1.125 1.125 0 011.33-.178l2.75 1.5c.365.196.593.572.593.986v2.25a2.25 2.25 0 01-2.25 2.25C9.88 21.75 2.25 14.12 2.25 6.75z"
                                            />
                                        </svg>

                                    </div>

                                    <div class="supplier-contact-content">

                                        <div class="supplier-contact-label">
                                            PHONE
                                        </div>

                                        <div class="supplier-contact-value">
                                            ' . $phone . '
                                        </div>

                                    </div>

                                </div>


                                <!-- WHATSAPP -->

                                <div class="supplier-contact-item">

                                    <div class="supplier-contact-icon whatsapp-icon">

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.7"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M21 11.5a8.25 8.25 0 01-8.25 8.25 8.2 8.2 0 01-3.63-.84L5 20.25l-4 1.5 1.34-3.78A8.2 8.2 0 014.5 11.5a8.25 8.25 0 1116.5 0z"
                                            />

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M8.75 9.25c.25 2 1.5 3.5 3.5 4.5"
                                            />
                                        </svg>

                                    </div>

                                    <div class="supplier-contact-content">

                                        <div class="supplier-contact-label">
                                            WHATSAPP
                                        </div>

                                        <div class="supplier-contact-value">
                                            ' . $whatsapp . '
                                        </div>

                                    </div>

                                </div>

                            </div>

                            '
                        );
                    })
                    ->html(),

            ])
            ->columnSpan([
                'default' => 1,
                'lg' => 8,
            ])
            ->extraAttributes([
                'class' => 'supplier-contact-card',
            ]),

    ])
    ->columnSpanFull()
    ->extraAttributes([
        'class' => 'supplier-top-grid',
    ]),
            /*
            |--------------------------------------------------------------------------
            | BUSINESS INFORMATION
            |--------------------------------------------------------------------------
            */

           Section::make('Business Information')
    ->icon('heroicon-m-building-office')
    ->schema([

        TextEntry::make('business_information')
            ->label('')
            ->state(function ($record) {

                $address = e($record->address ?? 'Not provided');
                $city = e($record->city ?? 'Not provided');
                $country = e($record->country ?? 'Not provided');
                $tax = e($record->tax_ntn_number ?? 'Not provided');

                return new HtmlString('

                    <div class="supplier-info-grid">

                        <!-- BUSINESS ADDRESS -->
                        <div class="supplier-info-item">

                            <div class="supplier-info-icon address-icon">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.7"
                                    stroke="currentColor">
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M19.5 10.5c0 7.142-7.5 10.5-7.5 10.5S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                </svg>
                            </div>

                            <div class="supplier-info-content">
                                <div class="supplier-info-label">
                                    BUSINESS ADDRESS
                                </div>

                                <div class="supplier-info-value">
                                    '.$address.'
                                </div>
                            </div>

                        </div>


                        <!-- CITY -->
                        <div class="supplier-info-item">

                            <div class="supplier-info-icon city-icon">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.7"
                                    stroke="currentColor">
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M3.75 21h16.5M4.5 18h15M5.25 18V7.5L12 3l6.75 4.5V18M9 21v-3h6v3M9 9h.01M12 9h.01M15 9h.01M9 12h.01M12 12h.01M15 12h.01" />
                                </svg>
                            </div>

                            <div class="supplier-info-content">
                                <div class="supplier-info-label">
                                    CITY
                                </div>

                                <div class="supplier-info-value">
                                    '.$city.'
                                </div>
                            </div>

                        </div>


                        <!-- COUNTRY -->
                        <div class="supplier-info-item">

                            <div class="supplier-info-icon country-icon">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.7"
                                    stroke="currentColor">
                                    <circle cx="12" cy="12" r="8.5"/>
                                    <path stroke-linecap="round"
                                        d="M3.5 12h17M12 3.5c2.2 2.4 3.4 5.2 3.4 8.5s-1.2 6.1-3.4 8.5c-2.2-2.4-3.4-5.2-3.4-8.5s1.2-6.1 3.4-8.5z"/>
                                </svg>
                            </div>

                            <div class="supplier-info-content">
                                <div class="supplier-info-label">
                                    COUNTRY
                                </div>

                                <div class="supplier-info-value">
                                    '.$country.'
                                </div>
                            </div>

                        </div>


                        <!-- TAX / NTN -->
                        <div class="supplier-info-item">

                            <div class="supplier-info-icon tax-icon">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.7"
                                    stroke="currentColor">
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M6.75 3.75h7.5L18 7.5v12.75H6.75V3.75z" />
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M14.25 3.75V7.5H18M9 11h6M9 14h6M9 17h3" />
                                </svg>
                            </div>

                            <div class="supplier-info-content">
                                <div class="supplier-info-label">
                                    TAX / NTN NUMBER
                                </div>

                                <div class="supplier-info-value">
                                    '.$tax.'
                                </div>
                            </div>

                        </div>

                    </div>

                ');
            })
            ->html(),

    ])
    ->columnSpanFull()
    ->extraAttributes([
        'class' => 'supplier-business-card',
    ]),
            /*
            |--------------------------------------------------------------------------
            | FINANCIAL OVERVIEW
            |--------------------------------------------------------------------------
            */

           Section::make('Financial Overview')
    ->icon('heroicon-m-chart-bar')
    ->schema([

        TextEntry::make('financial_information')
            ->label('')
            ->state(function ($record) {

                $openingBalance = format_currency(
                    $record->opening_balance ?? 0
                );

                $creditLimit = format_currency(
                    $record->credit_limit ?? 0
                );

                $paymentTerms = e(
                    $record->payment_terms ?? 'Not specified'
                );

                return new HtmlString('

                    <div class="supplier-financial-grid">

                        <!-- OPENING BALANCE -->
                        <div class="supplier-financial-item">

                            <div class="financial-icon financial-icon-green">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.7"
                                    stroke="currentColor">
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M3 7.5h15.75A2.25 2.25 0 0121 9.75v8.25A2.25 2.25 0 0118.75 20H5.25A2.25 2.25 0 013 17.75V7.5z" />
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M3 7.5V6.75A2.25 2.25 0 015.25 4.5h11.5A2.25 2.25 0 0119 6.75V7.5" />
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M16.5 13.5h.01" />
                                </svg>
                            </div>

                            <div class="financial-content">

                                <div class="financial-label">
                                    OPENING BALANCE
                                </div>

                                <div class="financial-value financial-value-green">
                                    '.$openingBalance.'
                                </div>

                                <div class="financial-description">
                                    Current balance
                                </div>

                            </div>

                        </div>


                        <!-- CREDIT LIMIT -->
                        <div class="supplier-financial-item">

                            <div class="financial-icon financial-icon-blue">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.7"
                                    stroke="currentColor">
                                    <rect x="3.5"
                                        y="5"
                                        width="17"
                                        height="14"
                                        rx="2"/>
                                    <path stroke-linecap="round"
                                        d="M3.5 9h17"/>
                                    <path stroke-linecap="round"
                                        d="M7 14h3"/>
                                </svg>
                            </div>

                            <div class="financial-content">

                                <div class="financial-label">
                                    CREDIT LIMIT
                                </div>

                                <div class="financial-value financial-value-blue">
                                    '.$creditLimit.'
                                </div>

                                <div class="financial-description">
                                    Available credit limit
                                </div>

                            </div>

                        </div>


                        <!-- PAYMENT TERMS -->
                        <div class="supplier-financial-item">

                            <div class="financial-icon financial-icon-yellow">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.7"
                                    stroke="currentColor">
                                    <rect x="4"
                                        y="5"
                                        width="16"
                                        height="15"
                                        rx="2"/>
                                    <path stroke-linecap="round"
                                        d="M8 3v4M16 3v4M4 9h16"/>
                                    <path stroke-linecap="round"
                                        d="M9 13h6M9 16h3"/>
                                </svg>
                            </div>

                            <div class="financial-content">

                                <div class="financial-label">
                                    PAYMENT TERMS
                                </div>

                                <div class="financial-value financial-value-yellow">
                                    '.$paymentTerms.'
                                </div>

                                <div class="financial-description">
                                    Net payment terms
                                </div>

                            </div>

                        </div>

                    </div>

                ');
            })
            ->html(),

    ])
    ->columnSpanFull()
    ->extraAttributes([
        'class' => 'supplier-financial-card',
    ]),
            /*
            |--------------------------------------------------------------------------
            | SUPPLIER NOTES
            |--------------------------------------------------------------------------
            */

         Section::make('Supplier Notes')
    ->icon('heroicon-m-document-text')
    ->schema([

        TextEntry::make('supplier_notes')
            ->label('')
            ->state(function ($record) {

                $notes = $record->notes ?? null;

                /*
                |--------------------------------------------------------------------------
                | EDIT PAGE URL
                |--------------------------------------------------------------------------
                */

                $editUrl = SupplierResource::getUrl(
                    'edit',
                    ['record' => $record]
                );

                /*
                |--------------------------------------------------------------------------
                | NO NOTES
                |--------------------------------------------------------------------------
                */

                if (blank($notes)) {

                    return new HtmlString('

                        <div class="supplier-notes-wrapper">

                            <div class="supplier-notes-icon">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.7"
                                    stroke="currentColor">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M6.75 3.75h7.5L18 7.5v12.75H6.75V3.75z"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M14.25 3.75V7.5H18"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        d="M9 11h6M9 14h6M9 17h3"
                                    />

                                </svg>

                            </div>


                            <div class="supplier-notes-content">

                                <div class="supplier-notes-title">
                                    No additional notes have been added.
                                </div>

                                <div class="supplier-notes-description">
                                    Add notes about this supplier, agreements,
                                    preferences, or any other important information.
                                </div>

                            </div>


                            <a
                                href="' . e($editUrl) . '"
                                class="supplier-add-note-button"
                            >

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="2"
                                    stroke="currentColor"
                                >

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M12 5v14M5 12h14"
                                    />

                                </svg>

                                Add Note

                            </a>

                        </div>

                    ');
                }


                /*
                |--------------------------------------------------------------------------
                | NOTES AVAILABLE
                |--------------------------------------------------------------------------
                */

                return new HtmlString('

                    <div class="supplier-notes-wrapper">

                        <div class="supplier-notes-icon">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.7"
                                stroke="currentColor"
                            >

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M6.75 3.75h7.5L18 7.5v12.75H6.75V3.75z"
                                />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M14.25 3.75V7.5H18"
                                />

                                <path
                                    stroke-linecap="round"
                                    d="M9 11h6M9 14h6M9 17h3"
                                />

                            </svg>

                        </div>


                        <div class="supplier-notes-content">

                            <div class="supplier-notes-title">
                                Supplier Note
                            </div>

                            <div class="supplier-notes-description">
                                ' . nl2br(e($notes)) . '
                            </div>

                        </div>


                        <a
                            href="' . e($editUrl) . '"
                            class="supplier-add-note-button"
                        >

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="2"
                                stroke="currentColor"
                            >

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 5v14M5 12h14"
                                />

                            </svg>

                            Edit Note

                        </a>

                    </div>

                ');
            })
            ->html(),

    ])
    ->columnSpanFull()
    ->extraAttributes([
        'class' => 'supplier-notes-card',
    ]),
        ]);
    }
}