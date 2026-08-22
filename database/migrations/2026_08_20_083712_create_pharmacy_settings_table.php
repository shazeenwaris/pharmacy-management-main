<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pharmacy_settings', function (Blueprint $table) {
            $table->id();

            // Pharmacy Info
            $table->string('pharmacy_name')->nullable();
            $table->string('logo')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            // Invoice
            $table->string('invoice_prefix')->default('INV-');
            $table->unsignedInteger('invoice_start_number')->default(1);
            $table->text('invoice_footer_note')->nullable();

            // Tax & Currency
            $table->decimal('default_tax_percentage', 5, 2)->default(0);
            $table->string('currency_code')->default('PKR');
            $table->string('currency_symbol')->default('Rs.');

            // Stock / Expiry thresholds
            $table->unsignedInteger('low_stock_threshold')->default(10);
            $table->unsignedInteger('expiry_warning_days')->default(30);

            // Payment methods (stored as JSON array e.g. ["cash","card","bank_transfer","other"])
            $table->json('payment_methods')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pharmacy_settings');
    }
};