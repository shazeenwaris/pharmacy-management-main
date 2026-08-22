<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('purchase_returns', function (Blueprint $table) {
        $table->id();
        $table->foreignId('purchase_id')->constrained()->cascadeOnDelete();
        $table->foreignId('medicine_id')->constrained()->cascadeOnDelete();
        $table->foreignId('medicine_batch_id')->nullable()->constrained('medicine_batches')->nullOnDelete();
        $table->string('batch_number');
        $table->unsignedInteger('quantity');
        $table->decimal('purchase_price', 12, 2);
        $table->decimal('refund_amount', 12, 2)->default(0);
        $table->string('reason');
        $table->date('return_date');
        $table->text('notes')->nullable();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('purchase_returns');
}
};
