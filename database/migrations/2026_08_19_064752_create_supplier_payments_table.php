<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplier_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();

            $table->date('payment_date');
            $table->decimal('amount', 14, 2);
            $table->string('payment_method')->nullable(); // Cash, Bank Transfer, Cheque...
            $table->string('reference_number')->nullable();
            $table->string('account')->nullable(); // which cash/bank account this was paid from
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_payments');
    }
};