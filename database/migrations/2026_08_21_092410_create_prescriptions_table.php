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
    Schema::create('prescriptions', function (Blueprint $table) {
        $table->id();
        $table->string('prescription_number')->unique();
        $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
        $table->string('patient_name');
        $table->string('doctor_name')->nullable();
        $table->date('prescription_date');
        $table->string('image_path')->nullable();
        $table->text('notes')->nullable();
        $table->string('status')->default('active'); // active, dispensed, expired
        $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('prescriptions');
}
};
