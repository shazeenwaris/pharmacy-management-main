<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();

            // Account
            $table->string('account_name');

            $table->enum('account_type', [
                'cash',
                'bank',
                'mobile_wallet',
                'other',
            ])->default('cash');

            $table->string('account_number')->nullable();

            // Bank Information
            $table->string('account_title')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('iban')->nullable();
            $table->string('branch')->nullable();

            // Financial
            $table->decimal('opening_balance', 14, 2)->default(0);
            $table->decimal('current_balance', 14, 2)->default(0);

            // Status
            $table->enum('status', [
                'active',
                'inactive',
            ])->default('active');

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};