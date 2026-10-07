<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            if (! Schema::hasColumn('medicines', 'strips_per_box')) {
                $table->unsignedInteger('strips_per_box')->default(1);
            }
            if (! Schema::hasColumn('medicines', 'units_per_strip')) {
                $table->unsignedInteger('units_per_strip')->default(1);
            }
            if (! Schema::hasColumn('medicines', 'purchase_price')) {
                $table->decimal('purchase_price', 12, 2)->default(0);
            }
            if (! Schema::hasColumn('medicines', 'sale_price')) {
                $table->decimal('sale_price', 12, 2)->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            $table->dropColumn(['strips_per_box', 'units_per_strip']);
        });
    }
};