<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('expenses', function (Blueprint $table) {

            // Add account_name only if it does not already exist
            if (! Schema::hasColumn('expenses', 'account_name')) {
                $table->string('account_name')->after('expense_category_id');
            }

            // Add expense_category_id only if it does not already exist
            if (! Schema::hasColumn('expenses', 'expense_category_id')) {
                $table->foreignId('expense_category_id')
                    ->after('expense_date')
                    ->constrained('expense_categories')
                    ->cascadeOnDelete();
            }

            // Remove old account_id only if it still exists
            if (Schema::hasColumn('expenses', 'account_id')) {
                $table->dropForeign(['account_id']);
                $table->dropColumn('account_id');
            }

            // Remove old category only if it still exists
            if (Schema::hasColumn('expenses', 'category')) {
                $table->dropColumn('category');
            }
        });
    }

    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {

            if (Schema::hasColumn('expenses', 'expense_category_id')) {
                $table->dropForeign(['expense_category_id']);
                $table->dropColumn('expense_category_id');
            }

            if (Schema::hasColumn('expenses', 'account_name')) {
                $table->dropColumn('account_name');
            }

            if (! Schema::hasColumn('expenses', 'category')) {
                $table->string('category')->after('expense_date');
            }

            if (! Schema::hasColumn('expenses', 'account_id')) {
                $table->foreignId('account_id')
                    ->constrained('accounts')
                    ->cascadeOnDelete();
            }
        });
    }
};