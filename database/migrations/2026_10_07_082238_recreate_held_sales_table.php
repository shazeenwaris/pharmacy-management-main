<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('held_sales');

        Schema::create('held_sales', function (Blueprint $table) {
            $table->id();

            $table->string('hold_number')->unique();

            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->unsignedBigInteger('customer_id')->nullable();

            $table->json('cart');

            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);
            $table->decimal('paid_amount', 12, 2)->default(0);

            $table->string('payment_method')->default('cash');
            $table->string('status')->default('held')->index();
            $table->text('note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('held_sales');
    }
};