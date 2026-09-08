<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Account
            |--------------------------------------------------------------------------
            */

            $table->foreignId('account_id')
                ->constrained('accounts')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Transaction Information
            |--------------------------------------------------------------------------
            */

            $table->date('transaction_date');

            $table->enum('transaction_type', [
                'income',
                'expense',
                'transfer',
                'adjustment',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Reference
            |--------------------------------------------------------------------------
            | Example:
            | Sale
            | Purchase
            | Supplier Payment
            | Expense
            */

            $table->string('reference_type')->nullable();

            $table->unsignedBigInteger('reference_id')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Amount
            |--------------------------------------------------------------------------
            */

            $table->decimal('amount', 14, 2);

            /*
            |--------------------------------------------------------------------------
            | Payment Method
            |--------------------------------------------------------------------------
            */

            $table->enum('payment_method', [
                'cash',
                'bank',
                'card',
                'online',
                'other',
            ])->nullable();

            /*
            |--------------------------------------------------------------------------
            | Description
            |--------------------------------------------------------------------------
            */

            $table->text('description')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Created By
            |--------------------------------------------------------------------------
            */

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index([
                'reference_type',
                'reference_id',
            ]);

            $table->index('transaction_date');
            $table->index('transaction_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_transactions');
    }
};