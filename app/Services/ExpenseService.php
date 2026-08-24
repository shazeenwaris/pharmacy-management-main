<?php

namespace App\Services;

use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class ExpenseService
{
    public function __construct(
        protected FinanceService $financeService
    ) {
    }

    /**
     * Create expense and automatically create
     * corresponding financial transaction.
     */
    public function createExpense(array $data): Expense
    {
        return DB::transaction(function () use ($data) {

            /*
             * 1. Create Expense
             */
            $expense = Expense::create($data);

            /*
             * 2. Load Category
             */
            $expense->load('category');

            /*
             * 3. Automatically create Financial Transaction
             */
            $this->financeService->createTransaction([

                'account_id' => $expense->account_id,

                'transaction_date' => $expense->expense_date,

                'transaction_type' => 'expense',

                'reference_type' => Expense::class,

                'reference_id' => $expense->id,

                'amount' => $expense->amount,

                'payment_method' => $expense->payment_method,

                'description' =>
                    ($expense->category?->name ?? 'Expense') .
                    ($expense->description
                        ? ' - ' . $expense->description
                        : ''),

                'created_by' => $expense->created_by,
            ]);

            /*
             * 4. Return Expense
             */
            return $expense;
        });
    }
}