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
    Schema::create('medicine_batches', function (Blueprint $table) {
        $table->id();
        $table->foreignId('medicine_id')->constrained()->cascadeOnDelete();
        $table->string('batch_number');
        $table->date('manufacturing_date')->nullable();
        $table->date('expiry_date');
        $table->decimal('purchase_price', 10, 2)->nullable();
        $table->decimal('sale_price', 10, 2)->nullable();
        $table->integer('quantity')->default(0);


        // Supplier + purchase reference (purchases module abhi nahi bana, isliye simple text/nullable rakha hai)
        $table->string('supplier_name')->nullable();
        $table->string('purchase_reference')->nullable();


        $table->boolean('status')->default(true);
        $table->text('notes')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_batches');
    }
};


