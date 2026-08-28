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
    Schema::create('app_notifications', function (Blueprint $table) {
        $table->id();
        $table->string('type'); // low_stock, expiry, out_of_stock, payment_due
        $table->string('title');
        $table->text('message');
        $table->string('level')->default('warning'); // info, warning, danger
        $table->string('link')->nullable(); // e.g. /admin/medicine-batches/5/edit
        $table->timestamp('read_at')->nullable();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('app_notifications');
}
};
