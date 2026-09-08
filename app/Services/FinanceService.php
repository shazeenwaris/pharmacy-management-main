<?php

namespace App\Services;

use App\Models\Account;
use App\Models\FinancialTransaction;
use Illuminate\Support\Facades\DB;

class FinanceService
{
    /**
     * Create a financial transaction and update the
     * related account's current balance accordingly.
     */
    public function createTransaction(array $data): FinancialTransaction
    {
        return DB::transaction(function () use ($data) {

            $transaction = FinancialTransaction::create($data);

            $account = Account::find($data['account_id']);

            if ($account) {
                match ($data['transaction_type']) {
                    'income' => $account->increment('current_balance', $data['amount']),
                    'expense' => $account->decrement('current_balance', $data['amount']),
                    'transfer', 'adjustment' => null, // handled separately if needed
                    default => null,
                };
            }

            return $transaction;
        });
    }
}