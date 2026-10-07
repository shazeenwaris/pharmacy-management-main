<?php

namespace App\Filament\Pharmacist\Support;

use App\Models\Customer;
use App\Models\Medicine;
use App\Models\Sale;
use Filament\GlobalSearch\GlobalSearchResult;
use Filament\GlobalSearch\GlobalSearchResults;
use Filament\GlobalSearch\Providers\Contracts\GlobalSearchProvider;

class PharmacistGlobalSearch implements GlobalSearchProvider
{
    public function getResults(string $query): ?GlobalSearchResults
    {
        $query = trim($query);

        if ($query === '') {
            return null;
        }

        $results = GlobalSearchResults::make();

        // Invoices
        $sales = Sale::query()
            ->where('invoice_number', 'like', "%{$query}%")
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn ($s) => new GlobalSearchResult(
                title: $s->invoice_number,
                url: url('/pharmacist/sales-history') . '?tableSearch=' . urlencode($s->invoice_number),
                details: array_filter([
                    'Date'  => $s->created_at?->format('d M Y'),
                    'Total' => function_exists('format_currency') ? format_currency($s->grand_total) : $s->grand_total,
                ]),
            ));

        if ($sales->isNotEmpty()) {
            $results->category('Invoices', $sales);
        }

        // Medicines
        $medicines = Medicine::query()
            ->where('name', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(fn ($m) => new GlobalSearchResult(
                title: $m->name,
                url: url('/pharmacist/search-medicines') . '?search=' . urlencode($m->name),
            ));

        if ($medicines->isNotEmpty()) {
            $results->category('Medicines', $medicines);
        }

        // Customers
        $customers = Customer::query()
            ->where(fn ($q) => $q
                ->where('name', 'like', "%{$query}%")
                ->orWhere('phone', 'like', "%{$query}%"))
            ->limit(5)
            ->get()
            ->map(fn ($c) => new GlobalSearchResult(
                title: $c->name,
                url: \App\Filament\Pharmacist\Resources\Customers\CustomerResource::getUrl('view', ['record' => $c]),
                details: array_filter(['Phone' => $c->phone]),
            ));

        if ($customers->isNotEmpty()) {
            $results->category('Customers', $customers);
        }

        return $results;
    }
}