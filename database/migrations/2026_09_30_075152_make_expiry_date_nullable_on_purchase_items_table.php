<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchase_items', function (Blueprint $table) {
            $table->date('expiry_date')->nullable()->change();
            $table->date('manufacturing_date')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('purchase_items', function (Blueprint $table) {
            $table->date('expiry_date')->nullable(false)->change();
            $table->date('manufacturing_date')->nullable(false)->change();
        });
    }
};