<?php

namespace App\Filament\Resources\Accounts\Pages;

use App\Filament\Resources\Accounts\AccountResource;
use App\Models\Account;
use Filament\Resources\Pages\ListRecords;

class ListAccounts extends ListRecords
{
    protected static string $resource = AccountResource::class;

    protected string $view = 'filament.resources.account-resource.pages.list-accounts';

    protected function getHeaderActions(): array
    {
        return [
            // Create button banner ke andar hai
        ];
    }

    protected function getViewData(): array
    {
        return [
            'totalAccounts'    => Account::count(),
            'activeAccounts'   => Account::where('status', 'active')->count(),
            'inactiveAccounts' => Account::where('status', 'inactive')->count(),
            'totalBalance'     => Account::sum('current_balance'),
        ];
    }
}